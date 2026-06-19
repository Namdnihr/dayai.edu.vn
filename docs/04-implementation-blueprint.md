# DAYAI - Implementation Blueprint

Tài liệu này khóa cách triển khai MVP từ góc nhìn kỹ thuật: cấu trúc app, module backend, menu Filament admin, route website và demo flow. Khi bắt đầu code, ưu tiên bám tài liệu này để tránh phát sinh kiến trúc tùy hứng.

## 1. Kiến Trúc Tổng Thể

```text
dayai.edu.vn/
  backend/     Laravel API + Filament Admin + Queue workers
  frontend/    Next.js public website + landing pages + future portals
  infra/       Docker, Nginx, PostgreSQL, Redis
  docs/        PM, schema, backlog, blueprint
```

## 2. Nguyên Tắc Triển Khai

- Modular monolith trong Laravel, không tách microservice ở MVP.
- Mỗi module có model, policy, service/action, Filament resource riêng.
- Không để toàn bộ logic trong Controller hoặc Filament Resource.
- Những thao tác nghiệp vụ quan trọng đi qua service/action.
- Giao diện admin dùng Filament trước, public website dùng Next.js.
- API cho public form phải nhỏ, rõ, validate mạnh.
- Queue dùng cho việc nặng hoặc side effect: email, thông báo, chatbot, báo cáo, import.
- Dữ liệu tài chính không xóa cứng.
- Mọi luồng phải giữ đúng nguyên tắc: người mua khác người học.

## 3. Backend Laravel Module Boundary

### 3.1 `Core`

Phụ trách nền tảng chung.

| Thành phần | Nội dung |
|---|---|
| Models | `Tenant`, `Branch`, `ActivityLog` |
| Services | audit logger, tenant resolver, branch resolver |
| Policies | tenant/branch access |
| Filament | Tenant/Branch config nếu cần |
| Events | `AuditLogCreated` |

Không đưa nghiệp vụ CRM/học phí/lớp học vào `Core`.

### 3.2 `Identity`

Phụ trách người dùng, cá nhân, phân quyền.

| Thành phần | Nội dung |
|---|---|
| Models | `User`, `Person` |
| Package | Spatie Permission nếu dùng Laravel |
| Services | user invitation, profile sync |
| Filament | Users, People, Roles |
| Policies | role-based menu/data access |

`Person` không đại diện riêng cho học viên/phụ huynh/HR; các vai trò này nằm ở profile/relation khác.

### 3.3 `Organization`

Phụ trách doanh nghiệp, trường, đối tác và liên hệ công ty.

| Thành phần | Nội dung |
|---|---|
| Models | `Organization`, `OrganizationContact` |
| Services | create company lead conversion, attach HR/contact |
| Filament | Organizations, Organization Contacts |
| Events | `OrganizationCreated`, `OrganizationContactAttached` |

### 3.4 `CRM`

Phụ trách lead, nguồn khách, tư vấn, học thử.

| Thành phần | Nội dung |
|---|---|
| Models | `LeadSource`, `Campaign`, `Lead`, `LeadAssignment`, `ConsultationActivity`, `TrialRegistration` |
| Services | lead intake, lead assignment, lead conversion, follow-up scheduling |
| Filament | Leads, Lead Sources, Campaigns, Trial Registrations |
| API | public lead capture endpoint |
| Events | `LeadCreated`, `LeadAssigned`, `LeadStatusChanged`, `LeadConverted` |

CRM không tự tạo đơn hàng nếu chưa qua conversion/order flow.

### 3.5 `Customer`

Phụ trách hồ sơ học viên, phụ huynh, customer account.

| Thành phần | Nội dung |
|---|---|
| Models | `StudentProfile`, `GuardianRelation`, `CustomerAccount` |
| Services | create student, attach guardian, create buyer account |
| Filament | Students, Guardians, Customer Accounts |
| Events | `StudentCreated`, `GuardianLinked`, `CustomerAccountCreated` |

