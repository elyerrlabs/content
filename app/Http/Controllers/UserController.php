<?php

namespace Content\App\Http\Controllers;

use App\Http\Controllers\WebController;

final class UserController extends WebController
{
    public function index()
    {
        return view('Content::user.index');
    }
}
