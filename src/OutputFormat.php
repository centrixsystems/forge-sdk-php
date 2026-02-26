<?php

declare(strict_types=1);

namespace Centrix\Forge;

enum OutputFormat: string
{
    case Pdf = 'pdf';
    case Png = 'png';
    case Jpeg = 'jpeg';
    case Bmp = 'bmp';
    case Tga = 'tga';
    case Qoi = 'qoi';
    case Svg = 'svg';
}
