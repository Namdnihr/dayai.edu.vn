# Hướng dẫn chuyển dự án DAYAI từ Windows sang Mac

## 1. Mục tiêu bàn giao

Sau khi hoàn thành tài liệu này, máy Mac phải có đủ:

- Mã nguồn tại commit Phase 0 hoặc mới hơn.
- Hai file môi trường local không đưa lên Git.
- Database PostgreSQL đã restore đúng dữ liệu demo hiện tại.
- Storage Laravel đã được chép lại.
- Backend, queue, PostgreSQL, Redis và Nginx chạy bằng Docker.
- Frontend chạy tại `http://localhost:3002`.
- Có thể tiếp tục Phase 1 bằng Codex mà không mất ngữ cảnh kỹ thuật.

Baseline hiện tại:

- Branch: `develop`
- Commit: `0a9460d`
- Tag: `phase0-baseline-20260731`
- Backend: 29 tests, 217 assertions.
- Database: 4 users, 1 course, 8 video lessons, 1 tenant, 29 migrations.

## 2. Những gì đi qua Git và những gì phải chép riêng

### Đi qua GitHub

- Toàn bộ mã nguồn backend/frontend.
- Migration, seeder và test.
- Video demo `frontend/public/course-videos/ai-la-gi-demo.mp4`.
- Tài liệu roadmap và hướng dẫn bàn giao.

### Không đi qua GitHub

Các file sau bị Git bỏ qua vì chứa cấu hình riêng hoặc dữ liệu:

- `backend/.env`
- `frontend/.env.local`
- `backups/phase0-20260731-1105/dayai-postgres.dump`
- `backups/phase0-20260731-1105/storage-app/`

Không commit hoặc gửi công khai hai file `.env`. Nên dùng USB cá nhân hoặc một file lưu trữ có mã hóa nếu phải chuyển qua cloud.

## 3. Làm trên máy Windows trước khi rời đi

Mở PowerShell tại:

```text
C:\Users\ngocn\Documents\dayai.edu.vn
```

### Bước 3.1 — Kiểm tra và push mã nguồn

```powershell
git status -sb
git log -1 --oneline
git tag --points-at HEAD
git push origin develop
git push origin phase0-baseline-20260731
```

Kết quả mong đợi:

- Không có file chưa commit.
- Commit hiện tại là `0a9460d` hoặc commit mới hơn đã được chủ động tạo.
- Nhánh `develop` không còn báo `ahead` sau khi push.

### Bước 3.2 — Tạo thư mục chuyển máy

Thay `D:\DAYAI-MAC-TRANSFER` bằng đường dẫn USB hoặc thư mục bảo mật của bạn:

```powershell
$DayaiTransfer = "D:\DAYAI-MAC-TRANSFER"
New-Item -ItemType Directory -Force -Path $DayaiTransfer
New-Item -ItemType Directory -Force -Path "$DayaiTransfer\storage-app"

Copy-Item "backend\.env" "$DayaiTransfer\backend.env" -Force
Copy-Item "frontend\.env.local" "$DayaiTransfer\frontend.env.local" -Force
Copy-Item "backups\phase0-20260731-1105\dayai-postgres.dump" $DayaiTransfer -Force
Copy-Item "backups\phase0-20260731-1105\storage-app\*" "$DayaiTransfer\storage-app" -Recurse -Force

Get-FileHash "$DayaiTransfer\dayai-postgres.dump" -Algorithm SHA256
```

SHA-256 của backup Phase 0 phải là:

```text
74439D2EA3D66458CB0BFD3A1EA054BF328525BA1BFCF4359B4BC97655BF2E89
```

Kiểm tra thư mục chuyển máy có bốn mục:

```text
DAYAI-MAC-TRANSFER/
├── backend.env
├── frontend.env.local
├── dayai-postgres.dump
└── storage-app/
```

## 4. Chuẩn bị máy Mac

Cài trước khi đi nếu có thể, sau đó mở các ứng dụng ít nhất một lần:

1. Git.
2. Docker Desktop và Docker Compose.
3. Node.js `>= 20.9.0` và npm.
4. Codex desktop hoặc công cụ lập trình bạn sẽ sử dụng.

Kiểm tra trong Terminal:

```bash
git --version
docker --version
docker compose version
node --version
npm --version
```

Các Docker image của dự án dùng image đa kiến trúc, phù hợp cả Mac Intel và Apple Silicon.

## 5. Clone mã nguồn trên Mac

```bash
mkdir -p "$HOME/Projects"
cd "$HOME/Projects"
git clone https://github.com/Namdnihr/dayai.edu.vn.git
cd dayai.edu.vn
git switch develop
git pull --ff-only origin develop
git log -1 --oneline
git tag --points-at HEAD
git rev-parse --short phase0-baseline-20260731
```

Commit mới nhất có thể mới hơn Phase 0 vì tài liệu bàn giao hoặc Phase sau đã được cập nhật. Điều bắt buộc là lệnh kiểm tra tag cuối cùng trả về:

```text
0a9460d
```

Nếu commit trên Mac cũ hơn, không tiếp tục restore hay sửa code; quay lại kiểm tra bước push trên Windows.

## 6. Chép cấu hình riêng vào repository trên Mac

Giả sử USB có tên `DAYAI`:

