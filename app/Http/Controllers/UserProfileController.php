<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function viewProfile(Request $request, $id) {
        return view('profile');
    }
}
