# DAYAI - MVP User Stories

Tài liệu này tách MVP P0 thành các user stories có thể đưa vào sprint/code. Mỗi story phải bám `docs/00-pm-master-checklist.md` và schema tại `docs/02-database-schema-p0.md`.

## 1. Quy Ước Ưu Tiên

| Mức | Ý nghĩa |
|---|---|
| `P0-Must` | Bắt buộc có để demo end-to-end MVP |
| `P0-Should` | Nên có trong MVP, có thể làm sau `P0-Must` |
| `P1-Later` | Không làm ở MVP đầu, để sau khi vận hành được |

## 2. Epic Tổng

| Epic | Mục tiêu |
|---|---|
| `E01-Core` | Đăng nhập, phân quyền, tenant/branch, audit log |
| `E02-CRM` | Quản lý lead, nguồn khách, tư vấn, học thử |
| `E03-Customer` | Quản lý cá nhân, học viên, phụ huynh, công ty, HR |
| `E04-Learning` | Quản lý khóa học, lớp học, lịch học, enrollment |
| `E05-Attendance` | Điểm danh, nghỉ học, học bù |
| `E06-Finance` | Đơn hàng, học phí, thanh toán, công nợ, phiếu thu |
| `E07-PublicWeb` | Website, landing page, form đẩy lead về CRM |
| `E08-Reporting` | Dashboard cơ bản cho admin |

## 3. E01-Core - Nền Tảng Admin

### US-E01-001 - Admin đăng nhập hệ thống

- **Ưu tiên:** `P0-Must`
- **Vai trò:** Admin, nhân viên nội bộ
- **User story:** Là nhân viên nội bộ, tôi muốn đăng nhập vào admin để thao tác các module được phân quyền.
- **Dữ liệu chính:** `users`, `people`, roles/permissions.
- **Tiêu chí nghiệm thu:**
  - [ ] User đăng nhập bằng email/password.
  - [ ] User inactive/locked không đăng nhập được.
  - [ ] Sau đăng nhập vào được Filament admin.
  - [ ] Hiển thị tên user đang đăng nhập.

### US-E01-002 - Quản lý vai trò cơ bản

- **Ưu tiên:** `P0-Must`
- **Vai trò:** Super Admin
- **User story:** Là Super Admin, tôi muốn gán vai trò cho user để giới hạn quyền theo nhiệm vụ.
- **Vai trò MVP:** `admin`, `sales`, `teacher`, `accountant`.
- **Tiêu chí nghiệm thu:**
  - [ ] Admin thấy toàn bộ menu P0.
  - [ ] Sales thấy CRM, khách hàng, khóa học cơ bản.
  - [ ] Teacher thấy lớp học, lịch học, điểm danh.
  - [ ] Accountant thấy đơn hàng, thanh toán, công nợ, phiếu thu.
  - [ ] User không có quyền không truy cập được URL resource bị cấm.

### US-E01-003 - Ghi audit log thao tác quan trọng

- **Ưu tiên:** `P0-Should`
- **Vai trò:** Admin
- **User story:** Là Admin, tôi muốn xem lịch sử thao tác quan trọng để truy vết sai sót.
- **Dữ liệu chính:** `activity_logs`.
- **Tiêu chí nghiệm thu:**
  - [ ] Ghi log khi tạo/sửa lead.
  - [ ] Ghi log khi đổi trạng thái lead.
  - [ ] Ghi log khi tạo payment.
  - [ ] Ghi log khi sửa attendance.
  - [ ] Log có user, action, subject, thời gian.

## 4. E02-CRM - Tuyển Sinh

### US-E02-001 - Tạo lead thủ công

- **Ưu tiên:** `P0-Must`
- **Vai trò:** Sales
- **User story:** Là tư vấn viên, tôi muốn tạo lead thủ công để lưu khách từ điện thoại/Zalo/Facebook.
- **Dữ liệu chính:** `leads`, `lead_sources`.
- **Tiêu chí nghiệm thu:**
  - [ ] Nhập được họ tên, số điện thoại, email, loại lead, nguồn lead.
  - [ ] Chọn được khóa học quan tâm nếu có.
  - [ ] Chọn được loại lead: phụ huynh, sinh viên, chủ doanh nghiệp, công ty.
  - [ ] Lead mới mặc định trạng thái `new`.
  - [ ] Không cho lưu nếu thiếu họ tên và thiếu cả phone/email.

