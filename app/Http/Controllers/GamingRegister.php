<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GamingRegister extends Controller
{
    public function viewReg(Request $request) {
        return view('auth.register');
    }
}
