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
    public function sqltest(Request $request)
    {
        DB::insert('insert into socioeconomica (responsavel) values (?)', ['xd']);
        $users = DB::select('select * from socioeconomica');

        print_r($users);
    }

    public function apiTest(Request $request) {
        $games = Game::select(['*'])->with(['cover'])->get();
        
        return view('dashboard', ['games' => $games]);
    }
}
