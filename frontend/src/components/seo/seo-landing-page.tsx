import Link from "next/link";
import { CSSProperties } from "react";
import { LeadForm } from "@/components/lead-form";
import { PublicSiteFooter } from "@/components/public-site-footer";
import { PublicSiteHeader } from "@/components/public-site-header";
import { getPagesByGroup, menuGroups, SitePage } from "@/lib/site-map";

type ExperienceKind = "kids" | "student" | "work" | "business" | "enterprise" | "resource" | "news" | "conversion" | "course";

type ExperienceProfile = {
  kind: ExperienceKind;
  accent: string;
  eyebrow: string;
  heroTitle?: string;
  heroDescription?: string;
  visualLabel: string;
  visualTitle: string;
  primaryCta: string;
  secondaryCta: string;
  stats: Array<{ value: string; label: string }>;
  outcomes: Array<{ title: string; description: string }>;
  journey: Array<{ title: string; description: string }>;
  proof: Array<{ label: string; value: string }>;
  ctaTitle: string;
  ctaDescription: string;
};

const profiles: Record<ExperienceKind, ExperienceProfile> = {
  kids: {
    kind: "kids",
    accent: "var(--dayai-secondary)",
    eyebrow: "AI Kids",
    heroTitle: "Học AI an toàn qua sáng tạo, kể chuyện và dự án nhỏ.",
    heroDescription:
      "Trang dành cho phụ huynh muốn con làm quen AI đúng cách: biết đặt câu hỏi, tạo sản phẩm sáng tạo, hiểu giới hạn công nghệ và có người lớn đồng hành.",
    visualLabel: "Creative classroom",
    visualTitle: "Mỗi buổi học là một dự án nhỏ có thể trình bày",
    primaryCta: "Tư vấn lộ trình cho con",
    secondaryCta: "Xem khóa AI Kids",
    stats: [
      { value: "3", label: "tầng an toàn" },
      { value: "1:1", label: "phụ huynh theo dõi" },
      { value: "Dự án", label: "học qua làm" },
    ],
    outcomes: [
      { title: "Tò mò có kiểm soát", description: "Trẻ biết hỏi AI, kiểm chứng câu trả lời và không phụ thuộc máy móc." },
      { title: "Sáng tạo có sản phẩm", description: "Mỗi chặng học có tranh, truyện, bài trình bày hoặc ý tưởng nhỏ để chia sẻ." },
      { title: "Phụ huynh nhìn thấy tiến bộ", description: "Portal giúp theo dõi lịch học, nhận xét, bài học và chứng chỉ." },
    ],
    journey: [
      { title: "Khởi động an toàn", description: "Quy tắc dùng AI, bảo vệ thông tin cá nhân, hiểu AI có thể sai." },
      { title: "Tạo nội dung", description: "Kể chuyện, hình ảnh, thuyết trình ngắn và cách diễn đạt ý tưởng." },
      { title: "Chia sẻ cùng gia đình", description: "Trẻ trình bày sản phẩm, phụ huynh nhận gợi ý đồng hành tại nhà." },
    ],
    proof: [
      { label: "Không gian", value: "nhẹ, vui, an toàn" },
      { label: "Đầu ra", value: "sản phẩm nhỏ" },
      { label: "Theo dõi", value: "portal phụ huynh" },
    ],
    ctaTitle: "Muốn biết con nên bắt đầu từ đâu?",
    ctaDescription: "DAYAI sẽ gợi ý lộ trình theo độ tuổi, khả năng đọc hiểu, mức độ tự học và mục tiêu của gia đình.",
  },
  student: {
    kind: "student",
    accent: "var(--dayai-warning)",
    eyebrow: "AI Student",
    heroTitle: "Biến AI thành trợ lý học tập, không phải lối tắt làm bài.",
    heroDescription:
      "Lộ trình cho học sinh, sinh viên muốn học hiệu quả hơn: ghi chú, ôn tập, làm slide, học tiếng Anh, nghiên cứu và định hướng nghề nghiệp có trách nhiệm.",
    visualLabel: "Study cockpit",
    visualTitle: "Từ mục tiêu học tập đến bài nộp có chất lượng",
    primaryCta: "Tư vấn lộ trình học tập",
    secondaryCta: "Xem khóa cho học viên",
    stats: [
      { value: "4", label: "workflow học" },
      { value: "Quiz", label: "tự kiểm tra" },
      { value: "Portfolio", label: "đầu ra cá nhân" },
    ],
    outcomes: [
      { title: "Học sâu hơn", description: "Dùng AI để đặt câu hỏi, tóm tắt, phản biện và tự kiểm tra hiểu bài." },
      { title: "Làm bài có trách nhiệm", description: "Biết phân biệt hỗ trợ học tập với sao chép, biết ghi nguồn và kiểm chứng." },
      { title: "Chuẩn bị nghề nghiệp", description: "Xây CV, portfolio, kế hoạch kỹ năng và thói quen tự học dài hạn." },
    ],
    journey: [
      { title: "Thiết lập trợ lý học tập", description: "Prompt ghi chú, ôn tập, flashcard, giải thích theo cấp độ." },
      { title: "Tạo sản phẩm học tập", description: "Slide, báo cáo, bài thuyết trình và checklist chất lượng." },
      { title: "Đo tiến bộ", description: "Quiz, lịch sử học, lesson progress và gợi ý bước tiếp theo." },
    ],
    proof: [
      { label: "Phù hợp", value: "THCS, THPT, đại học" },
      { label: "Kỹ năng", value: "học, viết, trình bày" },
      { label: "Định hướng", value: "ngành nghề" },
    ],
    ctaTitle: "Học AI để học tốt hơn, không học cho có phong trào.",
    ctaDescription: "Đội ngũ DAYAI sẽ giúp chọn lộ trình theo môn học, mục tiêu thi cử, kỹ năng trình bày hoặc định hướng nghề.",
  },
  work: {
    kind: "work",
    accent: "var(--dayai-success)",
    eyebrow: "AI Work",
    heroTitle: "Xây workflow AI cho công việc hằng ngày, đo được bằng đầu ra.",
    heroDescription:
      "Dành cho người đi làm muốn viết nhanh hơn, phân tích tốt hơn, xử lý email/báo cáo/content rõ hơn và giảm việc lặp lại bằng workflow AI thực tế.",
    visualLabel: "Productivity desk",
    visualTitle: "Prompt, dữ liệu, báo cáo và automation trong một luồng",
    primaryCta: "Tư vấn workflow cá nhân",
    secondaryCta: "Xem khóa AI Work",
    stats: [
      { value: "8", label: "workflow mẫu" },
      { value: "24", label: "prompt tình huống" },
      { value: "LMS", label: "học lại bất kỳ lúc nào" },
    ],
    outcomes: [
      { title: "Viết và tổng hợp nhanh", description: "Email, báo cáo, biên bản, proposal và nội dung nội bộ rõ ràng hơn." },
      { title: "Ra quyết định tốt hơn", description: "Dùng AI để đọc dữ liệu, tìm insight, đặt giả thuyết và chuẩn bị phương án." },
      { title: "Chuẩn hóa công việc", description: "Biến tác vụ lặp lại thành checklist, prompt template và quy trình có thể dùng lại." },
    ],
    journey: [
      { title: "Audit công việc", description: "Tìm điểm nghẽn, tác vụ lặp lại và loại đầu ra cần cải thiện." },
      { title: "Thiết kế prompt/workflow", description: "Viết prompt theo vai trò, dữ liệu đầu vào, tiêu chí đầu ra và vòng kiểm tra." },
      { title: "Đóng gói để dùng hằng ngày", description: "Lưu template, đo thời gian tiết kiệm và nâng chất lượng đầu ra." },
    ],
    proof: [
      { label: "Ứng dụng", value: "report, content, email" },
      { label: "Phong cách", value: "thực chiến" },
      { label: "Kết quả", value: "workflow cá nhân" },
    ],
    ctaTitle: "Bạn đang mất thời gian ở phần nào của công việc?",
    ctaDescription: "DAYAI sẽ tư vấn theo vai trò: sales, marketing, vận hành, HR, kế toán, quản lý hoặc chuyên viên văn phòng.",
  },
  business: {
    kind: "business",
    accent: "var(--dayai-accent)",
    eyebrow: "AI Business",
    heroTitle: "Đưa AI vào bán hàng, marketing, CSKH và vận hành doanh nghiệp.",
    heroDescription:
      "Trang cho chủ doanh nghiệp và quản lý muốn hiểu AI ở mức ứng dụng: chọn use case đúng, đào tạo đội ngũ, đo hiệu quả và tránh triển khai theo phong trào.",
    visualLabel: "Business AI board",
    visualTitle: "Từ use case đến quy trình vận hành có chỉ số",
    primaryCta: "Tư vấn use case AI",
    secondaryCta: "Xem giải pháp",
    stats: [
      { value: "CRM", label: "lead và chăm sóc" },
      { value: "BI", label: "báo cáo" },
      { value: "Ops", label: "vận hành" },
    ],
    outcomes: [
      { title: "Chọn đúng điểm bắt đầu", description: "Ưu tiên use case có dữ liệu, người phụ trách và chỉ số đo được." },
      { title: "Đội ngũ dùng cùng chuẩn", description: "Prompt, checklist và template giúp giảm lệch chất lượng giữa các nhân sự." },
      { title: "Gắn với tăng trưởng", description: "AI phục vụ lead, content, CSKH, tri thức nội bộ và báo cáo quản trị." },
    ],
    journey: [
      { title: "Khảo sát use case", description: "Bản đồ quy trình, dữ liệu hiện có và tác vụ có thể tăng tốc bằng AI." },
      { title: "Workshop triển khai", description: "Đào tạo theo tình huống thật của sales, marketing, CSKH hoặc vận hành." },
      { title: "Chuẩn hóa sau đào tạo", description: "Đóng gói prompt, checklist, báo cáo và kế hoạch duy trì." },
    ],
    proof: [
      { label: "Cho ai", value: "owner, manager" },
      { label: "Trọng tâm", value: "use case thực tế" },
      { label: "Đầu ra", value: "playbook AI" },
    ],
    ctaTitle: "Đừng bắt đầu bằng công cụ, hãy bắt đầu bằng bài toán kinh doanh.",
    ctaDescription: "DAYAI có thể giúp bóc tách quy trình và đề xuất lộ trình AI phù hợp với nguồn lực hiện tại.",
  },
  enterprise: {
    kind: "enterprise",
    accent: "var(--dayai-primary)",
    eyebrow: "AI Enterprise",
    heroTitle: "Đào tạo AI theo phòng ban, có LMS, quiz và báo cáo cho HR/L&D.",
    heroDescription:
      "Dành cho doanh nghiệp cần triển khai năng lực AI có kiểm soát: nội dung theo vai trò, lớp học theo nhóm, theo dõi tiến độ và báo cáo sau đào tạo.",
    visualLabel: "Enterprise learning OS",
    visualTitle: "Bản đồ năng lực AI theo phòng ban và tiến độ học",
    primaryCta: "Tư vấn đào tạo doanh nghiệp",
    secondaryCta: "Xem LMS doanh nghiệp",
    stats: [
      { value: "5", label: "phòng ban mẫu" },
      { value: "HR", label: "portal theo dõi" },
      { value: "KPI", label: "báo cáo tiến độ" },
    ],
    outcomes: [
      { title: "Nội dung theo vai trò", description: "Sales, marketing, HR, CSKH, vận hành học cùng nền tảng nhưng khác bài tập." },
      { title: "Quản trị được tiến độ", description: "HR nhìn thấy lớp, học viên, bài học, quiz, chứng chỉ và tình trạng hoàn thành." },
      { title: "Triển khai có kiểm soát", description: "Có nguyên tắc dữ liệu, bảo mật, kiểm chứng đầu ra và chuẩn dùng AI nội bộ." },
    ],
    journey: [
      { title: "Khảo sát năng lực", description: "Phân nhóm vai trò, mục tiêu, mức độ sẵn sàng và rủi ro dữ liệu." },
      { title: "Đào tạo theo phòng ban", description: "Workshop và LMS theo use case riêng, có bài tập và quiz." },
      { title: "Báo cáo sau đào tạo", description: "Tổng hợp tiến độ, điểm quiz, mức tham gia và đề xuất giai đoạn tiếp theo." },
    ],
    proof: [
      { label: "Đối tượng", value: "HR, L&D, quản lý" },
      { label: "Theo dõi", value: "LMS + portal" },
      { label: "Báo cáo", value: "progress + quiz" },
    ],
    ctaTitle: "Cần đào tạo AI cho đội ngũ nhưng chưa biết thiết kế chương trình?",
    ctaDescription: "DAYAI sẽ tư vấn theo quy mô nhân sự, phòng ban ưu tiên, dữ liệu nội bộ và mục tiêu đào tạo.",
  },
  resource: {
    kind: "resource",
    accent: "var(--dayai-accent)",
    eyebrow: "Tài nguyên AI",
    heroTitle: "Tài nguyên không chỉ để đọc, mà để áp dụng ngay vào việc thật.",
    heroDescription:
      "Checklist, ebook, prompt mẫu và playbook được thiết kế như bước đệm: đọc nhanh, làm thử, rồi đi tiếp vào khóa học hoặc tư vấn nếu cần.",
    visualLabel: "Resource lab",
    visualTitle: "Checklist, prompt và playbook được phân lớp theo mục tiêu",
    primaryCta: "Nhận bộ tài nguyên phù hợp",
    secondaryCta: "Xem khóa học AI",
    stats: [
      { value: "10'", label: "đọc nhanh" },
      { value: "3", label: "bước áp dụng" },
      { value: "PDF", label: "dễ chia sẻ" },
    ],
    outcomes: [
      { title: "Chọn đúng tài liệu", description: "Phụ huynh, sinh viên, người đi làm và doanh nghiệp có checklist riêng." },
      { title: "Có hướng dẫn sử dụng", description: "Mỗi tài nguyên gợi ý tình huống áp dụng, lỗi thường gặp và bước tiếp theo." },
      { title: "Nối với lộ trình học", description: "Tài nguyên miễn phí giúp người học tự đánh giá nhu cầu trước khi chọn khóa." },
    ],
    journey: [
      { title: "Đọc để định hướng", description: "Nắm khái niệm, rủi ro và cách chọn công cụ phù hợp." },
      { title: "Thử một prompt", description: "Áp dụng ngay với bài học, công việc, bán hàng hoặc quản trị." },
      { title: "Lưu thành thói quen", description: "Biến checklist thành quy trình nhỏ có thể lặp lại." },
    ],
    proof: [
      { label: "Loại", value: "checklist, ebook" },
      { label: "Ứng dụng", value: "học và làm" },
      { label: "Tiếp nối", value: "khóa học" },
    ],
    ctaTitle: "Bạn cần tài nguyên cho học tập, công việc hay doanh nghiệp?",
    ctaDescription: "Chọn đúng nhóm tài nguyên sẽ giúp tiết kiệm thời gian và tránh dùng AI theo kiểu thử mò.",
  },
  news: {
    kind: "news",
    accent: "var(--dayai-warning)",
    eyebrow: "Cập nhật AI",
    heroTitle: "Tin AI được biên tập để hiểu tác động, không chỉ đọc cho biết.",
    heroDescription:
      "DAYAI theo dõi xu hướng, công cụ, chính sách và ứng dụng AI trong giáo dục/kinh doanh để người học biết điều gì đáng thử, điều gì nên chờ.",
    visualLabel: "AI newsroom",
    visualTitle: "Từ tin mới đến góc nhìn ứng dụng cho người học",
    primaryCta: "Tư vấn xu hướng phù hợp",
    secondaryCta: "Xem tài nguyên AI",
    stats: [
      { value: "Trend", label: "xu hướng" },
      { value: "Tool", label: "công cụ" },
      { value: "Impact", label: "tác động" },
    ],
    outcomes: [
      { title: "Tin có ngữ cảnh", description: "Mỗi cập nhật cần trả lời: ai bị ảnh hưởng, dùng để làm gì, rủi ro nào cần biết." },
      { title: "Gợi ý áp dụng", description: "Tin công cụ mới được nối với tình huống học tập, công việc hoặc doanh nghiệp." },
      { title: "Không chạy theo hype", description: "Ưu tiên khả năng ứng dụng, độ an toàn và giá trị thực tế." },
    ],
    journey: [
      { title: "Nắm điểm mới", description: "Tóm tắt thay đổi quan trọng của công cụ, mô hình hoặc chính sách." },
      { title: "Đánh giá tác động", description: "Tác động với học sinh, người đi làm, doanh nghiệp hoặc đào tạo." },
      { title: "Chọn hành động", description: "Thử ngay, đưa vào tài nguyên, cập nhật khóa học hoặc chỉ theo dõi." },
    ],
    proof: [
      { label: "Góc nhìn", value: "giáo dục + business" },
      { label: "Nhịp", value: "cập nhật chọn lọc" },
      { label: "Mục tiêu", value: "ứng dụng đúng" },
    ],
    ctaTitle: "Muốn biết xu hướng nào đáng đưa vào học tập hoặc vận hành?",
    ctaDescription: "DAYAI có thể giúp lọc xu hướng theo mục tiêu thật thay vì chạy theo công cụ mới mỗi tuần.",
  },
  conversion: {
    kind: "conversion",
    accent: "var(--dayai-primary)",
    eyebrow: "Tư vấn lộ trình",
    heroTitle: "Kể mục tiêu của bạn, DAYAI sẽ gợi ý lộ trình học AI phù hợp.",
    heroDescription:
      "Form tư vấn được thiết kế để hiểu đúng đối tượng, mục tiêu, lịch học và nhu cầu triển khai. Sau đó đội ngũ DAYAI phản hồi bằng lộ trình cụ thể.",
    visualLabel: "Advisor desk",
    visualTitle: "Từ nhu cầu ban đầu đến lộ trình học có thể triển khai",
    primaryCta: "Gửi thông tin tư vấn",
    secondaryCta: "Xem khóa học AI",
    stats: [
      { value: "24h", label: "phản hồi" },
      { value: "1:1", label: "định hướng" },
      { value: "AI", label: "lộ trình" },
    ],
    outcomes: [
      { title: "Tư vấn theo người học", description: "Kids, student, người đi làm, chủ doanh nghiệp và HR có cách tư vấn khác nhau." },
      { title: "Rõ bước tiếp theo", description: "Sau form là gợi ý khóa, lịch học, tài nguyên hoặc cuộc gọi tư vấn sâu." },
      { title: "Không ép chọn khóa", description: "Ưu tiên hiểu mục tiêu trước, rồi mới đề xuất hình thức học phù hợp." },
    ],
    journey: [
      { title: "Gửi nhu cầu", description: "Mục tiêu học, nhóm người học, lịch học và vấn đề đang cần giải quyết." },
      { title: "Phân loại lộ trình", description: "DAYAI chọn nhóm phù hợp và gợi ý bước bắt đầu." },
      { title: "Theo dõi sau đăng ký", description: "Lead đi vào CRM để tư vấn viên chăm sóc và cập nhật trạng thái." },
    ],
    proof: [
      { label: "Phù hợp", value: "cá nhân + doanh nghiệp" },
      { label: "Tốc độ", value: "phản hồi nhanh" },
      { label: "Đầu ra", value: "lộ trình đề xuất" },
    ],
    ctaTitle: "Bạn không cần tự đoán khóa nào phù hợp.",
    ctaDescription: "Điền form, DAYAI sẽ giúp chọn lộ trình dựa trên mục tiêu và bối cảnh học thật.",
  },
  course: {
    kind: "course",
    accent: "var(--dayai-primary)",
    eyebrow: "Khóa học AI",
    heroTitle: "Học AI theo lộ trình có LMS, quiz và mentor đồng hành.",
    heroDescription:
      "Mỗi trang khóa học giúp người học hiểu mục tiêu, đầu ra, bài học, cách theo dõi tiến độ và bước đăng ký phù hợp.",
    visualLabel: "Learning path",
    visualTitle: "Từ mục tiêu đến bài học, quiz và tiến độ",
    primaryCta: "Nhận tư vấn khóa học",
    secondaryCta: "Xem danh sách khóa",
    stats: [
      { value: "LMS", label: "bài học" },
      { value: "Quiz", label: "kiểm tra" },
      { value: "Portal", label: "tiến độ" },
    ],
    outcomes: [
      { title: "Rõ mục tiêu học", description: "Biết học để làm gì, cần chuẩn bị gì và đầu ra sau khóa là gì." },
      { title: "Có bài tập thực hành", description: "Không chỉ xem video, người học có prompt, checklist và bài thực tế." },
      { title: "Theo dõi được tiến bộ", description: "Portal hiển thị bài học, quiz, thanh toán và chứng chỉ." },
    ],
    journey: [
      { title: "Chọn mục tiêu", description: "Xác định nhóm người học và bài toán cần AI hỗ trợ." },
      { title: "Học qua thực hành", description: "Video, tài liệu, prompt mẫu và bài tập." },
      { title: "Đo kết quả", description: "Quiz, tiến độ LMS và gợi ý bước tiếp theo." },
    ],
    proof: [
      { label: "Hình thức", value: "online/hybrid" },
      { label: "Theo dõi", value: "portal" },
      { label: "Đầu ra", value: "workflow" },
    ],
    ctaTitle: "Muốn chọn khóa học AI phù hợp?",
    ctaDescription: "DAYAI sẽ tư vấn theo mục tiêu học tập, công việc hoặc nhu cầu triển khai của doanh nghiệp.",
  },
};

