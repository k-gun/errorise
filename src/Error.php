<?php
declare(strict_types=1);

namespace Errorise;

class Error
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function data(): array
    {
        return $this->data;
    }

    public function getSeverity(): int
    {
        return $this->data['severity'];
    }
    public function getMessage(): string
    {
        return $this->data['message'];
    }
    public function getFile(): string
    {
        return $this->data['file'];
    }
    public function getLine(): int
    {
        return $this->data['line'];
    }

    public function getFunction(): ?string
    {
        preg_match('~^(\w+)\([^)]*\):~', $this->getMessage(), $match);

        return $match[1] ?? null;
    }
    public function getVariable(): ?string
    {
        preg_match('~Undefined variable (\$\w+)~', $this->getMessage(), $match);

        return $match[1] ?? null;
    }

    public function isFunctionRelated(): bool
    {
        return $this->getFunction() !== null;
    }
    public function isVariableRelated(): bool
    {
        return $this->getVariable() !== null;
    }
}
