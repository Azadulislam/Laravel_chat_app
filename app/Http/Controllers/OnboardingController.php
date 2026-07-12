<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('onboarding');
    }

    public function complete(Request $request)
    {
        $request->user()->update([
            'onboarding_completed' => true,
        ]);

        return redirect()->route('home');
    }
}
