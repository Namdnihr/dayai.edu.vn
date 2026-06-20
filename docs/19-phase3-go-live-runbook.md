# Phase 3 Go-Live Runbook

## 1. Mục tiêu

Đưa DAYAI từ môi trường phát triển sang staging/production có kiểm soát: cấu hình rõ, có health check, backup, smoke test và rollback.

## 2. Production Environment

- Backend dùng `backend/.env.production.example` làm mẫu, tuyệt đối không commit file `.env` thật.
- Frontend dùng `frontend/.env.production.example` làm mẫu.
- Bắt buộc `APP_ENV=production`, `APP_DEBUG=false`, `SESSION_SECURE_COOKIE=true`, `SESSION_ENCRYPT=true`.
- Database, Redis, S3/storage, SMTP phải dùng credential riêng cho production.
- Domain đề xuất:
  - Website: `https://dayai.edu.vn`
  - Backend API/Admin: `https://api.dayai.edu.vn`
  - Staging: `https://staging.dayai.edu.vn`

## 3. Health Check

- Laravel built-in: `/up`
- DAYAI API health: `/api/health`
- Kỳ vọng production:
  - HTTP `200`
  - `status=ok`
  - `checks.database.status=ok`
  - `checks.cache.status=ok`
  - `checks.app.debug=false`

## 4. Pre-Deploy Checklist

- Pull đúng branch/tag release.
- Backup database production trước deploy.
- Kiểm tra `.env` production không dùng credential local.
- Chạy `composer install --no-dev --optimize-autoloader`.
- Chạy `php artisan migrate --force`.
- Chạy `php artisan config:cache`, `route:cache`, `view:cache`.
- Build frontend production bằng `npm run build`.
- Kiểm tra `robots.txt`, `sitemap.xml`, portal nội bộ không index.

## 5. Smoke Test Sau Deploy

- Gọi `GET /api/health`.
- Mở homepage `https://dayai.edu.vn`.
- Gửi form đăng ký tư vấn test.
- Kiểm tra lead vào CRM.
- Đăng nhập admin và mở dashboard vận hành.
- Mở portal học viên, company portal, affiliate portal bằng dữ liệu test.
- Export một báo cáo CSV từ BI Reports.
- Chạy `php artisan automation:run --trigger=lead.created` trên staging.

## 6. Backup & Restore

- Backup database trước mỗi deploy.
- Backup storage/S3 theo lịch ngày.
- Lưu ít nhất 7 bản backup gần nhất.
- Restore drill tối thiểu mỗi tháng một lần trên staging.
- Ghi rõ commit/tag tương ứng với mỗi backup quan trọng.

## 7. Rollback

- Nếu migration chưa chạy: rollback code về commit/tag cũ và restart service.
- Nếu migration đã chạy: dùng runbook migration rollback hoặc restore backup gần nhất.
- Nếu frontend lỗi: rollback artifact build trước đó.
- Nếu API lỗi nặng: bật maintenance mode, restore DB nếu cần, kiểm tra `/api/health`.

## 8. Security Review

- Không public `/admin`, `/portal`, `/company-portal`, `/affiliate-portal` trong sitemap.
- Portal/affiliate/company portal đều không index.
- OTP portal không trả `demo_otp` khi `APP_ENV=production`.
- API report CSV cần được đưa sau auth trước khi mở production thật nếu dữ liệu nhạy cảm.
- Log không ghi password/token/secret.

## 9. Go-Live Decision

Chỉ go-live khi:

- Health check pass.
- Smoke test pass.
- Backup restore đã được kiểm tra.
- Admin/sales/accountant/teacher login đúng quyền.
- Có người chịu trách nhiệm trực deploy và rollback.
