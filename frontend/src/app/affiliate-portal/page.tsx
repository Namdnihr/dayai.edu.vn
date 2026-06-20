import Link from "next/link";
import type { Metadata } from "next";
import { AffiliatePortalLookup } from "@/components/affiliate/affiliate-portal-lookup";

export const metadata: Metadata = {
  title: "Cổng đối tác Affiliate | DAYAI",
  description: "Cổng tra cứu hiệu quả affiliate dành cho đối tác DAYAI.",
  robots: {
    index: false,
    follow: false,
  },
};

export default function AffiliatePortalPage() {
  return (
    <main className="min-h-screen bg-slate-50 text-slate-950">
      <header className="border-b border-slate-200 bg-white/90 backdrop-blur-xl">
        <div className="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
          <Link href="/" className="flex items-center gap-3">
            <span className="grid size-10 place-items-center rounded-2xl bg-[#003A99] text-lg font-black text-white">D</span>
            <span className="text-xl font-black tracking-tight">DAYAI Partner</span>
          </Link>
          <Link href="/" className="text-sm font-bold text-slate-600 hover:text-[#003A99]">← Về website</Link>
        </div>
      </header>

      <section className="mx-auto grid max-w-7xl gap-10 px-6 py-14 lg:grid-cols-[0.85fr_1.15fr]">
        <div>
          <p className="w-fit rounded-full border border-blue-100 bg-white px-4 py-2 text-sm font-bold text-[#003A99] shadow-sm">
            Khu vực dành cho đối tác
          </p>
          <h1 className="mt-6 text-5xl font-black tracking-[-0.04em] sm:text-6xl">
            Theo dõi hiệu quả affiliate DAYAI
          </h1>
          <p className="mt-5 text-lg leading-8 text-slate-600">
            Đối tác có thể kiểm tra link chiến dịch, lượt click, lead ghi nhận, trạng thái hoa hồng và số tiền đã duyệt/đã trả.
          </p>
          <div className="mt-8 rounded-3xl border border-blue-100 bg-white p-6 shadow-sm">
            <div className="text-sm font-bold uppercase tracking-wide text-[#003A99]">Dữ liệu demo</div>
            <p className="mt-2 text-slate-700">Mã đối tác: <b>PARTNER-A</b></p>
            <p className="text-slate-700">Mã link: <b>REF-A</b></p>
            <p className="mt-3 text-sm leading-6 text-slate-500">
              Trang này không index SEO và không nằm trong menu chính. Khi triển khai thật, mã đối tác nên được thay bằng đăng nhập riêng hoặc token bảo mật.
            </p>
          </div>
        </div>

        <AffiliatePortalLookup />
      </section>
    </main>
  );
}
