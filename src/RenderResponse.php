<?php

declare(strict_types=1);

namespace Centrix\Forge;

/**
 * Response from a render request, including any CSS compatibility warnings.
 */
class RenderResponse
{
    /** @var string The rendered output bytes (PDF, PNG, etc.). */
    private string $data;

    /** @var string[] CSS compatibility warnings from the Forge server. */
    private array $warnings;

    public function __construct(string $data, array $warnings)
    {
        $this->data = $data;
        $this->warnings = $warnings;
    }

    public function getData(): string
    {
        return $this->data;
    }

    /** @return string[] */
    public function getWarnings(): array
    {
        return $this->warnings;
    }
}
