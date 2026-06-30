# DAYAI Operating Manual — Combined

---

<!-- Source: README.md -->

# DAYAI Codex Operating Manual

Bộ tài liệu này dùng để đặt ở root repository DAYAI, giúp Codex hiểu thống nhất về sản phẩm, UI/UX, module, code style, security và Sprint 34.

## Cách dùng nhanh

1. Copy toàn bộ nội dung thư mục này vào root repo DAYAI.
2. Giữ file `AGENTS.md` ở đúng root repo.
3. Khi giao việc cho Codex, bắt đầu bằng:

```text
Read AGENTS.md first. Then follow the relevant DAYAI manual files for this task.
```

4. Với task UI hiện tại, dùng prompt trong:

```text
prompts/codex_first_ui_foundation_refactor.md
```

## Nguyên tắc

DAYAI không phải website bán khóa học đơn lẻ. DAYAI là một AI Education Operating System gồm public website, admin CRM/ERP, student/guardian portal, HR portal, affiliate portal và hạ tầng vận hành.

Mọi thay đổi code phải ưu tiên: thống nhất, bảo mật, có thể mở rộng, dễ bảo trì, UI tiếng Việt, có đầy đủ loading/empty/error/success state.

## Load order khuyến nghị cho Codex

1. `AGENTS.md`
2. `docs/00_LOAD_ORDER.md`
3. Tài liệu liên quan đến module đang sửa
4. Checklist tương ứng trong `checklists/`

## Các file quan trọng nhất

- `AGENTS.md`: luật chính cho Codex.
- `docs/03_DESIGN_LANGUAGE.md`: DNA giao diện DAYAI.
- `docs/04_DESIGN_TOKENS.md`: token màu, spacing, typography, radius, shadow.
- `docs/05_COMPONENT_LIBRARY.md`: component bắt buộc phải tái sử dụng.
- `docs/08_ADMIN_UI_RULES.md`: luật admin shell, sidebar, CRUD, dashboard.
- `docs/09_STUDENT_PORTAL_LMS_QUIZ.md`: portal học viên, LMS, quiz.
- `docs/14_SPRINT_34_QUIZ_ENGINE.md`: phạm vi Sprint 34.
- `prompts/codex_first_ui_foundation_refactor.md`: prompt đầu tiên để sửa UI hiện tại.

---

<!-- Source: AGENTS.md -->

# AGENTS.md — DAYAI Product Coding Rules

## Mission

DAYAI is an AI Education Operating System for Vietnam.

DAYAI is not a generic course-selling website. It is a multi-portal education operations platform with:

1. Public website
2. Internal admin CRM/ERP
3. Student / guardian portal
4. Company / HR portal
5. Affiliate portal
6. Operational infrastructure

All work must preserve this product architecture.

## Current Sprint

DAYAI is currently at Sprint 34: Online Assessment & Quiz Engine.

Priority UI areas:

1. Public homepage
2. Admin dashboard + sidebar modules
3. Student portal / LMS / quiz
4. HR portal
5. Affiliate portal

## Required Reading Before Work

Always read this file first.

Then read the relevant manual files:

- Product / architecture: `docs/01_PRODUCT_PHILOSOPHY.md`, `docs/02_PRODUCT_ARCHITECTURE.md`
- UI/design: `docs/03_DESIGN_LANGUAGE.md`, `docs/04_DESIGN_TOKENS.md`, `docs/05_COMPONENT_LIBRARY.md`
- Public website: `docs/07_PUBLIC_WEBSITE_RULES.md`
- Admin/CRM/ERP: `docs/08_ADMIN_UI_RULES.md`, `docs/10_CRM_FINANCE_LEARNING_RULES.md`
- Student/LMS/quiz: `docs/09_STUDENT_PORTAL_LMS_QUIZ.md`, `docs/14_SPRINT_34_QUIZ_ENGINE.md`
- Tables/forms/charts: `docs/11_TABLE_FORM_CHART_RULES.md`
- Security/SEO/accessibility: `docs/12_SECURITY_SEO_ACCESSIBILITY.md`
- Coding rules: `docs/13_CODING_RULES.md`

## Product Principles

Build DAYAI as:

- Premium
- Trustworthy
- Vietnamese-first
- Education-focused
- Enterprise-ready
- Role-based
- Data-driven
- SEO-aware for public pages
- Secure for private portals
- Scalable as an operating system, not a small course website

## UI/UX Direction

Use one unified design system across all product areas.

Design reference blend:

- Apple: clarity, whitespace, premium presentation
- OpenAI: calm intelligence, simple language, trust
- Stripe: product-system polish, gradients, strong hierarchy
- Linear: dense admin workflows that still feel elegant
- Coursera: learning and course credibility
- Notion: approachable structure and low friction

Do not copy any brand directly. Build DAYAI’s own visual language.

## Design System Rules

Use the tokens in:

- `design/dayai-tokens.json`
- `design/dayai-tokens.css`
- `design/tailwind-theme-extension.example.ts`

Never introduce random colors, spacing, border radius, shadows, or typography scales without updating the design tokens and explaining why.

Allowed spacing scale:

`4, 8, 12, 16, 24, 32, 48, 64, 96`

Allowed radius scale:

`8, 12, 16, 20, 24, 999`

All visible UI text must be Vietnamese.

Never use Lorem Ipsum.

Fix mojibake or broken Vietnamese encoding whenever found, for example strings like `Pháº¡m`, `HÃ`, `Ä`, `á»`.

## Frontend Architecture Rules

Prefer reusable components.

Required component groups:

- AppShell
- Sidebar
- Topbar
- PageHeader
- Breadcrumbs
- KPI cards
- Dashboard panels
- DataTable
- FilterBar
- SearchBox
- FormField
- DetailPanel
- EmptyState
- LoadingSkeleton
- ErrorState
- Toast
- Modal
- Tabs
- Badge / StatusBadge
- Stepper
- LessonPlayer
- Quiz components

Do not duplicate UI logic if a reusable component already exists.

## Module Map

Public website:

- Homepage
- Course listing
- Course detail / landing
- Audience pages
- Resource/news pages
- Contact/lead forms

Admin:

- Dashboard
- CRM
- Learning
- Finance
- Progress
- Content
- Affiliate
- Automation
- BI Reports
- Settings/RBAC

Student portal:

- OTP auth
- Overview
- Courses
- Lesson player
- LMS progress
- Quiz
- Finance
- Progress reports
- Certificates
- Notifications

Company / HR portal:

- Company overview
- Employee list
- Employee detail
- Learning progress
- Finance summary
- Notifications

Affiliate portal:

- Partner overview
- Campaign links
- Clicks
- Leads
- Orders
- Commissions

## Sprint 34 Quiz Rules

Build the quiz system with:

- Quiz list in portal
- Start quiz
- Answer questions
- Submit quiz
- View score
- Correct answer count
- Attempt history
- Support single choice
- Support multiple choice
- Support true / false
- Auto grading
- Sync result to assessment results

