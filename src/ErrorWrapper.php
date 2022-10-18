<?php
declare(strict_types=1);

namespace Errorise;

class ErrorWrapper
{
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
