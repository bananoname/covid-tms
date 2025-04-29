# Hệ Thống Quản Lý Xét Nghiệm COVID-19 - Phòng Thí Nghiệm Thực Hành

Chào mừng bạn đến với phòng lab Hệ Thống Quản Lý Xét Nghiệm COVID-19, phát triển bởi Global Health Corp.

## Mục Đích
Phòng lab này được thiết kế để sinh viên thực hành việc **tìm kiếm** và **khai thác** các lỗ hổng **SQL Injection** và **Prompt Injection** trong một ứng dụng web mô phỏng môi trường thực tế.

⚠️ **Lưu ý:**  
- Ứng dụng có chứa lỗ hổng **SQL Injection** được ẩn giấu, **không chỉ rõ vị trí**. Người học cần tự khám phá và khai thác.
- Ngoài ra còn có một chatbot dễ bị **Prompt Injection**.

## Tính Năng
- Trang thông tin về COVID-19
- Liệt kê triệu chứng COVID-19
- Hệ thống đăng ký xét nghiệm
- Cập nhật kết quả xét nghiệm trực tuyến
- Chatbot tư vấn COVID-19 (**dễ bị Prompt Injection**)
- Cổng đăng nhập Admin (**dễ bị SQL Injection**)

## Hướng Dẫn Sử Dụng

1. Cài đặt máy chủ web cục bộ (ví dụ: XAMPP, WAMP, Laragon).
2. Sao chép thư mục lab vào thư mục `htdocs` của máy chủ web.
3. Khởi động dịch vụ Apache và MySQL.
4. Import file cơ sở dữ liệu `covid19_lab.sql` vào MySQL server.
5. Truy cập hệ thống qua địa chỉ `http://localhost/[tên-thư-mục]/index.html`.

## Mục Tiêu Thực Hành
- Tìm và khai thác **SQL Injection**.
- Tìm và khai thác **Prompt Injection**.
- Thu thập thông tin nhạy cảm (flag) được giấu.

## Gợi Ý
- Hãy quan sát kỹ các **form đăng nhập**, **khung chat chatbot**, và các request **GET/POST**.
- Không phải lỗ hổng nào cũng "lộ liễu" ngay từ đầu!

---

© 2025 Global Health Corp. All rights reserved.