<?php

namespace App\Http\Controllers\Quote;

use App\Http\Controllers\Controller;
use App\Jobs\DeleteLocalFileJob;
use App\Models\Customer;
use App\Models\EjItem;
use App\Models\Location;
use App\Models\PosSale;
use App\Models\Quote;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class QuoteController extends Controller
{
    private $jsReportKey;
    private $jsReportServer;
    private $jsReportShortId;

    public function __construct() {

        $this->jsReportKey     = env('JSREPORT_KEY', '');
        $this->jsReportServer  = env('JSREPORT_SERVER', '');
        $this->jsReportShortId = env('JSREPORT_PDF_TEMPLATE_SHORT_ID', '');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $customerList = Customer::all();
        $storelist = Location::all();
        return view('quote.index', array('customers' => $customerList, 'storelist' => $storelist));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */

    public function invoiceDocumentPrepare($quote_data, $document_name)
    {
        try {
            $locationid = $quote_data["store"];
            $customerid = $quote_data["customer"];

            $ops_location = array(
                array(
                    '$match' => array('locationid' => $locationid)
                )
            );
            $location_data = Location::raw()->aggregate($ops_location)->toArray();
            $location_data = $location_data[0];

            $ops_customer = array(
                array(
                    '$match' => array('customerid' => $customerid)
                )
            );
            $customer_data = Customer::raw()->aggregate($ops_customer)->toArray();
            $customer_data = $customer_data[0];
            if($quote_data["quote"] == "sale"){
                $footerText1 = $location_data["invoice-footer-message-1"] ?? '';
                $footerText2 = $location_data["invoice-footer-message-2"] ?? '';
                $invoiceType = "Tax Invoice";
            }else{
                $footerText1 = $location_data["quote-footer-message-1"] ?? '';
                $footerText2 = $location_data["quote-footer-message-2"] ?? '';;
                $invoiceType = "Tax Quote";
            }
            $serial = strtotime(date("y-m-d H:i:s"));
            $products = [];
            if($quote_data["products"]){
                foreach ($quote_data["products"] as $product){
                    $invoice_product["serviceDate"] = $product["service-date"] ?? '';
                    $invoice_product["name"] = $product["sku-name"] ?? '';
                    $invoice_product["description"] = $product["description"] ?? '';
                    $invoice_product["quantity"] = $product["quantity"] ?? '';
                    $invoice_product["rate"] = $product["price"];
                    $invoice_product["amount"] =(float) intval($product["quantity"] ?? 0)* intval($product["price"] ?? 0);
                    $invoice_product["gst"] = $product["gst"] ?? '';
                    $products[] = $invoice_product;
                }
            }
            $response = Http::acceptJson()
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . $this->jsReportKey ,
                    'Connection' => 'keep-alive',
                    'Accept'=> '*/*',
                ])
                ->withoutVerifying()
                ->sink(public_path("documents/$document_name.pdf"))
                ->post( $this->jsReportServer . '/api/report', [
                    'template' => [
                        "shortid" => $this->jsReportShortId,
                    ],
                    "data" => $this->jsReporData($quote_data)
                ]);
            //dump($response->body());

            // DeleteLocalFileJob::dispatch(public_path("documents/$document_name.pdf"))
            //     ->delay(now()->addMinutes(3));

            return [
                'status' => true
            ];

        } catch (\Throwable $th) {
            //throw $th;
            Log::error("invoiceDocumentPrepare faced error: ".$th->getMessage());

            return [
                'status'  => false,
                'message' => $th->getMessage()
            ];
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $quote_data = $request->post('data');
            $ops = array(

                array(
                    '$group' => array(
                        '_id' => null,
                        'maxid' => array(
                            '$max' => array(
                                '$toDouble' => '$quote_id'
                            )))
                )

            );
            $data = PosSale::raw()->aggregate($ops)->toArray();
            if (count($data) === 0) {
                $quote_data["quote_id"] = "1";
            } else {
                $max_id = $data[0]["maxid"];
                $quote_data["quote_id"] = strval(++$max_id);
            }

            $quote_data["status"] = "active";
            $quote_data["email-status"] = null;
            if(!empty($quote_data["send-email-customer"])){
                $customer_email = $quote_data["customer-email"];
                $document_name = "Invoice_".strtotime(date("Y-m-d H:i:s"));
                if($this->invoiceDocumentPrepare($quote_data, $document_name)['status']){
                    $subject = "Tax Document";
                    $quote_data["send-email-customer"] = 1;
                    $attached_file = public_path("documents/$document_name.pdf");
                    Mail::send('quote.email.index', [],
                        function ($message) use($document_name, $attached_file, $customer_email, $subject){
                            $file_conn = array(
                                'as' => $document_name.'.pdf',
                                'mime' => 'application/pdf');
                            $message->to([$customer_email])->subject($subject)->getHeaders()->addTextHeader('x-mailgun-native-send', 'true');
                            $message->attach($attached_file, $file_conn);
                        });
                    if( count(Mail::failures()) > 0 ) {
                        $quote_data["email-status"] = "draft"; //when fail to customer email
                    }else{
                        $quote_data["email-status"] = "sent"; //when will sent to customer email
                    }
                }else{
                    $quote_data["email-status"] = "draft";
                }
            }else{
                $quote_data["send-email-customer"] = 0;
            }

            if($quote_data["quote"] == "sale" && !empty($quote_data["mark-paid"])){
                $quote_data["mark-paid"] = 1;
                EjItem::raw()->insertOne($quote_data);
                $this->saveLog("saved data : ".json_encode($quote_data), EjItem::tableName());
            }else{
                $quote_data["mark-paid"] = 0;
            }
            PosSale::raw()->insertOne($quote_data);
            $this->saveLog("saved data : ".json_encode($quote_data), Quote::tableName());


            return response()->json([
                'status' => 'success'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        try {
            $customer_info = Customer::tableName();
            if ($request->has('id')) {
                $id = $request->get('id');
                $ops = array(
                    array(
                        '$lookup' => array(
                            'from' => $customer_info,
                            'localField' => 'customer',
                            'foreignField' => 'customerid',
                            'as' => 'customer_info'
                        )
                    ),
                    array(
                        '$unwind' => '$customer_info'
                    ),
                    array(
                        '$match' => array('quote_id' => $id)
                    )

                );
                $data = PosSale::raw()->aggregate($ops)->toArray();
            } else {
                $status_list = ['active', 'ACTIVE'];
                $ops = array(
                    array(
                        '$lookup' => array(
                            'from' => $customer_info,
                            'localField' => 'customer',
                            'foreignField' => 'customerid',
                            'as' => 'customer_info'
                        )
                    ),
                    array(
                        '$unwind' => '$customer_info'
                    ),
                    array(
                        '$match' => array("status" => array('$in' => $status_list))
                    )
                );
                $data = PosSale::raw()->aggregate($ops)->toArray();
            }
            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $quote_data = $request->all();
            if($quote_data["quote"] == "sale" && !empty($quote_data["mark-paid"])){
                $quote_data["mark-paid"] = 1;
            } else{
                $quote_data["mark-paid"] = 0;
            }
            $quote_data["email-status"] = null;
            if(!empty($quote_data["send-email-customer"])){
                $customer_email = $quote_data["customer-email"];
                $document_name = "Invoice_".strtotime(date("Y-m-d H:i:s"));
                if($this->invoiceDocumentPrepare($quote_data, $document_name)['status']){
                    $subject = "Tax Document";
                    $quote_data["send-email-customer"] = 1;
                    $attached_file = public_path("documents/$document_name.pdf");
                    Mail::send('quote.email.index', [],
                        function ($message) use($document_name, $attached_file, $customer_email, $subject){
                            $file_conn = array(
                                'as' => $document_name.'.pdf',
                                'mime' => 'application/pdf');
                            $message->to([$customer_email])->subject($subject)->getHeaders()->addTextHeader('x-mailgun-native-send', 'true');
                            $message->attach($attached_file, $file_conn);
                        });
                    if( count(Mail::failures()) > 0 ) {
                        $quote_data["email-status"] = "draft"; //when fail to customer email
                    }else{
                        $quote_data["email-status"] = "sent"; //when will sent to customer email
                    }
                }else{
                    $quote_data["email-status"] = "draft";
                }
            }else{
                $quote_data["send-email-customer"] = 0;
            }
            $update_data = array('$set' => $quote_data);
            $condition = array('quote_id' => strval($id));
            PosSale::raw()->updateOne($condition, $update_data, ['upsert' => true]);

            $this->saveLog("reference id: " . $id . " updated data : " . json_encode($update_data), Quote::tableName());

            if($quote_data["quote"] == "sale" && !empty($quote_data["mark-paid"])){
                EjItem::raw()->updateOne($condition, $update_data, ['upsert' => true]);
                $this->saveLog("reference id: " . $id . "updated data : ".json_encode($quote_data), EjItem::tableName());
            }

            return response()->json([
                'status' => 'success'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            $update_data = array('$set' => array('status' => 'inactive'));
            $condition = array('quote_id' => strval($id));
            PosSale::raw()->updateOne($condition, $update_data, ['upsert' => true]);
            $this->saveLog("deleted data (reference id): " . $id, Quote::tableName());
            return response()->json([
                'status' => 'success'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function jsReporData($quote_data)
    {
        $locationid = $quote_data["store"];
        $customerid = $quote_data["customer"];

        $ops_location = array(
            array(
                '$match' => array('locationid' => $locationid)
            )
        );
        $location_data = Location::raw()->aggregate($ops_location)->toArray();
        $location_data = $location_data[0];

        $ops_customer = array(
            array(
                '$match' => array('customerid' => $customerid)
            )
        );
        $customer_data = Customer::raw()->aggregate($ops_customer)->toArray();
        $customer_data = $customer_data[0];
        if($quote_data["quote"] == "sale"){
            $footerText1 = $location_data["invoice-footer-message-1"] ?? '';
            $footerText2 = $location_data["invoice-footer-message-2"] ?? '';
            $invoiceType = "Tax Invoice";
        }else{
            $footerText1 = $location_data["quote-footer-message-1"] ?? '';
            $footerText2 = $location_data["quote-footer-message-2"] ?? '';;
            $invoiceType = "Tax Quote";
        }
        $serial = strtotime(date("y-m-d H:i:s"));
        $products = [];
        if($quote_data["products"]){
            foreach ($quote_data["products"] as $product){
                $invoice_product["serviceDate"] = $product["service-date"] ?? '';
                $invoice_product["name"] = $product["sku-name"] ?? '';
                $invoice_product["description"] = $product["description"] ?? '';
                $invoice_product["quantity"] = $product["quantity"] ?? '';
                $invoice_product["rate"] = $product["price"];
                $invoice_product["amount"] =(float) intval($product["quantity"] ?? 0)* intval($product["price"] ?? 0);
                $invoice_product["gst"] = $product["gst"] ?? '';
                $products[] = $invoice_product;
            }
        }
        if(!empty($quote_data["invoice-date"])){
            $quote_data["invoice-date"] = date("d/m/Y", strtotime($quote_data["invoice-date"]));
        }
        if(!empty($quote_data["due-date"])){
            $quote_data["due-date"] = date("d/m/Y", strtotime($quote_data["due-date"]));
        }
        return [
            "logo"     => $location_data["image"] ?? '',
            "location" =>  [
                "name"    => $location_data["locationname"] ?? '',
                "address" => $location_data["address"] ?? '',
                "email"   => $location_data["locationemail"] ?? '',
                "phone"   => $location_data["locationphone"] ?? '',
            ],
            "invoiceType" => $invoiceType,
            "customer"    =>  [
                "firstName"      => $customer_data["customerfirstname"] ?? '',
                "lastName"       => $customer_data["customerlastname"] ?? '',
                "email"          => $customer_data["email"] ?? '',
                "phone"          => $customer_data["mobile"] ?? '',
                "billingAddress" => $customer_data["billingaddress"] ?? '',
                "shippingAddress"=> $customer_data["shippingaddress"] ?? ''
            ],
            "invoice" =>  [
                "serial"  => $serial,
                "terms"   => str_replace("_", " ", $quote_data["terms"] ?? ''),
                "date"    => $quote_data["invoice-date"] ?? '',
                "dueDate" => $quote_data["due-date"] ?? '',
            ],
            "shipping" => [
                "date"     => $quote_data["shipping-date"] ?? '',
                "via"      => $quote_data["ship-via"] ?? '',
                "tracking" => $quote_data["tracking-no"] ?? ''
            ],
            "products" => $products,
            "total" => [
                "subTotal" => $quote_data["subTotal"] ?? '',
                "discount" => $quote_data["subTotalPercent"] ?? '',
                "gst"      => $quote_data["gstValue"] ?? '',
                "shipping" => $quote_data["shipping_cost"] ?? '',
                "total"    => $quote_data["total"] ?? '',
            ],
            "footerText1" => $footerText1,
            "footerText2" => $footerText2
        ];
    }

    public function pdfDownload(Request $request)
    {
        try {
            $id = $request->get('id');
            $ops = array(
                array(
                    '$match' => array('quote_id' => $id)
                )
            );
            $quote_data = PosSale::raw()->aggregate($ops)->toArray();
            $quote_data =$quote_data[0];
            $fileName = "Invoice_$id";

            File::ensureDirectoryExists(public_path("documents"));

            $response = Http::acceptJson()
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . $this->jsReportKey ,
                    'Connection' => 'keep-alive',
                    'Accept'=> '*/*',
                ])
                ->withoutVerifying()
                ->sink(public_path("documents/$fileName.pdf"))
                ->post( $this->jsReportServer . '/api/report', [
                    'template' => [
                        "shortid" => $this->jsReportShortId,
                    ],
                    "data" => $this->jsReporData($quote_data),
                    "options" => [
                        'Content-Disposition' => 'Attachment; filename=badges.pdf'
                    ]
                ]);

            if ($response->body()) {

                // DeleteLocalFileJob::dispatch(public_path("documents/$fileName.pdf"))
                //     ->delay(now()->addMinutes(3));

                return response()->download(public_path("documents/$fileName.pdf"));
            }

        } catch (\Throwable $th) {
            //throw $th;

            return back()->with('error', $th->getMessage());
        }
    }
}
