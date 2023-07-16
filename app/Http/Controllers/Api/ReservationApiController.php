<?php

namespace App\Http\Controllers\Api;

use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

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
}
