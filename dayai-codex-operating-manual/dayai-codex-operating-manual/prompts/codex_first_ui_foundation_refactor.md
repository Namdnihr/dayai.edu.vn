# Codex Prompt — First UI Foundation Refactor

Copy prompt này vào Codex để bắt đầu sửa UI hiện tại.

```text
Read AGENTS.md first.

Then read these files:
- docs/03_DESIGN_LANGUAGE.md
- docs/04_DESIGN_TOKENS.md
- docs/05_COMPONENT_LIBRARY.md
- docs/08_ADMIN_UI_RULES.md
- docs/11_TABLE_FORM_CHART_RULES.md
- docs/17_UI_REDESIGN_PLAYBOOK.md
- checklists/ui_quality_gate.md

Task:
Refactor the current DAYAI UI foundation so the app feels like a premium AI Education Operating System instead of a generic dark CRUD/admin theme.

Scope for this first pass:
1. Do not change business logic.
2. Do not change database schema unless absolutely necessary.
3. Create or centralize DAYAI design tokens using the existing styling system.
4. Build/reuse shared UI components:
   - PageHeader
   - KpiCard
   - DataToolbar
   - DataTable or table wrapper
   - StatusBadge
   - EmptyState
   - LoadingSkeleton
5. Apply the system to:
   - Admin dashboard
   - Lead list page
   - Trial registration list page
   - Trial registration detail page
6. Fix visible broken Vietnamese encoding if found, especially seed/demo strings like Pháº, HÃ, Ä, á».
7. Improve admin shell spacing, sidebar grouping, page width, card hierarchy and table readability.

Acceptance criteria:
- UI uses shared tokens/components instead of random one-off styles.
- Admin pages have standard PageHeader with title, description and actions.
- Tables have toolbar, clear search placeholder, status badges, pagination/result count and empty/loading/error states.
- Detail page is structured with summary/context instead of raw floating label-value text.
- Sidebar grouping is clearer and active state is consistent.
- No user-facing English/raw enum labels where Vietnamese labels should be shown.
- No unrelated rewrite.
- Run available lint/typecheck/test/build and report results.

Before coding:
- Inspect existing structure and styling approach.
- Write a short implementation plan.

After coding:
- Report changed files.
- Report tests/checks run.
- Mention limitations and next recommended task.
```
