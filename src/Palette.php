<?php

declare(strict_types=1);

namespace Centrix\Forge;

enum Palette: string
{
    case Auto = 'auto';
    case BlackWhite = 'bw';
    case Grayscale = 'grayscale';
    case Eink = 'eink';
}
