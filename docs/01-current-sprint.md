# DAYAI - Current Sprint

File này dùng để biết ngay dự án đang ở đâu và bước tiếp theo là gì. Nếu có thay đổi ưu tiên, cập nhật file này trước rồi mới triển khai.

## Sprint 0 - Product Foundation

### Mục Tiêu

- [x] Khóa scope MVP P0.
- [x] Khóa mô hình dữ liệu lõi.
- [x] Khóa luồng nghiệp vụ chính.
- [x] Chuẩn bị đủ thông tin để scaffold backend/frontend mà không bị đổi hướng liên tục.

### Việc Đang Cần Làm Ngay

- [x] Review `docs/00-pm-master-checklist.md`.
- [x] Tách MVP P0 thành user stories cụ thể.
- [x] Thiết kế database schema P0: bảng, cột, quan hệ, enum/status.
- [x] Thiết kế module backend Laravel theo boundary rõ ràng.
- [x] Thiết kế menu Filament admin MVP.
- [x] Thiết kế public website / landing page cần những trang nào.
- [x] Chốt demo flow đầu tiên: lead -> tư vấn -> đăng ký -> xếp lớp -> điểm danh -> thu phí.

### Kết Quả Cần Có Sau Sprint 0

- [x] Một bộ schema database P0 có thể triển khai.
- [x] Một backlog user stories MVP có ưu tiên rõ.
- [x] Một danh sách màn hình admin và website cần làm.
- [x] Một kế hoạch Sprint 1 để bắt đầu code.

## Sprint 1 - Backend Core & Admin Base

### Mục Tiêu

- [x] Dựng nền backend và admin để bắt đầu nhập dữ liệu thật.

### Dự Kiến Việc Làm

- [x] Scaffold Laravel backend.
- [x] Cấu hình PostgreSQL, Redis, Docker.
- [x] Cài Filament admin.
- [x] Tạo authentication admin.
- [x] Tạo roles/permissions cơ bản.
- [x] Tạo entity lõi: people, organizations, users.
- [x] Tạo audit log cơ bản.

### Demo Cuối Sprint 1

- [x] Admin đăng nhập được.
- [x] Tạo/sửa/xem cá nhân.
- [x] Tạo/sửa/xem doanh nghiệp.
- [x] Gán role cơ bản cho user.
- [x] Xem audit log thao tác quan trọng.

## Sprint 2 - CRM Tuyển Sinh

### Mục Tiêu

- [x] Quản lý lead và lịch sử tư vấn.

### Dự Kiến Việc Làm

- [x] Tạo lead sources.
- [x] Tạo leads.
- [x] Tạo lead assignments.
- [x] Tạo consultation activities.
- [x] Tạo trial registrations.
- [x] Tạo trạng thái lead.
- [x] Chuyển lead thành customer/student/company.

### Demo Cuối Sprint 2

- [x] Nhập lead mới.
- [x] Phân loại lead theo phụ huynh, sinh viên, chủ doanh nghiệp, công ty.
- [x] Gán tư vấn viên.
- [x] Ghi lịch sử tư vấn.
- [x] Chuyển lead thành hồ sơ khách hàng.

## Sprint 3 - Learning Core

### Mục Tiêu

- [x] Quản lý khóa học, lớp học, học viên và điểm danh cơ bản.

### Dự Kiến Việc Làm

- [x] Tạo courses.
- [x] Tạo course modules.
- [x] Tạo class groups.
- [x] Tạo class sessions.
- [x] Tạo student profiles.
- [x] Tạo guardian relations.
- [x] Tạo enrollments.
- [x] Tạo attendance.

### Demo Cuối Sprint 3

- [x] Tạo khóa học.
- [x] Tạo lớp học.
- [x] Xếp học viên vào lớp.
- [x] Điểm danh một buổi học.
- [x] Xem lịch sử điểm danh của học viên.

## Sprint 4 - Finance MVP

### Mục Tiêu

- [x] Quản lý đăng ký khóa học, học phí, thanh toán và công nợ.

### Dự Kiến Việc Làm

- [x] Tạo customer accounts.
- [x] Tạo orders.
- [x] Tạo order items.
- [x] Tạo invoices.
- [x] Tạo payments.
- [x] Tạo receivables.
- [x] Tạo receipts.
- [x] Tạo báo cáo doanh thu cơ bản.

