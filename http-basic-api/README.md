# HTTP Basic API
Implementation of HTTP server to process HTTP GET and POST requests on simulated data store.

Start server with `php server.php [--host=127.0.0.1] [--port=8000]`, for example:

```bash
php server.php --port=8787
```
Send request:

```bash
# GET
curl -i 127.0.0.1:8787/

# POST JSON data
curl -i -X POST \
  -H "Content-Type: application/json" \
  -d '{"title":"A new post","body":"This is a new post appended.","tags":["html","css"]}' \
  https://127.0.0.1:8787/addpost

# POST URL encoded form data
curl -i -X POST \
  -H 'Content-Type: application/x-www-form-urlencoded' \
  -d 'title=Form+data&body=A+post+sent+as+a+url+encoded+form+data.&tags%5B%5D=php&tags%5B%5D=http' \
	https://127.0.0.1:8787/addpost
```

