# 03 — DAYAI Design Language

## Mục tiêu

DAYAI cần một ngôn ngữ thiết kế thống nhất để mọi màn hình, từ homepage đến admin ERP và portal, có cùng DNA.

Hiện trạng UI thường gặp vấn đề:

- Card quá thô
- Khoảng trắng không có nhịp
- Typography không có hệ thống
- Sidebar dài và nặng
- Table giống CRUD mặc định
- Nhiều khối chữ nhật lặp lại
- Thiếu cảm giác premium

Tài liệu này là luật thiết kế để Codex sửa và tạo UI mới.

## Design positioning

DAYAI design = Premium Education SaaS.

Không phải:

- Template Bootstrap
- Admin theme đại trà
- Website khóa học phổ thông
- Dashboard crypto / gaming / neon
- UI quá nhiều gradient

## Visual keywords

- Calm intelligence
- Premium but practical
- Enterprise education
- Spacious
- Layered
- Clear hierarchy
- Soft contrast
- Data-rich but not cluttered

## Style blend

### Public website

Apple + OpenAI + Coursera.

Cảm giác:

- Sang
- Sạch
- Có tầm nhìn
- Dễ tin
- Dễ đăng ký tư vấn

### Admin CRM/ERP

Linear + Stripe + Attio.

Cảm giác:

- Nhanh
- Gọn
- Data rõ
- Sidebar thông minh
- Table mạnh
- Có command center

### Student portal

Coursera + Notion + Linear.

Cảm giác:

- Dễ học tiếp
- Ít áp lực
- Tiến độ rõ
- Quiz dễ hiểu

### HR / Affiliate portal

Stripe Dashboard + Linear.

Cảm giác:

- Business
- Chỉ số rõ
- Trạng thái minh bạch
- Dễ báo cáo

## Core layout rhythm

Dùng 8pt grid.

Không căn mọi thứ bằng mắt.

Khoảng cách khuyến nghị:

- Between icon and label: 8px
- Between form label and field: 8px
- Between toolbar controls: 12px
- Between card sections: 16px
- Between cards in grid: 24px
- Between page header and content: 32px
- Between major sections: 48px hoặc 64px

## Typography rhythm

Không dùng quá nhiều size.

DAYAI chỉ dùng các cấp:

- Display XL
- Display LG
- H1
- H2
- H3
- Title
- Body
- Body Small
- Caption
- Label

Title không được vừa quá to vừa thiếu subtitle.

Dashboard admin không nên dùng headline quá khổng lồ như landing page. Admin cần hiệu quả, không cần hero quá lớn.

## Color philosophy

Màu chủ đạo DAYAI:

- Blue = trust / AI / action
- Violet = intelligence / premium
- Cyan = technology / highlight
- Green = success
- Amber = warning / attention
- Red = destructive / risk

Không dùng màu trạng thái tùy tiện.

Một màn hình không nên có quá 2 màu nhấn chính ngoài màu trạng thái.

## Surface system

DAYAI có 3 lớp surface:

1. Background
2. Card / panel
3. Elevated / modal / popover

Public site dùng nền sáng là chính.

Admin dùng dark mode nhưng phải đủ tương phản và không biến thành màu đen đặc thiếu chiều sâu.

## Cards

Card tốt phải có:

- Title rõ
- Secondary text
- Main value hoặc content
- Optional action
- Optional status
- Padding nhất quán
- Border/subtle shadow

Card xấu:

- Chỉ là hộp chữ nhật chứa text
- Padding lệch
- Font size ngẫu nhiên
- Border quá rõ hoặc quá dày
- Màu nền quá nặng

## Sidebar

Sidebar là bản đồ sản phẩm.

Không để sidebar thành danh sách dài không kiểm soát.

Quy tắc:

- Group theo nghiệp vụ lớn
- Có collapsed state
- Active state rõ nhưng không chói
- Icon cùng style
- Label ngắn
- Không lặp lại từ không cần thiết
- Với admin, ưu tiên hiệu quả hơn trang trí

## Tables

Table là công cụ vận hành, không phải bảng demo.

Table tốt cần:

- Toolbar rõ: search, filters, view options, export nếu có
- Header sticky nếu bảng dài
- Row hover
- Status badge nhất quán
- Empty state có CTA
- Loading skeleton
- Pagination
- Column alignment đúng loại dữ liệu
- Action menu không chiếm quá nhiều cột

## Detail pages

Trang detail không nên chỉ rải label/value trên nền trống.

Dùng template:

- Header: title, status, actions
- Summary card
- Main content tabs
- Right side context panel nếu cần
- Activity/timeline nếu là CRM hoặc operation

## Motion

Motion phải tinh tế:

- Hover card: translateY(-1px) hoặc border highlight nhẹ
- Button: opacity/scale nhẹ
- Sidebar collapse: 160–220ms
- Modal: fade + small scale
- Avoid playful bouncing

## Vietnamese UI quality

Tất cả text hiển thị phải là tiếng Việt tự nhiên.

Không dùng:

- Text bị lỗi encoding
- Câu dịch máy cứng
- Label nửa Việt nửa Anh không cần thiết
- Placeholder chung chung như “Nhập thông tin”

Dùng:

- “Tìm kiếm lead”
- “Lọc theo trạng thái”
- “Tạo đăng ký học thử”
- “Chưa có bài kiểm tra nào”
- “Tiếp tục học”

## Final design rule

Một màn hình DAYAI tốt phải nhìn giống sản phẩm có đội Product Design đứng sau, không giống một CRUD được tô màu tối.
