# Production Readiness Checklist

## Mục Tiêu

Chuẩn bị hệ thống DAYAI cho môi trường staging/production mà không thay đổi scope MVP Phase 1.

## Kiến Trúc Triển Khai Dự Kiến

- Frontend: Next.js chạy sau reverse proxy.
- Backend: Laravel API + Filament admin.
- Database: PostgreSQL.
- Cache/session/queue: Redis.
- Web server: Nginx.
- Container runtime: Docker Compose cho giai đoạn đầu.

## Environment Checklist

- [x] Có Docker Compose cho local/staging.
- [x] Có backend Laravel `.env` theo chuẩn framework.
- [x] Có frontend `.env` dùng API base URL.
- [ ] Tạo `.env.production` trên server thật.
- [ ] Đổi `APP_ENV=production`.
- [ ] Đổi `APP_DEBUG=false`.
- [ ] Đặt `APP_URL=https://dayai.edu.vn`.
- [ ] Đặt `NEXT_PUBLIC_SITE_URL=https://dayai.edu.vn`.
- [ ] Đổi toàn bộ mật khẩu demo trước khi public production.

## Domain & Routing

- [ ] Trỏ `dayai.edu.vn` về frontend.
- [ ] Trỏ `admin.dayai.edu.vn` hoặc `dayai.edu.vn/admin` về Filament admin.
- [ ] Trỏ landing course `k01.dayai.edu.vn` nếu muốn tách chiến dịch.
- [ ] Bật HTTPS cho toàn bộ domain/subdomain.
- [ ] Bật redirect HTTP -> HTTPS.

## Security Checklist

- [x] Có phân quyền role MVP cho admin.
- [x] User inactive không được vào Filament panel.
- [x] Public API có validation bắt buộc.
- [ ] Đổi mật khẩu admin mặc định.
- [ ] Thiết lập rate limit cho form lead/portal lookup.
- [ ] Thiết lập backup định kỳ database.
- [ ] Kiểm tra quyền ghi file storage/logs.
- [ ] Cấu hình CORS theo domain thật.
- [ ] Bật security headers tại Nginx/reverse proxy.

## Backup & Restore

- [ ] Backup PostgreSQL hằng ngày.
- [ ] Lưu backup tối thiểu 14 ngày.
- [ ] Có bản backup trước mỗi lần deploy.
- [ ] Test restore database trên môi trường staging.
- [ ] Backup file upload/storage nếu dùng upload tài liệu/video nội bộ.

## Logging & Monitoring

- [x] Laravel có log application.
- [x] Có activity log nghiệp vụ mức MVP.
- [ ] Gom log container về file/server monitoring.
- [ ] Theo dõi lỗi 500 backend.
- [ ] Theo dõi lỗi frontend build/runtime.
- [ ] Theo dõi dung lượng database/storage.
- [ ] Theo dõi CPU/RAM/container restart.

## Deployment Checklist

- [ ] Pull code bản ổn định.
- [ ] Build frontend production.
- [ ] Install backend dependencies production.
- [ ] Chạy migration production.
- [ ] Chạy seeder cần thiết có kiểm soát.
- [ ] Clear Laravel cache/config/view.
- [ ] Restart container.
- [ ] Smoke test public website.
- [ ] Smoke test admin login.
- [ ] Smoke test portal phụ huynh/học viên.
- [ ] Smoke test portal doanh nghiệp/HR.

## Rollback Checklist

- [ ] Ghi lại version/image trước deploy.
- [ ] Có backup database trước deploy.
- [ ] Có lệnh rollback container/image.
- [ ] Có người phụ trách quyết định rollback.

## Go-Live Decision

Chỉ go-live khi các điều kiện sau đạt:

- [ ] Không còn bug P0/P1.
- [ ] Admin đổi mật khẩu mặc định.
- [ ] HTTPS hoạt động.
- [ ] Backup database chạy được.
- [ ] UAT public website/admin/portal pass.
- [ ] Có người trực vận hành trong ngày go-live.
