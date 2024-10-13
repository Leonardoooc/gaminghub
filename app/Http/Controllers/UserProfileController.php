<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use MarcReichel\IGDBLaravel\Models\Game;
use MarcReichel\IGDBLaravel\Models\Genre;
use MarcReichel\IGDBLaravel\Models\Company;
use MarcReichel\IGDBLaravel\Enums\Image\Size;

class UserProfileController extends Controller
{
    public function viewProfile(Request $request, $id) {
        $profileName = DB::select("SELECT name FROM users WHERE id = $id");
        if (!$profileName) {
            return view('profile', [
                'notfound' => true,
            ]);
        }

        $profileName = $profileName[0]->name;

        $scoresDB = DB::select("SELECT * FROM scores WHERE userid = $id");
        $scores = [];
        if ($scoresDB) {
            foreach ($scoresDB as $score) {
                $game = DB::select("SELECT * FROM game WHERE id = $score->gameid")[0];
                $scores[] = [
                    'id' => $score->gameid,
                    'name' => $game->name,
                    'coverUrl' => $game->coverUrl,
                    'score' => $score->score,
                ];
            }
        }

        return view('profile', [
            'name' => $profileName,
            'games' => $scores,
        ]);
    }
}