export function SeoLandingPage({ page }: { page: SitePage }) {
  const profile = getExperienceProfile(page);
  const relatedPages = getRelatedPages(page);
  const isConversionPage = page.path === "/dang-ky-tu-van/" || page.path === "/lien-he/";
  const heroTitle = profile.heroTitle ?? page.title;
  const heroDescription = profile.heroDescription ?? page.description;

  return (
    <main className="min-h-screen bg-[var(--dayai-bg-subtle)] text-[var(--dayai-text)]">
      <PublicSiteHeader />

      <section className="relative isolate overflow-hidden border-b border-[var(--dayai-border)] bg-white">
        <div className="absolute inset-0 dayai-muted-grid opacity-60" />
        <div className="absolute inset-x-0 top-0 h-40 bg-white" />

        <div className="dayai-container relative grid gap-12 py-16 lg:grid-cols-[0.92fr_1.08fr] lg:items-center lg:py-24">
          <div className="animate-fade-rise">
            <div className="dayai-chip">{profile.eyebrow}</div>
            <h1 className="mt-6 max-w-5xl text-4xl font-black leading-[1.06] text-[var(--dayai-text)] sm:text-6xl">
              {heroTitle}
            </h1>
            <p className="mt-6 max-w-3xl text-base leading-8 text-[var(--dayai-text-muted)] sm:text-lg">
              {heroDescription}
            </p>
            <div className="mt-8 flex flex-col gap-3 sm:flex-row">
              <Link href={isConversionPage ? "#lead-form" : "/dang-ky-tu-van/"} className="dayai-btn dayai-btn-primary">
                {profile.primaryCta}
              </Link>
              <Link href={profile.kind === "resource" || profile.kind === "news" ? "/tai-nguyen/" : "/khoa-hoc/"} className="dayai-btn dayai-btn-secondary">
                {profile.secondaryCta}
              </Link>
            </div>

            <div className="mt-8 grid gap-2 sm:grid-cols-3">
              {profile.stats.map((stat) => (
                <div key={stat.label} className="rounded-[var(--dayai-radius-lg)] border border-[var(--dayai-border)] bg-white p-4 shadow-[var(--dayai-shadow-xs)]">
                  <div className="text-lg font-black text-[var(--dayai-text)]">{stat.value}</div>
                  <div className="mt-1 text-xs font-bold text-[var(--dayai-text-muted)]">{stat.label}</div>
                </div>
              ))}
            </div>
          </div>

          {isConversionPage ? (
            <div className="animate-fade-rise-delay">
              <LeadForm campaign={page.path.includes("lien-he") ? "contact_page" : "consultation_page"} />
            </div>
          ) : (
            <ExperienceVisual profile={profile} />
          )}
        </div>
      </section>

      <section className="dayai-section bg-white">
        <div className="dayai-container grid gap-10 lg:grid-cols-[0.82fr_1.18fr] lg:items-start">
          <SectionIntro
            eyebrow="Trải nghiệm người dùng"
            title={sectionTitleFor(profile)}
            description="Mỗi nhóm trang được viết và trình bày theo một hành trình riêng: người đọc hiểu mình thuộc nhóm nào, thấy bài toán thật, hình dung được cách học và biết bước tiếp theo."
          />
          <div className="grid gap-4 md:grid-cols-3">
            {profile.outcomes.map((outcome, index) => (
              <article key={outcome.title} className="dayai-card dayai-lift p-6">
                <div
                  className="grid size-11 place-items-center rounded-[var(--dayai-radius-full)] text-sm font-black text-white"
                  style={{ background: profile.accent }}
                >
                  {index + 1}
                </div>
                <h2 className="mt-6 text-xl font-black leading-tight">{outcome.title}</h2>
                <p className="mt-3 text-sm leading-7 text-[var(--dayai-text-muted)]">{outcome.description}</p>
              </article>
            ))}
          </div>
        </div>
      </section>

      <section className="dayai-section">
        <div className="dayai-container grid gap-10 lg:grid-cols-[1.05fr_0.95fr] lg:items-center">
          <div className="dayai-card overflow-hidden p-4 shadow-[var(--dayai-shadow-sm)]">
            <div className="dayai-dark rounded-[var(--dayai-radius-xl)] bg-[var(--dayai-bg)] p-6 text-[var(--dayai-text)]">
              <div className="text-xs font-black uppercase" style={{ color: profile.accent }}>
                Journey map
              </div>
              <div className="mt-4 grid gap-4">
                {profile.journey.map((step, index) => (
                  <div key={step.title} className="grid grid-cols-[auto_1fr] gap-4 rounded-[var(--dayai-radius-lg)] bg-white/[0.06] p-4">
                    <div className="relative">
                      <div className="grid size-10 place-items-center rounded-full bg-white text-sm font-black" style={{ color: profile.accent }}>
                        {index + 1}
                      </div>
                    </div>
                    <div>
                      <h3 className="text-base font-black text-[var(--dayai-text)]">{step.title}</h3>
                      <p className="mt-2 text-sm leading-6 text-[var(--dayai-text-muted)]">{step.description}</p>
                    </div>
                  </div>
                ))}
              </div>
            </div>
          </div>

          <div>
            <SectionIntro
              eyebrow="Nội dung chuyên sâu"
              title={deepTitleFor(profile)}
              description={deepDescriptionFor(profile)}
            />
            <div className="mt-8 grid gap-3">
              {profile.proof.map((item) => (
                <div key={item.label} className="flex items-center justify-between gap-4 rounded-[var(--dayai-radius-lg)] border border-[var(--dayai-border)] bg-white p-4">
                  <span className="text-sm font-bold text-[var(--dayai-text-muted)]">{item.label}</span>
                  <span className="text-sm font-black text-[var(--dayai-text)]">{item.value}</span>
                </div>
              ))}
            </div>
          </div>
        </div>
      </section>

      <section className="dayai-section bg-white">
        <div className="dayai-container grid gap-10 lg:grid-cols-[0.78fr_1.22fr] lg:items-start">
          <SectionIntro
            eyebrow="Đi tiếp"
            title={`Nội dung liên quan trong nhóm ${page.group}.`}
            description="Các trang liên quan được đặt như những điểm rẽ trong hành trình: đọc thêm, chọn khóa, xem giải pháp hoặc để lại nhu cầu tư vấn."
          />
          <div className="grid gap-4 md:grid-cols-2">
            {relatedPages.slice(0, 6).map((relatedPage) => (
              <Link
                key={relatedPage.path}
                href={relatedPage.path}
                className="dayai-card dayai-lift group p-6"
              >
                <div className="text-sm font-black" style={{ color: profile.accent }}>
                  {relatedPage.group}
                </div>
                <h3 className="mt-3 text-xl font-black leading-tight group-hover:text-[var(--dayai-primary)]">
                  {relatedPage.title}
                </h3>
                <p className="mt-3 line-clamp-3 text-sm leading-6 text-[var(--dayai-text-muted)]">
                  {relatedPage.description}
                </p>
              </Link>
            ))}
          </div>
        </div>
      </section>

      <section className="bg-white pb-24">
        <div
          className="dayai-container grid gap-8 rounded-[var(--dayai-radius-2xl)] p-6 text-white shadow-[var(--dayai-shadow-md)] sm:p-8 lg:grid-cols-[0.9fr_1.1fr] lg:p-12"
          style={{ background: profile.accent }}
        >
          <div>
            <div className="text-xs font-black uppercase text-white/80">DAYAI advisor</div>
            <h2 className="mt-4 text-3xl font-black leading-tight sm:text-4xl">{profile.ctaTitle}</h2>
            <p className="mt-5 text-sm leading-7 text-white/90">{profile.ctaDescription}</p>
            {!isConversionPage ? (
              <Link href="/dang-ky-tu-van/" className="dayai-btn mt-8 bg-white text-[var(--dayai-primary)]">
                Nhận tư vấn lộ trình
              </Link>
            ) : null}
          </div>

          <div className="grid gap-3 self-end sm:grid-cols-2">
            {profile.journey.concat(profile.outcomes).slice(0, 4).map((item) => (
              <div key={item.title} className="rounded-[var(--dayai-radius-lg)] border border-white/20 bg-white/10 p-4 text-sm font-bold text-white">
                {item.title}
              </div>
            ))}
          </div>
        </div>
      </section>

      <PublicSiteFooter />
    </main>
  );
}

