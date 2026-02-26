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
