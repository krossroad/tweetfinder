<?php

namespace Tests\Unit\Services;


use App\Repositories\HistoryRepo;
use App\Services\TwitterService;
use Illuminate\Cache\Repository;
use Mockery as m;
use Tests\TestCase;
use Thujohn\Twitter\Twitter;

/**
 * @coversDefaultClass \App\Services\TwitterService
 */
class TwitterServiceTest extends TestCase
{
    private $historyRepoMock;
    private $cacheMock;
    private $twitterMock;

    public function setUp()
    {
        parent::setUp();

        $this->twitterMock     = m::mock(Twitter::class);
        $this->cacheMock       = m::mock(Repository::class);
        $this->historyRepoMock = m::mock(HistoryRepo::class);
    }

    /**
     * @test
     * @covers ::getTweetsByLocation
     */
    public function it_should_return_value_from_cache()
    {
        $searchRadius = 50;
        $cacheKey     = 'grand-palace';
        $latLng       = [];
        $address      = 'Grand Palace';

        $tweetService = new TwitterService($this->twitterMock, $this->cacheMock, $this->historyRepoMock);

        $this->history_repo_create_expectation($cacheKey, $address, $latLng);

        $this->cacheMock
            ->shouldReceive('has')
            ->once()
            ->with($cacheKey)
            ->andReturn(true);

        $this->cacheMock
            ->shouldReceive('get')
            ->once()
            ->with($cacheKey)
            ->andReturn(['tweets']);

        $this->assertEquals(['tweets'], $tweetService->getTweetsByLocation($address, $latLng, $searchRadius));
    }

    /**
     * @test
     * @covers ::getTweetsByLocation
     * @dataProvider get_api_fixtures
     *
     * @param $expectedResult
     * @param $apiResult
     */
    public function it_should_hit_tweet_api_if_cache_not_exists($expectedResult, $apiResult)
    {
        $searchRadius = 50;
        $cacheKey     = 'grand-palace';
        $latLng       = ['lat' => 12, 'lng' => 23];
        $address      = 'Grand Palace';
        $apiParams    = [
            'q' => $address,
            'geocode' => '12.000000,23.000000,50km',
            'count' => 150,
        ];

        $tweetService = new TwitterService($this->twitterMock, $this->cacheMock, $this->historyRepoMock);

        $this->cacheMock
            ->shouldReceive('has')
            ->once()
            ->with($cacheKey)
            ->andReturn(false);

        $this->history_repo_create_expectation($cacheKey, $address, $latLng);

        $this->cacheMock
            ->shouldReceive('put')
            ->once()
            ->with($cacheKey, $expectedResult, 10);

        $apiResult = json_decode(json_encode($apiResult));

        $this->twitterMock
            ->shouldReceive('getSearch')
            ->once()
            ->with($apiParams)
            ->andReturn($apiResult);

        $this->assertEquals($expectedResult, $tweetService->getTweetsByLocation($address, $latLng, $searchRadius));
    }

    /**
     * @param $cacheKey
     * @param $address
     * @param $latLng
     */
    private function history_repo_create_expectation($cacheKey, $address, $latLng)
    {
        $this->historyRepoMock
            ->shouldReceive('create')
            ->once()
            ->with($cacheKey, $address, $latLng);
    }

    /**
     * @return array
     */
    public function get_api_fixtures()
    {
        return [
            'api_fixture_1' => [
                [
                    [
                        'id' => 123,
                        'text' => 'Foo tweet!!',
                        'coordinates' => [
                            'lat' => 12.3,
                            'lng' => 23.3,
                        ],
                        'user' => [
                            'screen_name' => 'foo_user',
                            'profile_image_url' => 'http://img/url',
                        ],
                    ],
                ],
                [
                    'statuses' => [
                        [
                            'id' => 123,
                            'text' => 'Foo tweet!!',
                            'geo' => [
                                'coordinates' => [
                                    12.3,
                                    23.3,
                                ],
                            ],
                            'user' => [
                                'screen_name' => 'foo_user',
                                'profile_image_url' => 'http://img/url',
                            ],
                        ],
                    ],
                ]
            ],
        ];
    }
}
