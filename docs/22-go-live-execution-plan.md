# DAYAI Go-Live Execution Plan

## 1. Mục tiêu

Đưa DAYAI từ môi trường phát triển/closed beta lên production theo từng hạng mục nhỏ, có kiểm thử và cổng nghiệm thu rõ ràng. Không chuyển phase khi còn lỗi P0/P1 hoặc chưa có bằng chứng nghiệm thu.

## 2. Trạng thái baseline ngày 31/07/2026

- Frontend `npm run lint`: pass.
- Frontend `npm run build`: pass, 129 trang được sinh thành công.
- Backend: 29 test pass, 217 assertions.
- Health check local: app/database/cache đều `ok`.
- LMS đã có khóa học, module, nhiều video nhỏ, quiz tương tác, ghi chú và tiến độ học.
- Hệ thống hiện chỉ phù hợp cho local/closed beta:
  - Thanh toán vẫn là `test_gateway`.
  - OTP và xác minh email chưa gửi qua provider thật.
  - Một số API dữ liệu nhạy cảm chưa có auth production.
  - Compose chưa có frontend production và scheduler.
  - Video nội bộ vẫn dùng public disk.
  - Chưa có backup/restore và monitoring production thực tế.

## 3. Nguyên tắc triển khai

1. Làm từng hạng mục theo đúng thứ tự trong phase.
2. Mỗi hạng mục phải có test hoặc checklist kiểm chứng.
3. Không dùng dữ liệu production để chạy test tự động.
4. Không deploy khi chưa có backup và phương án rollback.
5. Thanh toán, phân quyền và dữ liệu học viên là nhóm P0.
6. Mỗi phase kết thúc bằng một bản release có commit/tag rõ ràng.

## Phase 0 — Đóng băng baseline và bảo vệ dữ liệu

### Hạng mục

- [x] G0.1 Rà toàn bộ thay đổi chưa commit, tách thay đổi hợp lệ khỏi file tạm.
- [x] G0.2 Chốt một commit/tag baseline có thể rollback.
- [x] G0.3 Tạo bản backup PostgreSQL và storage hiện tại.
- [x] G0.4 Viết và chạy thử lệnh restore trên môi trường test.
- [x] G0.5 Chuẩn hóa test dùng SQLite/test database, tuyệt đối không chạm database đang vận hành.
- [x] G0.6 Ghi smoke-test baseline: homepage, admin, portal, lesson, quiz và course builder.

### Bằng chứng nghiệm thu ngày 31/07/2026

- Git audit: không phát hiện secret thật; video demo 10.582.699 byte được giữ vì đang được seeder sử dụng; `git diff --check` không có lỗi whitespace.
- Backup PostgreSQL: `backups/phase0-20260731-1105/dayai-postgres.dump`, SHA-256 `74439D2EA3D66458CB0BFD3A1EA054BF328525BA1BFCF4359B4BC97655BF2E89`.
- Restore drill: khôi phục thành công vào database tạm với 29 migration, 4 user, 1 khóa học, 8 video và 1 tenant; database tạm đã được xóa sau kiểm tra.
- Storage: đã sao lưu vào `backups/phase0-20260731-1105/storage-app`; thư mục `backups/` được loại khỏi Git.
- Test backend an toàn: `docker compose --profile test run --rm --build backend-test`; 29 test pass, 217 assertions và không thay đổi số bản ghi PostgreSQL đang chạy.
- Frontend: `npm run lint` pass; `npm run build` pass và sinh 129 trang.
- Health check: app, PostgreSQL và Redis đều `ok`.
- Smoke test production build: homepage, đăng nhập OTP portal, dashboard học viên, trang bài học tương tác, ghi chú, phòng thực hành, quiz liên quan và course builder 3 module/8 video đều tải đúng.
- Sự cố được xử lý: việc chạy test trong container backend trước đây đã dùng nhầm PostgreSQL đang chạy và làm trống dữ liệu demo. Dữ liệu demo đã được seed lại; service `backend-test` dùng SQLite memory đã ngăn tái diễn.

### Cổng nghiệm thu

- Có commit/tag baseline.
- Có file backup và restore thử thành công.
- Frontend build/lint pass.
- Backend test pass.
- Không còn file tạm hoặc secret bị đưa vào Git.

## Phase 1 — Khóa lỗ hổng bảo mật và API P0

### Hạng mục

