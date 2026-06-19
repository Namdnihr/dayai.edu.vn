# DAYAI - Dev Setup

File này ghi lại cách chạy hệ thống để không bị lệch môi trường giữa các sprint.

## Stack Hiện Tại

- Backend: Laravel 13, PHP 8.4.
- Frontend: Next.js 16, TypeScript, Tailwind CSS.
- Admin: Filament 5.
- Database: PostgreSQL 17.
- Cache/session/queue: Redis 8.
- Web server local: Nginx trên Docker.
- Auth/RBAC: Laravel auth + Spatie Permission.

## Dịch Vụ Docker

- `backend`: PHP-FPM Laravel.
- `nginx`: public web server, map ra `http://localhost:8080`.
- `postgres`: database `dayai`, user `dayai_user`.
- `redis`: cache, session và queue.
- `queue`: Laravel queue worker, dùng sau khi có job thật.

## Lệnh Hay Dùng

- Build backend image: `docker --config .docker compose build backend`
- Chạy backend + nginx: `docker --config .docker compose up -d backend nginx`
- Chạy toàn bộ service: `docker --config .docker compose up -d`
- Reset DB và seed: `docker --config .docker compose run --rm backend php artisan migrate:fresh --seed --force`
- Kiểm tra app boot: `docker --config .docker compose run --rm backend php artisan about`
- Kiểm tra route admin: `docker --config .docker compose run --rm backend php artisan route:list --path=admin`
- Chạy test: `docker --config .docker compose run --rm backend php artisan test`
- Chạy frontend dev: `cd frontend && npm.cmd run dev`
- Build frontend: `cd frontend && npm.cmd run build`
- Lint frontend: `cd frontend && npm.cmd run lint`

## Endpoint Public Sprint 5

- Website local: `http://localhost:3000`
- Portal phụ huynh/học viên: `http://localhost:3000/portal`
- Portal doanh nghiệp/HR: `http://localhost:3000/company-portal`
- Landing campaign demo: `http://localhost:3000/k01`
- Landing khóa canonical: `http://localhost:3000/khoa-hoc/ai-can-ban`
- Backend API nhận lead: `POST http://localhost:8080/api/leads`
- Backend API nội dung trang chủ: `GET http://localhost:8080/api/content/home`
- Backend API portal lookup: `POST http://localhost:8080/api/portal/lookup`
- Backend API company portal lookup: `POST http://localhost:8080/api/company-portal/lookup`
- Next.js proxy form lead: `POST http://localhost:3000/api/leads`
- Next.js proxy nội dung trang chủ: `GET http://localhost:3000/api/content/home`
- Next.js proxy portal lookup: `POST http://localhost:3000/api/portal/lookup`
- Next.js proxy company portal lookup: `POST http://localhost:3000/api/company-portal/lookup`
- Biến môi trường frontend tùy chọn: `BACKEND_API_URL=http://localhost:8080/api`

## Tài Khoản Demo

- URL admin: `http://localhost:8080/admin`
- Dashboard vận hành: `http://localhost:8080/admin/operational-dashboard`
- Admin chứng chỉ: `http://localhost:8080/admin/certificates`
- Email: `admin@dayai.edu.vn`
- Password: `password`

Có thể đổi thông tin seed bằng biến môi trường `DAYAI_ADMIN_EMAIL`, `DAYAI_ADMIN_PASSWORD`, `DAYAI_ADMIN_PHONE`.

## Ghi Chú Vận Hành

- Docker image backend có `docker/php/entrypoint.sh` để tự tạo/chỉnh quyền `storage` và `bootstrap/cache`.
- Không commit file `.env`; dùng `backend/.env.example` hoặc `backend/.env.docker.example` làm mẫu.
- Sau khi sửa migration hoặc seeder, chạy lại `migrate:fresh --seed --force`.
- Sau khi sửa Filament resource, chạy `php artisan about`, `route:list --path=admin`, và `php artisan test`.
