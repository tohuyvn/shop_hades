#!/bin/bash
cd "$(dirname "$0")"

echo "Dừng Docker cũ..."
sudo docker compose down 2>/dev/null || true

echo "Khởi động Local server trên cổng 8000..."
./start.sh
