<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display the Guest Post Site & Vendor Inventory View.
     */
    public function index()
    {
        return view('inventory.index', [
            'pageTitle' => 'Guest Post Site & Vendor Inventory'
        ]);
    }
}
