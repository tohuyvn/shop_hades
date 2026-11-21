#!/bin/bash
cd "$(dirname "$0")"

PHP_BIN="/opt/lampp/bin/php"
PORT=8080

echo "▶ Đang khởi động Local Server tại http://127.0.0.1:${PORT}..."
env PHP_CLI_SERVER_WORKERS=10 "$PHP_BIN" -S 127.0.0.1:${PORT} -t public public/router.php
