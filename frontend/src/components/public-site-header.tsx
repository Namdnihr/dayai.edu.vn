import Link from "next/link";
import { menuGroups } from "@/lib/site-map";

export function PublicSiteHeader() {
  return (
    <header className="sticky top-0 z-50 border-b border-black/5 bg-white/86 backdrop-blur-xl">
      <div className="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 sm:px-8">
        <Link href="/" className="text-3xl font-black tracking-tight" aria-label="DAYAI">
          DAYAI
        </Link>

        <nav className="hidden items-center gap-6 text-sm font-semibold text-slate-500 xl:flex">
          {menuGroups.map((menu) => (
            <Link key={menu.href} href={menu.href} className="transition hover:text-[#003A99]">
              {menu.label}
            </Link>
          ))}
        </nav>

        <Link
          href="/dang-ky-tu-van/"
          className="rounded-full bg-[#003A99] px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-[#003A99]/20 transition hover:scale-[1.03]"
        >
          Tư vấn
        </Link>
      </div>
    </header>
  );
}