function ExperienceVisual({ profile }: { profile: ExperienceProfile }) {
  const style = { "--experience-accent": profile.accent } as CSSProperties;

  return (
    <div
      style={style}
      className="animate-fade-rise-delay dayai-card relative overflow-hidden p-4 shadow-[var(--dayai-shadow-md)]"
    >
      <div className="absolute inset-x-8 top-0 h-px bg-[var(--experience-accent)]" />
      <div className="dayai-dark relative overflow-hidden rounded-[var(--dayai-radius-xl)] bg-[var(--dayai-bg)] p-6 text-[var(--dayai-text)]">
        <div className="absolute inset-0 opacity-20 dayai-muted-grid" />
        <div className="relative flex items-start justify-between gap-6">
          <div>
            <div className="text-xs font-black uppercase text-[var(--experience-accent)]">{profile.visualLabel}</div>
            <div className="mt-3 max-w-md text-2xl font-black leading-tight">{profile.visualTitle}</div>
          </div>
          <div className="grid size-12 shrink-0 place-items-center rounded-[var(--dayai-radius-lg)] bg-white text-[var(--experience-accent)]">
            <VisualIcon kind={profile.kind} />
          </div>
        </div>

        <div className="relative mt-8">
          <VisualScene profile={profile} />
        </div>
      </div>

      <div className="mt-4 grid grid-cols-3 gap-3">
        {profile.stats.map((stat) => (
          <div key={stat.label} className="rounded-[var(--dayai-radius-lg)] border border-[var(--dayai-border)] bg-[var(--dayai-bg-subtle)] p-4 text-center">
            <div className="mx-auto grid size-10 place-items-center rounded-[var(--dayai-radius-full)] bg-[var(--experience-accent)] text-xs font-black text-white">
              {stat.value.slice(0, 3)}
            </div>
            <div className="mt-3 text-xs font-black leading-5 text-[var(--dayai-text-muted)]">{stat.label}</div>
          </div>
        ))}
      </div>
    </div>
  );
}

