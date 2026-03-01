<?php

declare(strict_types=1);

namespace Centrix\Forge;

/** Barcode symbology type. */
enum BarcodeType: string
{
    // 2D types
    case Qr = 'qr';
    case DataMatrix = 'datamatrix';
    case Pdf417 = 'pdf417';
    case Aztec = 'aztec';
    // 1D types
    case Code128 = 'code128';
    case Ean13 = 'ean13';
    case Ean8 = 'ean8';
    case UpcA = 'upca';
    case Code39 = 'code39';
    case Code93 = 'code93';
    case Codabar = 'codabar';
    case Itf = 'itf';
    case Code11 = 'code11';
}
