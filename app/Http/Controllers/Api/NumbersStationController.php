<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NumbersStationPasswordResource;
use App\Models\NumbersStationPassword;
use Illuminate\Http\Request;

/**
 * @tags Numbers Station
 */
class NumbersStationController extends Controller
{
    /**
     * Get
     *
     * Get the current numbers representing the password for the numbers station terminal
     */
    public function index(Request $request)
    {
        $numbersPass = NumbersStationPassword::firstOrFail();

        return new NumbersStationPasswordResource($numbersPass);
    }
}
