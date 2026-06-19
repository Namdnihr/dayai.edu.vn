# DAYAI - Phase 1 Demo Runbook

Tài liệu này dùng để demo/nghiệm thu Phase 1 theo một luồng thống nhất, tránh mở màn hình lung tung.

## 1. Link Demo

- Portal chính: `http://localhost:3000`
- Landing campaign K01: `http://localhost:3000/k01`
- Admin CRM/Learning/Finance: `http://localhost:8080/admin`
- Backend health: `http://localhost:8080/up`

## 2. Tài Khoản Admin

- Email: `admin@dayai.edu.vn`
- Password: `password`

## 3. Chuẩn Bị Dữ Liệu

Chạy lại dữ liệu demo nếu cần:

```bash
docker --config .docker compose up -d backend nginx postgres redis
docker --config .docker compose run --rm backend php artisan migrate:fresh --seed --force
cd frontend && npm.cmd run dev
```

## 4. Luồng Demo Website -> CRM

1. Mở `http://localhost:3000`.
2. Xem portal chính gồm Video Academy, Kho kiến thức AI, Khóa học & lộ trình.
3. Mở landing campaign `http://localhost:3000/k01`.
4. Điền form đăng ký tư vấn/học thử.
5. Vào admin `http://localhost:8080/admin`.
6. Mở menu `Leads`.
7. Kiểm tra lead mới có:
   - Nguồn: `Website`.
   - Campaign: `k01_ai_can_ban` hoặc campaign tương ứng.
   - Loại khách: phụ huynh, sinh viên, chủ doanh nghiệp hoặc công ty.

## 5. Luồng Demo CRM -> Hồ Sơ Thật

1. Trong danh sách lead, chọn lead cần demo.
2. Bấm hành động `Chuyển hồ sơ`.
3. Nhập tên người học nếu người mua khác người học.
4. Kiểm tra sau chuyển đổi:
   - Lead chuyển trạng thái `Đã đăng ký`.
   - Có hồ sơ `People`.
   - Có `Student Profile`.
   - Có `Customer Account`.
   - Nếu lead phụ huynh: có `Guardian Relation`.
   - Nếu lead công ty: có `Organization` và contact HR/owner.

## 6. Luồng Demo Learning

1. Mở `Courses` để xem khóa `AI Căn Bản Cho Người Mới`.
2. Mở `Class Groups` để xem lớp demo `AI-0626-01`.
3. Mở `Enrollments` để xem học viên được xếp lớp.
4. Mở `Attendance Records` để xem điểm danh buổi học demo.

## 7. Luồng Demo Finance

1. Mở `Orders` để xem đơn đăng ký khóa học.
2. Mở `Invoices` để xem học phí phải thu.
3. Mở `Payments` để xem thanh toán một phần/toàn phần.
4. Mở `Receivables` để xem công nợ còn lại.
5. Mở `Receipts` để xem phiếu thu.
6. Mở `Báo cáo doanh thu` để xem:
   - Doanh số đăng ký.
   - Đã thu.
   - Công nợ.
   - Doanh thu theo khóa học.

## 8. Tiêu Chí Nghiệm Thu Phase 1

- [x] Portal chính không bị hiểu nhầm là landing page đơn lẻ.
- [x] Landing K01 hoạt động và có form.
- [x] Form tạo lead thật trong CRM.
- [x] Lead phân loại đúng 4 nhóm khách.
- [x] Chuyển lead tạo đúng buyer/student/company structure.
- [x] Có khóa học, lớp học, enrollment, attendance demo.
- [x] Có order, invoice, payment, receivable, receipt demo.
- [x] Có báo cáo doanh thu cơ bản.
- [x] Admin đăng nhập được bằng tài khoản demo.
- [x] Frontend build pass.
- [x] Backend test pass.

## 9. Ghi Chú PM

- `dayai.edu.vn` là portal chính để làm thương hiệu, SEO, video, kiến thức và nội dung dài hạn.
- `k01.dayai.edu.vn` là landing/campaign cho một đợt tuyển sinh cụ thể.
- Giao diện sẽ được tinh chỉnh sau; Phase 1 ưu tiên đúng luồng dữ liệu và module nghiệp vụ.

## 10. Demo Sprint 7 - Nội Dung

1. Vào admin `http://localhost:8080/admin`.
2. Mở nhóm menu `Nội dung`.
3. Kiểm tra `Danh mục nội dung` có `Kho kiến thức AI` và `Video Academy`.
4. Kiểm tra `Bài viết & tài nguyên` có bài viết/checklist/prompt library demo.
5. Kiểm tra `Video Academy` có video demo với quyền xem `public`, `lead_magnet`, `student`.

