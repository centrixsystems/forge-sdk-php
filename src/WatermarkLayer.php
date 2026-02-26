<?php

declare(strict_types=1);

namespace Centrix\Forge;

/** Watermark layer position. */
enum WatermarkLayer: string
{
    case Over = 'over';
    case Under = 'under';
}