- [ ] G1.1 Checkout chỉ nhận `course_slug`; tên, giá và trạng thái khóa học phải lấy từ database.
- [ ] G1.2 Không cho public request tự tạo khóa học, module hoặc video mẫu.
- [ ] G1.3 Xóa hoặc khóa tuyệt đối endpoint `mark-paid` thử khỏi production.
- [ ] G1.4 Bảo vệ API xuất BI/CSV bằng đăng nhập và permission.
- [ ] G1.5 Chuyển Company Portal và Affiliate Portal sang token/session có thời hạn.
- [ ] G1.6 Thêm rate limit cho lead, register, login, checkout và các portal lookup.
- [ ] G1.7 Chốt CORS theo domain thật; thêm security headers tại reverse proxy.
- [ ] G1.8 Rà log để không ghi password, OTP, token hoặc secret.
- [ ] G1.9 Viết test cho toàn bộ tình huống tấn công và truy cập trái quyền ở trên.

### Cổng nghiệm thu

- Không thể sửa giá từ trình duyệt.
- Không thể tự đánh dấu thanh toán thành công.
- Không thể tải báo cáo hoặc xem dữ liệu công ty/affiliate khi chưa xác thực.
- Security tests pass, không còn route demo nhạy cảm mở ở production.

## Phase 2 — Tài khoản, OTP và email thật

### Hạng mục

- [ ] G2.1 Chọn provider email và cấu hình SMTP/API production.
- [ ] G2.2 Gửi email xác minh tài khoản thật, có thời hạn và chống dùng lại token.
- [ ] G2.3 Gửi OTP portal qua kênh thật; production không sử dụng `channel=demo`.
- [ ] G2.4 Đăng nhập trả về session/token an toàn; bổ sung đăng xuất và thu hồi phiên.
- [ ] G2.5 Bổ sung quên mật khẩu/đặt lại mật khẩu.
- [ ] G2.6 Thêm cooldown, giới hạn sai mật khẩu/OTP và audit log.
- [ ] G2.7 Đổi toàn bộ mật khẩu demo; bật 2FA hoặc giới hạn truy cập cho admin.
- [ ] G2.8 Kiểm thử email delivery, spam, link hết hạn và phiên đăng nhập hết hạn.

### Cổng nghiệm thu

- Người dùng mới tự đăng ký, xác minh, đăng nhập và khôi phục mật khẩu được.
- Học viên nhận OTP thật và vào đúng dữ liệu của mình.
- Không lộ token/OTP trong response production.

## Phase 3 — Thanh toán thật và đối soát

### Hạng mục

- [ ] G3.1 Chọn cổng thanh toán phù hợp và chốt tài khoản merchant.
- [ ] G3.2 Tạo payment intent/order từ giá trong database.
- [ ] G3.3 Xác minh chữ ký webhook và chống xử lý webhook trùng.
- [ ] G3.4 Chỉ mở enrollment sau khi webhook hợp lệ xác nhận đã thanh toán.
- [ ] G3.5 Chuẩn hóa trạng thái pending, paid, failed, cancelled, expired và refunded.
- [ ] G3.6 Tạo trang kết quả thanh toán và khả năng thử lại khi thất bại.
- [ ] G3.7 Gửi email biên nhận/xác nhận mở khóa học.
- [ ] G3.8 Làm báo cáo đối soát order, invoice, payment và enrollment.
- [ ] G3.9 Xây luồng hoàn tiền/hủy quyền học có audit log.
- [ ] G3.10 UAT sandbox đầy đủ trước khi chuyển production merchant.

### Cổng nghiệm thu

- Không có webhook hợp lệ thì không mở khóa học.
- Một giao dịch không tạo hai payment/enrollment.
- Số tiền trên cổng thanh toán khớp order và invoice.
- Thanh toán thành công/thất bại/hủy/hoàn tiền đều có trạng thái rõ ràng.

## Phase 4 — Hạ tầng production, media, backup và monitoring

### Hạng mục

- [ ] G4.1 Tạo Dockerfile/container frontend production hoặc chốt nền tảng host frontend.
- [ ] G4.2 Tạo cấu hình compose/deploy production không dùng credential mặc định.
- [ ] G4.3 Thêm scheduler chạy Laravel schedule và giám sát queue worker.
- [ ] G4.4 Cấu hình domain, DNS, HTTPS và redirect HTTP sang HTTPS.
- [ ] G4.5 Quản lý secret ngoài Git; bật secure/encrypted session cookie.
- [ ] G4.6 Chuyển video/tài liệu sang S3/R2 private và URL có thời hạn.
- [ ] G4.7 Kiểm tra CDN, byte-range/seek video và giới hạn upload.
- [ ] G4.8 Backup database và storage tự động; đặt retention tối thiểu 14 ngày.
- [ ] G4.9 Thực hiện restore drill trên staging.
- [ ] G4.10 Cấu hình error monitoring, uptime, CPU/RAM, disk, queue failure và cảnh báo.
- [ ] G4.11 Viết lệnh deploy, rollback và smoke test tự động.

