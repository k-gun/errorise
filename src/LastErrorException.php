<?php
/**
 * Copyright (c) 2022 · Kerem Güneş
 * Apache License 2.0 · https://github.com/okerem/errorise
 */
declare(strict_types=1);

namespace Errorise;

/**
 * Exception class for last occured errors.
 *
 * @package Errorise
 * @object  Errorise\LastErrorException
 * @author  Kerem Güneş
 */
class LastErrorException extends ErrorException
{
    /**
     * Defaults for no errors / invalid calls.
     *
     * @var array
     */
    private static array $lastErrorDefault = [
        'type' => 0,    'message' => '',
        'file' => null, 'line'    => null,
    ];

    /**
     * Constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        $severity = 0;
        $file = $line = null;

        if (!func_num_args()) {
            // Merge with defaults.
            $lastError = array_merge(self::$lastErrorDefault, (array) error_get_last());

            // Re-assign variables extracting from last error.
            ['type' => $severity, 'file' => $file, 'line' => $line, 'message' => $message] = $lastError;

            $error = new Error(compact('severity', 'message', 'file', 'line'));
        }

        parent::__construct($message, $code, $severity, $file, $line, $previous, $error ?? null);
    }
}
