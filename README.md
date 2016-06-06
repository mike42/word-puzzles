# PHP Word Puzzle Generator [![Build Status](https://travis-ci.org/mike42/word-puzzles.svg?branch=master)](https://travis-ci.org/mike42/word-puzzles)

This is a PHP web app for generating basic word puzzles.

It can generate-

- Find-a-words
- Cryptograms
- Word scrambles

A live version of this code runs at [https://mike42.me/words/](https://mike42.me/words/).

## Installation

PHP versions from 5.6 up are supported. Simply generate the stylesheets and scripts, then copy the application to your PHP-enabled webserver:

```bash
composer install --no-dev
bower install
npm install
grunt
```

## Development

MIT-licensed contributions are welcome.

The CI script at [.travis.yml](https://github.com/mike42/word-puzzles/blob/master/.travis.yml) contains commands to set up and run a build on Linux.

### Technology
This project uses-

Server-side:
- PHP (PSR-2 code conventions checked with PHP_CodeSniffer)
- Composer for server-side dependencies
- Testing
 - PHPUnit for test execution
 - Selenium browser tests
 - Apache Web Server on Debian (in a Docker container) to host the app for testing.

Client code:
- Libraries
 - Bootstrap
 - JQuery
- Compilation/pre-processing
 - Sass stylesheet processing
 - Grunt build
 - npm & bower for loading build pipleine dependencies 
