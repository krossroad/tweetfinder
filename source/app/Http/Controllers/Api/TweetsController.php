<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Services\TwitterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TweetsController extends Controller
{
    /**
     * @var TwitterService
     */
    private $twitterService;

    /**
     * TweetsController constructor.
     *
     * @param TwitterService $twitterService
     */
    public function __construct(TwitterService $twitterService)
    {
        $this->twitterService = $twitterService;
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) : JsonResponse
    {
        $request->validate([
            'query' => 'required|string',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $query   = $request->query('query');
        $latLong = $request->only(['lat', 'lng']);

        return response()->json([
            'tweets' => $this->twitterService->getTweetsByLocation($query, $latLong),
        ]);
    }
}
