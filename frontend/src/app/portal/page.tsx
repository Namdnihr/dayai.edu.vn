import Link from "next/link";
import type { Metadata } from "next";
import { PortalLookup } from "@/components/portal/portal-lookup";

export const metadata: Metadata = {
  title: "Cổng học viên & phụ huynh | DAYAI",
  description: "Cổng tra cứu riêng cho phụ huynh và học viên DAYAI.",
  robots: {
    index: false,
    follow: false,
  },
};

export default function PortalPage() {
  return (
    <main className="min-h-screen bg-slate-50 text-slate-950">
      <header className="border-b border-slate-200 bg-white/90 backdrop-blur-xl">
        <div className="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
          <Link href="/" className="flex items-center gap-3">
            <span className="grid size-10 place-items-center rounded-2xl bg-[#003A99] text-lg font-black text-white">D</span>
            <span className="text-xl font-black tracking-tight">DAYAI Portal</span>
          </Link>
          <Link href="/" className="text-sm font-bold text-slate-600 hover:text-[#003A99]">← Về website</Link>
        </div>
      </header>

      <section className="mx-auto grid max-w-7xl gap-10 px-6 py-14 lg:grid-cols-[0.85fr_1.15fr]">
        <div>
          <p className="w-fit rounded-full border border-blue-100 bg-white px-4 py-2 text-sm font-bold text-[#003A99] shadow-sm">
            Khu tra cứu riêng tư
          </p>
          <h1 className="mt-6 text-5xl font-black tracking-[-0.04em] sm:text-6xl">
            Cổng phụ huynh & học viên
          </h1>
          <p className="mt-5 text-lg leading-8 text-slate-600">
            Tra cứu lịch học, điểm danh, học phí, video học liên quan và báo cáo tiến bộ của học viên DAYAI.
          </p>
          <div className="mt-8 rounded-3xl border border-blue-100 bg-white p-6 shadow-sm">
            <div className="text-sm font-bold uppercase tracking-wide text-[#003A99]">Dữ liệu demo</div>
            <p className="mt-2 text-slate-700">SĐT: <b>0901888000</b></p>
            <p className="text-slate-700">Mã học viên: <b>HV-000001</b></p>
            <p className="mt-3 text-sm leading-6 text-slate-500">
              Portal không hiển thị trên menu chính. Người dùng cần có SĐT và mã học viên để tra cứu.
            </p>
          </div>
        </div>

        <PortalLookup />
      </section>
    </main>
  );
}
