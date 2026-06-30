export type CourseCategory = "foundation" | "prompt" | "work" | "business" | "enterprise" | "kids";
export type CourseAccessType = "free_funnel" | "paid" | "consultation";

export type CatalogCourse = {
  slug: string;
  title: string;
  subtitle: string;
  category: CourseCategory;
  audience: string;
  level: string;
  format: string;
  duration: string;
  sessions: string;
  lessons: number;
  students: number;
  rating: number;
  price: string;
  priceAmountVnd: number;
  accessType: CourseAccessType;
  ctaLabel: string;
  flowNote: string;
  badge: string;
  description: string;
  outcomes: string[];
  tools: string[];
  curriculum: Array<{
    title: string;
    lessons: string[];
  }>;
};

export const courseCategories: Array<{ key: "all" | CourseCategory; label: string; description: string }> = [
  { key: "all", label: "Tất cả", description: "Toàn bộ lộ trình DAYAI" },
  { key: "foundation", label: "Nền tảng", description: "Bắt đầu học AI bài bản" },
  { key: "prompt", label: "Prompt", description: "Làm chủ ChatGPT và prompt" },
  { key: "work", label: "Công việc", description: "Tăng năng suất cá nhân" },
  { key: "business", label: "Kinh doanh", description: "Ứng dụng AI cho chủ doanh nghiệp" },
  { key: "enterprise", label: "Doanh nghiệp", description: "Đào tạo đội ngũ và phòng ban" },
  { key: "kids", label: "Kids", description: "AI an toàn cho trẻ em" },
];

