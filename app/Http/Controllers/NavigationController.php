<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use MarcReichel\IGDBLaravel\Models\Game;
use MarcReichel\IGDBLaravel\Models\Genre;
use MarcReichel\IGDBLaravel\Models\Company;
use MarcReichel\IGDBLaravel\Enums\Image\Size;

class NavigationController extends Controller
{
    public function getGames(Request $request) {
        $name = $request->get('q');

        $games = [];
        if (!$name || trim($name) === '') {
            return response()->json([], 200);
        }

        $gamesFind = Game::search($name)->select(['id', 'name', 'cover'])->with(['cover'])->get();
        foreach ($gamesFind as $game) {
            $coverUrl = isset($game->cover) ? $game->cover->url : null;
            $games[] = [
                'name' => $game->name,
                'id' => $game->id,
                'coverUrl' => $coverUrl,
            ];
        }

        return response()->json($games, 200);
    }
}