function VisualScene({ profile }: { profile: ExperienceProfile }) {
  if (profile.kind === "resource" || profile.kind === "news") {
    return (
      <div className="grid gap-4">
        <div className="rounded-[var(--dayai-radius-lg)] bg-white/[0.06] p-4">
          <div className="flex items-center gap-2">
            <span className="h-2 flex-1 rounded-full bg-[var(--experience-accent)]" />
            <span className="h-2 w-16 rounded-full bg-white/20" />
            <span className="h-2 w-10 rounded-full bg-white/12" />
          </div>
          <div className="mt-5 grid gap-3">
            {profile.journey.map((item) => (
              <div key={item.title} className="flex items-center justify-between gap-4 rounded-[var(--dayai-radius-md)] bg-white/[0.06] px-4 py-3">
                <span className="text-sm font-semibold text-[var(--dayai-text-muted)]">{item.title}</span>
                <span className="rounded-[var(--dayai-radius-full)] bg-white px-3 py-1 text-xs font-black text-[var(--experience-accent)]">
                  {profile.kind === "news" ? "trend" : "guide"}
                </span>
              </div>
            ))}
          </div>
        </div>
        <div className="grid grid-cols-3 gap-3">
          {profile.proof.map((item) => (
            <div key={item.label} className="rounded-[var(--dayai-radius-md)] border border-white/10 bg-white/[0.04] p-3 text-center text-xs font-black text-[var(--dayai-text-muted)]">
              {item.label}
            </div>
          ))}
        </div>
      </div>
    );
  }

  if (profile.kind === "business" || profile.kind === "enterprise") {
    return (
      <div className="grid gap-4">
        {profile.outcomes.map((item, index) => (
          <div key={item.title} className="rounded-[var(--dayai-radius-lg)] bg-white/[0.06] p-4">
            <div className="flex items-center justify-between gap-4">
              <span className="text-sm font-black text-[var(--dayai-text-muted)]">{item.title}</span>
              <span className="text-xs font-black text-[var(--experience-accent)]">{74 + index * 8}%</span>
            </div>
            <div className="mt-3 h-2 rounded-full bg-white/10">
              <div className="dayai-progress-line h-2 rounded-full bg-[var(--experience-accent)]" style={{ width: `${66 + index * 11}%` }} />
            </div>
          </div>
        ))}
        <div className="grid grid-cols-2 gap-3">
          {["HR portal", "LMS report"].map((item) => (
            <div key={item} className="rounded-[var(--dayai-radius-md)] border border-white/10 bg-white/[0.04] p-3 text-center text-xs font-black text-[var(--dayai-text-muted)]">
              {item}
            </div>
          ))}
        </div>
      </div>
    );
  }

  return (
    <div className="grid gap-3">
      {profile.journey.map((item, index) => (
        <div key={item.title} className="grid grid-cols-[auto_1fr] items-center gap-4 rounded-[var(--dayai-radius-lg)] bg-white/[0.06] p-4">
          <div className="dayai-float-slow grid size-9 place-items-center rounded-full bg-white text-xs font-black text-[var(--experience-accent)]">
            {index + 1}
          </div>
          <div>
            <div className="text-sm font-black text-[var(--dayai-text-muted)]">{item.title}</div>
            <div className="mt-2 flex gap-2">
              <span className="dayai-progress-line h-1.5 flex-1 rounded-full bg-[var(--experience-accent)]" />
              <span className="h-1.5 w-12 rounded-full bg-white/15" />
              <span className="h-1.5 w-8 rounded-full bg-white/10" />
            </div>
          </div>
        </div>
      ))}
    </div>
  );
}