## 11. Demo Sprint 8 - Public Content API

1. Mở `http://localhost:8080/api/content/home`.
2. Kiểm tra JSON có `knowledge_items` và `video_lessons`.
3. Mở `http://localhost:3000`.
4. Kiểm tra khu `Video Academy` và `Kho kiến thức AI` hiển thị nội dung từ CMS.
5. Nếu backend tạm chưa chạy, frontend vẫn có fallback nội dung để không vỡ trang.

## 12. Demo Sprint 9 - Portal Phụ Huynh / Học Viên

1. Mở `http://localhost:3000/portal`.
2. Dùng dữ liệu demo:
   - SĐT: `0901888000`
   - Mã học viên: `HV-000001`
3. Bấm `Tra cứu`.
4. Kiểm tra portal hiển thị:
   - Hồ sơ học viên.
   - Khóa/lớp đang học.
   - Lịch học.
   - Điểm danh.
   - Tổng học phí, đã thanh toán, công nợ.
   - Video học liên quan.
5. Nhập sai thông tin để kiểm tra trạng thái không tìm thấy.

## 13. Demo Sprint 10 - Tiến Bộ Học Viên

1. Vào admin `http://localhost:8080/admin`.
2. Mở nhóm menu `Tiến bộ học viên`.
3. Kiểm tra 4 màn:
   - `Bài đánh giá`.
   - `Kết quả đánh giá`.
   - `Nhận xét giáo viên`.
   - `Báo cáo tiến bộ`.
4. Mở `http://localhost:3000/portal`.
5. Dùng dữ liệu demo:
   - SĐT: `0901888000`
   - Mã học viên: `HV-000001`
6. Kiểm tra portal hiển thị:
   - Báo cáo tiến bộ tuần 1 với `% tiến độ`.
   - Kết quả đánh giá đầu vào.
   - Nhận xét giáo viên.

## 14. Demo Sprint 11 - Cổng Doanh Nghiệp / HR

1. Mở `http://localhost:3000/company-portal`.
2. Dùng dữ liệu demo:
   - Email HR: `hr@examplecorp.test`
   - Mã công ty: `EXAMPLE-CORP`
3. Bấm `Tra cứu doanh nghiệp`.
4. Kiểm tra portal hiển thị:
   - Công ty `Example Corp`.
   - 2 nhân sự đang học.
   - Tiến độ trung bình nhóm.
   - Tiến độ, điểm đánh giá và điểm danh từng nhân sự.
   - Tổng học phí, đã thanh toán và công nợ B2B.
5. Nhập sai email HR để kiểm tra trạng thái không tìm thấy.

## 15. Demo Sprint 12 - Thông Báo Portal

1. Vào admin `http://localhost:8080/admin`.
2. Mở nhóm menu `Nội dung` -> `Thông báo`.
3. Kiểm tra có thông báo demo cho:
   - Học viên/phụ huynh.
   - Doanh nghiệp/HR.
4. Mở `http://localhost:3000/portal`.
5. Tra cứu bằng:
   - SĐT: `0901888000`
   - Mã học viên: `HV-000001`
6. Kiểm tra section `Thông báo từ trung tâm`.
7. Mở `http://localhost:3000/company-portal`.
8. Tra cứu bằng:
   - Email HR: `hr@examplecorp.test`
   - Mã công ty: `EXAMPLE-CORP`
9. Kiểm tra section `Thông báo cho HR`.

## 16. Demo Sprint 13 - Dashboard Tuyển Sinh & Vận Hành

1. Vào admin `http://localhost:8080/admin`.
2. Mở menu `Dashboard vận hành`.
3. Kiểm tra các KPI:
   - Lead tổng, lead mới, tỷ lệ chuyển đổi.
   - Học viên đang học, lớp hoạt động, lịch học sắp tới.
   - Doanh số, đã thu, công nợ.
   - Tiến độ trung bình.
4. Kiểm tra các bảng:
   - Phễu tuyển sinh.
   - Hiệu quả nguồn lead.
   - Lịch học sắp tới.
   - Học viên cần chú ý.
   - Doanh thu theo khóa.

## 17. Demo Sprint 14 - Chứng Chỉ Khóa Học

