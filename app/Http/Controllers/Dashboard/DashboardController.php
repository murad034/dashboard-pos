<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\EjItem;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getData(Request $request): JsonResponse
    {
        try {
            if ($request->has('id') && $request->has('dateFrom') && $request->has('dateTo')) {
                $locationId = $request->get('id');
                $dateFrom = $request->get('dateFrom');
                $dateTo = $request->get('dateTo');

                $tableName = EjItem::tableName();

                //            get Total Sales, ATV, Total Transactions value
                $ops = array(
                    array(
                        '$addFields' => array(
                            'saleDate' => array(
                                '$dateToString' => array(
                                    'format' => '%Y-%m-%d',
                                    'date' => '$datetime'
                                )
                            )
                        )
                    ),
                    array(
                        '$group' => array(
                            '_id' => null,
                            'salesTotal' => array(
                                '$sum' => array(
                                    '$toDouble' => '$provalue'
                                )
                            ),
                            'allCustomer' => array(
                                '$addToSet' => '$recnum'
                            )
                        )
                    ),
                    array(
                        '$project' => array(
                            '_id' => 1,
                            'salesTotal' => 1,
                            'salesCount' => array(
                                '$size' => array(
                                    '$setUnion' => '$allCustomer'
                                )
                            )
                        )
                    ),
                    array(
                        '$addFields' => array(
                            'ATV' => array(
                                '$divide' => array(
                                    array(
                                        '$toDouble' => '$salesTotal'
                                    ),
                                    '$salesCount'
                                )
                            )
                        )
                    )
                );
                if ($locationId == 0 || $locationId == "0") {
                    $between = array(array(
                        '$match' => array(
                            'saleDate' => array(
                                '$gte' => $dateFrom,
                                '$lte' => $dateTo
                            )
                        )
                    ));
                    array_splice($ops, 1, 0, $between);
                } else {
                    $between = array(array(
                        '$match' => array(
                            'saleDate' => array(
                                '$gte' => $dateFrom,
                                '$lte' => $dateTo
                            ),
                            'locationid' => $locationId
                        )
                    ));
                    array_splice($ops, 1, 0, $between);
                }

                $result["totalsale"] = [];
                $result["totaltrans"] = [];
                $result["atv"] = [];
                $data = EjItem::raw()->aggregate($ops)->toArray();
                foreach ($data as $val) {
                    array_push($result["totalsale"], $val->salesTotal);
                    array_push($result["totaltrans"], $val->salesCount);
                    array_push($result["atv"], $val->ATV);
                }
//            get Monthly Sales chart values
                $dateFromYear = date('Y-01-01', strtotime($dateTo));
                $dateToYear = date('Y-12-31', strtotime($dateTo));
                $dateOldFrom = date("Y-m-d", strtotime("-1 year", strtotime(date($dateFromYear))));
                $dateOldTo = date("Y-m-d", strtotime("-1 year", strtotime(date($dateToYear))));
                $start = $month = strtotime(date('Y-m-01', strtotime($dateFromYear)));
                $end = strtotime(date($dateToYear));

                $dateList = [];
                $dateOldList = [];
                $dateLabel = [];

                do {
                    $old_month = strtotime("-1 year", $month);

                    array_push($dateList, date("Y-m", $month));
                    array_push($dateOldList, date("Y-m", $old_month));
                    array_push($dateLabel, date("F", $month));
                    $month = strtotime("+1 month", $month);
                } while ($month <= $end);
//             get current year monthly values
                $ops = array(
                    array(
                        '$addFields' => array(
                            'saleDate' => array(
                                '$dateToString' => array(
                                    'format' => '%Y-%m-%d',
                                    'date' => '$datetime'
                                )
                            ),
                            'groupDate' => array(
                                '$dateToString' => array(
                                    'format' => '%Y-%m',
                                    'date' => '$datetime'
                                )
                            ),
                        )
                    ),
                    array(
                        '$group' => array(
                            '_id' => '$groupDate',
                            'salesTotal' => array(
                                '$sum' => array(
                                    '$toDouble' => '$provalue'
                                )
                            ),
                            'allCustomer' => array(
                                '$addToSet' => '$recnum'
                            )
                        )
                    ),
                    array(
                        '$project' => array(
                            '_id' => 1,
                            'salesTotal' => 1,
                            'salesCount' => array(
                                '$size' => array(
                                    '$setUnion' => '$allCustomer'
                                )
                            )
                        )
                    ),
                    array(
                        '$addFields' => array(
                            'ATV' => array(
                                '$divide' => array(
                                    array(
                                        '$toDouble' => '$salesTotal'
                                    ),
                                    '$salesCount'
                                )
                            )
                        )
                    ),
                    array(
                        '$sort' => array(
                            '_id' => 1
                        )
                    )

                );
                if ($locationId == 0 || $locationId == "0") {
                    $between = array(array(
                        '$match' => array(
                            'saleDate' => array(
                                '$gte' => $dateFromYear,
                                '$lte' => $dateToYear
                            )
                        )
                    ));
                    array_splice($ops, 1, 0, $between);
                } else {
                    $between = array(array(
                        '$match' => array(
                            'saleDate' => array(
                                '$gte' => $dateFromYear,
                                '$lte' => $dateToYear
                            ),
                            'locationid' => $locationId
                        )
                    ));
                    array_splice($ops, 1, 0, $between);
                }

                $result["monthlySales"]["label"] = $dateLabel;
                $result["monthlySales"]["c_totalsale"] = [];
                $result["monthlySales"]["c_totaltrans"] = [];
                $result["monthlySales"]["c_atv"] = [];
                $result["monthlySales"]["o_totalsale"] = [];
                $result["monthlySales"]["o_totaltrans"] = [];
                $result["monthlySales"]["o_atv"] = [];

                $data = DB::connection('mongodb')->collection($tableName)->raw(function ($collection) use ($ops) {
                    return $collection->aggregate($ops);
                })->toArray();
                foreach ($dateList as $date_val) {
                    if (count($data) === 0) {
                        array_push($result["monthlySales"]["c_totalsale"], 0);
                        array_push($result["monthlySales"]["c_totaltrans"], 0);
                        array_push($result["monthlySales"]["c_atv"], 0);
                    } else {
                        $status_data = false;
                        foreach ($data as $val) {
                            if (strcmp($val->_id, $date_val) === 0) {
                                array_push($result["monthlySales"]["c_totalsale"], $val->salesTotal);
                                array_push($result["monthlySales"]["c_totaltrans"], $val->salesCount);
                                array_push($result["monthlySales"]["c_atv"], $val->ATV);
                                $status_data = true;
                            }
                        }
                        if (!$status_data) {
                            array_push($result["monthlySales"]["c_totalsale"], 0);
                            array_push($result["monthlySales"]["c_totaltrans"], 0);
                            array_push($result["monthlySales"]["c_atv"], 0);
                        }

                    }

                }
//             get previous year monthly values
                $ops = array(
                    array(
                        '$addFields' => array(
                            'saleDate' => array(
                                '$dateToString' => array(
                                    'format' => '%Y-%m-%d',
                                    'date' => '$datetime'
                                )
                            ),
                            'groupDate' => array(
                                '$dateToString' => array(
                                    'format' => '%Y-%m',
                                    'date' => '$datetime'
                                )
                            ),
                        )
                    ),
                    array(
                        '$group' => array(
                            '_id' => '$groupDate',
                            'salesTotal' => array(
                                '$sum' => array(
                                    '$toDouble' => '$provalue'
                                )
                            ),
                            'allCustomer' => array(
                                '$addToSet' => '$recnum'
                            )
                        )
                    ),
                    array(
                        '$project' => array(
                            '_id' => 1,
                            'salesTotal' => 1,
                            'salesCount' => array(
                                '$size' => array(
                                    '$setUnion' => '$allCustomer'
                                )
                            )
                        )
                    ),
                    array(
                        '$addFields' => array(
                            'ATV' => array(
                                '$divide' => array(
                                    array(
                                        '$toDouble' => '$salesTotal'
                                    ),
                                    '$salesCount'
                                )
                            )
                        )
                    ),
                    array(
                        '$sort' => array(
                            '_id' => 1
                        )
                    )
                );
                if ($locationId == 0 || $locationId == "0") {
                    $between = array(array(
                        '$match' => array(
                            'saleDate' => array(
                                '$gte' => $dateOldFrom,
                                '$lte' => $dateOldTo
                            )
                        )
                    ));
                    array_splice($ops, 1, 0, $between);
                } else {
                    $between = array(array(
                        '$match' => array(
                            'saleDate' => array(
                                '$gte' => $dateOldFrom,
                                '$lte' => $dateOldTo
                            ),
                            'locationid' => $locationId
                        )
                    ));
                    array_splice($ops, 1, 0, $between);
                }

                $data = DB::connection('mongodb')->collection($tableName)->raw(function ($collection) use ($ops) {
                    return $collection->aggregate($ops);
                })->toArray();
                foreach ($dateOldList as $date_val) {
                    if (count($data) == 0) {
                        array_push($result["monthlySales"]["o_totalsale"], 0);
                        array_push($result["monthlySales"]["o_totaltrans"], 0);
                        array_push($result["monthlySales"]["o_atv"], 0);
                    } else {
                        $status_data = false;
                        foreach ($data as $val) {
                            if (strcmp($val->_id, $date_val) === 0) {
                                array_push($result["monthlySales"]["o_totalsale"], $val->salesTotal);
                                array_push($result["monthlySales"]["o_totaltrans"], $val->salesCount);
                                array_push($result["monthlySales"]["o_atv"], $val->ATV);
                                $status_data = true;
                            }
                        }
                        if (!$status_data) {
                            array_push($result["monthlySales"]["o_totalsale"], 0);
                            array_push($result["monthlySales"]["o_totaltrans"], 0);
                            array_push($result["monthlySales"]["o_atv"], 0);
                        }
                    }

                }
                //            get Weather chart values
                $ops = array(
                    array(
                        '$addFields' => array(
                            'saleDate' => array(
                                '$dateToString' => array(
                                    'format' => '%Y-%m-%d',
                                    'date' => '$datetime'
                                )
                            )
                        )
                    ),
//                array(
//                    '$group' => array(
//                        '_id' => '$recnum',
//                        'saleDate' => array(
//                            '$first' => '$saleDate'
//                        ),
//                        'weather' => array(
//                            '$first' => '$weather'
//                        ),
//                        'provalue' => array(
//                            '$sum' => array(
//                                '$toDouble' => '$provalue'
//                            )
//                        ),
//                        'temp' => array(
//                            '$first' => '$temp'
//                        )
//                    )
//                ),
                    array(
                        '$group' => array(
                            '_id' => '$saleDate',
                            'Cloudy' => array(
                                '$sum' => array(
                                    '$cond' => array(
                                        array(
                                            '$eq' => array(
                                                '$weather', 'cloudy'
                                            )
                                        ),
                                        1,
                                        0
                                    )
                                )
                            ),
                            'ClearDay' => array(
                                '$sum' => array(
                                    '$cond' => array(
                                        array(
                                            '$eq' => array(
                                                '$weather', 'clear-day'
                                            )
                                        ),
                                        1,
                                        0
                                    )
                                )
                            ),
                            'ClearNight' => array(
                                '$sum' => array(
                                    '$cond' => array(
                                        array(
                                            '$eq' => array(
                                                '$weather', 'clear-night'
                                            )
                                        ),
                                        1,
                                        0
                                    )
                                )
                            ),
                            'Rain' => array(
                                '$sum' => array(
                                    '$cond' => array(
                                        array(
                                            '$eq' => array(
                                                '$weather', 'rain'
                                            )
                                        ),
                                        1,
                                        0
                                    )
                                )
                            ),
                            'Snow' => array(
                                '$sum' => array(
                                    '$cond' => array(
                                        array(
                                            '$eq' => array(
                                                '$weather', 'snow'
                                            )
                                        ),
                                        1,
                                        0
                                    )
                                )
                            ),
                            'Sleet' => array(
                                '$sum' => array(
                                    '$cond' => array(
                                        array(
                                            '$eq' => array(
                                                '$weather', 'sleet'
                                            )
                                        ),
                                        1,
                                        0
                                    )
                                )
                            ),
                            'Wind' => array(
                                '$sum' => array(
                                    '$cond' => array(
                                        array(
                                            '$eq' => array(
                                                '$weather', 'wind'
                                            )
                                        ),
                                        1,
                                        0
                                    )
                                )
                            ),
                            'Fog' => array(
                                '$sum' => array(
                                    '$cond' => array(
                                        array(
                                            '$eq' => array(
                                                '$weather', 'fog'
                                            )
                                        ),
                                        1,
                                        0
                                    )
                                )
                            ),
                            'PartyCloudyDay' => array(
                                '$sum' => array(
                                    '$cond' => array(
                                        array(
                                            '$eq' => array(
                                                '$weather', 'partly-cloudy-day'
                                            )
                                        ),
                                        1,
                                        0
                                    )
                                )
                            ),
                            'PartyCloudyNight' => array(
                                '$sum' => array(
                                    '$cond' => array(
                                        array(
                                            '$eq' => array(
                                                '$weather', 'partly-cloudy-night'
                                            )
                                        ),
                                        1,
                                        0
                                    )
                                )
                            ),
                            'Sales' => array(
                                '$sum' => array(
                                    '$toDouble' => '$provalue'
                                )
                            ),
                            'Min_Temp' => array(
                                '$min' => array(
                                    '$toDouble' => '$temp'
                                )
                            ),
                            'Max_Temp' => array(
                                '$max' => array(
                                    '$toDouble' => '$temp'
                                )
                            ),
                            'Avg_Temp' => array(
                                '$avg' => array(
                                    '$toDouble' => '$temp'
                                )
                            ),
                        )
                    ),
                    array(
                        '$project' => array(
                            '_id' => 1,
                            'Min_Temp' => 1,
                            'Max_Temp' => 1,
                            'Avg_Temp' => 1,
                            'Cloudy' => 1,
                            'ClearDay' => 1,
                            'ClearNight' => 1,
                            'Rain' => 1,
                            'Snow' => 1,
                            'Sleet' => 1,
                            'Wind' => 1,
                            'Fog' => 1,
                            'PartyCloudyDay' => 1,
                            'PartyCloudyNight' => 1,
                            'Sales' => 1,
                            'maxVal' => array(
                                '$max' => array(
                                    '$Cloudy',
                                    '$ClearDay',
                                    '$ClearNight',
                                    '$Rain',
                                    '$Snow',
                                    '$Sleet',
                                    '$Wind',
                                    '$Fog',
                                    '$PartyCloudyDay',
                                    '$PartyCloudyNight',
                                )
                            )
                        )
                    ),
                    array(
                        '$addFields' => array(
                            'Weather' => array(
                                '$switch' => array(
                                    'branches' => array(
                                        array(
                                            'case' => array(
                                                '$eq' => array(
                                                    '$maxVal',
                                                    '$Cloudy'
                                                )
                                            ),
                                            'then' => 'cloudy'
                                        ),
                                        array(
                                            'case' => array(
                                                '$eq' => array(
                                                    '$maxVal',
                                                    '$ClearDay'
                                                )
                                            ),
                                            'then' => 'clear-day'
                                        ),
                                        array(
                                            'case' => array(
                                                '$eq' => array(
                                                    '$maxVal',
                                                    '$ClearNight'
                                                )
                                            ),
                                            'then' => 'clear-night'
                                        ),
                                        array(
                                            'case' => array(
                                                '$eq' => array(
                                                    '$maxVal',
                                                    '$Rain'
                                                )
                                            ),
                                            'then' => 'rain'
                                        ),
                                        array(
                                            'case' => array(
                                                '$eq' => array(
                                                    '$maxVal',
                                                    '$Snow'
                                                )
                                            ),
                                            'then' => 'snow'
                                        ),
                                        array(
                                            'case' => array(
                                                '$eq' => array(
                                                    '$maxVal',
                                                    '$Sleet'
                                                )
                                            ),
                                            'then' => 'sleet'
                                        ),
                                        array(
                                            'case' => array(
                                                '$eq' => array(
                                                    '$maxVal',
                                                    '$Wind'
                                                )
                                            ),
                                            'then' => 'wind'
                                        ),
                                        array(
                                            'case' => array(
                                                '$eq' => array(
                                                    '$maxVal',
                                                    '$Fog'
                                                )
                                            ),
                                            'then' => 'fog'
                                        ),
                                        array(
                                            'case' => array(
                                                '$eq' => array(
                                                    '$maxVal',
                                                    '$PartyCloudyDay'
                                                )
                                            ),
                                            'then' => 'partly-cloudy-day'
                                        ),
                                        array(
                                            'case' => array(
                                                '$eq' => array(
                                                    '$maxVal',
                                                    '$PartyCloudyNight'
                                                )
                                            ),
                                            'then' => 'partly-cloudy-Night'
                                        ),
                                    ),
                                    'default' => 'Unknown'
                                )
                            )
                        )
                    ),
                    array(
                        '$sort' => array(
                            '_id' => 1
                        )
                    )
                );
                if ($locationId == 0 || $locationId == "0") {
                    $between = array(array(
                        '$match' => array(
                            'saleDate' => array(
                                '$gte' => $dateFrom,
                                '$lte' => $dateTo
                            )
                        )
                    ));
                    array_splice($ops, 1, 0, $between);
                } else {
                    $between = array(array(
                        '$match' => array(
                            'saleDate' => array(
                                '$gte' => $dateFrom,
                                '$lte' => $dateTo
                            ),
                            'locationid' => strval($locationId)
                        )
                    ));
                    array_splice($ops, 1, 0, $between);
                }
                $result["salesWeather"]["label"] = [];
                $result["salesWeather"]["data"] = [];
                $result["salesWeather"]["date"] = [];
                $result["salesWeather"]["maxTemp"] = [];
                $result["salesWeather"]["minTemp"] = [];
                $result["salesWeather"]["avgTemp"] = [];

                $data = DB::connection('mongodb')->collection($tableName)->raw(function ($collection) use ($ops) {
                    return $collection->aggregate($ops);
                })->toArray();
                foreach ($data as $val) {
                    array_push($result["salesWeather"]["label"], $val->Weather);
                    array_push($result["salesWeather"]["maxTemp"], $val->Max_Temp);
                    array_push($result["salesWeather"]["minTemp"], $val->Min_Temp);
                    array_push($result["salesWeather"]["avgTemp"], $val->Avg_Temp);
                    array_push($result["salesWeather"]["data"], $val->Sales);
                    array_push($result["salesWeather"]["date"], $val->_id);
                }
                // get Top 5 Items by Sales
                $ops = array(
                    array(
                        '$addFields' => array(
                            'saleDate' => array(
                                '$dateToString' => array(
                                    'format' => '%Y-%m-%d',
                                    'date' => '$datetime'
                                )
                            )
                        )
                    ),
                    array(
                        '$lookup' => array(
                            "from" => "products",
                            "localField" => "sku",
                            "foreignField" => "sku",
                            "as" => "products"
                        )
                    ),
                    array(
                        '$unwind' => '$products'
                    ),
                    array(
                        '$group' => array(
                            '_id' => '$sku',
                            'salesTotal' => array(
                                '$sum' => array(
                                    '$toDouble' => '$provalue'
                                )
                            ),
                            'SKU' => array(
                                '$first' => '$sku'
                            ),
                            'name' => array(
                                '$first' => '$products.productname'
                            )
                        )
                    ),
                    array(
                        '$sort' => array(
                            'salesTotal' => -1
                        )
                    ),
                    array(
                        '$limit' => 5
                    )
                );
                if ($locationId == 0 || $locationId == "0") {
                    $between = array(array(
                        '$match' => array(
                            'saleDate' => array(
                                '$gte' => $dateFrom,
                                '$lte' => $dateTo
                            )
                        )
                    ));
                    array_splice($ops, 3, 0, $between);
                } else {
                    $between = array(array(
                        '$match' => array(
                            'saleDate' => array(
                                '$gte' => $dateFrom,
                                '$lte' => $dateTo
                            ),
                            'locationid' => $locationId
                        )
                    ));
                    array_splice($ops, 3, 0, $between);
                }
                $result["itemsales"]["val"] = [];
                $result["itemsales"]["name"] = [];
                $data = DB::connection('mongodb')->collection($tableName)->raw(function ($collection) use ($ops) {
                    return $collection->aggregate($ops);
                })->toArray();
                foreach ($data as $val) {
                    array_push($result["itemsales"]["val"], $val->salesTotal);
                    array_push($result["itemsales"]["name"], $val->name);
                }
                // get Top 5 Items by Cost
                $ops = array(
                    array(
                        '$addFields' => array(
                            'saleDate' => array(
                                '$dateToString' => array(
                                    'format' => '%Y-%m-%d',
                                    'date' => '$datetime'
                                )
                            )
                        )
                    ),
                    array(
                        '$lookup' => array(
                            "from" => "products",
                            "localField" => "sku",
                            "foreignField" => "sku",
                            "as" => "products"
                        )
                    ),
                    array(
                        '$unwind' => '$products'
                    ),
                    array(
                        '$group' => array(
                            '_id' => '$sku',
                            'salesTotal' => array(
                                '$sum' => array(
                                    '$toDouble' => '$procost'
                                )
                            ),
                            'SKU' => array(
                                '$first' => '$sku'
                            ),
                            'name' => array(
                                '$first' => '$products.productname'
                            )
                        )
                    ),
                    array(
                        '$sort' => array(
                            'salesTotal' => -1
                        )
                    ),
                    array(
                        '$limit' => 5
                    )
                );
                if ($locationId == 0 || $locationId == "0") {
                    $between = array(array(
                        '$match' => array(
                            'saleDate' => array(
                                '$gte' => $dateFrom,
                                '$lte' => $dateTo
                            )
                        )
                    ));
                    array_splice($ops, 3, 0, $between);
                } else {
                    $between = array(array(
                        '$match' => array(
                            'saleDate' => array(
                                '$gte' => $dateFrom,
                                '$lte' => $dateTo
                            ),
                            'locationid' => $locationId
                        )
                    ));
                    array_splice($ops, 3, 0, $between);
                }
                $result["itemcost"]["val"] = [];
                $result["itemcost"]["name"] = [];
                $data = DB::connection('mongodb')->collection($tableName)->raw(function ($collection) use ($ops) {
                    return $collection->aggregate($ops);
                })->toArray();
                foreach ($data as $val) {
                    array_push($result["itemcost"]["val"], $val->salesTotal);
                    array_push($result["itemcost"]["name"], $val->name);
                }
                // get Top Staff by Sales
                $ops = array(
                    array(
                        '$addFields' => array(
                            'saleDate' => array(
                                '$dateToString' => array(
                                    'format' => '%Y-%m-%d',
                                    'date' => '$datetime'
                                )
                            )
                        )
                    ),
                    array(
                        '$lookup' => array(
                            "from" => "staff",
                            "localField" => "staffid",
                            "foreignField" => "staffid",
                            "as" => "staff"
                        )
                    ),
                    array(
                        '$unwind' => '$staff'
                    ),
                    array(
                        '$group' => array(
                            '_id' => '$staffid',
                            'salesTotal' => array(
                                '$sum' => array(
                                    '$toDouble' => '$provalue'
                                )
                            ),
                            'SKU' => array(
                                '$first' => '$staffid'
                            ),
                            'name' => array(
                                '$first' => '$staff.staffname'
                            )
                        )
                    ),
                    array(
                        '$sort' => array(
                            'salesTotal' => -1
                        )
                    ),
                    array(
                        '$limit' => 5
                    )
                );
                if ($locationId == 0 || $locationId == "0") {
                    $between = array(array(
                        '$match' => array(
                            'saleDate' => array(
                                '$gte' => $dateFrom,
                                '$lte' => $dateTo
                            )
                        )
                    ));
                    array_splice($ops, 3, 0, $between);
                } else {
                    $between = array(array(
                        '$match' => array(
                            'saleDate' => array(
                                '$gte' => $dateFrom,
                                '$lte' => $dateTo
                            ),
                            'locationid' => $locationId
                        )
                    ));
                    array_splice($ops, 3, 0, $between);
                }
                $result["staffsales"]["val"] = [];
                $result["staffsales"]["name"] = [];
                $data = DB::connection('mongodb')->collection($tableName)->raw(function ($collection) use ($ops) {
                    return $collection->aggregate($ops);
                })->toArray();
                foreach ($data as $val) {
                    array_push($result["staffsales"]["val"], $val->salesTotal);
                    array_push($result["staffsales"]["name"], $val->name);
                }
                // get Top Customer By Sales
                $ops = array(
                    array(
                        '$addFields' => array(
                            'saleDate' => array(
                                '$dateToString' => array(
                                    'format' => '%Y-%m-%d',
                                    'date' => '$datetime'
                                )
                            )
                        )
                    ),
                    array(
                        '$lookup' => array(
                            "from" => "customers",
                            "localField" => "customerid",
                            "foreignField" => "customerid",
                            "as" => "customers"
                        )
                    ),
                    array(
                        '$unwind' => '$customers'
                    ),
                    array(
                        '$group' => array(
                            '_id' => '$customerid',
                            'salesTotal' => array(
                                '$sum' => array(
                                    '$toDouble' => '$provalue'
                                )
                            ),
                            'SKU' => array(
                                '$first' => '$customerid'
                            ),
                            'name' => array(
                                '$first' => '$customers.customername'
                            )
                        )
                    ),
                    array(
                        '$sort' => array(
                            'salesTotal' => -1
                        )
                    ),
                    array(
                        '$limit' => 5
                    )
                );
                if ($locationId == 0 || $locationId == "0") {
                    $between = array(array(
                        '$match' => array(
                            'saleDate' => array(
                                '$gte' => $dateFrom,
                                '$lte' => $dateTo
                            )
                        )
                    ));
                    array_splice($ops, 3, 0, $between);
                } else {
                    $between = array(array(
                        '$match' => array(
                            'saleDate' => array(
                                '$gte' => $dateFrom,
                                '$lte' => $dateTo
                            ),
                            'locationid' => $locationId
                        )
                    ));
                    array_splice($ops, 3, 0, $between);
                }
                $result["customersales"]["val"] = [];
                $result["customersales"]["name"] = [];

                $data = DB::connection('mongodb')->collection($tableName)->raw(function ($collection) use ($ops) {
                    return $collection->aggregate($ops);
                })->toArray();
                foreach ($data as $val) {
                    array_push($result["customersales"]["val"], $val->salesTotal);
                    array_push($result["customersales"]["name"], $val->name);
                }
                return response()->json([
                    'status' => 'success',
                    'data' => $result
                ]);

            } else {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Request must have the parameters'
                ]);
            }


        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }

    }


}