function VisualIcon({ kind }: { kind: ExperienceKind }) {
  if (kind === "resource" || kind === "news") {
    return (
      <svg aria-hidden="true" className="size-6" viewBox="0 0 24 24" fill="none">
        <path d="M6 5h12v14H6z" stroke="currentColor" strokeLinejoin="round" strokeWidth="2" />
        <path d="M9 9h6M9 12h6M9 15h3" stroke="currentColor" strokeLinecap="round" strokeWidth="2" />
      </svg>
    );
  }

  if (kind === "business" || kind === "enterprise") {
    return (
      <svg aria-hidden="true" className="size-6" viewBox="0 0 24 24" fill="none">
        <path d="M5 18V8M12 18V5M19 18v-7" stroke="currentColor" strokeLinecap="round" strokeWidth="2" />
        <path d="M4 18h16" stroke="currentColor" strokeLinecap="round" strokeWidth="2" />
      </svg>
    );
  }

  return (
    <svg aria-hidden="true" className="size-6" viewBox="0 0 24 24" fill="none">
      <path d="M4 16c4.4-.7 7.5-3.2 9.5-7.5M10 17c3.8-.5 6.8-2.4 9-5.7" stroke="currentColor" strokeLinecap="round" strokeWidth="1.8" />
      <path d="M14 8.5h4.5V13" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.8" />
    </svg>
  );
}

