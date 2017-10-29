<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Services\TwitterService;
use Illuminate\Http\Request;

class TweetsController extends Controller
{
    private $twitterService;

    public function __construct(TwitterService $twitterService)
    {
        $this->twitterService = $twitterService;
    }

    public function index(Request $request)
    {
        $request->validate([
            'query' => 'required|string',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $query        = $request->query('query');
        $latLong      = $request->only(['lat', 'lng']);
        $searchRadius = 50;

        return response()->json([
            'tweets' => $this->twitterService->getTweetsByLocation($query, $latLong, $searchRadius),
        ]);
    }
}
