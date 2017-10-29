<?php

namespace Tests\Unit\Services;


use App\Services\TwitterService;
use Tests\TestCase;

class TweetServiceTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_return_empty_on_empty_query_values()
    {
        $tweetService = new TwitterService();

        $this->assertEquals([], $tweetService->getTweets(''));
    }


}
