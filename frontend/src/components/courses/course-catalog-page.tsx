"use client";

import Image from "next/image";
import Link from "next/link";
import { useMemo, useState } from "react";
import { CatalogCourse, catalogCourses, courseCategories, CourseCategory, getCategoryLabel } from "@/lib/course-catalog";
import { CourseAccessPanel } from "@/components/courses/course-access-panel";
import { PublicSiteFooter } from "@/components/public-site-footer";
import { PublicSiteHeader } from "@/components/public-site-header";

type CategoryFilter = "all" | CourseCategory;

const sortOptions = [
  { value: "featured", label: "Nổi bật" },
  { value: "popular", label: "Nhiều học viên" },
  { value: "rating", label: "Đánh giá cao" },
];

const courseImages: Record<CourseCategory, string> = {
  foundation: "/images/instructor_minh.png",
  prompt: "/images/instructor_hoang.png",
  work: "/images/instructor_minh.png",
  business: "/images/instructor_hoang.png",
  enterprise: "/images/instructor_hoang.png",
  kids: "/images/instructor_an.png",
};

export function CourseCatalogPage() {
  const [selectedCategory, setSelectedCategory] = useState<CategoryFilter>("all");
  const [query, setQuery] = useState("");
  const [sortBy, setSortBy] = useState("featured");
  const [selectedAccessCourse, setSelectedAccessCourse] = useState<CatalogCourse | null>(null);

  const filteredCourses = useMemo(() => {
    const normalizedQuery = query.trim().toLowerCase();

    const result = catalogCourses.filter((course) => {
      const matchesCategory = selectedCategory === "all" || course.category === selectedCategory;
      const matchesQuery =
        !normalizedQuery ||
        `${course.title} ${course.subtitle} ${course.audience} ${course.tools.join(" ")}`
          .toLowerCase()
          .includes(normalizedQuery);

      return matchesCategory && matchesQuery;
    });

    return result.sort((first, second) => {
      if (sortBy === "popular") {
        return second.students - first.students;
      }

      if (sortBy === "rating") {
        return second.rating - first.rating;
      }

      return Number(second.badge === "Nên bắt đầu") - Number(first.badge === "Nên bắt đầu");
    });
  }, [query, selectedCategory, sortBy]);

  return (
    <main className="min-h-screen bg-[var(--dayai-bg-subtle)] text-[var(--dayai-text)]">
      <PublicSiteHeader />

      <section className="relative isolate overflow-hidden border-b border-[var(--dayai-border)] bg-white">
        <div className="absolute inset-0 dayai-muted-grid opacity-60" />
        <div className="absolute inset-x-0 top-0 h-40 bg-white" />
        <div className="dayai-container relative grid gap-10 py-16 lg:grid-cols-[0.95fr_1.05fr] lg:items-center lg:py-24">
          <div>
            <div className="dayai-chip">DAYAI Course Catalog</div>
            <h1 className="mt-6 max-w-5xl text-4xl font-black leading-[1.06] sm:text-6xl">
              Khóa học AI
            </h1>
            <p className="mt-6 max-w-3xl text-base leading-8 text-[var(--dayai-text-muted)] sm:text-lg">
              Chọn khóa học theo mục tiêu: nhập môn AI, prompt, công việc, kinh doanh, doanh nghiệp hoặc trẻ em. Mỗi khóa đều nối với LMS, quiz và portal theo dõi tiến độ.
            </p>
            <div className="mt-8 flex flex-col gap-3 sm:flex-row">
              <Link href="#course-list" className="dayai-btn dayai-btn-primary">
                Xem danh sách khóa học
              </Link>
              <Link href="/dang-ky-tu-van/" className="dayai-btn dayai-btn-secondary">
                Nhận tư vấn lộ trình
              </Link>
            </div>
          </div>

          <div className="hidden rounded-[var(--dayai-radius-2xl)] border border-[var(--dayai-border)] bg-white p-4 shadow-[var(--dayai-shadow-md)] lg:block">
            <div className="dayai-dark rounded-[var(--dayai-radius-xl)] bg-[var(--dayai-bg)] p-6 text-[var(--dayai-text)]">
              <div className="text-xs font-black text-[var(--dayai-accent)]">Course finder</div>
              <div className="mt-3 text-2xl font-black">Tìm khóa theo mục tiêu học</div>
              <div className="mt-8 grid gap-3">
                {["Chọn nhóm học viên", "Lọc theo cấp độ", "Xem curriculum", "Đăng ký tư vấn"].map((item, index) => (
                  <div key={item} className="flex items-center gap-4 rounded-[var(--dayai-radius-lg)] bg-white/[0.06] p-4">
                    <span className="grid size-8 place-items-center rounded-full bg-white text-xs font-black text-[var(--dayai-primary)]">
                      {index + 1}
                    </span>
                    <span className="text-sm font-semibold text-[var(--dayai-text-muted)]">{item}</span>
                  </div>
                ))}
              </div>
            </div>
            <div className="mt-4 grid grid-cols-3 gap-3">
              {["6 khóa", "4 nhóm", "LMS + Quiz"].map((item) => (
                <div key={item} className="rounded-[var(--dayai-radius-lg)] border border-[var(--dayai-border)] bg-[var(--dayai-bg-subtle)] p-4 text-center text-xs font-black text-[var(--dayai-text-muted)]">
                  {item}
                </div>
              ))}
            </div>
          </div>
        </div>
      </section>

      <section id="course-list" className="dayai-section">
        <div className="dayai-container grid gap-8 lg:grid-cols-[280px_1fr] lg:items-start">
          <aside className="rounded-[var(--dayai-radius-2xl)] border border-[var(--dayai-border)] bg-white p-5 shadow-[var(--dayai-shadow-xs)] lg:sticky lg:top-28">
            <div className="flex items-center justify-between gap-4">
              <div>
                <div className="dayai-kicker">Categories</div>
                <h2 className="mt-2 text-xl font-black">Lọc khóa học</h2>
              </div>
              <span className="rounded-[var(--dayai-radius-full)] bg-[var(--dayai-surface-tint)] px-3 py-1 text-xs font-black text-[var(--dayai-primary)]">
                {filteredCourses.length}
              </span>
            </div>

            <div className="mt-6 grid gap-2">
              {courseCategories.map((category) => {
                const active = selectedCategory === category.key;
                const count =
                  category.key === "all"
                    ? catalogCourses.length
                    : catalogCourses.filter((course) => course.category === category.key).length;

                return (
                  <button
                    key={category.key}
                    type="button"
                    onClick={() => setSelectedCategory(category.key)}
                    className={`rounded-[var(--dayai-radius-lg)] border p-4 text-left transition ${
                      active
                        ? "border-[var(--dayai-primary)] bg-[var(--dayai-surface-tint)]"
                        : "border-[var(--dayai-border)] bg-white hover:border-[var(--dayai-primary)]"
                    }`}
                  >
                    <span className="flex items-center justify-between gap-3">
                      <span className="text-sm font-black">{category.label}</span>
                      <span className="text-xs font-black text-[var(--dayai-text-subtle)]">{count}</span>
                    </span>
                    <span className="mt-1 block text-xs leading-5 text-[var(--dayai-text-muted)]">{category.description}</span>
                  </button>
                );
              })}
            </div>
          </aside>

          <div>
            <div className="rounded-[var(--dayai-radius-2xl)] border border-[var(--dayai-border)] bg-white p-4 shadow-[var(--dayai-shadow-xs)]">
              <div className="grid gap-3 md:grid-cols-[1fr_220px]">
                <label className="grid gap-2">
                  <span className="text-sm font-black">Tìm khóa học</span>
                  <input
                    value={query}
                    onChange={(event) => setQuery(event.target.value)}
                    placeholder="Nhập tên khóa, công cụ hoặc mục tiêu học..."
                    className="form-control"
                  />
                </label>
                <label className="grid gap-2">
                  <span className="text-sm font-black">Sắp xếp</span>
                  <select value={sortBy} onChange={(event) => setSortBy(event.target.value)} className="form-control">
                    {sortOptions.map((option) => (
                      <option key={option.value} value={option.value}>
                        {option.label}
                      </option>
                    ))}
                  </select>
                </label>
              </div>
              <div className="mt-4 flex flex-wrap items-center justify-between gap-3 text-sm font-semibold text-[var(--dayai-text-muted)]">
                <span>
                  Hiển thị {filteredCourses.length} khóa học
                  {selectedCategory !== "all" ? ` trong nhóm ${getCategoryLabel(selectedCategory)}` : ""}
                </span>
                <button
                  type="button"
                  onClick={() => {
                    setSelectedCategory("all");
                    setQuery("");
                    setSortBy("featured");
                  }}
                  className="text-sm font-black text-[var(--dayai-primary)]"
                >
                  Xóa bộ lọc
                </button>
              </div>
            </div>

            <div className="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
              {filteredCourses.map((course) => (
                <CourseCard key={course.slug} course={course} onStartAccess={setSelectedAccessCourse} />
              ))}
            </div>
          </div>
        </div>
      </section>

      {selectedAccessCourse ? (
        <CourseAccessPanel
          key={selectedAccessCourse.slug}
          course={selectedAccessCourse}
          hideTrigger
          open={Boolean(selectedAccessCourse)}
          onOpenChange={(nextOpen) => {
            if (!nextOpen) {
              setSelectedAccessCourse(null);
            }
          }}
        />
      ) : null}

      <PublicSiteFooter />
    </main>
  );
}