Quiz must connect to:

- Lesson
- Module
- Course
- Student
- Assessment result

## CRM Rules

Every lead should support:

- Source
- UTM
- Affiliate code
- Campaign slug
- Course slug
- Page URL
- Referrer
- Assigned sales
- Pipeline stage
- Lead temperature
- Follow-up status
- Trial registration history

## Portal Security Rules

Portal pages must not be indexed by search engines.

OTP must support:

- Rate limit
- Limited access token
- Audit log
- Separate student and guardian roles

Never log secrets, OTP, tokens, passwords, or private financial data.

## SEO Rules

Public pages should include:

- Metadata
- Sitemap support
- Robots rules
- llms.txt
- E-E-A-T content structure
- Semantic HTML
- Clean URL slug

Private portals must be `noindex`.

## Coding Behavior

Before coding:

1. Inspect existing structure.
2. Reuse existing patterns.
3. Identify related models, routes, components, and services.
4. Plan changes briefly.

While coding:

1. Keep changes small and focused.
2. Do not rewrite unrelated code.
3. Do not invent a new architecture if the existing one works.
4. Add types and validation.
5. Handle loading, empty, error, and success states.
6. Preserve existing business logic unless the task explicitly changes it.

After coding:

1. Run lint/test/build if available.
2. Report what changed.
3. Mention files changed.
4. Mention any limitation or follow-up.

## Quality Bar

Code must be:

- Maintainable
- Typed
- Modular
- Secure
- Production-ready
- Easy for future Codex sessions to understand

Do not create demo-only code unless explicitly requested.

Do not hardcode fake data into production flows.

Mock data is allowed only in demo/dev files and must be clearly labeled.

## Final Rule

When unsure, choose the option that makes DAYAI more scalable, consistent, secure, and premium as an AI Education Operating System.

---

<!-- Source: docs/00_LOAD_ORDER.md -->

# 00 — Load Order for Codex

## Mục tiêu

File này chỉ cho Codex nên đọc tài liệu nào trước, để không bị ngập context.

## Luồng đọc mặc định

Mọi task bắt đầu bằng:

1. `AGENTS.md`
2. `docs/01_PRODUCT_PHILOSOPHY.md`
3. `docs/02_PRODUCT_ARCHITECTURE.md`
4. Tài liệu theo module đang sửa
5. Checklist tương ứng

## Theo loại task

### Task UI tổng thể

Đọc:

- `docs/03_DESIGN_LANGUAGE.md`
- `docs/04_DESIGN_TOKENS.md`
- `docs/05_COMPONENT_LIBRARY.md`
- `docs/06_PAGE_TEMPLATES.md`
- `docs/11_TABLE_FORM_CHART_RULES.md`
- `checklists/ui_quality_gate.md`

### Task homepage public

Đọc:

- `docs/03_DESIGN_LANGUAGE.md`
- `docs/04_DESIGN_TOKENS.md`
- `docs/07_PUBLIC_WEBSITE_RULES.md`
- `docs/12_SECURITY_SEO_ACCESSIBILITY.md`
- `checklists/ui_quality_gate.md`

### Task admin dashboard / CRM / ERP

Đọc:

- `docs/03_DESIGN_LANGUAGE.md`
- `docs/04_DESIGN_TOKENS.md`
- `docs/05_COMPONENT_LIBRARY.md`
- `docs/08_ADMIN_UI_RULES.md`
- `docs/10_CRM_FINANCE_LEARNING_RULES.md`
- `docs/11_TABLE_FORM_CHART_RULES.md`

### Task student portal / LMS / quiz

Đọc:

- `docs/03_DESIGN_LANGUAGE.md`
- `docs/04_DESIGN_TOKENS.md`
- `docs/09_STUDENT_PORTAL_LMS_QUIZ.md`
- `docs/14_SPRINT_34_QUIZ_ENGINE.md`
- `checklists/sprint_34_acceptance_criteria.md`

### Task bảo mật / OTP / portal

Đọc:

- `docs/12_SECURITY_SEO_ACCESSIBILITY.md`
- `checklists/security_gate.md`

### Task coding/refactor

Đọc:

- `docs/13_CODING_RULES.md`
- `checklists/codex_preflight_checklist.md`

## Quy tắc context

Codex không cần đọc toàn bộ manual mỗi lần. Chỉ đọc phần liên quan.

Nếu task có dấu hiệu ảnh hưởng nhiều module, Codex phải dừng lại và viết kế hoạch ngắn trước khi sửa code.

---

<!-- Source: docs/01_PRODUCT_PHILOSOPHY.md -->

# 01 — DAYAI Product Philosophy

## DAYAI là gì?

DAYAI là hệ điều hành giáo dục AI cho thị trường Việt Nam.

DAYAI kết nối 4 lớp giá trị:

1. Học AI cho cá nhân
2. Quản trị học tập cho trung tâm
3. Theo dõi tiến bộ cho phụ huynh / HR
4. Vận hành tuyển sinh, tài chính, nội dung, affiliate và báo cáo

## DAYAI không phải gì?

DAYAI không phải:

- Website bán khóa học đơn lẻ
- Landing page marketing đơn giản
- CRUD admin rời rạc
- LMS demo
- Theme WordPress
- Dashboard đẹp nhưng không vận hành được

## Product North Star

DAYAI giúp người Việt học, ứng dụng và vận hành đào tạo AI một cách có hệ thống.

Mọi màn hình phải trả lời được ít nhất một câu hỏi:

- Người dùng cần làm việc gì tiếp theo?
- Dữ liệu nào giúp ra quyết định?
- Hành động chính có rõ không?
- Màn hình này có làm DAYAI đáng tin hơn không?

## Các nhóm người dùng chính

### Public Visitor

Người chưa biết DAYAI, cần hiểu nhanh:

- DAYAI là ai?
- Học gì?
- Học cho ai?
- Có đáng tin không?
- Đăng ký tư vấn/học thử ở đâu?

### Admin nội bộ

Người vận hành trung tâm:

- Tuyển sinh
- Lớp học
- Điểm danh
- Tiến độ
- Tài chính
- Nội dung
- Affiliate
- Automation
- Báo cáo

### Sales / tư vấn viên

Cần thấy:

- Lead nóng
- Follow-up quá hạn
- Nguồn lead hiệu quả
- Lịch sử tư vấn
- Trial registration
- Chuyển đổi lead thành khách hàng/học viên/công ty

### Teacher

Cần thấy:

- Lịch dạy
- Danh sách lớp
- Điểm danh
- Nhận xét học viên
- Kết quả đánh giá
- Bài học/video/tài liệu

### Accountant

Cần thấy:

- Orders
- Invoices
- Payments
- Receivables
- Receipts
- Công nợ
- Doanh thu
- B2C/B2B finance

### Student

Cần thấy:

