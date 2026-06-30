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
      "Học AI an toàn qua sáng tạo, kể chuyện, hình ảnh và các dự án nhỏ có phụ huynh đồng hành.",
    href: "/ai-kids/",
    color: "var(--dayai-secondary)",
  },
  {
    title: "AI Student",
    subtitle: "Học sinh, sinh viên",
    description:
      "Dùng AI để học tập hiệu quả, làm slide, học tiếng Anh, nghiên cứu và định hướng nghề nghiệp.",
    href: "/ai-student/",
    color: "var(--dayai-warning)",
  },
  {
    title: "AI Work",
    subtitle: "Người đi làm",
    description:
      "Tăng năng suất cá nhân với prompt, báo cáo, content, phân tích dữ liệu và workflow công việc.",
    href: "/ai-work/",
    color: "var(--dayai-success)",
  },
  {
    title: "AI Business",
    subtitle: "Chủ doanh nghiệp",
    description:
      "Ứng dụng AI vào bán hàng, marketing, chăm sóc khách hàng, automation và quản trị vận hành.",
    href: "/ai-business/",
    color: "var(--dayai-accent)",
  },
  {
    title: "AI Enterprise",
    subtitle: "Công ty, HR, L&D",
    description:
      "Đào tạo AI theo phòng ban, theo dõi tiến độ nhóm và chuẩn hóa năng lực AI nội bộ.",
    href: "/ai-enterprise/",
    color: "var(--dayai-primary)",
  },
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

        <div className="dayai-container relative py-16 text-center lg:py-24">
          <div className="mx-auto w-fit dayai-chip">Đối tượng học</div>
          <h1 className="mx-auto mt-6 max-w-5xl text-4xl font-black leading-[1.06] sm:text-6xl">
            Chọn lộ trình AI phù hợp với bạn
          </h1>
          <p className="mx-auto mt-6 max-w-3xl text-base leading-8 text-[var(--dayai-text-muted)] sm:text-lg">
            DAYAI thiết kế năm hành trình học riêng cho trẻ em, học sinh sinh viên, người đi làm, chủ doanh nghiệp và đội ngũ công ty.
          </p>
          <div className="mx-auto mt-10 max-w-4xl">
            <AudienceHubVisual />
          </div>
        </div>
      </section>

      <section className="dayai-section">
        <div className="dayai-container">
          <div className="grid gap-4 lg:grid-cols-5">
            {audiences.map((audience, index) => (
              <Link
                key={audience.href}
                href={audience.href}
                className="dayai-card group overflow-hidden transition hover:-translate-y-0.5 hover:border-[var(--dayai-primary)] hover:shadow-[var(--dayai-shadow-sm)]"
                style={{ "--audience-color": audience.color } as CSSProperties}
              >
                <div className="h-24 bg-[color-mix(in_srgb,var(--audience-color)_22%,white)] p-4">
                  <div className="grid size-10 place-items-center rounded-[var(--dayai-radius-full)] bg-[var(--audience-color)] text-sm font-black text-white">
                    {index + 1}
                  </div>
                </div>
                <div className="p-6">
                  <div className="text-sm font-black text-[var(--audience-color)]">{audience.subtitle}</div>
                  <h2 className="mt-3 text-2xl font-black">{audience.title}</h2>
                  <p className="mt-4 text-sm leading-6 text-[var(--dayai-text-muted)]">{audience.description}</p>
                  <div className="mt-6 text-sm font-black text-[var(--dayai-text)] transition group-hover:text-[var(--dayai-primary)]">
                    Xem lộ trình
                  </div>
                </div>
              </Link>
            ))}
          </div>
        </div>
      </section>

      <section className="bg-white pb-24">
        <div className="dayai-container grid gap-8 rounded-[var(--dayai-radius-2xl)] bg-[var(--dayai-primary)] p-8 text-white shadow-[var(--dayai-shadow-md)] lg:grid-cols-[1fr_0.8fr] lg:items-center">
          <div>
            <div className="text-xs font-black uppercase">DAYAI advisor</div>
            <h2 className="mt-4 text-3xl font-black leading-tight sm:text-4xl">
              Chưa chắc mình thuộc nhóm nào?
            </h2>
            <p className="mt-5 text-sm leading-7 text-blue-50">
              Đội ngũ DAYAI sẽ hỏi vài câu về độ tuổi, mục tiêu và lịch học để gợi ý lộ trình phù hợp.
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

function AudienceHubVisual() {
  return (
    <div className="rounded-[var(--dayai-radius-2xl)] border border-[var(--dayai-border)] bg-white p-4 shadow-[var(--dayai-shadow-md)]">
      <div className="grid gap-3 sm:grid-cols-5">
        {["Kids", "Student", "Work", "Business", "Enterprise"].map((item, index) => (
          <div key={item} className="rounded-[var(--dayai-radius-lg)] border border-[var(--dayai-border)] bg-[var(--dayai-bg-subtle)] p-4 text-center">
            <div className="mx-auto grid size-12 place-items-center rounded-[var(--dayai-radius-full)] bg-[var(--dayai-primary)] text-sm font-black text-white">
              {index + 1}
            </div>
            <div className="mt-3 text-sm font-black text-[var(--dayai-text)]">AI {item}</div>
          </div>
        ))}
      </div>
    </div>
  );
}
