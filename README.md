# PHP Word Puzzle Generator

This is a PHP web app for generating basic word puzzles.

It can generate-

- Find-a-words
- Cryptograms
- Word scrambles

A live version of this code runs at [https://mike42.me/words/](https://mike42.me/words/).

## Installation

Simply copy the application to your PHP-enabled webserver to install it.

PHP versions from 5.3-7 are supported.

## Development

MIT-licensed contributions are welcome. This project uses the PSR-2 code conventions.

You will require `composer` for development dependencies.

```bash
composer install
```

### Style check

```
php vendor/bin/phpcs --standard=psr2 cli/ common/ includes/
```

