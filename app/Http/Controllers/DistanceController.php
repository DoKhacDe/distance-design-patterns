<?php

namespace App\Http\Controllers;

use App\Domain\Distance\DistanceCalculator;
use App\Domain\Distance\Point;
use Illuminate\Http\Request;

class DistanceController extends Controller
{
    public function __construct(
        private readonly DistanceCalculator $calculator
    ) {
    }

    public function calculate(Request $request)
    {
        $point1 = new Point($request->input('lat1'), $request->input('long1'));
        $point2 = new Point($request->input('lat2'), $request->input('long2'));

        $distance = $this->calculator->getDistance($point1, $point2);

        return response()->json(['distance' => $distance]);
    }
}
