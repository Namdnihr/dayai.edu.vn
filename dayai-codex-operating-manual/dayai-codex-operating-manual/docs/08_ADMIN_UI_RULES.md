# 08 — Admin UI Rules

## Vai trò admin

Admin là hệ CRM/ERP nội bộ của DAYAI.

Admin không phải public landing page, không phải dark theme demo, không phải CRUD generator.

Admin cần:

- Nhanh
- Rõ
- Dữ liệu có hành động
- Bố cục ổn định
- Điều hướng module tốt
- Table mạnh
- Detail page có ngữ cảnh
- Bảo mật theo role

## Admin visual direction

Reference blend:

- Linear: sidebar, density, interaction
- Stripe Dashboard: cards, panels, finance clarity
- Attio: CRM table/detail polish
- Notion: low cognitive load

## Admin shell

### Layout

```text
Sidebar fixed / collapsible
Topbar fixed
Main content scroll
Content container max width
```

Recommended widths:

- Sidebar expanded: 280px
- Sidebar collapsed: 72px
- Topbar: 56–64px
- Main max width: 1280–1440px depending page

### Current issue to avoid

Không để trang admin có cảm giác:

- Quá trống ở bên phải
- Nội dung bị lệch giữa không rõ lý do
- Scrollbar trong sidebar quá thô
- Double-scroll gây khó chịu

## Sidebar grouping

Sidebar nên chia nhóm:

```text
Tổng quan
- Bảng điều khiển

Tuyển sinh CRM
- Lead tuyển sinh
- Nguồn lead
- Phân công lead
- Lịch sử tư vấn
- Đăng ký học thử

Học tập LMS
- Khóa học
- Module khóa học
- Lớp học
- Buổi học
- Video bài học
- Tài liệu học
- Điểm danh

Tiến bộ & đánh giá
- Bài kiểm tra
- Kết quả đánh giá
- Nhận xét giáo viên
- Báo cáo tiến bộ
- Chứng chỉ

Tài chính
- Đơn hàng
- Hóa đơn
- Thanh toán
- Công nợ
- Phiếu thu

Nội dung
- Danh mục nội dung
- Bài viết & tài nguyên
- Video Academy
- Thông báo

Affiliate
- Đối tác affiliate
- Link affiliate
- Click affiliate
- Hoa hồng

Automation
- Kịch bản tự động
- Mẫu email tự động
- Log email tự động
- Notification outbox

Báo cáo
- BI reports
- Xuất CSV

Cài đặt
- Người dùng
- Vai trò & phân quyền
- Cấu hình hệ thống
```

## PageHeader standard

Admin page header phải có:

- Breadcrumbs
- Title
- Description
- Primary action
- Secondary actions if needed

Bad:

```text
Đăng Ký Học Thử
```

Good:

```text
Đăng ký học thử
Theo dõi lịch học thử, trạng thái xếp lịch và người phụ trách tư vấn.
[Tạo đăng ký học thử]
```

## Admin dashboard

Dashboard là command center.

Không dùng hero quá lớn như landing page.

Recommended structure:

```text
PageHeader
Operations Summary Strip
KPI cards
Lead funnel + Source performance
Revenue / Receivables
Upcoming classes + At-risk students
Course revenue + Affiliate performance
Automation health
Readiness metrics
```

### Dashboard KPI cards

Each card:

- Label
- Value
- Trend or context
- Optional icon
- Optional mini sparkline

Example:

```text
Lead tổng
4
+1 lead mới trong 7 ngày
```

## Admin cards

Use consistent card padding:

- Productive: 20–24px
- Compact table container: 16px

Cards need subtle border and surface; avoid heavy colored borders unless status-critical.

## List pages

Current list pages should be upgraded from raw CRUD table to enterprise list page:

```text
PageHeader
DataToolbar
DataTable
Pagination
```

DataToolbar:

- Search input with placeholder specific to data type
- Filters with visible count
- View options
- Export if report-related

## Detail pages

Current detail pages are too empty if they just show label/value.

Use:

```text
PageHeader with actions
Summary card
Two-column detail grid
Activity timeline / related records
```

For Trial Registration detail:

- Lead summary
- Desired date/time
- Status
- Assigned owner
- Notes
- Related lead/contact info
- Timeline
- Actions: edit, mark scheduled, create enrollment, cancel

## Badges

Use Vietnamese labels, not raw enum when shown to users/admin.

Bad:

```text
scheduled
```

Good:

```text
Đã xếp lịch
```

## Encoding rule

Broken Vietnamese text must be fixed immediately.

Examples seen in UI:

- `Pháº¡m Thu HÃ`
- `LÃª HoÃ ng Nam`
- `Nguyá»...n`

Codex should inspect seed data, API serialization, database encoding, and frontend rendering if such text appears.

## Admin density

Admin should not be too sparse.

Use productive density:

- Page header gap: 24–32
- Card grid gap: 20–24
- Table row height: 48–56
- Sidebar item height: 40–44

## Admin no-go list

Do not:

- Create huge gradient hero on every admin page
- Use inconsistent button styles
- Use raw enum labels in UI
- Let tables overflow horizontally without control
- Hide important filters behind unclear icons only
- Leave empty right side with tiny content on large screens
- Use white text on pure black without surface hierarchy
- Duplicate table code across modules