### US-E02-002 - Quản lý danh sách lead

- **Ưu tiên:** `P0-Must`
- **Vai trò:** Sales, Admin
- **User story:** Là tư vấn viên, tôi muốn lọc và xem danh sách lead để biết khách nào cần xử lý.
- **Tiêu chí nghiệm thu:**
  - [ ] Lọc theo trạng thái.
  - [ ] Lọc theo loại lead.
  - [ ] Lọc theo nguồn lead.
  - [ ] Lọc theo tư vấn viên phụ trách.
  - [ ] Tìm kiếm theo tên, phone, email, công ty.
  - [ ] Hiển thị cảnh báo lead có `next_follow_up_at` đã quá hạn.

### US-E02-003 - Phân công tư vấn viên

- **Ưu tiên:** `P0-Must`
- **Vai trò:** Admin, Sales Manager
- **User story:** Là quản lý tuyển sinh, tôi muốn phân công lead cho tư vấn viên để rõ người chịu trách nhiệm.
- **Dữ liệu chính:** `leads.assigned_user_id`, `lead_assignments`.
- **Tiêu chí nghiệm thu:**
  - [ ] Gán tư vấn viên cho một lead.
  - [ ] Đổi tư vấn viên phụ trách.
  - [ ] Lưu lịch sử phân công.
  - [ ] Lead hiển thị tư vấn viên hiện tại.

### US-E02-004 - Ghi lịch sử tư vấn

- **Ưu tiên:** `P0-Must`
- **Vai trò:** Sales
- **User story:** Là tư vấn viên, tôi muốn ghi lại lịch sử gọi điện/nhắn tin để người khác nắm được quá trình chăm sóc.
- **Dữ liệu chính:** `consultation_activities`.
- **Tiêu chí nghiệm thu:**
  - [ ] Thêm activity vào lead.
  - [ ] Chọn loại activity: call, Zalo, email, meeting, note.
  - [ ] Ghi nội dung tư vấn.
  - [ ] Ghi kết quả tư vấn.
  - [ ] Đặt lịch follow-up tiếp theo.
  - [ ] Lead cập nhật `last_contacted_at` và `next_follow_up_at`.

### US-E02-005 - Đổi trạng thái lead

- **Ưu tiên:** `P0-Must`
- **Vai trò:** Sales
- **User story:** Là tư vấn viên, tôi muốn đổi trạng thái lead để pipeline tuyển sinh luôn rõ ràng.
- **Tiêu chí nghiệm thu:**
  - [ ] Đổi được trạng thái: mới, đang liên hệ, đang tư vấn, hẹn học thử, đã đăng ký, không phù hợp, mất liên hệ.
  - [ ] Nếu chọn `lost` hoặc `not_fit`, bắt buộc nhập lý do.
  - [ ] Nếu chọn `registered`, hệ thống yêu cầu chuyển đổi lead sang hồ sơ khách/học viên hoặc liên kết hồ sơ có sẵn.

### US-E02-006 - Đăng ký học thử

- **Ưu tiên:** `P0-Should`
- **Vai trò:** Sales
- **User story:** Là tư vấn viên, tôi muốn tạo lịch học thử cho lead để tăng tỷ lệ chuyển đổi.
- **Dữ liệu chính:** `trial_registrations`.
- **Tiêu chí nghiệm thu:**
  - [ ] Tạo được yêu cầu học thử từ lead.
  - [ ] Chọn khóa học quan tâm.
  - [ ] Nhập ngày/khung giờ mong muốn.
  - [ ] Cập nhật trạng thái học thử: requested, scheduled, attended, no_show, cancelled, converted.
  - [ ] Lead có trạng thái `trial_scheduled` khi học thử đã được xếp lịch.

## 5. E03-Customer - Khách Hàng, Học Viên, Phụ Huynh, Công Ty

### US-E03-001 - Tạo hồ sơ cá nhân

