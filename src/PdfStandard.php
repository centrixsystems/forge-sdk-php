<?php

declare(strict_types=1);

namespace Centrix\Forge;

/** PDF standard compliance level. */
enum PdfStandard: string
{
    case None = 'none';
    case A2B = 'pdf/a-2b';
    case A3B = 'pdf/a-3b';
}
