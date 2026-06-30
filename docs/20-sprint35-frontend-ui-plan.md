# DAYAI - Sprint 35 Frontend UI & Experience Foundation

Sprint 35 moves the project from feature expansion to a consistent product UI foundation across the public website, student/parent portal, HR portal, affiliate portal, LMS/quiz experience, and Filament admin.

## 1. Goals

- Map all major UI surfaces after Sprint 34.
- Standardize the DAYAI visual language across public, portal, and admin screens.
- Prioritize real product screens over generic marketing sections.
- Create a checklist that lets the frontend work continue screen by screen without scope drift.

## 2. UI Principles

- Public website: polished, visual, trustworthy, and conversion-focused.
- Admin/CRM: dense, scan-friendly, table-first, filter-first, action-first.
- Student portal: simple, calm, and focused on the next learning action.
- Lesson/quiz: clear status, clear progress, clear submit/result states.
- Mobile layouts must avoid overflowing text, cramped buttons, and hidden primary actions.

## 3. UI Map

### Public Website

- Homepage `/`
- Course list `/khoa-hoc`
- Course landing `/khoa-hoc/[slug]`
- Audience hubs: `/ai-kids`, `/ai-student`, `/ai-work`, `/ai-business`, `/ai-enterprise`
- Resource hubs: `/cam-nang-ai`, `/prompt-ai`, `/cong-cu-ai`, `/tin-tuc-ai`, `/case-study`, `/tai-nguyen`
- Conversion pages: `/dang-ky-tu-van`, `/lien-he`, `/ve-day-ai`, `/giang-vien`

### Student / Parent Portal

- OTP request and verify
- Session restore
- Student dashboard
- Courses and LMS progress
- Lesson player
- Quiz and attempt history
- Finance, notifications, reports, certificates

### Company / HR Portal

- Company lookup
- Employee list
- Learning progress
- Attendance and assessment snapshot
- B2B finance summary
- Company notifications

### Affiliate Portal

- Partner lookup
- Campaign links
- Click and lead performance
- Commission status
- Payout readiness

### Filament Admin

- Operational dashboard
- CRM admissions
- Learning management
- Student progress
- Online assessments and quiz attempts
- Finance
- Content
- Affiliate
- Automation
- BI reports
- Settings, RBAC, and audit logs

## 4. Sprint 35 Checklist

- [x] Create UI inventory for public site, portals, and admin.
- [x] Map screens by user group.
- [x] Lock public website navigation IA.
- [ ] Lock student/parent portal IA.
- [ ] Lock admin module grouping by role.
- [x] Standardize design tokens: color, typography, spacing, radius, shadow, status colors.
- [ ] Standardize component patterns: button, badge, stat, table, filter, form, tab, empty state, alert.
- [x] Redesign homepage first viewport and main sections.
- [x] Redesign public child page template for course, audience, solution, resource, news, contact, and consultation pages.
- [ ] Redesign student portal dashboard layout.
- [ ] Redesign lesson player and quiz UI.
- [ ] Write admin UX spec for dashboard, list, create/edit, view/detail, relation managers.
- [x] Run `npm run lint` and `npm run build` after the public homepage pass.

## 5. Implementation Order

1. Public homepage shell.
2. Public header, footer, and navigation.
3. Course listing and course landing template.
4. Student portal dashboard and OTP flow.
5. Lesson player and quiz UI.
6. HR portal and affiliate portal.
7. Filament admin theme/navigation polish.
8. High-traffic admin resources: Leads, Courses, Enrollments, Orders, Assessments, Quiz Attempts.

## 6. Definition Of Done

- UI map exists for all product surfaces.
- Public homepage has a coherent visual direction.
- Header/footer/navigation use clean display text.
- Homepage, lint, and production build pass.
- Remaining portal/admin work is broken down into concrete follow-up UI tasks.

## 7. Progress Update - 2026-06-30

- Added DAYAI design tokens to the frontend global CSS.
- Added reusable public UI primitives for container, section, card, chip, button, and focus states.
- Refactored public header and footer with clean Vietnamese text and token-based styling.
- Refactored homepage into a product-first DAYAI experience covering website, portal, LMS, quiz, HR portal, and admin OS.
- Verified desktop and mobile in browser: no mojibake, no horizontal overflow, and mobile hero shows the next section cue.
- Re-ran `npm.cmd run lint` and `npm.cmd run build` successfully.

## 8. Progress Update - Public Child Pages

- Refactored the shared SEO landing template used by audience, course, solution, resource, news, about, instructor, contact, and consultation pages.
- Refactored the lead form with clean Vietnamese copy and token-based form controls.
- Refactored `/doi-tuong-hoc/` as a custom audience hub using the same DAYAI visual system.
- Refactored `/khoa-hoc/ai-can-ban` to align the custom course landing page with the new token system while keeping video as the primary visual asset.
- Verified representative pages in browser: `/doi-tuong-hoc/`, `/ai-kids/`, `/khoa-hoc/`, `/ai-enterprise/`, `/dang-ky-tu-van/`, and `/khoa-hoc/ai-can-ban`.
- Re-ran `npm.cmd run lint` and `npm.cmd run build` successfully after the child-page pass.

## 9. Public Child Page Visual Variants

- Replaced the repeated right-side hero mockup with group-specific visual variants.
- Course pages now show a learning path visual.
- Kids pages now show a creative AI studio visual.
- Student pages now show a study cockpit visual.
- Work pages now show a productivity workflow visual.
- Business and Enterprise pages now show dashboard/progress visuals.
- Resource, prompt, tool, news, and case-study pages now show a knowledge lab visual.
- Contact and consultation pages now show an advisor desk visual.
- Rule going forward: public child pages may share structure, but hero visuals must vary by user intent and page group.