- Khóa đang học
- Bài học tiếp theo
- Lịch học
- Tiến độ LMS
- Quiz/bài kiểm tra
- Điểm số
- Chứng chỉ
- Thông báo

### Guardian

Cần thấy:

- Tiến bộ học viên
- Nhận xét giáo viên
- Điểm danh
- Học phí/công nợ
- Chứng chỉ
- Thông báo riêng

### HR / Company

Cần thấy:

- Nhân sự đang học
- Tiến độ từng nhân sự
- Điểm đánh giá
- Điểm danh
- Chứng chỉ
- Học phí, đã thanh toán, công nợ B2B

### Affiliate Partner

Cần thấy:

- Link chiến dịch
- Click
- Lead
- Đơn hàng
- Hoa hồng
- Trạng thái thanh toán

## Product tone

DAYAI phải tạo cảm giác:

- Rõ ràng
- Có hệ thống
- Đáng tin
- Thân thiện
- Hiện đại
- Không phô trương
- Không rối
- Không màu mè

## Nguyên tắc ra quyết định

Khi có mâu thuẫn, ưu tiên theo thứ tự:

1. Bảo mật dữ liệu người dùng
2. Tính đúng của nghiệp vụ
3. Khả năng mở rộng hệ thống
4. Trải nghiệm người dùng
5. Độ đẹp thị giác
6. Tốc độ triển khai

## Một câu để Codex nhớ

Nếu không chắc nên làm gì, hãy chọn phương án khiến DAYAI giống một sản phẩm SaaS giáo dục AI nghiêm túc, không phải một trang bán khóa học.

---

<!-- Source: docs/02_PRODUCT_ARCHITECTURE.md -->

# 02 — Product Architecture

## 6 cụm sản phẩm lớn

DAYAI gồm 6 cụm giao diện và hạ tầng:

1. Website public
2. Admin nội bộ / CRM / ERP
3. Student / Guardian portal
4. Company / HR portal
5. Affiliate portal
6. Hạ tầng vận hành / go-live

## 1. Website Public

### Vai trò

Lớp nhận diện thương hiệu, SEO, chuyển đổi lead.

### Pages

- Homepage
- Course listing
- Course detail
- Landing AI căn bản / campaign landing
- Audience pages
- Resource/news pages
- Contact/lead forms
- AI tools
- Case studies
- Prompt library
- Video Academy

### Data and tracking

Lead forms phải lưu được:

- Name
- Phone
- Email
- Audience type
- Course interest
- Message
- UTM source / medium / campaign / term / content
- Referral
- Affiliate code
- Page URL
- Referrer
- Campaign slug
- Course slug

## 2. Admin nội bộ / CRM / ERP

### Vai trò

Command center của DAYAI.

### Core modules

- CRM tuyển sinh
- People / Organizations
- Learning admin
- Finance admin
- Progress admin
- Content admin
- Affiliate admin
- Automation admin
- BI reports
- RBAC / Settings

### Admin shell bắt buộc

- Sidebar theo nhóm module
- Topbar với search, notification, user menu
- PageHeader chuẩn
- Breadcrumbs
- Role-based navigation
- Responsive admin layout

## 3. Student / Guardian Portal

### Vai trò

Không gian học tập và theo dõi tiến độ.

### Core flows

- OTP auth
- Overview dashboard
- Courses currently learning
- Upcoming sessions
- Attendance
- Finance/receivables
- Progress reports
- Teacher comments
- Notifications
- Certificates
- Video lessons
- LMS progress
- Quiz / assessments

## 4. Company / HR Portal

### Vai trò

Cho doanh nghiệp theo dõi nhân sự học AI.

### Core flows

- HR lookup by email + company code
- Company overview
- Employee list
- Employee detail
- Progress by employee
- Assessment results
- Attendance
- Certificates
- B2B finance summary
- HR notifications

## 5. Affiliate Portal

### Vai trò

Cho đối tác theo dõi hiệu quả giới thiệu.

### Core flows

- Partner lookup by code/link
- Campaign links
- Clicks
- Leads
- Related orders
- Commissions
- Commission statuses: pending, approved, paid, rejected

Affiliate portal must be noindex.

## 6. Infrastructure / Go-live

### Stack expectations

- Docker
- Nginx
- PostgreSQL
- Redis
- Health check `/api/health`
- Backend/frontend `.env.production.example`
- Backup/restore/rollback checklist
- Smoke test checklist
- Security review checklist

## Module dependency map

```text
Public website
  -> Lead form
  -> CRM lead
  -> Sales follow-up
  -> Trial registration
  -> Customer/student/company
  -> Enrollment/order

Student portal
  -> Enrollment
  -> Course/module/lesson/session
  -> Attendance
  -> LMS progress
  -> Quiz attempt
  -> Assessment result
  -> Progress report/certificate

HR portal
  -> Company
  -> Employees/students
  -> Enrollments
  -> Progress/attendance/assessment
  -> B2B finance

Affiliate portal
  -> Affiliate partner/link/click
  -> Lead attribution
  -> Order attribution
  -> Commission
```

## Route groups recommendation

```text
/                         public homepage
/khoa-hoc                 public course listing
/khoa-hoc/[slug]          public course detail
/doi-tuong/[slug]         audience page
/tai-nguyen/[slug]        resource detail
/lien-he                  lead/contact

/admin                    admin dashboard
/admin/crm/*              CRM
/admin/learning/*         LMS/admin learning
/admin/finance/*          finance
/admin/progress/*         progress
/admin/content/*          content
/admin/affiliate/*        affiliate
/admin/automation/*       automation
/admin/reports/*          BI reports
/admin/settings/*         RBAC/settings

/portal                   student/guardian overview
/portal/bai-hoc/[slug]    lesson player
/portal/quiz              quiz list
/portal/quiz/[id]         quiz attempt/result

/company-portal           company HR portal
/affiliate-portal         affiliate portal
```

## Architecture principle

Routes can be separate, but product language, tokens, components, status labels, empty states, forms, tables, and interaction patterns must be unified.

---

<!-- Source: docs/03_DESIGN_LANGUAGE.md -->

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

---

<!-- Source: docs/04_DESIGN_TOKENS.md -->

# 04 — Design Tokens

## Mục tiêu

Codex phải dùng token thay vì tự đặt màu, spacing, radius, shadow.

Các token mẫu nằm ở:

- `design/dayai-tokens.json`
- `design/dayai-tokens.css`
- `design/tailwind-theme-extension.example.ts`

## Color tokens

### Brand

```text
primary:   #1E6BFF
secondary: #7B61FF
accent:    #06B6D4
success:   #22C55E
warning:   #F59E0B
danger:    #EF4444
```

### Light surfaces

```text
bg:              #FFFFFF
bg-subtle:       #F8FAFC
surface:         #FFFFFF
surface-muted:   #F1F5F9
surface-tint:    #EFF6FF
border:          #E2E8F0
border-strong:   #CBD5E1
text:            #0F172A
text-muted:      #475569
text-subtle:     #64748B
```

