<?php

declare(strict_types=1);

namespace Centrix\Forge;

enum Flow: string
{
    case Auto = 'auto';
    case Paginate = 'paginate';
    case Continuous = 'continuous';
}