- **Ưu tiên:** `P0-Must`
- **Vai trò:** Admin, Sales
- **User story:** Là nhân viên, tôi muốn tạo hồ sơ cá nhân dùng chung để không nhập trùng phụ huynh/học viên/HR/giáo viên.
- **Dữ liệu chính:** `people`.
- **Tiêu chí nghiệm thu:**
  - [ ] Tạo/sửa/xem được cá nhân.
  - [ ] Nhập họ tên, phone, email, ngày sinh, địa chỉ, ghi chú.
  - [ ] Tìm kiếm theo tên, phone, email.
  - [ ] Khi tạo trùng phone/email, hệ thống cảnh báo bản ghi có thể trùng.

### US-E03-002 - Tạo hồ sơ học viên

- **Ưu tiên:** `P0-Must`
- **Vai trò:** Admin, Sales
- **User story:** Là nhân viên, tôi muốn tạo hồ sơ người học thực tế để xếp lớp và theo dõi quá trình học.
- **Dữ liệu chính:** `people`, `student_profiles`.
- **Tiêu chí nghiệm thu:**
  - [ ] Tạo student profile từ một person có sẵn.
  - [ ] Chọn loại học viên: child, university_student, working_professional, business_owner, company_employee.
  - [ ] Có mã học viên tự sinh.
  - [ ] Nhập mục tiêu học và trình độ đầu vào.
  - [ ] Gắn organization nếu là nhân sự công ty.

### US-E03-003 - Tạo quan hệ phụ huynh - con

- **Ưu tiên:** `P0-Must`
- **Vai trò:** Sales, Admin
- **User story:** Là nhân viên, tôi muốn liên kết phụ huynh với học viên để phụ huynh mua và theo dõi việc học của con.
- **Dữ liệu chính:** `guardian_relations`.
- **Tiêu chí nghiệm thu:**
  - [ ] Chọn được person phụ huynh.
  - [ ] Chọn được student profile của con.
  - [ ] Chọn quan hệ: cha, mẹ, người giám hộ, người thân.
  - [ ] Đánh dấu phụ huynh chính.
  - [ ] Không tạo trùng cùng một quan hệ phụ huynh - học viên.

### US-E03-004 - Tạo hồ sơ doanh nghiệp

- **Ưu tiên:** `P0-Must`
- **Vai trò:** Sales, Admin
- **User story:** Là nhân viên, tôi muốn tạo hồ sơ doanh nghiệp để quản lý khách B2B mua khóa cho nhân sự.
- **Dữ liệu chính:** `organizations`.
- **Tiêu chí nghiệm thu:**
  - [ ] Tạo/sửa/xem doanh nghiệp.
  - [ ] Nhập tên công ty, mã số thuế, ngành, quy mô, thông tin liên hệ.
  - [ ] Tìm kiếm theo tên công ty hoặc mã số thuế.
  - [ ] Trạng thái doanh nghiệp: prospect, active, inactive.

### US-E03-005 - Quản lý HR/liên hệ công ty

- **Ưu tiên:** `P0-Must`
- **Vai trò:** Sales, Admin
- **User story:** Là nhân viên, tôi muốn gắn HR/người đại diện vào công ty để biết ai là người làm việc và thanh toán.
- **Dữ liệu chính:** `organization_contacts`.
- **Tiêu chí nghiệm thu:**
  - [ ] Gắn một person làm HR/liên hệ công ty.
  - [ ] Chọn vai trò liên hệ: HR, decision maker, training manager, finance contact.
  - [ ] Đánh dấu liên hệ chính.
  - [ ] Một công ty có thể có nhiều liên hệ.

### US-E03-006 - Chuyển lead thành khách hàng/học viên/công ty

- **Ưu tiên:** `P0-Must`
- **Vai trò:** Sales
- **User story:** Là tư vấn viên, tôi muốn chuyển lead đã chốt thành hồ sơ thật để tiếp tục đăng ký học.
- **Dữ liệu chính:** `leads`, `people`, `student_profiles`, `organizations`, `customer_accounts`.
- **Tiêu chí nghiệm thu:**
  - [ ] Lead phụ huynh tạo được person phụ huynh và student profile cho con.
  - [ ] Lead sinh viên tạo được person và student profile cùng một cá nhân.
  - [ ] Lead chủ doanh nghiệp tạo được person, student profile, có thể gắn organization.
  - [ ] Lead công ty tạo được organization, HR contact, customer account tổ chức.
  - [ ] Lead chuyển trạng thái `registered` hoặc trạng thái chuyển đổi phù hợp.

