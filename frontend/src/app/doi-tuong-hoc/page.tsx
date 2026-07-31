import type { Metadata } from "next";
import Link from "next/link";
import type { CSSProperties } from "react";
import { PublicSiteFooter } from "@/components/public-site-footer";
import { PublicSiteHeader } from "@/components/public-site-header";

const audiences = [
  {
    title: "AI Kids",
    subtitle: "Trẻ em và học sinh nhỏ",
    description:
      "Học AI an toàn qua kể chuyện, hình ảnh, dự án sáng tạo và quy tắc dùng công nghệ có phụ huynh đồng hành.",
    href: "/ai-kids/",
    color: "var(--dayai-secondary)",
    image: "Creative classroom",
    outcomes: ["An toàn số", "Dự án nhỏ", "Phụ huynh theo dõi"],
    next: "Bắt đầu bằng tư duy an toàn và trí tưởng tượng.",
  },
  {
    title: "AI Student",
    subtitle: "Học sinh, sinh viên",
    description:
      "Dùng AI để học sâu hơn: ghi chú, ôn tập, làm slide, học tiếng Anh, nghiên cứu và định hướng nghề nghiệp.",
    href: "/ai-student/",
    color: "var(--dayai-warning)",
    image: "Study cockpit",
    outcomes: ["Ôn tập", "Slide", "Portfolio"],
    next: "Bắt đầu bằng phương pháp học và trách nhiệm học thuật.",
  },
  {
    title: "AI Work",
    subtitle: "Người đi làm",
    description:
      "Xây workflow AI cho báo cáo, email, content, phân tích dữ liệu, lập kế hoạch và tự động hóa tác vụ lặp lại.",
    href: "/ai-work/",
    color: "var(--dayai-success)",
    image: "Productivity desk",
    outcomes: ["Prompt", "Workflow", "Báo cáo"],
    next: "Bắt đầu bằng một tác vụ đang tốn thời gian mỗi ngày.",
  },
  {
    title: "AI Business",
    subtitle: "Chủ doanh nghiệp",
    description:
      "Ứng dụng AI vào bán hàng, marketing, chăm sóc khách hàng, tri thức nội bộ và quản trị vận hành.",
    href: "/ai-business/",
    color: "var(--dayai-accent)",
    image: "Business board",
    outcomes: ["Sales", "CSKH", "Automation"],
    next: "Bắt đầu bằng một use case có thể đo hiệu quả.",
  },
  {
    title: "AI Enterprise",
    subtitle: "Công ty, HR, L&D",
    description:
      "Đào tạo AI theo phòng ban, có LMS, quiz, báo cáo tiến độ và chuẩn dùng AI nội bộ cho đội ngũ.",
    href: "/ai-enterprise/",
    color: "var(--dayai-primary)",
    image: "Enterprise OS",
    outcomes: ["LMS", "Quiz", "HR report"],
    next: "Bắt đầu bằng bản đồ năng lực AI theo vai trò.",
  },
];

const experienceRows = [
  ["Mục tiêu", "Tò mò an toàn", "Học hiệu quả", "Tăng năng suất", "Tăng trưởng", "Năng lực đội ngũ"],
  ["Cách học", "Dự án sáng tạo", "Bài tập học thuật", "Workflow thực tế", "Use case kinh doanh", "Theo phòng ban"],
  ["Theo dõi", "Phụ huynh", "Quiz + tiến độ", "Portal cá nhân", "CRM/BI", "HR portal"],
];

export const metadata: Metadata = {
  title: "Đối tượng học AI",
  description:
    "Chọn lộ trình học AI phù hợp cho Kids, Students, Workers, Business Owners và Enterprises tại DAYAI.",
  alternates: {
    canonical: "/doi-tuong-hoc/",
  },
};

