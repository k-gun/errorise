If you are tired of using the `@` error suppression operator and then digging for errors with the `error_get_last()` function, you can try using Errorise. Errorise offers well-caught PHP errors to handle them on your side, freeing you from these stuff for each error-prone call.

### Installing
```
composer require k-gun/errorise
```

### Using `ErrorHandler`

```php
use KGun\Errorise\{ErrorHandler, ErrorException};

$eh = new ErrorHandler();
try {
    fopen('/path/to/file.txt', 'r');

    // Throws if any error occured.
    $eh->throw();
} catch (ErrorException $e) {
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
} catch (ErrorException $e) {
    // ...
} finally {
    // ...
}
```

### Using `ErrorHandler` for Undefined Variables

Likewise for function errors, `ErrorHandler` is available for undefined variable errors as well (in case):

```php
try {
    $bar = $foo;

    // Throws since $foo is undefined.
    $eh->throw();
} catch (ErrorException $e) {
    // Message: Undefined variable $foo.
    // ...
} finally {
    // ...
}
```

### Using `ErrorHandler` with Non-Auto Mode

If you want full controll on register / unregister routine, pass `$auto` argument as `false`, just like:

```php
$eh = new ErrorHandler(false);
try {
    // Register Errorise error handler.
    $eh->register();

    // Some risky or error-prone works.

    // Throws if any error occured.
    $eh->throw();
} catch (ErrorException $e) {
    // ...
} finally {
    // Un-Register Errorise error handler.
    // So, back to the previous or internal error handler.
    $eh->unregister();
}
```
### Getting Errors Messages

You can get error messages by using two methods of caught `ErrorException`.

```php
try {
    // ...
} catch (ErrorException $e) {
    // Message: mkdir(): No such file or directory
    $e->getMessage();

    // Message: No such file or directory
    $e->getPureMessage();
} finally {
    // ...
}
```

### Utilising `Error` Object

To get more details (in case), you can utilise the `$error` property of the `ErrorHandler` which is passed to the caught `ErrorException`.

```php
try {
    // ...
} catch (ErrorException $e) {
    // @var KGun\Errorise\Error
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
} finally {
    // ...
}
```