### Dark admin surfaces

```text
dark-bg:             #070A12
dark-bg-subtle:      #0B1020
dark-surface:        #101827
dark-surface-muted:  #151C2B
dark-surface-raised: #1B2435
dark-border:         #243148
dark-border-strong:  #334155
dark-text:           #F8FAFC
dark-text-muted:     #CBD5E1
dark-text-subtle:    #94A3B8
```

## Typography tokens

```text
display-xl: 64px / 72px / 800
display-lg: 48px / 56px / 800
h1:         40px / 48px / 760
h2:         32px / 40px / 720
h3:         24px / 32px / 700
title:      18px / 28px / 650
body:       16px / 24px / 450
body-sm:    14px / 22px / 450
caption:    12px / 18px / 500
label:      13px / 20px / 600
```

## Spacing tokens

Only use:

```text
4, 8, 12, 16, 24, 32, 48, 64, 96
```

Do not create spacing like 17, 22, 28, 36, 44 unless there is an existing layout constraint.

## Radius tokens

```text
radius-sm:   8px
radius-md:   12px
radius-lg:   16px
radius-xl:   20px
radius-2xl:  24px
radius-full: 999px
```

## Shadow tokens

### Light

```text
shadow-xs: 0 1px 2px rgba(15, 23, 42, 0.06)
shadow-sm: 0 8px 24px rgba(15, 23, 42, 0.08)
shadow-md: 0 18px 48px rgba(15, 23, 42, 0.12)
```

### Dark

```text
shadow-dark-xs: 0 1px 2px rgba(0, 0, 0, 0.24)
shadow-dark-sm: 0 12px 32px rgba(0, 0, 0, 0.32)
shadow-dark-md: 0 24px 64px rgba(0, 0, 0, 0.42)
```

## Border rules

- Light border: `#E2E8F0`
- Dark border: `#243148`
- Hover border can use brand color with low opacity
- Avoid thick 2px borders except focus state

## Focus state

Every interactive element must have visible keyboard focus.

Recommended:

```text
outline: 2px solid rgba(30, 107, 255, 0.65)
outline-offset: 2px
```

## Component density

DAYAI has 3 density levels:

### Comfortable

For public site and student portal.

- Card padding: 24 or 32
- Row height: 56–64
- Section gap: 48–64

### Productive

For admin dashboards.

- Card padding: 20 or 24
- Row height: 52–56
- Section gap: 32–48

### Compact

For data-heavy admin lists.

- Card padding: 16
- Row height: 44–48
- Toolbar height: 44

Do not mix density levels inside one page without purpose.

## Bad token behavior

Codex must avoid:

- Hardcoding random hex colors
- Inline styles for repeated values
- One-off spacing
- One-off typography
- Different button heights on same page
- Different badge styles for same status

## Good token behavior

Codex should:

- Centralize tokens
- Reuse CSS variables / Tailwind config
- Replace repeated values with named classes/components
- Update token docs if a new token is truly needed

---

<!-- Source: docs/05_COMPONENT_LIBRARY.md -->

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

---

<!-- Source: docs/06_PAGE_TEMPLATES.md -->

# 06 — Page Templates

## Mục tiêu

Page template giúp Codex tạo màn hình nhất quán thay vì mỗi trang một kiểu.

## 1. Public Homepage Template

```text
Header / Navbar
Hero
Social Proof
Ecosystem
Audience Segments
Why DAYAI
Featured Courses
Learning Journey
AI Mentor / LMS / Quiz preview
Case Study
AI Tools / Resources
Teachers
Testimonials
Partners
FAQ
Final CTA
Footer
```

Rules:

- Homepage không chỉ bán khóa học; homepage bán niềm tin.
- Hero phải nói rõ DAYAI là hệ sinh thái học AI.
- CTA chính: “Nhận tư vấn lộ trình”.
- CTA phụ: “Xem khóa học”.
- Nội dung tiếng Việt.
- Không dùng Lorem Ipsum.
- Không biến homepage thành quá nhiều card giống nhau.

## 2. Public Course Listing Template

```text
PageHeader
Audience / level filters
Course cards
Comparison / guidance section
FAQ
Lead CTA
```

Course card gồm:

- Title
- Audience
- Level
- Duration
- Outcome
- Rating/social proof nếu có
- CTA

## 3. Public Course Detail Template

```text
Hero course detail
Outcomes
Who should learn
Curriculum
Learning format
Teacher
Projects/case studies
Pricing/consultation
FAQ
Sticky CTA on mobile
```

## 4. Admin Dashboard Template

```text
PageHeader
Command Center / summary strip
KPI grid
Lead funnel / source performance
Revenue / receivables
Upcoming classes
At-risk students
Affiliate performance
Automation health
Readiness metrics
```

Rules:

- Dashboard là command center, không phải landing page.
- Banner hero admin phải gọn, không chiếm quá nhiều chiều cao.
- Ưu tiên insight và action.

## 5. Admin List Page Template

```text
PageHeader
DataToolbar
DataTable
Pagination
Bulk actions if needed
```

PageHeader:

- Breadcrumbs
- Title
- Description
- Primary action

DataToolbar:

- Search
- Filters
- Sort/view options
- Export nếu có

DataTable:

- Loading
- Empty
- Error
- Pagination
- Row actions

## 6. Admin Detail Page Template

```text
PageHeader
Summary card
Main detail tabs
Right context panel
Activity / history timeline
```

Example Lead Detail:

- Contact summary
- Stage/status
- Assigned sales
- UTM/source attribution
- Follow-up schedule
- Consultation history
- Related trial registrations
- Related orders/enrollments

## 7. Admin Create/Edit Form Template

```text
PageHeader
Form sections
Sticky action bar
Validation errors
Cancel / Save actions
```

Rules:

- Group fields by business meaning.
- Avoid one long unstructured form.
- Show helper text where field meaning is not obvious.

## 8. Student Portal Dashboard Template

```text
Greeting
Current course / resume learning
Upcoming sessions
Learning progress
Quiz pending/recent results
Teacher comments
Finance summary
Notifications
Certificates
```

Primary action:

- “Tiếp tục học”

## 9. Lesson Player Template

```text
Header / back to portal
Video player
Lesson title + metadata
Progress bar
Materials
Related quiz
Notes / teacher comment if applicable
Next lesson CTA
```

Rules:

- Resume progress.
- Show percent and last position.
- Lesson player must not feel like an admin page.

## 10. Quiz Attempt Template

```text
Quiz intro
Question progress
Question card
Answer options
Submit / Next action
Review warning if unanswered
Result summary after submit
Attempt history
```

## 11. HR Portal Template

```text
Company header
KPI summary
Employee list
Progress overview
Finance summary
Certificates
Notifications
```

## 12. Affiliate Portal Template

