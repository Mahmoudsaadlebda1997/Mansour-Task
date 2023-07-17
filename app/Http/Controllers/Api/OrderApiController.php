<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Models\Order;
use App\Models\OrderDetail;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderApiController extends Controller
{
    public function createOrder(Request $request){
        $validator = Validator::make($request->all(), [
            'table_id'          => 'required|exists:tables,id',
            'reservation_id'          => 'required|exists:reservations,id',
            'customer_id'          => 'required|exists:customers,id',
            'waiter_id'          => 'required|exists:waiters,id',
            'total_paid'         => 'nullable',
            'order_id.*'      => 'nullable',
            'meal_id.*'      => 'required|exists:meals,id',
            'amount_to_pay.*'      => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }
        $order = new Order;
        $order->table_id     = $request->table_id;
        $order->reservation_id     = $request->reservation_id;
        $order->customer_id     = $request->customer_id;
        $order->waiter_id     = $request->waiter_id;
        $timestamp = $request->created_at;
        $date = Carbon::createFromTimestamp($timestamp);
        $order->date     = $date;
        $order->save();
        //        Create Order Details
        foreach ($request->orderDetails as $key => $value) {
            $orderDetail = new OrderDetail();
            $orderDetail->order_id        = $order->id;
            $orderDetail->meal_id         = $value['meal_id'];
            $meal = Meal::findOrFail($value['meal_id']);
            if($meal->discount > 0 && $meal->discount){
                $orderDetail->amount_to_pay =  round($meal->price * ($meal->discount / 100) ,2);
            }else{
            $orderDetail->amount_to_pay         = $meal->price;
            }
            $orderDetail->save();
            $order->total_paid += $orderDetail->amount_to_pay;
        }
        $order->save();
        $data = Order::with('orderDetails')->find($order->id);
//        return response()->json([
//            'success' => true,
//            'message' => 'Order is Made Succesfully',
//            'data' => $data
//        ], 201);
        // Generate HTML invoice
        $html = "<h1>Invoice for Table " . $request->table_id . "</h1>";
        $html .= "<table>";
        $html .= "<tr><th>Item</th><th>Price</th></tr>";
        foreach ($data->orderDetails as $detail) {
            $html .= "<tr><td>" . $detail->meal->name . "</td><td>" . $detail->amount_to_pay . "</td></tr>";
        }
        $html .= "<tr><td>Total:</td><td>" . $data->total_paid . "</td></tr>";
        $html .= "</table>";

        // Send invoice to printer
        $client = new Client();
        $response = $client->request('POST', 'https://www.google.com/cloudprint/submit', [
            'headers' => [
//                Here My Access Token From Login User
                'Authorization' => 'Bearer YOUR_ACCESS_TOKEN',
                'Content-Type' => 'application/json'
            ],
            'json' => [
//                    Here i  Enter Printer ID
                'printerid' => 'YOUR_PRINTER_ID',
                'content' => base64_encode($html),
                'contentType' => 'text/html',
                'title' => 'Invoice for Table ' . $request->table_id
            ]
        ]);
        if ($response->getStatusCode() == 200) {
            return response()->json([
                'success' => true,
                'message' => 'Order is Made Succesfully and Invoice has been sent to the printer',
                'data' => $data
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Error sending print job: ' . $response->getBody(),
                'data' => $data
            ], 500);
        }
    }
}
