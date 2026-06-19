# DAYAI - Domain & Content Strategy

Tài liệu này chốt cách hiểu domain để không nhầm website chính với landing page bán khóa.

## 1. Website Chính

- Domain: `dayai.edu.vn`.
- Vai trò: portal thương hiệu và nội dung dài hạn.
- Nội dung chính:
  - Trang chủ thương hiệu DAYAI.
  - Video Academy.
  - Kho kiến thức AI.
  - Tài nguyên / prompt / checklist / case study.
  - Danh sách khóa học.
  - Tin tức, thư viện ảnh/video, liên hệ.
- Mục tiêu: SEO, xây niềm tin, nuôi dưỡng người học, dẫn traffic về các landing page khóa học.

## 2. Landing Page Khóa / Campaign

- Ví dụ domain: `k01.dayai.edu.vn`.
- Route demo local hiện tại: `http://localhost:3000/k01`.
- Route canonical hiện tại: `http://localhost:3000/khoa-hoc/ai-can-ban`.
- Vai trò: landing page bán một khóa hoặc một đợt tuyển sinh cụ thể.
- Nội dung chính:
  - Tên khóa / mã khóa.
  - Lợi ích, đối tượng học, lộ trình.
  - Giảng viên, lịch khai giảng, học phí nếu công khai.
  - Feedback / FAQ / cam kết.
  - Form đăng ký học thử hoặc tư vấn.
- CRM tracking:
  - `utm_source=website`.
  - `utm_medium=course_landing`.
  - `utm_campaign=k01_ai_can_ban`.

## 3. Quy Tắc PM

- Không biến `dayai.edu.vn` thành một landing page duy nhất.
- Mỗi khóa/campaign lớn có landing page riêng.
- Mọi form từ portal và landing đều phải đẩy lead về CRM.
- Campaign/subdomain chỉ là lớp acquisition; dữ liệu thật vẫn quản lý trong CRM, Learning và Finance.

## 4. Hướng Triển Khai Sau MVP

- Cấu hình DNS `k01.dayai.edu.vn` trỏ về frontend.
- Reverse proxy hoặc middleware map host `k01.dayai.edu.vn` tới landing tương ứng.
- Thêm bảng/cấu hình campaign nếu cần quản lý nhiều landing động.
