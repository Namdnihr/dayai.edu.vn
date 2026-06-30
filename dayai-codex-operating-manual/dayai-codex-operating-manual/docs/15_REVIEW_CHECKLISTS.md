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
