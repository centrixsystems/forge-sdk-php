<?php

declare(strict_types=1);

namespace Centrix\Forge;

/** Builder for a render request. */
class RenderRequestBuilder
{
    private ForgeClient $client;
    private ?string $html;
    private ?string $url;
    private OutputFormat $format = OutputFormat::Pdf;
    private ?int $width = null;
    private ?int $height = null;
    private ?string $paper = null;
    private ?Orientation $orientation = null;
    private ?string $margins = null;
    private ?Flow $flow = null;
    private ?float $density = null;
    private ?string $background = null;
    private ?int $timeout = null;
    private ?int $colors = null;
    private Palette|array|null $palette = null;
    private ?DitherMethod $dither = null;
    private ?string $pdfTitle = null;
    private ?string $pdfAuthor = null;
    private ?string $pdfSubject = null;
    private ?string $pdfKeywords = null;
    private ?string $pdfCreator = null;
    private ?bool $pdfBookmarks = null;
    private ?string $pdfWatermarkText = null;
    private ?string $pdfWatermarkImage = null; // base64-encoded
    private ?float $pdfWatermarkOpacity = null;
    private ?float $pdfWatermarkRotation = null;
    private ?string $pdfWatermarkColor = null;
    private ?float $pdfWatermarkFontSize = null;
    private ?float $pdfWatermarkScale = null;
    private ?WatermarkLayer $pdfWatermarkLayer = null;

    public function __construct(
        ForgeClient $client,
        ?string $html = null,
        ?string $url = null,
    ) {
        $this->client = $client;
        $this->html = $html;
        $this->url = $url;
    }

    public function format(OutputFormat $f): static { $this->format = $f; return $this; }
    public function width(int $px): static { $this->width = $px; return $this; }
    public function height(int $px): static { $this->height = $px; return $this; }
    public function paper(string $size): static { $this->paper = $size; return $this; }
    public function orientation(Orientation $o): static { $this->orientation = $o; return $this; }
    public function margins(string $m): static { $this->margins = $m; return $this; }
    public function flow(Flow $f): static { $this->flow = $f; return $this; }
    public function density(float $dpi): static { $this->density = $dpi; return $this; }
    public function background(string $color): static { $this->background = $color; return $this; }
    public function timeout(int $seconds): static { $this->timeout = $seconds; return $this; }
    public function colors(int $n): static { $this->colors = $n; return $this; }
    public function palette(Palette|array $p): static { $this->palette = $p; return $this; }
    public function dither(DitherMethod $method): static { $this->dither = $method; return $this; }
    public function pdfTitle(string $title): static { $this->pdfTitle = $title; return $this; }
    public function pdfAuthor(string $author): static { $this->pdfAuthor = $author; return $this; }
    public function pdfSubject(string $subject): static { $this->pdfSubject = $subject; return $this; }
    public function pdfKeywords(string $keywords): static { $this->pdfKeywords = $keywords; return $this; }
    public function pdfCreator(string $creator): static { $this->pdfCreator = $creator; return $this; }
    public function pdfBookmarks(bool $bookmarks): static { $this->pdfBookmarks = $bookmarks; return $this; }
    public function pdfWatermarkText(string $text): static { $this->pdfWatermarkText = $text; return $this; }
    public function pdfWatermarkImage(string $base64Data): static { $this->pdfWatermarkImage = $base64Data; return $this; }
    public function pdfWatermarkOpacity(float $opacity): static { $this->pdfWatermarkOpacity = $opacity; return $this; }
    public function pdfWatermarkRotation(float $degrees): static { $this->pdfWatermarkRotation = $degrees; return $this; }
    public function pdfWatermarkColor(string $hex): static { $this->pdfWatermarkColor = $hex; return $this; }
    public function pdfWatermarkFontSize(float $size): static { $this->pdfWatermarkFontSize = $size; return $this; }
    public function pdfWatermarkScale(float $scale): static { $this->pdfWatermarkScale = $scale; return $this; }
    public function pdfWatermarkLayer(WatermarkLayer $layer): static { $this->pdfWatermarkLayer = $layer; return $this; }

