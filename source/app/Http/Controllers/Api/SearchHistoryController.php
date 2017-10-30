<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Repositories\HistoryRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchHistoryController extends Controller
{
    /**
     * @var HistoryRepo
     */
    private $historyRepo;

    /**
     * SearchHistoryController constructor.
     *
     * @param HistoryRepo $historyRepo
     */
    public function __construct(HistoryRepo $historyRepo)
    {
        $this->historyRepo = $historyRepo;
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) : JsonResponse
    {
        return response()->json([
            'history' => $this->historyRepo->getSearchHistory(),
        ]);
    }
}
