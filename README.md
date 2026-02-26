# forge-sdk

PHP SDK for the [Forge](https://github.com/centrixsystems/forge) rendering engine. Converts HTML/CSS to PDF, PNG, and other formats via a running Forge server.

Uses `ext-curl` (no framework dependencies).

## Installation

```sh
composer require centrix/forge-sdk
```

## Quick Start

```php
use Centrix\Forge\{ForgeClient, OutputFormat};

$client = new ForgeClient('http://localhost:3000');

$pdf = $client->renderHtml('<h1>Invoice #1234</h1>')
    ->format(OutputFormat::Pdf)
    ->paper('a4')
    ->send();

file_put_contents('invoice.pdf', $pdf);
```

## Usage

### Render URL to PNG

```php
$png = $client->renderUrl('https://example.com')
    ->format(OutputFormat::Png)
    ->width(1280)
    ->height(800)
    ->send();
```

### Color Quantization

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

### Health Check

```php
$healthy = $client->health();
```

## API Reference

### Enums

- `OutputFormat`: Pdf, Png, Jpeg, Bmp, Tga, Qoi, Svg
- `Orientation`: Portrait, Landscape
- `Flow`: Auto, Paginate, Continuous
- `DitherMethod`: None, FloydSteinberg, Atkinson, Ordered
- `Palette`: Auto, BlackWhite, Grayscale, Eink

### Errors

- `ForgeException` — base exception
- `ForgeServerException` — 4xx/5xx (has `$statusCode`)
- `ForgeConnectionException` — network failures

## Requirements

- PHP 8.1+
- `ext-curl`
- `ext-json`

## License

MIT