function SectionIntro({ eyebrow, title, description }: { eyebrow: string; title: string; description: string }) {
  return (
    <div className="max-w-3xl">
      <div className="dayai-kicker">{eyebrow}</div>
      <h2 className="mt-4 text-3xl font-black leading-tight sm:text-4xl">{title}</h2>
      <p className="mt-5 text-sm leading-7 text-[var(--dayai-text-muted)]">{description}</p>
    </div>
  );
}

function getExperienceProfile(page: SitePage) {
  const text = `${page.path} ${page.group} ${page.title}`.toLowerCase();

  if (text.includes("dang-ky-tu-van") || text.includes("lien-he")) return profiles.conversion;
  if (text.includes("ai-kids") || text.includes("trẻ")) return profiles.kids;
  if (text.includes("ai-student") || text.includes("sinh viên") || text.includes("học sinh")) return profiles.student;
  if (text.includes("ai-work") || text.includes("người đi làm") || text.includes("công việc")) return profiles.work;
  if (text.includes("ai-enterprise")) return profiles.enterprise;
  if (text.includes("ai-business") || text.includes("doanh nghiệp") || text.includes("giai-phap")) return profiles.business;
  if (text.includes("tin-tuc-ai") || text.includes("cập nhật ai")) return profiles.news;
  if (text.includes("tai-nguyen") || text.includes("prompt-ai") || text.includes("cam-nang-ai") || text.includes("case-study") || text.includes("công cụ")) return profiles.resource;

  return profiles.course;
}

