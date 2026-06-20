# DAYAI Phase 2 - UAT, Staging & Release Note

## 1. Mục tiêu Sprint 27

Sprint 27 đóng Phase 2 bằng bộ kiểm thử nghiệm thu, hướng dẫn staging/deploy, checklist backup/restore và release note để team có thể bàn giao, kiểm thử và vận hành có trật tự.

## 2. Dashboard cần kiểm tra

### Dashboard tuyển sinh

- Lead tổng, lead mới, lead đã chuyển đổi.
- Tỷ lệ chuyển đổi từ lead sang đăng ký.
- Phễu lead theo trạng thái.
- Hiệu quả nguồn lead, bao gồm website, ads, social, referral và affiliate.
- Lead quá hạn follow-up.
- Hiệu quả tư vấn viên.

### Dashboard học viên/lớp học

- Số học viên đang học.
- Số lớp đang tuyển sinh/đang hoạt động.
- Lịch học sắp tới.
- Học viên cần chú ý dựa trên vắng/muộn/xin nghỉ.
- Tiến độ học trung bình từ báo cáo giáo viên.
- Tiến độ video LMS đã ghi nhận.

### Dashboard doanh thu/công nợ

- Doanh số đăng ký.
- Số tiền đã thu.
- Công nợ còn lại.
- Doanh thu theo tháng.
- Doanh thu theo khóa học.
- Số hóa đơn/phiếu thu.

## 3. Checklist UAT theo vai trò

### Admin

- Đăng nhập admin panel.
- Xem Dashboard vận hành.
- Xem Phase 2 readiness.
- Tạo/sửa khóa học, module, video lesson.
- Tạo/sửa bài viết, tin tức, tài nguyên.
- Kiểm tra phân quyền admin/tư vấn/giáo viên/kế toán.

### Tư vấn tuyển sinh

- Tạo lead từ form website.
- Kiểm tra UTM, nguồn lead, affiliate/referral code.
- Phân công người phụ trách.
- Cập nhật trạng thái tư vấn.
- Ghi lịch sử chăm sóc.
- Chuyển lead thành học viên/đăng ký.

### Giáo viên

- Xem lớp và buổi học.
- Điểm danh học viên.
- Nhập nhận xét buổi học.
- Tạo báo cáo tiến bộ.
- Xuất bản nhận xét/báo cáo cho phụ huynh hoặc học viên.

### Phụ huynh

- Vào portal bằng số điện thoại và mã học viên.
- Xem lịch học, điểm danh, học phí/công nợ.
- Xem nhận xét giáo viên, báo cáo tiến bộ, chứng chỉ.
- Xem thông báo từ trung tâm.

### Học viên

- Vào portal bằng số điện thoại và mã học viên.
- Xem khóa đang học.
- Xem module, video lesson, tài liệu học.
- Xem trạng thái hoàn thành bài học.
- Xem tiến độ LMS tổng thể.

### Doanh nghiệp/HR

- Vào company portal.
- Xem danh sách nhân sự đang học.
- Xem tiến độ học viên theo công ty.
- Xem công nợ/đơn đăng ký theo doanh nghiệp.
- Xem thông báo dành cho doanh nghiệp.

## 4. Checklist staging/deploy

### Trước deploy

- Pull code mới nhất từ nhánh `develop`.
- Kiểm tra file `.env` staging không dùng credential local.
- Cấu hình `APP_ENV=staging`.
- Cấu hình `APP_DEBUG=false`.
- Cấu hình database staging riêng.
- Cấu hình domain staging, CORS, mail, storage.
- Chạy `npm.cmd run lint`.
- Chạy `npm.cmd run build`.
- Chạy backend test trọng yếu.

### Deploy backend

- Build/recreate container backend nếu dùng Docker.
- Chạy migration: `php artisan migrate --force`.
- Chạy seed dữ liệu mẫu staging nếu cần.
- Chạy `php artisan optimize:clear`.
- Kiểm tra admin panel truy cập được.
- Kiểm tra API public course/content/lead/portal.

### Deploy frontend

- Build Next.js production.
- Kiểm tra biến môi trường API endpoint.
- Kiểm tra sitemap/robots.
- Kiểm tra trang chủ, landing page, portal, company portal.
- Kiểm tra form đăng ký tư vấn gửi lead thành công.

## 5. Checklist backup/restore

### Backup trước deploy

- Dump database staging/production.
- Backup file upload/storage.
- Ghi lại commit hash đang chạy.
- Ghi lại migration batch hiện tại.
- Lưu bản `.env` hiện hành ở nơi bảo mật.

### Restore khi có sự cố

- Đưa site về maintenance mode nếu cần.
- Restore database từ dump gần nhất.
- Restore storage/upload.
- Checkout lại commit ổn định trước deploy.
- Chạy `php artisan optimize:clear`.
- Kiểm tra smoke test: trang chủ, admin, form lead, portal.

## 6. Release note Phase 2

### Hoàn thành

- CMS/SEO EEAT cho bài viết, tin tức, tài nguyên và nội dung AI.
- Public course API và landing page khóa học đọc dữ liệu động.
- Tracking UTM, nguồn lead, referral và affiliate code.
- CRM tuyển sinh nâng cấp pipeline, nguồn lead, độ nóng lead và overdue follow-up.
- Portal phụ huynh/học viên có lịch học, điểm danh, công nợ, nhận xét, báo cáo tiến bộ, chứng chỉ.
- Company portal cho doanh nghiệp/HR theo dõi nhân sự học AI.
- LMS cơ bản gồm khóa đang học, module, video lesson, tài liệu và tiến độ học.
- Dashboard vận hành có readiness report cho Phase 2.

### Còn lại cho Phase 3

- Auth/OTP cho portal thay vì tra cứu bằng mã.
- Video player thật và tracking realtime khi học viên xem video.
- Affiliate module đầy đủ: partner, link, commission, đối soát.
- Automation chăm sóc lead/phụ huynh qua email/Zalo/SMS.
- Báo cáo BI nâng cao và export Excel/PDF.