export const catalogCourses: CatalogCourse[] = [
  {
    slug: "ai-can-ban",
    title: "AI Căn Bản",
    subtitle: "Hiểu đúng AI, dùng đúng công cụ, bắt đầu học bài bản.",
    category: "foundation",
    audience: "Người mới bắt đầu",
    level: "Cơ bản",
    format: "Hybrid",
    duration: "12 giờ",
    sessions: "6 buổi",
    lessons: 18,
    students: 428,
    rating: 4.9,
    price: "Liên hệ tư vấn",
    priceAmountVnd: 0,
    accessType: "consultation",
    ctaLabel: "Nhận tư vấn",
    flowNote: "DAYAI tư vấn lộ trình và lịch học trước khi ghi danh.",
    badge: "Nên bắt đầu",
    description:
      "Khóa học nền tảng giúp học viên hiểu AI là gì, biết chọn công cụ phù hợp và ứng dụng AI vào học tập, công việc hoặc vận hành hằng ngày.",
    outcomes: [
      "Hiểu đúng khả năng và giới hạn của AI",
      "Biết viết prompt rõ mục tiêu, ngữ cảnh và tiêu chí đầu ra",
      "Tạo tài liệu, slide, kế hoạch và báo cáo bằng AI",
      "Xây thói quen dùng AI có trách nhiệm",
    ],
    tools: ["ChatGPT", "Gemini", "Canva AI", "NotebookLM"],
    curriculum: [
      {
        title: "Khởi động tư duy AI",
        lessons: ["AI là gì và không phải là gì", "Dùng AI an toàn", "Chọn công cụ theo mục tiêu"],
      },
      {
        title: "Prompt căn bản",
        lessons: ["Cấu trúc prompt rõ mục tiêu", "Vai trò, ngữ cảnh, dữ liệu", "Kiểm tra và cải thiện đầu ra"],
      },
      {
        title: "Ứng dụng thực tế",
        lessons: ["Tạo slide và tài liệu", "Tóm tắt và phân tích", "Xây workflow cá nhân"],
      },
    ],
  },
  {
    slug: "khoa-hoc-chatgpt",
    title: "Khóa học ChatGPT",
    subtitle: "Dùng ChatGPT trong học tập, công việc và kinh doanh.",
    category: "prompt",
    audience: "Học viên, nhân sự văn phòng",
    level: "Cơ bản đến trung cấp",
    format: "Online / Hybrid",
    duration: "8 giờ",
    sessions: "4 buổi",
    lessons: 14,
    students: 612,
    rating: 4.8,
    price: "1.490.000đ",
    priceAmountVnd: 1490000,
    accessType: "paid",
    ctaLabel: "Thanh toán để học",
    flowNote: "Thanh toán xong hệ thống mở quyền học trên portal.",
    badge: "Phổ biến",
    description:
      "Lộ trình giúp học viên dùng ChatGPT có phương pháp: đặt yêu cầu, kiểm soát chất lượng, tái sử dụng prompt và áp dụng vào các tình huống thật.",
    outcomes: [
      "Viết prompt cho học tập, báo cáo, email và content",
      "Tạo prompt template có thể dùng lại",
      "Biết kiểm chứng, chỉnh sửa và cải thiện kết quả",
      "Ứng dụng ChatGPT vào workflow cá nhân",
    ],
    tools: ["ChatGPT", "Custom GPT", "Canva AI", "Google Workspace"],
    curriculum: [
      {
        title: "Nền tảng ChatGPT",
        lessons: ["Cách ChatGPT xử lý yêu cầu", "Sai lầm thường gặp", "Prompt theo vai trò"],
      },
      {
        title: "Prompt Engineering",
        lessons: ["Prompt theo mục tiêu", "Prompt nhiều bước", "Checklist đánh giá đầu ra"],
      },
      {
        title: "Workflow ứng dụng",
        lessons: ["Viết và biên tập", "Nghiên cứu và tóm tắt", "Lập kế hoạch và báo cáo"],
      },
    ],
  },
  {
    slug: "khoa-hoc-ai-co-ban",
    title: "Khóa học AI Cơ Bản",
    subtitle: "Lộ trình nhập môn ngắn gọn cho người bận rộn.",
    category: "foundation",
    audience: "Người mới, phụ huynh, sinh viên",
    level: "Cơ bản",
    format: "Online",
    duration: "6 giờ",
    sessions: "3 buổi",
    lessons: 10,
    students: 288,
    rating: 4.7,
    price: "Miễn phí",
    priceAmountVnd: 0,
    accessType: "free_funnel",
    ctaLabel: "Học miễn phí",
    flowNote: "Khóa phễu miễn phí: đăng ký xong học ngay.",
    badge: "Tinh gọn",
    description:
      "Phiên bản tinh gọn của AI nền tảng, phù hợp với người muốn hiểu nhanh AI và bắt đầu thực hành ngay trong vài buổi.",
    outcomes: [
      "Nắm khái niệm AI quan trọng",
      "Biết các nhóm công cụ AI phổ biến",
      "Thực hành prompt căn bản",
      "Có lộ trình học tiếp theo",
    ],
    tools: ["ChatGPT", "Gemini", "Perplexity"],
    curriculum: [
      { title: "Hiểu AI nhanh", lessons: ["AI trong đời sống", "Công cụ nên biết", "Rủi ro và kiểm chứng"] },
      { title: "Thực hành prompt", lessons: ["Prompt hỏi đáp", "Prompt tạo nội dung", "Prompt học tập"] },
      { title: "Lộ trình tiếp theo", lessons: ["Chọn khóa phù hợp", "Thiết kế thói quen học", "Theo dõi tiến độ"] },
    ],
  },
  {
    slug: "khoa-hoc-ai-thuc-chien",
    title: "Khóa học AI Thực Chiến",
    subtitle: "Bài tập thật, workflow thật, sản phẩm đầu ra rõ.",
    category: "work",
    audience: "Người đi làm",
    level: "Trung cấp",
    format: "Workshop",
    duration: "16 giờ",
    sessions: "8 buổi",
    lessons: 24,
    students: 356,
    rating: 4.9,
    price: "2.990.000đ",
    priceAmountVnd: 2990000,
    accessType: "paid",
    ctaLabel: "Thanh toán để học",
    flowNote: "Cần hoàn tất thanh toán trước khi mở quyền học.",
    badge: "Thực hành nhiều",
    description:
      "Khóa học tập trung vào việc biến AI thành công cụ làm việc: viết, tóm tắt, phân tích, lập kế hoạch, báo cáo và tự động hóa tác vụ lặp lại.",
    outcomes: [
      "Tạo workflow cá nhân bằng AI",
      "Rút ngắn thời gian viết báo cáo và tài liệu",
      "Phân tích dữ liệu đơn giản bằng prompt",
      "Tạo bộ prompt dùng lại cho công việc",
    ],
    tools: ["ChatGPT", "Gemini", "Sheets", "Automation AI"],
    curriculum: [
      { title: "Workflow cá nhân", lessons: ["Bản đồ công việc", "Chọn điểm dùng AI", "Prompt theo quy trình"] },
      { title: "Tạo đầu ra công việc", lessons: ["Báo cáo", "Content", "Email", "Slide"] },
      { title: "Tối ưu và đo lường", lessons: ["Checklist chất lượng", "Chuẩn hóa prompt", "Bộ công cụ cá nhân"] },
    ],
  },
  {
    slug: "khoa-hoc-ai-online",
    title: "Khóa học AI Online",
    subtitle: "Học linh hoạt với LMS, video, quiz và mentor hỗ trợ.",
    category: "foundation",
    audience: "Học viên ở xa, người bận lịch",
    level: "Cơ bản",
    format: "Online",
    duration: "10 giờ",
    sessions: "Tự học + mentor",
    lessons: 20,
    students: 502,
    rating: 4.8,
    price: "Miễn phí",
    priceAmountVnd: 0,
    accessType: "free_funnel",
    ctaLabel: "Học miễn phí",
    flowNote: "Đăng ký nhanh để vào LMS học thử ngay.",
    badge: "Online",
    description:
      "Lộ trình online có video, tài liệu, quiz và hệ thống theo dõi tiến độ để học viên học linh hoạt nhưng vẫn có định hướng.",
    outcomes: [
      "Học theo video và tài liệu có cấu trúc",
      "Làm quiz để kiểm tra hiểu bài",
      "Theo dõi tiến độ qua portal",
      "Được gợi ý lộ trình học tiếp",
    ],
    tools: ["DAYAI LMS", "ChatGPT", "Gemini", "Quiz Portal"],
    curriculum: [
      { title: "Học nền tảng", lessons: ["Video nhập môn", "Tài liệu đọc nhanh", "Quiz kiểm tra"] },
      { title: "Thực hành", lessons: ["Bài tập prompt", "Bài tập công cụ", "Bài nộp mentor"] },
      { title: "Theo dõi tiến độ", lessons: ["Dashboard học viên", "Nhận xét mentor", "Chứng chỉ"] },
    ],
  },
  {
    slug: "lich-khai-giang",
    title: "Lịch khai giảng",
    subtitle: "Các lớp sắp mở và hình thức học phù hợp.",
    category: "enterprise",
    audience: "Cá nhân và doanh nghiệp",
    level: "Theo lớp",
    format: "Online / Offline / In-house",
    duration: "Theo khóa",
    sessions: "Theo lịch",
    lessons: 12,
    students: 180,
    rating: 4.8,
    price: "Liên hệ tư vấn",
    priceAmountVnd: 0,
    accessType: "consultation",
    ctaLabel: "Nhận tư vấn",
    flowNote: "DAYAI xác nhận lịch khai giảng và lớp phù hợp.",
    badge: "Lớp mới",
    description:
      "Trang tổng hợp các lớp sắp khai giảng, phù hợp cho học viên cá nhân, phụ huynh hoặc doanh nghiệp cần đào tạo theo nhóm.",
    outcomes: [
      "Chọn lớp theo lịch rảnh",
      "Đăng ký tư vấn trước khi học",
      "Có lựa chọn online, offline hoặc in-house",
      "Theo dõi lịch qua portal",
    ],
    tools: ["DAYAI Portal", "LMS", "Quiz", "Mentor"],
    curriculum: [
      { title: "Lớp cá nhân", lessons: ["AI Căn Bản", "Prompt Engineering", "AI Cho Công Việc"] },
      { title: "Lớp doanh nghiệp", lessons: ["Khảo sát nhu cầu", "Workshop phòng ban", "Báo cáo HR"] },
      { title: "Theo dõi sau đăng ký", lessons: ["Xác nhận lịch", "Thông báo lớp", "Portal học viên"] },
    ],
  },
];

export function findCatalogCourse(slug: string) {
  return catalogCourses.find((course) => course.slug === slug);
}

export function getCategoryLabel(category: CourseCategory) {
  return courseCategories.find((item) => item.key === category)?.label ?? "Khóa học";
}

export function getAccessFlowLabel(accessType: CourseAccessType) {
  return {
    free_funnel: "Học miễn phí",
    paid: "Thanh toán rồi học",
    consultation: "Tư vấn trước",
  }[accessType];
}
