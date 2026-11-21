#!/bin/bash
cd "$(dirname "$0")"

URL=$(sudo docker compose logs tunnel 2>&1 | grep -o 'https://[a-zA-Z0-9.-]*\.trycloudflare\.com' | tail -n 1)

if [ -z "$URL" ]; then
    echo "Đang khởi động tunnel mới..."
    sudo docker compose restart tunnel
    sleep 3
    URL=$(sudo docker compose logs tunnel 2>&1 | grep -o 'https://[a-zA-Z0-9.-]*\.trycloudflare\.com' | tail -n 1)
fi

echo "=========================================================="
echo "  HADES ACADEMY — LINK PUBLIC INTERNET (CLOUDFLARE)"
echo "=========================================================="
echo "  Trang chủ Khóa học : ${URL}/courses"
echo "  Trang Đăng nhập    : ${URL}/login"
echo "  Trang Quản trị LMS : ${URL}/admin/lms"
echo "  Xác thực chứng chỉ : ${URL}/verify-certificate"
echo "=========================================================="
