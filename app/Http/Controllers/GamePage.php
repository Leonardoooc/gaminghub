<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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

        $url = null;
        if ($game->cover != null) {
            $url = $game->cover->getUrl(Size::COVER_SMALL, true);
        }

        $isInDb = DB::table('game')->where('id', $id)->first();
        if (!$isInDb) {
            DB::table('game')->insert([
                'id' => $id,
                'name' => $game->name,
                'coverUrl' => $url,
            ]);
        }

        $genreNames = [];
        $genres = $game->genres;
        foreach ($genres as $genreId) {
            $genreName = Genre::find($genreId);
            $genreNames[] = $genreName->name;
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

        $query = "SELECT * FROM reviews WHERE gameid = $id ORDER BY likes desc";
        $reviewsRequest = DB::select($query);

        $reviews = [];
        foreach ($reviewsRequest as $review) {
            $reviewAuthor = DB::select("SELECT name FROM users WHERE id = $review->userid")[0];
            
            $reviews[] = [
                'description' => $review->description,
                'author' => $reviewAuthor->name,
                'likes' => $review->likes,
            ];
        }

        return view('gamepage', ['name' => $game->name, 'summary' => $game->summary, 'coverUrl' => $url, 'genres' => $genreNames, 'publishers' => $publishers, 'developers' => $developers, 'launchDate' => $launchDate, 'reviews' => $reviews]);
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
