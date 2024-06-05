<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MarcReichel\IGDBLaravel\Models\Game;
use MarcReichel\IGDBLaravel\Enums\Image\Size;

class GamePage extends Controller
{
    public function viewGamePage(Request $request, $id) {
        $id = (int)$id;
        $game = Game::where('id', $id)->with(['cover'])->first();

        $url = null;
        if ($game->cover != null) {
            $url = $game->cover->getUrl(Size::COVER_SMALL, true);
        }

        return view('gamepage', ['game' => $game, 'coverUrl' => $url]);
    }
}
