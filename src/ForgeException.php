<?php

declare(strict_types=1);

namespace Centrix\Forge;

/** Base exception for the Forge SDK. */
class ForgeException extends \RuntimeException
{
}

/** The server returned a 4xx/5xx response. */
class ForgeServerException extends ForgeException
{
    public function __construct(
        public readonly int $statusCode,
        string $message,
    ) {
        parent::__construct("server error ({$statusCode}): {$message}");
    }
}

/** Failed to connect to the Forge server. */
class ForgeConnectionException extends ForgeException
{
    public function __construct(\Throwable $cause)
    {
        parent::__construct("connection error: {$cause->getMessage()}", 0, $cause);
    }
}
