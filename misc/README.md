# Miscellaneous Examples

This directory hosts miscellaneous simple examples explaining use of library.

All examples can be executed from repository root by:

```bash
php misc/script.php
```

## List of Scripts

| Script                                   | Example                                      | Description                                                  |
| ---------------------------------------- | -------------------------------------------- | ------------------------------------------------------------ |
| [`readable.php`](readable.php)           | `echo "hello world" | php readable.php`      | `STDIN` stream read asynchronously using event loop.         |
| [`stream_bidir.php`](stream_bidir.php)   | `echo "hello world | php stream_bidir.php" ` | `STDIN` and `STDOUT` streams read/write asynchronously using event loop. |
| [`stream_piped.php`](stream_piped.php)   | `cat data.txt | php stream_piped.php`        | Streams piped to perform read/transform/write (`STDIN`-Through-`STDOUT`) asynchronously using event loop. |
| [`callbacks.php`](callbacks.php)         | `php callbacks.php`                          | Classic callback pattern, predates promises.                 |
| [`promises.php`](promises.php)           | `php promises.php`                           | Example use of promises using simulated HTTP request/response. |
| [`promise_chain.php`](promise_chain.php) | `php promise_chain.php`                      | Explains usage of promise chaining using simulated HTTP request/response. |
| [`httpserv.php`](httpserv.php)           | `php httpserv.php`                           | Explains usage of HTTP server and socket objects to implement a simple HTTP request/response. |

