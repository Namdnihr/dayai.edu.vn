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