function getRelatedPages(page: SitePage) {
  const sameGroup = getPagesByGroup(page.group).filter((relatedPage) => relatedPage.path !== page.path);

  if (sameGroup.length) {
    return sameGroup;
  }

  return menuGroups.flatMap((menu) => menu.children).filter((relatedPage) => relatedPage.path !== page.path);
}

function sectionTitleFor(profile: ExperienceProfile) {
  return {
    kids: "Một trải nghiệm học AI đủ vui cho trẻ và đủ an tâm cho phụ huynh.",
    student: "Một trải nghiệm học tập giúp người học hiểu sâu, làm bài tốt và tự tin hơn.",
    work: "Một trải nghiệm học gắn với tác vụ thật, không dừng ở mẹo prompt.",
    business: "Một trải nghiệm dành cho người cần AI phục vụ kết quả kinh doanh.",
    enterprise: "Một trải nghiệm đào tạo có quản trị, có báo cáo và có chuẩn triển khai.",
    resource: "Một trải nghiệm tài nguyên giúp người đọc chuyển ngay sang hành động.",
    news: "Một trải nghiệm đọc tin có chọn lọc, có ngữ cảnh và có góc ứng dụng.",
    conversion: "Một trải nghiệm tư vấn giảm phân vân trước khi chọn khóa học.",
    course: "Một trải nghiệm khóa học rõ mục tiêu, rõ bài học và rõ bước tiếp theo.",
  }[profile.kind];
}

