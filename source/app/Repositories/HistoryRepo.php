<?php

namespace App\Repositories;

use App\Models\History;
use Illuminate\Config\Repository as ConfigRepository;

class HistoryRepo
{
    /**
     * @var History
     */
    private $history;

    /**
     * @var ConfigRepository
     */
    private $config;

    public function __construct(History $history, ConfigRepository $config)
    {
        $this->history = $history;
        $this->config  = $config;
    }

    /**
     * @param string $cacheKey
     * @param string $address
     * @param array $latLong
     *
     * @return History
     */
    public function create(string $cacheKey, string $address, array $latLong) : History
    {
        $params = compact('address') + $latLong;

        $history = $this->history
            ->firstOrNew(['key' => $cacheKey]);

        $history->payload = $params;
        $history->count   = ($history->count ?: 0) + 1;

        $history->save();

        return $history;
    }

    /**
     *
     */
    public function getSearchHistory() : array
    {
        return $this->history
            ->orderBy('count', 'desc')
            ->take($this->config->get('tweet-finder.history-limit'))
            ->get()
            ->toArray();
    }
}
