# DAYAI - Phase 2 Review & Phase 3 Roadmap

## 1. Kết luận Phase 2

Phase 2 đã đưa DAYAI từ nền MVP sang hệ thống có thể vận hành thực tế ở quy mô nhỏ đến vừa: public website có CMS/SEO, landing khóa học đọc dữ liệu động, CRM có tracking nguồn lead, portal cho phụ huynh/học viên, company portal cho HR, LMS cơ bản và dashboard readiness.

### Trạng thái bàn giao

- Public site có sitemap lớn, menu chính, trang chủ, landing khóa học, form lead và nội dung động.
- CMS có nền SEO/E-E-A-T cho bài viết, tài nguyên, tin tức và video.
- CRM tuyển sinh có nguồn lead, UTM, affiliate/referral code, pipeline, độ nóng lead và follow-up quá hạn.
- Portal phụ huynh/học viên có lịch học, điểm danh, học phí, nhận xét, báo cáo tiến bộ, chứng chỉ, video và LMS cơ bản.
- Company portal có tổng quan nhân sự học, tiến độ nhóm, chứng chỉ, công nợ và thông báo.
- Admin có dashboard vận hành, dashboard doanh thu và readiness report cho Phase 2.
- Có checklist UAT, staging/deploy, backup/restore và release note Phase 2.

### Điều kiện chấp nhận Phase 2

- Chạy được luồng: landing -> lead -> CRM -> đăng ký -> xếp lớp -> điểm danh -> học phí -> portal -> báo cáo.
- Chạy được luồng B2B lite: công ty/HR -> nhân sự học -> company portal -> tiến độ/công nợ.
- Frontend build pass.
- Backend tests trọng yếu pass.
- Code đã được commit/push lên nhánh `develop` sau từng sprint.

## 2. Những việc không kéo tiếp trong Phase 2

Các hạng mục dưới đây không nên nhét thêm vào Phase 2 vì sẽ làm loạn nghiệm thu:

- Đăng nhập portal bằng OTP/tài khoản thật.
- Video player thật và tracking realtime từng giây.
- Affiliate đầy đủ có partner, link, commission và đối soát.
- Automation chăm sóc lead/phụ huynh qua email/Zalo/SMS.
- BI/export nâng cao cho báo cáo tài chính, tuyển sinh và lớp học.
- Payment gateway thật.
- Multi-tenant SaaS hoặc microservices.

## 3. Phase 3 - Mục tiêu

Phase 3 biến hệ thống từ “vận hành được” thành “vận hành an toàn, có tự động hóa, có học online tốt hơn và có tăng trưởng qua affiliate/automation”.

### North Star

DAYAI có thể chạy tuyển sinh, đào tạo, chăm sóc và báo cáo theo quy trình lặp lại; dữ liệu đủ sạch để scale marketing, affiliate, LMS và automation mà không phải sửa lõi.

### Nguyên tắc Phase 3

- Không thay đổi nền tảng công nghệ nếu không có lý do kỹ thuật rõ ràng.
- Ưu tiên hoàn thiện luồng thật hơn là thêm màn hình đẹp.
- Tính năng nào phát sinh phải có vai trò, dữ liệu, quyền, API/UI và tiêu chí nghiệm thu.
- Portal phải chuyển dần từ tra cứu bằng mã sang cơ chế đăng nhập an toàn.
- Affiliate và automation phải dùng cùng CRM source/tracking đã có, không tạo hệ thống song song.

## 4. Roadmap Phase 3

## Sprint 28 - Portal Auth & Security

### Mục tiêu

Biến portal từ tra cứu bằng mã thành khu đăng nhập có kiểm soát cho phụ huynh, học viên và HR.

### Checklist

- [x] Thiết kế auth flow cho portal: OTP hoặc magic link.
- [x] Tạo bảng/token đăng nhập portal có hạn dùng.
- [x] Gửi OTP/magic link qua kênh demo; email/SMS/Zalo nối provider sau.
- [x] Tách quyền portal học viên/phụ huynh; HR đang ở company portal riêng.
- [x] Rate limit request code để chống dò mã.
- [x] Session portal bảo mật bằng access token hết hạn.
- [x] Audit log đăng nhập portal.
- [x] UAT đăng nhập sai/đúng/hết hạn/quá số lần ở mức API trọng yếu.

### Demo cuối sprint

- Phụ huynh/học viên/HR đăng nhập portal bằng OTP hoặc magic link.
- Sai mã hoặc hết hạn thì bị chặn.
- Portal không còn phụ thuộc hoàn toàn vào form tra cứu công khai.

## Sprint 29 - LMS Player & Realtime Progress

### Mục tiêu

Học viên học video trong hệ thống, progress được ghi nhận tự động thay vì nhập dữ liệu mẫu.

### Checklist

- [ ] Tạo trang xem bài học/video riêng.
- [ ] Nhúng player an toàn theo provider.
- [ ] API cập nhật progress theo interval.
- [ ] Ghi nhận last position, percent watched, completed_at.
- [ ] Khóa bài học theo enrollment/access level.
- [ ] Hiển thị tài liệu tải về theo bài học.
- [ ] Resume video từ vị trí xem gần nhất.
- [ ] UAT học/chưa học/đang học/hoàn thành.