### Demo Cuối Sprint 4

- [x] Tạo đơn đăng ký khóa học.
- [x] Ghi nhận người mua khác người học.
- [x] Ghi nhận thanh toán một phần/toàn phần.
- [x] Xem công nợ còn lại.
- [x] In/xem phiếu thu cơ bản.

## Sprint 5 - Website & Lead Capture

### Mục Tiêu

- [x] Website và landing page gửi lead vào CRM.

### Dự Kiến Việc Làm

- [x] Scaffold Next.js frontend.
- [x] Tạo trang chủ.
- [x] Tạo trang khóa học.
- [x] Tạo landing page khóa học.
- [x] Tạo form tư vấn.
- [x] Tạo form học thử.
- [x] Gửi form về backend CRM.

### Demo Cuối Sprint 5

- [x] Khách truy cập landing page.
- [x] Điền form đăng ký tư vấn/học thử.
- [x] Lead xuất hiện trong admin CRM.
- [x] Tư vấn viên nhận và xử lý lead.
- [x] Tách trang chủ DAYAI khỏi landing page từng khóa.
- [x] Trang chủ có khu video academy và kho kiến thức AI.
- [x] Bổ sung chiến lược domain: `dayai.edu.vn` là portal, `k01.dayai.edu.vn` là landing campaign.
- [x] Thêm route demo local `/k01` cho landing khóa/campaign.

## Sprint 6 - Phase 1 End-to-End Hardening

### Mục Tiêu

- [x] Kiểm thử, vá lỗi và demo trọn luồng Phase 1.

### Dự Kiến Việc Làm

- [x] Rà lại route admin và frontend.
- [x] Rà chiến lược domain/campaign trước khi deploy.
- [x] Chuẩn hóa dữ liệu demo end-to-end.
- [x] Test lead website -> CRM -> tư vấn -> chuyển đổi.
- [x] Test đăng ký khóa học -> xếp lớp -> điểm danh -> thu phí.
- [x] Ghi lại hướng dẫn demo Phase 1.

### Demo Cuối Sprint 6

- [x] Một lead từ website đi hết luồng đến thu phí.
- [x] Admin xem được CRM, learning, finance và báo cáo.
- [x] Có checklist nghiệm thu Phase 1.

## Sprint 7 - Content & Video CMS

### Mục Tiêu

- [x] Quản trị được nội dung website chính: danh mục, bài viết/tài nguyên và video academy.

### Dự Kiến Việc Làm

- [x] Tạo content categories.
- [x] Tạo content items cho bài viết, checklist, prompt library, case study.
- [x] Tạo video lessons cho Video Academy.
- [x] Tạo admin resources nhóm `Nội dung`.
- [x] Seed dữ liệu demo cho kho kiến thức và video.

### Demo Cuối Sprint 7

- [x] Admin thấy menu `Nội dung`.
- [x] Quản trị được `Danh mục nội dung`.
- [x] Quản trị được `Bài viết & tài nguyên`.
- [x] Quản trị được `Video Academy`.
- [x] Có dữ liệu demo để frontend sau này đọc động.

## Sprint 8 - Public Content API & Dynamic Portal

### Mục Tiêu

- [x] Website chính đọc nội dung động từ CMS thay vì hardcode Video Academy và Kho kiến thức.

### Dự Kiến Việc Làm

- [x] Tạo public API `GET /api/content/home`.
- [x] Tạo Next.js proxy `GET /api/content/home`.
- [x] Trang chủ đọc dynamic `knowledge_items`.
- [x] Trang chủ đọc dynamic `video_lessons`.
- [x] Có fallback nội dung khi backend API chưa sẵn sàng.
- [x] Thêm backend test cho public content API.

### Demo Cuối Sprint 8

- [x] Admin nhập/xem nội dung trong nhóm `Nội dung`.
- [x] Public API trả bài viết/video đã xuất bản.
- [x] Trang chủ hiển thị bài viết/video từ CMS.
- [x] Frontend build pass.
- [x] Backend test pass.

## Sprint 9 - Parent & Student Portal Lite

### Mục Tiêu

- [x] Tạo cổng tra cứu phụ huynh/học viên bản nhẹ bằng số điện thoại và mã học viên.