## 6. E04-Learning - Khóa Học, Lớp Học, Enrollment

### US-E04-001 - Quản lý khóa học

- **Ưu tiên:** `P0-Must`
- **Vai trò:** Admin
- **User story:** Là Admin, tôi muốn tạo khóa học để website, CRM và lớp học dùng chung dữ liệu.
- **Dữ liệu chính:** `courses`.
- **Tiêu chí nghiệm thu:**
  - [ ] Tạo/sửa/xem khóa học.
  - [ ] Nhập tên, slug, mã khóa, đối tượng, level, mô tả, giá mặc định.
  - [ ] Trạng thái draft/published/archived.
  - [ ] Khóa published có thể hiển thị trên website.

### US-E04-002 - Quản lý module học

- **Ưu tiên:** `P0-Should`
- **Vai trò:** Admin
- **User story:** Là Admin, tôi muốn định nghĩa module/buổi học trong khóa để lớp học có khung chương trình.
- **Dữ liệu chính:** `course_modules`.
- **Tiêu chí nghiệm thu:**
  - [ ] Thêm nhiều module cho một khóa.
  - [ ] Sắp xếp module theo thứ tự.
  - [ ] Nhập mục tiêu học cho từng module.

### US-E04-003 - Tạo lớp học

- **Ưu tiên:** `P0-Must`
- **Vai trò:** Admin
- **User story:** Là Admin, tôi muốn tạo lớp học cụ thể từ khóa học để xếp học viên và lịch học.
- **Dữ liệu chính:** `class_groups`.
- **Tiêu chí nghiệm thu:**
  - [ ] Chọn khóa học.
  - [ ] Nhập mã lớp, tên lớp, hình thức học, ngày bắt đầu/kết thúc.
  - [ ] Gán giáo viên chính.
  - [ ] Chọn organization nếu lớp riêng cho công ty.
  - [ ] Trạng thái lớp: planned, enrolling, active, completed, paused, cancelled.

### US-E04-004 - Tạo buổi học

- **Ưu tiên:** `P0-Must`
- **Vai trò:** Admin, Teacher
- **User story:** Là Admin/giáo viên, tôi muốn tạo các buổi học của lớp để điểm danh và quản lý lịch.
- **Dữ liệu chính:** `class_sessions`.
- **Tiêu chí nghiệm thu:**
  - [ ] Tạo buổi học cho lớp.
  - [ ] Nhập thời gian bắt đầu/kết thúc.
  - [ ] Gắn module học nếu có.
  - [ ] Gán giáo viên cho buổi.
  - [ ] Không cho tạo trùng `session_no` trong cùng lớp.

### US-E04-005 - Đăng ký học viên vào khóa/lớp

- **Ưu tiên:** `P0-Must`
- **Vai trò:** Sales, Admin
- **User story:** Là nhân viên, tôi muốn đăng ký học viên vào khóa/lớp sau khi chốt đơn.
- **Dữ liệu chính:** `enrollments`, `student_profiles`, `class_groups`, `orders`.
- **Tiêu chí nghiệm thu:**
  - [ ] Chọn học viên.
  - [ ] Chọn khóa học.
  - [ ] Chọn lớp nếu đã có lớp.
  - [ ] Gắn order nếu đăng ký đã có đơn hàng.
  - [ ] Trạng thái enrollment mặc định `active` hoặc `pending`.
  - [ ] Một học viên có thể học nhiều khóa/lớp khác nhau.

## 7. E05-Attendance - Điểm Danh & Học Bù

### US-E05-001 - Điểm danh buổi học

