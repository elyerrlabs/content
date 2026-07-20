<?php

namespace Content\App\Http\Controllers\web;

use App\Http\Controllers\Controller;

final class PoliciesController extends Controller
{
    public function termsAndCondition()
    {
        return view(
            'Content::policies',
            [
                'title' => __('Terms and Conditions'),
                'path' => 'pages.layouts.terms-and-conditions'
            ]
        );
    }

    public function policiesOfPrivacy()
    {
        return view(
            'Content::policies',
            [
                'title' => __('Privacy policy'),
                'path' => 'pages.layouts.policies-of-privacy'
            ]
        );
    }

    public function policiesOfCookies()
    {
        return view(
            'Content::policies',
            [
                'title' => __('Cookies Policy'),
                'path' => 'pages.layouts.policies-of-cookies'
            ]
        );
    }
}
