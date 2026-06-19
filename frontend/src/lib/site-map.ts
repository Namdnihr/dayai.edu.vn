export type SitePage = {
  path: string;
  title: string;
  description: string;
  group: string;
  priority: "P0" | "P1" | "P2";
};

export type MenuGroup = {
  label: string;
  href: string;
  description: string;
  children: SitePage[];
};

const pages = [
  page("/", "DAYAI", "Hệ sinh thái học AI cho Kids, Students, Workers, Business Owners và Enterprises.", "Trang chủ", "P0"),
  page("/doi-tuong-hoc/", "Đối tượng học", "Chọn lộ trình học AI phù hợp cho Kids, Students, Workers, Business Owners và Enterprises.", "Đối tượng học", "P0"),
  page("/ai-kids/", "AI Kids", "Lộ trình học AI an toàn, sáng tạo cho trẻ em và phụ huynh đồng hành.", "Đối tượng học", "P0"),
  page("/ai-kids/khoa-hoc-ai-cho-tre-em/", "Khóa học AI cho trẻ em", "Khóa học AI giúp trẻ phát triển tư duy sáng tạo và sử dụng công nghệ an toàn.", "AI Kids", "P0"),
  page("/ai-kids/ai-cho-hoc-sinh-tieu-hoc/", "AI cho học sinh tiểu học", "Học AI qua kể chuyện, hình ảnh và dự án nhỏ phù hợp học sinh tiểu học.", "AI Kids", "P1"),
  page("/ai-kids/ai-cho-hoc-sinh-thcs/", "AI cho học sinh THCS", "Lộ trình AI cho học sinh THCS: học tập, thuyết trình, sáng tạo và an toàn số.", "AI Kids", "P1"),
  page("/ai-kids/hoc-ai-an-toan-cho-tre/", "Học AI an toàn cho trẻ", "Nguyên tắc giúp trẻ dùng AI đúng cách, an toàn và có sự đồng hành của phụ huynh.", "AI Kids", "P1"),
  page("/ai-kids/phu-huynh-dong-hanh-cung-con-hoc-ai/", "Phụ huynh đồng hành cùng con học AI", "Hướng dẫn phụ huynh theo dõi tiến độ và định hướng con học AI.", "AI Kids", "P1"),
  page("/ai-kids/du-an-ai-cho-tre-em/", "Dự án AI cho trẻ em", "Các dự án AI nhỏ giúp trẻ học qua trải nghiệm và trình bày ý tưởng.", "AI Kids", "P2"),
  page("/ai-kids/cau-hoi-thuong-gap/", "FAQ AI Kids", "Câu hỏi thường gặp khi cho trẻ bắt đầu học AI tại DAYAI.", "AI Kids", "P1"),
  page("/ai-student/", "AI Student", "Học AI để học tập hiệu quả, làm slide, học tiếng Anh và định hướng nghề nghiệp.", "Đối tượng học", "P0"),
  page("/ai-student/khoa-hoc-ai-cho-hoc-sinh-sinh-vien/", "Khóa học AI cho học sinh, sinh viên", "Lộ trình AI dành cho học sinh, sinh viên muốn học tập và làm việc hiệu quả hơn.", "AI Student", "P0"),
  page("/ai-student/chatgpt-trong-hoc-tap/", "ChatGPT trong học tập", "Cách dùng ChatGPT để nghiên cứu, ghi chú, ôn tập và làm bài có trách nhiệm.", "AI Student", "P1"),
  page("/ai-student/ai-cho-sinh-vien/", "AI cho sinh viên", "Ứng dụng AI vào học đại học, nghiên cứu, portfolio và chuẩn bị nghề nghiệp.", "AI Student", "P1"),
  page("/ai-student/ai-ho-tro-hoc-tieng-anh/", "AI hỗ trợ học tiếng Anh", "Dùng AI để luyện từ vựng, viết, nói và phản xạ tiếng Anh.", "AI Student", "P1"),
  page("/ai-student/ai-lam-slide-thuyet-trinh/", "AI làm slide thuyết trình", "Cách dùng AI để lên dàn ý, viết nội dung và chuẩn bị slide thuyết trình.", "AI Student", "P1"),
  page("/ai-student/ai-dinh-huong-nghe-nghiep/", "AI định hướng nghề nghiệp", "Dùng AI để khám phá ngành nghề, kỹ năng và kế hoạch phát triển cá nhân.", "AI Student", "P2"),
  page("/ai-student/cau-hoi-thuong-gap/", "FAQ AI Student", "Câu hỏi thường gặp cho học sinh, sinh viên học AI.", "AI Student", "P1"),
  page("/ai-work/", "AI Work", "AI cho người đi làm: tăng năng suất, viết báo cáo, content, phân tích và tự động hóa.", "Đối tượng học", "P0"),
  page("/ai-work/khoa-hoc-ai-cho-nguoi-di-lam/", "Khóa học AI cho người đi làm", "Khóa học thực chiến giúp người đi làm ứng dụng AI vào công việc hằng ngày.", "AI Work", "P0"),
  page("/ai-work/chatgpt-cho-cong-viec/", "ChatGPT cho công việc", "Cách dùng ChatGPT để viết, tóm tắt, phân tích, lập kế hoạch và xử lý tác vụ.", "AI Work", "P1"),
  page("/ai-work/ai-tang-nang-suat-ca-nhan/", "AI tăng năng suất cá nhân", "Xây workflow cá nhân với AI để giảm việc lặp lại và tăng chất lượng đầu ra.", "AI Work", "P1"),
  page("/ai-work/ai-viet-bao-cao/", "AI viết báo cáo", "Dùng AI để lập cấu trúc, phân tích dữ liệu và viết báo cáo rõ ràng.", "AI Work", "P1"),
  page("/ai-work/ai-viet-content/", "AI viết content", "Ứng dụng AI trong viết bài, kịch bản, email, nội dung bán hàng và marketing.", "AI Work", "P1"),
  page("/ai-work/ai-phan-tich-du-lieu/", "AI phân tích dữ liệu", "Dùng AI để đọc bảng dữ liệu, tìm insight và chuẩn bị quyết định.", "AI Work", "P1"),
  page("/ai-work/ai-tu-dong-hoa-cong-viec/", "AI tự động hóa công việc", "Tư duy tự động hóa tác vụ cá nhân và nhóm bằng AI workflow.", "AI Work", "P1"),
  page("/ai-work/cau-hoi-thuong-gap/", "FAQ AI Work", "Câu hỏi thường gặp cho người đi làm học AI.", "AI Work", "P1"),
  page("/ai-business/", "AI Business", "AI cho chủ doanh nghiệp: vận hành, bán hàng, marketing, CSKH và automation.", "Đối tượng học", "P0"),
  page("/ai-business/khoa-hoc-ai-cho-chu-doanh-nghiep/", "Khóa học AI cho chủ doanh nghiệp", "Chương trình giúp chủ doanh nghiệp hiểu và triển khai AI thực tế.", "AI Business", "P0"),
  page("/ai-business/chatgpt-cho-doanh-nghiep/", "ChatGPT cho doanh nghiệp", "Ứng dụng ChatGPT vào quy trình kinh doanh và quản trị đội ngũ.", "AI Business", "P1"),
  page("/ai-business/ai-trong-ban-hang/", "AI trong bán hàng", "Dùng AI để nghiên cứu khách hàng, viết kịch bản và tối ưu quy trình bán hàng.", "AI Business", "P1"),
  page("/ai-business/ai-trong-marketing/", "AI trong marketing", "Ứng dụng AI để lập kế hoạch, sản xuất nội dung và đo lường marketing.", "AI Business", "P1"),
  page("/ai-business/ai-cham-soc-khach-hang/", "AI chăm sóc khách hàng", "Dùng AI để chuẩn hóa phản hồi, kịch bản chăm sóc và trải nghiệm khách hàng.", "AI Business", "P1"),
  page("/ai-business/ai-automation-cho-doanh-nghiep/", "AI Automation cho doanh nghiệp", "Tư duy tự động hóa quy trình doanh nghiệp bằng AI.", "AI Business", "P1"),
  page("/ai-business/ai-quan-tri-van-hanh/", "AI quản trị vận hành", "Ứng dụng AI vào vận hành, báo cáo, quản lý tri thức và quy trình nội bộ.", "AI Business", "P1"),
  page("/ai-business/cau-hoi-thuong-gap/", "FAQ AI Business", "Câu hỏi thường gặp cho chủ doanh nghiệp học AI.", "AI Business", "P1"),
  page("/ai-enterprise/", "AI Enterprise", "Đào tạo AI cho doanh nghiệp, theo phòng ban, có LMS theo dõi tiến độ.", "Đối tượng học", "P0"),
  page("/ai-enterprise/dao-tao-ai-cho-doanh-nghiep/", "Đào tạo AI cho doanh nghiệp", "Chương trình đào tạo AI nội bộ cho doanh nghiệp Việt Nam.", "AI Enterprise", "P0"),
  page("/ai-enterprise/dao-tao-ai-theo-phong-ban/", "Đào tạo AI theo phòng ban", "Thiết kế nội dung AI theo nhu cầu từng phòng ban.", "AI Enterprise", "P1"),
  page("/ai-enterprise/dao-tao-ai-cho-sale/", "Đào tạo AI cho sale", "Ứng dụng AI vào nghiên cứu khách hàng, kịch bản bán hàng và chăm sóc lead.", "AI Enterprise", "P1"),
  page("/ai-enterprise/dao-tao-ai-cho-marketing/", "Đào tạo AI cho marketing", "AI cho lập kế hoạch, content, chiến dịch và phân tích hiệu quả marketing.", "AI Enterprise", "P1"),
  page("/ai-enterprise/dao-tao-ai-cho-hr/", "Đào tạo AI cho HR", "Ứng dụng AI vào tuyển dụng, đào tạo, truyền thông nội bộ và L&D.", "AI Enterprise", "P1"),
  page("/ai-enterprise/dao-tao-ai-cho-cskh/", "Đào tạo AI cho CSKH", "AI hỗ trợ chăm sóc khách hàng, tri thức sản phẩm và phản hồi đa kênh.", "AI Enterprise", "P1"),
  page("/ai-enterprise/lms-theo-doi-tien-do/", "LMS theo dõi tiến độ", "Theo dõi học viên, điểm danh, tiến độ, chứng chỉ và báo cáo doanh nghiệp.", "AI Enterprise", "P1"),
  page("/ai-enterprise/workshop-ai-noi-bo/", "Workshop AI nội bộ", "Workshop AI ngắn hạn theo use case doanh nghiệp.", "AI Enterprise", "P1"),
  page("/ai-enterprise/cau-hoi-thuong-gap/", "FAQ AI Enterprise", "Câu hỏi thường gặp về đào tạo AI cho doanh nghiệp.", "AI Enterprise", "P1"),
  page("/khoa-hoc/", "Khóa học AI", "Tổng hợp các khóa học AI tại DAYAI.", "Khóa học AI", "P0"),
  page("/khoa-hoc/ai-can-ban/", "AI Căn Bản", "Landing page khóa AI Căn Bản cho người mới bắt đầu.", "Khóa học AI", "P0"),
  page("/khoa-hoc/khoa-hoc-chatgpt/", "Khóa học ChatGPT", "Khóa học sử dụng ChatGPT trong học tập, công việc và doanh nghiệp.", "Khóa học AI", "P1"),
  page("/khoa-hoc/khoa-hoc-ai-co-ban/", "Khóa học AI cơ bản", "Khóa học AI cơ bản cho người mới bắt đầu.", "Khóa học AI", "P0"),
  page("/khoa-hoc/khoa-hoc-ai-thuc-chien/", "Khóa học AI thực chiến", "Học AI qua ví dụ thật, bài tập thực hành và workflow ứng dụng.", "Khóa học AI", "P1"),
  page("/khoa-hoc/khoa-hoc-ai-online/", "Khóa học AI online", "Học AI online/hybrid với video, bài tập và hỗ trợ tư vấn.", "Khóa học AI", "P1"),
  page("/khoa-hoc/lich-khai-giang/", "Lịch khai giảng", "Lịch khai giảng các khóa học AI tại DAYAI.", "Khóa học AI", "P0"),
  page("/giai-phap/", "Giải pháp doanh nghiệp", "Giải pháp AI automation, chatbot, workflow và tư vấn triển khai AI.", "Giải pháp doanh nghiệp", "P0"),
  page("/giai-phap/ai-automation/", "AI Automation", "Tự động hóa quy trình bằng AI cho cá nhân và doanh nghiệp.", "Giải pháp doanh nghiệp", "P1"),
  page("/giai-phap/chatbot-ai/", "Chatbot AI", "Tư vấn chatbot AI cho tuyển sinh, CSKH và quy trình nội bộ.", "Giải pháp doanh nghiệp", "P1"),
  page("/giai-phap/ai-workflow/", "AI Workflow", "Thiết kế workflow AI để chuẩn hóa công việc và tăng năng suất.", "Giải pháp doanh nghiệp", "P1"),
  page("/giai-phap/tu-van-trien-khai-ai/", "Tư vấn triển khai AI", "Tư vấn roadmap ứng dụng AI theo mục tiêu doanh nghiệp.", "Giải pháp doanh nghiệp", "P1"),
  page("/giai-phap/chuyen-doi-so-bang-ai/", "Chuyển đổi số bằng AI", "AI như lớp tăng tốc cho chuyển đổi số trong doanh nghiệp.", "Giải pháp doanh nghiệp", "P1"),
  page("/cong-cu-ai/", "Công cụ AI", "Hướng dẫn, đánh giá và so sánh các công cụ AI phổ biến.", "Công cụ AI", "P1"),
  page("/cong-cu-ai/chatgpt-plus/", "ChatGPT Plus", "Hướng dẫn dùng ChatGPT Plus hiệu quả trong học tập và công việc.", "Công cụ AI", "P1"),
  page("/cong-cu-ai/canva-pro/", "Canva Pro", "Ứng dụng Canva Pro và AI trong thiết kế nội dung.", "Công cụ AI", "P2"),
  page("/cong-cu-ai/capcut-pro/", "CapCut Pro", "Dùng CapCut Pro và AI để dựng video nhanh hơn.", "Công cụ AI", "P2"),
  page("/cong-cu-ai/google-one-ai/", "Google One AI", "Tìm hiểu các tính năng AI trong hệ sinh thái Google.", "Công cụ AI", "P2"),
  page("/cong-cu-ai/grok-ai/", "Grok AI", "Tìm hiểu Grok AI và cách ứng dụng phù hợp.", "Công cụ AI", "P2"),
  page("/cong-cu-ai/so-sanh-cong-cu-ai/", "So sánh công cụ AI", "So sánh công cụ AI theo nhu cầu học tập, công việc và doanh nghiệp.", "Công cụ AI", "P1"),
  page("/prompt-ai/", "Prompt AI", "Thư viện prompt AI cho học tập, công việc, bán hàng, marketing và doanh nghiệp.", "Tài nguyên", "P1"),
  page("/prompt-ai/prompt-chatgpt/", "Prompt ChatGPT", "Bộ prompt ChatGPT nền tảng cho người mới.", "Tài nguyên", "P1"),
  page("/prompt-ai/prompt-cho-hoc-tap/", "Prompt cho học tập", "Prompt hỗ trợ học tập, ghi chú, ôn thi và nghiên cứu.", "Tài nguyên", "P1"),
  page("/prompt-ai/prompt-cho-cong-viec/", "Prompt cho công việc", "Prompt tăng năng suất công việc cá nhân và đội nhóm.", "Tài nguyên", "P1"),
  page("/prompt-ai/prompt-cho-ban-hang/", "Prompt cho bán hàng", "Prompt hỗ trợ nghiên cứu khách hàng và kịch bản bán hàng.", "Tài nguyên", "P1"),
  page("/prompt-ai/prompt-cho-marketing/", "Prompt cho marketing", "Prompt lập kế hoạch, viết nội dung và tối ưu chiến dịch marketing.", "Tài nguyên", "P1"),
  page("/prompt-ai/prompt-cho-doanh-nghiep/", "Prompt cho doanh nghiệp", "Prompt chuẩn hóa quy trình và quản lý tri thức doanh nghiệp.", "Tài nguyên", "P1"),
  page("/cam-nang-ai/", "Cẩm nang AI", "Kiến thức nền tảng về AI, ChatGPT, prompt và ứng dụng AI.", "Tài nguyên", "P1"),
  page("/cam-nang-ai/ai-la-gi/", "AI là gì?", "Giải thích AI cho người mới bắt đầu.", "Tài nguyên", "P1"),
  page("/cam-nang-ai/chatgpt-la-gi/", "ChatGPT là gì?", "Tìm hiểu ChatGPT và các ứng dụng phổ biến.", "Tài nguyên", "P1"),
  page("/cam-nang-ai/cach-dung-chatgpt/", "Cách dùng ChatGPT", "Hướng dẫn dùng ChatGPT hiệu quả và có trách nhiệm.", "Tài nguyên", "P1"),
  page("/cam-nang-ai/cach-viet-prompt-chatgpt/", "Cách viết prompt ChatGPT", "Nguyên tắc viết prompt rõ mục tiêu, dễ kiểm soát đầu ra.", "Tài nguyên", "P1"),
  page("/cam-nang-ai/hoc-ai-bat-dau-tu-dau/", "Học AI bắt đầu từ đâu?", "Lộ trình bắt đầu học AI cho từng nhóm người học.", "Tài nguyên", "P1"),
  page("/cam-nang-ai/ung-dung-ai-trong-cong-viec/", "Ứng dụng AI trong công việc", "Các cách ứng dụng AI vào công việc hằng ngày.", "Tài nguyên", "P1"),
  page("/tai-nguyen/", "Tài nguyên AI", "Checklist, ebook và mẫu prompt miễn phí từ DAYAI.", "Tài nguyên", "P1"),
  page("/tai-nguyen/checklist-ai-cho-phu-huynh/", "Checklist AI cho phụ huynh", "Checklist giúp phụ huynh đồng hành cùng con học AI.", "Tài nguyên", "P1"),
  page("/tai-nguyen/checklist-ai-cho-sinh-vien/", "Checklist AI cho sinh viên", "Checklist ứng dụng AI trong học tập và định hướng nghề nghiệp.", "Tài nguyên", "P1"),
  page("/tai-nguyen/checklist-ai-cho-nguoi-di-lam/", "Checklist AI cho người đi làm", "Checklist tăng năng suất cá nhân bằng AI.", "Tài nguyên", "P1"),
  page("/tai-nguyen/checklist-ai-cho-doanh-nghiep/", "Checklist AI cho doanh nghiệp", "Checklist chuẩn bị triển khai AI trong doanh nghiệp.", "Tài nguyên", "P1"),
  page("/tai-nguyen/mau-prompt-chatgpt/", "Mẫu prompt ChatGPT", "Tải bộ mẫu prompt ChatGPT cho nhiều tình huống.", "Tài nguyên", "P1"),
  page("/tai-nguyen/ebook-ai-thuc-chien/", "Ebook AI thực chiến", "Ebook nhập môn ứng dụng AI vào học tập, công việc và doanh nghiệp.", "Tài nguyên", "P1"),
  page("/case-study/", "Case Study", "Câu chuyện ứng dụng AI của học viên và doanh nghiệp.", "Tài nguyên", "P2"),
  page("/case-study/ai-kids/", "Case Study AI Kids", "Dự án và kết quả học AI của trẻ em.", "Tài nguyên", "P2"),
  page("/case-study/ai-student/", "Case Study AI Student", "Câu chuyện học sinh, sinh viên ứng dụng AI.", "Tài nguyên", "P2"),
  page("/case-study/ai-work/", "Case Study AI Work", "Câu chuyện người đi làm tăng năng suất bằng AI.", "Tài nguyên", "P2"),
  page("/case-study/ai-business/", "Case Study AI Business", "Câu chuyện chủ doanh nghiệp ứng dụng AI.", "Tài nguyên", "P2"),
  page("/case-study/ai-enterprise/", "Case Study AI Enterprise", "Câu chuyện đào tạo AI nội bộ cho doanh nghiệp.", "Tài nguyên", "P2"),
  page("/tin-tuc-ai/", "Tin tức AI", "Tin tức, xu hướng và cập nhật công cụ AI mới nhất.", "Cập nhật AI", "P0"),
  page("/tin-tuc-ai/tin-ai-moi-nhat/", "Tin AI mới nhất", "Cập nhật tin AI mới nhất cho người học và doanh nghiệp.", "Cập nhật AI", "P1"),
  page("/tin-tuc-ai/cap-nhat-chatgpt/", "Cập nhật ChatGPT", "Các thay đổi mới của ChatGPT và cách ứng dụng.", "Cập nhật AI", "P1"),
  page("/tin-tuc-ai/cap-nhat-gemini/", "Cập nhật Gemini", "Tin mới về Gemini và hệ sinh thái Google AI.", "Cập nhật AI", "P1"),
  page("/tin-tuc-ai/cap-nhat-canva-ai/", "Cập nhật Canva AI", "Cập nhật tính năng AI trong Canva.", "Cập nhật AI", "P2"),
  page("/tin-tuc-ai/cap-nhat-cong-cu-ai/", "Cập nhật công cụ AI", "Tin mới về các công cụ AI phổ biến.", "Cập nhật AI", "P1"),
  page("/tin-tuc-ai/ai-trong-giao-duc/", "AI trong giáo dục", "Xu hướng AI trong giáo dục và đào tạo.", "Cập nhật AI", "P1"),
  page("/tin-tuc-ai/ai-trong-kinh-doanh/", "AI trong kinh doanh", "Xu hướng AI trong kinh doanh và vận hành.", "Cập nhật AI", "P1"),
  page("/tin-tuc-ai/chinh-sach-va-dao-duc-ai/", "Chính sách và đạo đức AI", "Tin về chính sách, đạo đức và an toàn AI.", "Cập nhật AI", "P1"),
  page("/tin-tuc-ai/xu-huong-ai/", "Xu hướng AI", "Các xu hướng AI đáng chú ý cho cá nhân và doanh nghiệp.", "Cập nhật AI", "P1"),
  page("/ve-day-ai/", "Về DAYAI", "Giới thiệu tầm nhìn, phương pháp đào tạo và hệ sinh thái DAYAI.", "Thương hiệu", "P0"),
  page("/giang-vien/", "Giảng viên", "Đội ngũ giảng viên, mentor và chuyên gia đồng hành tại DAYAI.", "Thương hiệu", "P1"),
  page("/lien-he/", "Liên hệ", "Thông tin liên hệ DAYAI và form tư vấn.", "Chuyển đổi", "P0"),
  page("/dang-ky-tu-van/", "Đăng ký tư vấn", "Đăng ký tư vấn lộ trình học AI phù hợp.", "Chuyển đổi", "P0"),
  page("/portal/", "Cổng học viên", "Tra cứu lịch học, điểm danh, học phí, thông báo và chứng chỉ.", "Portal", "P0"),
  page("/company-portal/", "Cổng doanh nghiệp", "Tra cứu tiến độ học tập và công nợ đào tạo doanh nghiệp.", "Portal", "P0"),
] as const satisfies SitePage[];

