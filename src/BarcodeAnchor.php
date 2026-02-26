<?php

declare(strict_types=1);

namespace Centrix\Forge;

/** Barcode anchor position on the page. */
enum BarcodeAnchor: string
{
    case TopLeft = 'top-left';
    case TopRight = 'top-right';
    case BottomLeft = 'bottom-left';
    case BottomRight = 'bottom-right';
}
