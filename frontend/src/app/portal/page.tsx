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
    <main className="min-h-screen bg-[var(--dayai-bg-subtle)] text-[var(--dayai-text)]">
      <header className="sticky top-0 z-40 border-b border-[var(--dayai-border)] bg-white/90 backdrop-blur-xl">
        <div className="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
          <Link href="/" className="flex items-center gap-3">
            <span className="grid size-10 place-items-center rounded-[var(--dayai-radius-lg)] bg-[var(--dayai-primary)] text-lg font-black text-white">D</span>
            <span>
              <span className="block text-lg font-black leading-none tracking-tight">DAYAI Portal</span>
              <span className="text-xs font-bold text-[var(--dayai-text-subtle)]">Learning OS</span>
            </span>
          </Link>
          <Link href="/" className="rounded-[var(--dayai-radius-full)] border border-[var(--dayai-border)] px-4 py-2 text-sm font-bold text-[var(--dayai-text-muted)] hover:border-[var(--dayai-primary)] hover:text-[var(--dayai-primary)]">
            Về website
          </Link>
        </div>
      </header>

      <section className="mx-auto max-w-7xl px-6 py-10">
        <div className="relative mb-8 overflow-hidden rounded-[var(--dayai-radius-2xl)] border border-[var(--dayai-border)] bg-white p-6 shadow-[var(--dayai-shadow-sm)] md:p-8">
          <div className="absolute inset-0 dayai-muted-grid opacity-45" />
          <div className="absolute right-8 top-8 hidden h-40 w-40 rounded-full bg-blue-50 lg:block" />
          <div className="relative grid gap-8 lg:grid-cols-[1fr_380px] lg:items-end">
            <div>
              <p className="w-fit rounded-[var(--dayai-radius-full)] border border-blue-100 bg-blue-50 px-4 py-2 text-sm font-bold text-[var(--dayai-primary)]">
                Khu học tập riêng tư
              </p>
              <h1 className="mt-5 text-4xl font-black leading-tight sm:text-5xl">
                Dashboard học viên DAYAI
              </h1>
              <p className="mt-4 max-w-3xl text-lg leading-8 text-slate-600">
                Một nơi để học tiếp, xem khóa đã mua hoặc khóa miễn phí, theo dõi bài học, quiz, học phí, thông báo và chứng chỉ.
              </p>
              <div className="mt-6 flex flex-wrap gap-3">
                {["LMS", "Quiz", "Học phí", "Thông báo", "Chứng chỉ"].map((item) => (
                  <span key={item} className="rounded-[var(--dayai-radius-full)] bg-[var(--dayai-bg-subtle)] px-4 py-2 text-xs font-black text-[var(--dayai-text-muted)]">
                    {item}
                  </span>
                ))}
              </div>
            </div>

            <div className="relative rounded-[var(--dayai-radius-xl)] border border-[var(--dayai-border)] bg-slate-950 p-5 text-sm text-white shadow-[var(--dayai-shadow-md)]">
              <div className="text-xs font-black uppercase tracking-wide text-blue-200">Demo local</div>
              <div className="mt-4 grid gap-3">
                <div className="rounded-[var(--dayai-radius-lg)] bg-white/10 p-4">
                  <div className="text-xs font-bold text-blue-100">Học viên mới có khóa free + paid</div>
                  <p className="mt-2">SĐT: <b>0901002003</b></p>
                  <p>Mã học viên: <b>HV-260630181712-IOBH</b></p>
                </div>
                <div className="rounded-[var(--dayai-radius-lg)] bg-white/10 p-4">
                  <div className="text-xs font-bold text-blue-100">Dữ liệu demo hệ thống</div>
                  <p className="mt-2">SĐT: <b>0901888000</b></p>
                  <p>Mã học viên: <b>HV-000001</b></p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <PortalLookup />
      </section>
    </main>
  );
}
