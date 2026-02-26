<?php

declare(strict_types=1);

namespace Centrix\Forge\Tests;

use Centrix\Forge\{
    BarcodeAnchor,
    BarcodeType,
    DitherMethod,
    Flow,
    ForgeClient,
    Orientation,
    OutputFormat,
    Palette,
    WatermarkLayer,
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

    public function testWatermarkPagesPayload(): void
    {
        $payload = $this->client->renderHtml('<h1>Report</h1>')
            ->pdfWatermarkText('DRAFT')
            ->pdfWatermarkPages('1,3-5')
            ->buildPayload();

        $wm = $payload['pdf']['watermark'];
        $this->assertSame('DRAFT', $wm['text']);
        $this->assertSame('1,3-5', $wm['pages']);
    }

    public function testWatermarkPagesOnly(): void
    {
        $payload = $this->client->renderHtml('<p>test</p>')
            ->pdfWatermarkPages('2-4')
            ->buildPayload();

        $this->assertArrayHasKey('pdf', $payload);
        $wm = $payload['pdf']['watermark'];
        $this->assertSame('2-4', $wm['pages']);
        $this->assertArrayNotHasKey('text', $wm);
    }

    public function testSingleBarcodeMinimal(): void
    {
        $payload = $this->client->renderHtml('<p>test</p>')
            ->pdfBarcode(BarcodeType::Qr, 'https://example.com')
            ->buildPayload();

        $barcodes = $payload['pdf']['barcodes'];
        $this->assertCount(1, $barcodes);
        $this->assertSame('qr', $barcodes[0]['type']);
        $this->assertSame('https://example.com', $barcodes[0]['data']);
        $this->assertArrayNotHasKey('x', $barcodes[0]);
        $this->assertArrayNotHasKey('anchor', $barcodes[0]);
    }

    public function testSingleBarcodeAllOptions(): void
    {
        $payload = $this->client->renderHtml('<p>test</p>')
            ->pdfBarcode(
                type: BarcodeType::Code128,
                data: 'ABC-123',
                x: 10.5,
                y: 20.0,
                width: 200.0,
                height: 80.0,
                anchor: BarcodeAnchor::BottomRight,
                foreground: '#000000',
                background: '#ffffff',
                drawBackground: true,
                pages: '1,3',
            )
            ->buildPayload();

        $bc = $payload['pdf']['barcodes'][0];
        $this->assertSame('code128', $bc['type']);
        $this->assertSame('ABC-123', $bc['data']);
        $this->assertSame(10.5, $bc['x']);
        $this->assertSame(20.0, $bc['y']);
        $this->assertSame(200.0, $bc['width']);
        $this->assertSame(80.0, $bc['height']);
        $this->assertSame('bottom-right', $bc['anchor']);
        $this->assertSame('#000000', $bc['foreground']);
        $this->assertSame('#ffffff', $bc['background']);
        $this->assertTrue($bc['draw_background']);
        $this->assertSame('1,3', $bc['pages']);
    }

    public function testMultipleBarcodes(): void
    {
        $payload = $this->client->renderHtml('<p>test</p>')
            ->pdfBarcode(BarcodeType::Qr, 'qr-data', anchor: BarcodeAnchor::TopLeft)
            ->pdfBarcode(BarcodeType::Ean13, '5901234123457', anchor: BarcodeAnchor::BottomLeft)
            ->pdfBarcode(BarcodeType::Code39, 'CODE39', pages: '2')
            ->buildPayload();

        $barcodes = $payload['pdf']['barcodes'];
        $this->assertCount(3, $barcodes);
        $this->assertSame('qr', $barcodes[0]['type']);
        $this->assertSame('top-left', $barcodes[0]['anchor']);
        $this->assertSame('ean13', $barcodes[1]['type']);
        $this->assertSame('bottom-left', $barcodes[1]['anchor']);
        $this->assertSame('code39', $barcodes[2]['type']);
        $this->assertSame('2', $barcodes[2]['pages']);
    }

    public function testBarcodeWithWatermarkAndMetadata(): void
    {
        $payload = $this->client->renderHtml('<h1>Invoice</h1>')
            ->pdfTitle('Invoice #42')
            ->pdfWatermarkText('PAID')
            ->pdfWatermarkLayer(WatermarkLayer::Over)
            ->pdfWatermarkPages('1')
            ->pdfBarcode(BarcodeType::Qr, 'INV-42', anchor: BarcodeAnchor::TopRight)
            ->buildPayload();

        $pdf = $payload['pdf'];
        $this->assertSame('Invoice #42', $pdf['title']);
        $this->assertSame('PAID', $pdf['watermark']['text']);
        $this->assertSame('over', $pdf['watermark']['layer']);
        $this->assertSame('1', $pdf['watermark']['pages']);
        $this->assertCount(1, $pdf['barcodes']);
        $this->assertSame('qr', $pdf['barcodes'][0]['type']);
        $this->assertSame('top-right', $pdf['barcodes'][0]['anchor']);
    }

    public function testBarcodeOnlyTriggersPdfSection(): void
    {
        $payload = $this->client->renderHtml('<p>test</p>')
            ->pdfBarcode(BarcodeType::UpcA, '012345678905')
            ->buildPayload();

        $this->assertArrayHasKey('pdf', $payload);
        $this->assertArrayHasKey('barcodes', $payload['pdf']);
        $this->assertArrayNotHasKey('watermark', $payload['pdf']);
        $this->assertArrayNotHasKey('title', $payload['pdf']);
        $this->assertSame('upca', $payload['pdf']['barcodes'][0]['type']);
    }

    public function testBarcodeDrawBackgroundFalse(): void
    {
        $payload = $this->client->renderHtml('<p>test</p>')
            ->pdfBarcode(BarcodeType::Qr, 'test', drawBackground: false)
            ->buildPayload();

        $this->assertFalse($payload['pdf']['barcodes'][0]['draw_background']);
    }
}
