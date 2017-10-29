<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Repositories\HistoryRepo;
use Illuminate\Http\Request;

class SearchHistoryController extends Controller
{
    /**
     * @var HistoryRepo
     */
    private $historyRepo;

    public function __construct(HistoryRepo $historyRepo)
    {
        $this->historyRepo = $historyRepo;
    }

    public function index(Request $request)
    {
        return response()->json([
            'history' => $this->historyRepo->getSearchHistory(),
        ]);
    }
}
