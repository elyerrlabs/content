<?php

namespace Content\App\Http\Controllers;

use App\Http\Controllers\WebController;
use Inertia\Inertia;


class AdminController extends WebController
{


    public function __construct()
    {
        $this->middleware('userCanAny:administrator:view');
    }


    public function index()
    {
        return Inertia::render('Admin/Index');
    }
}
