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
    private ?bool $pdfPageNumbers = null;
    private ?string $pdfWatermarkText = null;
    private ?string $pdfWatermarkImage = null; // base64-encoded
    private ?float $pdfWatermarkOpacity = null;
    private ?float $pdfWatermarkRotation = null;
    private ?string $pdfWatermarkColor = null;
    private ?float $pdfWatermarkFontSize = null;
    private ?float $pdfWatermarkScale = null;
    private ?WatermarkLayer $pdfWatermarkLayer = null;
    private ?string $pdfWatermarkPages = null;
    private ?PdfStandard $pdfStandard = null;
    private ?array $pdfEmbeddedFiles = null;
    private ?array $pdfBarcodes = null;
    private ?string $pdfMode = null;
    private ?string $pdfSignCertificate = null;
    private ?string $pdfSignPassword = null;
    private ?string $pdfSignName = null;
    private ?string $pdfSignReason = null;
    private ?string $pdfSignLocation = null;
    private ?string $pdfSignTimestampUrl = null;
    private ?string $pdfUserPassword = null;
    private ?string $pdfOwnerPassword = null;
    private ?string $pdfPermissions = null;
    private ?string $pdfAccessibility = null;
    private ?bool $pdfLinearize = null;

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
    public function pdfPageNumbers(bool $pageNumbers): static { $this->pdfPageNumbers = $pageNumbers; return $this; }
    public function pdfWatermarkText(string $text): static { $this->pdfWatermarkText = $text; return $this; }
    public function pdfWatermarkImage(string $base64Data): static { $this->pdfWatermarkImage = $base64Data; return $this; }
    public function pdfWatermarkOpacity(float $opacity): static { $this->pdfWatermarkOpacity = $opacity; return $this; }
    public function pdfWatermarkRotation(float $degrees): static { $this->pdfWatermarkRotation = $degrees; return $this; }
    public function pdfWatermarkColor(string $hex): static { $this->pdfWatermarkColor = $hex; return $this; }
    public function pdfWatermarkFontSize(float $size): static { $this->pdfWatermarkFontSize = $size; return $this; }
    public function pdfWatermarkScale(float $scale): static { $this->pdfWatermarkScale = $scale; return $this; }
    public function pdfWatermarkLayer(WatermarkLayer $layer): static { $this->pdfWatermarkLayer = $layer; return $this; }
    public function pdfWatermarkPages(string $pages): static { $this->pdfWatermarkPages = $pages; return $this; }
    public function pdfStandard(PdfStandard $standard): static { $this->pdfStandard = $standard; return $this; }
    public function pdfAttach(string $path, string $base64Data, ?string $mimeType = null, ?string $description = null, ?EmbedRelationship $relationship = null): static
    {
        $this->pdfEmbeddedFiles ??= [];
        $this->pdfEmbeddedFiles[] = ['path' => $path, 'data' => $base64Data, 'mime_type' => $mimeType, 'description' => $description, 'relationship' => $relationship];
        return $this;
    }
    public function pdfBarcode(
        BarcodeType $type,
        string $data,
        ?float $x = null,
        ?float $y = null,
        ?float $width = null,
        ?float $height = null,
        ?BarcodeAnchor $anchor = null,
        ?string $foreground = null,
        ?string $background = null,
        ?bool $drawBackground = null,
        ?string $pages = null,
    ): static {
        $entry = ['type' => $type->value, 'data' => $data];
        if ($x !== null) $entry['x'] = $x;
        if ($y !== null) $entry['y'] = $y;
        if ($width !== null) $entry['width'] = $width;
        if ($height !== null) $entry['height'] = $height;
        if ($anchor !== null) $entry['anchor'] = $anchor->value;
        if ($foreground !== null) $entry['foreground'] = $foreground;
        if ($background !== null) $entry['background'] = $background;
        if ($drawBackground !== null) $entry['draw_background'] = $drawBackground;
        if ($pages !== null) $entry['pages'] = $pages;
        $this->pdfBarcodes ??= [];
        $this->pdfBarcodes[] = $entry;
        return $this;
    }
    public function pdfMode(PdfMode $mode): static { $this->pdfMode = $mode->value; return $this; }
    public function pdfSignCertificate(string $data): static { $this->pdfSignCertificate = $data; return $this; }
    public function pdfSignPassword(string $password): static { $this->pdfSignPassword = $password; return $this; }
    public function pdfSignName(string $name): static { $this->pdfSignName = $name; return $this; }
    public function pdfSignReason(string $reason): static { $this->pdfSignReason = $reason; return $this; }
    public function pdfSignLocation(string $location): static { $this->pdfSignLocation = $location; return $this; }
    public function pdfSignTimestampUrl(string $url): static { $this->pdfSignTimestampUrl = $url; return $this; }
    public function pdfUserPassword(string $password): static { $this->pdfUserPassword = $password; return $this; }
    public function pdfOwnerPassword(string $password): static { $this->pdfOwnerPassword = $password; return $this; }
    public function pdfPermissions(string $permissions): static { $this->pdfPermissions = $permissions; return $this; }
    public function pdfAccessibility(AccessibilityLevel $level): static { $this->pdfAccessibility = $level->value; return $this; }
    public function pdfLinearize(bool $linearize): static { $this->pdfLinearize = $linearize; return $this; }

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
            || $this->pdfPageNumbers !== null
            || $this->pdfWatermarkText !== null || $this->pdfWatermarkImage !== null
            || $this->pdfWatermarkOpacity !== null || $this->pdfWatermarkRotation !== null
            || $this->pdfWatermarkColor !== null || $this->pdfWatermarkFontSize !== null
            || $this->pdfWatermarkScale !== null || $this->pdfWatermarkLayer !== null
            || $this->pdfWatermarkPages !== null
            || $this->pdfStandard !== null || $this->pdfEmbeddedFiles !== null
            || $this->pdfBarcodes !== null
            || $this->pdfMode !== null || $this->pdfSignCertificate !== null
            || $this->pdfUserPassword !== null || $this->pdfOwnerPassword !== null
            || $this->pdfAccessibility !== null || $this->pdfLinearize !== null) {
            $p = [];
            if ($this->pdfTitle !== null) $p['title'] = $this->pdfTitle;
            if ($this->pdfAuthor !== null) $p['author'] = $this->pdfAuthor;
            if ($this->pdfSubject !== null) $p['subject'] = $this->pdfSubject;
            if ($this->pdfKeywords !== null) $p['keywords'] = $this->pdfKeywords;
            if ($this->pdfCreator !== null) $p['creator'] = $this->pdfCreator;
            if ($this->pdfBookmarks !== null) $p['bookmarks'] = $this->pdfBookmarks;
            if ($this->pdfPageNumbers !== null) $p['page_numbers'] = $this->pdfPageNumbers;
            if ($this->pdfStandard !== null) $p['standard'] = $this->pdfStandard->value;
            if ($this->pdfWatermarkText !== null || $this->pdfWatermarkImage !== null
                || $this->pdfWatermarkOpacity !== null || $this->pdfWatermarkRotation !== null
                || $this->pdfWatermarkColor !== null || $this->pdfWatermarkFontSize !== null
                || $this->pdfWatermarkScale !== null || $this->pdfWatermarkLayer !== null
                || $this->pdfWatermarkPages !== null) {
                $wm = [];
                if ($this->pdfWatermarkText !== null) $wm['text'] = $this->pdfWatermarkText;
                if ($this->pdfWatermarkImage !== null) $wm['image_data'] = $this->pdfWatermarkImage;
                if ($this->pdfWatermarkOpacity !== null) $wm['opacity'] = $this->pdfWatermarkOpacity;
                if ($this->pdfWatermarkRotation !== null) $wm['rotation'] = $this->pdfWatermarkRotation;
                if ($this->pdfWatermarkColor !== null) $wm['color'] = $this->pdfWatermarkColor;
                if ($this->pdfWatermarkFontSize !== null) $wm['font_size'] = $this->pdfWatermarkFontSize;
                if ($this->pdfWatermarkScale !== null) $wm['scale'] = $this->pdfWatermarkScale;
                if ($this->pdfWatermarkLayer !== null) $wm['layer'] = $this->pdfWatermarkLayer->value;
                if ($this->pdfWatermarkPages !== null) $wm['pages'] = $this->pdfWatermarkPages;
                $p['watermark'] = $wm;
            }
            if ($this->pdfEmbeddedFiles !== null) {
                $files = [];
                foreach ($this->pdfEmbeddedFiles as $ef) {
                    $e = ['path' => $ef['path'], 'data' => $ef['data']];
                    if ($ef['mime_type'] !== null) $e['mime_type'] = $ef['mime_type'];
                    if ($ef['description'] !== null) $e['description'] = $ef['description'];
                    if ($ef['relationship'] !== null) $e['relationship'] = $ef['relationship']->value;
                    $files[] = $e;
                }
                $p['embedded_files'] = $files;
            }
            if ($this->pdfBarcodes !== null) {
                $p['barcodes'] = $this->pdfBarcodes;
            }
            if ($this->pdfMode !== null) $p['mode'] = $this->pdfMode;
            if ($this->pdfSignCertificate !== null) {
                $sig = ['certificate_data' => $this->pdfSignCertificate];
                if ($this->pdfSignPassword !== null) $sig['password'] = $this->pdfSignPassword;
                if ($this->pdfSignName !== null) $sig['signer_name'] = $this->pdfSignName;
                if ($this->pdfSignReason !== null) $sig['reason'] = $this->pdfSignReason;
                if ($this->pdfSignLocation !== null) $sig['location'] = $this->pdfSignLocation;
                if ($this->pdfSignTimestampUrl !== null) $sig['timestamp_url'] = $this->pdfSignTimestampUrl;
                $p['signature'] = $sig;
            }
            if ($this->pdfUserPassword !== null || $this->pdfOwnerPassword !== null || $this->pdfPermissions !== null) {
                $enc = [];
                if ($this->pdfUserPassword !== null) $enc['user_password'] = $this->pdfUserPassword;
                if ($this->pdfOwnerPassword !== null) $enc['owner_password'] = $this->pdfOwnerPassword;
                if ($this->pdfPermissions !== null) $enc['permissions'] = $this->pdfPermissions;
                $p['encryption'] = $enc;
            }
            if ($this->pdfAccessibility !== null) $p['accessibility'] = $this->pdfAccessibility;
            if ($this->pdfLinearize !== null) $p['linearize'] = $this->pdfLinearize;
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
