<?php
/**
 * Copyright (c) 2022 · Kerem Güneş
 * Apache License 2.0 · https://github.com/k-gun/errorise
 */
declare(strict_types=1);

namespace KGun\Errorise;

/**
 * Exception class for controlled try/catch routines.
 *
 * @package KGun\Errorise
 * @object  KGun\Errorise\ErrorException
 * @author  Kerem Güneş
 */
class ErrorException extends \ErrorException
{
    /**
     * Error data holder.
     *
     * @var KGun\Errorise\Error|null
     * @readonly
     */
    private Error $error;

    /**
     * Constructor.
     *
     * @param string                   $message
     * @param int                      $code
     * @param int                      $severity
     * @param string|null              $file
     * @param int|null                 $line
     * @param Throwable|null           $previous
     * @param KGun\Errorise\Error|null $error
     */
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

    /**
     * Get error property.
     *
     * @return KGun\Errorise\Error|null
     */
    public function error(): ?Error
    {
        return $this->error ?? null;
    }

    /**
     * Get pure message without function prefix (eg: mkdir(): The message => The message).
     *
     * @return string
     */
    public function getPureMessage(): string
    {
        preg_match('~^\w+\([^)]*\):\s*(.+)~', $this->message, $match);

        return $match[1] ?? $this->message;
    }
}