`CustomerAccount` là điểm bắt buộc trước khi tạo `Order`.

### 3.6 `Learning`

Phụ trách khóa học, lớp học, lịch học, enrollment.

| Thành phần | Nội dung |
|---|---|
| Models | `TeacherProfile`, `Course`, `CourseModule`, `ClassGroup`, `ClassSession`, `Enrollment` |
| Services | course publishing, class scheduling, enroll student |
| Filament | Courses, Class Groups, Class Sessions, Enrollments, Teachers |
| Events | `CoursePublished`, `ClassCreated`, `StudentEnrolled` |

Không xử lý thu tiền trong `Learning`; chỉ liên kết `Enrollment` với `Order` nếu có.

### 3.7 `Attendance`

Phụ trách điểm danh, nghỉ học, học bù.

| Thành phần | Nội dung |
|---|---|
| Models | `AttendanceRecord`, `MakeupSession` |
| Services | take attendance, schedule makeup |
| Filament | Attendance, Makeup Sessions |
| Events | `AttendanceRecorded`, `MakeupScheduled` |

Điểm danh phải validate học viên thuộc lớp hoặc có lịch học bù hợp lệ.

### 3.8 `Finance`

Phụ trách đơn hàng, học phí, hóa đơn, thanh toán, công nợ, phiếu thu.

| Thành phần | Nội dung |
|---|---|
| Models | `TrainingContract`, `Order`, `OrderItem`, `Invoice`, `Payment`, `Receivable`, `Receipt` |
| Services | create order, issue invoice, record payment, update debt, issue receipt |
| Filament | Orders, Invoices, Payments, Receivables, Receipts, Contracts |
| Events | `OrderConfirmed`, `InvoiceIssued`, `PaymentRecorded`, `ReceiptIssued` |

Tài chính là module cần audit log kỹ nhất.

### 3.9 `CMS`

Phụ trách nội dung public website.

| Thành phần | Nội dung |
|---|---|
| Models | `CmsPage`, `NewsPost`, `MediaAsset` |
| Services | publish content |
| Filament | Pages, News Posts, Media Library |
| API | course/page/news read endpoints cho frontend |

P0 chỉ cần CMS tối thiểu, không làm page builder phức tạp.

### 3.10 `Reporting`

Phụ trách dashboard cơ bản.

| Thành phần | Nội dung |
|---|---|
| Queries | lead counts, follow-up due, active students, active classes, monthly revenue, open receivables |
| Filament | Dashboard widgets |
| Services | lightweight report queries |

P0 không làm BI phức tạp, không tạo reporting database.

## 4. Laravel Folder Gợi Ý

```text
backend/app/
  Domain/
    Core/
    Identity/
    Organization/
    CRM/
    Customer/
    Learning/
    Attendance/
    Finance/
    CMS/
    Reporting/
  Filament/
    Resources/
    Pages/
    Widgets/
  Http/
    Controllers/
      Api/
  Models/
```

Có thể chọn một trong hai cách:

- **Cách nhanh:** để Models trong `app/Models`, Services trong `app/Services`.
- **Cách sạch hơn:** chia `app/Domain/<Module>`.

Khuyến nghị: dùng `app/Domain/<Module>` ngay từ đầu để module boundary rõ, nhưng vẫn giữ Laravel conventions đủ dễ hiểu.

## 5. Filament Admin Menu MVP

### 5.1 Dashboard

- Dashboard tổng quan
- Lead cần follow-up
- Doanh thu tháng
- Công nợ mở
- Lớp đang học

### 5.2 CRM Tuyển Sinh

- Leads
- Lead Sources
- Campaigns
- Trial Registrations
- Consultation Activities

### 5.3 Khách Hàng

- People
- Students
- Guardians
- Organizations
- Organization Contacts
- Customer Accounts

### 5.4 Đào Tạo

