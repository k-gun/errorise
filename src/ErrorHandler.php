<?php
/**
 * Copyright (c) 2022 · Kerem Güneş
 * Apache License 2.0 · https://github.com/k-gun/errorise
 */
declare(strict_types=1);

namespace KGun\Errorise;

/**
 * Handler class for registering, unregistering error handler and
 * throwing the last occured error.
 *
 * @package KGun\Errorise
 * @object  KGun\Errorise\ErrorHandler
 * @author  Kerem Güneş
 */
class ErrorHandler
{
    /**
     * For internal auto register/unregister calls.
     *
     * @internal
     */
    private bool $auto;

    /**
     * Error data holder.
     *
     * @var KGun\Errorise\Error|null
     * @readonly
     */
    private ?Error $error = null;

    /**
     * Constructor.
     *
     * @param bool $auto For internal auto register/unregister calls.
     */
    public function __construct(bool $auto = true)
    {
        $this->auto = $auto;
        $this->auto && $this->register();
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        $this->auto && $this->unregister();
    }

    /**
     * Get error property.
     *
     * @return KGun\Errorise\Error|null
     */
    public function error(): ?Error
    {
        return $this->error;
    }

    /**
     * Register error handler for once.
     *
     * Note: After calling this method, `unregister()` method must be called in
     * `finally {..}` block in order to restore previous or internal error handler.
     *
     * @return void
     */
    public function register(): void
    {
        // Clear.
        $this->error = null;

        set_error_handler(function ($severity, $message, $file, $line) {
            $this->error = new Error(compact('severity', 'message', 'file', 'line'));
        });
    }

    /**
     * Un-Register error handler for once.
     *
     * Note: Before calling this method, `register()` method must be called at top of
     * `try/catch {..}` block in order to override previous or internal error handler.
     *
     * @return void
     */
    public function unregister(): void
    {
        restore_error_handler();
    }

    /**
     * Throw self error if any occured.
     *
     * @param  int $code
     * @return void
     * @throws KGun\Errorise\ErrorException
     */
    public function throw(int $code = 0): void
    {
        if ($this->error) {
            extract($this->error->data());
            throw new ErrorException($message, $code, $severity, $file, $line, null, $this->error);
        }
    }

    /**
     * Throw self error if any occured & given function is the cause of.
     *
     * @param  string $function
     * @param  int    $code
     * @return void
     * @causes KGun\Errorise\ErrorException
     */
    public function throwFor(string $function, int $code = 0): void
    {
        if ($this->error && strtolower($this->error->getFunction() . '') == strtolower($function)) {
            $this->throw($code);
        }
    }

    /**
     * Throw self error if any occured & given pattern is matched with error message.
     *
     * @param  string $pattern
     * @param  int    $code
     * @return void
     * @causes KGun\Errorise\ErrorException
     */
    public function throwForMatch(string $pattern, int $code = 0): void
    {
        if ($this->error && preg_match($pattern, $this->error->getMessage() . '')) {
            $this->throw($code);
        }
    }
}
