<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function index()
        {
        return view('pricing.index', [
        'priceBasic'   => 0,
        'priceCreator' => 59000,
        'pricePro'     => 119000,
    ]);

    }
}
