# 05 — Component Library

## Mục tiêu

DAYAI cần một component library tối thiểu để Codex không tạo lại UI lung tung cho mỗi màn hình.

## Component groups

### 1. AppShell

Dùng cho admin, portal, HR portal, affiliate portal.

Gồm:

- Sidebar
- Topbar
- Main content container
- Mobile drawer
- User menu
- Notification entry

Rules:

- Không duplicate shell cho từng module.
- Shell nhận navigation config theo role.
- Main content có max-width hợp lý.
- Scroll chỉ nên nằm ở main hoặc table, tránh double-scroll khó chịu.

### 2. Sidebar

Props khuyến nghị:

```ts
type SidebarItem = {
  label: string;
  href: string;
  icon?: ReactNode;
  badge?: string | number;
  roles?: string[];
  children?: SidebarItem[];
};

type SidebarGroup = {
  label: string;
  items: SidebarItem[];
  defaultOpen?: boolean;
};
```

Rules:

- Group theo nghiệp vụ lớn.
- Active item rõ.
- Collapse group được.
- Icon cùng kích thước.
- Label không quá dài.

### 3. Topbar

Gồm:

- Global search
- Quick action
- Notifications
- User profile
- Environment badge nếu staging/demo

Admin topbar không nên chỉ có avatar trống.

### 4. PageHeader

Props:

```ts
type PageHeaderProps = {
  breadcrumbs?: BreadcrumbItem[];
  title: string;
  description?: string;
  status?: ReactNode;
  actions?: ReactNode;
  meta?: ReactNode;
};
```

Rules:

- Mọi trang admin/list/detail đều dùng PageHeader.
- H1 không quá to.
- Action chính nằm bên phải.
- Description giúp người dùng hiểu màn hình dùng để làm gì.

### 5. KPI Card

Props:

```ts
type KpiCardProps = {
  label: string;
  value: string | number;
  description?: string;
  trend?: {
    value: string;
    direction: 'up' | 'down' | 'flat';
    label?: string;
  };
  icon?: ReactNode;
  tone?: 'neutral' | 'primary' | 'success' | 'warning' | 'danger';
};
```

Rules:

- Label small.
- Value strong.
- Description muted.
- Không để card chỉ có số to và thiếu ý nghĩa.

### 6. DashboardPanel

Dùng để nhóm chart/table/list.

Props:

```ts
type DashboardPanelProps = {
  title: string;
  description?: string;
  actions?: ReactNode;
  children: ReactNode;
};
```

### 7. DataTable

DataTable phải hỗ trợ:

- Search
- Filter
- Sort
- Pagination
- Row action
- Bulk select nếu cần
- Loading skeleton
- Empty state
- Error state
- Responsive overflow có kiểm soát

Không tạo table HTML thô cho từng trang.

### 8. FilterBar

Gồm:

- SearchBox
- Select filters
- Date range
- Reset filters
- View options
- Export nếu có

FilterBar phải rõ ràng nhưng không chiếm quá nhiều chiều cao.

### 9. FormField

Mọi input phải có:

- Label
- Optional helper text
- Error text
- Required indicator nếu cần
- Consistent height

### 10. StatusBadge

Status dùng tone chuẩn.

Ví dụ CRM:

- Mới: primary
- Đang liên hệ: warning
- Đang tư vấn: warning
- Học thử: info
- Đã chuyển đổi: success
- Không phù hợp: neutral
- Mất lead: danger

Không dùng badge màu ngẫu nhiên.

### 11. EmptyState

Props:

```ts
type EmptyStateProps = {
  title: string;
  description?: string;
  action?: ReactNode;
  icon?: ReactNode;
};
```

Ví dụ:

```text
Chưa có đăng ký học thử
Khi có học viên đăng ký, thông tin sẽ hiển thị tại đây.
[Tạo đăng ký học thử]
```

### 12. LoadingSkeleton

Dùng skeleton phù hợp với layout, không chỉ spinner toàn trang.

### 13. ErrorState

Error phải có:

- Message dễ hiểu
- Retry action nếu có thể
- Không lộ stack trace

### 14. Modal / Drawer

- Modal cho hành động ngắn.
- Drawer cho detail hoặc edit nhanh.
- Trang riêng cho flow phức tạp.

### 15. Quiz components

Bắt buộc cho Sprint 34:

- QuizListCard
- QuizStartCard
- QuizQuestionCard
- QuizOption
- QuizProgress
- QuizTimer nếu có
- QuizSubmitBar
- QuizResultSummary
- QuizAttemptHistory

## Component naming rules

Tên component phải mô tả đúng vai trò.

Good:

- `AdminShell`
- `DataTable`
- `LeadStatusBadge`
- `QuizQuestionCard`
- `StudentProgressCard`

Bad:

- `NiceCard`
- `NewTable`
- `Box2`
- `DashboardThing`
- `CustomButton2`

## Component quality checklist

Mỗi component dùng lại nhiều nơi nên có:

- Props typed
- Default empty/loading/error states nếu phù hợp
- Accessible labels
- No hardcoded business data
- Uses tokens
- Responsive behavior
- No inline random styles
