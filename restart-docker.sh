#!/bin/bash
cd "$(dirname "$0")"
echo "Restarting Docker containers..."
sudo docker compose down
sudo docker compose up -d
echo "Done!"