function deepTitleFor(profile: ExperienceProfile) {
  return {
    kids: "Nội dung tập trung vào an toàn số, trí tưởng tượng và khả năng trình bày.",
    student: "Nội dung tập trung vào phương pháp học, kiểm chứng và năng lực tự học.",
    work: "Nội dung tập trung vào workflow cá nhân và chất lượng đầu ra công việc.",
    business: "Nội dung tập trung vào use case, quy trình và chỉ số vận hành.",
    enterprise: "Nội dung tập trung vào năng lực đội ngũ, phòng ban và báo cáo HR.",
    resource: "Tài nguyên được tổ chức như một thư viện hành động, không phải kho bài rời rạc.",
    news: "Tin tức được biên tập theo tác động thực tế với học tập và kinh doanh.",
    conversion: "Form tư vấn là điểm bắt đầu của một luồng chăm sóc, không chỉ là thu lead.",
    course: "Nội dung khóa học nối từ landing page đến LMS, quiz và portal.",
  }[profile.kind];
}

function deepDescriptionFor(profile: ExperienceProfile) {
  return {
    kids: "Phần quan trọng không phải trẻ tạo được bao nhiêu hình ảnh, mà là trẻ hiểu cách hỏi, biết kiểm chứng và biết dùng AI trong ranh giới an toàn.",
    student: "AI được đưa vào như một phương pháp học: hỏi tốt hơn, đọc nhanh hơn, ôn tập có hệ thống hơn và giữ trách nhiệm học thuật.",
    work: "Người học không cần thêm lý thuyết chung chung. Họ cần mẫu prompt, tiêu chí đầu ra, cách kiểm tra và workflow dùng được ngay.",
    business: "Chủ doanh nghiệp cần nhìn AI như một lớp năng lực vận hành: chọn bài toán, đào tạo người dùng, chuẩn hóa quy trình và đo hiệu quả.",
    enterprise: "Doanh nghiệp cần nhiều hơn một buổi workshop: cần phân nhóm vai trò, LMS theo dõi, quiz kiểm tra và báo cáo sau đào tạo.",
    resource: "Mỗi tài nguyên nên trả lời ba câu hỏi: dùng cho ai, dùng vào việc gì, và sau khi dùng xong nên đi tiếp đâu.",
    news: "Tin AI chỉ có giá trị khi được đặt vào bối cảnh: ảnh hưởng đến ai, nên thử như thế nào và rủi ro nào cần tránh.",
    conversion: "Người dùng cần cảm giác được dẫn đường. Vì vậy nội dung tư vấn phải rõ, tin cậy và giảm ma sát ngay từ form đầu tiên.",
    course: "Trang khóa học cần giúp người học hình dung hành trình: đăng ký, học, làm bài, kiểm tra, theo dõi tiến độ và nhận hỗ trợ.",
  }[profile.kind];
}
