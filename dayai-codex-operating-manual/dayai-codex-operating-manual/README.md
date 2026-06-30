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