- **Ưu tiên:** `P0-Must`
- **Vai trò:** Teacher, Admin
- **User story:** Là giáo viên, tôi muốn điểm danh học viên theo từng buổi để trung tâm theo dõi quá trình học.
- **Dữ liệu chính:** `attendance_records`.
- **Tiêu chí nghiệm thu:**
  - [ ] Mở buổi học thấy danh sách học viên trong lớp.
  - [ ] Chọn trạng thái: có mặt, vắng, đi muộn, xin nghỉ.
  - [ ] Nhập phút đi muộn nếu trạng thái là đi muộn.
  - [ ] Nhập lý do nếu vắng/xin nghỉ.
  - [ ] Không tạo trùng điểm danh cho cùng học viên trong cùng buổi.

### US-E05-002 - Xem lịch sử điểm danh học viên

- **Ưu tiên:** `P0-Must`
- **Vai trò:** Admin, Teacher, Sales
- **User story:** Là nhân viên, tôi muốn xem lịch sử điểm danh của học viên để tư vấn/chăm sóc và báo cáo.
- **Tiêu chí nghiệm thu:**
  - [ ] Trong chi tiết học viên thấy danh sách điểm danh.
  - [ ] Lọc theo lớp/khóa.
  - [ ] Hiển thị số buổi có mặt, vắng, đi muộn.

### US-E05-003 - Tạo lịch học bù thủ công

- **Ưu tiên:** `P0-Should`
- **Vai trò:** Admin, Teacher
- **User story:** Là Admin/giáo viên, tôi muốn tạo học bù cho học viên vắng để xử lý vận hành thực tế.
- **Dữ liệu chính:** `makeup_sessions`.
- **Tiêu chí nghiệm thu:**
  - [ ] Chọn buổi học vắng gốc.
  - [ ] Chọn buổi học bù hoặc ghi lịch học bù.
  - [ ] Cập nhật trạng thái requested/scheduled/completed/cancelled.

## 8. E06-Finance - Học Phí & Công Nợ

### US-E06-001 - Tạo customer account cá nhân

- **Ưu tiên:** `P0-Must`
- **Vai trò:** Sales, Accountant
- **User story:** Là nhân viên, tôi muốn tạo người mua là cá nhân để thu học phí cho phụ huynh/sinh viên/chủ doanh nghiệp.
- **Dữ liệu chính:** `customer_accounts`.
- **Tiêu chí nghiệm thu:**
  - [ ] Chọn person làm người mua.
  - [ ] Account type là `individual`.
  - [ ] Có mã customer account tự sinh.
  - [ ] Snapshot billing name/phone/email.

### US-E06-002 - Tạo customer account doanh nghiệp

- **Ưu tiên:** `P0-Must`
- **Vai trò:** Sales, Accountant
- **User story:** Là nhân viên, tôi muốn tạo người mua là doanh nghiệp để xử lý B2B.
- **Dữ liệu chính:** `customer_accounts`, `organizations`.
- **Tiêu chí nghiệm thu:**
  - [ ] Chọn organization làm người mua.
  - [ ] Account type là `organization`.
  - [ ] Có thông tin xuất hóa đơn/công nợ.

### US-E06-003 - Tạo đơn đăng ký khóa học

- **Ưu tiên:** `P0-Must`
- **Vai trò:** Sales, Accountant
- **User story:** Là nhân viên, tôi muốn tạo đơn mua khóa học để ghi nhận ai mua, mua cho ai và số tiền phải thu.
- **Dữ liệu chính:** `orders`, `order_items`, `customer_accounts`, `student_profiles`, `courses`.
- **Tiêu chí nghiệm thu:**
  - [ ] Chọn customer account là người mua.
  - [ ] Thêm một hoặc nhiều khóa học vào đơn.
  - [ ] Gắn student profile ở từng dòng nếu mua cho người học cụ thể.
  - [ ] Tính subtotal, discount, total.
  - [ ] Hỗ trợ case người mua khác người học.
  - [ ] Tạo enrollment từ order hoặc liên kết order với enrollment.

### US-E06-004 - Tạo invoice/khoản phải thu

