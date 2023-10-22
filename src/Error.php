<?php
/**
 * Copyright (c) 2022 · Kerem Güneş
 * Apache License 2.0 · https://github.com/okerem/errorise
 */
declare(strict_types=1);

namespace Errorise;

/**
 * Error class for holding / accessing error data.
 *
 * @package Errorise
 * @class   Errorise\Error
 * @author  Kerem Güneş
 * @internal
 */
class Error
{
    /**
     * Data stack.
     *
     * @var array
     * @readonly
     */
    private array $data;

    /**
     * Constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get data property.
     *
     * @return array
     */
    public function data(): array
    {
        return $this->data;
    }

    /**
     * Get severity.
     *
     * @return int
     */
    public function getSeverity(): int
    {
        return $this->data['severity'];
    }

    /**
     * Get message.
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->data['message'];
    }

    /**
     * Get file.
     *
     * @return string
     */
    public function getFile(): string
    {
        return $this->data['file'];
    }

    /**
     * Get line.
     *
     * @return int
     */
    public function getLine(): int
    {
        return $this->data['line'];
    }

    /**
     * Get causing function.
     *
     * @return string|null
     */
    public function getFunction(): ?string
    {
        preg_match('~^(\w+)\([^)]*\):~', $this->getMessage(), $match);

        return $match[1] ?? null;
    }

    /**
     * Get causing (undefined) variable name.
     *
     * @return string|null
     */
    public function getVariable(): ?string
    {
        preg_match('~Undefined variable (\$\w+)~', $this->getMessage(), $match);

        return $match[1] ?? null;
    }
}
