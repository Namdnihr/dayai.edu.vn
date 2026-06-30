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
