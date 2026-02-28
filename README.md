# forge-sdk

PHP SDK for the [Forge](https://github.com/centrixsystems/forge) rendering engine. Converts HTML/CSS to PDF, PNG, and other formats via a running Forge server.

Uses `ext-curl`. No framework dependencies.

## Installation

```sh
composer require centrix/forge-sdk
```

## Quick Start

```php
<?php

use Centrix\Forge\{ForgeClient, OutputFormat};

$client = new ForgeClient('http://localhost:3000');

$pdf = $client->renderHtml('<h1>Invoice #1234</h1>')
    ->format(OutputFormat::Pdf)
    ->paper('a4')
    ->send();

file_put_contents('invoice.pdf', $pdf);
```

## Usage

### Render HTML to PDF

```php
use Centrix\Forge\{Orientation, Flow};

$pdf = $client->renderHtml('<h1>Hello</h1>')
    ->format(OutputFormat::Pdf)
    ->paper('a4')
    ->orientation(Orientation::Portrait)
    ->margins('25.4,25.4,25.4,25.4')
    ->flow(Flow::Paginate)
    ->send();
```

### Render URL to PNG

```php
$png = $client->renderUrl('https://example.com')
    ->format(OutputFormat::Png)
    ->width(1280)
    ->height(800)
    ->density(2.0)
    ->send();
```

### Color Quantization

Reduce colors for e-ink displays or limited-palette output.

```php
use Centrix\Forge\{Palette, DitherMethod};

$eink = $client->renderHtml('<h1>Dashboard</h1>')
    ->format(OutputFormat::Png)
    ->palette(Palette::Eink)
    ->dither(DitherMethod::FloydSteinberg)
    ->send();
```

### Custom Palette

```php
$img = $client->renderHtml('<h1>Brand</h1>')
    ->format(OutputFormat::Png)
    ->palette(['#000000', '#ffffff', '#ff0000'])
    ->dither(DitherMethod::Atkinson)
    ->send();
```

### PDF Metadata

Set PDF document properties (title, author, etc.) for the generated file.

```php
$pdf = $client->renderHtml('<h1>Annual Report</h1>')
    ->format(OutputFormat::Pdf)
    ->paper('a4')
    ->pdfTitle('Annual Report 2026')
    ->pdfAuthor('Jane Doe')
    ->pdfSubject('Company Financials')
    ->pdfKeywords('finance,annual,report')
    ->pdfCreator('Centrix ERP')
    ->pdfBookmarks(true)
    ->send();
```

### PDF Watermarks

Add text or image watermarks to each page.

```php
use Centrix\Forge\WatermarkLayer;

$pdf = $client->renderHtml('<h1>Draft Report</h1>')
    ->pdfWatermarkText('DRAFT')
    ->pdfWatermarkOpacity(0.15)
    ->pdfWatermarkRotation(-45)
    ->pdfWatermarkColor('#888888')
    ->pdfWatermarkLayer(WatermarkLayer::Over)
    ->send();
```

### PDF Rendering Mode

Control how PDF content is rendered.

```php
use Centrix\Forge\PdfMode;

$pdf = $client->renderHtml('<h1>Vector Report</h1>')
    ->pdfMode(PdfMode::Vector)
    ->send();
```

### PDF Digital Signatures

Digitally sign PDF documents with a PKCS#12 certificate.

```php
$cert = base64_encode(file_get_contents('certificate.p12'));

$pdf = $client->renderHtml('<h1>Signed Contract</h1>')
    ->pdfSignCertificate($cert)
    ->pdfSignPassword('cert-password')
    ->pdfSignName('Jane Doe')
    ->pdfSignReason('Approval')
    ->pdfSignLocation('New York')
    ->pdfSignTimestampUrl('https://tsa.example.com')
    ->send();
```

### PDF Encryption

Password-protect PDF documents and restrict permissions.

```php
$pdf = $client->renderHtml('<h1>Confidential</h1>')
    ->pdfUserPassword('reader-password')
    ->pdfOwnerPassword('admin-password')
    ->pdfPermissions('print,copy')
    ->send();
```

### PDF Accessibility

Generate accessible PDFs conforming to PDF/UA-1.

```php
use Centrix\Forge\AccessibilityLevel;

$pdf = $client->renderHtml('<h1>Accessible Report</h1>')
    ->pdfAccessibility(AccessibilityLevel::PdfUa1)
    ->send();
```

### PDF Linearization

Optimize PDFs for fast web viewing (byte-serving).

```php
$pdf = $client->renderHtml('<h1>Web Report</h1>')
    ->pdfLinearize(true)
    ->send();
```

### PDF/A Archival Output

Generate PDF/A-compliant documents for long-term archiving.

```php
use Centrix\Forge\PdfStandard;

$pdf = $client->renderHtml('<h1>Archival Report</h1>')
    ->pdfStandard(PdfStandard::A2B)
    ->pdfTitle('Archival Report')
    ->send();
```

### Embedded Files (ZUGFeRD/Factur-X)

Attach files to PDF output. Requires PDF/A-3b for embedded file attachments.

```php
use Centrix\Forge\{PdfStandard, EmbedRelationship};

$xmlData = base64_encode(file_get_contents('factur-x.xml'));

$pdf = $client->renderHtml('<h1>Invoice #1234</h1>')
    ->pdfStandard(PdfStandard::A3B)
    ->pdfAttach('factur-x.xml', $xmlData, 'text/xml', 'Factur-X invoice', EmbedRelationship::Alternative)
    ->send();
```

### Custom Timeout

```php
$client = new ForgeClient('http://forge:3000', timeout: 300);
```

### Health Check

```php
$healthy = $client->health();
```

## API Reference

### `ForgeClient`

```php
new ForgeClient(string $baseUrl, int $timeout = 120)
```

| Method | Returns | Description |
|--------|---------|-------------|
| `renderHtml($html)` | `RenderRequestBuilder` | Start a render request from HTML |
| `renderUrl($url)` | `RenderRequestBuilder` | Start a render request from a URL |
| `health()` | `bool` | Check server health |

### `RenderRequestBuilder`

All methods return `static` for chaining. Call `->send()` to execute.

| Method | Type | Description |
|--------|------|-------------|
| `format` | `OutputFormat` | Output format (default: `Pdf`) |
| `width` | `int` | Viewport width in CSS pixels |
| `height` | `int` | Viewport height in CSS pixels |
| `paper` | `string` | Paper size: a3, a4, a5, b4, b5, letter, legal, ledger |
| `orientation` | `Orientation` | `Portrait` or `Landscape` |
| `margins` | `string` | Preset (`default`, `none`, `narrow`) or `"T,R,B,L"` in mm |
| `flow` | `Flow` | `Auto`, `Paginate`, or `Continuous` |
| `density` | `float` | Output DPI (default: 96) |
| `background` | `string` | CSS background color (e.g. `"#ffffff"`) |
| `timeout` | `int` | Page load timeout in seconds |
| `colors` | `int` | Quantization color count (2-256) |
| `palette` | `Palette\|array` | Enum preset or array of hex color strings |
| `dither` | `DitherMethod` | Dithering algorithm |
| `pdfTitle` | `string` | PDF document title |
| `pdfAuthor` | `string` | PDF document author |
| `pdfSubject` | `string` | PDF document subject |
| `pdfKeywords` | `string` | PDF keywords (comma-separated) |
| `pdfCreator` | `string` | PDF creator application name |
| `pdfBookmarks` | `bool` | Generate PDF bookmarks from headings |
| `pdfPageNumbers` | `bool` | Add "Page X of Y" footers to each page |
| `pdfWatermarkText` | `string` | Watermark text on each page |
| `pdfWatermarkImage` | `string` | Base64-encoded PNG/JPEG watermark image |
| `pdfWatermarkOpacity` | `float` | Watermark opacity (0.0-1.0, default: 0.15) |
| `pdfWatermarkRotation` | `float` | Watermark rotation in degrees (default: -45) |
| `pdfWatermarkColor` | `string` | Watermark text color as hex (default: #888888) |
| `pdfWatermarkFontSize` | `float` | Watermark font size in PDF points (default: auto) |
| `pdfWatermarkScale` | `float` | Watermark image scale (0.0-1.0, default: 0.5) |
| `pdfWatermarkLayer` | `WatermarkLayer` | Layer position: `Over` or `Under` |
| `pdfStandard` | `PdfStandard` | PDF standard: `None`, `A2B`, `A3B` |
| `pdfAttach` | `string, string, ...` | Embed file: path, base64 data, mime type, description, relationship |
| `pdfMode` | `PdfMode` | PDF rendering mode: `Auto`, `Vector`, `Raster` |
| `pdfSignCertificate` | `string` | Base64-encoded PKCS#12 certificate for digital signing |
| `pdfSignPassword` | `string` | Password for the signing certificate |
| `pdfSignName` | `string` | Signer display name |
| `pdfSignReason` | `string` | Reason for signing |
| `pdfSignLocation` | `string` | Signing location |
| `pdfSignTimestampUrl` | `string` | RFC 3161 timestamp server URL |
| `pdfUserPassword` | `string` | Password required to open the PDF |
| `pdfOwnerPassword` | `string` | Password for full PDF access |
| `pdfPermissions` | `string` | Comma-separated permissions (e.g. `"print,copy"`) |
| `pdfAccessibility` | `AccessibilityLevel` | Accessibility level: `None`, `Basic`, `PdfUa1` |
| `pdfLinearize` | `bool` | Enable PDF linearization for fast web viewing |
| `pdfLang` | `string` | Document language (BCP 47 tag, e.g. `"en-US"`). Required for PDF/UA-1 |

| Terminal Method | Returns | Description |
|-----------------|---------|-------------|
| `send()` | `string` | Execute the request, returns raw binary output |

### Enums

| Enum | Cases |
|------|-------|
| `OutputFormat` | `Pdf`, `Png`, `Jpeg`, `Bmp`, `Tga`, `Qoi`, `Svg` |
| `Orientation` | `Portrait`, `Landscape` |
| `Flow` | `Auto`, `Paginate`, `Continuous` |
| `DitherMethod` | `None`, `FloydSteinberg`, `Atkinson`, `Ordered` |
| `Palette` | `Auto`, `BlackWhite`, `Grayscale`, `Eink` |
| `WatermarkLayer` | `Over`, `Under` |
| `PdfStandard` | `None`, `A2B`, `A3B` |
| `EmbedRelationship` | `Alternative`, `Supplement`, `Data`, `Source`, `Unspecified` |
| `PdfMode` | `Auto`, `Vector`, `Raster` |
| `AccessibilityLevel` | `None`, `Basic`, `PdfUa1` |

### Exceptions

| Exception | Properties | Description |
|-----------|------------|-------------|
| `ForgeException` | `getMessage()` | Base exception for all SDK errors |
| `ForgeServerException` | `readonly int $statusCode` | Server returned 4xx/5xx |
| `ForgeConnectionException` | `getPrevious()` | Network failure |

## Requirements

- PHP 8.1+
- `ext-curl`
- `ext-json`
- A running [Forge](https://github.com/centrixsystems/forge) server

## License

MIT
