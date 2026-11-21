<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display the Guest Post Orders & Live Links Tracker View.
     */
    public function index()
    {
        return view('orders.index', [
            'pageTitle' => 'Orders & Live Link Status Tracker'
        ]);
    }
}