- **Ưu tiên:** `P0-Must`
- **Vai trò:** Accountant
- **User story:** Là kế toán, tôi muốn tạo khoản phải thu từ đơn hàng để theo dõi hạn thanh toán.
- **Dữ liệu chính:** `invoices`, `receivables`.
- **Tiêu chí nghiệm thu:**
  - [ ] Tạo invoice từ order.
  - [ ] Có số tiền phải thu, hạn thanh toán.
  - [ ] Tự tạo receivable tương ứng.
  - [ ] Invoice ban đầu có trạng thái `issued` hoặc `draft`.

### US-E06-005 - Ghi nhận thanh toán

- **Ưu tiên:** `P0-Must`
- **Vai trò:** Accountant
- **User story:** Là kế toán, tôi muốn ghi nhận thanh toán để cập nhật công nợ và phiếu thu.
- **Dữ liệu chính:** `payments`, `invoices`, `orders`, `receivables`.
- **Tiêu chí nghiệm thu:**
  - [ ] Chọn invoice cần thanh toán.
  - [ ] Nhập số tiền, phương thức, ngày thanh toán.
  - [ ] Payment completed cập nhật paid/balance của invoice/order/receivable.
  - [ ] Hỗ trợ thanh toán một phần.
  - [ ] Không cho số tiền thanh toán vượt quá số dư nếu không có quyền đặc biệt.

### US-E06-006 - Tạo phiếu thu

- **Ưu tiên:** `P0-Must`
- **Vai trò:** Accountant
- **User story:** Là kế toán, tôi muốn tạo phiếu thu từ payment để in hoặc gửi cho khách.
- **Dữ liệu chính:** `receipts`.
- **Tiêu chí nghiệm thu:**
  - [ ] Mỗi payment completed có thể tạo một receipt.
  - [ ] Receipt có mã phiếu thu.
  - [ ] Receipt lưu payer name, amount, content, issued_at.
  - [ ] Không tạo trùng receipt cho cùng payment.

### US-E06-007 - Xem danh sách công nợ

- **Ưu tiên:** `P0-Must`
- **Vai trò:** Accountant, Admin, Sales
- **User story:** Là nhân viên, tôi muốn xem công nợ còn lại để nhắc thanh toán đúng hạn.
- **Dữ liệu chính:** `receivables`.
- **Tiêu chí nghiệm thu:**
  - [ ] Lọc công nợ theo trạng thái open/overdue/paid.
  - [ ] Lọc theo khách hàng cá nhân/doanh nghiệp.
  - [ ] Lọc theo hạn thanh toán.
  - [ ] Hiển thị số tiền gốc, đã thu, còn lại.

## 9. E07-PublicWeb - Website & Lead Capture

### US-E07-001 - Website trang chủ

- **Ưu tiên:** `P0-Must`
- **Vai trò:** Khách truy cập
- **User story:** Là khách truy cập, tôi muốn xem trang chủ để hiểu trung tâm đào tạo AI cung cấp gì.
- **Dữ liệu chính:** `cms_pages`, `courses`.
- **Tiêu chí nghiệm thu:**
  - [ ] Trang chủ có giới thiệu trung tâm.
  - [ ] Có khu vực khóa học nổi bật.
  - [ ] Có CTA đăng ký tư vấn/học thử.
  - [ ] Responsive trên mobile.

### US-E07-002 - Danh sách và chi tiết khóa học

- **Ưu tiên:** `P0-Must`
- **Vai trò:** Khách truy cập
- **User story:** Là khách truy cập, tôi muốn xem khóa học phù hợp để quyết định đăng ký tư vấn.
- **Dữ liệu chính:** `courses`.
- **Tiêu chí nghiệm thu:**
  - [ ] Trang danh sách chỉ hiển thị khóa `published`.
  - [ ] Chi tiết khóa có tên, mô tả, đối tượng, level, thời lượng, kết quả đầu ra.
  - [ ] Có CTA đăng ký tư vấn/học thử.

### US-E07-003 - Form đăng ký tư vấn tạo lead

- **Ưu tiên:** `P0-Must`
- **Vai trò:** Khách truy cập
- **User story:** Là khách truy cập, tôi muốn gửi thông tin tư vấn để trung tâm liên hệ lại.
- **Dữ liệu chính:** `leads`.
- **Tiêu chí nghiệm thu:**
  - [ ] Form có họ tên, phone, email, loại khách, khóa quan tâm, nhu cầu.
  - [ ] Submit thành công tạo lead trong CRM.
  - [ ] Lead có source `website`.
  - [ ] Ghi lại UTM nếu có.
  - [ ] Người dùng thấy thông báo gửi thành công.

