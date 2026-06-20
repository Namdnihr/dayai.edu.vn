# DAYAI - Phase 2 Delivery Plan

Phase 2 biến nền tảng DAYAI từ bản MVP có đủ module lõi thành hệ thống có thể vận hành nội dung, tuyển sinh và học tập thực tế ở quy mô nhỏ đến vừa.

## Nguyên tắc Phase 2

- Làm trên nhánh `develop`, sau mỗi sprint ổn định phải commit và push lên GitHub.
- Không hardcode thêm nội dung chiến lược nếu có thể quản trị qua CMS/CRM.
- Nội dung public phải theo E-E-A-T: kinh nghiệm thực tế, chuyên môn, độ tin cậy, minh bạch người biên soạn/kiểm duyệt.
- Ưu tiên luồng vận hành thật: nội dung -> landing -> lead -> tư vấn -> học -> portal -> báo cáo.
- Không đưa Portal thành menu public đại trà; portal chỉ là khu tra cứu/đăng nhập có kiểm soát.

## Sprint 20 - CMS SEO & E-E-A-T Foundation

### Mục tiêu

Chuẩn hóa CMS để quản trị nội dung SEO, thông tin tác giả/kiểm duyệt và thuộc tính trust cho bài viết, tài nguyên, tin tức, video.

### Checklist

- [x] Thêm checklist Phase 2 vào tài liệu PM.
- [x] Thêm trường SEO cho `content_categories`.
- [x] Thêm trường SEO/E-E-A-T cho `content_items`.
- [x] Thêm trường SEO cho `video_lessons`.
- [x] Cập nhật model fillable/casts.
- [x] Cập nhật form Filament CMS.
- [x] Public content API trả thêm dữ liệu SEO/E-E-A-T.
- [ ] Chuẩn hóa trang chi tiết bài viết từ CMS.
- [ ] Chuẩn hóa trang danh mục nội dung từ CMS.

### Demo cuối sprint

- Admin nhập được SEO title/meta/canonical.
- Admin nhập được thông tin tác giả, người kiểm duyệt, ngày cập nhật, nguồn tham khảo.
- API public trả ra metadata để frontend dùng cho SEO.

## Sprint 21 - Course & Video Academy CMS

### Mục tiêu

Biến khóa học, module, video lesson và tài liệu học thành dữ liệu quản trị được, phục vụ cả public website và LMS.

### Checklist

- [ ] Chuẩn hóa course fields: audience, level, outcome, duration, price display, thumbnail, hero image.
- [ ] Chuẩn hóa course modules: mục tiêu, bài học, thứ tự, trạng thái.
- [ ] Gắn video lessons vào course/module.
- [ ] Quản lý tài liệu tải về theo bài học.
- [ ] Public API danh sách khóa học.
- [ ] Public API chi tiết khóa học.
- [ ] Frontend `/khoa-hoc` đọc dữ liệu động.

## Sprint 22 - Dynamic Landing Pages

### Mục tiêu

Landing page từng khóa/campaign không còn hardcode, đọc từ dữ liệu khóa học và CMS.

### Checklist

- [ ] Thiết kế schema landing page sections.
- [ ] Admin quản trị hero, benefits, curriculum, instructor, FAQ, testimonials, CTA.
- [ ] Route landing động theo slug.
- [ ] Form đăng ký gắn course/campaign/source.
- [ ] SEO metadata/schema cho landing.
- [ ] Fallback khi course chưa đủ dữ liệu.

## Sprint 23 - Lead Capture & UTM Tracking

### Mục tiêu

Mọi form public phải tạo lead có nguồn rõ ràng, UTM đầy đủ và phân tuyến ban đầu.

### Checklist

- [ ] Chuẩn hóa payload form lead.
- [ ] Lưu UTM source/medium/campaign/content/term.
- [ ] Lưu page URL/referrer.
- [ ] Gắn lead với course/campaign/segment.
- [ ] Auto phân loại: phụ huynh, student, work, business, enterprise.
- [ ] Tạo activity đầu tiên khi lead vào CRM.

## Sprint 24 - CRM Pipeline Advanced

### Mục tiêu

CRM tuyển sinh có pipeline rõ trạng thái, follow-up, phân công và hiệu quả tư vấn.

### Checklist

- [ ] Pipeline Kanban theo trạng thái lead.
- [ ] Follow-up date và nhắc việc.
- [ ] Lý do mất lead/không phù hợp.
- [ ] Kịch bản tư vấn theo segment.
- [ ] Bộ lọc lead nóng/lạnh/quá hạn.
- [ ] Báo cáo conversion theo nguồn và tư vấn viên.

## Sprint 25 - Parent/Student Portal V1

### Mục tiêu

Portal đủ để phụ huynh/học viên tra cứu lịch học, điểm danh, học phí và tiến độ.

### Checklist

- [ ] Chuẩn hóa cơ chế tra cứu/đăng nhập portal.
- [ ] Trang tổng quan học viên.
- [ ] Lịch học/lớp đang học.
- [ ] Điểm danh và buổi vắng.
- [ ] Học phí/công nợ/phiếu thu.
- [ ] Báo cáo tiến bộ và nhận xét giáo viên.

## Sprint 26 - LMS Learner Experience

### Mục tiêu

Học viên có trải nghiệm học video/tài liệu cơ bản trong hệ sinh thái DAYAI.

### Checklist

- [ ] Danh sách khóa đang học.
- [ ] Module/bài học/video/tài liệu.
- [ ] Trạng thái hoàn thành bài học.
- [ ] Ghi nhận tiến độ học video.
- [ ] Tài nguyên tải về theo khóa.
- [ ] Khóa nội dung theo quyền truy cập.

## Sprint 27 - Reporting, UAT & Staging

### Mục tiêu

Đóng Phase 2 bằng báo cáo vận hành, checklist nghiệm thu và chuẩn bị staging.

### Checklist

- [ ] Dashboard tuyển sinh Phase 2.
- [ ] Dashboard học viên/lớp học.
- [ ] Dashboard doanh thu/công nợ.
- [ ] Checklist UAT cho admin, tư vấn, giáo viên, phụ huynh, học viên.
- [ ] Tài liệu staging/deploy.
- [ ] Backup/restore checklist.
- [ ] Release note Phase 2.
