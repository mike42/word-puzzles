# PHP Word Puzzle Generator

This is a PHP web app for generating basic word puzzles.

It can generate-

- Find-a-words
- Cryptograms
- Word scrambles

A live version of this code runs at [https://mike42.me/words/](https://mike42.me/words/).

## Installation

PHP versions from 5.6 up are supported. Simply copy the application to your PHP-enabled webserver, and set up the autoloader via `composer`.

```bash
composer install --no-dev
```

## Development

MIT-licensed contributions are welcome. This project uses the PSR-2 code conventions.

You will require `composer` for development dependencies.

```bash
composer install
```

### Style check

```
php vendor/bin/phpcs -n --standard=psr2 cli/ src/ includes/ *.php
```

