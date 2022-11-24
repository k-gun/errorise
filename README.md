If you are tired of using the `@` error suppression operator and then digging for errors with the `error_get_last()` function, you can try using Errorise. Errorise offers well-caught PHP errors to handle them on your side, freeing you from these stuff for each error-prone call.

### Installing
```
composer require okerem/errorise
```

### Using `ErrorHandler`

```php
use Errorise;

$eh = new Errorise\ErrorHandler();
try {
    fopen('/path/to/file.txt', 'r');

    // Throws if any error occured.
    $eh->throw();
} catch (Errorise\ErrorException $e) {
    // Message: fopen(/path/to/file.txt): Failed to open ...
    throw new YourCustomException_After_Some_Business(
        $e->getMessage()
    );
} finally {
    // Trigger handler __destruct() to call unregister().
    unset($eh);
}
```

### Using `ErrorHandler` for Specific Functions / Patterns

You can controll that when to throw or for which function or message pattern to throw.

```php
try {
    fopen('/path/to/file.txt', 'r');

    // Throws if any error occured with fopen().
    $eh->throwFor('fopen');

    // Throws if any error occured with message pattern.
    $eh->throwForMatch('/fopen/');
} catch (Errorise\ErrorException $e) {
    // ...
} finally {
    // ...
}
```

### Using `ErrorHandler` for Undefined Variables

Like for function errors, `ErrorHandler` is available for undefined variable errors as well:

```php
try {
    $bar = $foo;

    // Throws since $foo is undefined.
    $eh->throw();
} catch (Errorise\ErrorException $e) {
    // ...
} finally {
    // ...
}
```

### Using `ErrorHandler` with Non-Auto Mode

If you want full controll on register / unregister routine, pass `$auto` argument as `false`, just like:

```php
$eh = new Errorise\ErrorHandler(false);
try {
    // Register Errorise error handler.
    $eh->register();

    // Some risky or error-prone works.

    // Throws if any error occured.
    $eh->throw();
} catch (Errorise\ErrorException $e) {
    // ...
} finally {
    // Un-Register Errorise error handler.
    // So, back to the previous or internal error handler.
    $eh->unregister();
}
```

### Getting Error Messages in Catch

You can get error messages by using two methods of caught `ErrorException`.

```php
try {
    // ...
} catch (Errorise\ErrorException $e) {
    // Message: mkdir(): No such file or directory
    $e->getMessage();

    // Message: No such file or directory
    $e->getPureMessage();
} finally {
    // ...
}
```

### Utilising `Error` Object

To get more details, you can utilise the `$error` property of the `ErrorHandler` which is passed to the caught `ErrorException`.

```php
try {
    // ...
} catch (Errorise\ErrorException $e) {
    // @var Errorise\Error
    $error = $e->error();

    // Data: [severity, message, file, line]
    $data = $error->data();

    // Severity: 2
    $error->getSeverity();

    // Message: mkdir(): No such file or directory
    $error->getMessage();

    // File: /tmp/php/errorise/test.php
    $error->getFile();

    // Line: 3, where mkdir() was called.
    $error->getLine();

    // Function / Variable Name.
    $error->getFunction();
    $error->getVariable();
} finally {
    // ...
}
```

### Using `ErrorWrapper`

You can use `ErrorWrapper` to wrap your calls instead of using try/catch blocks.

```php
$ret = Errorise\ErrorWrapper::wrap(function () {
    $fp = fopen('/path/to/file.txt', 'r');
    return $fp;
}, $e /* byref */);

assert($ret == false);
assert($e instanceof Errorise\ErrorException);
```

### Manually Handling the Last Errors

You can use `LastErrorException` to throw errors after checking your call results.

```php
use Errorise;

// Your filesystem module.
class FileSystem {
    public static function createDirectory(
        string $dir, int $mode = 0777, bool $recursive = false
    ): void {
        $ok = @mkdir($dir, $mode, $recursive);
        if (!$ok) {
            throw new Errorise\LastErrorException();
        }
    }
}

// Your client layer.
try {
    FileSystem::createDirectory('/tmp');
} catch (Errorise\LastErrorException $e) {
    // Message: mkdir(): File exists
    throw new YourCustomException_After_Some_Business(
        $e->getMessage()
    );
}
```
