import type { Metadata } from "next";
import Link from "next/link";
import { PublicSiteFooter } from "@/components/public-site-footer";
import { PublicSiteHeader } from "@/components/public-site-header";

const audiences = [
  {
    title: "AI Kids",
    subtitle: "Trẻ em và học sinh nhỏ",
    description:
      "Học AI an toàn qua sáng tạo, kể chuyện, hình ảnh và các dự án nhỏ có phụ huynh đồng hành.",
    href: "/ai-kids/",
    color: "from-[#003A99] to-[#00AEEF]",
  },
  {
    title: "AI Student",
    subtitle: "Học sinh, sinh viên",
    description:
      "Dùng AI để học tập hiệu quả, làm slide, học tiếng Anh, nghiên cứu và định hướng nghề nghiệp.",
    href: "/ai-student/",
    color: "from-[#00AEEF] to-[#003A99]",
  },
  {
    title: "AI Work",
    subtitle: "Người đi làm",
    description:
      "Tăng năng suất cá nhân với prompt, báo cáo, content, phân tích dữ liệu và workflow công việc.",
    href: "/ai-work/",
    color: "from-[#003A99] to-[#111827]",
  },
  {
    title: "AI Business",
    subtitle: "Chủ doanh nghiệp",
    description:
      "Ứng dụng AI vào bán hàng, marketing, chăm sóc khách hàng, automation và quản trị vận hành.",
    href: "/ai-business/",
    color: "from-[#111827] to-[#F5B400]",
  },
  {
    title: "AI Enterprise",
    subtitle: "Công ty, HR, L&D",
    description:
      "Đào tạo AI theo phòng ban, theo dõi tiến độ nhóm và chuẩn hóa năng lực AI nội bộ.",
    href: "/ai-enterprise/",
    color: "from-[#003A99] to-[#F5B400]",
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
    <main className="min-h-screen bg-white text-slate-950">
      <PublicSiteHeader />

      <section className="relative overflow-hidden">
        <div className="pointer-events-none absolute inset-0">
          <div className="absolute inset-x-0 top-0 h-[640px] bg-[linear-gradient(90deg,rgba(0,58,153,0.045)_1px,transparent_1px),linear-gradient(rgba(0,58,153,0.045)_1px,transparent_1px)] bg-[size:76px_76px] [mask-image:radial-gradient(ellipse_at_center,black,transparent_72%)]" />
          <div className="absolute left-[18%] top-24 size-44 rounded-full bg-[#00AEEF]/15 blur-3xl" />
          <div className="absolute right-[14%] top-40 size-44 rounded-full bg-[#F5B400]/15 blur-3xl" />
        </div>

        <div className="relative z-10 mx-auto max-w-7xl px-5 py-20 text-center sm:px-8 lg:py-28">
          <div className="mx-auto mb-6 w-fit rounded-full border border-[#003A99]/10 bg-white/80 px-5 py-2 text-sm font-bold text-[#003A99] shadow-sm">
            Đối tượng học
          </div>
          <h1 className="mx-auto max-w-5xl text-5xl font-extrabold leading-[1.08] tracking-[-0.025em] sm:text-7xl">
            Chọn lộ trình AI phù hợp với bạn
          </h1>
          <p className="mx-auto mt-7 max-w-3xl text-lg leading-8 text-slate-600">
            DAYAI thiết kế năm hành trình học riêng cho trẻ em, học sinh sinh
            viên, người đi làm, chủ doanh nghiệp và đội ngũ công ty.
          </p>
          <div className="mx-auto mt-10 max-w-4xl">
            <AudienceHubVisual />
          </div>
        </div>
      </section>

      <section className="mx-auto max-w-7xl px-5 pb-24 sm:px-8">
        <div className="grid gap-5 lg:grid-cols-5">
          {audiences.map((audience) => (
            <Link
              key={audience.href}
              href={audience.href}
              className="group overflow-hidden rounded-[2rem] border border-black/10 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-2xl hover:shadow-[#003A99]/10"
            >
              <div className={`h-28 bg-gradient-to-br ${audience.color}`} />
              <div className="p-6">
                <div className="text-sm font-bold text-[#003A99]">
                  {audience.subtitle}
                </div>
                <h2 className="mt-3 text-2xl font-extrabold tracking-[-0.03em]">
                  {audience.title}
                </h2>
                <p className="mt-4 text-sm leading-6 text-slate-600">
                  {audience.description}
                </p>
                <div className="mt-6 text-sm font-bold text-black transition group-hover:text-[#003A99]">
                  Xem lộ trình →
                </div>
              </div>
            </Link>
          ))}
        </div>
      </section>
      <PublicSiteFooter />
    </main>
  );
}

function AudienceHubVisual() {
  return (
    <div className="relative overflow-hidden rounded-[2rem] border border-black/10 bg-white/78 p-5 shadow-[0_30px_110px_rgba(0,58,153,0.12)] backdrop-blur-xl">
      <div className="absolute inset-0 bg-[radial-gradient(circle_at_22%_20%,rgba(0,174,239,0.22),transparent_28%),radial-gradient(circle_at_82%_30%,rgba(245,180,0,0.16),transparent_26%)]" />
      <div className="relative grid gap-4 sm:grid-cols-5">
        {["Kids", "Student", "Work", "Business", "Enterprise"].map((item, index) => (
          <div key={item} className="dayai-float rounded-3xl bg-white/80 p-4 text-center shadow-sm" style={{ animationDelay: `${index * 0.22}s` }}>
            <div className="mx-auto grid size-14 place-items-center rounded-2xl bg-gradient-to-br from-[#003A99] to-[#00AEEF] text-sm font-black text-white">
              {index + 1}
            </div>
            <div className="mt-3 text-sm font-bold text-slate-800">AI {item}</div>
          </div>
        ))}
      </div>
    </div>
  );
}
