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

        if ($genres) {
            foreach ($genres as $genreId) {
                $genreName = Genre::find($genreId);
                $genreNames[] = $genreName->name;
            }
        }

        $publishers = [];
        $developers = [];

        if ($game->involved_companies) {
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
        $userHasReview = false;

        $currentRating = null;
        if (Auth::check()) {
            $userId = Auth::user()->id;
            $hasRating = DB::select("SELECT * FROM scores WHERE userid = $userId and gameid = $id");
            if ($hasRating) {
                $currentRating = $hasRating[0]->score;
            }
        }

        foreach ($reviewsRequest as $review) {
            $reviewAuthor = DB::select("SELECT name FROM users WHERE id = $review->userid")[0];

            $isReviewAuthor = false;
            if (Auth::check()) {
                $isReviewAuthor = $review->userid == Auth::user()->id;
            }

            $reviews[] = [
                'description' => $review->description,
                'author' => $reviewAuthor->name,
                'likes' => $review->likes,
                'isReviewAuthor' => $isReviewAuthor,
            ];

            if ($isReviewAuthor) {
                $userHasReview = true;
            }
        }

        usort($reviews, function ($a, $b) {
            return $b['isReviewAuthor'] <=> $a['isReviewAuthor'];
        });

        $gameStats = DB::table('scores')->where('gameid', $id)->selectRaw('COUNT(*) as total_scores, AVG(score) as average_score')->first();

        $ranking = DB::table(DB::raw('(SELECT gameid, AVG(score) as average_score, RANK() OVER (ORDER BY AVG(score) DESC) as rank FROM scores GROUP BY gameid) as ranked_scores'))->where('gameid', '=', $id)->first();
        $rank = null;
        if ($ranking) {
            $rank = $ranking->rank;
        }

        $popularityRankQuery = DB::table('scores')
            ->selectRaw('gameid, COUNT(*) as total_reviews')
            ->groupBy('gameid')
            ->havingRaw('COUNT(*) > 0')
            ->orderByDesc('total_reviews')
            ->get();

        $popularity = $popularityRankQuery->pluck('gameid')->search($id);

        return view('gamepage', [
            'id' => $id, 
            'name' => $game->name, 
            'summary' => $game->summary, 
            'coverUrl' => $url, 
            'genres' => $genreNames, 
            'publishers' => $publishers, 
            'developers' => $developers, 
            'launchDate' => $launchDate, 
            'reviews' => $reviews, 
            'userHasReview' => $userHasReview, 
            'currentRating' => $currentRating, 
            'averageRating' => round($gameStats->average_score, 2),
            'ratingCount' => $gameStats->total_scores,
            'ranking' => $rank,
            'popularity' => $popularity !== false ? $popularity + 1 : 'N/A',
        ]);
    }

    public function onSearchGameList(Request $request) {
        $name = $request->input('name');

        $games = [];
        if (!$name || trim($name) === '') {
            return redirect()->route('home')->with('games', $games);
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

        return redirect()->route('home')->with('games', $games);
    }

    public function onSendReview(Request $request) {
        if (!Auth::check()) {
            return;
        }

        $review = $request->input('review');
        $id = $request->input('gameId');

        if (!$review || trim($review) === '') {
            return response()->json(['error' => 'O review não pode estar vazio.'], 400);
        }

        $newReview = DB::table('reviews')->insert([
            'description' => $review,
            'userid' => Auth::user()->id,
            'gameid' => $id,
            'likes' => 0,
        ]);

        return response()->json(['success' => 'Review enviado com sucesso!', 'id' => $id], 200);
    }

    public function onSendRating(Request $request) {
        if (!Auth::check()) {
            return;
        }
        
        $rating = $request->input('rating');
        $id = $request->input('gameId');
        $userId = Auth::user()->id;

        if (!$rating || $rating > 10 || $rating < 1) {
            return response()->json(['error' => 'Erro, nota inválida.'], 400);
        }

        $currentRating = DB::select("SELECT * FROM scores WHERE userid = $userId and gameid = $id");
        if ($currentRating) {
            DB::table('scores')->where('userid', $userId)->where('gameid', $id)->update(['score' => $rating, 'updated' => now()]);
        } else {
            DB::table('scores')->insert([
                'userid' => $userId,
                'gameid' => $id,
                'score' => $rating,
                'created' => now(),
            ]);
        }

        return response()->json(['success' => 'Nota atualizada com sucesso.', 'id' => $id], 200);
    }
}
