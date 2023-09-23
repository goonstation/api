<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DonateController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Donate/Index');
    }
}
