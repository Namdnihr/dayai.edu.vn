import Link from "next/link";
import { menuGroups } from "@/lib/site-map";

export function PublicSiteFooter() {
  return (
    <footer className="border-t border-black/10 bg-white">
      <div className="mx-auto grid max-w-7xl gap-10 px-5 py-14 sm:px-8 lg:grid-cols-[1.05fr_2fr]">
        <div>
          <Link href="/" className="text-3xl font-black tracking-tight">
            DAYAI
          </Link>
          <p className="mt-4 max-w-md text-sm leading-7 text-slate-600">
            Hệ sinh thái học AI cho Kids, Students, Workers, Business Owners và
            Enterprises tại Việt Nam.
          </p>
          <div className="mt-6 grid gap-2 text-sm text-slate-600">
            <span>Hotline: 0901 000 001</span>
            <span>Email: hello@dayai.edu.vn</span>
          </div>
          <div className="mt-6 flex flex-wrap gap-3">
            <Link
              href="/dang-ky-tu-van/"
              className="rounded-full bg-[#003A99] px-5 py-2.5 text-sm font-bold text-white"
            >
              Đăng ký tư vấn
            </Link>
            <Link
              href="/lien-he/"
              className="rounded-full border border-black/10 px-5 py-2.5 text-sm font-bold text-slate-950"
            >
              Liên hệ
            </Link>
          </div>
        </div>

        <div className="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
          {menuGroups.map((menu) => (
            <div key={menu.href}>
              <Link href={menu.href} className="font-bold text-slate-950">
                {menu.label}
              </Link>
              <p className="mt-2 text-xs leading-5 text-slate-500">
                {menu.description}
              </p>
              <div className="mt-4 grid gap-2">
                {menu.children.slice(0, 5).map((child) => (
                  <Link
                    key={child.path}
                    href={child.path}
                    className="text-sm leading-6 text-slate-500 transition hover:text-[#003A99]"
                  >
                    {child.title}
                  </Link>
                ))}
              </div>
            </div>
          ))}
        </div>
      </div>

      <div className="border-t border-black/10">
        <div className="mx-auto flex max-w-7xl flex-col justify-between gap-4 px-5 py-6 text-sm text-slate-500 sm:flex-row sm:px-8">
          <span>© DAYAI.EDU.VN</span>
          <div className="flex flex-wrap gap-5">
            <Link href="/portal/">Portal học viên</Link>
            <Link href="/company-portal/">Portal doanh nghiệp</Link>
            <Link href="/sitemap.xml">Sitemap</Link>
          </div>
        </div>
      </div>
    </footer>
  );
}
