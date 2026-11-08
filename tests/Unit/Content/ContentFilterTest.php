<?php

namespace Tests\Unit\Content;

use App\Domain\Content\CachingContentFilter;
use App\Domain\Content\ContentFilter;
use App\Domain\Content\ContentFilterChain;
use App\Domain\Content\ContentFilterService;
use App\Domain\Content\KeywordFilter;
use App\Domain\Content\KeywordFilterHandler;
use App\Domain\Content\LoggingContentFilter;
use App\Domain\Content\ReportingContentFilter;
use App\Domain\Content\StatusFilter;
use App\Domain\Content\StatusFilterHandler;
use App\Domain\Content\ViewsFilter;
use App\Domain\Content\ViewsFilterHandler;
use Mockery;
use Psr\Log\LoggerInterface;
use Tests\TestCase;

final class ContentFilterTest extends TestCase
{
    private function createContents(): array
    {
        return [
            [
                'title' => 'Laravel Design Pattern',
                'status' => 'published',
                'views' => 1500,
            ],
            [
                'title' => 'PHP Tutorial',
                'status' => 'draft',
                'views' => 300,
            ],
            [
                'title' => 'Travel Guide',
                'status' => 'published',
                'views' => 800,
            ],
        ];
    }

    public function test_keyword_filter(): void
    {
        $result = (new KeywordFilter())->filter(
            $this->createContents(),
            'Laravel',
        );

        $this->assertCount(1, $result);
        $this->assertSame('Laravel Design Pattern', $result[0]['title']);
    }

    public function test_status_filter(): void
    {
        $result = (new StatusFilter())->filter(
            $this->createContents(),
            'published',
        );

        $this->assertCount(2, $result);
    }

    public function test_views_filter(): void
    {
        $result = (new ViewsFilter())->filter(
            $this->createContents(),
            1000,
        );

        $this->assertCount(1, $result);
        $this->assertSame(1500, $result[0]['views']);
    }

    public function test_can_apply_multiple_filters_in_sequence(): void
    {
        $contents = $this->createContents();

        $keyword = new KeywordFilterHandler(new KeywordFilter());
        $status = new StatusFilterHandler(new StatusFilter());
        $views = new ViewsFilterHandler(new ViewsFilter());

        $keyword->setNext($status)->setNext($views);

        $chain = new ContentFilterChain($keyword);

        $result = $chain->handle(
            new ContentFilter(
                $contents,
                [
                    'keyword' => 'Laravel',
                    'status' => 'published',
                    'min_views' => 1000,
                ],
            ),
        );

        $this->assertCount(1, $result);
        $this->assertSame('Laravel Design Pattern', $result[0]['title']);
    }

    public function test_logging_decorator_logs_filtering(): void
    {
        $contents = $this->createContents();
        $filters = ['status' => 'published'];

        $service = Mockery::mock(ContentFilterService::class);

        $service
            ->shouldReceive('filter')
            ->once()
            ->with($contents, $filters)
            ->andReturn($contents);

        $logger = Mockery::mock(LoggerInterface::class);

        $logger
            ->shouldReceive('info')
            ->twice();

        $decorator = new LoggingContentFilter($service, $logger);

        $result = $decorator->filter($contents, $filters);

        $this->assertSame($contents, $result);
    }


    public function test_reporting_decorator_returns_filtered_content(): void
    {
        $contents = $this->createContents();
        $filters = ['status' => 'published'];

        $service = Mockery::mock(ContentFilterService::class);

        $service
            ->shouldReceive('filter')
            ->once()
            ->andReturn($contents);

        $decorator = new ReportingContentFilter($service);

        $result = $decorator->filter($contents, $filters);

        $this->assertSame($contents, $result);
    }

    public function test_caching_decorator_caches_filter_result(): void
    {
        $contents = $this->createContents();
        $filters = ['status' => 'published'];

        $service = Mockery::mock(ContentFilterService::class);

        $service
            ->shouldReceive('filter')
            ->once()
            ->with($contents, $filters)
            ->andReturn($contents);

        $decorator = new CachingContentFilter($service);

        $firstResult = $decorator->filter($contents, $filters);
        $secondResult = $decorator->filter($contents, $filters);

        $this->assertSame($firstResult, $secondResult);
    }
}
