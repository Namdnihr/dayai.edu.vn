import Link from "next/link";
import { CSSProperties } from "react";
import { LeadForm } from "@/components/lead-form";
import { PublicSiteFooter } from "@/components/public-site-footer";
import { PublicSiteHeader } from "@/components/public-site-header";
import { getPagesByGroup, menuGroups, SitePage } from "@/lib/site-map";

type HeroVisualConfig = {
  accent: string;
  label: string;
  title: string;
  mode: "course" | "kids" | "student" | "work" | "business" | "enterprise" | "resource" | "conversion";
  items: string[];
  mobilePills: string[];
  stats: Array<{
    label: string;
    value: string;
  }>;
};

const benefits = [
  {
    title: "Lộ trình rõ theo nhu cầu",
    description: "Mỗi nhóm học viên có mục tiêu, bài thực hành và tiêu chí tiến bộ riêng.",
  },
  {
    title: "Học xong dùng được",
    description: "Nội dung tập trung vào prompt, workflow, bài tập và tình huống thật.",
  },
  {
    title: "Có portal theo dõi",
    description: "Lịch học, LMS, quiz, học phí và thông báo được nối vào cùng một hệ thống.",
  },
];

export function SeoLandingPage({ page }: { page: SitePage }) {
  const relatedPages = getRelatedPages(page);
  const visualConfig = getHeroVisualConfig(page);
  const isConversionPage = page.path === "/dang-ky-tu-van/" || page.path === "/lien-he/";

  return (
    <main className="min-h-screen bg-[var(--dayai-bg-subtle)] text-[var(--dayai-text)]">
      <PublicSiteHeader />

      <section className="relative isolate overflow-hidden border-b border-[var(--dayai-border)] bg-white">
        <div className="absolute inset-0 dayai-muted-grid opacity-60" />
        <div className="absolute inset-x-0 top-0 h-40 bg-white" />

        <div className="dayai-container relative grid gap-12 py-16 lg:grid-cols-[0.95fr_1.05fr] lg:items-center lg:py-24">
          <div className="animate-fade-rise">
            <div className="dayai-chip">{page.group}</div>
            <h1 className="mt-6 max-w-5xl text-4xl font-black leading-[1.06] text-[var(--dayai-text)] sm:text-6xl">
              {page.title}
            </h1>
            <p className="mt-6 max-w-3xl text-base leading-8 text-[var(--dayai-text-muted)] sm:text-lg">
              {page.description}
            </p>
            <div className="mt-8 flex flex-col gap-3 sm:flex-row">
              <Link href={isConversionPage ? "#lead-form" : "/dang-ky-tu-van/"} className="dayai-btn dayai-btn-primary">
                Nhận tư vấn lộ trình
              </Link>
              <Link href="/khoa-hoc/" className="dayai-btn dayai-btn-secondary">
                Xem khóa học AI
              </Link>
            </div>

            {!isConversionPage ? (
              <div className="mt-8 grid grid-cols-3 gap-2 rounded-[var(--dayai-radius-xl)] border border-[var(--dayai-border)] bg-white p-3 shadow-[var(--dayai-shadow-xs)] lg:hidden">
                {visualConfig.mobilePills.map((item) => (
                  <div key={item} className="rounded-[var(--dayai-radius-lg)] bg-[var(--dayai-bg-subtle)] px-3 py-4 text-center text-xs font-black text-[var(--dayai-text-muted)]">
                    {item}
                  </div>
                ))}
              </div>
            ) : null}
          </div>

          {isConversionPage ? (
            <div className="animate-fade-rise-delay">
              <LeadForm campaign={page.path.includes("lien-he") ? "contact_page" : "consultation_page"} />
            </div>
          ) : (
            <div className="hidden lg:block">
              <SeoHeroVisual config={visualConfig} />
            </div>
          )}
        </div>
      </section>

      <section className="dayai-section bg-white">
        <div className="dayai-container">
          <SectionIntro
            eyebrow="Giá trị chính"
            title="Trang này nằm trong hệ sinh thái học và vận hành DAYAI."
            description="Người học không chỉ đọc thông tin. Họ có thể đi tiếp sang khóa học, portal, bài học LMS, quiz và lộ trình tư vấn phù hợp."
          />
          <div className="mt-12 grid gap-4 md:grid-cols-3">
            {benefits.map((benefit, index) => (
              <article key={benefit.title} className="dayai-card p-6">
                <div className="grid size-10 place-items-center rounded-[var(--dayai-radius-full)] bg-[var(--dayai-surface-tint)] text-sm font-black text-[var(--dayai-primary)]">
                  {index + 1}
                </div>
                <h2 className="mt-6 text-xl font-black">{benefit.title}</h2>
                <p className="mt-3 text-sm leading-7 text-[var(--dayai-text-muted)]">{benefit.description}</p>
              </article>
            ))}
          </div>
        </div>
      </section>

      <section className="dayai-section">
        <div className="dayai-container grid gap-10 lg:grid-cols-[0.85fr_1.15fr] lg:items-start">
          <SectionIntro
            eyebrow="Nội dung liên quan"
            title={`Đi tiếp trong nhóm ${page.group}.`}
            description="Các trang liên quan giúp người học chuyển từ tìm hiểu ban đầu sang chọn lộ trình, khóa học hoặc giải pháp phù hợp."
          />
          <div className="grid gap-4 md:grid-cols-2">
            {relatedPages.slice(0, 6).map((relatedPage) => (
              <Link
                key={relatedPage.path}
                href={relatedPage.path}
                className="dayai-card group p-6 transition hover:-translate-y-0.5 hover:border-[var(--dayai-primary)] hover:shadow-[var(--dayai-shadow-sm)]"
              >
                <div className="text-sm font-black text-[var(--dayai-primary)]">{relatedPage.group}</div>
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
        <div className="dayai-container grid gap-8 rounded-[var(--dayai-radius-2xl)] bg-[var(--dayai-primary)] p-6 text-white shadow-[var(--dayai-shadow-md)] sm:p-8 lg:grid-cols-[0.85fr_1.15fr] lg:p-12">
          <div>
            <div className="text-xs font-black uppercase">Tư vấn DAYAI</div>
            <h2 className="mt-4 text-3xl font-black leading-tight sm:text-4xl">
              Muốn chọn đúng lộ trình học AI?
            </h2>
            <p className="mt-5 text-sm leading-7 text-blue-50">
              Để lại thông tin, DAYAI sẽ tư vấn theo độ tuổi, mục tiêu học tập, công việc hoặc nhu cầu đào tạo doanh nghiệp.
            </p>
            {!isConversionPage ? (
              <Link href="/dang-ky-tu-van/" className="dayai-btn mt-8 bg-white text-[var(--dayai-primary)]">
                Nhận tư vấn miễn phí
              </Link>
            ) : null}
          </div>

          <div className="grid gap-3 self-end">
            {(isConversionPage ? ["Tiếp nhận nhu cầu", "Gợi ý lộ trình", "Hẹn lịch học thử", "Theo dõi qua portal"] : ["Học viên cá nhân", "Phụ huynh", "Doanh nghiệp", "Đội ngũ HR/L&D"]).map((item) => (
              <div key={item} className="rounded-[var(--dayai-radius-lg)] border border-white/15 bg-white/10 p-4 text-sm font-bold text-white">
                {item}
              </div>
            ))}
          </div>
        </div>
      </section>

      <PublicSiteFooter />
    </main>
  );
}

function SeoHeroVisual({ config }: { config: HeroVisualConfig }) {
  const visualStyle = { "--hero-accent": config.accent } as CSSProperties;

  return (
    <div
      style={visualStyle}
      className="animate-fade-rise-delay rounded-[var(--dayai-radius-2xl)] border border-[var(--dayai-border)] bg-white p-6 shadow-[var(--dayai-shadow-md)]"
    >
      <div className="dayai-dark overflow-hidden rounded-[var(--dayai-radius-xl)] border border-[var(--dayai-border)] bg-[var(--dayai-bg)] text-[var(--dayai-text)]">
        <div className="grid gap-6 p-6">
          <div className="flex items-start justify-between gap-6">
            <div>
              <div className="text-xs font-black text-[var(--hero-accent)]">{config.label}</div>
              <div className="mt-3 text-2xl font-black leading-tight">{config.title}</div>
            </div>
            <div className="grid size-12 shrink-0 place-items-center rounded-[var(--dayai-radius-lg)] bg-white text-[var(--hero-accent)]">
              <VisualIcon mode={config.mode} />
            </div>
          </div>

          <VisualBody config={config} />
        </div>
      </div>

      <div className="mt-4 grid grid-cols-3 gap-3">
        {config.stats.map((stat) => (
          <div key={stat.label} className="rounded-[var(--dayai-radius-lg)] border border-[var(--dayai-border)] bg-[var(--dayai-bg-subtle)] p-4 text-center">
            <div className="mx-auto grid size-10 place-items-center rounded-[var(--dayai-radius-full)] bg-[var(--hero-accent)] text-xs font-black text-white">
              {stat.value}
            </div>
            <div className="mt-3 text-xs font-black leading-5 text-[var(--dayai-text-muted)]">{stat.label}</div>
          </div>
        ))}
      </div>
    </div>
  );
}

function VisualBody({ config }: { config: HeroVisualConfig }) {
  if (config.mode === "resource") {
    return (
      <div className="grid gap-4">
        <div className="rounded-[var(--dayai-radius-lg)] bg-white/[0.06] p-4">
          <div className="flex items-center gap-2">
            <span className="h-2 flex-1 rounded-full bg-[var(--hero-accent)]" />
            <span className="h-2 w-16 rounded-full bg-white/20" />
            <span className="h-2 w-10 rounded-full bg-white/12" />
          </div>
          <div className="mt-5 grid gap-3">
            {config.items.map((item) => (
              <div key={item} className="flex items-center justify-between gap-4 rounded-[var(--dayai-radius-md)] bg-white/[0.06] px-4 py-3">
                <span className="text-sm font-semibold text-[var(--dayai-text-muted)]">{item}</span>
                <span className="rounded-[var(--dayai-radius-full)] bg-white px-3 py-1 text-xs font-black text-[var(--hero-accent)]">
                  đọc
                </span>
              </div>
            ))}
          </div>
        </div>
        <div className="grid grid-cols-3 gap-3">
          {["Checklist", "Prompt", "Ebook"].map((item) => (
            <div key={item} className="rounded-[var(--dayai-radius-md)] border border-white/10 bg-white/[0.04] p-3 text-center text-xs font-black text-[var(--dayai-text-muted)]">
              {item}
            </div>
          ))}
        </div>
      </div>
    );
  }

  if (config.mode === "business" || config.mode === "enterprise") {
    return (
      <div className="grid gap-4">
        <div className="grid gap-3">
          {config.items.map((item, index) => (
            <div key={item} className="rounded-[var(--dayai-radius-lg)] bg-white/[0.06] p-4">
              <div className="flex items-center justify-between gap-4">
                <span className="text-sm font-black text-[var(--dayai-text-muted)]">{item}</span>
                <span className="text-xs font-black text-[var(--hero-accent)]">{72 + index * 8}%</span>
              </div>
              <div className="mt-3 h-2 rounded-full bg-white/10">
                <div
                  className="h-2 rounded-full bg-[var(--hero-accent)]"
                  style={{ width: `${68 + index * 10}%` }}
                />
              </div>
            </div>
          ))}
        </div>
        <div className="grid grid-cols-2 gap-3">
          {["HR portal", "Báo cáo"].map((item) => (
            <div key={item} className="rounded-[var(--dayai-radius-md)] border border-white/10 bg-white/[0.04] p-3 text-center text-xs font-black text-[var(--dayai-text-muted)]">
              {item}
            </div>
          ))}
        </div>
      </div>
    );
  }

  if (config.mode === "conversion") {
    return (
      <div className="grid gap-3">
        {config.items.map((item, index) => (
          <div key={item} className="grid grid-cols-[auto_1fr_auto] items-center gap-4 rounded-[var(--dayai-radius-lg)] bg-white/[0.06] p-4">
            <span className="grid size-8 place-items-center rounded-full bg-white text-xs font-black text-[var(--hero-accent)]">
              {index + 1}
            </span>
            <span className="text-sm font-semibold text-[var(--dayai-text-muted)]">{item}</span>
            <span className="rounded-full bg-[var(--hero-accent)] px-3 py-1 text-xs font-black text-white">
              mới
            </span>
          </div>
        ))}
      </div>
    );
  }

  return (
    <div className="grid gap-3">
      {config.items.map((item, index) => (
        <div key={item} className="grid grid-cols-[auto_1fr] items-center gap-4 rounded-[var(--dayai-radius-lg)] bg-white/[0.06] p-4">
          <div className="grid size-9 place-items-center rounded-full bg-white text-xs font-black text-[var(--hero-accent)]">
            {index + 1}
          </div>
          <div>
            <div className="text-sm font-black text-[var(--dayai-text-muted)]">{item}</div>
            <div className="mt-2 flex gap-2">
              <span className="h-1.5 flex-1 rounded-full bg-[var(--hero-accent)]" />
              <span className="h-1.5 w-12 rounded-full bg-white/15" />
              <span className="h-1.5 w-8 rounded-full bg-white/10" />
            </div>
          </div>
        </div>
      ))}
    </div>
  );
}

function VisualIcon({ mode }: { mode: HeroVisualConfig["mode"] }) {
  if (mode === "kids") {
    return (
      <svg aria-hidden="true" className="size-6" viewBox="0 0 24 24" fill="none">
        <path d="M6 13c3-5 7-7 12-7" stroke="currentColor" strokeLinecap="round" strokeWidth="2" />
        <path d="M7 15h5v5H7zM14 11h4v9h-4z" fill="currentColor" opacity="0.18" />
        <circle cx="6" cy="13" r="2" fill="currentColor" />
      </svg>
    );
  }

  if (mode === "resource") {
    return (
      <svg aria-hidden="true" className="size-6" viewBox="0 0 24 24" fill="none">
        <path d="M6 5h12v14H6z" stroke="currentColor" strokeLinejoin="round" strokeWidth="2" />
        <path d="M9 9h6M9 12h6M9 15h3" stroke="currentColor" strokeLinecap="round" strokeWidth="2" />
      </svg>
    );
  }

  if (mode === "business" || mode === "enterprise") {
    return (
      <svg aria-hidden="true" className="size-6" viewBox="0 0 24 24" fill="none">
        <path d="M5 18V8M12 18V5M19 18v-7" stroke="currentColor" strokeLinecap="round" strokeWidth="2" />
        <path d="M4 18h16" stroke="currentColor" strokeLinecap="round" strokeWidth="2" />
      </svg>
    );
  }

  return (
    <svg aria-hidden="true" className="size-6" viewBox="0 0 24 24" fill="none">
      <path
        d="M4 16c4.4-.7 7.5-3.2 9.5-7.5M10 17c3.8-.5 6.8-2.4 9-5.7"
        stroke="currentColor"
        strokeLinecap="round"
        strokeWidth="1.8"
      />
      <path
        d="M14 8.5h4.5V13"
        stroke="currentColor"
        strokeLinecap="round"
        strokeLinejoin="round"
        strokeWidth="1.8"
      />
    </svg>
  );
}

function SectionIntro({
  eyebrow,
  title,
  description,
}: {
  eyebrow: string;
  title: string;
  description: string;
}) {
  return (
    <div className="max-w-3xl">
      <div className="dayai-kicker">{eyebrow}</div>
      <h2 className="mt-4 text-3xl font-black leading-tight sm:text-4xl">{title}</h2>
      <p className="mt-5 text-sm leading-7 text-[var(--dayai-text-muted)]">{description}</p>
    </div>
  );
}

function getRelatedPages(page: SitePage) {
  const sameGroup = getPagesByGroup(page.group).filter((relatedPage) => relatedPage.path !== page.path);

  if (sameGroup.length) {
    return sameGroup;
  }

  return menuGroups.flatMap((menu) => menu.children).filter((relatedPage) => relatedPage.path !== page.path);
}

function getHeroVisualConfig(page: SitePage): HeroVisualConfig {
  const text = `${page.group} ${page.title}`.toLowerCase();

  if (text.includes("kids") || text.includes("trẻ")) {
    return {
      accent: "var(--dayai-secondary)",
      label: "Creative AI studio",
      title: "Học AI an toàn, có phụ huynh đồng hành",
      mode: "kids",
      items: ["Kể chuyện bằng AI", "Dự án hình ảnh nhỏ", "Phụ huynh xem tiến độ"],
      mobilePills: ["Dự án", "An toàn", "Portal"],
      stats: [
        { value: "01", label: "ý tưởng" },
        { value: "02", label: "thực hành" },
        { value: "03", label: "chia sẻ" },
      ],
    };
  }

  if (text.includes("student") || text.includes("sinh viên") || text.includes("học sinh")) {
    return {
      accent: "var(--dayai-warning)",
      label: "Study cockpit",
      title: "Từ bài tập đến định hướng nghề nghiệp",
      mode: "student",
      items: ["Ghi chú và ôn tập", "Slide thuyết trình", "Portfolio cá nhân"],
      mobilePills: ["Ôn tập", "Slide", "Nghề"],
      stats: [
        { value: "AI", label: "trợ lý học" },
        { value: "3", label: "bài tập" },
        { value: "1", label: "lộ trình" },
      ],
    };
  }

  if (text.includes("work") || text.includes("người đi làm") || text.includes("công việc")) {
    return {
      accent: "var(--dayai-success)",
      label: "Productivity OS",
      title: "Workflow cá nhân cho công việc hằng ngày",
      mode: "work",
      items: ["Viết và tóm tắt", "Phân tích dữ liệu", "Tự động hóa tác vụ"],
      mobilePills: ["Prompt", "Report", "Flow"],
      stats: [
        { value: "4h", label: "tiết kiệm" },
        { value: "8", label: "workflow" },
        { value: "24", label: "prompt" },
      ],
    };
  }

  if (text.includes("enterprise")) {
    return {
      accent: "var(--dayai-primary)",
      label: "HR learning dashboard",
      title: "Đào tạo theo phòng ban, đo được tiến độ",
      mode: "enterprise",
      items: ["Khảo sát năng lực", "Workshop theo vai trò", "Báo cáo HR/L&D"],
      mobilePills: ["HR", "LMS", "Report"],
      stats: [
        { value: "5", label: "phòng ban" },
        { value: "82", label: "tiến độ" },
        { value: "KPI", label: "đo lường" },
      ],
    };
  }

  if (text.includes("business") || text.includes("doanh nghiệp") || text.includes("giải pháp")) {
    return {
      accent: "var(--dayai-accent)",
      label: "Business AI dashboard",
      title: "Ứng dụng AI vào tăng trưởng và vận hành",
      mode: "business",
      items: ["Bán hàng và marketing", "CSKH và tri thức", "Automation vận hành"],
      mobilePills: ["Sales", "CSKH", "Ops"],
      stats: [
        { value: "CRM", label: "lead" },
        { value: "AI", label: "workflow" },
        { value: "BI", label: "báo cáo" },
      ],
    };
  }

  if (
    text.includes("tài nguyên") ||
    text.includes("tin") ||
    text.includes("prompt") ||
    text.includes("công cụ") ||
    text.includes("case")
  ) {
    return {
      accent: "var(--dayai-accent)",
      label: "AI knowledge lab",
      title: "Đọc nhanh, áp dụng ngay, đi tiếp vào khóa học",
      mode: "resource",
      items: ["Cẩm nang nền tảng", "Checklist ứng dụng", "Prompt theo tình huống"],
      mobilePills: ["Guide", "Prompt", "Tool"],
      stats: [
        { value: "10", label: "phút đọc" },
        { value: "3", label: "bước làm" },
        { value: "1", label: "CTA" },
      ],
    };
  }

  if (text.includes("liên hệ") || text.includes("tư vấn")) {
    return {
      accent: "var(--dayai-primary)",
      label: "Advisor desk",
      title: "Tư vấn đúng người, đúng mục tiêu, đúng lộ trình",
      mode: "conversion",
      items: ["Tiếp nhận nhu cầu", "Gợi ý lộ trình", "Hẹn lịch học thử"],
      mobilePills: ["Tư vấn", "Lộ trình", "Lịch học"],
      stats: [
        { value: "1:1", label: "tư vấn" },
        { value: "24h", label: "phản hồi" },
        { value: "AI", label: "lộ trình" },
      ],
    };
  }

  return {
    accent: "var(--dayai-primary)",
    label: "Learning path",
    title: "Từ mục tiêu đến bài học, quiz và tiến độ",
    mode: "course",
    items: ["Đánh giá mục tiêu", "Học qua thực hành", "Quiz và LMS theo dõi"],
    mobilePills: ["LMS", "Quiz", "Portal"],
    stats: [
      { value: "01", label: "mentor" },
      { value: "02", label: "bài học" },
      { value: "03", label: "quiz" },
    ],
  };
}
