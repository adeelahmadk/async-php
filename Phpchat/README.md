# PHP Chat

A simple chat app with a CLI client.

## Setup

Major dependencies are:

1. PHP
2. composer

Server dependencies:

1. `react/event-loop` (^1.5.0),
2. `react/socket` (^1.16.0),
3. `kevinlebrun/colors.php` (1.0.*)

Client dependencies:

1. `react/stream` (^1.4.0)

Inside the directory give command:

```bash
composer install
```

## Use

Start server with `php src/Server/server.php [--host=127.0.0.1] [--port=8000]`. For example:

```bash
php src/Server/server.php --port=8787
```

Start client with `php src/Client/client.php [--host=127.0.0.1] [--port=8000]`. For example:

```bash
php src/Client/client.php --port=8787
```

