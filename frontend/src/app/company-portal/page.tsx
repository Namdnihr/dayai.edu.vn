import Link from "next/link";
import type { Metadata } from "next";
import { CompanyPortalLookup } from "@/components/portal/company-portal-lookup";

export const metadata: Metadata = {
  title: "Cổng doanh nghiệp",
  description: "Cổng tra cứu riêng cho doanh nghiệp và HR của DAYAI.",
  robots: {
    index: false,
    follow: false,
  },
};

export default function CompanyPortalPage() {
  return (
    <main className="min-h-screen bg-slate-50 text-slate-950">
      <header className="border-b border-slate-200 bg-white">
        <div className="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
          <Link href="/" className="flex items-center gap-3">
            <span className="grid size-10 place-items-center rounded-2xl bg-blue-600 text-lg font-black text-white">D</span>
            <span className="text-xl font-black tracking-tight">DAYAI Company Portal</span>
          </Link>
          <Link href="/" className="text-sm font-bold text-slate-600 hover:text-blue-700">← Về website</Link>
        </div>
      </header>

      <section className="mx-auto grid max-w-7xl gap-10 px-6 py-14 lg:grid-cols-[0.85fr_1.15fr]">
        <div>
          <h1 className="text-5xl font-black tracking-[-0.04em]">Cổng tra cứu doanh nghiệp / HR</h1>
          <p className="mt-5 text-lg leading-8 text-slate-600">
            HR có thể xem danh sách nhân sự đang học, tiến độ từng người, điểm danh và công nợ đào tạo theo đơn B2B.
          </p>
          <div className="mt-8 rounded-3xl border border-blue-100 bg-white p-6 shadow-sm">
            <div className="text-sm font-bold uppercase tracking-wide text-blue-600">Dữ liệu demo</div>
            <p className="mt-2 text-slate-700">Email HR: <b>hr@examplecorp.test</b></p>
            <p className="text-slate-700">Mã công ty: <b>EXAMPLE-CORP</b></p>
          </div>
        </div>

        <CompanyPortalLookup />
      </section>
    </main>
  );
}
