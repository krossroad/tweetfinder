<?php

namespace App\Services;


use App\Repositories\HistoryRepo;
use Illuminate\Cache\Repository as Cache;
use Illuminate\Config\Repository as Config;
use Thujohn\Twitter\Twitter;

class TwitterService
{
    /**
     * @var Twitter
     */
    private $twitter;

    /**
     * @var Cache
     */
    private $cache;

    /**
     * @var HistoryRepo
     */
    private $historyRepo;

    /**
     * @var Config
     */
    private $config;

    /**
     * TwitterService constructor.
     *
     * @param Twitter $twitter
     * @param Cache $cache
     * @param HistoryRepo $historyRepo
     * @param Config $config
     */
    public function __construct(Twitter $twitter, Cache $cache, HistoryRepo $historyRepo, Config $config)
    {
        $this->twitter     = $twitter;
        $this->cache       = $cache;
        $this->historyRepo = $historyRepo;
        $this->config      = $config;
    }

    /**
     * @param string $address
     * @param array $latLong
     *
     * @return array
     *
     */
    public function getTweetsByLocation(string $address, array $latLong) : array
    {
        $cacheKey = str_slug($address);

        $this->historyRepo->create($cacheKey, $address, $latLong);

        return $this->cache->has($cacheKey) ? $this->cache->get($cacheKey)
            : $this->fetchTweets($address, $latLong, $cacheKey);
    }

    /**
     * @param string $address
     * @param array $latLong
     * @param string $cacheKey
     *
     * @return array
     * @internal param int $searchRadius
     */
    private function fetchTweets(string $address, array $latLong, string $cacheKey)
    {
        $catchTtl     = $this->config->get('tweet-finder.cache-ttl', 60);
        $searchRadius = $this->config->get('tweet-finder.search-radius', 50);
        $tweetLimit   = $this->config->get('tweet-finder.tweet-limit', 150);

        $rawTweets = $this->twitter->getSearch([
            'q' => $address,
            'geocode' => $this->generateGeocode($latLong, $searchRadius),
            'count' => $tweetLimit,
        ]);
        $result    = $this->processTweets($rawTweets);

        if (!empty($result)) {
            $this->cache->put($cacheKey, $result, $catchTtl);
        }

        return $result;
    }

    /**
     * @param $result
     *
     * @return array
     */
    private function processTweets($result) : array
    {
        $toReturn = [];

        foreach ($result->statuses as $tweet) {
            if (empty($tweet->geo)) {
                continue;
            }

            $toReturn[] = [
                'id' => $tweet->id,
                'text' => $tweet->text,
                'coordinates' => [
                    'lat' => $tweet->geo->coordinates[0],
                    'lng' => $tweet->geo->coordinates[1],
                ],
                'user' => $this->processTwitterUser($tweet->user),
            ];
        }

        return $toReturn;
    }

    /**
     * @param $user
     *
     * @return array
     */
    private function processTwitterUser($user) : array
    {
        $keys = ['id', 'name', 'screen_name', 'profile_image_url'];

        return array_reduce($keys, function ($acc, $key) use ($user) {
            if (!empty($user->{$key})) {
                $acc[$key] = $user->{$key};
            }

            return $acc;
        }, []);
    }

    /**
     * @param array $latLng
     * @param int $searchRadius
     *
     * @return string
     */
    private function generateGeocode(array $latLng, int $searchRadius) : string
    {
        $distanceUnit = $this->config->get('tweet-finder.search-radius-unit', 'km');

        return sprintf('%f,%f,%d%s', $latLng['lat'], $latLng['lng'], $searchRadius, $distanceUnit);
    }
}