```bash
export DAYAI_TRANSFER="/Volumes/DAYAI/DAYAI-MAC-TRANSFER"

cp "$DAYAI_TRANSFER/backend.env" backend/.env
cp "$DAYAI_TRANSFER/frontend.env.local" frontend/.env.local

test -f backend/.env && echo "backend env: OK"
test -f frontend/.env.local && echo "frontend env: OK"
```

Không dùng `git add -f` cho các file môi trường này. Xác nhận chúng vẫn bị ignore:

```bash
git check-ignore -v backend/.env frontend/.env.local
```

## 7. Khởi tạo và restore database

Phần này giả định đây là lần chạy đầu tiên của repository trên Mac và Docker volume DAYAI chưa chứa dữ liệu cần giữ.

### Bước 7.1 — Chạy PostgreSQL và Redis

```bash
docker compose up -d postgres redis
docker compose ps
```

Đợi PostgreSQL chuyển sang trạng thái healthy rồi tiếp tục.

### Bước 7.2 — Restore PostgreSQL

```bash
docker cp "$DAYAI_TRANSFER/dayai-postgres.dump" dayai-postgres:/tmp/dayai-postgres.dump

docker exec -i dayai-postgres pg_restore \
  --no-owner \
  --no-privileges \
  -U dayai_user \
  -d dayai \
  /tmp/dayai-postgres.dump
```

Xác nhận dữ liệu:

```bash
docker exec -i dayai-postgres psql -U dayai_user -d dayai -Atc \
"select 'users='||(select count(*) from users)||'; courses='||(select count(*) from courses)||'; video_lessons='||(select count(*) from video_lessons)||'; tenants='||(select count(*) from tenants)||'; migrations='||(select count(*) from migrations);"
```

Kết quả baseline:

```text
users=4; courses=1; video_lessons=8; tenants=1; migrations=29
```

Nếu `pg_restore` báo object đã tồn tại, dừng lại để kiểm tra volume hiện có. Không chạy `docker compose down -v` nếu chưa chắc chắn, vì `-v` sẽ xóa database và storage Docker.

## 8. Chạy backend và phục hồi storage

```bash
docker compose up -d --build backend queue nginx
docker compose ps
```

Chép storage vào Docker volume:

```bash
docker cp "$DAYAI_TRANSFER/storage-app/." dayai-backend:/var/www/html/storage/

docker exec -i dayai-backend sh -lc \
"chown -R www-data:www-data /var/www/html/storage && chmod -R ug+rwX /var/www/html/storage"
```

Kiểm tra backend:

```bash
curl http://localhost:8081/api/health
```

Kết quả cần có `status: ok`, đồng thời app, database và cache đều `ok`.

## 9. Cài và chạy frontend

Mở một Terminal mới:

```bash
cd "$HOME/Projects/dayai.edu.vn/frontend"
npm ci
npm run lint
npm run build
npm run dev
```

Giữ Terminal này mở khi đang phát triển. Truy cập:

- Website: `http://localhost:3002`
- Portal: `http://localhost:3002/portal`
- Admin: `http://localhost:8081/admin`

Tài khoản demo local sau khi restore:

- Admin: `admin@dayai.edu.vn` / `password`
- Học viên: `0901888000` / `HV-000001`
- OTP demo sẽ hiển thị trực tiếp trên màn hình local.

## 10. Chạy kiểm thử an toàn trên Mac

Không chạy test bằng lệnh dưới đây trong container backend đang kết nối PostgreSQL:

```text
docker exec dayai-backend php artisan test
```

Luôn dùng service test cô lập bằng SQLite memory:

```bash
docker compose --profile test run --rm --build backend-test
```

Frontend:

```bash
cd frontend
npm run lint
npm run build
```

## 11. Tiếp tục công việc bằng Codex trên Mac

Mở thư mục repository trong Codex và gửi yêu cầu:

```text
Đọc docs/22-go-live-execution-plan.md và docs/23-mac-handoff-guide.md.
Kiểm tra branch develop, baseline phase0-baseline-20260731 và trạng thái Docker.
Không chạy test trong container backend đang dùng PostgreSQL.
Tiếp tục triển khai Phase 1 theo từng hạng mục và tiêu chí nghiệm thu.
```

Mọi thay đổi mới nên theo nhịp:

```bash
git status
git add <file-da-kiem-tra>
git commit -m "..."
git push origin develop
```

Khi quay lại Windows, chạy `git pull --ff-only origin develop` trước khi tiếp tục để nhận thay đổi từ Mac.

## 12. Checklist hoàn tất chuyển máy

- [ ] Windows đã push nhánh `develop`.
- [ ] Windows đã push tag `phase0-baseline-20260731`.
- [ ] USB/thư mục bảo mật có đủ hai file env, database dump và storage.
- [ ] SHA-256 database dump khớp.
- [ ] Mac checkout đúng commit/tag.
- [ ] PostgreSQL restore đúng số bản ghi.
- [ ] Backend health check pass.
- [ ] Frontend lint và build pass.
- [ ] Website, portal, bài học và admin đều mở được.
- [ ] Backend test chạy bằng service `backend-test` cô lập.
- [ ] Codex đã đọc roadmap trước khi bắt đầu Phase 1.