### Dự Kiến Việc Làm

- [x] Tạo API `POST /api/portal/lookup`.
- [x] Trả thông tin học viên, khóa đang học, lớp học.
- [x] Trả lịch học và điểm danh.
- [x] Trả học phí, đã thanh toán và công nợ.
- [x] Trả video học liên quan từ Video Academy.
- [x] Tạo frontend page `/portal`.
- [x] Thêm tests cho portal lookup API.

### Demo Cuối Sprint 9

- [x] Phụ huynh/học viên mở `/portal`.
- [x] Nhập SĐT + mã học viên.
- [x] Xem lịch học, điểm danh, công nợ và video liên quan.
- [x] Sai thông tin thì báo không tìm thấy.

## Sprint 10 - Student Progress Reports

### Mục Tiêu

- [x] Quản lý đánh giá, nhận xét giáo viên và báo cáo tiến bộ để portal theo dõi được phát triển học viên.

### Dự Kiến Việc Làm

- [x] Tạo bảng `assessments`.
- [x] Tạo bảng `assessment_results`.
- [x] Tạo bảng `teacher_comments`.
- [x] Tạo bảng `progress_reports`.
- [x] Tạo Filament admin resources nhóm `Tiến bộ học viên`.
- [x] Seed dữ liệu demo cho học viên `HV-000001`.
- [x] Mở rộng API `POST /api/portal/lookup`.
- [x] Mở rộng frontend `/portal` hiển thị tiến độ, điểm đánh giá và nhận xét.
- [x] Thêm backend test cho dữ liệu progress trong portal.

### Demo Cuối Sprint 10

- [x] Admin thấy nhóm menu `Tiến bộ học viên`.
- [x] Admin quản lý được bài đánh giá, kết quả đánh giá, nhận xét giáo viên và báo cáo tiến bộ.
- [x] Portal demo hiển thị `% tiến độ`, điểm đánh giá đầu vào và nhận xét giáo viên.
- [x] Backend test pass.
- [x] Frontend lint/build pass.

## Sprint 11 - Company / HR Portal Lite

### Mục Tiêu

- [x] Hoàn thiện mảnh B2B lite cho công ty mua khóa học cho nhân sự: HR tra cứu danh sách học viên, tiến độ và công nợ.

### Dự Kiến Việc Làm

- [x] Tận dụng schema `organizations`, `organization_contacts`, `student_profiles.organization_id`, `customer_accounts.organization_id`.
- [x] Tạo API `POST /api/company-portal/lookup`.
- [x] Seed công ty demo `Example Corp` và HR `hr@examplecorp.test`.
- [x] Seed 2 nhân sự học theo công ty.
- [x] Seed order/invoice/payment B2B demo.
- [x] Tạo Next.js proxy `POST /api/company-portal/lookup`.
- [x] Tạo frontend page `/company-portal`.
- [x] Thêm link `HR Portal` trên homepage.
- [x] Thêm backend test cho company portal lookup API.

### Demo Cuối Sprint 11

- [x] HR mở `/company-portal`.
- [x] Nhập email HR và mã công ty.
- [x] Xem danh sách nhân sự đang học.
- [x] Xem tiến độ, điểm đánh giá, điểm danh từng nhân sự.
- [x] Xem tổng học phí, đã thanh toán và công nợ B2B.
- [x] Backend test pass.
- [x] Frontend lint/build pass.

## Sprint 12 - Notification Center Lite

### Mục Tiêu

- [x] Có hệ thống thông báo cơ bản để admin đăng thông báo và portal hiển thị cho học viên/phụ huynh/HR.

### Dự Kiến Việc Làm

- [x] Tạo bảng `notifications`.
- [x] Tạo model `Notification`.
- [x] Gắn thông báo với `person`, `student_profile`, `organization`.
- [x] Tạo Filament admin resource `Thông báo`.
- [x] Seed thông báo demo cho học viên `HV-000001`.
- [x] Seed thông báo demo cho HR `Example Corp`.
- [x] Mở rộng API `POST /api/portal/lookup`.
- [x] Mở rộng API `POST /api/company-portal/lookup`.
- [x] Hiển thị thông báo trên `/portal` và `/company-portal`.
- [x] Thêm backend tests cho notifications trong portal API.

### Demo Cuối Sprint 12

