import Link from "next/link";

const footerGroups = [
  {
    title: "Học AI",
    links: [
      { label: "AI Kids", href: "/ai-kids/" },
      { label: "AI Student", href: "/ai-student/" },
      { label: "AI Work", href: "/ai-work/" },
      { label: "Khóa học AI", href: "/khoa-hoc/" },
    ],
  },
  {
    title: "Doanh nghiệp",
    links: [
      { label: "AI Business", href: "/ai-business/" },
      { label: "AI Enterprise", href: "/ai-enterprise/" },
      { label: "Giải pháp AI", href: "/giai-phap/" },
      { label: "HR Portal", href: "/company-portal/" },
    ],
  },
  {
    title: "Tài nguyên",
    links: [
      { label: "Cẩm nang AI", href: "/cam-nang-ai/" },
      { label: "Prompt AI", href: "/prompt-ai/" },
      { label: "Công cụ AI", href: "/cong-cu-ai/" },
      { label: "Tin tức AI", href: "/tin-tuc-ai/" },
    ],
  },
  {
    title: "DAYAI",
    links: [
      { label: "Về DAYAI", href: "/ve-day-ai/" },
      { label: "Giảng viên", href: "/giang-vien/" },
      { label: "Liên hệ", href: "/lien-he/" },
      { label: "Portal học viên", href: "/portal/" },
    ],
  },
];

export function PublicSiteFooter({ dark = false }: { dark?: boolean }) {
  const shell = dark
    ? "dayai-dark border-t border-[var(--dayai-border)] bg-[var(--dayai-bg)] text-[var(--dayai-text)]"
    : "border-t border-[var(--dayai-border)] bg-white text-[var(--dayai-text)]";

  return (
    <footer className={shell}>
      <div className="dayai-container grid gap-12 py-16 lg:grid-cols-[1.05fr_2fr]">
        <div>
          <Link href="/" className="inline-flex items-center gap-3">
            <span className="grid size-11 place-items-center rounded-[var(--dayai-radius-lg)] bg-[var(--dayai-primary)] text-sm font-black text-white">
              D
            </span>
            <span className="text-3xl font-black">DAYAI</span>
          </Link>
          <p className="mt-6 max-w-md text-sm leading-7 text-[var(--dayai-text-muted)]">
            Hệ sinh thái học AI cho học viên, phụ huynh, người đi làm và doanh nghiệp Việt Nam.
          </p>
          <div className="mt-6 grid gap-2 text-sm font-semibold text-[var(--dayai-text-muted)]">
            <span>Hotline: 0901 000 001</span>
            <span>Email: hello@dayai.edu.vn</span>
          </div>
          <div className="mt-8 flex flex-wrap gap-3">
            <Link href="/dang-ky-tu-van/" className="dayai-btn dayai-btn-primary">
              Đăng ký tư vấn
            </Link>
            <Link href="/portal/" className="dayai-btn dayai-btn-secondary">
              Vào portal
            </Link>
          </div>
        </div>

        <div className="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
          {footerGroups.map((group) => (
            <div key={group.title}>
              <h3 className="text-sm font-black text-[var(--dayai-text)]">{group.title}</h3>
              <div className="mt-4 grid gap-3">
                {group.links.map((item) => (
                  <Link
                    key={item.href}
                    href={item.href}
                    className="text-sm font-semibold text-[var(--dayai-text-muted)] transition hover:text-[var(--dayai-primary)]"
                  >
                    {item.label}
                  </Link>
                ))}
              </div>
            </div>
          ))}
        </div>
      </div>

      <div className="border-t border-[var(--dayai-border)]">
        <div className="dayai-container flex flex-col justify-between gap-4 py-6 text-sm text-[var(--dayai-text-muted)] sm:flex-row">
          <span>© DAYAI.EDU.VN</span>
          <div className="flex flex-wrap gap-5">
            <Link href="/affiliate-portal/" className="transition hover:text-[var(--dayai-primary)]">
              Affiliate Portal
            </Link>
            <Link href="/company-portal/" className="transition hover:text-[var(--dayai-primary)]">
              HR Portal
            </Link>
            <Link href="/sitemap.xml" className="transition hover:text-[var(--dayai-primary)]">
              Sitemap
            </Link>
          </div>
        </div>
      </div>
    </footer>
  );
}
