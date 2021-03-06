<?php

namespace Tests\Integration\Repositories;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use LaravelItalia\Domain\Repositories\SeriesRepository;
use Tests\Integration\Repositories\Support\EntitiesPreparer;

class SeriesRepositoryTest extends TestCase
{
    use DatabaseMigrations, EntitiesPreparer;

    /**
     * @var SeriesRepository
     */
    private $repository;

    public function setUp()
    {
        $this->repository = new SeriesRepository();
        parent::setUp();
    }

    public function testCanGetAll()
    {
        $this->assertEmpty($this->repository->getAll());

        $this->saveTestSeries();
        $this->saveTestSeries(true);

        $this->assertCount(2, $this->repository->getAll());
        $this->assertCount(1, $this->repository->getAll(true));
    }

    public function testCanFindById()
    {
        $this->saveTestSeries();

        $existingSeries = $this->repository->findByid(1);

        $this->assertEquals('Title', $existingSeries->title);
    }

    /**
     * @expectedException \LaravelItalia\Exceptions\NotFoundException
     */
    public function testCanFindByIdThrowsException()
    {
        $this->repository->findById(999);
    }

    public function testCanFindBySlug()
    {
        $this->saveTestSeries();

        $existingSeries = $this->repository->findBySlug('title');

        $this->assertEquals('Title', $existingSeries->title);
    }

    /**
     * @expectedException \LaravelItalia\Exceptions\NotFoundException
     */
    public function testCanFindBySlugThrowsException()
    {
        $this->repository->findBySlug('i-did-it-for-teh-lulz');
    }

    /**
     * @expectedException \LaravelItalia\Exceptions\NotFoundException
     */
    public function testCanFindBySlugThrowsException2()
    {
        $this->saveTestSeries();

        $this->repository->findBySlug('title', true);
    }

    public function testCanSave()
    {
        $this->repository->save($this->prepareTestSeries());

        $this->seeInDatabase('series', [
            'title' => 'Title',
            'slug' => 'title',
        ]);
    }

    public function testCanDelete()
    {
        $series = $this->saveTestSeries();

        $this->repository->delete($series);

        $this->dontSeeInDatabase('series', [
            'title' => 'Title',
            'slug' => 'title',
        ]);
    }
}