function compactActionLabel(course: CatalogCourse) {
  if (course.accessType === "paid") {
    return "Thanh toán";
  }

  if (course.accessType === "free_funnel") {
    return "Học miễn phí";
  }

  return "Tư vấn";
}

function CourseCard({ course, onStartAccess }: { course: CatalogCourse; onStartAccess: (course: CatalogCourse) => void }) {
  const courseHref = `/khoa-hoc/${course.slug}/`;

  return (
    <article
      data-course-card={course.slug}
      className="group flex h-full flex-col overflow-hidden rounded-[var(--dayai-radius-2xl)] border border-[var(--dayai-border)] bg-white shadow-[var(--dayai-shadow-xs)] transition hover:-translate-y-0.5 hover:border-[var(--dayai-primary)] hover:shadow-[var(--dayai-shadow-sm)]"
    >
      <Link href={courseHref} className="block">
        <div className="relative aspect-[16/10] bg-[var(--dayai-surface-muted)]">
          <Image
            src={courseImages[course.category]}
            alt={course.title}
            fill
            sizes="(min-width: 1280px) 25vw, (min-width: 768px) 50vw, 100vw"
            className="object-cover transition duration-300 group-hover:scale-[1.03]"
          />
          <div className="absolute inset-0 bg-gradient-to-t from-slate-950/55 via-slate-950/8 to-transparent" />
          <div className="absolute left-4 top-4 rounded-[var(--dayai-radius-full)] bg-white px-3 py-1 text-xs font-black text-[var(--dayai-primary)]">
            {course.badge}
          </div>
          <div className="absolute bottom-4 left-4 right-4 flex items-center justify-between gap-3 text-xs font-black text-white">
            <span>{getCategoryLabel(course.category)}</span>
            <span>{course.rating.toFixed(1)} / 5</span>
          </div>
        </div>
      </Link>

      <div className="flex flex-1 flex-col gap-4 p-5">
        <div>
          <div className="text-xs font-black text-[var(--dayai-primary)]">{course.audience}</div>
          <Link href={courseHref} className="mt-2 block text-xl font-black leading-tight group-hover:text-[var(--dayai-primary)]">
            {course.title}
          </Link>
          <p className="mt-3 line-clamp-2 text-sm leading-6 text-[var(--dayai-text-muted)]">{course.subtitle}</p>
        </div>

        <div className="grid grid-cols-3 gap-2 text-center text-xs font-bold text-[var(--dayai-text-muted)]">
          <span className="rounded-[var(--dayai-radius-md)] bg-[var(--dayai-bg-subtle)] px-2 py-3">{course.sessions}</span>
          <span className="rounded-[var(--dayai-radius-md)] bg-[var(--dayai-bg-subtle)] px-2 py-3">{course.lessons} bài</span>
          <span className="rounded-[var(--dayai-radius-md)] bg-[var(--dayai-bg-subtle)] px-2 py-3">{course.students} HV</span>
        </div>

        <div className="mt-auto grid gap-4 border-t border-[var(--dayai-border)] pt-4">
          <div className="min-h-12">
            <div>
            <div className="text-xs font-semibold text-[var(--dayai-text-subtle)]">Học phí</div>
              <div className="text-sm font-black">{course.price}</div>
            </div>
          </div>
          <div className="grid grid-cols-2 gap-3">
            <Link href={courseHref} className="dayai-btn dayai-btn-secondary h-12 w-full justify-center px-3 py-0 text-xs">
              Chi tiết
            </Link>
            {course.accessType === "consultation" ? (
              <Link
                href={`/dang-ky-tu-van/?course=${course.slug}`}
                data-course-action={course.slug}
                className="dayai-btn dayai-btn-primary h-12 w-full justify-center px-3 py-0 text-xs"
              >
                Tư vấn
              </Link>
            ) : (
              <button
                type="button"
                data-course-action={course.slug}
                onClick={() => onStartAccess(course)}
                className="dayai-btn dayai-btn-primary h-12 w-full justify-center px-3 py-0 text-xs"
              >
                {compactActionLabel(course)}
              </button>
            )}
          </div>
        </div>
      </div>
    </article>
  );
}