- [x] Admin quản lý được `Thông báo` trong nhóm `Nội dung`.
- [x] Portal phụ huynh/học viên hiển thị thông báo lịch học, học phí, tiến bộ.
- [x] Portal doanh nghiệp/HR hiển thị thông báo tiến độ nhóm và công nợ B2B.
- [x] Backend test pass.
- [x] Frontend lint/build pass.

## Sprint 13 - Operations & Admissions Dashboard

### Mục Tiêu

- [x] Có một màn dashboard điều hành để xem nhanh tuyển sinh, vận hành lớp học, tiến độ học viên và tài chính.

### Dự Kiến Việc Làm

- [x] Tạo Filament page `Dashboard vận hành`.
- [x] Thêm KPI lead tổng, lead mới, chuyển đổi.
- [x] Thêm KPI học viên đang học, lớp hoạt động, lịch sắp tới.
- [x] Thêm KPI doanh số, đã thu, công nợ.
- [x] Thêm phễu tuyển sinh theo trạng thái lead.
- [x] Thêm hiệu quả nguồn lead.
- [x] Thêm lịch học sắp tới.
- [x] Thêm danh sách học viên cần chú ý theo điểm danh.
- [x] Thêm doanh thu theo khóa.
- [x] Thêm backend test cho dashboard metrics.

### Demo Cuối Sprint 13

- [x] Admin mở được `Dashboard vận hành`.
- [x] Xem KPI tuyển sinh, vận hành, tài chính.
- [x] Xem phễu lead và hiệu quả nguồn lead.
- [x] Xem lớp/buổi học sắp tới và học viên cần chú ý.
- [x] Backend test pass.
- [x] Frontend lint/build pass.

## Sprint 14 - Course Certificates

### Mục Tiêu

- [x] Quản lý chứng chỉ hoàn thành khóa học và hiển thị chứng chỉ trong portal học viên/HR.

### Dự Kiến Việc Làm

- [x] Tạo bảng `certificates`.
- [x] Tạo model `Certificate`.
- [x] Gắn chứng chỉ với học viên, enrollment, khóa học, lớp học.
- [x] Tạo admin resource `Chứng chỉ` trong nhóm `Tiến bộ học viên`.
- [x] Seed chứng chỉ demo cho học viên `HV-000001`.
- [x] Seed chứng chỉ demo cho nhân sự công ty `Example Corp`.
- [x] Mở rộng API `POST /api/portal/lookup`.
- [x] Mở rộng API `POST /api/company-portal/lookup`.
- [x] Hiển thị chứng chỉ trên `/portal`.
- [x] Hiển thị trạng thái chứng chỉ trong `/company-portal`.
- [x] Thêm backend tests cho chứng chỉ trong portal API.

### Demo Cuối Sprint 14

- [x] Admin quản lý được `Chứng chỉ`.
- [x] Portal học viên/phụ huynh hiển thị chứng chỉ đã cấp.
- [x] Portal doanh nghiệp/HR hiển thị chứng chỉ từng nhân sự.
- [x] Backend test pass.
- [x] Frontend lint/build pass.

## Sprint 15 - Role-Based Access Control

### Mục Tiêu

- [x] Thiết lập phân quyền MVP cho admin theo vai trò: admin, sales, teacher, accountant.

### Dự Kiến Việc Làm

- [x] Tạo permission map chuẩn cho CRM, học tập, tài chính, tiến bộ, nội dung, quản trị.
- [x] Seed permissions và gán quyền theo role.
- [x] Áp permission vào toàn bộ Filament Resource.
- [x] Khóa dashboard vận hành theo `view_dashboard`.
- [x] Khóa báo cáo doanh thu theo `manage_finance`.
- [x] Thêm backend tests cho ma trận quyền role.

### Demo Cuối Sprint 15

- [x] Admin xem được toàn bộ resource.
- [x] Sales xem được CRM nhưng không xem được tài chính.
- [x] Accountant xem được tài chính nhưng không xem được CRM.
- [x] Teacher xem được học tập/tiến bộ nhưng không xem được tài chính.
- [x] Backend test pass.

## Sprint 16 - Demo Role Accounts

### Mục Tiêu

- [x] Tạo tài khoản demo cố định theo từng role để kiểm thử admin trực tiếp trên browser.

