import Link from "next/link";
import { PublicSiteFooter } from "@/components/public-site-footer";
import { PublicSiteHeader } from "@/components/public-site-header";
import { getPagesByGroup, menuGroups, SitePage } from "@/lib/site-map";

export function SeoLandingPage({ page }: { page: SitePage }) {
  const relatedPages = getPagesByGroup(page.group).filter(
    (relatedPage) => relatedPage.path !== page.path,
  );

  return (
    <main className="min-h-screen bg-white text-slate-950">
      <PublicSiteHeader />
      <section className="relative overflow-hidden pt-24">
        <div className="pointer-events-none absolute inset-0">
          <div className="absolute inset-x-0 top-0 h-[640px] bg-[linear-gradient(90deg,rgba(0,58,153,0.045)_1px,transparent_1px),linear-gradient(rgba(0,58,153,0.045)_1px,transparent_1px)] bg-[size:76px_76px] [mask-image:radial-gradient(ellipse_at_center,black,transparent_72%)]" />
          <div className="absolute right-[14%] top-28 size-48 rounded-full bg-[#00AEEF]/15 blur-3xl" />
          <div className="absolute left-[12%] top-44 size-40 rounded-full bg-[#F5B400]/15 blur-3xl" />
        </div>

        <div className="relative z-10 mx-auto grid max-w-7xl gap-10 px-5 py-20 sm:px-8 lg:grid-cols-[0.95fr_1.05fr] lg:items-center lg:py-28">
          <div>
            <div className="mb-6 inline-flex rounded-full border border-[#003A99]/10 bg-white/80 px-5 py-2 text-sm font-bold text-[#003A99] shadow-sm backdrop-blur">
              {page.group}
            </div>
            <h1 className="max-w-5xl text-5xl font-semibold leading-[1.05] tracking-[-0.025em] text-black sm:text-7xl">
              {page.title}
            </h1>
            <p className="mt-7 max-w-3xl text-lg leading-8 text-slate-600">
              {page.description}
            </p>
            <div className="mt-10 flex flex-col gap-3 sm:flex-row">
              <Link
                href="/dang-ky-tu-van/"
                className="rounded-full bg-[#003A99] px-8 py-4 text-center font-bold text-white shadow-xl shadow-[#003A99]/20 transition hover:scale-[1.03]"
              >
                Đăng ký tư vấn
              </Link>
              <Link
                href="/khoa-hoc/"
                className="rounded-full border border-black/10 bg-white/80 px-8 py-4 text-center font-bold text-black shadow-sm backdrop-blur transition hover:border-[#00AEEF]/50"
              >
                Xem khóa học AI
              </Link>
            </div>
          </div>
          <SeoHeroVisual page={page} />
        </div>
      </section>

      <section className="mx-auto grid max-w-7xl gap-5 px-5 py-10 sm:px-8 lg:grid-cols-3">
        {[
          "Lộ trình rõ ràng theo nhu cầu",
          "Học qua thực hành và dự án",
          "Có hệ thống theo dõi tiến độ",
        ].map((benefit) => (
          <div key={benefit} className="rounded-[2rem] border border-black/10 bg-white p-7 shadow-sm">
            <div className="dayai-float grid size-14 place-items-center rounded-2xl bg-blue-50 text-[#003A99]">
              <SeoBenefitIcon />
            </div>
            <h2 className="mt-4 text-xl font-black">{benefit}</h2>
            <p className="mt-3 text-sm leading-6 text-slate-600">
              DAYAI tập trung vào năng lực ứng dụng AI thật, không dùng nội dung
              chung chung hoặc minh họa sáo rỗng.
            </p>
          </div>
        ))}
      </section>

      <section className="bg-blue-50 py-20">
        <div className="mx-auto max-w-7xl px-5 sm:px-8">
          <div className="max-w-3xl">
            <h2 className="text-4xl font-black tracking-[-0.03em]">
              Nội dung liên quan trong nhóm {page.group}
            </h2>
            <p className="mt-4 text-lg leading-8 text-slate-600">
              Các trang này giúp người học đi từ tìm hiểu ban đầu đến lựa chọn
              khóa học hoặc giải pháp phù hợp.
            </p>
          </div>
          <div className="mt-10 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            {(relatedPages.length ? relatedPages : menuGroups.flatMap((menu) => menu.children))
              .slice(0, 6)
              .map((relatedPage) => (
                <Link
                  key={relatedPage.path}
                  href={relatedPage.path}
                  className="rounded-[1.5rem] bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-xl"
                >
                  <div className="text-sm font-bold text-[#003A99]">
                    {relatedPage.group}
                  </div>
                  <h3 className="mt-3 text-xl font-black">{relatedPage.title}</h3>
                  <p className="mt-3 line-clamp-3 text-sm leading-6 text-slate-600">
                    {relatedPage.description}
                  </p>
                </Link>
              ))}
          </div>
        </div>
      </section>

      <section className="mx-auto max-w-7xl px-5 py-20 sm:px-8">
        <div className="rounded-[2.5rem] bg-slate-950 p-8 text-white sm:p-12">
          <div className="grid gap-10 lg:grid-cols-[1fr_0.8fr] lg:items-center">
            <div>
              <h2 className="text-4xl font-black tracking-[-0.03em]">
                Muốn chọn đúng lộ trình học AI?
              </h2>
              <p className="mt-5 text-lg leading-8 text-white/65">
                Để lại thông tin, DAYAI sẽ tư vấn theo độ tuổi, mục tiêu học tập,
                công việc hoặc nhu cầu đào tạo doanh nghiệp.
              </p>
            </div>
            <Link
              href="/dang-ky-tu-van/"
              className="rounded-full bg-white px-8 py-4 text-center font-bold text-[#003A99] transition hover:scale-[1.03]"
            >
              Nhận tư vấn miễn phí
            </Link>
          </div>
        </div>
      </section>
      <PublicSiteFooter />
    </main>
  );
}

