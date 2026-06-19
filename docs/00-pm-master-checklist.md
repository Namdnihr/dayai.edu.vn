# DAYAI - PM Master Checklist

Tài liệu này là nguồn kiểm soát chính của dự án. Mỗi lần bắt đầu một hạng mục mới, phải đối chiếu file này trước để tránh làm lệch scope, trùng việc hoặc thiếu luồng nghiệp vụ.

## 1. Mục Tiêu Sản Phẩm

- [ ] Xây dựng hệ thống cho trung tâm đào tạo AI, không chỉ là website giới thiệu.
- [ ] Bao phủ hành trình: khách quan tâm -> tư vấn -> đăng ký -> xếp lớp -> học -> điểm danh -> thu phí -> báo cáo tiến bộ -> chăm sóc lại.
- [ ] Hỗ trợ cả B2C và B2B:
  - [ ] Phụ huynh mua khóa học cho con.
  - [ ] Sinh viên tự đăng ký học.
  - [ ] Chủ doanh nghiệp tự đi học.
  - [ ] Công ty mua khóa học cho nhân sự.
- [ ] Thiết kế theo nguyên tắc: người mua, người học, người thanh toán, người theo dõi tiến độ có thể là các đối tượng khác nhau.

## 2. Công Nghệ Đã Chốt

- [ ] Public website / portal: Next.js + TypeScript + Tailwind CSS.
- [x] Backend core: Laravel.
- [x] Admin / CRM nội bộ: Filament for Laravel.
- [x] Database: PostgreSQL.
- [x] Cache / queue: Redis.
- [ ] AI chatbot: OpenAI API tích hợp qua backend.
- [ ] Triển khai: Docker + Nginx + VPS hoặc cloud server.
- [ ] Kiến trúc giai đoạn đầu: modular monolith, chưa tách microservices.
- [ ] Chuẩn bị scale sau này bằng module boundary, queue, audit log, phân quyền, tenant/organization structure.

## 3. Vai Trò Người Dùng

- [ ] Super Admin: quản trị toàn hệ thống.
- [ ] Admin Trung Tâm: cấu hình khóa học, lớp học, nhân sự, báo cáo.
- [ ] Nhân Viên Tuyển Sinh / CSKH: quản lý lead, tư vấn, chăm sóc.
- [ ] Giáo Viên / Mentor: xem lớp, điểm danh, nhận xét, báo cáo tiến độ.
- [ ] Kế Toán: học phí, công nợ, phiếu thu, báo cáo doanh thu.
- [x] Học Viên: xem lịch học, tiến độ, tài liệu, chứng chỉ.
- [ ] Phụ Huynh: xem lịch học, điểm danh, học phí, tiến độ của con.
- [x] HR / Đại Diện Doanh Nghiệp: xem danh sách nhân sự học, tiến độ nhóm và công nợ B2B lite.

## 4. Module Tổng Thể

- [ ] Website giới thiệu trung tâm.
- [ ] Landing page khóa học.
- [ ] CRM tuyển sinh.
- [ ] Quản lý khách hàng cá nhân và doanh nghiệp.
- [ ] Quản lý học viên và phụ huynh.
- [x] Quản lý công ty, HR và nhân sự học.
- [ ] Quản lý khóa học, lớp học, lịch học.
- [ ] Điểm danh, nghỉ học, học bù.
- [ ] Quản lý học phí, công nợ, phiếu thu.
- [ ] Hợp đồng đào tạo B2B.
- [x] Cổng học viên.
- [x] Cổng phụ huynh.
- [x] Cổng doanh nghiệp / HR.
- [x] Báo cáo phát triển học viên.
- [ ] Chatbot AI tuyển sinh và CSKH.
- [x] Thông báo portal lite; email / SMS / Zalo triển khai sau MVP nếu cần.
- [ ] Admin, phân quyền, audit log.

## 5. MVP P0 - Bắt Buộc Làm Trước

### 5.1 Website & Landing Page

