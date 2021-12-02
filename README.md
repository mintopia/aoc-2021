# Advent of Code 2021
An attempt at 2021 Advent of Code in PHP, until I get bored/run out of time around day 8 or 9 and stop.

## Installation

This package is meant to be used with PHP 8.1 with dependencies managed by composer. If you have PHP 8.1 and composer installed, you can install the dependencies using:

```bash
composer install
```

If you don't have PHP 8.1 and composer but do have Docker and Docker compose, you can use the development docker containers:

```bash
docker-compose run --rm composer install
```

This will install the relevant dependencies.

## Usage

Once you have installed dependencies, if you're running PHP natively, you can use `run.php` to see the available commands

```bash
php run.php list
```

The days can then be run using:

```bash
php run.php aoc:day1
```

If you are using the docker container approach, you can run these commands in the following way:

```bash
docker-compose run --rm aoc list
docker-compose run --rm aoc aoc:day1
```

## License

MIT License

Copyright (c) 2021 Jessica Smith

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
