<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display the Vendor & Client CRM View.
     */
    public function index()
    {
        return view('contacts.index', [
            'pageTitle' => 'Vendor & Client Directory (CRM)'
        ]);
    }
}