- [x] Trang chủ giới thiệu trung tâm.
- [x] Tách `dayai.edu.vn` là portal chính, không phải landing page đơn lẻ.
- [x] Trang giới thiệu.
- [x] Trang danh sách khóa học.
- [x] Landing page chi tiết từng khóa học.
- [x] Landing campaign có thể chạy qua subdomain, ví dụ `k01.dayai.edu.vn`.
- [x] Khu đào tạo bằng video trên website chính.
- [x] Khu kiến thức / tài nguyên AI trên website chính.
- [x] Form đăng ký tư vấn.
- [x] Form đăng ký học thử.
- [x] Form liên hệ.
- [x] Quản lý nội dung cơ bản: banner, khóa học, tin tức, thư viện ảnh/video.
- [x] Tất cả form phải đẩy dữ liệu về CRM lead.

### 5.2 CRM Tuyển Sinh

- [ ] Tạo lead thủ công từ admin.
- [x] Nhận lead tự động từ website / landing page.
- [ ] Phân loại lead:
  - [ ] Phụ huynh.
  - [ ] Sinh viên.
  - [ ] Chủ doanh nghiệp.
  - [ ] Công ty / HR.
- [ ] Quản lý nguồn lead: website, Facebook, TikTok, Zalo, referral, sự kiện, chatbot.
- [ ] Trạng thái lead: mới, đang tư vấn, hẹn học thử, đã đăng ký, không phù hợp, mất liên hệ.
- [ ] Lịch sử tư vấn: cuộc gọi, tin nhắn, ghi chú, lịch hẹn.
- [ ] Phân công tư vấn viên phụ trách.
- [ ] Nhắc lịch follow-up cơ bản.
- [x] Chuyển lead thành khách hàng / học viên / công ty.

### 5.3 Khách Hàng & Học Viên

- [ ] Hồ sơ cá nhân dùng chung cho phụ huynh, học viên, sinh viên, chủ doanh nghiệp, HR, giáo viên.
- [ ] Hồ sơ học viên thực tế tham gia học.
- [ ] Quan hệ phụ huynh - con.
- [x] Quan hệ công ty - HR - nhân sự học.
- [ ] Phân loại học viên: học sinh, sinh viên, người đi làm, chủ doanh nghiệp, nhân sự công ty.
- [ ] Lịch sử đăng ký khóa học.
- [ ] Ghi chú nội bộ về nhu cầu, mục tiêu học, năng lực đầu vào.

### 5.4 Khóa Học & Lớp Học

- [ ] Danh mục khóa học.
- [ ] Module / buổi học trong khóa.
- [ ] Lớp học cụ thể.
- [ ] Gán giáo viên / mentor cho lớp.
- [ ] Lịch học theo ngày, giờ, hình thức học.
- [ ] Danh sách học viên trong lớp.
- [ ] Trạng thái lớp: sắp mở, đang học, hoàn thành, tạm dừng.

### 5.5 Điểm Danh & Học Bù

- [ ] Điểm danh theo từng buổi học.
- [ ] Trạng thái điểm danh: có mặt, vắng, đi muộn, xin nghỉ.
- [ ] Ghi chú lý do nghỉ.
- [ ] Tạo lịch học bù thủ công.
- [ ] Xem lịch sử tham gia của từng học viên.

### 5.6 Học Phí & Công Nợ

- [x] Tạo đơn đăng ký khóa học.
- [x] Gắn đơn hàng với người mua.
- [x] Gắn enrollment với người học.
- [x] Hỗ trợ người mua là cá nhân hoặc doanh nghiệp.
- [x] Ghi nhận học phí phải thu.
- [x] Ghi nhận thanh toán.
- [x] Theo dõi công nợ còn lại.
- [x] Tạo phiếu thu cơ bản.
- [x] Báo cáo doanh thu cơ bản theo ngày/tháng/khóa học.

### 5.7 Admin & Phân Quyền MVP

- [x] Đăng nhập admin.
- [x] Vai trò: admin, tuyển sinh, giáo viên, kế toán.
- [ ] Mỗi vai trò chỉ thấy các menu và dữ liệu cần thiết.
- [x] Audit log cơ bản cho các thao tác quan trọng: tạo lead, chuyển trạng thái, thu tiền, sửa điểm danh.

## 6. P1 - Làm Sau Khi MVP Chạy Được