### US-E07-004 - Form đăng ký học thử tạo lead/trial

- **Ưu tiên:** `P0-Should`
- **Vai trò:** Khách truy cập
- **User story:** Là khách truy cập, tôi muốn đăng ký học thử để trải nghiệm trước khi mua.
- **Dữ liệu chính:** `leads`, `trial_registrations`.
- **Tiêu chí nghiệm thu:**
  - [ ] Form tạo lead nếu chưa có.
  - [ ] Tạo trial registration gắn với lead.
  - [ ] CRM hiển thị lead có yêu cầu học thử.

## 10. E08-Reporting - Dashboard Cơ Bản

### US-E08-001 - Dashboard admin P0

- **Ưu tiên:** `P0-Should`
- **Vai trò:** Admin
- **User story:** Là Admin, tôi muốn xem dashboard cơ bản để nắm tình hình tuyển sinh và vận hành.
- **Tiêu chí nghiệm thu:**
  - [ ] Hiển thị tổng lead mới trong tháng.
  - [ ] Hiển thị lead cần follow-up hôm nay/quá hạn.
  - [ ] Hiển thị số học viên active.
  - [ ] Hiển thị số lớp đang học.
  - [ ] Hiển thị doanh thu đã thu trong tháng.
  - [ ] Hiển thị công nợ đang mở.

## 11. Demo Flow MVP Bắt Buộc

### DF-001 - Phụ huynh mua cho con

- [ ] Khách gửi form tư vấn loại phụ huynh.
- [ ] Lead vào CRM.
- [ ] Sales ghi lịch sử tư vấn.
- [ ] Chuyển lead thành person phụ huynh + student profile của con.
- [ ] Tạo customer account cho phụ huynh.
- [ ] Tạo order mua khóa cho con.
- [ ] Tạo enrollment cho con vào lớp.
- [ ] Giáo viên điểm danh một buổi.
- [ ] Kế toán ghi nhận thanh toán.
- [ ] Xem được phiếu thu và công nợ còn lại nếu có.

### DF-002 - Sinh viên tự đăng ký

- [ ] Khách gửi form loại sinh viên.
- [ ] Lead vào CRM.
- [ ] Chuyển lead thành person + student profile.
- [ ] Tạo customer account cá nhân cùng person.
- [ ] Tạo order và enrollment.
- [ ] Ghi nhận thanh toán.

### DF-003 - Công ty mua cho nhân sự

- [ ] HR gửi form loại công ty.
- [ ] Lead vào CRM.
- [ ] Tạo organization và HR contact.
- [ ] Tạo customer account organization.
- [ ] Tạo order B2B hoặc training contract cơ bản.
- [ ] Tạo student profiles cho nhân sự.
- [ ] Gắn nhân sự vào lớp.
- [ ] Điểm danh và xem danh sách nhân sự học.
- [ ] Theo dõi công nợ công ty.

## 12. Thứ Tự Build Đề Xuất

- [ ] Build E01-Core trước.
- [ ] Build E02-CRM để nhập lead.
- [ ] Build E03-Customer để chuyển lead thành hồ sơ thật.
- [ ] Build E04-Learning để tạo khóa/lớp/enrollment.
- [ ] Build E05-Attendance để có vận hành lớp.
- [ ] Build E06-Finance để có thu tiền/công nợ.
- [ ] Build E07-PublicWeb để form website đẩy lead vào CRM.
- [ ] Build E08-Reporting sau khi có dữ liệu thật.

## 13. Checklist Trước Khi Code Mỗi Story

- [ ] Story có epic và priority.
- [ ] Story có vai trò người dùng.
- [ ] Story có bảng dữ liệu liên quan.
- [ ] Story có tiêu chí nghiệm thu rõ.
- [ ] Story không phá quy tắc người mua khác người học.
- [ ] Story không yêu cầu P1/P2 trừ khi được đổi scope.
