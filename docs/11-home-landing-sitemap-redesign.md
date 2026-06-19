# Sitemap & Redesign Checklist - Trang Chủ Và Landing Page

## Mục Tiêu

Chốt cấu trúc sitemap và checklist nội dung trước khi làm lại giao diện `dayai.edu.vn` và landing page khóa học. Tài liệu này là “khung xương” để redesign không bị biến trang chủ thành landing bán một khóa duy nhất.

## Nguyên Tắc PM

- `dayai.edu.vn` là website trung tâm: thương hiệu, nội dung, khóa học, video, kiến thức, niềm tin.
- Landing page chỉ phục vụ một khóa/campaign cụ thể, ví dụ `k01.dayai.edu.vn`.
- Mọi CTA đăng ký phải đẩy lead về CRM.
- Mọi trang public cần có metadata, canonical, sitemap, trạng thái empty/error nếu lấy dữ liệu động.
- Không đưa portal phụ huynh/HR thành CTA chính trên trang chủ; portal là tiện ích sau bán hàng.

## Sitemap Tổng Thể Public Website

> Bản sitemap SEO đầy đủ cho toàn bộ cấu trúc dài hạn được chốt tại `docs/12-full-seo-sitemap-menu.md`.

| Route | Tên Trang | Mục Tiêu | CTA Chính | Ưu Tiên |
| --- | --- | --- | --- | --- |
| `/` | Trang chủ DAYAI | Giới thiệu trung tâm, điều hướng nội dung/khóa học | Đăng ký tư vấn | P0 |
| `/khoa-hoc` | Danh sách khóa học | Gom tất cả khóa học theo nhóm đối tượng | Xem khóa phù hợp | P1 |
| `/khoa-hoc/ai-can-ban` | Landing AI Căn Bản | Bán/thu lead cho khóa AI căn bản | Đăng ký học thử | P0 |
| `/kien-thuc` | Kho kiến thức AI | SEO, nuôi dưỡng người học | Đọc bài / nhận tư vấn | P1 |
| `/kien-thuc/[slug]` | Chi tiết bài viết | SEO long-tail, giáo dục thị trường | Đăng ký khóa liên quan | P1 |
| `/video` | Video Academy | Đào tạo bằng video, tăng niềm tin | Xem bài học miễn phí | P1 |
| `/video/[slug]` | Chi tiết video | Bài học/video replay/workshop | Đăng ký học thử | P1 |
| `/giang-vien` | Đội ngũ giảng viên | Xây dựng uy tín chuyên môn | Xem khóa học | P1 |
| `/doanh-nghiep` | AI cho doanh nghiệp | B2B training cho HR/chủ doanh nghiệp | Đăng ký tư vấn doanh nghiệp | P1 |
| `/tin-tuc` | Tin tức/sự kiện | Hoạt động trung tâm, workshop | Theo dõi / đăng ký | P2 |
| `/thu-vien` | Ảnh/video hoạt động | Social proof | Đăng ký tham quan/học thử | P2 |
| `/lien-he` | Liên hệ | Hotline, địa chỉ, form | Gửi liên hệ | P1 |
| `/portal` | Cổng phụ huynh/học viên | Tra cứu sau bán hàng | Tra cứu thông tin học viên | P0 hệ thống |
| `/company-portal` | Cổng doanh nghiệp/HR | Tra cứu tiến độ nhân sự B2B | Tra cứu doanh nghiệp | P0 hệ thống |

## Sitemap Landing/Campaign

| Route/Subdomain | Tên Trang | Mục Tiêu | Ghi Chú |
| --- | --- | --- | --- |
| `k01.dayai.edu.vn` | Landing AI Căn Bản K01 | Thu lead cho khóa AI căn bản đợt K01 | Map về `/khoa-hoc/ai-can-ban` hoặc campaign dynamic |
| `k02.dayai.edu.vn` | Landing Prompt Engineering K02 | Thu lead cho khóa prompt | Làm sau khi có nhiều campaign |
| `business.dayai.edu.vn` | Landing B2B Training | Thu lead doanh nghiệp | Có thể map về `/doanh-nghiep` |

## Checklist Trang Chủ `dayai.edu.vn`

### 1. Header / Navigation

- [ ] Logo DAYAI.
- [ ] Menu: Khóa học, Kiến thức, Video, Doanh nghiệp, Giảng viên, Liên hệ.
- [ ] Link phụ: Portal học viên, Portal doanh nghiệp.
- [ ] CTA chính: `Đăng ký tư vấn`.
- [ ] Header sticky hoặc rõ CTA trên mobile.