- [x] Cổng học viên lite: lịch học, khóa đang học, điểm danh, video và tiến độ.
- [x] Cổng phụ huynh: lịch học của con, điểm danh, học phí, báo cáo tiến bộ.
- [x] Cổng doanh nghiệp / HR: danh sách nhân sự học, tiến độ, báo cáo nhóm.
- [x] Báo cáo tiến bộ: đánh giá đầu vào, nhận xét giáo viên, biểu đồ phát triển.
- [x] Chứng chỉ hoàn thành khóa học.
- [x] Thông báo portal lite; email / SMS / Zalo.
- [x] Dashboard tuyển sinh: nguồn lead, tỷ lệ chuyển đổi, hiệu quả tư vấn viên.
- [ ] Hợp đồng B2B nâng cao.
- [ ] Import danh sách nhân sự từ Excel.

## 7. P2 - Scale & Tối Ưu

- [ ] Chatbot AI tuyển sinh và CSKH.
- [ ] RAG / knowledge base cho chatbot từ tài liệu khóa học, FAQ, chính sách học phí.
- [ ] Tự động nhắc lịch học, nhắc đóng học phí, nhắc follow-up.
- [x] Dashboard điều hành toàn trung tâm.
- [ ] Báo cáo tài chính nâng cao.
- [ ] Multi-branch: nhiều cơ sở / chi nhánh.
- [ ] Multi-tenant: bán SaaS cho nhiều trung tâm khác.
- [ ] Read replica / reporting database nếu báo cáo nặng.
- [ ] Tách AI service hoặc reporting service nếu tải lớn.

## 8. Database Entity Cần Có

### 8.1 Core Identity

- [ ] people: mọi cá nhân trong hệ thống.
- [ ] user_accounts: tài khoản đăng nhập.
- [ ] roles: vai trò.
- [ ] permissions: quyền.
- [ ] organizations: công ty, trường, đối tác.
- [x] organization_contacts: HR, quản lý đào tạo, người đại diện công ty.
- [ ] guardian_relations: quan hệ phụ huynh - học viên.

### 8.2 CRM

- [ ] lead_sources.
- [ ] leads.
- [ ] lead_assignments.
- [ ] consultation_activities.
- [ ] trial_registrations.
- [ ] campaigns.

### 8.3 Learning

- [ ] student_profiles.
- [ ] teacher_profiles.
- [ ] courses.
- [ ] course_modules.
- [ ] class_groups.
- [ ] class_sessions.
- [ ] enrollments.
- [ ] attendance.
- [ ] makeup_sessions.

### 8.4 Finance

- [x] customer_accounts.
- [x] orders.
- [x] order_items.
- [x] invoices.
- [x] payments.
- [x] receivables.
- [x] receipts.
- [ ] discounts.
- [ ] training_contracts.

### 8.5 Reports & Communication

- [x] content_categories.
- [x] content_items.
- [x] video_lessons.
- [x] assessments.
- [x] assessment_results.
- [x] progress_reports.
- [x] teacher_comments.
- [x] certificates.
- [x] notifications.
- [ ] chatbot_conversations.
- [ ] chatbot_messages.

## 9. Luồng Nghiệp Vụ Cần Khóa

### 9.1 Phụ Huynh Mua Cho Con

- [ ] Phụ huynh để lại thông tin.
- [ ] CRM tạo lead loại phụ huynh.
- [ ] Tư vấn viên liên hệ và ghi lịch sử tư vấn.
- [ ] Tạo hồ sơ phụ huynh.
- [ ] Tạo hồ sơ học viên là con.
- [ ] Liên kết phụ huynh - con.
- [ ] Phụ huynh mua khóa học.
- [ ] Con được xếp lớp và học.
- [ ] Phụ huynh xem điểm danh, học phí, tiến độ.

### 9.2 Sinh Viên Tự Đăng Ký

- [ ] Sinh viên để lại thông tin.
- [ ] CRM tạo lead loại sinh viên.
- [ ] Tư vấn viên xác định mục tiêu học.
- [ ] Sinh viên là người mua và người học.
- [ ] Đăng ký khóa học.
- [ ] Thanh toán học phí.
- [x] Vào lớp, điểm danh, nhận chứng chỉ.

### 9.3 Chủ Doanh Nghiệp Đi Học

- [ ] Chủ doanh nghiệp để lại thông tin.
- [ ] CRM tạo lead loại chủ doanh nghiệp.
- [ ] Tư vấn theo nhu cầu ứng dụng AI vào kinh doanh.
- [ ] Người mua và người học có thể là cùng một người.
- [ ] Có thể gắn thêm organization nếu cần.
- [ ] Theo dõi tiến độ cá nhân và nhu cầu mua thêm cho nhân sự.

