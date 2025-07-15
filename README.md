# Asynchronous Programming in PHP

The purpose of this repository is to experiment and explore the asynchronous programming landscape of modern PHP.  We begin with [ReactPHP](https://reactphp.org/) and will continue to explore other packages and libraries.

ReactPHP is best explained on its website as:

> ReactPHP is a low-level library for event-driven programming in PHP. At its core is an event loop, on top of which it provides low-level utilities, such as: Streams abstraction, async DNS resolver, network client/server, HTTP client/server and interaction with processes. Third-party libraries can use these components to create async network clients/servers and more.



**Example code snippets:**

| Module | Description |
| --- | --- |
| [`misc`](./misc/README.md) | Miscellaneous Scripts |
| [`socket`](./socket/README.md) | Simple Chat with Sockets |
| [`http-basic-api`](./http-basic-api/README.md) | HTTP server implementing API to DB |

**Standalone projects:**

| Module                                   | Description                                           |
| ---------------------------------------- | ----------------------------------------------------- |
| [`Phpchat`](./Phpchat/README.md)         | A simple CLI client/server chat-room app (standalone) |
| [`http-server`](./http-server/README.md) | A HTTP server with modular middleware.                |

## Setup

The main repository and standalone projects contain `composer.json` files. All you need is PHP and composer. Start by running `composer install` in repo root or the respective project directory and run a script like:

```bash
php http-basic-api/server.php --port=8787
```

