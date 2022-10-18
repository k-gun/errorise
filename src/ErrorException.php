<?php
declare(strict_types=1);

namespace Errorise;

class ErrorException extends \ErrorException
{
    private Error $error;

    public function __construct(
        string $message = '', int $code = 0, int $severity = E_ERROR,
        string $file = null,  int $line = null,
        \Throwable $previous = null,
        Error $error = null,
    )
    {
        parent::__construct($message, $code, $severity, $file, $line, $previous);

        // Assign error if provided.
        $error && $this->error = $error;
    }

    public function error(): ?Error
    {
        return $this->error ?? null;
    }

    public function getPureMessage(): ?string
    {
        preg_match('~^(?:\w+)\([^)]*\):\s*(.+)~', $this->message, $match);

        return $match[1] ?? $this->message;
    }
}
