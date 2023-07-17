<?php

namespace App\Http\Middleware;

use App\Models\Reservation;
use App\Models\Table;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAvaliablityTable
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Find available table
        $table = Table::where('status', 'available')
            ->orderBy('capacity')
            ->first();
//        dd($table);
        // Reserve table or add to waiting list if no available tables
        if ($table) {
            $table->reserve($request->customer_id, $request->from_time, $request->to_time);
        } else {
            // Add customer to waiting list
            $reservation = Reservation::create([
                'customer_id' => $request->customer_id,
                'from_time' => $request->from_time,
                'to_time' => $request->to_time,
                'status' => 'Waiting List'
            ]);
            return response()->json([
                'success' => true,
                'message' => 'No available tables, customer added to waiting list',
                'data' => $reservation
            ], 200);
        }

        return $next($request);
    }
}
