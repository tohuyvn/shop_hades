<?php

namespace App;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;

class Seed
{
    public static function run(): void
    {
        if (!is_dir(STORAGE_PATH)) {
            mkdir(STORAGE_PATH, 0775, true);
        }

        $userModel = new User();
        if ($userModel->findByEmail('admin@shop.test') === null) {
            $userModel->create([
                'name' => 'Admin',
                'email' => 'admin@shop.test',
                'phone' => '0901234567',
                'password' => 'admin123',
                'role' => 'admin',
            ]);
        }

        if ($userModel->findByEmail('khach@shop.test') === null) {
            $userModel->create([
                'name' => 'Nguyễn Văn A',
                'email' => 'khach@shop.test',
                'phone' => '0912345678',
                'password' => '123456',
                'role' => 'customer',
            ]);
        }

        if ($userModel->findByEmail('shipper@shop.test') === null) {
            $userModel->create([
                'name' => 'Nguyễn Văn Shipper',
                'email' => 'shipper@shop.test',
                'phone' => '0987654321',
                'password' => '123456',
                'role' => 'shipper',
            ]);
        }

        $categoryModel = new Category();
        $productModel = new Product();

        // Kiểm tra xem số sản phẩm có chính xác là 30 không. Chỉ seed khi DB trống hoàn toàn.
        if (count($productModel->all()) === 0 && count($categoryModel->all()) === 0) {
            $catMap = [];
            $cats = [
                'Điện thoại' => 'Điện thoại di động chính hãng, trả góp 0%',
                'Laptop' => 'Máy tính xách tay từ các thương hiệu hàng đầu',
                'Phụ kiện' => 'Tai nghe, cáp sạc, sạc dự phòng chất lượng cao',
                'Smartwatch' => 'Đồng hồ thông minh theo dõi sức khỏe',
                'Đồng hồ' => 'Đồng hồ đeo tay Casio lịch lãm, cá tính',
                'Máy cũ' => 'Điện thoại, Laptop đã qua sử dụng, bảo hành uy tín',
                'Tablet' => 'Máy tính bảng Apple iPad giải trí, làm việc tiện lợi'
            ];

            foreach ($cats as $name => $desc) {
                $c = $categoryModel->create([
                    'name' => $name,
                    'description' => $desc,
                    'status' => 'active'
                ]);
                $catMap[$name] = $c['id'];
            }

            // Seed Products (30 samples)
            $products = [
                // Nhóm 1: Điện thoại
                [
                    'category_id' => $catMap['Điện thoại'],
                    'name' => 'iPhone 15 Pro Max 256GB',
                    'price' => 29990000,
                    'description' => 'Khung Titan cao cấp, chip A17 Pro siêu mạnh mẽ.',
                    'detail' => 'iPhone 15 Pro Max sở hữu thiết kế khung titan siêu bền và nhẹ, nút Action mới tiện dụng, hệ thống camera chuyên nghiệp 48MP và cổng kết nối USB-C tốc độ cao.',
                    'stock' => 12,
                    'image' => 'ip15.webp'
                ],
                [
                    'category_id' => $catMap['Điện thoại'],
                    'name' => 'iPhone 16 Pro 128GB',
                    'price' => 28990000,
                    'description' => 'Nút điều khiển camera chuyên biệt, chip A18 Pro.',
                    'detail' => 'iPhone 16 Pro nâng tầm nhiếp ảnh di động với nút Camera Control mới, chip xử lý A18 Pro tối ưu hiệu năng AI và thiết kế tản nhiệt tốt hơn.',
                    'stock' => 8,
                    'image' => 'ip16.webp'
                ],
                [
                    'category_id' => $catMap['Điện thoại'],
                    'name' => 'iPhone 17 Ultra Concept',
                    'price' => 39990000,
                    'description' => 'Thiết kế siêu mỏng tương lai, viền màn hình vô cực.',
                    'detail' => 'iPhone 17 Ultra là đỉnh cao công nghệ màn hình tràn viền hoàn toàn không khuyết điểm, camera ẩn dưới màn hình và camera zoom quang học 10x.',
                    'stock' => 3,
                    'image' => 'ip17.webp'
                ],
                [
                    'category_id' => $catMap['Điện thoại'],
                    'name' => 'iPhone 15 128GB',
                    'price' => 19990000,
                    'description' => 'Màn hình Dynamic Island, mặt lưng kính pha màu độc đáo.',
                    'detail' => 'iPhone 15 trang bị chip A16 Bionic mượt mà, camera sau nâng cấp lên 48MP và cổng sạc USB-C phổ biến.',
                    'stock' => 15,
                    'image' => 'ip15.webp'
                ],
                [
                    'category_id' => $catMap['Điện thoại'],
                    'name' => 'iPhone 16 Plus 256GB',
                    'price' => 25990000,
                    'description' => 'Màn hình 6.7 inch siêu rộng rãi, pin trâu vượt trội.',
                    'detail' => 'iPhone 16 Plus mang đến không gian trải nghiệm lớn cùng thời lượng pin tốt nhất dòng iPhone, chip A18 tối ưu chơi game đồ họa cao.',
                    'stock' => 10,
                    'image' => 'ip16.webp'
                ],

                // Nhóm 2: Laptop
                [
                    'category_id' => $catMap['Laptop'],
                    'name' => 'MacBook Pro M5 Max',
                    'price' => 54990000,
                    'description' => 'Siêu chip M5 Max thế hệ mới, màn hình Liquid Retina XDR.',
                    'detail' => 'Dành cho người dùng chuyên nghiệp đồ họa, lập trình và xử lý tác vụ nặng. MacBook Pro M5 Max cân mọi giới hạn hiệu năng với RAM khủng và tản nhiệt tối ưu.',
                    'stock' => 5,
                    'image' => 'mac_m5.webp'
                ],
                [
                    'category_id' => $catMap['Laptop'],
                    'name' => 'Dell XPS 13 Plus 9320',
                    'price' => 38990000,
                    'description' => 'Vỏ nhôm nguyên khối, hàng phím chức năng cảm ứng độc đáo.',
                    'detail' => 'Thiết kế tràn viền thời thượng, touchpad ẩn sang trọng và bộ vi xử lý Intel Core i7 thế hệ mới mạnh mẽ.',
                    'stock' => 6,
                    'image' => 'dell_xps.webp'
                ],
                [
                    'category_id' => $catMap['Laptop'],
                    'name' => 'ThinkPad X1 Carbon Gen 13',
                    'price' => 45990000,
                    'description' => 'Chất liệu sợi carbon siêu nhẹ siêu bền, bàn phím gõ đỉnh cao.',
                    'detail' => 'Lựa chọn số một cho doanh nhân và lập trình viên. ThinkPad X1 Carbon nổi tiếng với độ bền chuẩn quân đội và bàn phím huyền thoại êm ái.',
                    'stock' => 4,
                    'image' => 'thinkpad_gen13.webp'
                ],
                [
                    'category_id' => $catMap['Laptop'],
                    'name' => 'MacBook Air M2 13"',
                    'price' => 24990000,
                    'description' => 'Thiết kế vuông vắn siêu mỏng nhẹ, pin lên tới 18 giờ.',
                    'detail' => 'MacBook Air M2 mang lại sự cân bằng hoàn hảo giữa hiệu năng mượt mà và tính cơ động cực cao, phù hợp học sinh, sinh viên và văn phòng.',
                    'stock' => 11,
                    'image' => 'mac_m5.webp'
                ],
                [
                    'category_id' => $catMap['Laptop'],
                    'name' => 'Dell Inspiron 14 Core i5',
                    'price' => 16990000,
                    'description' => 'Vỏ kim loại bền bỉ, hiệu năng văn phòng mượt mà.',
                    'detail' => 'Dell Inspiron 14 trang bị màn hình FHD+ sắc nét, chip Core i5 đời mới đáp ứng tốt mọi công việc học tập và giải trí cơ bản.',
                    'stock' => 9,
                    'image' => 'dell_xps.webp'
                ],

                // Nhóm 3: Phụ kiện
                [
                    'category_id' => $catMap['Phụ kiện'],
                    'name' => 'Cáp sạc nhanh USB-C bọc dù 60W',
                    'price' => 190000,
                    'description' => 'Dây bọc dù chống đứt, hỗ trợ sạc nhanh PD.',
                    'detail' => 'Cáp sạc nhanh USB-C sang USB-C hỗ trợ truyền dữ liệu tốc độ cao, chất liệu sợi nylon bọc dù siêu bền bỉ chống rối gập.',
                    'stock' => 100,
                    'image' => 'day_sac.webp'
                ],
                [
                    'category_id' => $catMap['Phụ kiện'],
                    'name' => 'Củ sạc nhanh Hades GaN 65W',
                    'price' => 390000,
                    'description' => 'Công nghệ GaN sạc siêu mát, 3 cổng ra tiện lợi.',
                    'detail' => 'Củ sạc sử dụng vật liệu bán dẫn Gallium Nitride giúp thu nhỏ kích thước tối đa, sạc nhanh đồng thời cho cả Laptop, iPad và điện thoại.',
                    'stock' => 80,
                    'image' => 'day_sac.webp'
                ],
                [
                    'category_id' => $catMap['Phụ kiện'],
                    'name' => 'Tai nghe True Wireless Hades Pro',
                    'price' => 1290000,
                    'description' => 'Chống ồn chủ động ANC, chống nước IPX4.',
                    'detail' => 'Tai nghe bluetooth không dây thiết kế công thái học ôm tai, chất âm bass sâu, pin kết hợp hộp sạc dùng đến 24 giờ liên tục.',
                    'stock' => 45,
                    'image' => 'tai_nghe.webp'
                ],
                [
                    'category_id' => $catMap['Phụ kiện'],
                    'name' => 'Tai nghe chụp tai Gaming Pro',
                    'price' => 850000,
                    'description' => 'Âm thanh vòm 7.1, mic khử tiếng ồn tiện lợi.',
                    'detail' => 'Tai nghe Over-ear đệm da êm ái, cách âm tuyệt đối, mang lại trải nghiệm âm thanh chiến game 3D chân thực.',
                    'stock' => 30,
                    'image' => 'tai_nghe.webp'
                ],
                [
                    'category_id' => $catMap['Phụ kiện'],
                    'name' => 'Sạc dự phòng Hades 20000mAh',
                    'price' => 590000,
                    'description' => 'Dung lượng khủng sạc nhiều lần, sạc nhanh 22.5W.',
                    'detail' => 'Tích hợp màn hình LED hiển thị % pin trực quan, hỗ trợ sạc nhanh đa thiết bị cùng lúc cực kỳ an toàn.',
                    'stock' => 60,
                    'image' => 'day_sac.webp'
                ],

                // Nhóm 4: Smartwatch
                [
                    'category_id' => $catMap['Smartwatch'],
                    'name' => 'Apple Watch Ultra 3',
                    'price' => 21990000,
                    'description' => 'Đồng hồ thám hiểm chuyên nghiệp, màn hình độ sáng 3000 nits.',
                    'detail' => 'Màn hình Sapphire chống trầy, vỏ Titan chuẩn quân đội, GPS tần số kép cực kỳ chính xác cho các hoạt động ngoài trời dã ngoại.',
                    'stock' => 7,
                    'image' => 'smartwatch_apple.png'
                ],
                [
                    'category_id' => $catMap['Smartwatch'],
                    'name' => 'Apple Watch Series 9',
                    'price' => 9990000,
                    'description' => 'Chip S9 SiP thông minh, tính năng Double Tap chạm đúp.',
                    'detail' => 'Theo dõi sức khỏe nâng cao: điện tâm đồ ECG, nồng độ oxy trong máu SpO2, đo nhiệt độ cơ thể hỗ trợ dự báo chu kỳ sức khỏe.',
                    'stock' => 14,
                    'image' => 'smartwatch_apple.png'
                ],
                [
                    'category_id' => $catMap['Smartwatch'],
                    'name' => 'Smartwatch Hades Active 2',
                    'price' => 1990000,
                    'description' => 'Đo nhịp tim 24/7, pin 14 ngày bền bỉ.',
                    'detail' => 'Đồng hồ thông minh thể thao hỗ trợ hơn 100 chế độ luyện tập, kháng nước 5ATM thoải mái đi bơi.',
                    'stock' => 25,
                    'image' => 'smartwatch_apple.png'
                ],
                [
                    'category_id' => $catMap['Smartwatch'],
                    'name' => 'Apple Watch SE 2024',
                    'price' => 5990000,
                    'description' => 'Giá trị vượt trội, tính năng phát hiện đụng xe khẩn cấp.',
                    'detail' => 'Trang bị các tính năng theo dõi sức khỏe cơ bản, thiết kế thể thao trẻ trung, lựa chọn hoàn hảo cho các bạn trẻ và gia đình.',
                    'stock' => 18,
                    'image' => 'smartwatch_apple.png'
                ],

                // Nhóm 5: Đồng hồ
                [
                    'category_id' => $catMap['Đồng hồ'],
                    'name' => 'Đồng hồ Casio Edifice EFR',
                    'price' => 3450000,
                    'description' => 'Thiết kế kim lịch lãm, chống nước độ sâu 100 mét.',
                    'detail' => 'Dòng đồng hồ Casio cao cấp cho nam, vỏ thép không gỉ nguyên khối, mặt kính khoáng chống va đập cực tốt.',
                    'stock' => 10,
                    'image' => 'watch_casio.png'
                ],
                [
                    'category_id' => $catMap['Đồng hồ'],
                    'name' => 'Casio G-Shock G-Steel Tough Solar',
                    'price' => 4990000,
                    'description' => 'Sử dụng pin năng lượng mặt trời, tự động đồng bộ giờ.',
                    'detail' => 'Chống va đập cực đỉnh, tích hợp đèn LED đôi siêu sáng, kết nối bluetooth thông minh với điện thoại.',
                    'stock' => 6,
                    'image' => 'watch_casio.png'
                ],
                [
                    'category_id' => $catMap['Đồng hồ'],
                    'name' => 'Đồng hồ Casio Classic MTP Dây Da',
                    'price' => 950000,
                    'description' => 'Phong cách tối giản classic cổ điển, dây da bò thật.',
                    'detail' => 'Đồng hồ đeo tay thanh lịch cho nam nữ, phù hợp đi học, đi làm công sở, thời gian pin dùng đến 3 năm.',
                    'stock' => 20,
                    'image' => 'watch_casio.png'
                ],
                [
                    'category_id' => $catMap['Đồng hồ'],
                    'name' => 'G-Shock GA-2100 "CasiOak"',
                    'price' => 3120000,
                    'description' => 'Mặt bát giác cá tính, thiết kế mỏng nhẹ hiện đại.',
                    'detail' => 'Vỏ bảo vệ cấu trúc carbon bền nhẹ, thiết kế sang trọng pha nét thể thao bụi bặm bán chạy số 1 toàn cầu.',
                    'stock' => 15,
                    'image' => 'watch_casio.png'
                ],

                // Nhóm 6: Máy cũ
                [
                    'category_id' => $catMap['Máy cũ'],
                    'name' => 'iPhone 15 Pro 128GB Cũ Đẹp',
                    'price' => 21490000,
                    'description' => 'Máy cũ đẹp 99% likenew như mới, bảo hành 6 tháng.',
                    'detail' => 'Cam kết zin nguyên bản 100%, chưa qua sửa chữa thay thế linh kiện, pin trên 90%, đầy đủ phụ kiện sạc cáp.',
                    'stock' => 5,
                    'image' => 'ip15.webp'
                ],
                [
                    'category_id' => $catMap['Máy cũ'],
                    'name' => 'MacBook Air M1 Cũ Likenew',
                    'price' => 13990000,
                    'description' => 'Lựa chọn giá rẻ tối ưu nhất của Apple, máy dùng lướt.',
                    'detail' => 'Ngoại hình đẹp keng không cấn móp, pin sạc ít lần còn rất trâu, bảo hành phần cứng 1 đổi 1 trong 30 ngày.',
                    'stock' => 4,
                    'image' => 'mac_m5.webp'
                ],
                [
                    'category_id' => $catMap['Máy cũ'],
                    'name' => 'Dell XPS 13 Cũ Giá Tốt',
                    'price' => 18900000,
                    'description' => 'Laptop văn phòng sang chảnh, độ bền siêu ổn định.',
                    'detail' => 'Dell XPS cũ qua sử dụng, vỏ nhôm còn rất đẹp, màn hình IPS siêu sáng, hoạt động trơn tru mát mẻ.',
                    'stock' => 3,
                    'image' => 'dell_xps.webp'
                ],
                [
                    'category_id' => $catMap['Máy cũ'],
                    'name' => 'iPad Air 5 M1 Cũ 64GB WiFi',
                    'price' => 11490000,
                    'description' => 'Hiệu năng chip M1 cực khỏe, máy nguyên bản.',
                    'detail' => 'iPad Air 5 cũ phục vụ vẽ đồ họa, ghi chú học tập mượt mà, cảm biến vân tay nhạy bén.',
                    'stock' => 5,
                    'image' => 'tablet_ipad_air.png'
                ],

                // Nhóm 7: Tablet
                [
                    'category_id' => $catMap['Tablet'],
                    'name' => 'iPad Pro M4 11 inch 256GB',
                    'price' => 25990000,
                    'description' => 'iPad mỏng nhất lịch sử, chip M4 siêu khủng, màn hình OLED.',
                    'detail' => 'iPad Pro cao cấp nhất của Apple với màn hình Tandem OLED rực rỡ, chip M4 tối ưu hóa hiệu năng làm việc đồ họa 3D.',
                    'stock' => 6,
                    'image' => 'tablet_ipad_pro.png'
                ],
                [
                    'category_id' => $catMap['Tablet'],
                    'name' => 'iPad Air 6 M2 2026',
                    'price' => 16490000,
                    'description' => 'Sức mạnh từ chip M2 mượt mà, hỗ trợ Apple Pencil Pro.',
                    'detail' => 'iPad Air thế hệ mới nâng cấp cấu hình vượt trội đáp ứng tốt mọi công việc đa nhiệm học tập của bạn.',
                    'stock' => 10,
                    'image' => 'tablet_ipad_air.png'
                ],
                [
                    'category_id' => $catMap['Tablet'],
                    'name' => 'iPad Gen 10 64GB WiFi',
                    'price' => 9490000,
                    'description' => 'Thiết kế thời trang nhiều màu sắc trẻ trung, cổng USB-C.',
                    'detail' => 'iPad tiêu chuẩn màn hình 10.9 inch sắc nét Liquid Retina, nút nguồn tích hợp Touch ID, hỗ trợ bàn phím Magic Keyboard Folio.',
                    'stock' => 16,
                    'image' => 'tablet_ipad.png'
                ]
            ];

            foreach ($products as $p) {
                $productModel->create(array_merge($p, ['status' => 'active']));
            }
        }
    }
}
