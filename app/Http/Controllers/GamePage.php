<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MarcReichel\IGDBLaravel\Models\Game;
use MarcReichel\IGDBLaravel\Models\Genre;
use MarcReichel\IGDBLaravel\Models\Company;
use MarcReichel\IGDBLaravel\Enums\Image\Size;

class GamePage extends Controller
{
    public function viewGamePage(Request $request, $id) {
        $id = (int)$id;
        $game = Game::where('id', $id)->with(['cover', 'involved_companies', 'release_dates'])->first();

        if (!$game) {
            return view('gamepage', []); 
        }

        $genreNames = [];
        $genres = $game->genres;
        foreach ($genres as $genreId) {
            $genreName = Genre::find($genreId);
            $genreNames[] = $genreName->name;
        }

        $url = null;
        if ($game->cover != null) {
            $url = $game->cover->getUrl(Size::COVER_SMALL, true);
        }

        $publishers = [];
        $developers = [];
        foreach ($game->involved_companies as $involved_company) {
            if ($involved_company->company) {
                $company = Company::find($involved_company->company);
                if ($involved_company->publisher) {
                    $publishers[] = $company->name;
                }

                if ($involved_company->developer) {
                    $developers[] = $company->name;
                }

            }
        }

        $launchDate = null;
        foreach ($game->release_dates as $date) {
            if ($launchDate == null || $launchDate > $date->date) {
                $launchDate = $date->date;
            }
        }

        return view('gamepage', ['name' => $game->name, 'summary' => $game->summary, 'coverUrl' => $url, 'genres' => $genreNames, 'publishers' => $publishers, 'developers' => $developers, 'launchDate' => $launchDate]);
    }

    public function onSearchGameList(Request $request) {
        $name = $request->input('name');

        $gamesFind = Game::search($name)->select(['id', 'name', 'cover'])->with(['cover'])->get();

        $games = [];
        foreach ($gamesFind as $game) {
            $coverUrl = isset($game->cover) ? $game->cover->url : null;
            $games[] = [
                'name' => $game->name,
                'id' => $game->id,
                'coverUrl' => $coverUrl,
            ];
        }

        return redirect()->route('dashboard')->with('games', $games);
    }
}
