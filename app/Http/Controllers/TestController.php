<?php

namespace Content\App\Http\Controllers;

use App\Http\Controllers\WebController;
use Inertia\Inertia;

class TestController extends WebController
{
    public function admin()
    {
        return Inertia::render('Admin');
    }
}