```text
Partner header
Campaign links
Performance KPIs
Clicks/leads/orders table
Commission summary
Commission history
```

## Template principle

Nếu Codex tạo một trang mới, nó phải chọn template gần nhất trước khi viết UI.

---

<!-- Source: docs/07_PUBLIC_WEBSITE_RULES.md -->

# 07 — Public Website Rules

## Vai trò website public

Website public là lớp thương hiệu, SEO, giáo dục thị trường và tạo lead.

Không chỉ là nơi đặt nút đăng ký khóa học.

## Navigation

Menu chính:

- Đối tượng học
- Khóa học AI
- Doanh nghiệp
- Tài nguyên
- Tin AI
- Liên hệ

Optional:

- Công cụ AI
- Portal
- Tư vấn

Rules:

- Navbar không quá cao.
- CTA “Tư vấn” rõ.
- Portal là secondary action, không cạnh tranh với CTA tư vấn.

## Homepage hero

Hero cần trả lời trong 5 giây:

- DAYAI là gì?
- Ai nên học?
- Học xong được gì?
- Bấm vào đâu để bắt đầu?

Recommended hero content:

```text
Badge: Hệ sinh thái học AI thực chiến
Headline: Học AI hôm nay, dẫn đầu tương lai.
Subheadline: Khóa học, LMS, quiz online, portal phụ huynh, HR portal và báo cáo tiến bộ trong một nền tảng đào tạo AI dành cho người Việt.
Primary CTA: Nhận tư vấn lộ trình
Secondary CTA: Xem khóa học
```

Hero visual nên có:

- AI learning dashboard mockup
- Floating cards
- Course/progress/quiz indicators
- Subtle gradient/glow

Không nên:

- Nền quá tối làm mất cảm giác giáo dục
- 3 card trắng to và thô
- Hình chữ nhật lặp lại quá nhiều
- Headline quá dài trên nhiều dòng khó đọc

## Social proof

Dùng khi có dữ liệu thật.

Nếu chưa có dữ liệu thật, dùng ngôn ngữ trung tính:

- “Được thiết kế cho học sinh, sinh viên, người đi làm và doanh nghiệp”
- “Lộ trình học theo năng lực”
- “Theo dõi tiến bộ qua LMS và bài kiểm tra”

Không bịa số liệu.

## Course cards

Course cards cần:

- Tên khóa
- Nhóm học viên
- Level
- Thời lượng
- Kết quả sau khóa học
- CTA

Không nhồi quá nhiều chữ.

## Resource / CMS

Các nhóm nội dung:

- Cẩm nang AI
- Prompt AI
- Công cụ AI
- Tin tức AI
- Case study
- Video Academy
- Checklist / tài nguyên

## Lead forms

Form tư vấn/học thử/liên hệ phải lưu tracking:

- UTM
- Referral
- Affiliate code
- Page URL
- Referrer
- Campaign slug
- Course slug

Form UX:

- Label rõ
- Field tối thiểu
- Error state rõ
- Success state có bước tiếp theo
- Không mất dữ liệu khi submit lỗi

## SEO

Public pages phải có:

- Metadata title/description
- Canonical nếu cần
- Semantic heading
- Structured content
- Sitemap support
- robots rules
- llms.txt nếu dự án đã hỗ trợ
- E-E-A-T content structure

## Public visual rules

- Light theme là mặc định.
- Dark gradient có thể dùng cho hero nhưng phải cân bằng với giáo dục và sự tin cậy.
- Dùng nhiều whitespace.
- Card mềm, không quá nặng.
- Button primary rõ.
- Mobile CTA phải dễ bấm.

## Public homepage immediate fix direction

Với UI hiện tại, Codex nên:

1. Giữ concept hero nhưng làm sáng và cao cấp hơn.
2. Giảm cảm giác “hộp chữ nhật” của các card bên phải.
3. Tạo hero visual như product mockup thay vì chỉ các card văn bản.
4. Giảm độ nặng của overlay tối.
5. Thêm section tiếp theo ngay dưới fold để trang không bị hụt.
6. Chuẩn hóa navbar, button, typography theo tokens.

---

<!-- Source: docs/08_ADMIN_UI_RULES.md -->

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

---

<!-- Source: docs/09_STUDENT_PORTAL_LMS_QUIZ.md -->

# 09 — Student Portal, LMS and Quiz Rules

## Vai trò portal

Student/Guardian Portal là nơi học viên tiếp tục học, phụ huynh theo dõi tiến bộ, và hệ thống hiển thị kết quả học tập rõ ràng.

Portal phải thân thiện hơn admin.

## Portal visual direction

Reference blend:

- Coursera: learning clarity
- Notion: approachable layout
- Linear: progress and navigation polish

## Auth

Portal sử dụng OTP demo / token có hạn.

UX cần:

- Nhập email/số điện thoại hoặc mã truy cập
- Gửi OTP
- Nhập OTP
- Trạng thái gửi lại
- Rate limit message
- Login audit
- Không lộ token

Student và Guardian phải tách quyền.

## Portal dashboard

Recommended layout:

```text
Greeting
Primary course card / Continue learning
Upcoming sessions
Learning progress
Pending quiz
Recent score
Teacher comments
Finance summary
Notifications
Certificates
```

Primary CTA:

```text
Tiếp tục học
```

## Course card

Course card cần:

- Tên khóa
- Module hiện tại
- Tiến độ %
- Bài học tiếp theo
- Số buổi sắp tới
- Quiz đang chờ nếu có

## Lesson player

Route:

```text
/portal/bai-hoc/[slug]
```

Components:

- VideoPlayer
- LessonHeader
- ProgressBar
- MaterialList
- RelatedQuizCard
- NextLessonCTA

Progress data:

- percent
- last position
- completed
- updated at

UX:

- Resume progress automatically if allowed
- Show clear “Đánh dấu hoàn thành” or auto-complete logic
- Keep materials easy to find
- Related quiz should be near lesson completion area

## Quiz list

Route suggestion:

```text
/portal/quiz
```

Show:

- Assigned quiz
- Course/module/lesson relation
- Status: Chưa làm, Đang làm, Đã nộp, Cần làm lại nếu có
- Score if submitted
- Attempt count
- CTA: Bắt đầu / Làm lại / Xem kết quả

## Quiz attempt

Quiz attempt page needs:

- Quiz title
- Course/module/lesson context
- Attempt number
- Progress indicator
- Question card
- Answer options
- Submit action
- Warning if unanswered questions

Question types:

- Single choice
- Multiple choice
- True/false

## Quiz result

After submit, show:

- Score
- Correct count
- Total questions
- Percent
- Pass/fail if threshold exists
- Attempt history
- Review answers if allowed by policy

Result must sync to assessment result.

## Guardian view

Guardian can see:

- Student overview
- Progress reports
- Teacher comments intended for guardian
- Attendance
- Finance/công nợ
- Certificates
- Notifications

