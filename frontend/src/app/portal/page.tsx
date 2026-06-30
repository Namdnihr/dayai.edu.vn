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

      <section className="mx-auto max-w-7xl px-6 py-10">
        <div className="mb-8 rounded-[2rem] border border-blue-100 bg-white p-6 shadow-sm md:p-8">
          <p className="w-fit rounded-full border border-blue-100 bg-blue-50 px-4 py-2 text-sm font-bold text-[#003A99]">
            Khu học tập riêng tư
          </p>
          <div className="mt-5 grid gap-5 lg:grid-cols-[1fr_auto] lg:items-end">
            <div>
              <h1 className="text-4xl font-black tracking-[-0.04em] sm:text-5xl">
                Dashboard học viên DAYAI
              </h1>
              <p className="mt-4 max-w-3xl text-lg leading-8 text-slate-600">
                Đăng nhập để xem nhanh khóa học, lịch học, video, điểm danh, học phí và báo cáo tiến bộ.
              </p>
            </div>
            <div className="rounded-3xl border border-slate-200 bg-slate-50 p-5 text-sm text-slate-700">
              <div className="font-black uppercase tracking-wide text-[#003A99]">Demo local</div>
              <p className="mt-2">SĐT: <b>0901888000</b></p>
              <p>Mã học viên: <b>HV-000001</b></p>
            </div>
          </div>
        </div>
        <PortalLookup />
      </section>
    </main>
  );
}