- Courses
- Course Modules
- Teachers
- Class Groups
- Class Sessions
- Enrollments

### 5.5 Điểm Danh

- Attendance
- Makeup Sessions

### 5.6 Tài Chính

- Orders
- Invoices
- Payments
- Receivables
- Receipts
- Training Contracts

### 5.7 Website / CMS

- Pages
- News Posts
- Media Library

### 5.8 Hệ Thống

- Users
- Roles
- Branches
- Activity Logs
- Settings

## 6. Ma Trận Menu Theo Vai Trò

| Menu | Admin | Sales | Teacher | Accountant |
|---|---:|---:|---:|---:|
| Dashboard | yes | yes | yes | yes |
| CRM Tuyển Sinh | yes | yes | no | read |
| Khách Hàng | yes | yes | read | read |
| Đào Tạo | yes | read | yes | read |
| Điểm Danh | yes | no | yes | no |
| Tài Chính | yes | limited | no | yes |
| Website / CMS | yes | no | no | no |
| Hệ Thống | yes | no | no | no |

`limited` nghĩa là Sales xem được trạng thái thanh toán/công nợ liên quan đến khách của mình, nhưng không sửa payment/receipt.

## 7. Public Website / Next.js Routes P0

| Route | Mục tiêu | Dữ liệu/API |
|---|---|---|
| `/` | Trang chủ | courses published, cms home |
| `/gioi-thieu` | Giới thiệu trung tâm | cms page |
| `/khoa-hoc` | Danh sách khóa học | courses |
| `/khoa-hoc/[slug]` | Landing page khóa học | course detail |
| `/tin-tuc` | Danh sách tin tức | news posts |
| `/tin-tuc/[slug]` | Chi tiết tin tức | news detail |
| `/thu-vien` | Ảnh/video | media assets public |
| `/lien-he` | Liên hệ | cms contact + form |
| `/dang-ky-tu-van` | Form tư vấn | submit lead |
| `/dang-ky-hoc-thu` | Form học thử | submit lead + trial |

## 8. API Endpoints P0

### Public API

| Method | Endpoint | Mục tiêu |
|---|---|---|
| `GET` | `/api/public/courses` | Danh sách khóa học published |
| `GET` | `/api/public/courses/{slug}` | Chi tiết khóa học |
| `GET` | `/api/public/pages/{slug}` | Trang CMS |
| `GET` | `/api/public/news` | Tin tức |
| `POST` | `/api/public/leads/consultation` | Form đăng ký tư vấn |
| `POST` | `/api/public/leads/trial` | Form đăng ký học thử |

### Admin API

P0 ưu tiên thao tác qua Filament Resource. Chỉ tạo API admin riêng khi frontend portal cần.

## 9. Service/Action Cần Có Đầu Tiên

| Action | Module | Mục tiêu |
|---|---|---|
| `CreateLeadAction` | CRM | Tạo lead từ admin/public form |
| `AssignLeadAction` | CRM | Phân công tư vấn viên |
| `RecordConsultationActivityAction` | CRM | Ghi lịch sử tư vấn |
| `ConvertLeadAction` | CRM/Customer | Chuyển lead thành hồ sơ thật |
| `CreateStudentProfileAction` | Customer | Tạo hồ sơ học viên |
| `LinkGuardianAction` | Customer | Gắn phụ huynh với con |
| `CreateCustomerAccountAction` | Customer/Finance | Tạo người mua |
| `CreateOrderAction` | Finance | Tạo đơn mua khóa |
| `IssueInvoiceAction` | Finance | Tạo khoản phải thu |
| `RecordPaymentAction` | Finance | Ghi thanh toán và cập nhật công nợ |
| `IssueReceiptAction` | Finance | Tạo phiếu thu |
| `EnrollStudentAction` | Learning | Xếp học viên vào khóa/lớp |
| `TakeAttendanceAction` | Attendance | Điểm danh |

## 10. Events Nghiệp Vụ P0

