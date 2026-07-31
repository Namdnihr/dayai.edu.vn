# DAYAI - Sprint 35 Filament Admin UX Spec

## 1. Outcome

Filament admin is organized by job-to-be-done instead of database entities. Permissions continue to decide which resources a user can access; navigation groups make the allowed resources easier to scan for each role.

## 2. Navigation IA

| Group | Primary users | Resources and pages |
| --- | --- | --- |
| Tổng quan | All admin roles | Dashboard vận hành |
| CRM & Tuyển sinh | Admissions, sales, CSKH | Leads, sources, assignments, consultation history, trial registrations, customer accounts |
| Đào tạo & LMS | Academic ops, teachers | Courses, modules, video lessons, classes, sessions, enrollments, attendance, students, teachers, guardians, progress, comments, certificates |
| Kiểm tra & Đánh giá | Academic ops, teachers | Question banks, questions, assessments, results, quiz attempts |
| Tài chính | Accounting, operations | Orders, order items, invoices, payments, receipts, receivables, revenue report |
| Nội dung | Content, marketing | Content categories, content items, notifications |
| Affiliate | Partnership, accounting | Partners, links, clicks, commissions |
| Tự động hóa | Operations, super admin | Workflows, message templates, delivery logs |
| Báo cáo | Management | BI reports and exports |
| Hệ thống | Super admin | Organizations, branches, people, users, roles, activity logs |

### Role defaults

- Admissions lands on CRM and sees the operational dashboard as the first summary surface.
- Academic operations lands on Đào tạo & LMS; quiz work stays in Kiểm tra & Đánh giá.
- Accounting lands on Tài chính; affiliate commissions remain visible only when permission allows.
- Content users work only in Nội dung.
- Management uses Tổng quan and Báo cáo.
- Super admin can access Hệ thống and all operational groups.

## 3. Page Patterns

### Dashboard

- First row: 4-6 decision metrics, each with period, delta, and destination.
- Second row: operational queues requiring action, not decorative charts.
- Third row: trend charts and drill-down tables.
- Every alert links to a filtered list; never leave the user at a dead-end metric.
- Empty states explain what creates the data and provide the next permitted action.

### List

- Page header contains title, short scope description, and one primary create action.
- Default columns answer identity, current status, owner, value/progress, and updated time.
- Filters appear in business order: time/branch, status, owner, then domain-specific filters.
- High-frequency filters should be selectable without opening an advanced panel.
- Row actions use View as the safe default; Edit and destructive actions remain secondary.
- Bulk actions require a clear eligible-record rule and a confirmation summary.
- Tables must remain usable at 1280px without horizontal scrolling for primary columns.

### Create and edit

- Group fields into 2-4 named sections following the business workflow.
- Required fields appear before optional metadata.
- Relationship selectors support search and show the business identifier, not only an internal name.
- Validation messages use Vietnamese and explain how to fix the value.
- The primary save action stays consistent; secondary actions never visually compete with it.
- Long forms may use a sticky action bar, but must not hide the final validation summary.

### View and detail

- Header shows record identity, status, owner, and the next permitted action.
- Summary cards contain the fields used for decisions; raw metadata stays in a lower section.
- Timeline/audit information is chronological and shows actor, action, and time.
- Related operational records use relation managers instead of duplicating full tables on the page.
- Sensitive values follow permission checks already defined by resource policies.

### Relation managers

- The parent record remains visible in the page title or summary while editing relations.
- Relation tables inherit the list pattern: useful defaults, filters, empty state, and safe row actions.
- Create/link actions must clarify whether they create a new record or attach an existing one.
- Destructive detach/delete actions state their different effects before confirmation.
- Relation managers with more than 20 rows need search or filters.

## 4. Shared UI Contracts

- Status badge colors: blue/in-progress, green/success, amber/warning, red/error, slate/inactive.
- Monetary values use Vietnamese currency formatting and align right in tables.
- Dates use `dd/mm/yyyy`; date-time values also include local time.
- IDs/codes remain copyable and visually distinct from human-readable names.
- Buttons: one primary action per surface, neutral secondary actions, red only for destructive actions.
- Alerts always state what happened, what is affected, and what the user can do next.
- Loading, empty, error, and permission-denied states are required for every high-traffic resource.

## 5. Polish Order

1. Dashboard vận hành.
2. Leads and consultation workflow.
3. Courses, enrollments, classes, and attendance.
4. Assessments and quiz attempts.
5. Orders, invoices, payments, and receivables.
6. Content and notifications.
7. Affiliate and automation.
8. System resources.

## 6. Definition Of Done

- All registered navigation groups match this IA and no legacy group remains.
- Each role sees only permitted resources, in the expected business group.
- High-traffic list pages follow the list contract.
- High-traffic forms use named sections and Vietnamese validation copy.
- View pages expose the next action and relevant relations.
- Admin routes load successfully and the backend test suite passes.
