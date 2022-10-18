If you are tired of using the `@` error suppression operator and then digging for errors with the `error_get_last()` function, you can try using Errorise. Errorise offers well-caught PHP errors to handle them on your side, freeing you from these stuff for each error-prone call.

### Installing
```
composer require k-gun/errorise
```

### Using Handler

```php
use KGun\Errorise\{ErrorHandler, ErrorException};

$eh = new ErrorHandler();
try {
    fopen('/path/to/file.txt', 'r');

    // Throws if any error occured.
    $eh->throw();
} catch (ErrorException $e) {
    // Message: fopen(/path/to/file.txt): Failed to open stream: No such file or directory
    // Throw your custom exception after some business.
    throw new YourCustomException($e->getMessage());
} finally {
    // Trigger handler __destruct() to call unregister().
    unset($eh);
}
```

### Using Handler for specific functions / message patterns.

You can controll that when to throw for which function or message pattern.

```php
use KGun\Errorise\{ErrorHandler, ErrorException};

$eh = new ErrorHandler();
try {
    fopen('/path/to/file.txt', 'r');

    // Throws if any error occured with fopen().
    $eh->throwFor('fopen');

    // Throws if any error occured with message pattern.
    $eh->throwForMatch('/fopen/');
} catch (ErrorException $e) {
    // Message: fopen(/path/to/file.txt): Failed to open stream: No such file or directory
    // Throw your custom exception after some business.
    throw new YourCustomException($e->getMessage());
} finally {
    // Trigger handler __destruct() to call unregister().
    unset($eh);
}
```

### Using Handler for undefined variables.

Handler is available for undefined variables as well (in case):

```php
use KGun\Errorise\{ErrorHandler, ErrorException};

$eh = new ErrorHandler();
try {
    $bar = $foo;

    // Throws since $foo is undefined.
    $eh->throw();
} catch (ErrorException $e) {
    // Message: Undefined variable $foo.
    // Throw your custom exception after some business.
    throw new YourCustomException($e->getMessage());
} finally {
    // Trigger handler __destruct() to call unregister().
    unset($eh);
}
```

### Using Handler with non-auto mode.

If you want full controll on register / unregister routine, pass `$auto` argument as `false`, just like:

```php
use KGun\Errorise\{ErrorHandler, ErrorException};

$eh = new ErrorHandler(false);
try {
    // Register Errorise error handler.
    $eh->register();

    // Some error-prone works.

    // Throws if any error occured.
    $eh->throw();
} catch (ErrorException $e) {
    // Message: Undefined variable $foo.
    // Throw your custom exception after some business.
    throw new YourCustomException($e->getMessage());
} finally {
    // Un-Register Errorise error handler.
    // So, back to the previous or internal error handler.
    $eh->unregister();
}
```