| Event | Khi nào phát |
|---|---|
| `LeadCreated` | Lead được tạo từ form/admin |
| `LeadAssigned` | Lead được phân công |
| `LeadStatusChanged` | Lead đổi trạng thái |
| `LeadConverted` | Lead chuyển thành hồ sơ thật |
| `StudentCreated` | Tạo hồ sơ học viên |
| `CustomerAccountCreated` | Tạo người mua |
| `OrderConfirmed` | Đơn hàng được xác nhận |
| `InvoiceIssued` | Tạo khoản phải thu |
| `PaymentRecorded` | Thanh toán hoàn tất |
| `ReceiptIssued` | Phiếu thu được tạo |
| `StudentEnrolled` | Học viên được xếp lớp |
| `AttendanceRecorded` | Điểm danh được ghi |

P0 listener tối thiểu:

- [ ] Ghi audit log.
- [ ] Cập nhật denormalized totals tài chính.
- [ ] Cập nhật trạng thái liên quan.

## 11. Demo Flow Đầu Tiên Phải Chạy Được

### 11.1 Flow Phụ Huynh

- [ ] Public form tạo lead loại `parent`.
- [ ] Sales ghi tư vấn và đổi trạng thái.
- [ ] Convert lead thành person phụ huynh + student profile con.
- [ ] Tạo customer account cá nhân cho phụ huynh.
- [ ] Tạo order mua khóa cho con.
- [ ] Tạo invoice/receivable.
- [ ] Enroll con vào lớp.
- [ ] Điểm danh một buổi.
- [ ] Ghi payment.
- [ ] Tạo receipt.

### 11.2 Flow Sinh Viên

- [ ] Public/admin tạo lead loại `student`.
- [ ] Convert thành person + student profile.
- [ ] Person đó cũng là customer account.
- [ ] Tạo order/enrollment/payment.

### 11.3 Flow Công Ty

- [ ] Public/admin tạo lead loại `company`.
- [ ] Convert thành organization + HR contact.
- [ ] Tạo customer account organization.
- [ ] Tạo student profiles cho nhân sự.
- [ ] Tạo order B2B.
- [ ] Enroll nhân sự vào lớp.
- [ ] Theo dõi payment/receivable công ty.

## 12. Sprint 1 Build Order

- [ ] Scaffold Laravel backend.
- [ ] Cấu hình Docker/PostgreSQL/Redis.
- [ ] Cài Filament.
- [ ] Cài auth admin.
- [ ] Cài roles/permissions.
- [ ] Tạo migrations core: tenants, branches, people, users.
- [ ] Tạo organizations và organization_contacts.
- [ ] Tạo activity_logs.
- [ ] Tạo Filament Resources: Users, People, Organizations, Branches, Activity Logs.
- [ ] Tạo seed data: tenant DAYAI, branch mặc định, admin user, roles.
- [ ] Demo: admin đăng nhập, tạo person, tạo organization, xem audit log.

## 13. Những Việc Không Làm Trong Sprint 1

- [ ] Không làm website Next.js.
- [ ] Không làm chatbot AI.
- [ ] Không làm portal phụ huynh/học viên.
- [ ] Không làm báo cáo tiến bộ nâng cao.
- [ ] Không làm payment gateway thật.
- [ ] Không làm notification Zalo/SMS.
- [ ] Không tách microservice.

## 14. Checklist Trước Khi Scaffold Code

- [ ] Đã đọc `docs/00-pm-master-checklist.md`.
- [ ] Đã đọc `docs/02-database-schema-p0.md`.
- [ ] Đã đọc `docs/03-mvp-user-stories.md`.
- [ ] Đã xác nhận dùng Laravel + Filament + PostgreSQL + Redis.
- [ ] Đã xác nhận repo structure `backend/`, `frontend/`, `infra/`.
- [ ] Đã xác nhận Sprint 1 chỉ làm backend core/admin base.
