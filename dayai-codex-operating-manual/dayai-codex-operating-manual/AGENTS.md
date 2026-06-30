# AGENTS.md — DAYAI Product Coding Rules

## Mission

DAYAI is an AI Education Operating System for Vietnam.

DAYAI is not a generic course-selling website. It is a multi-portal education operations platform with:

1. Public website
2. Internal admin CRM/ERP
3. Student / guardian portal
4. Company / HR portal
5. Affiliate portal
6. Operational infrastructure

All work must preserve this product architecture.

## Current Sprint

DAYAI is currently at Sprint 34: Online Assessment & Quiz Engine.

Priority UI areas:

1. Public homepage
2. Admin dashboard + sidebar modules
3. Student portal / LMS / quiz
4. HR portal
5. Affiliate portal

## Required Reading Before Work

Always read this file first.

Then read the relevant manual files:

- Product / architecture: `docs/01_PRODUCT_PHILOSOPHY.md`, `docs/02_PRODUCT_ARCHITECTURE.md`
- UI/design: `docs/03_DESIGN_LANGUAGE.md`, `docs/04_DESIGN_TOKENS.md`, `docs/05_COMPONENT_LIBRARY.md`
- Public website: `docs/07_PUBLIC_WEBSITE_RULES.md`
- Admin/CRM/ERP: `docs/08_ADMIN_UI_RULES.md`, `docs/10_CRM_FINANCE_LEARNING_RULES.md`
- Student/LMS/quiz: `docs/09_STUDENT_PORTAL_LMS_QUIZ.md`, `docs/14_SPRINT_34_QUIZ_ENGINE.md`
- Tables/forms/charts: `docs/11_TABLE_FORM_CHART_RULES.md`
- Security/SEO/accessibility: `docs/12_SECURITY_SEO_ACCESSIBILITY.md`
- Coding rules: `docs/13_CODING_RULES.md`

## Product Principles

Build DAYAI as:

- Premium
- Trustworthy
- Vietnamese-first
- Education-focused
- Enterprise-ready
- Role-based
- Data-driven
- SEO-aware for public pages
- Secure for private portals
- Scalable as an operating system, not a small course website

## UI/UX Direction

Use one unified design system across all product areas.

Design reference blend:

- Apple: clarity, whitespace, premium presentation
- OpenAI: calm intelligence, simple language, trust
- Stripe: product-system polish, gradients, strong hierarchy
- Linear: dense admin workflows that still feel elegant
- Coursera: learning and course credibility
- Notion: approachable structure and low friction

Do not copy any brand directly. Build DAYAI’s own visual language.

## Design System Rules

Use the tokens in:

- `design/dayai-tokens.json`
- `design/dayai-tokens.css`
- `design/tailwind-theme-extension.example.ts`

Never introduce random colors, spacing, border radius, shadows, or typography scales without updating the design tokens and explaining why.

Allowed spacing scale:

`4, 8, 12, 16, 24, 32, 48, 64, 96`

Allowed radius scale:

`8, 12, 16, 20, 24, 999`

All visible UI text must be Vietnamese.

Never use Lorem Ipsum.

Fix mojibake or broken Vietnamese encoding whenever found, for example strings like `Pháº¡m`, `HÃ`, `Ä`, `á»`.

## Frontend Architecture Rules

Prefer reusable components.

Required component groups:

- AppShell
- Sidebar
- Topbar
- PageHeader
- Breadcrumbs
- KPI cards
- Dashboard panels
- DataTable
- FilterBar
- SearchBox
- FormField
- DetailPanel
- EmptyState
- LoadingSkeleton
- ErrorState
- Toast
- Modal
- Tabs
- Badge / StatusBadge
- Stepper
- LessonPlayer
- Quiz components

Do not duplicate UI logic if a reusable component already exists.

## Module Map

Public website:

- Homepage
- Course listing
- Course detail / landing
- Audience pages
- Resource/news pages
- Contact/lead forms

Admin:

- Dashboard
- CRM
- Learning
- Finance
- Progress
- Content
- Affiliate
- Automation
- BI Reports
- Settings/RBAC

Student portal:

- OTP auth
- Overview
- Courses
- Lesson player
- LMS progress
- Quiz
- Finance
- Progress reports
- Certificates
- Notifications

Company / HR portal:

- Company overview
- Employee list
- Employee detail
- Learning progress
- Finance summary
- Notifications

Affiliate portal:

- Partner overview
- Campaign links
- Clicks
- Leads
- Orders
- Commissions

## Sprint 34 Quiz Rules

Build the quiz system with:

- Quiz list in portal
- Start quiz
- Answer questions
- Submit quiz
- View score
- Correct answer count
- Attempt history
- Support single choice
- Support multiple choice
- Support true / false
- Auto grading
- Sync result to assessment results

Quiz must connect to:

- Lesson
- Module
- Course
- Student
- Assessment result

## CRM Rules

Every lead should support:

- Source
- UTM
- Affiliate code
- Campaign slug
- Course slug
- Page URL
- Referrer
- Assigned sales
- Pipeline stage
- Lead temperature
- Follow-up status
- Trial registration history

## Portal Security Rules

Portal pages must not be indexed by search engines.

OTP must support:

- Rate limit
- Limited access token
- Audit log
- Separate student and guardian roles

Never log secrets, OTP, tokens, passwords, or private financial data.

## SEO Rules

Public pages should include:

- Metadata
- Sitemap support
- Robots rules
- llms.txt
- E-E-A-T content structure
- Semantic HTML
- Clean URL slug

Private portals must be `noindex`.

## Coding Behavior

Before coding:

1. Inspect existing structure.
2. Reuse existing patterns.
3. Identify related models, routes, components, and services.
4. Plan changes briefly.

While coding:

1. Keep changes small and focused.
2. Do not rewrite unrelated code.
3. Do not invent a new architecture if the existing one works.
4. Add types and validation.
5. Handle loading, empty, error, and success states.
6. Preserve existing business logic unless the task explicitly changes it.

After coding:

1. Run lint/test/build if available.
2. Report what changed.
3. Mention files changed.
4. Mention any limitation or follow-up.

## Quality Bar

Code must be:

- Maintainable
- Typed
- Modular
- Secure
- Production-ready
- Easy for future Codex sessions to understand

Do not create demo-only code unless explicitly requested.

Do not hardcode fake data into production flows.

Mock data is allowed only in demo/dev files and must be clearly labeled.

## Final Rule

When unsure, choose the option that makes DAYAI more scalable, consistent, secure, and premium as an AI Education Operating System.