Guardian should not see admin-only data.

## Portal noindex

All portal pages must be noindex.

## Portal UI tone

Use supportive copy:

- “Bạn đang học tốt. Tiếp tục bài tiếp theo nhé.”
- “Bạn còn 2 bài kiểm tra cần hoàn thành.”
- “Phụ huynh có thể xem nhận xét mới từ giáo viên.”

Avoid admin-like harsh language.

---

<!-- Source: docs/10_CRM_FINANCE_LEARNING_RULES.md -->

# 10 — CRM, Finance, Learning Domain Rules

## CRM tuyển sinh

### Lead fields

Every lead should support:

- Customer name
- Phone
- Email
- Customer type
- Company
- Interested course
- Source
- UTM fields
- Affiliate code
- Campaign slug
- Course slug
- Page URL
- Referrer
- Assigned sales
- Pipeline stage
- Lead temperature
- Priority
- Follow-up date/time
- Follow-up status
- Notes

### Pipeline stages

Recommended labels:

- Mới
- Đang liên hệ
- Đang tư vấn
- Đăng ký học thử
- Đã học thử
- Chờ thanh toán
- Đã chuyển đổi
- Không phù hợp
- Mất lead

### Lead temperature

Use Vietnamese labels:

- Nóng
- Ấm
- Lạnh

Avoid showing raw `hot`, `warm`, `cold` unless in code only.

### Lead detail page

Must include:

- Contact summary
- Source attribution
- Sales owner
- Pipeline stage
- Follow-up status
- Consultation history
- Trial registrations
- Related orders/enrollments if any
- Timeline

## Trial registration

List page needs:

- Lead/student/customer
- Desired date
- Time slot
- Course interest
- Status
- Owner
- Actions

Status labels:

- Mới
- Đã xếp lịch
- Đã hoàn thành
- Đã hủy
- Không tham gia

Detail page should include status actions.

## Learning admin

Core entities:

- Courses
- Course modules
- Class groups
- Class sessions
- Teacher profiles
- Enrollments
- Attendance records
- Video lessons
- Materials
- LMS progress

Course management UI should show:

- Course status
- Audience
- Level
- Modules
- Lessons
- Enrollment count
- Revenue if relevant

## Attendance

Attendance statuses:

- Có mặt
- Vắng
- Đi muộn
- Có phép
- Chưa điểm danh

Attendance pages should support bulk update if class/session workflow requires it.

## Progress admin

Core entities:

- Assessments
- Assessment results
- Teacher comments
- Progress reports
- Certificates

Progress report page should combine:

- Attendance
- LMS progress
- Quiz/assessment results
- Teacher comments
- Suggested next step

## Finance admin

Core entities:

- Orders
- Order items
- Invoices
- Payments
- Receivables
- Receipts

Finance rules:

- Money formatting in Vietnamese currency: `8.500.000 ₫`
- Paid/receivable/overdue must be clearly separated
- B2C and B2B customers must be supported
- Never expose private finance data in public pages

Finance status labels:

- Chưa thanh toán
- Thanh toán một phần
- Đã thanh toán
- Quá hạn
- Đã hủy
- Hoàn tiền

## Affiliate

Core entities:

- Affiliate partners
- Affiliate links
- Affiliate clicks
- Affiliate commissions

Commission statuses:

- Chờ duyệt
- Đã duyệt
- Đã thanh toán
- Từ chối

Affiliate attribution must connect:

```text
Affiliate link/click -> Lead -> Order -> Commission
```

## Automation

Core entities:

- Automation workflows
- Messages
- Logs
- Notification outbox

Automation pages should show:

- Health
- Last run
- Success/failure count
- Failed messages
- Retry action if available

Never log secrets/tokens in automation logs.

---

<!-- Source: docs/11_TABLE_FORM_CHART_RULES.md -->

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

---

<!-- Source: docs/12_SECURITY_SEO_ACCESSIBILITY.md -->

# 12 — Security, SEO and Accessibility

## Security principles

DAYAI handles education, CRM, finance and progress data. Treat all private data carefully.

## Portal noindex

Private portals must be noindex:

- `/portal/*`
- `/company-portal/*`
- `/affiliate-portal/*`
- `/admin/*`

Use appropriate metadata/headers/robots handling.

## OTP security

OTP flows must include:

- Rate limit request OTP
- Expiration
- Limited access token
- Audit log
- No token leakage
- No OTP logging
- Clear error states

Do not reveal whether a private account exists unless product policy allows it.

## Logs

Never log:

- Passwords
- OTP
- Access tokens
- Refresh tokens
- Secrets
- API keys
- Private finance data
- Full PII when unnecessary

## RBAC

Admin must respect role permissions:

- admin
- sales
- teacher
- accountant
- content/admin roles if applicable

Navigation should hide unauthorized modules, but backend must also enforce permission.

## Public SEO

Public pages should support:

- Metadata title/description
- OpenGraph if available
- Semantic headings
- Sitemap
- robots rules
- Clean slug
- E-E-A-T content structure

## E-E-A-T content structure

For educational content:

- Clear author/organization signal
- Updated date where useful
- Practical examples
- Source/experience notes when relevant
- FAQ section
- Internal links to courses/resources

## Accessibility

Every UI must support:

- Keyboard navigation
- Visible focus state
- Sufficient color contrast
- Labels for inputs
- Alt text for meaningful images
- Button text not icon-only unless aria-label exists
- Semantic HTML where possible

## Forms accessibility

- Input must associate with label.
- Error text must be connected to field if possible.
- Required fields must be indicated.
- Do not rely only on color to show error.

## Tables accessibility

- Use table semantics for tabular data.
- Header cells should be clear.
- Sort controls should have labels.
- Row actions should have accessible labels.

## Quiz accessibility

Quiz options must be keyboard selectable.

For single choice:

- Radio group semantics

For multiple choice:

- Checkbox semantics

Feedback after submit should be announced or clearly visible.

## Data privacy copy

For public forms, add trust copy when appropriate:

```text
DAYAI chỉ sử dụng thông tin này để tư vấn lộ trình học phù hợp.
```

## Security review before go-live

Run checklist:

- Portal noindex confirmed
- OTP production secrets safe
- Logs do not expose secrets
- RBAC enforced server-side
- Finance data private
- Demo credentials disabled or isolated
- CORS/env configured
- Health check safe

---

<!-- Source: docs/13_CODING_RULES.md -->

# 13 — Coding Rules

## Before coding

Codex must:

1. Inspect existing folders/routes/components/services.
2. Identify current patterns.
3. Reuse before creating new abstractions.
4. Write a short plan if change touches multiple files.
5. Avoid unrelated rewrites.

## Implementation rules

- Keep changes small and reviewable.
- Preserve business logic unless the task explicitly changes it.
- Prefer typed interfaces.
- Add validation at boundaries.
- Handle loading, empty, error, and success states.
- Use design tokens.
- Use Vietnamese UI text.
- Avoid hardcoded fake data in production flows.
- Fix broken Vietnamese encoding when seen.

