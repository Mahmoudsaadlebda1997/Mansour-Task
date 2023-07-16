<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use Illuminate\Http\Request;

class MealsApiController extends Controller
{
    public function allMeals(){
        $meals = Meal::all();
            return response()->json([
                'success' => true,
                'message' =>'All Meals Return Successfully' ,
                'data' =>$meals
        ]);
    }
}