### 9.4 Công Ty Mua Cho Nhân Sự

- [ ] HR hoặc đại diện công ty để lại thông tin.
- [ ] CRM tạo lead loại công ty.
- [ ] Tư vấn nhu cầu đào tạo theo phòng ban.
- [ ] Tạo hồ sơ organization.
- [x] Tạo contact HR / người đại diện.
- [x] Tạo đơn hàng B2B.
- [x] Tạo danh sách nhân sự học.
- [x] Xếp lớp cho nhân sự công ty.
- [x] Theo dõi điểm danh, tiến độ, công nợ.
- [x] HR xem báo cáo nhóm.

## 10. Màn Hình MVP Cần Làm

### 10.1 Public Site

- [x] Trang chủ.
- [x] Giới thiệu trung tâm.
- [x] Danh sách khóa học.
- [x] Chi tiết khóa học / landing page.
- [x] Tin tức.
- [ ] Thư viện ảnh/video.
- [x] Liên hệ.
- [x] Form đăng ký tư vấn.
- [x] Form đăng ký học thử.

### 10.2 Admin / CRM

- [ ] Dashboard tổng quan.
- [ ] Danh sách lead.
- [ ] Chi tiết lead.
- [ ] Tạo/sửa lead.
- [ ] Lịch sử tư vấn.
- [ ] Phân công tư vấn viên.
- [ ] Danh sách khách hàng cá nhân.
- [ ] Danh sách công ty.
- [ ] Chi tiết công ty và HR.

### 10.3 Learning Admin

- [ ] Danh sách học viên.
- [ ] Chi tiết học viên.
- [ ] Danh sách phụ huynh.
- [ ] Danh sách khóa học.
- [ ] Chi tiết khóa học.
- [ ] Danh sách lớp học.
- [ ] Chi tiết lớp học.
- [ ] Lịch học.
- [ ] Điểm danh buổi học.
- [ ] Học bù.

### 10.4 Finance Admin

- [x] Đơn đăng ký khóa học.
- [x] Hóa đơn / khoản phải thu.
- [x] Thanh toán.
- [x] Công nợ.
- [x] Phiếu thu.
- [x] Báo cáo doanh thu cơ bản.

## 11. Quy Tắc Không Được Phá

- [x] Không gắn cứng học phí vào học viên; phải gắn với người mua / customer account.
- [x] Không coi phụ huynh, học viên, HR, giáo viên là các bảng hoàn toàn rời rạc; dùng people làm lõi.
- [x] Không thiết kế chỉ cho B2C rồi vá B2B sau.
- [x] Không làm chatbot AI trước khi CRM và dữ liệu khóa học ổn.
- [x] Không làm mobile app native ở MVP.
- [x] Không tách microservices ở MVP.
- [x] Không thêm tính năng mới nếu chưa biết module, dữ liệu, vai trò, màn hình, tiêu chí nghiệm thu.
- [x] Không triển khai báo cáo nâng cao khi dữ liệu điểm danh, học phí, tiến độ chưa chuẩn.

## 12. Definition Of Done Cho Mỗi Tính Năng

- [x] Có mô tả nghiệp vụ rõ ràng.
- [x] Có vai trò người dùng được phép thao tác.
- [x] Có dữ liệu đầu vào / đầu ra.
- [x] Có màn hình hoặc API tương ứng.
- [x] Có trạng thái lỗi / trường hợp rỗng.
- [x] Có phân quyền cơ bản.
- [x] Có audit log nếu là thao tác quan trọng.
- [x] Có tiêu chí nghiệm thu.
- [x] Có dữ liệu mẫu để demo.
- [x] Đã kiểm thử luồng chính.

## 13. Thứ Tự Thực Hiện Đề Xuất

