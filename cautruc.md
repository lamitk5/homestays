homestay-project/
│
├── uploads/              # Thư mục chứa ảnh (tự tạo)
├── trang_chu.php         # Trang chủ khách hàng
├── dangnhap.php          # Đăng nhập (User + Admin)
├── dangky.php            # Đăng ký user
├── dashboard.php         # Trang quản trị
├── sidebar.php           # Menu sidebar admin
├── qly_home.php          # Quản lý homestay
├── qly_datphong.php      # Quản lý đơn đặt phòng
├── quanlykh.php          # Quản lý khách hàng
├── them_homestay_moi.php # Thêm homestay
├── sua_homestay.php      # Sửa homestay
├── reports.php           # Báo cáo
├── settings.php          # Cài đặt
├── logout.php            # Đăng xuất user
└── homestays.sql         # Database
├── chi_tiet_home.php     # Thông tin homestay
├── chitiet_kh.php        # Thông tin khách hàng
├── chi_tiet_blog.php     # Thông tin về mục khám phá

1. User vào trang_chu.php
2. Chọn homestay → chi_tiet_home.php?id=X
3. Chọn ngày check-in, check-out, số khách
4. Hệ thống tự động tính:
   - Số đêm
   - Giá ngày thường/cuối tuần
   - Phí dịch vụ 150k
   - Tổng tiền
5. Nhấn "Đặt phòng ngay"
6. xuly_datphong.php xử lý:
   - Validate dữ liệu
   - Kiểm tra phòng trống
   - Lưu vào database
   - Thông báo thành công
7. Admin vào dashboard.php → Thấy biểu đồ cập nhật real-time
