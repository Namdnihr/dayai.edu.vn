# Phase 1 Closure UAT Checklist

## Mục Tiêu

Đóng Phase 1 MVP bằng một vòng kiểm thử nghiệm thu có kiểm soát, tập trung vào luồng thật từ marketing -> tuyển sinh -> học tập -> tài chính -> portal.

## Nguyên Tắc Chốt Phase

- Không thêm module mới trong UAT.
- Chỉ sửa bug, lỗi dữ liệu, lỗi quyền, lỗi hiển thị, lỗi luồng demo.
- Mọi yêu cầu mới đưa vào Phase 1.5 hoặc Phase 2.
- Mỗi lỗi cần có màn hình/API, bước tái hiện, kết quả mong muốn, mức độ ưu tiên.

## Tài Khoản UAT

- Admin: `admin@dayai.edu.vn` / `password`
- Sales: `sales@dayai.edu.vn` / `password`
- Teacher: `teacher@dayai.edu.vn` / `password`
- Accountant: `accountant@dayai.edu.vn` / `password`

## Checklist Luồng Public Website

- [x] Trang chủ mở được tại `http://localhost:3000`.
- [x] Landing course mở được tại `http://localhost:3000/khoa-hoc/ai-can-ban`.
- [x] Form đăng ký tư vấn gửi được lead.
- [x] API public lead validate lỗi thiếu dữ liệu.
- [x] Trang chủ hiển thị nhóm khóa học, video, kiến thức.
- [x] Sitemap và robots được chuẩn bị cho SEO cơ bản.

## Checklist Luồng CRM Tuyển Sinh

- [x] Sales đăng nhập admin được.
- [x] Sales thấy lead/nguồn lead/lịch sử tư vấn.
- [x] Sales không thấy nhóm tài chính.
- [x] Lead từ website vào CRM.
- [x] Lead có nguồn khách và trạng thái.
- [x] Có thể ghi nhận hoạt động tư vấn.

## Checklist Luồng Học Tập

- [x] Admin/teacher thấy khóa học, module, lớp, buổi học.
- [x] Có học viên demo `HV-000001`.
- [x] Có lớp học và lịch học demo.
- [x] Có thể xem điểm danh.
- [x] Có dữ liệu học bù/nghỉ học ở mức MVP qua attendance status.

## Checklist Luồng Tài Chính

- [x] Accountant đăng nhập admin được.
- [x] Accountant thấy đơn hàng, hóa đơn, thanh toán, công nợ, phiếu thu.
- [x] Accountant không thấy CRM lead.
- [x] Học phí gắn với customer account, không gắn cứng vào student profile.
- [x] Có báo cáo doanh thu cơ bản.

## Checklist Portal Phụ Huynh/Học Viên

- [x] Portal mở được tại `http://localhost:3000/portal`.
- [x] Tra cứu demo bằng SĐT `0901888000` và mã `HV-000001`.
- [x] Hiển thị hồ sơ học viên.
- [x] Hiển thị lịch học/điểm danh.
- [x] Hiển thị học phí/công nợ.
- [x] Hiển thị báo cáo tiến bộ, thông báo, chứng chỉ.

## Checklist Portal Doanh Nghiệp/HR

- [x] Portal mở được tại `http://localhost:3000/company-portal`.
- [x] Tra cứu demo bằng email `hr@examplecorp.test` và mã `EXAMPLE-CORP`.
- [x] Hiển thị danh sách nhân sự đang học.
- [x] Hiển thị tiến độ nhóm.
- [x] Hiển thị công nợ B2B.
- [x] Hiển thị thông báo và trạng thái chứng chỉ từng nhân sự.

## Checklist Phân Quyền

- [x] Admin có toàn quyền.
- [x] Sales chỉ vào CRM/nội dung/dashboard.
- [x] Teacher chỉ vào học tập/tiến bộ/dashboard.
- [x] Accountant chỉ vào tài chính/dashboard.
- [x] User inactive không được vào Filament panel.

## Bug Bash Template

| ID | Module | Mức độ | Bước tái hiện | Kết quả hiện tại | Kết quả mong muốn | Trạng thái |
| --- | --- | --- | --- | --- | --- | --- |
| UAT-001 | TBD | P2 | TBD | TBD | TBD | Open |

## Điều Kiện Đóng Phase 1

- [x] Backend test pass.
- [x] Frontend lint/build pass.
- [x] Có tài khoản demo theo role.
- [x] Có runbook demo.
- [x] Có checklist production readiness.
- [x] Không còn bug P0/P1 trong bug bash.