export default function AudienceHubPage() {
  return (
    <main className="min-h-screen bg-[var(--dayai-bg-subtle)] text-[var(--dayai-text)]">
      <PublicSiteHeader />

      <section className="relative isolate overflow-hidden border-b border-[var(--dayai-border)] bg-white">
        <div className="absolute inset-0 dayai-muted-grid opacity-60" />
        <div className="absolute inset-x-0 top-0 h-40 bg-white" />

        <div className="dayai-container relative grid gap-12 py-16 lg:grid-cols-[0.9fr_1.1fr] lg:items-center lg:py-24">
          <div className="animate-fade-rise">
            <div className="dayai-chip">Đối tượng học</div>
            <h1 className="mt-6 max-w-5xl text-4xl font-black leading-[1.06] sm:text-6xl">
              Một hệ sinh thái AI, năm hành trình học rất khác nhau.
            </h1>
            <p className="mt-6 max-w-3xl text-base leading-8 text-[var(--dayai-text-muted)] sm:text-lg">
              DAYAI không dùng một chương trình cho tất cả. Trẻ em cần an toàn và sáng tạo, sinh viên cần phương pháp học,
              người đi làm cần workflow, chủ doanh nghiệp cần use case, còn HR cần đào tạo có báo cáo.
            </p>
            <div className="mt-8 flex flex-col gap-3 sm:flex-row">
              <Link href="/dang-ky-tu-van/" className="dayai-btn dayai-btn-primary">
                Nhận tư vấn lộ trình
              </Link>
              <Link href="#audience-paths" className="dayai-btn dayai-btn-secondary">
                So sánh 5 nhóm học
              </Link>
            </div>
          </div>

          <AudienceOrbit />
        </div>
      </section>

      <section id="audience-paths" className="dayai-section">
        <div className="dayai-container">
          <div className="max-w-3xl">
            <div className="dayai-kicker">Chọn đúng ngữ cảnh</div>
            <h2 className="mt-4 text-3xl font-black leading-tight sm:text-4xl">
              Mỗi nhóm có một động lực học, một loại bài tập và một cách đo tiến bộ.
            </h2>
            <p className="mt-5 text-sm leading-7 text-[var(--dayai-text-muted)]">
              Các card dưới đây không chỉ là danh mục. Đây là những cánh cửa vào các trải nghiệm khác nhau,
              được viết riêng theo nhu cầu thật của người học.
            </p>
          </div>

          <div className="mt-12 grid gap-5 lg:grid-cols-5">
            {audiences.map((audience, index) => (
              <Link
                key={audience.href}
                href={audience.href}
                className="dayai-card dayai-lift group overflow-hidden"
                style={{ "--audience-color": audience.color } as CSSProperties}
              >
                <div className="relative h-32 overflow-hidden bg-[color-mix(in_srgb,var(--audience-color)_18%,white)] p-4">
                  <div className="absolute inset-4 rounded-[var(--dayai-radius-lg)] border border-white/70 bg-white/45" />
                  <div className="relative flex items-center justify-between">
                    <div className="grid size-11 place-items-center rounded-[var(--dayai-radius-full)] bg-[var(--audience-color)] text-sm font-black text-white">
                      {index + 1}
                    </div>
                    <div className="text-right text-xs font-black text-[var(--audience-color)]">{audience.image}</div>
                  </div>
                  <div className="relative mt-5 flex gap-2">
                    <span className="h-2 flex-1 rounded-full bg-[var(--audience-color)]" />
                    <span className="h-2 w-10 rounded-full bg-white" />
                    <span className="h-2 w-6 rounded-full bg-white/70" />
                  </div>
                </div>
                <div className="p-5">
                  <div className="text-sm font-black text-[var(--audience-color)]">{audience.subtitle}</div>
                  <h2 className="mt-3 text-2xl font-black leading-tight">{audience.title}</h2>
                  <p className="mt-4 text-sm leading-6 text-[var(--dayai-text-muted)]">{audience.description}</p>
                  <div className="mt-5 grid gap-2">
                    {audience.outcomes.map((item) => (
                      <span key={item} className="rounded-[var(--dayai-radius-full)] bg-[var(--dayai-bg-subtle)] px-3 py-2 text-xs font-black text-[var(--dayai-text-muted)]">
                        {item}
                      </span>
                    ))}
                  </div>
                  <p className="mt-5 text-sm font-bold leading-6 text-[var(--dayai-text)]">{audience.next}</p>
                  <div className="mt-6 text-sm font-black text-[var(--dayai-text)] transition group-hover:text-[var(--dayai-primary)]">
                    Xem lộ trình
                  </div>
                </div>
              </Link>
            ))}
          </div>
        </div>
      </section>

      <section className="dayai-section bg-white">
        <div className="dayai-container grid gap-10 lg:grid-cols-[0.85fr_1.15fr] lg:items-start">
          <div>
            <div className="dayai-kicker">So sánh nhanh</div>
            <h2 className="mt-4 text-3xl font-black leading-tight sm:text-4xl">
              Không phải ai học AI cũng cần cùng một bài học.
            </h2>
            <p className="mt-5 text-sm leading-7 text-[var(--dayai-text-muted)]">
              Bảng này giúp người dùng tự định vị trước khi vào trang con. Nếu vẫn chưa chắc, form tư vấn sẽ phân loại lại bằng câu hỏi ngắn.
            </p>
          </div>

          <div className="overflow-hidden rounded-[var(--dayai-radius-2xl)] border border-[var(--dayai-border)] bg-white shadow-[var(--dayai-shadow-xs)]">
            <div className="grid min-w-[760px] grid-cols-6 border-b border-[var(--dayai-border)] bg-[var(--dayai-bg-subtle)] text-xs font-black text-[var(--dayai-text-muted)]">
              {["Tiêu chí", "Kids", "Student", "Work", "Business", "Enterprise"].map((item) => (
                <div key={item} className="p-4">{item}</div>
              ))}
            </div>
            {experienceRows.map((row) => (
              <div key={row[0]} className="grid min-w-[760px] grid-cols-6 border-b border-[var(--dayai-border)] last:border-b-0">
                {row.map((item, index) => (
                  <div key={`${row[0]}-${item}`} className={`p-4 text-sm ${index === 0 ? "font-black text-[var(--dayai-text)]" : "font-semibold text-[var(--dayai-text-muted)]"}`}>
                    {item}
                  </div>
                ))}
              </div>
            ))}
          </div>
        </div>
      </section>

      <section className="bg-white pb-24">
        <div className="dayai-container grid gap-8 rounded-[var(--dayai-radius-2xl)] bg-[var(--dayai-primary)] p-8 text-white shadow-[var(--dayai-shadow-md)] lg:grid-cols-[1fr_0.8fr] lg:items-center">
          <div>
            <div className="text-xs font-black uppercase text-white/80">DAYAI advisor</div>
            <h2 className="mt-4 text-3xl font-black leading-tight sm:text-4xl">
              Chưa chắc mình thuộc nhóm nào?
            </h2>
            <p className="mt-5 text-sm leading-7 text-blue-50">
              Đội ngũ DAYAI sẽ hỏi vài câu về độ tuổi, vai trò, mục tiêu và lịch học để gợi ý lộ trình phù hợp.
            </p>
          </div>
          <Link href="/dang-ky-tu-van/" className="dayai-btn bg-white text-[var(--dayai-primary)]">
            Nhận tư vấn lộ trình
          </Link>
        </div>
      </section>

      <PublicSiteFooter />
    </main>
  );
}

