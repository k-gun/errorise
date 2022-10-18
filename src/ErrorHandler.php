<?php
declare(strict_types=1);

namespace KGun\Errorise;

class ErrorHandler
{
    private bool $auto;
    private ?Error $error = null;

    public function __construct(bool $auto = true)
    {
        $this->auto = $auto;
        $this->auto && $this->register();
    }
    public function __destruct()
    {
        $this->auto && $this->unregister();
    }

    public function error(): ?Error
    {
        return $this->error;
    }

    public function register(): void
    {
        // Clear.
        $this->error = null;

        set_error_handler(function ($severity, $message, $file, $line) {
            $this->error = new Error(compact('severity', 'message', 'file', 'line'));
        });
    }
    public function unregister(): void
    {
        restore_error_handler();
    }

    public function throw(int $code = 0): void
    {
        if ($this->error) {
            extract($this->error->data());
            throw new ErrorException($message, $code, $severity, $file, $line, null, $this->error);
        }
    }
    public function throwFor(string $function, int $code = 0): void
    {
        if ($this->error && strtolower($this->error->getFunction()) == strtolower($function)) {
            $this->throw($code);
        }
    }
    public function throwForMatch(string $pattern, int $code = 0): void
    {
        if ($this->error && preg_match($pattern, $this->error->getMessage())) {
            $this->throw($code);
        }
    }
}