### Cổng nghiệm thu

- Staging chạy bằng cấu hình gần giống production.
- HTTPS, health check, queue và scheduler hoạt động.
- Video phát ổn định và không có URL public vĩnh viễn cho nội dung trả phí.
- Backup/restore đã được kiểm chứng.
- Có cảnh báo khi app hoặc queue lỗi.

## Phase 5 — Nội dung, pháp lý, SEO và vận hành

### Hạng mục

- [ ] G5.1 Rà và nhập nội dung thật: giá, giảng viên, mô tả, module, video, quiz, prompt và tài liệu.
- [ ] G5.2 Thêm chính sách bảo mật, điều khoản sử dụng, thanh toán và hoàn tiền.
- [ ] G5.3 Chốt đồng ý xử lý dữ liệu tại form đăng ký/checkout và nhờ pháp lý rà soát.
- [ ] G5.4 Cấu hình email hỗ trợ, hotline/kênh hỗ trợ và SLA xử lý yêu cầu.
- [ ] G5.5 Chuẩn hóa SOP tuyển sinh, thanh toán, cấp quyền, hỗ trợ học viên và hoàn tiền.
- [ ] G5.6 Thiết lập analytics, conversion tracking, Search Console và consent nếu cần.
- [ ] G5.7 Rà SEO: metadata, canonical, sitemap, robots, Open Graph và dữ liệu có cấu trúc.
- [ ] G5.8 Đào tạo admin/sales/teacher/accountant theo đúng role.

### Cổng nghiệm thu

- Không còn nội dung, giá hoặc tài khoản demo trên production.
- Trang pháp lý và thông tin hỗ trợ hiển thị đầy đủ.
- Nhân sự vận hành hoàn thành các luồng nghiệp vụ bằng tài khoản đúng quyền.

## Phase 6 — UAT, soft launch và go-live

### Hạng mục

- [ ] G6.1 Viết E2E test cho đăng ký, đăng nhập, thanh toán, vào học, quiz và resume video.
- [ ] G6.2 Kiểm thử mobile, tablet, desktop và các trình duyệt phổ biến.
- [ ] G6.3 Kiểm thử hiệu năng trang công khai, API và video với tải dự kiến.
- [ ] G6.4 Chạy full regression trên staging bằng dữ liệu test.
- [ ] G6.5 Chạy security review và kiểm tra phân quyền lần cuối.
- [ ] G6.6 Soft launch với nhóm học viên nhỏ trong 5–7 ngày.
- [ ] G6.7 Theo dõi lỗi, phản hồi, thanh toán và quy trình hỗ trợ trong soft launch.
- [ ] G6.8 Chốt release production, backup trước deploy và người chịu trách nhiệm rollback.
- [ ] G6.9 Go-live công khai và trực vận hành 24–48 giờ đầu.

### Cổng nghiệm thu

- Không còn bug P0/P1.
- Full regression, payment reconciliation và restore drill pass.
- Soft launch không có lỗi chặn học hoặc thất thoát thanh toán.
- Có người trực vận hành, checklist rollback và kênh cảnh báo.

## 4. Thứ tự thực hiện ngay

1. Bắt đầu G0.1: rà thay đổi chưa commit.
2. Hoàn thành toàn bộ Phase 0.
3. Làm lần lượt G1.1 đến G1.9; không làm song song thanh toán thật khi API demo chưa được khóa.
4. Chỉ chọn provider và nhập credential khi bắt đầu Phase 2/3.
5. Không mở nút thanh toán công khai trước khi Phase 1–4 đạt cổng nghiệm thu.

## 5. Ước lượng

- Phase 0: 1–2 ngày.
- Phase 1: 2–4 ngày.
- Phase 2: 2–4 ngày, phụ thuộc provider.
- Phase 3: 3–5 ngày, phụ thuộc cổng thanh toán và merchant.
- Phase 4: 3–5 ngày, phụ thuộc server/domain/storage.
- Phase 5: 2–4 ngày, phụ thuộc nội dung và pháp lý.
- Phase 6: 5–7 ngày gồm soft launch.

Tổng thời gian hợp lý cho một người triển khai tuần tự: khoảng 3–4 tuần. Có thể đưa website giới thiệu và form lead lên sớm hơn, nhưng chỉ mở thanh toán công khai sau khi Phase 1–4 hoàn tất.
