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
