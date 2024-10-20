<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use MarcReichel\IGDBLaravel\Models\Game;
use MarcReichel\IGDBLaravel\Enums\Image\Size;

class MysqlTestController extends Controller
{
    public function apiTest(Request $request) {
        $games = Game::select(['*'])->with(['cover'])->get();
        
        return view('home', ['games' => $games]);
    }
}
