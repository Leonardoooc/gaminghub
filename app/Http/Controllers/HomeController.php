<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MarcReichel\IGDBLaravel\Builder as IGDB;
use MarcReichel\IGDBLaravel\Models\Game;
use MarcReichel\IGDBLaravel\Enums\Image\Size;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    public function viewHome(Request $request) {
        $igdb = new IGDB('popularity_primitives');
        $popularityData = collect($igdb->select(['game_id', 'value', 'popularity_type'])
            ->limit(20)
            ->where('popularity_type', '=', 1)
            ->orderBy('value', 'desc')
            ->get());

        $gameIds = $popularityData->pluck('game_id')->toArray();

        $games = Game::select(['id', 'name', 'cover', 'themes'])
            ->with(['cover'])
            ->whereIn('id', $gameIds)
            ->limit(20)
            ->get();

        $popularGames = [];
        foreach ($games as $game) {
            $skipGame = false;
            if ($game->themes) {
                foreach ($game->themes as $theme) {
                    if ($theme === 42) {
                        $skipGame = true;
                        break;
                    }
                }
            }

            if ($skipGame) {
                continue;
            }

            $popularGames[] = [
                'id' => $game->id,
                'name' => $game->name,
                'coverUrl' => $game->cover ? $game->cover->getUrl(Size::COVER_BIG, true) : asset('assets/noimg.jpg'),
            ];
        }

        return view('home', [
            'popularGames' => $popularGames,
        ]);
    }
}
