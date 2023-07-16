<?php

namespace App\Http\Middleware;

use App\Models\Reservation;
use App\Models\Table;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class CheckReservation
{
    public function handle(Request $request, Closure $next)
    {
//        dd($request->all());
        $validator = Validator::make($request->all(), [
            'table_id' => 'required|exists:tables,id',
            'from_time' => 'required|date_format:Y-m-d H:i:s',
            'to_time' => 'required|date_format:Y-m-d H:i:s|after:from_time',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $table = Table::findOrFail($request->table_id);

        if ($table->capacity < $request->capacity) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, this table is not available for the given capacity',
            ], 400);
        }

        $fromTime = Carbon::parse($request->from_time);
        $toTime = Carbon::parse($request->to_time);

        $reservations = Reservation::where('table_id', $table->id)
            ->where(function ($query) use ($fromTime, $toTime) {
                $query->where(function ($query) use ($fromTime,$toTime) {
                    $query->where('from_time', '>=', $fromTime)
                        ->where('from_time', '<=', $toTime);
                })
                    ->orWhere(function ($query) use ($toTime,$fromTime) {
                        $query->where('to_time', '>=', $fromTime)
                            ->where('to_time', '<=', $toTime);
                    })
                    ->orWhere(function ($query) use ($fromTime, $toTime) {
                        $query->where('from_time', '<=', $fromTime)
                            ->where('to_time', '>=', $toTime);
                    });
            })
            ->get();
        if ($reservations->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, this table is not available for the given reservation time',
            ], 400);
        }

        return $next($request);
    }
}
