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
