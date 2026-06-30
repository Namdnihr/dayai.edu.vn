# 11 — Table, Form and Chart Rules

## Tables

### Table anatomy

```text
Card container
  Header / toolbar
    Search
    Filters
    View/export actions
  Table
    Header
    Rows
    Empty/loading/error state
  Footer
    Result count
    Pagination
```

### Column rules

- Text: left align
- Numbers: right align if comparable
- Currency: right align
- Dates: consistent format
- Status: badge
- Actions: right align, ideally compact

### Table row height

- Compact: 44–48px
- Productive: 52–56px
- Comfortable: 60–64px

Admin list pages should use compact or productive.

### Horizontal overflow

Avoid horizontal scroll for normal pages.

If many columns:

- Pin key column
- Hide less important columns behind column visibility menu
- Use row detail drawer
- Put actions in kebab menu

Do not let a horizontal scrollbar dominate the UI.

### Search placeholder

Use specific placeholders:

- “Tìm kiếm lead”
- “Tìm kiếm đăng ký học thử”
- “Tìm kiếm học viên”
- “Tìm kiếm khóa học”

Not generic:

- “Tìm kiếm” everywhere

### Filters

Filters need readable labels.

Bad:

```text
[icon filter] 0
```

Good:

```text
Trạng thái: Tất cả
Nguồn: Tất cả
Tư vấn viên: Tất cả
```

### Empty state examples

```text
Chưa có lead nào
Lead từ form tư vấn, học thử hoặc affiliate sẽ hiển thị tại đây.
[Tạo lead]
```

```text
Chưa có bài kiểm tra
Tạo bài kiểm tra để gắn với khóa học, module hoặc bài học.
[Tạo bài kiểm tra]
```

## Forms

### Form anatomy

```text
Section title
Description/helper text
Fields
Validation/errors
Actions
```

### Field rules

Every field should have:

- Label
- Input/control
- Helper text if needed
- Error text
- Required state if applicable

### Form grouping

Long forms should be grouped:

CRM lead form:

- Thông tin khách hàng
- Nhu cầu học
- Nguồn & tracking
- Phân công & follow-up
- Ghi chú

Trial registration form:

- Lead/học viên
- Khóa quan tâm
- Ngày mong muốn
- Khung giờ
- Trạng thái
- Ghi chú

Quiz form:

- Thông tin bài kiểm tra
- Phạm vi gắn kết
- Câu hỏi
- Cài đặt chấm điểm

### Validation copy

Use Vietnamese and be specific.

Bad:

```text
Invalid input
Required
```

Good:

```text
Vui lòng nhập số điện thoại.
Email chưa đúng định dạng.
Bạn cần chọn ít nhất một đáp án đúng.
```

## Buttons

### Button types

- Primary: action chính
- Secondary: action phụ
- Ghost: navigation/light action
- Danger: destructive
- Link: inline/navigation

### Button labels

Use verb-first labels:

- Tạo lead
- Lưu thay đổi
- Gửi OTP
- Nộp bài
- Xem kết quả
- Xuất CSV

Avoid vague:

- OK
- Submit
- Click here

## Charts

Charts in admin reports should be:

- Simple
- Labeled clearly
- Not decorative
- Tied to business decision

Recommended charts:

- Lead funnel
- Lead source performance
- Revenue by course
- Receivable aging
- Attendance trend
- Quiz score distribution
- Affiliate performance

Chart rules:

- Always show empty state if no data
- Include time range
- Use accessible colors
- Avoid 3D / pie overload
- Donut charts only for small category counts

## Detail panels / drawers

Use detail drawer when:

- User needs quick inspection from table
- Full navigation is not required
- Editing is lightweight

Use full detail page when:

- Many related records
- Timeline/history important
- Complex actions
- Data should be linkable

## Status mapping

Always map backend enum to Vietnamese UI label.

Example:

```ts
const trialStatusLabel = {
  new: 'Mới',
  scheduled: 'Đã xếp lịch',
  completed: 'Đã hoàn thành',
  cancelled: 'Đã hủy',
  no_show: 'Không tham gia',
};
```

## Encoding and formatting

- Source files should be UTF-8.
- Seed data must use valid Vietnamese strings.
- Money uses `vi-VN` formatting.
- Dates should be clear: `Thứ 3, 01/07/2026` or `01/07/2026` depending context.
