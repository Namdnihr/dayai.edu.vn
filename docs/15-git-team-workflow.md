# Quy trình Git cho team DAYAI

## 1. Cấu trúc repo

- `frontend/`: Website Next.js của DAYAI.
- `backend/`: Backend Laravel/API/CRM.
- `docs/`: Tài liệu PM, sitemap, sprint, checklist và quy trình.
- `diagrams/`: Sơ đồ module/entity.
- `assets/`: Tài nguyên thiết kế/nội dung dùng chung.

## 2. Nhánh làm việc

- `main`: mã ổn định, có thể deploy.
- `develop`: nhánh tích hợp tính năng trước khi lên `main`.
- `feature/<ten-tinh-nang>`: nhánh cho từng tính năng.
- `fix/<ten-loi>`: nhánh sửa lỗi.

Ví dụ:

```bash
git checkout -b feature/homepage-redesign
```

## 3. Quy tắc commit

Dùng commit message ngắn, rõ phạm vi:

```bash
git commit -m "feat(frontend): update DAYAI homepage"
git commit -m "fix(frontend): correct Vietnamese encoding"
git commit -m "docs(pm): add sprint checklist"
```

Prefix gợi ý:

- `feat`: thêm tính năng.
- `fix`: sửa lỗi.
- `docs`: tài liệu.
- `style`: chỉnh UI/CSS không đổi logic.
- `refactor`: cải tổ code.
- `chore`: cấu hình, dependency, công việc phụ.

## 4. Trước khi push

Frontend:

```bash
cd frontend
npm install
npm run lint
npm run build
```

Backend:

```bash
cd backend
composer install
php artisan test
```

Nếu chạy bằng Docker, dùng lệnh trong `compose.yaml` theo môi trường hiện tại.

## 5. Push lên GitHub/GitLab lần đầu

Tại thư mục root dự án:

```bash
git status
git add .
git commit -m "chore: initial DAYAI project structure"
git branch -M main
git remote add origin <URL_REPOSITORY_CUA_BAN>
git push -u origin main
```

Sau đó tạo nhánh develop:

```bash
git checkout -b develop
git push -u origin develop
```

## 6. Quy trình làm việc nhóm

1. Dev kéo code mới nhất từ `develop`.
2. Tạo nhánh riêng từ `develop`.
3. Code và commit nhỏ theo từng phần.
4. Chạy lint/build/test trước khi push.
5. Tạo Pull Request vào `develop`.
6. Review xong mới merge.
7. Khi chuẩn bị release, merge `develop` vào `main`.

## 7. Không commit

- `.env`, secret key, token API.
- `node_modules/`, `vendor/`.
- `.next/`, build output, cache.
- File log, file tạm, backup cá nhân.
