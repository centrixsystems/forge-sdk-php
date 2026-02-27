<?php

declare(strict_types=1);

namespace Centrix\Forge;

/** PDF rendering mode. */
enum PdfMode: string
{
    case Auto = 'auto';
    case Vector = 'vector';
    case Raster = 'raster';
}
