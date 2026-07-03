<?php

namespace Content\App\Http\Controllers;

use App\Http\Controllers\Web\Admin\Setting\SettingController as Controller;

final class SettingsController extends Controller
{

    public function __construct()
    {
        $this->middleware('userCanAny:settings:content:full,settings:content:view')->only('index');
        $this->middleware('userCanAny:settings:content:full,settings:content:update')->only('update');
    }

    /**
     * Index
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view("Content::settings.parts.general");
    }

}
