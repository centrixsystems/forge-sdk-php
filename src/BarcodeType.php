<?php

declare(strict_types=1);

namespace Centrix\Forge;

/** Barcode symbology type. */
enum BarcodeType: string
{
    case Qr = 'qr';
    case Code128 = 'code128';
    case Ean13 = 'ean13';
    case UpcA = 'upca';
    case Code39 = 'code39';
}