## File organization

Prefer clear module folders.

Suggested structure if applicable:

```text
components/
  shell/
  ui/
  data-table/
  forms/
  dashboard/
  quiz/
  lms/

features/
  crm/
  learning/
  finance/
  progress/
  content/
  affiliate/
  automation/
  reports/
  portal/

lib/
  api/
  auth/
  formatting/
  validation/
  permissions/
  constants/
```

Do not force this structure if the repo already has a good one. Adapt to existing conventions.

## Naming rules

Good:

- `LeadListPage`
- `TrialRegistrationDetail`
- `QuizAttemptPage`
- `AssessmentResultSummary`
- `formatCurrencyVND`
- `getLeadStatusLabel`

Bad:

- `Page2`
- `TableNew`
- `DataThing`
- `HelperFinal`
- `abc`

## Data formatting helpers

Centralize helpers:

- Currency formatter
- Date formatter
- Status label mapper
- Phone display
- Percent display
- Empty value display

Example:

```ts
export function formatVnd(amount: number | null | undefined) {
  if (amount == null) return '—';
  return new Intl.NumberFormat('vi-VN', {
    style: 'currency',
    currency: 'VND',
    maximumFractionDigits: 0,
  }).format(amount);
}
```

## UI state requirements

Every data-fetching page must handle:

- Loading
- Empty
- Error
- Success

Every mutation must handle:

- Pending
- Success
- Error
- Disabled duplicate submit

## Testing

Run available checks:

- lint
- typecheck
- test
- build

If a command is unavailable or fails for existing reasons, report it clearly.

## Database / migrations

For schema changes:

- Inspect existing models.
- Add migrations carefully.
- Preserve existing data.
- Avoid destructive migrations without explicit approval.
- Update seed data if needed.

## API rules

- Validate input.
- Return consistent errors.
- Do not expose secrets.
- Apply RBAC/ownership checks.
- Keep response DTOs stable.

## Git / diff hygiene

Codex should report:

- Files changed
- What changed
- Tests run
- Risk/limitations
- Follow-up recommendations

Avoid formatting the whole repo unless task is formatting.

---

<!-- Source: docs/14_SPRINT_34_QUIZ_ENGINE.md -->

# 14 — Sprint 34: Online Assessment & Quiz Engine

## Sprint goal

Build online quiz flow inside Student Portal and sync submitted results into Assessment Results.

## Scope

### Student portal

- Quiz list
- Start quiz
- Answer questions
- Submit quiz
- View score
- View correct count
- View attempt history

### Question types

- Single choice
- Multiple choice
- True/false

### Grading

- Auto-grade objective questions
- Single choice: exactly one correct option
- Multiple choice: selected set must match correct set unless partial credit is explicitly implemented
- True/false: selected boolean matches correct boolean

### Sync

Quiz result must sync to assessment result.

## Data relationships

```text
Course
  -> Module
    -> Lesson
      -> Quiz
        -> Question
          -> Options
        -> QuizAttempt
          -> Answers
          -> AssessmentResult
```

Quiz can be attached to:

- Lesson
- Module
- Course

At least one attachment context should be supported according to existing schema.

## Recommended statuses

Quiz status for student:

- Chưa làm
- Đang làm
- Đã nộp
- Đã đạt
- Chưa đạt

Attempt status:

- Đang làm
- Đã nộp
- Hết hạn nếu timer exists

## Quiz list UI

Each quiz card/row shows:

- Quiz title
- Course/module/lesson context
- Number of questions
- Time limit if available
- Last score
- Attempt count
- Status
- CTA

CTA rules:

- Chưa làm: `Bắt đầu`
- Đang làm: `Tiếp tục`
- Đã nộp: `Xem kết quả`
- Allow retake if business rule permits: `Làm lại`

## Start quiz UI

Before starting:

- Show title
- Show context
- Show question count
- Show time limit if any
- Show attempt policy
- Primary CTA: `Bắt đầu làm bài`

## Attempt UI

Question card:

- Question number
- Question text
- Question type label
- Options
- Save answer behavior if supported
- Progress indicator

Submit bar:

- Answered count
- Unanswered count
- Submit button

Before submit:

- Warn if unanswered questions exist.

## Result UI

Show:

- Score
- Percent
- Correct / total
- Submitted time
- Pass/fail if threshold exists
- Attempt history
- Review answers if allowed

Vietnamese copy examples:

```text
Bạn đạt 8/10 câu đúng.
Điểm số đã được đồng bộ vào báo cáo tiến bộ.
```

## Grading pseudocode

```ts
type QuestionType = 'single_choice' | 'multiple_choice' | 'true_false';

function gradeQuestion(question, selectedOptionIds) {
  const correctIds = new Set(question.options.filter(o => o.isCorrect).map(o => o.id));
  const selectedIds = new Set(selectedOptionIds);

  if (question.type === 'single_choice' || question.type === 'true_false') {
    return selectedIds.size === 1 && [...selectedIds].every(id => correctIds.has(id));
  }

  if (question.type === 'multiple_choice') {
    if (selectedIds.size !== correctIds.size) return false;
    return [...selectedIds].every(id => correctIds.has(id));
  }

  return false;
}
```

## Assessment result sync

After submit:

1. Calculate score.
2. Store quiz attempt.
3. Store answers.
4. Create/update assessment result.
5. Update progress report data if existing architecture supports it.
6. Show result page.

## Edge cases

Handle:

- Quiz has no questions
- Student is not enrolled
- Quiz not assigned to student/course
- Attempt already submitted
- Multiple submissions
- Network error on submit
- Missing answer
- Deleted/changed question after attempt start

## Acceptance criteria

Sprint 34 is done when:

- Student can see quiz list.
- Student can start a quiz.
- Student can answer single choice, multiple choice, true/false.
- Student can submit.
- Student can see score and correct count.
- Student can see attempt history.
- Result syncs to assessment result.
- Loading/empty/error states exist.
- Portal routes are noindex.
- OTP/security rules remain intact.
- UI text is Vietnamese.
- No broken Vietnamese encoding.
- Tests/build/lint are run if available.

---

<!-- Source: docs/15_REVIEW_CHECKLISTS.md -->

# 15 — Review Checklists

## Product checklist

- Does this screen support DAYAI as an AI Education Operating System?
- Is the primary user action obvious?
- Does the screen show the right data for decision-making?
- Does it avoid generic course-website language?
- Does it support role-based access where needed?

## UI checklist

- Uses design tokens
- Consistent spacing
- Consistent typography
- Consistent button style
- Consistent card style
- Responsive behavior checked
- Loading state
- Empty state
- Error state
- Success state where needed
- Vietnamese copy
- No mojibake

## Admin checklist

