import Image from "next/image";
import Link from "next/link";
import { CatalogCourse, getAccessFlowLabel, getCategoryLabel } from "@/lib/course-catalog";
import { CourseAccessPanel } from "@/components/courses/course-access-panel";
import { PublicSiteFooter } from "@/components/public-site-footer";
import { PublicSiteHeader } from "@/components/public-site-header";

const courseImages = {
  foundation: "/images/instructor_minh.png",
  prompt: "/images/instructor_hoang.png",
  work: "/images/instructor_minh.png",
  business: "/images/instructor_hoang.png",
  enterprise: "/images/instructor_hoang.png",
  kids: "/images/instructor_an.png",
};

export function CourseDetailPage({ course }: { course: CatalogCourse }) {
  return (
    <main className="min-h-screen bg-[var(--dayai-bg-subtle)] text-[var(--dayai-text)]">
      <PublicSiteHeader />

      <section className="relative isolate overflow-hidden border-b border-[var(--dayai-border)] bg-white">
        <div className="absolute inset-0 dayai-muted-grid opacity-60" />
        <div className="absolute inset-x-0 top-0 h-40 bg-white" />
        <div className="dayai-container relative grid gap-10 py-10 sm:py-16 lg:grid-cols-[0.92fr_1.08fr] lg:items-center lg:py-24">
          <div>
            <div className="flex flex-wrap gap-2">
              <span className="dayai-chip">{getCategoryLabel(course.category)}</span>
              <span className="dayai-chip">{course.level}</span>
              <span className="dayai-chip">{course.format}</span>
              <span className="dayai-chip">{getAccessFlowLabel(course.accessType)}</span>
            </div>
            <h1 className="mt-5 max-w-5xl text-4xl font-black leading-[1.06] sm:mt-6 sm:text-6xl">{course.title}</h1>
            <p className="mt-5 max-w-3xl text-base leading-7 text-[var(--dayai-text-muted)] sm:mt-6 sm:text-lg sm:leading-8">
              {course.description}
            </p>
            <div className="mt-6 flex flex-col gap-3 sm:mt-8 sm:flex-row">
              <CourseAccessPanel course={course} variant="hero" />
              <Link href="#curriculum" className="dayai-btn dayai-btn-secondary">
                Xem chương trình
              </Link>
            </div>
            <div className="mt-6 grid grid-cols-3 gap-2 rounded-[var(--dayai-radius-xl)] border border-[var(--dayai-border)] bg-white p-3 shadow-[var(--dayai-shadow-xs)] sm:mt-8 lg:hidden">
              {[
                [course.duration, "Thời lượng"],
                [course.sessions, "Buổi học"],
                [`${course.lessons} bài`, "LMS"],
              ].map(([value, label]) => (
                <div key={label} className="rounded-[var(--dayai-radius-lg)] bg-[var(--dayai-bg-subtle)] px-3 py-4 text-center">
                  <div className="text-xs font-black text-[var(--dayai-text)]">{value}</div>
                  <div className="mt-1 text-[11px] font-bold text-[var(--dayai-text-subtle)]">{label}</div>
                </div>
              ))}
            </div>
          </div>

          <div className="hidden overflow-hidden rounded-[var(--dayai-radius-2xl)] border border-[var(--dayai-border)] bg-white p-4 shadow-[var(--dayai-shadow-md)] lg:block">
            <div className="relative aspect-[16/10] overflow-hidden rounded-[var(--dayai-radius-xl)] bg-[var(--dayai-surface-muted)]">
              <Image
                src={courseImages[course.category]}
                alt={course.title}
                fill
                priority
                sizes="(min-width: 1024px) 50vw, 100vw"
                className="object-cover"
              />
              <div className="absolute inset-0 bg-gradient-to-t from-slate-950/62 via-slate-950/10 to-transparent" />
              <div className="absolute bottom-5 left-5 right-5 grid gap-3 text-white sm:grid-cols-3">
                {[
                  [course.duration, "Thời lượng"],
                  [course.sessions, "Số buổi"],
                  [`${course.lessons} bài`, "Bài học"],
                ].map(([value, label]) => (
                  <div key={label} className="rounded-[var(--dayai-radius-lg)] bg-white/14 p-4 backdrop-blur">
                    <div className="text-lg font-black">{value}</div>
                    <div className="mt-1 text-xs font-bold text-white/75">{label}</div>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </div>
      </section>

      <section className="dayai-section">
        <div className="dayai-container grid gap-8 lg:grid-cols-[1fr_340px] lg:items-start">
          <div className="grid gap-8">
            <section className="rounded-[var(--dayai-radius-2xl)] border border-[var(--dayai-border)] bg-white p-6 shadow-[var(--dayai-shadow-xs)] sm:p-8">
              <div className="dayai-kicker">Tổng quan khóa học</div>
              <h2 className="mt-4 text-3xl font-black leading-tight">Bạn sẽ làm được gì sau khóa học?</h2>
              <div className="mt-8 grid gap-4 md:grid-cols-2">
                {course.outcomes.map((outcome, index) => (
                  <div key={outcome} className="flex gap-4 rounded-[var(--dayai-radius-lg)] bg-[var(--dayai-bg-subtle)] p-4">
                    <span className="grid size-8 shrink-0 place-items-center rounded-full bg-[var(--dayai-primary)] text-xs font-black text-white">
                      {index + 1}
                    </span>
                    <span className="text-sm font-semibold leading-6 text-[var(--dayai-text-muted)]">{outcome}</span>
                  </div>
                ))}
              </div>
            </section>

            <section id="curriculum" className="rounded-[var(--dayai-radius-2xl)] border border-[var(--dayai-border)] bg-white p-6 shadow-[var(--dayai-shadow-xs)] sm:p-8">
              <div className="dayai-kicker">Curriculum</div>
              <h2 className="mt-4 text-3xl font-black leading-tight">Chương trình học</h2>
              <div className="mt-8 grid gap-4">
                {course.curriculum.map((module, index) => (
                  <article key={module.title} className="rounded-[var(--dayai-radius-xl)] border border-[var(--dayai-border)]">
                    <div className="flex items-center justify-between gap-4 border-b border-[var(--dayai-border)] p-5">
                      <div>
                        <div className="text-xs font-black text-[var(--dayai-primary)]">Module {index + 1}</div>
                        <h3 className="mt-2 text-xl font-black">{module.title}</h3>
                      </div>
                      <span className="rounded-[var(--dayai-radius-full)] bg-[var(--dayai-surface-tint)] px-3 py-1 text-xs font-black text-[var(--dayai-primary)]">
                        {module.lessons.length} bài
                      </span>
                    </div>
                    <div className="grid gap-2 p-5">
                      {module.lessons.map((lesson, lessonIndex) => (
                        <div key={lesson} className="flex items-center gap-3 rounded-[var(--dayai-radius-lg)] bg-[var(--dayai-bg-subtle)] p-4">
                          <span className="grid size-7 shrink-0 place-items-center rounded-full bg-white text-xs font-black text-[var(--dayai-primary)]">
                            {lessonIndex + 1}
                          </span>
                          <span className="text-sm font-bold text-[var(--dayai-text-muted)]">{lesson}</span>
                        </div>
                      ))}
                    </div>
                  </article>
                ))}
              </div>
            </section>
          </div>

          <aside className="rounded-[var(--dayai-radius-2xl)] border border-[var(--dayai-border)] bg-white p-5 shadow-[var(--dayai-shadow-md)] lg:sticky lg:top-28">
            <div className="rounded-[var(--dayai-radius-xl)] bg-[var(--dayai-bg-subtle)] p-5">
              <div className="text-xs font-black text-[var(--dayai-primary)]">Thông tin khóa học</div>
              <div className="mt-4 grid gap-3 text-sm">
                {[
                  ["Học phí", course.price],
                  ["Luồng học", getAccessFlowLabel(course.accessType)],
                  ["Đối tượng", course.audience],
                  ["Cấp độ", course.level],
                  ["Hình thức", course.format],
                  ["Đánh giá", `${course.rating.toFixed(1)} / 5`],
                  ["Học viên", `${course.students}+`],
                ].map(([label, value]) => (
                  <div key={label} className="flex items-center justify-between gap-4 border-b border-[var(--dayai-border)] pb-3 last:border-0 last:pb-0">
                    <span className="text-[var(--dayai-text-muted)]">{label}</span>
                    <span className="text-right font-black">{value}</span>
                  </div>
                ))}
              </div>
            </div>

            <div className="mt-5">
              <CourseAccessPanel course={course} variant="sidebar" />
            </div>

            <div className="mt-6">
              <div className="text-sm font-black">Công cụ trong khóa học</div>
              <div className="mt-3 flex flex-wrap gap-2">
                {course.tools.map((tool) => (
                  <span key={tool} className="dayai-chip">
                    {tool}
                  </span>
                ))}
              </div>
            </div>
          </aside>
        </div>
      </section>

      <PublicSiteFooter />
    </main>
  );
}