function AudienceOrbit() {
  return (
    <div className="animate-fade-rise-delay dayai-card overflow-hidden p-4 shadow-[var(--dayai-shadow-md)]">
      <div className="dayai-dark relative overflow-hidden rounded-[var(--dayai-radius-xl)] bg-[var(--dayai-bg)] p-6 text-[var(--dayai-text)]">
        <div className="absolute inset-0 opacity-20 dayai-muted-grid" />
        <div className="relative">
          <div className="text-xs font-black uppercase text-[var(--dayai-accent)]">Audience routing</div>
          <div className="mt-3 text-2xl font-black">Chọn đúng người học trước khi chọn khóa học</div>
        </div>
        <div className="relative mt-8 grid gap-3">
          {audiences.map((audience, index) => (
            <div key={audience.title} className="grid grid-cols-[auto_1fr_auto] items-center gap-4 rounded-[var(--dayai-radius-lg)] bg-white/[0.06] p-4">
              <span className="dayai-float-slow grid size-9 place-items-center rounded-full bg-white text-xs font-black" style={{ color: audience.color }}>
                {index + 1}
              </span>
              <span className="text-sm font-black text-[var(--dayai-text-muted)]">{audience.title}</span>
              <span className="h-2 w-20 rounded-full" style={{ background: audience.color }} />
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