### Dự Kiến Việc Làm

- [x] Seed tài khoản `sales@dayai.edu.vn`.
- [x] Seed tài khoản `teacher@dayai.edu.vn`.
- [x] Seed tài khoản `accountant@dayai.edu.vn`.
- [x] Gán role đúng cho từng tài khoản demo.
- [x] Dùng chung mật khẩu demo `password`.
- [x] Thêm backend test đảm bảo tài khoản demo được seed đúng role.

### Demo Cuối Sprint 16

- [x] Đăng nhập admin bằng `admin@dayai.edu.vn`.
- [x] Đăng nhập sales bằng `sales@dayai.edu.vn`.
- [x] Đăng nhập teacher bằng `teacher@dayai.edu.vn`.
- [x] Đăng nhập accountant bằng `accountant@dayai.edu.vn`.
- [x] Kiểm tra mỗi role chỉ thấy đúng nhóm menu được phân quyền.

## Sprint 17 - Phase 1 Closure UAT

### Mục Tiêu

- [x] Có checklist nghiệm thu Phase 1 để kiểm thử toàn bộ luồng demo mà không phát sinh scope mới.

### Dự Kiến Việc Làm

- [x] Tạo tài liệu `Phase 1 Closure UAT Checklist`.
- [x] Chốt nguyên tắc không thêm module mới trong UAT.
- [x] Chốt checklist public website, CRM, học tập, tài chính, portal, phân quyền.
- [x] Thêm bug bash template.
- [x] Chốt điều kiện đóng Phase 1.

## Sprint 18 - Production Readiness

### Mục Tiêu

- [x] Có checklist triển khai staging/production cho môi trường thật.

### Dự Kiến Việc Làm

- [x] Tạo checklist environment production.
- [x] Tạo checklist domain/routing/HTTPS.
- [x] Tạo checklist security, backup, logging, deployment, rollback.
- [x] Chốt điều kiện go-live.

## Sprint 19 - SEO & Content Launch

### Mục Tiêu

- [x] Chuẩn bị SEO kỹ thuật và checklist nội dung thật trước khi public website.

### Dự Kiến Việc Làm

- [x] Cập nhật metadata global cho Next.js.
- [x] Thêm metadata riêng cho landing `AI Căn Bản`.
- [x] Thêm `robots.txt`.
- [x] Thêm `sitemap.xml`.
- [x] Tạo checklist nội dung thật cho trang chủ, landing, kiến thức, video.

## Luồng Demo MVP Cuối Cùng

- [x] Khách vào landing page.
- [x] Khách gửi form đăng ký tư vấn.
- [x] Lead vào CRM.
- [x] Tư vấn viên gọi và cập nhật lịch sử tư vấn.
- [x] Admin xem dashboard tuyển sinh và vận hành.
- [x] Lead chuyển thành khách hàng/học viên/công ty.
- [x] Tạo đăng ký khóa học.
- [x] Xếp học viên vào lớp.
- [x] Điểm danh buổi học.
- [x] Xem đánh giá, nhận xét giáo viên và báo cáo tiến bộ.
- [x] HR xem danh sách nhân sự học, tiến độ nhóm và công nợ B2B.
- [x] Xem thông báo từ trung tâm trên portal.
- [x] Xem chứng chỉ hoàn thành khóa học.
- [x] Kiểm tra phân quyền admin theo vai trò.
- [x] Đăng nhập bằng tài khoản demo từng role.
- [x] Chạy checklist UAT đóng Phase 1.
- [x] Kiểm tra production readiness.
- [x] Kiểm tra SEO/content launch checklist.
- [x] Ghi nhận thanh toán.
- [x] Xem công nợ và phiếu thu.

## Phase 2 Review & Phase 3 Planning

### M?c Ti�u

- [x] Ch?t tr?ng th�i b�n giao Phase 2.
- [x] T�ch ph?n c�n n? sang Phase 3 thay v� k�o d�i Phase 2.
- [x] L?p roadmap Phase 3 g?m Sprint 28-33.
- [x] Uu ti�n 3 sprint d?u: Portal Auth, LMS Player, Affiliate.

### T�i Li?u Ch�nh

- [x] `docs/18-phase2-review-phase3-roadmap.md`

## Sprint 28 - Portal Auth & Security

