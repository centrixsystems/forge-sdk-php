<?php

declare(strict_types=1);

namespace Centrix\Forge\Tests;

use Centrix\Forge\{
    DitherMethod,
    Flow,
    ForgeClient,
    Orientation,
    OutputFormat,
    Palette,
};
use PHPUnit\Framework\TestCase;

class ForgeClientTest extends TestCase
{
    private ForgeClient $client;

    protected function setUp(): void
    {
        $this->client = new ForgeClient('http://localhost:3000');
    }

    public function testMinimalHtmlPayload(): void
    {
        $payload = $this->client->renderHtml('<h1>Hi</h1>')->buildPayload();

        $this->assertSame('<h1>Hi</h1>', $payload['html']);
        $this->assertSame('pdf', $payload['format']);
        $this->assertArrayNotHasKey('url', $payload);
        $this->assertArrayNotHasKey('quantize', $payload);
    }

    public function testUrlPayloadWithOptions(): void
    {
        $payload = $this->client->renderUrl('https://example.com')
            ->format(OutputFormat::Png)
            ->width(1280)
            ->height(800)
            ->paper('letter')
            ->orientation(Orientation::Landscape)
            ->margins('10,20,10,20')
            ->flow(Flow::Paginate)
            ->density(300.0)
            ->background('#ffffff')
            ->timeout(60)
            ->buildPayload();

        $this->assertArrayNotHasKey('html', $payload);
        $this->assertSame('https://example.com', $payload['url']);
        $this->assertSame('png', $payload['format']);
        $this->assertSame(1280, $payload['width']);
        $this->assertSame(800, $payload['height']);
        $this->assertSame('letter', $payload['paper']);
        $this->assertSame('landscape', $payload['orientation']);
        $this->assertSame('paginate', $payload['flow']);
        $this->assertArrayNotHasKey('quantize', $payload);
    }

    public function testQuantizePayload(): void
    {
        $payload = $this->client->renderHtml('<p>test</p>')
            ->format(OutputFormat::Png)
            ->colors(16)
            ->palette(Palette::Auto)
            ->dither(DitherMethod::FloydSteinberg)
            ->buildPayload();

        $q = $payload['quantize'];
        $this->assertSame(16, $q['colors']);
        $this->assertSame('auto', $q['palette']);
        $this->assertSame('floyd-steinberg', $q['dither']);
    }

    public function testCustomPalette(): void
    {
        $payload = $this->client->renderHtml('<p>test</p>')
            ->palette(['#000000', '#ffffff', '#ff0000'])
            ->dither(DitherMethod::Atkinson)
            ->buildPayload();

        $q = $payload['quantize'];
        $this->assertSame(['#000000', '#ffffff', '#ff0000'], $q['palette']);
        $this->assertSame('atkinson', $q['dither']);
    }

    public function testNoQuantizeWhenUnset(): void
    {
        $payload = $this->client->renderHtml('<p>test</p>')
            ->format(OutputFormat::Png)
            ->buildPayload();

        $this->assertArrayNotHasKey('quantize', $payload);
    }

    public function testPdfOptionsPayload(): void
    {
        $payload = $this->client->renderHtml('<h1>Report</h1>')
            ->format(OutputFormat::Pdf)
            ->pdfTitle('Annual Report')
            ->pdfAuthor('Jane Doe')
            ->pdfSubject('Financials')
            ->pdfKeywords('finance,annual,report')
            ->pdfCreator('Centrix ERP')
            ->pdfBookmarks(true)
            ->buildPayload();

        $pdf = $payload['pdf'];
        $this->assertSame('Annual Report', $pdf['title']);
        $this->assertSame('Jane Doe', $pdf['author']);
        $this->assertSame('Financials', $pdf['subject']);
        $this->assertSame('finance,annual,report', $pdf['keywords']);
        $this->assertSame('Centrix ERP', $pdf['creator']);
        $this->assertTrue($pdf['bookmarks']);
    }

    public function testPdfOptionsPartial(): void
    {
        $payload = $this->client->renderHtml('<p>test</p>')
            ->pdfTitle('Title Only')
            ->buildPayload();

        $pdf = $payload['pdf'];
        $this->assertSame('Title Only', $pdf['title']);
        $this->assertArrayNotHasKey('author', $pdf);
        $this->assertArrayNotHasKey('subject', $pdf);
        $this->assertArrayNotHasKey('keywords', $pdf);
        $this->assertArrayNotHasKey('creator', $pdf);
        $this->assertArrayNotHasKey('bookmarks', $pdf);
    }

    public function testNoPdfWhenUnset(): void
    {
        $payload = $this->client->renderHtml('<p>test</p>')
            ->format(OutputFormat::Pdf)
            ->buildPayload();

        $this->assertArrayNotHasKey('pdf', $payload);
    }
}
