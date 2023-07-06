# PHP `.env` Editor

`redot/dotenv-editor` is a PHP package that provides a simple and convenient way to read, modify, and save environment variables from a `.env` file. It utilizes the `Dotenv` package for parsing the environment file.

## Installation

You can install the package via Composer:

```bash
composer require redot/dotenv-editor
```

## Usage

### Creating an Instance

To use the `DotenvEditor` class, create an instance by providing the path to the `.env` file:

```php
use Redot\DotenvEditor\DotenvEditor;

$editor = new DotenvEditor('/path/to/.env');
```

Optionally, you can pass a second parameter to enable backup functionality:

```php
$editor = new DotenvEditor('/path/to/.env', true);
```

### Getting Environment Variables

You can retrieve the value of an environment variable by specifying its key:

```php
$value = $editor->get('KEY');
```

If the key is not found, you can provide a default value that will be returned instead:

```php
$value = $editor->get('KEY', 'default');
```

### Setting Environment Variables

To set the value of an environment variable, use the `set` method:

```php
$editor->set('KEY', 'value');
```

### Saving Changes

After modifying the environment variables, you need to save the changes back to the `.env` file:

```php
$editor->save();
```

This will overwrite the existing file with the updated variables.

### Backup Functionality

By default, the backup functionality is disabled. If you want to enable it, pass `true` as the second argument when creating the `DotenvEditor` instance:

```php
$editor = new DotenvEditor('/path/to/.env', true);
```

This will create a backup file (`.env.backup`) before saving any changes. The backup file will contain the previous version of the `.env` file.

**Note:** The backup file will be overwritten each time you backup the `.env` file again.

### Example

Here's an example of how you can use the `DotenvEditor` class:

```php
use Redot\DotenvEditor\DotenvEditor;
use Redot\DotenvEditor\Exceptions\FileNotFoundException;

try {
    $editor = new DotenvEditor('/path/to/.env');

    $editor->set('APP_ENV', 'production');
    $editor->set('APP_DEBUG', 'false');

    $editor->save();

    echo 'Changes saved successfully.';
} catch (FileNotFoundException $e) {
    echo 'The file could not be found.';
}
```

## Testing

The package includes a test suite that can be run using Pest. run the following command:

```bash
composer test
```

## License

This package is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