### Demo cuối sprint

- Học viên mở bài học, xem video, rời trang rồi quay lại đúng vị trí.
- Dashboard/portal cập nhật tiến độ LMS tự động.

## Sprint 30 - Affiliate & Referral Module

### Mục tiêu

Biến tracking affiliate/referral hiện có thành module tăng trưởng có đối tác, link, hoa hồng và đối soát.

### Checklist

- [ ] Tạo partner/affiliate profile.
- [ ] Tạo affiliate link/code theo campaign/course.
- [ ] Ghi nhận click và lead attribution.
- [ ] Gắn lead -> order -> commission.
- [ ] Trạng thái commission: pending, approved, paid, rejected.
- [ ] Báo cáo hiệu quả affiliate.
- [ ] Portal hoặc màn hình lite cho partner xem kết quả.
- [ ] Quy tắc chống trùng/ghi đè attribution.

### Demo cuối sprint

- Một affiliate link tạo lead.
- Lead chuyển thành đơn hàng.
- Hệ thống tính commission chờ duyệt.

## Sprint 31 - Automation & Notification Workflows

### Mục tiêu

Giảm việc thủ công cho tư vấn, giáo viên và CSKH bằng automation có kiểm soát.

### Checklist

- [ ] Thiết kế workflow trigger/action cơ bản.
- [ ] Trigger lead mới, quá hạn follow-up, lịch học sắp tới, công nợ, báo cáo tiến bộ.
- [ ] Template email/message theo ngữ cảnh.
- [ ] Queue gửi thông báo.
- [ ] Log trạng thái gửi: pending, sent, failed.
- [ ] Rule không spam cùng người nhận.
- [ ] Dashboard automation health.
- [ ] UAT automation bật/tắt/chạy lỗi/retry.

### Demo cuối sprint

- Lead mới nhận email/tin nhắn xác nhận.
- Tư vấn viên được nhắc khi follow-up quá hạn.
- Phụ huynh nhận thông báo lịch học/công nợ theo rule.

## Sprint 32 - BI Reports & Export

### Mục tiêu

Nâng cấp báo cáo từ dashboard xem nhanh sang công cụ quản trị có lọc, xuất file và theo dõi KPI.

### Checklist

- [ ] Báo cáo tuyển sinh theo nguồn, campaign, affiliate, tư vấn viên.
- [ ] Báo cáo lớp học theo attendance, completion, progress.
- [ ] Báo cáo doanh thu/công nợ theo thời gian, khóa, chi nhánh, B2C/B2B.
- [ ] Export Excel/CSV cho các báo cáo chính.
- [ ] Bộ lọc thời gian, khóa học, nguồn lead, tư vấn viên.
- [ ] Chuẩn hóa metric dictionary.
- [ ] Kiểm tra hiệu năng query.
- [ ] UAT số liệu đối chiếu với order/payment/enrollment.

### Demo cuối sprint

- Admin lọc báo cáo theo tháng/campaign/khóa học.
- Kế toán export doanh thu/công nợ.
- Sales export hiệu quả nguồn lead.

## Sprint 33 - Production Hardening & Go-Live

### Mục tiêu

Chuẩn bị hệ thống đủ an toàn để chạy staging/production có kiểm soát.

### Checklist

- [ ] Chuẩn hóa `.env` staging/production.
- [ ] Cấu hình domain, HTTPS, CORS, storage, mail.
- [ ] Backup tự động database/storage.
- [ ] Logging và error monitoring.
- [ ] Health check backend/frontend/database.
- [ ] Migration/rollback runbook.
- [ ] Security review: rate limit, permission, exposed route.
- [ ] UAT full regression trước go-live.

### Demo cuối sprint

- Deploy staging sạch.
- Smoke test pass.
- Có checklist rollback nếu deploy lỗi.

## 5. Ưu tiên nếu nguồn lực hạn chế

Nếu chỉ chọn 3 sprint đầu Phase 3, ưu tiên:

1. Sprint 28 - Portal Auth & Security.
2. Sprint 29 - LMS Player & Realtime Progress.
3. Sprint 30 - Affiliate & Referral Module.

Lý do: đây là ba phần ảnh hưởng trực tiếp đến niềm tin người dùng, trải nghiệm học và tăng trưởng doanh thu.

## 6. Phase 3 Definition of Done

- Có tài liệu nghiệp vụ và tiêu chí nghiệm thu trước khi code.
- Có migration/model/API/UI hoặc admin screen tương ứng.
- Có phân quyền hoặc kiểm soát truy cập.
- Có test cho luồng trọng yếu.
- Frontend lint/build pass nếu chạm frontend.
- Backend test liên quan pass nếu chạm backend.
- Có cập nhật checklist sprint.
- Commit/push lên `develop` sau khi sprint ổn định.

## 7. Quyết định PM

Phase 2 được xem là đóng về mặt scope phát triển. Từ thời điểm này, các yêu cầu mới phải được đưa vào Phase 3 backlog, trừ lỗi blocking trong UAT/staging.
