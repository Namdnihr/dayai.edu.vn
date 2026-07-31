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
- [x] Lock student/parent portal IA.
- [x] Lock admin module grouping by role.
- [x] Standardize design tokens: color, typography, spacing, radius, shadow, status colors.
- [x] Standardize component patterns: button, badge, stat, table, filter, form, tab, empty state, alert.
- [x] Redesign homepage first viewport and main sections.
- [x] Redesign public child page template for course, audience, solution, resource, news, contact, and consultation pages.
- [x] Redesign student portal dashboard layout.
- [x] Redesign lesson player and quiz UI.
- [x] Write admin UX spec for dashboard, list, create/edit, view/detail, relation managers.
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

## 14. Public Page Experience Pass

- Rebuilt the shared public SEO landing template around page-specific experience profiles instead of a generic repeated layout.
- Added dedicated content and visual directions for AI Kids, AI Student, AI Work, AI Business, AI Enterprise, Resources, AI News, Consultation, and Course pages.
- Added richer sections for each profile: hero promise, experience outcomes, journey map, proof rows, related page cards, and contextual CTA.
- Refactored `/doi-tuong-hoc/` into a deeper audience-routing hub with five distinct learning paths, comparison matrix, animated visual, and advisory CTA.
- Added subtle motion utilities for lift, floating markers, and progress-line emphasis while keeping reduced-motion support.
- Verified representative pages in browser: `/doi-tuong-hoc`, `/ai-enterprise`, `/tai-nguyen`, `/tin-tuc-ai`, `/dang-ky-tu-van`, `/ai-kids`, `/ai-student`, and `/ai-work`.
- Re-ran `npm.cmd run lint` and `npm.cmd run build` successfully.

## 15. Student Portal Experience Pass

- Refactored `/portal` into a stronger learner-facing dashboard entry with a product header, demo learner cards, LMS/quiz/finance/notification/certificate chips, and clearer private learning copy.
- Added an account-aware dashboard overview with a continue-learning panel, quick actions for quiz/finance/notifications, and more consistent token-based cards.
- Polished the LMS course tab so lesson actions use client-side navigation and module/lesson progress reads as a learning workflow instead of a raw data list.
- Refined `/portal/bai-hoc/[slug]` with a stronger lesson hero, video stage, progress-save panel, lesson navigation cards, outline, related assessments, and resources panel.
- Replaced remaining internal lesson anchors with Next.js `Link` to reduce full page reload/flicker during portal navigation.
- Verified local OTP demo login for `0901888000` / `HV-000001`, opened the authenticated portal dashboard, clicked into `/portal/bai-hoc/nguyen-tac-dung-ai-an-toan`, and confirmed no runtime error or horizontal overflow.
- Re-ran `npm.cmd run lint` and `npm.cmd run build` successfully.

## 16. Locked Student Portal IA

- Entry: OTP request, OTP verification, session restore, and logout.
- Overview: continue learning, next assessment, schedule, finance, notifications, and progress summary.
- Courses: paid/free enrollments, module structure, lesson progress, and continue actions.
- Schedule: upcoming sessions and attendance history.
- Finance: balance, orders, invoices, payments, and receipts.
- Reports: assessment results, teacher comments, and progress reports.
- Tests: available assessments, quiz room, result state, and attempt history.
- Notifications: learner-facing operational updates.
- Certificates: issued certificates and related learning resources.

## 17. Filament Admin IA And UX Spec

- Replaced the broad legacy groups with role-oriented groups: Tổng quan, CRM & Tuyển sinh, Đào tạo & LMS, Kiểm tra & Đánh giá, Tài chính, Nội dung, Affiliate, Tự động hóa, Báo cáo, and Hệ thống.
- Moved each Filament resource into its business group while preserving the existing resource permission checks.
- Fixed broken Vietnamese navigation labels for question banks, questions, and assessments.
- Added the detailed page-pattern and role matrix in `docs/21-sprint35-admin-ux-spec.md`.

## 18. Shared Component Foundation

- Added reusable frontend primitives for button, badge, alert, field/input, panel, stat, tab, empty state, filter bar, and responsive table shell.
- Kept the primitives dependency-free and presentation-only so they can be composed inside both Server and Client Component trees.
- Applied the first production pass to portal OTP, session messaging, dashboard tabs, stats, quiz actions/states, lesson progress controls, related assessments, and lesson empty states.
- Added shared disabled, focus, status-tone, compact-size, and responsive overflow behavior to the global DAYAI component layer.

## 19. Interactive Video Learning

- Added the internal Full HD demo video for `Bài 1: AI là gì và học AI bắt đầu từ đâu?`.
- Added required video checkpoints at 30, 60, 90, and 120 seconds with a 30-second response window and immediate learning feedback.
- The HTML5 player pauses at the first unanswered checkpoint, blocks seeking past it, and cannot mark the lesson complete until every checkpoint has an answer.
- Added backend persistence for checkpoint answers, correctness, learner notes, last position, and completion gating.
- Added a lesson notebook with copy-ready AI prompts and personal notes saved per learner and lesson.
- Added API coverage for blocked completion, checkpoint answer validation, correctness calculation, prompt configuration, and saved learner notes.

## 20. Focused And Active Learning Pass

- Added a compact mobile lesson hero and a focus mode that removes the course outline, keeps the video prominent, and places the notebook/practice workspace beside it on wide screens.
- Added automatic video pause when the learner hides the tab or has no interaction for two minutes, plus an explicit resume prompt and persisted attention counters.
- Replaced native video-only fullscreen with an interactive-stage fullscreen control so checkpoint dialogs and attention prompts remain visible while the lesson is fullscreen.
- Added searchable timeline notes with click-to-seek behavior. These are intentionally labeled as time-based summaries, not verbatim captions.
- Added debounced note autosave with visible dirty, saving, saved, and error states while retaining the manual save action.
- Added an AI practice room for copying a prompt, using an external AI tool, pasting the result, reflecting on verification, rating confidence, and saving the exercise per lesson.
- Added an end-of-lesson summary with correct-answer count, note/practice indicators, next-lesson action, and retry controls for incorrect checkpoints.
- Extended the lesson progress API to persist practice sessions and attention metrics inside `interaction_state`.
- Verified the pass with frontend lint/build, 29 backend tests (215 assertions), and direct browser checks for authenticated loading, focus mode, checkpoint gating, timeline seeking, and note autosave.

## 21. Course Lesson Publisher

- Rebuilt the Filament video-lesson form as a seven-part publishing workspace: course position, lesson content, video/resources, interactive checkpoints, timeline notes, practice prompts, and access/publishing.
- Added direct MP4/WebM upload to public course storage while retaining YouTube, Vimeo, and external-link sources.
- Added repeatable resource uploads, 30-second checkpoint authoring, correct-answer/explanation controls, timeline markers, and copy-ready AI prompts.
- Reused the same publisher in the standalone `Bài học video` resource and inside each course's `Video bài học` relation manager.
- Filtered modules by the selected course and preserved legacy lesson category metadata during edits.
- Added public storage delivery through Nginx and API URL resolution for uploaded videos and resource files.
- Migrated and seeded the running Docker environment, verified the real admin create/edit screens in the browser, and passed all 29 backend tests (215 assertions).
