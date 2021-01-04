###Logger

Invalid Omnisend requests can be logged by custom provided logger. Logger must implement: `Psr\Log\LoggerInterface` interface.

Configuration:
```yaml
nfq_sylius_omnisend:
    client_logger: 'your_custom_logger_service_name'
```

Example message
```
app.CRITICAL: Request to Omnisend API failed.
{
  "request_data": "",
  "response": {
    "status": 403,
    "headers": {
      "server": [
        "nginx"
      ],
      "date": [
        "Sun, 22 Nov 2020 22:54:21 GMT"
      ],
      "content-type": [
        "application/json"
      ],
      "vary": [
        "Accept-Encoding"
      ],
      "x-rate-limit-limit": [
        "400"
      ],
      "x-rate-limit-remaining": [
        "400"
      ],
      "x-rate-limit-reset": [
        "60"
      ],
      "entry-point": [
        "production-public-entry-point-n6-us-central1-c"
      ],
      "content-encoding": [
        "gzip"
      ],
      "via": [
        "1.1 google"
      ],
      "alt-svc": [
        "clear"
      ]
    },
    "partial_body": "{\"error\":\"Forbidden\"}"
  }
}

```
