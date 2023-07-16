<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ReservationApiController extends Controller
{
    #Check Table Availability
    public function checkDate(Request $request){
//        dd($request->all());
        $table = Table::findOrFail($request->table_id);
        if ($table->capacity >= $request->capacity){
            $reservations = Reservation::where('table_id', $table->id)
                ->where('from_time', '<=', $request->dateAndTime)
                ->where('to_time', '>=', $request->dateAndTime)
                ->get();
            if($reservations->count() > 0){
                return response()->json([
                    'success' => true,
                    'message' =>'Sorry This Table Is Not Available For This Time Given' ,
                ]);
            }else{
                return response()->json([
                    'success' => true,
                    'message' =>'Its Available To Take The Reservation' ,
                ]);
            }
        }else{
            return response()->json([
                'success' => true,
                'message' =>'Sorry This Table Is Not Available For This Capacity Given' ,
            ]);
        }
    }
    public function reserveTable(Request $request){
        $validator = Validator::make($request->all(), [
            'table_id'          => 'required|exists:tables,id',
            'from_time'         => 'required',
            'to_time'      => 'required|after:from_time',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }
        $data = new Reservation;
        $data->table_id     = $request->table_id;
        $data->from_time     = $request->from_time;
        $data->from_time     = $request->to_time;
        $data->save();
        return response()->json([
            'success' => true,
            'message' => 'Table is Reserved Succesfully',
            'data' => $data
        ], 201);
    }
}