1. Vào admin `http://localhost:8080/admin`.
2. Mở nhóm menu `Tiến bộ học viên` -> `Chứng chỉ`.
3. Kiểm tra chứng chỉ demo:
   - `CERT-DAYAI-000001` cho học viên `HV-000001`.
   - `CERT-HV-B2B-001` và `CERT-HV-B2B-002` cho nhân sự công ty.
4. Mở `http://localhost:3000/portal`.
5. Tra cứu bằng:
   - SĐT: `0901888000`
   - Mã học viên: `HV-000001`
6. Kiểm tra section `Chứng chỉ`.
7. Mở `http://localhost:3000/company-portal`.
8. Tra cứu bằng:
   - Email HR: `hr@examplecorp.test`
   - Mã công ty: `EXAMPLE-CORP`
9. Kiểm tra mỗi nhân sự có trạng thái chứng chỉ.

## 18. Demo Sprint 15 - Phân Quyền Admin Theo Role

1. Vào admin `http://localhost:8080/admin`.
2. Đăng nhập bằng tài khoản admin demo:
   - Email: `admin@dayai.edu.vn`
   - Password: `password`
3. Kiểm tra admin xem được toàn bộ nhóm menu.
4. Với tài khoản role `sales`, kiểm tra:
   - Xem được CRM/lead.
   - Không xem được tài chính.
5. Với tài khoản role `accountant`, kiểm tra:
   - Xem được đơn hàng, hóa đơn, thanh toán, công nợ.
   - Không xem được CRM/lead.
6. Với tài khoản role `teacher`, kiểm tra:
   - Xem được lớp học, điểm danh, báo cáo tiến bộ.
   - Không xem được tài chính.
7. Chạy backend test phân quyền để xác nhận ma trận role không bị lệch.

## 19. Demo Sprint 16 - Tài Khoản Demo Theo Role

1. Vào admin `http://localhost:8080/admin`.
2. Đăng nhập lần lượt bằng các tài khoản demo:
   - Admin: `admin@dayai.edu.vn` / `password`
   - Sales: `sales@dayai.edu.vn` / `password`
   - Teacher: `teacher@dayai.edu.vn` / `password`
   - Accountant: `accountant@dayai.edu.vn` / `password`
3. Kiểm tra nhanh theo vai trò:
   - Admin thấy toàn bộ menu.
   - Sales thấy CRM/lead và nội dung, không thấy tài chính.
   - Teacher thấy lớp học, điểm danh, tiến bộ, không thấy tài chính.
   - Accountant thấy tài chính, không thấy CRM/lead.
4. Nếu vừa pull code hoặc reset database, chạy lại seeder trước khi demo.

## 20. Demo Sprint 17-19 - Closure / Production / SEO

1. Mở checklist UAT: `docs/08-phase1-closure-uat.md`.
2. Đi lần lượt các luồng:
   - Public website.
   - CRM tuyển sinh.
   - Học tập/lớp học/điểm danh.
   - Tài chính.
   - Portal phụ huynh/học viên.
   - Portal doanh nghiệp/HR.
   - Phân quyền role.
3. Mở checklist production: `docs/09-production-readiness.md`.
4. Kiểm tra các mục cần làm trên server thật:
   - Domain/HTTPS.
   - `.env.production`.
   - Backup database.
   - Logging/monitoring.
   - Rollback.
5. Mở checklist SEO/content: `docs/10-seo-content-launch-checklist.md`.
6. Kiểm tra SEO kỹ thuật:
   - `http://localhost:3000/robots.txt`
   - `http://localhost:3000/sitemap.xml`
7. Xác nhận nội dung thật cần bổ sung trước go-live:
   - Giảng viên.
   - Ảnh/video thật.
   - FAQ khóa học.
   - Bài viết kiến thức.
   - Video public/free.

## 21. Pre-Handover - Sitemap Trước Khi Redesign

1. Mở tài liệu sitemap redesign: `docs/11-home-landing-sitemap-redesign.md`.
2. Chốt route public website nào làm ngay, route nào để Phase 1.5/Phase 2.
3. Chốt checklist trang chủ:
   - Header/navigation.
   - Hero.
   - Nhóm đối tượng học.
   - Khóa học nổi bật.
   - Video Academy.
   - Kho kiến thức.
   - AI cho doanh nghiệp.
   - Giảng viên/social proof/FAQ/footer.
4. Chốt checklist landing page:
   - Hero bán khóa.
   - Đối tượng phù hợp.
   - Pain point/outcome.
   - Lộ trình.
   - Giảng viên.
   - Lịch học/học phí/FAQ/form lead.
5. Sau khi checklist được duyệt mới bắt đầu làm lại UI trang chủ và landing.
