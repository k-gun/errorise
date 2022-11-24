<?php
/**
 * Copyright (c) 2022 · Kerem Güneş
 * Apache License 2.0 · https://github.com/okerem/errorise
 */
declare(strict_types=1);

namespace Errorise;

/**
 * Wrapper class for wrapping a call routine.
 *
 * @package Errorise
 * @object  Errorise\ErrorWrapper
 * @author  Kerem Güneş
 */
class ErrorWrapper
{
    /**
     * Wrap a call routine & return called function result.
     *
     * @param  Closure              $call
     * @param  ErrorException|null &$error
     * @return mixed
     */
    public static function wrap(\Closure $call, \ErrorException &$error = null)
    {
        $eh = new ErrorHandler(false);
        $eh->register();

        $ret = null;

        try {
            $ret = $call();
            $eh->throw();
        } catch (\ErrorException $e) {
            $error = $e;
            unset($e);
        } finally {
            $eh->unregister();
        }

        return $ret;
    }
}