    /** Build the payload array. */
    public function buildPayload(): array
    {
        $payload = ['format' => $this->format->value];

        if ($this->html !== null) $payload['html'] = $this->html;
        if ($this->url !== null) $payload['url'] = $this->url;
        if ($this->width !== null) $payload['width'] = $this->width;
        if ($this->height !== null) $payload['height'] = $this->height;
        if ($this->paper !== null) $payload['paper'] = $this->paper;
        if ($this->orientation !== null) $payload['orientation'] = $this->orientation->value;
        if ($this->margins !== null) $payload['margins'] = $this->margins;
        if ($this->flow !== null) $payload['flow'] = $this->flow->value;
        if ($this->density !== null) $payload['density'] = $this->density;
        if ($this->background !== null) $payload['background'] = $this->background;
        if ($this->timeout !== null) $payload['timeout'] = $this->timeout;

        if ($this->colors !== null || $this->palette !== null || $this->dither !== null) {
            $q = [];
            if ($this->colors !== null) $q['colors'] = $this->colors;
            if ($this->palette instanceof Palette) {
                $q['palette'] = $this->palette->value;
            } elseif (is_array($this->palette)) {
                $q['palette'] = $this->palette;
            }
            if ($this->dither !== null) $q['dither'] = $this->dither->value;
            $payload['quantize'] = $q;
        }

        if ($this->pdfTitle !== null || $this->pdfAuthor !== null || $this->pdfSubject !== null
            || $this->pdfKeywords !== null || $this->pdfCreator !== null || $this->pdfBookmarks !== null
            || $this->pdfWatermarkText !== null || $this->pdfWatermarkImage !== null
            || $this->pdfWatermarkOpacity !== null || $this->pdfWatermarkRotation !== null
            || $this->pdfWatermarkColor !== null || $this->pdfWatermarkFontSize !== null
            || $this->pdfWatermarkScale !== null || $this->pdfWatermarkLayer !== null) {
            $p = [];
            if ($this->pdfTitle !== null) $p['title'] = $this->pdfTitle;
            if ($this->pdfAuthor !== null) $p['author'] = $this->pdfAuthor;
            if ($this->pdfSubject !== null) $p['subject'] = $this->pdfSubject;
            if ($this->pdfKeywords !== null) $p['keywords'] = $this->pdfKeywords;
            if ($this->pdfCreator !== null) $p['creator'] = $this->pdfCreator;
            if ($this->pdfBookmarks !== null) $p['bookmarks'] = $this->pdfBookmarks;
            if ($this->pdfWatermarkText !== null || $this->pdfWatermarkImage !== null
                || $this->pdfWatermarkOpacity !== null || $this->pdfWatermarkRotation !== null
                || $this->pdfWatermarkColor !== null || $this->pdfWatermarkFontSize !== null
                || $this->pdfWatermarkScale !== null || $this->pdfWatermarkLayer !== null) {
                $wm = [];
                if ($this->pdfWatermarkText !== null) $wm['text'] = $this->pdfWatermarkText;
                if ($this->pdfWatermarkImage !== null) $wm['image_data'] = $this->pdfWatermarkImage;
                if ($this->pdfWatermarkOpacity !== null) $wm['opacity'] = $this->pdfWatermarkOpacity;
                if ($this->pdfWatermarkRotation !== null) $wm['rotation'] = $this->pdfWatermarkRotation;
                if ($this->pdfWatermarkColor !== null) $wm['color'] = $this->pdfWatermarkColor;
                if ($this->pdfWatermarkFontSize !== null) $wm['font_size'] = $this->pdfWatermarkFontSize;
                if ($this->pdfWatermarkScale !== null) $wm['scale'] = $this->pdfWatermarkScale;
                if ($this->pdfWatermarkLayer !== null) $wm['layer'] = $this->pdfWatermarkLayer->value;
                $p['watermark'] = $wm;
            }
            $payload['pdf'] = $p;
        }

        return $payload;
    }

    /** Send the render request. */
    public function send(): string
    {
        return $this->client->sendRender($this->buildPayload());
    }
}
