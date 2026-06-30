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