### 2. Hero Section

- [ ] Headline nói rõ DAYAI đào tạo AI cho ai.
- [ ] Subheadline giải thích khác biệt: thực chiến, có lộ trình, có giảng viên, có portal theo dõi.
- [ ] CTA chính: `Đăng ký tư vấn`.
- [ ] CTA phụ: `Xem khóa học`.
- [ ] Visual: lớp học/workshop/dashboard AI/video learning.
- [ ] Có trust indicators: số khóa, số học viên, doanh nghiệp, giờ học thực hành nếu có số liệu thật.

### 3. Nhóm Đối Tượng Học

- [ ] Phụ huynh mua cho con học.
- [ ] Sinh viên/người mới đi làm.
- [ ] Chủ doanh nghiệp.
- [ ] Công ty/HR mua khóa cho nhân sự.
- [ ] Mỗi nhóm có pain point, kết quả mong muốn, khóa gợi ý.

### 4. Danh Sách Khóa Học Nổi Bật

- [ ] AI Căn Bản.
- [ ] Prompt Engineering.
- [ ] AI Cho Doanh Nghiệp.
- [ ] AI Cho Học Sinh/Sinh Viên nếu tách riêng.
- [ ] Mỗi course card có: đối tượng, thời lượng, hình thức học, CTA.
- [ ] CTA từng khóa dẫn về landing tương ứng.

### 5. Video Academy

- [ ] Hiển thị 3 video public/free.
- [ ] Có badge miễn phí/cần đăng nhập nếu sau này phân quyền.
- [ ] Có duration và chủ đề.
- [ ] CTA: `Xem thêm video`.

### 6. Kho Kiến Thức AI

- [ ] Hiển thị 3-6 bài viết mới/nổi bật.
- [ ] Có category/tag.
- [ ] Có excerpt ngắn.
- [ ] CTA: `Xem kho kiến thức`.

### 7. AI Cho Doanh Nghiệp

- [ ] Nêu bài toán B2B: đào tạo nội bộ, tăng năng suất, chuẩn hóa kỹ năng AI.
- [ ] Có các phòng ban phù hợp: HR, Sales, Marketing, Vận hành, CSKH.
- [ ] CTA: `Tư vấn đào tạo doanh nghiệp`.
- [ ] Link sang trang/section B2B riêng.

### 8. Đội Ngũ Giảng Viên

- [ ] 3-6 giảng viên/mentor.
- [ ] Mỗi người có ảnh, chuyên môn, kinh nghiệm, khóa phụ trách.
- [ ] Không dùng avatar giả nếu chuẩn bị public.

### 9. Social Proof

- [ ] Feedback học viên/phụ huynh.
- [ ] Logo đối tác/doanh nghiệp nếu có quyền dùng.
- [ ] Ảnh lớp học/workshop thật.
- [ ] Kết quả học viên/case study.

### 10. Quy Trình Học Tại DAYAI

- [ ] Tư vấn đầu vào.
- [ ] Chọn lộ trình.
- [ ] Học trực tiếp/hybrid/video.
- [ ] Thực hành bài tập.
- [ ] Nhận xét/đánh giá tiến bộ.
- [ ] Cấp chứng chỉ.

### 11. FAQ Trang Chủ

- [ ] DAYAI dạy AI cho ai?
- [ ] Có khóa cho học sinh không?
- [ ] Người mới bắt đầu có học được không?
- [ ] Doanh nghiệp có thể mua khóa cho nhân sự không?
- [ ] Có học online/video không?
- [ ] Có chứng chỉ không?

### 12. Footer

- [ ] Thông tin trung tâm.
- [ ] Hotline/email/địa chỉ/fanpage.
- [ ] Link khóa học, kiến thức, video, portal.
- [ ] Chính sách bảo mật/điều khoản nếu chuẩn bị production.

## Checklist Landing Page Khóa Học

### 1. Hero Bán Khóa

- [ ] Tên khóa học rõ ràng.
- [ ] Mã khóa/campaign nếu có: K01, K02.
- [ ] Một câu hứa hẹn kết quả đầu ra cụ thể.
- [ ] CTA chính: `Đăng ký học thử` hoặc `Đăng ký tư vấn`.
- [ ] CTA phụ: `Xem lộ trình`.
- [ ] Form lead hoặc anchor tới form nằm trong màn hình đầu/giữa trang.

