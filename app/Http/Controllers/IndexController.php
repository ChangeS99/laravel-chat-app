<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    //
    /**
     * Index Route
     */
    public function index()
    {
        return inertia('Index/Index');
    }
}