function SeoBenefitIcon() {
  return (
    <svg aria-hidden="true" className="size-7" viewBox="0 0 24 24" fill="none">
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
      <circle cx="5" cy="16" r="1.5" fill="currentColor" opacity="0.22" />
    </svg>
  );
}

function SeoHeroVisual({ page }: { page: SitePage }) {
  const visualItems = getVisualItems(page.group);

  return (
    <div className="relative min-h-[420px] overflow-hidden rounded-[2.25rem] border border-black/10 bg-white/78 p-5 shadow-[0_35px_120px_rgba(0,58,153,0.13)] backdrop-blur-xl">
      <div className="absolute inset-0 bg-[radial-gradient(circle_at_25%_25%,rgba(0,174,239,0.24),transparent_28%),radial-gradient(circle_at_78%_20%,rgba(245,180,0,0.18),transparent_24%),linear-gradient(180deg,rgba(255,255,255,0.92),rgba(239,246,255,0.84))]" />
      <div className="relative rounded-[1.75rem] bg-slate-950 p-5 text-white">
        <div className="flex items-center justify-between">
          <div>
            <div className="text-xs font-bold uppercase tracking-[0.18em] text-[#00AEEF]">
              {page.group}
            </div>
            <div className="mt-2 text-xl font-black">Hành trình học tập</div>
          </div>
          <div className="grid size-11 place-items-center rounded-2xl bg-white/10">
            <SeoBenefitIcon />
          </div>
        </div>
        <div className="mt-7 grid gap-3">
          {visualItems.map((item, index) => (
            <div key={item} className="flex items-center gap-3 rounded-2xl bg-white/[0.07] p-3">
              <div className="grid size-9 place-items-center rounded-full bg-white text-sm font-black text-[#003A99]">
                {index + 1}
              </div>
              <span className="text-sm font-semibold text-white/82">{item}</span>
            </div>
          ))}
        </div>
      </div>
      <div className="relative mt-4 grid grid-cols-3 gap-3">
        {["Mentor", "Bài tập", "Tiến bộ"].map((item, index) => (
          <div key={item} className="dayai-float rounded-3xl border border-black/10 bg-white/82 p-4 text-center shadow-sm" style={{ animationDelay: `${index * 0.3}s` }}>
            <div className="mx-auto size-12 rounded-full bg-gradient-to-br from-[#003A99] to-[#00AEEF]" />
            <div className="mt-3 text-xs font-bold text-slate-700">{item}</div>
          </div>
        ))}
      </div>
    </div>
  );
}

function getVisualItems(group: string) {
  if (group.includes("Kids")) {
    return ["Học an toàn", "Dự án sáng tạo", "Phụ huynh đồng hành"];
  }

  if (group.includes("Enterprise") || group.includes("doanh nghiệp")) {
    return ["Khảo sát nhu cầu", "Workshop theo phòng ban", "Báo cáo tiến độ"];
  }

  if (group.includes("Tài nguyên") || group.includes("Cập nhật")) {
    return ["Đọc hiểu nhanh", "Áp dụng bằng checklist", "Chọn khóa liên quan"];
  }

  return ["Đánh giá mục tiêu", "Học qua thực hành", "Ứng dụng vào thực tế"];
}