- [ ] Bước 1: Chốt MVP scope lần cuối.
- [x] Bước 2: Vẽ database schema chi tiết cho P0.
- [x] Bước 3: Tạo Laravel backend + PostgreSQL + Redis + Docker.
- [x] Bước 4: Cài Filament admin.
- [x] Bước 5: Tạo entity core: people, organizations, users, roles.
- [x] Bước 6: Làm CRM lead.
- [x] Bước 7: Làm customer/student/guardian/company structure.
- [x] Bước 8: Làm courses/classes/sessions/enrollments.
- [x] Bước 9: Làm attendance.
- [x] Bước 10: Làm finance cơ bản.
- [x] Bước 11: Làm website + landing + form đẩy lead.
- [x] Bước 12: Demo end-to-end lead -> đăng ký -> xếp lớp -> điểm danh -> thu phí.
- [x] Bước 13: Khóa phân quyền MVP theo role vận hành.

## 13.1 Sprint 15 - RBAC / Phân Quyền MVP

- [x] Role `admin` có toàn quyền.
- [x] Role `sales` có quyền dashboard, CRM, nội dung.
- [x] Role `teacher` có quyền dashboard, học tập, tiến bộ học viên.
- [x] Role `accountant` có quyền dashboard, tài chính.
- [x] Filament Resource được khóa theo permission map.
- [x] Dashboard vận hành được khóa theo `view_dashboard`.
- [x] Báo cáo doanh thu được khóa theo `manage_finance`.
- [x] Có test chống lệch quyền giữa sales/accountant/teacher/admin.

## 13.2 Sprint 16 - Tài Khoản Demo Theo Role

- [x] Có tài khoản admin demo `admin@dayai.edu.vn`.
- [x] Có tài khoản sales demo `sales@dayai.edu.vn`.
- [x] Có tài khoản teacher demo `teacher@dayai.edu.vn`.
- [x] Có tài khoản accountant demo `accountant@dayai.edu.vn`.
- [x] Tất cả tài khoản demo dùng mật khẩu `password`.
- [x] Tất cả tài khoản demo có trạng thái `active`.
- [x] Có test đảm bảo seeder tạo đúng tài khoản và role.

## 13.3 Sprint 17-19 - Đóng Phase 1

- [x] Có checklist UAT toàn luồng Phase 1.
- [x] Có bug bash template để gom lỗi thay vì thêm scope.
- [x] Có production readiness checklist.
- [x] Có checklist domain, HTTPS, backup, logging, rollback.
- [x] Có metadata SEO global cho website.
- [x] Có metadata riêng cho landing khóa học.
- [x] Có sitemap và robots.
- [x] Có checklist nội dung thật trước go-live.
- [x] Phase 1 chuyển sang trạng thái sẵn sàng UAT/bàn giao nội bộ.

## 13.4 Pre-Handover - Sitemap Redesign

- [x] Có sitemap tổng thể cho public website.
- [x] Có checklist section cho trang chủ `dayai.edu.vn`.
- [x] Có checklist section cho landing page khóa học/campaign.
- [x] Có checklist data/API cần chuẩn bị khi redesign.
- [x] Có SEO/mobile acceptance criteria cho redesign.
- [x] Chốt nguyên tắc không biến trang chủ thành landing đơn lẻ.
- [x] Có full SEO sitemap dài hạn theo 5 tệp khách hàng, tài nguyên, công cụ và tin tức AI.
- [x] Có menu chính: Đối tượng học, Khóa học AI, Giải pháp doanh nghiệp, Tài nguyên, Công cụ AI, Cập nhật AI, Liên hệ.

## 14. Checklist Trước Mỗi Sprint

- [ ] Sprint này thuộc module nào?
- [ ] Tính năng này thuộc P0, P1 hay P2?
- [ ] Người dùng chính là ai?
- [ ] Có ảnh hưởng đến database entity nào?
- [ ] Có liên quan tài chính hoặc phân quyền không?
- [ ] Có liên quan B2B không?
- [ ] Có cần notification hoặc audit log không?
- [ ] Kết quả demo cuối sprint là gì?

## 15. Backlog Ngay Sau Tài Liệu Này

- [x] Tạo database schema chi tiết cho P0.
- [x] Tạo danh sách bảng, cột, quan hệ, enum/status.
- [ ] Tạo wireframe màn hình admin MVP.
- [x] Tạo backlog user stories theo module.
- [x] Tạo kế hoạch sprint 1.
- [x] Scaffold dự án backend sau khi schema được chốt.
- [ ] Scaffold dự án frontend sau khi backend core ổn định.
