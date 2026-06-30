# 02 — Product Architecture

## 6 cụm sản phẩm lớn

DAYAI gồm 6 cụm giao diện và hạ tầng:

1. Website public
2. Admin nội bộ / CRM / ERP
3. Student / Guardian portal
4. Company / HR portal
5. Affiliate portal
6. Hạ tầng vận hành / go-live

## 1. Website Public

### Vai trò

Lớp nhận diện thương hiệu, SEO, chuyển đổi lead.

### Pages

- Homepage
- Course listing
- Course detail
- Landing AI căn bản / campaign landing
- Audience pages
- Resource/news pages
- Contact/lead forms
- AI tools
- Case studies
- Prompt library
- Video Academy

### Data and tracking

Lead forms phải lưu được:

- Name
- Phone
- Email
- Audience type
- Course interest
- Message
- UTM source / medium / campaign / term / content
- Referral
- Affiliate code
- Page URL
- Referrer
- Campaign slug
- Course slug

## 2. Admin nội bộ / CRM / ERP

### Vai trò

Command center của DAYAI.

### Core modules

- CRM tuyển sinh
- People / Organizations
- Learning admin
- Finance admin
- Progress admin
- Content admin
- Affiliate admin
- Automation admin
- BI reports
- RBAC / Settings

### Admin shell bắt buộc

- Sidebar theo nhóm module
- Topbar với search, notification, user menu
- PageHeader chuẩn
- Breadcrumbs
- Role-based navigation
- Responsive admin layout

## 3. Student / Guardian Portal

### Vai trò

Không gian học tập và theo dõi tiến độ.

### Core flows

- OTP auth
- Overview dashboard
- Courses currently learning
- Upcoming sessions
- Attendance
- Finance/receivables
- Progress reports
- Teacher comments
- Notifications
- Certificates
- Video lessons
- LMS progress
- Quiz / assessments

## 4. Company / HR Portal

### Vai trò

Cho doanh nghiệp theo dõi nhân sự học AI.

### Core flows

- HR lookup by email + company code
- Company overview
- Employee list
- Employee detail
- Progress by employee
- Assessment results
- Attendance
- Certificates
- B2B finance summary
- HR notifications

## 5. Affiliate Portal

### Vai trò

Cho đối tác theo dõi hiệu quả giới thiệu.

### Core flows

- Partner lookup by code/link
- Campaign links
- Clicks
- Leads
- Related orders
- Commissions
- Commission statuses: pending, approved, paid, rejected

Affiliate portal must be noindex.

## 6. Infrastructure / Go-live

### Stack expectations

- Docker
- Nginx
- PostgreSQL
- Redis
- Health check `/api/health`
- Backend/frontend `.env.production.example`
- Backup/restore/rollback checklist
- Smoke test checklist
- Security review checklist

## Module dependency map

```text
Public website
  -> Lead form
  -> CRM lead
  -> Sales follow-up
  -> Trial registration
  -> Customer/student/company
  -> Enrollment/order

Student portal
  -> Enrollment
  -> Course/module/lesson/session
  -> Attendance
  -> LMS progress
  -> Quiz attempt
  -> Assessment result
  -> Progress report/certificate

HR portal
  -> Company
  -> Employees/students
  -> Enrollments
  -> Progress/attendance/assessment
  -> B2B finance

Affiliate portal
  -> Affiliate partner/link/click
  -> Lead attribution
  -> Order attribution
  -> Commission
```

## Route groups recommendation

```text
/                         public homepage
/khoa-hoc                 public course listing
/khoa-hoc/[slug]          public course detail
/doi-tuong/[slug]         audience page
/tai-nguyen/[slug]        resource detail
/lien-he                  lead/contact

/admin                    admin dashboard
/admin/crm/*              CRM
/admin/learning/*         LMS/admin learning
/admin/finance/*          finance
/admin/progress/*         progress
/admin/content/*          content
/admin/affiliate/*        affiliate
/admin/automation/*       automation
/admin/reports/*          BI reports
/admin/settings/*         RBAC/settings

/portal                   student/guardian overview
/portal/bai-hoc/[slug]    lesson player
/portal/quiz              quiz list
/portal/quiz/[id]         quiz attempt/result

/company-portal           company HR portal
/affiliate-portal         affiliate portal
```

## Architecture principle

Routes can be separate, but product language, tokens, components, status labels, empty states, forms, tables, and interaction patterns must be unified.