### 2. Đối Tượng Phù Hợp

- [ ] Ai nên học.
- [ ] Ai chưa phù hợp.
- [ ] Kiến thức đầu vào cần có.
- [ ] Thiết bị/công cụ cần chuẩn bị.

### 3. Pain Point & Outcome

- [ ] Người học đang gặp vấn đề gì.
- [ ] Sau khóa học làm được gì.
- [ ] Kết quả đo được: prompt, workflow, bài tập, project, chứng chỉ.

### 4. Lộ Trình / Curriculum

- [ ] Số buổi/số giờ.
- [ ] Nội dung từng buổi/module.
- [ ] Bài tập thực hành.
- [ ] Sản phẩm cuối khóa.
- [ ] Tài liệu/video bổ trợ.

### 5. Giảng Viên

- [ ] Ảnh giảng viên.
- [ ] Chuyên môn liên quan khóa học.
- [ ] Kinh nghiệm thực tế.
- [ ] Lý do người học nên tin.

### 6. Lịch Học & Hình Thức

- [ ] Ngày khai giảng.
- [ ] Lịch học trong tuần.
- [ ] Offline/online/hybrid.
- [ ] Địa điểm học.
- [ ] Số lượng học viên tối đa.

### 7. Học Phí / Ưu Đãi

- [ ] Học phí niêm yết nếu public.
- [ ] Ưu đãi early bird nếu có.
- [ ] Chính sách học thử/tư vấn.
- [ ] Chính sách hoàn/hủy nếu cần.

### 8. Feedback / Case Study

- [ ] Feedback học viên/phụ huynh.
- [ ] Feedback doanh nghiệp nếu khóa B2B.
- [ ] Ảnh/video lớp học thật.
- [ ] Tránh dùng review giả trước production.

### 9. FAQ Chốt Sale

- [ ] Tôi chưa biết gì về AI có học được không?
- [ ] Khóa học có thực hành không?
- [ ] Có video xem lại không?
- [ ] Có chứng chỉ không?
- [ ] Phụ huynh theo dõi tiến độ thế nào?
- [ ] Công ty mua cho nhiều nhân sự được không?

### 10. Lead Form

- [ ] Họ tên.
- [ ] Số điện thoại.
- [ ] Email nếu cần.
- [ ] Nhóm khách: phụ huynh, sinh viên, chủ doanh nghiệp, HR/công ty.
- [ ] Khóa quan tâm.
- [ ] Ghi chú/nhu cầu.
- [ ] Hidden fields: source, medium, campaign, landing slug.
- [ ] Success state rõ ràng sau khi gửi.
- [ ] Error state rõ ràng khi gửi lỗi.

## Data/API Cần Có Khi Redesign

- [ ] API hoặc mock data cho danh sách khóa học.
- [ ] API hoặc mock data cho bài viết kiến thức.
- [ ] API hoặc mock data cho video public.
- [ ] API hoặc mock data cho giảng viên.
- [ ] API hoặc mock data cho testimonial.
- [ ] Lead form giữ tương thích với CRM hiện tại.

## SEO Checklist Khi Làm Lại Giao Diện

- [ ] Mỗi route có title/description riêng.
- [ ] Mỗi landing có canonical.
- [ ] Heading chỉ có một `h1` chính.
- [ ] Section dùng heading hierarchy rõ: `h2`, `h3`.
- [ ] CTA không dùng text mơ hồ như `click here`.
- [ ] Ảnh thật có alt text.
- [ ] Sitemap cập nhật route mới.
- [ ] Robots không chặn trang public.

## Mobile Checklist

- [ ] Header gọn trên mobile.
- [ ] CTA chính thấy trong 1-2 màn hình đầu.
- [ ] Form dễ nhập trên điện thoại.
- [ ] Course cards không quá dài.
- [ ] Video/card responsive.
- [ ] Font size dễ đọc.

## Acceptance Criteria

- [ ] Trang chủ không còn giống landing đơn lẻ.
- [ ] Trang chủ điều hướng rõ tới khóa học, video, kiến thức, doanh nghiệp.
- [ ] Landing page tập trung chuyển đổi cho một khóa/campaign.
- [ ] Form lead vẫn tạo lead trong CRM.
- [ ] Sitemap/robots build pass.
- [ ] Frontend lint/build pass.
- [ ] Có checklist nội dung thật còn thiếu trước khi public.
