<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View|RedirectResponse
    {
        // If setup is not completed, redirect to setup wizard
        if (! is_setup_completed()) {
            return redirect()->route('setup.index');
        }

        return view('landing.index');
    }
}