## 10. Consultation Form Redesign

- Refactored the shared consultation form into a conversion panel with a clear header, response-time badge, privacy reassurance, two-column field rhythm, CTA panel, and clearer success/error states.
- Moved the consultation form into the hero area for `/dang-ky-tu-van/` and `/lien-he/` so conversion pages show the form immediately instead of only near the bottom.
- Kept one form per conversion page to avoid duplicate submissions and visual repetition.
- Refined the custom course lead form on `/khoa-hoc/ai-can-ban` to match the same form language.
- Verified desktop and mobile: no mojibake, no horizontal overflow, and form placement is visible early on consultation pages.

## 11. Course Catalog And Course Detail

- Replaced `/khoa-hoc/` with a real course catalog instead of the generic SEO page.
- Added Categories filter, search, sort, result count, reset filter action, and responsive course cards.
- Course cards now show image, badge, category, rating, audience, sessions, lesson count, student count, price label, and detail CTA.
- Added a structured course detail template for `/khoa-hoc/[slug]/` pages such as `/khoa-hoc/khoa-hoc-chatgpt/`.
- Course detail pages now include hero, course meta, learning outcomes, curriculum modules/lessons, tool chips, and sticky enrollment sidebar.
- Verified category filtering, desktop/mobile layout, no mojibake, no horizontal overflow, and production build.

## 12. Course Access Flow

- Added catalog metadata for `free_funnel`, `paid`, and `consultation` course flows.
- Course detail pages can explain the access flow when needed, but catalog cards should keep the copy clean and only show the real CTA: consultation, checkout, or free learning.
- Free/funnel courses now use account-first access instead of a lead-style form: learners register/login with email and password, must verify email, then the course is activated.
- Local email verification returns a demo token/button for testing; production should send the verification email through the configured mail provider.
- Google login is represented as the intended OAuth entry point but stays disabled until Google Client ID/Secret are configured.
- Paid courses open a checkout modal, create order/invoice first, and only create active enrollment after the test payment confirmation endpoint succeeds.
- Consultation courses keep the user in the lead/advisor path via `/dang-ky-tu-van/`.
- Course catalog now uses one page-level access modal for free and paid courses; cards only trigger that shared modal, which prevents duplicate popup/login flicker.
- Course card CTA layout is fixed to a two-column action row so detail and primary actions stay aligned across consultation, paid, and free cards.
- Added public backend endpoints: `/api/course-access/free-enroll`, `/api/course-access/checkout`, and `/api/course-access/checkout/{orderCode}/mark-paid`.
- Added frontend proxy endpoints under `/api/course-access/...` so the Next.js UI can call the Laravel API through the configured backend URL.
- Enrollment success now links to `/portal` with `phone`, `student_code`, and `tab=courses` query params; the portal login form auto-fills those credentials for faster testing.
- Verified by API and browser UI: paid checkout creates pending order, test payment marks order paid, portal lookup shows finance balance 0 and LMS lessons; free course opens only after email verification.

## 13. Handoff Note - 2026-07-01

### Done Today

- Finished the public course catalog `/khoa-hoc` with category filter, search, sort, course cards, and course detail routes.
- Fixed the catalog card action layout so `Chi tiết` and the primary CTA stay aligned across consultation, paid, and free courses.
- Removed noisy access-flow badges from the course card; the card now only shows the actual action: `Tư vấn`, `Thanh toán`, or `Học miễn phí`.
- Reworked course access so the catalog uses one page-level modal for free/paid flows instead of one modal per card, preventing popup flicker.
- Implemented account-first free course flow: register/login by email, email verification required, then activate free enrollment.
- Implemented paid course demo flow: checkout creates order/invoice, test payment confirmation creates active enrollment.
- Created verified demo learner with one free course and one paid course active.
- Verified portal lookup returns 2 active enrollments and LMS lessons for both courses.
- Re-ran `npm.cmd run lint` and `npm.cmd run build` successfully after the UI changes.

### Demo Learner For Tomorrow

- Email: `hocvien.demo@dayai.edu.vn`
- Password: `Dayai@123456`
- Phone: `0901002003`
- Student code: `HV-260630181712-IOBH`
- Portal link: `http://localhost:3001/portal?phone=0901002003&student_code=HV-260630181712-IOBH&tab=courses`
- Courses active: `Khóa học AI Cơ Bản` and `Khóa học ChatGPT`.

### Tomorrow Priority

- Start with the student portal UI because the course/payment flow now lands there.
- Redesign `/portal` dashboard around the real learner state: continue learning, active courses, lesson progress, quiz, finance, notifications, certificates.
- Make the course tab stronger: show paid/free courses clearly, continue button per course, module/lesson progress, and empty/loading/error states.
- Polish the lesson player `/portal/bai-hoc/[slug]`: better header, video/content area, resource panel, progress state, and next lesson CTA.
- Polish quiz UI inside portal and lesson context: start quiz, answer questions, submit, result state, attempt history.
- Decide whether portal should keep OTP-first login only, or merge the new email/password learner account into a proper student login screen.
- After portal UI, move to admin UX spec and module grouping: Learning, CRM, Finance, Content, Quiz/Assessment, Affiliate, Automation, Reports, Settings.
- Keep SePay/payment integration as a planned backend integration item after local demo flow is stable and merchant credentials are available.