- Sidebar grouped logically
- PageHeader exists
- Table toolbar exists
- Search/filter labels are clear
- Row actions are not messy
- Status badges map to Vietnamese labels
- Detail pages have context, not just raw fields
- Dashboard shows insight/action, not decoration

## Portal checklist

- User can continue learning quickly
- Progress is visible
- Quiz flow is clear
- Guardian/student permissions are separated
- Finance info is private and clear
- Portal noindex

## Security checklist

- No secrets logged
- OTP not logged
- Tokens not exposed
- RBAC enforced server-side
- Private pages noindex
- Error messages do not leak internals

## Code checklist

- Existing patterns inspected
- No unrelated rewrites
- Types added/kept
- Validation added/kept
- Reusable components used
- Tests/checks run or limitations reported

---

<!-- Source: docs/16_CODEX_TASK_TEMPLATES.md -->

# 16 — Codex Task Templates

## Template 1 — General task

```text
Read AGENTS.md first.

Task:
[describe exact task]

Scope:
- [file/module/page 1]
- [file/module/page 2]

Rules:
- Preserve existing business logic unless needed.
- Use DAYAI design tokens/components.
- UI text must be Vietnamese.
- Include loading/empty/error/success states where relevant.
- Do not add production mock data.

Before coding:
- Inspect existing structure.
- Write a short plan.

After coding:
- Run available lint/typecheck/test/build.
- Report changed files and limitations.
```

## Template 2 — UI refactor

```text
Read AGENTS.md and these files:
- docs/03_DESIGN_LANGUAGE.md
- docs/04_DESIGN_TOKENS.md
- docs/05_COMPONENT_LIBRARY.md
- docs/11_TABLE_FORM_CHART_RULES.md

Refactor UI for [page/module].

Goal:
Make it feel like a premium enterprise education SaaS, not a generic CRUD/admin template.

Scope:
- Use existing business logic/API.
- Create/reuse shared components.
- Replace random spacing/colors with tokens.
- Improve hierarchy, table, card, buttons, empty/loading/error states.
- Fix broken Vietnamese encoding if found.

Do not:
- Rewrite unrelated modules.
- Add new dependencies without explaining why.
- Change database schema unless necessary.
```

## Template 3 — Sprint 34 quiz

```text
Read AGENTS.md and these files:
- docs/09_STUDENT_PORTAL_LMS_QUIZ.md
- docs/14_SPRINT_34_QUIZ_ENGINE.md
- checklists/sprint_34_acceptance_criteria.md

Implement Sprint 34 quiz flow.

Requirements:
- Quiz list in portal
- Start quiz
- Answer single choice / multiple choice / true-false
- Submit quiz
- Auto-grade
- Show score and correct count
- Show attempt history
- Sync result to assessment result
- Loading/empty/error/success states
- Vietnamese UI
- Portal noindex/security preserved

After implementation, run available checks and report changed files.
```

## Template 4 — Admin list page upgrade

```text
Read AGENTS.md and docs/08_ADMIN_UI_RULES.md + docs/11_TABLE_FORM_CHART_RULES.md.

Upgrade [admin list page] from raw CRUD table to DAYAI enterprise list page.

Requirements:
- Standard PageHeader with breadcrumbs, title, description, primary action
- DataToolbar with specific search placeholder and filters
- Shared DataTable component if available
- Status badges in Vietnamese
- Empty/loading/error states
- Pagination/result count
- Row action menu
- No uncontrolled horizontal scrollbar unless truly needed
```

## Template 5 — Fix Vietnamese encoding

```text
Read AGENTS.md.

Find and fix broken Vietnamese encoding in UI and seed/demo data.

Examples to search:
- Pháº
- HÃ
- Ä
- á»
- Láº

Check:
- seed files
- database fixtures
- frontend constants
- API serialization
- page rendering

Do not change valid Vietnamese strings.
Report files changed.
```

---

<!-- Source: docs/17_UI_REDESIGN_PLAYBOOK.md -->

# 17 — UI Redesign Playbook

## Mục tiêu

Dùng playbook này để cải thiện UI hiện tại mà không phá business logic.

## Observed issues from current screenshots

1. Homepage khá ổn về ý tưởng nhưng hơi nặng, tối và nhiều khối chữ nhật.
2. Admin dashboard có architecture tốt nhưng visual chưa premium.
3. Admin sidebar dài, nhiều item, grouping chưa đủ rõ.
4. Table giống CRUD mặc định, chưa giống CRM/ERP enterprise.
5. Detail pages quá trống, thiếu summary/timeline/context.
6. Vietnamese text có lỗi encoding ở một số dữ liệu.

## Redesign order

Không sửa tất cả cùng lúc.

### Phase 1 — Foundation

- Add design tokens
- Add shared UI primitives
- Normalize typography
- Normalize colors/surfaces
- Normalize spacing/radius/shadow

### Phase 2 — Admin shell

- Sidebar grouping
- Topbar polish
- Main content width
- PageHeader component
- Breadcrumbs
- Better scroll behavior

### Phase 3 — Data pages

- DataToolbar
- DataTable
- StatusBadge
- Pagination
- Empty/loading/error states
- Detail page template

### Phase 4 — Dashboard

- Command center compacted
- KPI cards upgraded
- Panels/charts/lists structured
- At-risk/lead/follow-up insights

### Phase 5 — Public homepage

- Lighter premium hero
- Product mockup visual
- Better section rhythm
- More trust and conversion clarity

### Phase 6 — Student portal / quiz

- Portal dashboard polish
- Lesson player
- Quiz list/attempt/result

## Admin visual target

From:

```text
Dark CRUD table + sidebar
```

To:

```text
Premium dark enterprise OS with clear command center, high-density data, calm hierarchy and reusable components.
```

## Homepage visual target

From:

```text
Dark hero with text cards
```

To:

```text
Premium AI education homepage with lighter trust, product preview, real learning outcomes and clear CTA.
```

## Detail page target

From:

```text
Labels floating on empty dark page
```

To:

```text
Structured detail page with summary card, context, timeline, related records and clear actions.
```

## Table target

From:

```text
Wide scroll table with many columns and raw badges
```

To:

```text
CRM-grade table with toolbar, filters, pinned key information, compact actions and readable statuses.
```

## First UI refactor tasks for Codex

1. Create or centralize design tokens.
2. Create/reuse `PageHeader`, `KpiCard`, `DataToolbar`, `DataTable`, `StatusBadge`, `EmptyState`, `LoadingSkeleton`.
3. Apply to admin dashboard.
4. Apply to lead list.
5. Apply to trial registration list/detail.
6. Fix Vietnamese encoding in visible data.
7. Run checks.

## Anti-patterns

Do not:

- Add more gradients to hide weak layout.
- Increase font sizes randomly.
- Add animation before layout is fixed.
- Rebuild the whole app shell without understanding existing routes.
- Create a second table system.
- Add a UI library dependency without approval.
