<?php

declare(strict_types=1);

namespace Centrix\Forge;

enum DitherMethod: string
{
    case None = 'none';
    case FloydSteinberg = 'floyd-steinberg';
    case Atkinson = 'atkinson';
    case Ordered = 'ordered';
}
