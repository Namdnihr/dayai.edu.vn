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
