<?php

namespace App\Services;


use App\Repositories\HistoryRepo;
use Illuminate\Cache\Repository as Cache;
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
     * TwitterService constructor.
     *
     * @param Twitter $twitter
     * @param Cache $cache
     * @param HistoryRepo $historyRepo
     */
    public function __construct(Twitter $twitter, Cache $cache, HistoryRepo $historyRepo)
    {
        $this->twitter     = $twitter;
        $this->cache       = $cache;
        $this->historyRepo = $historyRepo;
    }

    /**
     * @param string $address
     * @param array $latLong
     * @param int $searchRadius
     *
     * @return array
     */
    public function getTweetsByLocation(string $address, array $latLong, int $searchRadius) : array
    {
        $cacheKey = str_slug($address);

        $this->historyRepo->createFromParams($cacheKey, $address, $latLong);

        return $this->cache->has($cacheKey) ? $this->cache->get($cacheKey)
            : $this->fetchTweets($address, $latLong, $searchRadius, $cacheKey);
    }

    /**
     * @param string $address
     * @param array $latLong
     * @param int $searchRadius
     * @param string $cacheKey
     *
     * @return array
     */
    private function fetchTweets(string $address, array $latLong, int $searchRadius, string $cacheKey)
    {
        $rawTweets = $this->twitter->getSearch([
            'q' => $address,
            'geocode' => $this->generateGeocode($latLong, $searchRadius),
            'count' => 150,
        ]);
        $result    = $this->processTweets($rawTweets);

        if (!empty($result)) {
            $this->cache->put($cacheKey, $result, 10);
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
     * @param array $latLong
     * @param int $searchRadius
     *
     * @return string
     */
    private function generateGeocode(array $latLng, int $searchRadius) : string
    {
        $distanceUnit = 'km';

        return sprintf('%f,%f,%d%s', $latLng['lat'], $latLng['lng'], $searchRadius, $distanceUnit);
    }
}
