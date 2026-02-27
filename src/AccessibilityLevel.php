<?php

declare(strict_types=1);

namespace Centrix\Forge;

/** PDF accessibility conformance level. */
enum AccessibilityLevel: string
{
    case None = 'none';
    case Basic = 'basic';
    case PdfUa1 = 'pdf/ua-1';
}
