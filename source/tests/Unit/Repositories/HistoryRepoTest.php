<?php

namespace Tests\Unit\Repositories;


use App\Models\History;
use App\Repositories\HistoryRepo;
use Illuminate\Config\Repository;
use Mockery as m;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Repositories\HistoryRepo
 */
class HistoryRepoTest extends TestCase
{
    private $configMock;
    private $historyMock;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();

        $this->historyMock = m::mock(History::class)->shouldDeferMissing();
        $this->configMock  = m::mock(Repository::class);
    }

    /**
     * @test
     * @covers ::create
     */
    public function it_should_be_able_to_create_history()
    {
        $historyRepo = new HistoryRepo($this->historyMock, $this->configMock);

        $this->historyMock
            ->shouldReceive('firstOrNew')
            ->once()
            ->with(['key' => 'grand-palace'])
            ->andReturnSelf();

        $this->historyMock
            ->shouldReceive('setAttribute')
            ->once()
            ->with('payload', [
                'address' => 'Grand Palace',
                'lat' => 12,
                'lng' => 23,
            ]);

        $this->historyMock
            ->shouldReceive('save')
            ->once();

        $this->assertEquals(
            $this->historyMock,
            $historyRepo->create(
                'grand-palace',
                'Grand Palace', 
                ['lat' => 12, 'lng' => 23]
            )
        );
    }
}
