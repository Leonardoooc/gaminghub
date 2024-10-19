<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MarcReichel\IGDBLaravel\Models\Game;
use MarcReichel\IGDBLaravel\Enums\Image\Size;

class NavigationController extends Controller
{
    public function getGames($name) {
        $games = [];
        if (!$name || trim($name) === '') {
            return [];
        }

        $gamesFind = Game::search($name)
            ->select(['id', 'name', 'cover', 'rating_count'])
            ->with(['cover'])
            ->get();

        foreach ($gamesFind as $game) {
            $coverUrl = null;
            if ($game->cover != null) {
                $coverUrl = $game->cover->getUrl(Size::COVER_SMALL, true);
            }

            $ratingCount = $game->rating_count ?? 0;

            $games[] = [
                'name' => $game->name,
                'id' => $game->id,
                'coverUrl' => $coverUrl,
                'rating_count' => $ratingCount,
            ];
        }

        usort($games, function($a, $b) {
            return $b['rating_count'] <=> $a['rating_count'];
        });

        return $games;
    }

    public function getGamesNav(Request $request) {
        $name = $request->get('q');
        $games = $this->getGames($name);
        return response()->json($games, 200);
    }

    public function searchPage(Request $request) {
        $name = $request->get('q');
        $games = $this->getGames($name);
        return view('search', ['games' => $games, 'query' => $name]);
    }
}
