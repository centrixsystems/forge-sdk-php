<?php

declare(strict_types=1);

namespace Centrix\Forge;

/** Relationship of an embedded file to the PDF document. */
enum EmbedRelationship: string
{
    case Alternative = 'alternative';
    case Supplement = 'supplement';
    case Data = 'data';
    case Source = 'source';
    case Unspecified = 'unspecified';
}