export const sitePages: SitePage[] = [...pages];

export const menuGroups: MenuGroup[] = [
  group("Đối tượng học", "/doi-tuong-hoc/", "Kids, Student, Work, Business và Enterprise.", [
    "/doi-tuong-hoc/",
    "/ai-kids/",
    "/ai-student/",
    "/ai-work/",
    "/ai-business/",
    "/ai-enterprise/",
  ]),
  group("Khóa học AI", "/khoa-hoc/", "Khóa học, lịch khai giảng và landing khóa.", [
    "/khoa-hoc/",
    "/khoa-hoc/khoa-hoc-chatgpt/",
    "/khoa-hoc/khoa-hoc-ai-co-ban/",
    "/khoa-hoc/khoa-hoc-ai-thuc-chien/",
    "/khoa-hoc/khoa-hoc-ai-online/",
    "/khoa-hoc/lich-khai-giang/",
  ]),
  group("Giải pháp doanh nghiệp", "/giai-phap/", "Automation, chatbot, workflow và tư vấn triển khai AI.", [
    "/giai-phap/",
    "/giai-phap/ai-automation/",
    "/giai-phap/chatbot-ai/",
    "/giai-phap/ai-workflow/",
    "/giai-phap/tu-van-trien-khai-ai/",
  ]),
  group("Tài nguyên", "/tai-nguyen/", "Prompt, cẩm nang, checklist, ebook và case study.", [
    "/prompt-ai/",
    "/cam-nang-ai/",
    "/tai-nguyen/",
    "/case-study/",
  ]),
  group("Công cụ AI", "/cong-cu-ai/", "Hướng dẫn và so sánh công cụ AI.", [
    "/cong-cu-ai/",
    "/cong-cu-ai/chatgpt-plus/",
    "/cong-cu-ai/so-sanh-cong-cu-ai/",
  ]),
  group("Cập nhật AI", "/tin-tuc-ai/", "Tin mới, xu hướng và cập nhật công cụ AI.", [
    "/tin-tuc-ai/",
    "/tin-tuc-ai/tin-ai-moi-nhat/",
    "/tin-tuc-ai/cap-nhat-chatgpt/",
    "/tin-tuc-ai/xu-huong-ai/",
  ]),
  group("Liên hệ", "/lien-he/", "Thông tin liên hệ và đăng ký tư vấn.", [
    "/lien-he/",
    "/dang-ky-tu-van/",
  ]),
];

export function findSitePage(path: string) {
  const normalizedPath = normalizePath(path);

  return sitePages.find((sitePage) => normalizePath(sitePage.path) === normalizedPath);
}

export function getPagesByGroup(groupName: string) {
  return sitePages.filter((sitePage) => sitePage.group === groupName);
}

function page(
  path: string,
  title: string,
  description: string,
  group: string,
  priority: SitePage["priority"],
): SitePage {
  return { path, title, description, group, priority };
}

function group(
  label: string,
  href: string,
  description: string,
  paths: string[],
): MenuGroup {
  return {
    label,
    href,
    description,
    children: paths
      .map((path) => findSitePage(path))
      .filter((sitePage): sitePage is SitePage => Boolean(sitePage)),
  };
}

function normalizePath(path: string) {
  if (path === "/") {
    return path;
  }

  return path.endsWith("/") ? path : `${path}/`;
}
