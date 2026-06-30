# 10 — CRM, Finance, Learning Domain Rules

## CRM tuyển sinh

### Lead fields

Every lead should support:

- Customer name
- Phone
- Email
- Customer type
- Company
- Interested course
- Source
- UTM fields
- Affiliate code
- Campaign slug
- Course slug
- Page URL
- Referrer
- Assigned sales
- Pipeline stage
- Lead temperature
- Priority
- Follow-up date/time
- Follow-up status
- Notes

### Pipeline stages

Recommended labels:

- Mới
- Đang liên hệ
- Đang tư vấn
- Đăng ký học thử
- Đã học thử
- Chờ thanh toán
- Đã chuyển đổi
- Không phù hợp
- Mất lead

### Lead temperature

Use Vietnamese labels:

- Nóng
- Ấm
- Lạnh

Avoid showing raw `hot`, `warm`, `cold` unless in code only.

### Lead detail page

Must include:

- Contact summary
- Source attribution
- Sales owner
- Pipeline stage
- Follow-up status
- Consultation history
- Trial registrations
- Related orders/enrollments if any
- Timeline

## Trial registration

List page needs:

- Lead/student/customer
- Desired date
- Time slot
- Course interest
- Status
- Owner
- Actions

Status labels:

- Mới
- Đã xếp lịch
- Đã hoàn thành
- Đã hủy
- Không tham gia

Detail page should include status actions.

## Learning admin

Core entities:

- Courses
- Course modules
- Class groups
- Class sessions
- Teacher profiles
- Enrollments
- Attendance records
- Video lessons
- Materials
- LMS progress

Course management UI should show:

- Course status
- Audience
- Level
- Modules
- Lessons
- Enrollment count
- Revenue if relevant

## Attendance

Attendance statuses:

- Có mặt
- Vắng
- Đi muộn
- Có phép
- Chưa điểm danh

Attendance pages should support bulk update if class/session workflow requires it.

## Progress admin

Core entities:

- Assessments
- Assessment results
- Teacher comments
- Progress reports
- Certificates

Progress report page should combine:

- Attendance
- LMS progress
- Quiz/assessment results
- Teacher comments
- Suggested next step

## Finance admin

Core entities:

- Orders
- Order items
- Invoices
- Payments
- Receivables
- Receipts

Finance rules:

- Money formatting in Vietnamese currency: `8.500.000 ₫`
- Paid/receivable/overdue must be clearly separated
- B2C and B2B customers must be supported
- Never expose private finance data in public pages

Finance status labels:

- Chưa thanh toán
- Thanh toán một phần
- Đã thanh toán
- Quá hạn
- Đã hủy
- Hoàn tiền

## Affiliate

Core entities:

- Affiliate partners
- Affiliate links
- Affiliate clicks
- Affiliate commissions

Commission statuses:

- Chờ duyệt
- Đã duyệt
- Đã thanh toán
- Từ chối

Affiliate attribution must connect:

```text
Affiliate link/click -> Lead -> Order -> Commission
```

## Automation

Core entities:

- Automation workflows
- Messages
- Logs
- Notification outbox

Automation pages should show:

- Health
- Last run
- Success/failure count
- Failed messages
- Retry action if available

Never log secrets/tokens in automation logs.
