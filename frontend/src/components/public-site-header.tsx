import Link from "next/link";

const primaryNav = [
  { label: "Đối tượng học", href: "/doi-tuong-hoc/" },
  { label: "Khóa học AI", href: "/khoa-hoc/" },
  { label: "Doanh nghiệp", href: "/ai-enterprise/" },
  { label: "Tài nguyên", href: "/tai-nguyen/" },
  { label: "Tin AI", href: "/tin-tuc-ai/" },
  { label: "Liên hệ", href: "/lien-he/" },
];

export function PublicSiteHeader() {
  return (
    <header className="sticky top-0 z-50 border-b border-[var(--dayai-border)] bg-white/95 backdrop-blur-xl">
      <div className="dayai-container flex items-center justify-between gap-4 py-3">
        <Link href="/" className="inline-flex min-w-fit items-center gap-3" aria-label="DAYAI">
          <span className="grid size-10 place-items-center rounded-[var(--dayai-radius-lg)] bg-[var(--dayai-primary)] text-sm font-black text-white shadow-[var(--dayai-shadow-xs)]">
            D
          </span>
          <span>
            <span className="block text-xl font-black text-[var(--dayai-text)]">DAYAI</span>
            <span className="hidden text-xs font-bold text-[var(--dayai-text-subtle)] sm:block">
              AI Education OS
            </span>
          </span>
        </Link>

        <nav className="hidden items-center gap-1 rounded-[var(--dayai-radius-full)] border border-[var(--dayai-border)] bg-[var(--dayai-bg-subtle)] p-1 text-sm font-bold text-[var(--dayai-text-muted)] xl:flex">
          {primaryNav.map((item) => (
            <Link
              key={item.href}
              href={item.href}
              className="rounded-[var(--dayai-radius-full)] px-4 py-2 transition hover:bg-white hover:text-[var(--dayai-primary)] hover:shadow-[var(--dayai-shadow-xs)]"
            >
              {item.label}
            </Link>
          ))}
        </nav>

        <div className="flex items-center gap-2">
          <Link href="/portal/" className="dayai-btn dayai-btn-secondary hidden md:inline-flex">
            Portal
          </Link>
          <Link href="/dang-ky-tu-van/" className="dayai-btn dayai-btn-primary">
            Tư vấn
          </Link>
        </div>
      </div>

      <nav className="flex gap-2 overflow-x-auto border-t border-[var(--dayai-border)] px-3 py-2 text-xs font-bold text-[var(--dayai-text-muted)] xl:hidden">
        {primaryNav.map((item) => (
          <Link
            key={item.href}
            href={item.href}
            className="shrink-0 rounded-[var(--dayai-radius-full)] bg-[var(--dayai-bg-subtle)] px-3 py-2"
          >
            {item.label}
          </Link>
        ))}
      </nav>
    </header>
  );
}
