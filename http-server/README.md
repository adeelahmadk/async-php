# HTTP Server with middleware

An HTTP server with modular middleware.

Dependencies:
1. `react/event-loop` (^1.5.0),
2. `react/socket` (^1.16.0),

## List of Scripts

| Module | Description |
| --- | --- |
| [`func-server.php`](./src/func-server.php) | function-oriented implementation |
| [`server0.php`](./src/server0.php) | object-oriented implementation |
| [`promise-server.php`](./src/promise-server.php) | uses promises for deferred resolution or rejection (i.e. Exception) |

## Use

The general format of the command is `php server.php [--port=127.0.0.1] [--port=8000]` where `127.0.0.1` and `8000` are default values for host and port.

```bash
php server0.php --host=127.0.0.1 --port=8989
# or
php server0.php --port=8787
```

