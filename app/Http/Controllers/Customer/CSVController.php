<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CSVController extends Controller
{


    public function uploadCSV(Request $request): JsonResponse
    {
        try {
            $data = Customer::all()->toArray();
            if (count($data) > 0) {
                $h_arrays = $data[0];
                $headers = array_keys($h_arrays);
                array_shift($headers);
            } else {
                $headers = ["customerfirstname", "customerid", "customerlastname", "customername", "customerpoints", "dob", "email", "favstore", "gender", "lastsaledate", "marketingstatus", "memberid", "mobile", "pointsredeemed", "regdate", "syncid", "status"];
            }

            session_start();

            $error = '';

            $html = '';

            if ($request->file->getClientOriginalName() != '') {
                $file_array = explode(".", $request->file->getClientOriginalName());

                $extension = end($file_array);

                if ($extension == 'csv') {
                    $file_data = fopen($_FILES['file']['tmp_name'], 'r');

                    $file_header = fgetcsv($file_data);

                    $html .= '<table class="table table-bordered" style="width:1400px;"><tr>';
                    $header_column = '<option value="">Set Column</option>';

                    foreach ($headers as $item => $values) {
                        $header_column .= '<option value="' . $values . '">' . $values . '</option>';
                    }

                    for ($count = 0; $count < count($file_header); $count++) {
                        $html .= '
                                   <th>
                                    <select name="set_column_data" class="form-control set_column_data" data-column_number="' . $count . '">
                                    ' . $header_column . '
                                    </select>
                                   </th>
                                   ';
                    }

                    $html .= '</tr>';

                    $limit = 0;

                    while (($row = fgetcsv($file_data)) !== FALSE) {
                        $limit++;

                        if ($limit < 6) {
                            $html .= '<tr>';

                            for ($count = 0; $count < count($row); $count++) {
                                $html .= '<td>' . $row[$count] . '</td>';
                            }

                            $html .= '</tr>';
                        }

                        $temp_data[] = $row;
                    }

                    $_SESSION['file_data'] = $temp_data;

                    $html .= '
                              </table>

                              ';
                } else {
                    $error = 'Only <b>.csv</b> file allowed';
                }
            } else {
                $error = 'Please Select CSV File';
            }

            $output = array(
                'error' => $error,
                'output' => $html
            );

            return response()->json($output);

        } catch (Exception $e) {
            return response()->json([]);
        }
    }

    public function importCSV(Request $request): JsonResponse
    {
        try {
            session_start();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                try {
                    $file_data = $_SESSION['file_data'];

                    $customer_data = $request->post();

                    foreach ($file_data as $row) {
                        $data = array();
                        $sku = "";

                        foreach ($customer_data as $index => $val) {
                            $data[$index] = $row[$val];
                            if ($index == "email") {
                                $sku = strval($row[$val]);
                            } else if ($index == "memberid") {
                                $data[$index] = str_pad(strval($row[$val]), 7, "0", STR_PAD_LEFT);
                            }
                        }
                        $update_data = array('$set' => $data);
                        $condition = array('email' => $sku);
                        Customer::raw()->updateOne($condition, $update_data, array('upsert' => true));
                    }
                    return response()->json([
                        'status' => 'success'
                    ]);
                } catch (Exception $e) {
                    return response()->json([
                        'status' => 'fail'
                    ]);
                }

            }

        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }
}