### M?c Ti�u

- [x] Chuy?n portal ph? huynh/h?c vi�n sang flow x�c th?c OTP demo tru?c khi tr? d? li?u.
- [x] T?o token portal c� h?n d�ng v� access token d� verify.
- [x] B?t `/api/portal/lookup` y�u c?u `portal_access_token` h?p l?.
- [x] Th�m rate limit request OTP.
- [x] C?p nh?t frontend `/portal` th�nh 2 bu?c: g?i m� v� x�c th?c.
- [x] Th�m test b?o v? lu?ng lookup thi?u token.
- [ ] T�ch s�u quy?n h?c vi�n/ph? huynh/HR v� audit log dang nh?p portal.

### Sprint 28 Completion Update

- [x] T�ch quy?n h?c vi�n/ph? huynh b?ng `access_role`; HR v?n thu?c company portal ri�ng.
- [x] Ghi audit log request OTP, verify th�nh c�ng v� verify th?t b?i.
- [x] Ki?m th? student token kh�ng xem du?c nh?n x�t ch? d�nh cho ph? huynh.

## Sprint 29 - LMS Player & Realtime Progress

### M?c Ti�u

- [x] T?o API portal lesson detail c� ki?m tra token, enrollment v� access level.
- [x] T?o API c?p nh?t ti?n d? b�i h?c: ph?n tram, v? tr� xem g?n nh?t, tr?ng th�i ho�n th�nh.
- [x] T?o trang `/portal/bai-hoc/[slug]` d? h?c vi�n xem video v� t�i li?u.
- [x] N?i n�t `V�o h?c` t? LMS trong portal sang trang b�i h?c.
- [x] Luu session portal ? `sessionStorage` sau OTP d? trang b�i h?c d?c du?c.
- [x] Th�m test cho lesson detail v� progress update.
- [ ] Tracking t? d?ng theo interval/player event s? l�m s�u hon n?u c?n.

## Sprint 30 - Affiliate & Referral Module

### M?c Ti�u

- [x] T?o schema affiliate partner, affiliate link, click v� commission.
- [x] Public lead affiliate t? ghi nh?n click attribution.
- [x] Order c� lead affiliate t? t?o commission pending.
- [x] Commission c� tr?ng th�i d? duy?t/tr? sau: pending, approved, paid, rejected.
- [x] Test lead affiliate -> click -> order -> commission.
- [ ] Dashboard hi?u qu? affiliate v� portal d?i t�c l�m ? bu?c ti?p theo n?u c?n.

### Sprint 30 Deepening Update

- [x] Th�m admin resources cho partner, link, click v� commission affiliate.
- [x] Th�m affiliate performance v�o dashboard v?n h�nh.
- [x] B? sung readiness metrics: partner active, click, hoa h?ng ch? duy?t.
- [x] G?n quy?n truy c?p affiliate theo nh�m CRM/finance.
- [x] B? sung test dashboard affiliate.
- [ ] Portal ho?c m�n h�nh lite cho partner xem k?t qu? s? t�ch sang backlog sau Sprint 30.

### Sprint 30 Affiliate Closure Update

- [x] Th�m API `affiliate-portal/lookup` cho d?i t�c xem hi?u qu? theo m� partner/link.
- [x] Th�m trang `/affiliate-portal` cho d?i t�c xem click, lead, hoa h?ng v� link chi?n d?ch.
- [x] Ch?n index SEO cho affiliate portal.
- [x] B? sung test nghi?m thu affiliate portal.

## Sprint 31 - Automation & Notification Workflows

### M?c Ti�u

- [x] T?o l�i automation cham s�c lead, nh?c follow-up, l?ch h?c v� c�ng n?.
- [x] D�ng notification portal l�m outbox demo tru?c khi t�ch h?p email/Zalo/SMS th?t.

### Checklist

- [x] T?o schema automation workflows, messages v� logs.
- [x] T?o runner service c� ch?ng g?i l?p b?ng cooldown.
- [x] Seed workflow m?c d?nh cho DAYAI.
- [x] Th�m command `automation:run`.
- [x] Th�m dashboard automation health.
- [x] Test lead confirmation v� ch?ng spam trong cooldown.
- [ ] T�ch h?p provider g?i th?t email/Zalo/SMS ? sprint sau n?u c?n.
