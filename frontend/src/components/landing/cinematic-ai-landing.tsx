"use client";

import Link from "next/link";
import { FormEvent, useState } from "react";
import { PublicSiteFooter } from "@/components/public-site-footer";
import { PublicSiteHeader } from "@/components/public-site-header";

const videoUrl =
  "https://d8j0ntlcm91z4.cloudfront.net/user_38xzZboKViGWJOttwIXH07lWA1P/hf_20260328_083109_283f3553-e28f-428b-a723-d639c617eb2b.mp4";

type CourseModule = {
  sort_order: number;
  title: string;
  description?: string | null;
  duration_minutes?: number | null;
  learning_objectives?: string[];
};

export type LandingCourse = {
  name: string;
  subtitle?: string | null;
  slug: string;
  course_code?: string | null;
  audience_type?: string | null;
  level?: string | null;
  learning_format?: string | null;
  short_description?: string | null;
  description?: string | null;
  outcomes?: string[];
  who_should_join?: string[];
  prerequisites?: string[];
  tools_covered?: string[];
  duration_hours?: number | null;
  default_session_count?: number | null;
  price_label?: string | null;
  primary_cta?: string | null;
  modules?: CourseModule[];
  seo?: {
    title?: string | null;
    description?: string | null;
    canonical_url?: string | null;
  };
};

type SubmitState = "idle" | "submitting" | "success" | "error";

type NormalizedCourse = LandingCourse & {
  subtitle: string;
  short_description: string;
  description: string;
  outcomes: string[];
  who_should_join: string[];
  prerequisites: string[];
  tools_covered: string[];
  primary_cta: string;
  modules: CourseModule[];
};

const fallbackCourse: LandingCourse = {
  name: "AI Căn Bản",
  subtitle: "Học AI bài bản để làm chủ tương lai số",
  slug: "ai-can-ban",
  course_code: "AI-FUNDAMENTALS",
  audience_type: "mixed",
  level: "beginner",
  learning_format: "hybrid",
  short_description:
    "Khóa học AI Căn Bản giúp người mới hiểu đúng, dùng đúng và ứng dụng AI vào học tập, công việc và vận hành doanh nghiệp.",
  description:
    "Nội dung tập trung vào thực hành, ví dụ thật và cách biến AI thành trợ lý cá nhân cho học tập, công việc hoặc đội nhóm.",
  outcomes: [
    "Hiểu đúng AI và biết chọn công cụ phù hợp",
    "Viết prompt rõ mục tiêu, dễ kiểm soát đầu ra",
    "Ứng dụng AI vào học tập, công việc và vận hành",
  ],
  who_should_join: [
    "Phụ huynh muốn con học AI an toàn",
    "Sinh viên muốn học nhanh và làm bài tốt hơn",
    "Người đi làm muốn tăng năng suất cá nhân",
    "Chủ doanh nghiệp muốn hiểu cách ứng dụng AI",
  ],
  tools_covered: ["ChatGPT", "Canva AI", "Gemini", "Workflow AI"],
  duration_hours: 12,
  default_session_count: 6,
  price_label: "Liên hệ tư vấn",
  primary_cta: "Đăng ký học thử",
  modules: [
    {
      sort_order: 1,
      title: "Tổng quan AI và tư duy sử dụng an toàn",
      description: "Hiểu AI là gì, giới hạn của AI và cách học AI không lệ thuộc.",
      duration_minutes: 90,
    },
    {
      sort_order: 2,
      title: "Prompt Engineering căn bản",
      description: "Biết đặt yêu cầu rõ mục tiêu, ngữ cảnh, vai trò và tiêu chí đầu ra.",
      duration_minutes: 120,
    },
    {
      sort_order: 3,
      title: "Ứng dụng AI vào học tập và công việc",
      description: "Thực hành tạo tài liệu, slide, kế hoạch, báo cáo và nội dung.",
      duration_minutes: 120,
    },
    {
      sort_order: 4,
      title: "Xây workflow cá nhân với AI",
      description: "Biến AI thành trợ lý hỗ trợ công việc lặp lại hằng ngày.",
      duration_minutes: 120,
    },
  ],
};

export function CinematicAiLanding({ course = fallbackCourse }: { course?: LandingCourse }) {
  const normalizedCourse = mergeCourse(course);
  const [submitState, setSubmitState] = useState<SubmitState>("idle");
  const [message, setMessage] = useState("");

  async function handleSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setSubmitState("submitting");
    setMessage("");

    const form = event.currentTarget;
    const formData = new FormData(form);
    const payload = {
      ...Object.fromEntries(formData.entries()),
      ...getTrackingPayload(normalizedCourse.slug),
    };

    try {
      const response = await fetch("/api/leads", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(payload),
      });

      const result = await response.json();

      if (!response.ok) {
        throw new Error(result.message ?? "Không thể gửi thông tin.");
      }

      form.reset();
      setSubmitState("success");
      setMessage("Đã nhận thông tin. DAYAI sẽ liên hệ tư vấn sớm.");
    } catch (error) {
      setSubmitState("error");
      setMessage(error instanceof Error ? error.message : "Có lỗi xảy ra, vui lòng thử lại.");
    }
  }

  return (
    <main className="min-h-screen bg-[var(--dayai-bg-subtle)] text-[var(--dayai-text)]">
      <PublicSiteHeader />

      <section className="relative isolate overflow-hidden border-b border-[var(--dayai-border)] bg-white">
        <div className="absolute inset-0 dayai-muted-grid opacity-60" />
        <div className="absolute inset-x-0 top-0 h-40 bg-white" />

        <div className="dayai-container relative grid gap-10 py-16 lg:grid-cols-[0.95fr_1.05fr] lg:items-center lg:py-24">
          <div className="animate-fade-rise">
            <div className="dayai-chip">
              {getAudienceLabel(normalizedCourse.audience_type)} | {getLevelLabel(normalizedCourse.level)} | {getFormatLabel(normalizedCourse.learning_format)}
            </div>
            <h1 className="mt-6 max-w-5xl text-4xl font-black leading-[1.06] sm:text-6xl">
              {normalizedCourse.name}
              <span className="mt-3 block text-[var(--dayai-primary)]">{normalizedCourse.subtitle}</span>
            </h1>
            <p className="mt-6 max-w-3xl text-base leading-8 text-[var(--dayai-text-muted)] sm:text-lg">
              {normalizedCourse.short_description}
            </p>
            <div className="mt-8 flex flex-col gap-3 sm:flex-row">
              <Link href="#lead-form" className="dayai-btn dayai-btn-primary">
                {normalizedCourse.primary_cta}
              </Link>
              <Link href="#curriculum" className="dayai-btn dayai-btn-secondary">
                Xem lộ trình
              </Link>
            </div>

            <div className="mt-8 grid grid-cols-3 gap-2 rounded-[var(--dayai-radius-xl)] border border-[var(--dayai-border)] bg-white p-3 shadow-[var(--dayai-shadow-xs)] lg:hidden">
              {[
                formatDuration(normalizedCourse.duration_hours),
                formatSessions(normalizedCourse.default_session_count),
                "LMS + Quiz",
              ].map((item) => (
                <div key={item} className="rounded-[var(--dayai-radius-lg)] bg-[var(--dayai-bg-subtle)] px-3 py-4 text-center text-xs font-black text-[var(--dayai-text-muted)]">
                  {item}
                </div>
              ))}
            </div>
          </div>

          <div className="animate-fade-rise-delay hidden rounded-[var(--dayai-radius-2xl)] border border-[var(--dayai-border)] bg-white p-4 shadow-[var(--dayai-shadow-md)] lg:block">
            <div className="relative aspect-[16/10] overflow-hidden rounded-[var(--dayai-radius-xl)] bg-[var(--dayai-surface-muted)]">
              <video muted loop playsInline preload="metadata" autoPlay className="size-full object-cover" src={videoUrl} />
            </div>
            <div className="grid gap-3 p-2 pt-4 sm:grid-cols-3">
              {normalizedCourse.outcomes.slice(0, 3).map((outcome) => (
                <div key={outcome} className="rounded-[var(--dayai-radius-lg)] bg-[var(--dayai-bg-subtle)] p-4 text-sm font-semibold leading-6 text-[var(--dayai-text-muted)]">
                  {outcome}
                </div>
              ))}
            </div>
          </div>
        </div>
      </section>

      <section id="curriculum" className="dayai-section bg-white">
        <div className="dayai-container grid gap-10 lg:grid-cols-[0.85fr_1.15fr]">
          <div>
            <div className="dayai-kicker">Lộ trình khóa học</div>
            <h2 className="mt-4 text-3xl font-black leading-tight sm:text-4xl">
              Tinh gọn, học xong dùng được ngay.
            </h2>
            <p className="mt-5 text-sm leading-7 text-[var(--dayai-text-muted)]">{normalizedCourse.description}</p>
            <div className="mt-8 grid gap-3 sm:grid-cols-3 lg:grid-cols-1">
              <StatCard label="Thời lượng" value={formatDuration(normalizedCourse.duration_hours)} />
              <StatCard label="Số buổi" value={formatSessions(normalizedCourse.default_session_count)} />
              <StatCard label="Học phí" value={normalizedCourse.price_label ?? "Liên hệ tư vấn"} />
            </div>
          </div>

          <div className="grid gap-4">
            {normalizedCourse.modules.map((item, index) => (
              <article key={`${item.sort_order}-${item.title}`} className="dayai-card flex items-start gap-4 p-5">
                <div className="grid size-10 shrink-0 place-items-center rounded-[var(--dayai-radius-full)] bg-[var(--dayai-primary)] text-sm font-black text-white">
                  {index + 1}
                </div>
                <div>
                  <h3 className="text-lg font-black">{item.title}</h3>
                  {item.description ? (
                    <p className="mt-2 text-sm leading-6 text-[var(--dayai-text-muted)]">{item.description}</p>
                  ) : null}
                </div>
              </article>
            ))}
          </div>
        </div>
      </section>

      <section className="dayai-dark dayai-section bg-[var(--dayai-bg)] text-[var(--dayai-text)]">
        <div className="dayai-container grid gap-10 lg:grid-cols-[0.85fr_1.15fr] lg:items-end">
          <div>
            <div className="dayai-kicker text-[var(--dayai-accent)]">Phù hợp cho ai</div>
            <h2 className="mt-4 text-3xl font-black leading-tight sm:text-4xl">
              Đúng người, đúng mục tiêu học.
            </h2>
            <p className="mt-5 text-sm leading-7 text-[var(--dayai-text-muted)]">
              DAYAI thiết kế khóa học theo hành trình tăng trưởng con người: học để hiểu, thực hành để làm được, và có định hướng ứng dụng sau khóa.
            </p>
          </div>
          <div className="grid gap-3 sm:grid-cols-2">
            {normalizedCourse.who_should_join.map((item) => (
              <div key={item} className="rounded-[var(--dayai-radius-lg)] border border-[var(--dayai-border)] bg-[var(--dayai-surface)] p-5 text-sm leading-6 text-[var(--dayai-text-muted)]">
                {item}
              </div>
            ))}
          </div>
        </div>
      </section>

      <section className="bg-white py-24">
        <div className="dayai-container grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:items-start">
          <div>
            <div className="dayai-kicker">Đăng ký tư vấn</div>
            <h2 className="mt-4 text-3xl font-black leading-tight sm:text-4xl">
              Nhận tư vấn khóa {normalizedCourse.name}.
            </h2>
            <p className="mt-5 max-w-xl text-sm leading-7 text-[var(--dayai-text-muted)]">
              Để lại thông tin, đội ngũ DAYAI sẽ liên hệ tư vấn lịch học, lộ trình và hình thức phù hợp với nhu cầu của bạn.
            </p>
            <div className="mt-6 flex flex-wrap gap-2">
              {normalizedCourse.tools_covered.map((tool) => (
                <span key={tool} className="dayai-chip text-[var(--dayai-primary)]">
                  {tool}
                </span>
              ))}
            </div>
          </div>

          <CourseLeadForm
            course={normalizedCourse}
            submitState={submitState}
            message={message}
            onSubmit={handleSubmit}
          />
        </div>
      </section>

      <PublicSiteFooter />
    </main>
  );
}

function CourseLeadForm({
  course,
  submitState,
  message,
  onSubmit,
}: {
  course: NormalizedCourse;
  submitState: SubmitState;
  message: string;
  onSubmit: (event: FormEvent<HTMLFormElement>) => void;
}) {
  return (
    <form
      id="lead-form"
      onSubmit={onSubmit}
      className="overflow-hidden rounded-[var(--dayai-radius-2xl)] border border-[var(--dayai-border)] bg-white shadow-[var(--dayai-shadow-md)]"
    >
      <div className="border-b border-[var(--dayai-border)] bg-[var(--dayai-bg-subtle)] p-6 sm:p-8">
        <div className="dayai-kicker">Đăng ký khóa học</div>
        <h3 className="mt-3 text-2xl font-black leading-tight">Nhận tư vấn khóa {course.name}</h3>
        <p className="mt-3 text-sm leading-6 text-[var(--dayai-text-muted)]">
          DAYAI sẽ tư vấn lịch học, lộ trình và hình thức học phù hợp với nhu cầu của bạn.
        </p>
      </div>

      <div className="grid gap-5 p-6 sm:p-8">
        <div className="grid gap-5 md:grid-cols-2">
          <LandingField label="Họ tên" name="full_name" placeholder="Nguyễn Minh Anh" required />
          <LandingField label="Số điện thoại" name="phone" placeholder="0901 000 001" required />
        </div>
        <LandingField label="Email" name="email" placeholder="ban@example.com" type="email" />
        <label className="grid gap-2">
          <span className="text-sm font-black text-[var(--dayai-text)]">Nhu cầu</span>
          <textarea name="learning_goal" rows={5} placeholder="Tôi muốn học AI để..." className="form-control resize-none" />
        </label>

        <input type="hidden" name="lead_type" value={getLeadType(course.audience_type)} />
        <input type="hidden" name="interested_course_id" value={course.course_code ?? course.slug} />
        <input type="hidden" name="course_slug" value={course.slug} />
        <input type="hidden" name="request_type" value="trial" />
        <input type="hidden" name="preferred_contact_method" value="phone" />

        <div className="rounded-[var(--dayai-radius-xl)] border border-[var(--dayai-border)] bg-[var(--dayai-bg-subtle)] p-4">
          <button type="submit" disabled={submitState === "submitting"} className="dayai-btn dayai-btn-primary w-full disabled:cursor-not-allowed disabled:opacity-60">
            {submitState === "submitting" ? "Đang gửi..." : course.primary_cta}
          </button>
          <p className="mt-3 text-center text-xs font-semibold leading-5 text-[var(--dayai-text-subtle)]">
            Thông tin được dùng để tư vấn khóa học, không chia sẻ cho bên thứ ba.
          </p>
        </div>

        {message ? (
          <p
            className={
              submitState === "error"
                ? "rounded-[var(--dayai-radius-lg)] bg-red-50 p-4 text-sm font-semibold text-[var(--dayai-danger)]"
                : "rounded-[var(--dayai-radius-lg)] bg-green-50 p-4 text-sm font-semibold text-[var(--dayai-success)]"
            }
          >
            {message}
          </p>
        ) : null}
      </div>
    </form>
  );
}

function LandingField({
  label,
  name,
  placeholder,
  required = false,
  type = "text",
}: {
  label: string;
  name: string;
  placeholder: string;
  required?: boolean;
  type?: string;
}) {
  return (
    <label className="grid gap-2">
      <span className="text-sm font-black text-[var(--dayai-text)]">{label}</span>
      <input name={name} required={required} type={type} placeholder={placeholder} className="form-control" />
    </label>
  );
}

function StatCard({ label, value }: { label: string; value: string }) {
  return (
    <div className="dayai-card p-5">
      <div className="text-xs font-black text-[var(--dayai-primary)]">{label}</div>
      <div className="mt-2 text-lg font-black">{value}</div>
    </div>
  );
}

function mergeCourse(course: LandingCourse): NormalizedCourse {
  return {
    ...fallbackCourse,
    ...course,
    subtitle: course.subtitle || fallbackCourse.subtitle || "",
    short_description: course.short_description || fallbackCourse.short_description || "",
    description: course.description || fallbackCourse.description || "",
    outcomes: course.outcomes?.length ? course.outcomes : fallbackCourse.outcomes ?? [],
    who_should_join: course.who_should_join?.length ? course.who_should_join : fallbackCourse.who_should_join ?? [],
    prerequisites: course.prerequisites?.length ? course.prerequisites : fallbackCourse.prerequisites ?? [],
    tools_covered: course.tools_covered?.length ? course.tools_covered : fallbackCourse.tools_covered ?? [],
    primary_cta: course.primary_cta || fallbackCourse.primary_cta || "Đăng ký học thử",
    modules: course.modules?.length ? course.modules : fallbackCourse.modules ?? [],
  };
}

function formatDuration(hours?: number | null) {
  return hours ? `${hours} giờ` : "Theo lộ trình";
}

function formatSessions(count?: number | null) {
  return count ? `${count} buổi` : "Theo lớp";
}

function getAudienceLabel(audience?: string | null) {
  return {
    kids: "AI Kids",
    student: "AI Student",
    work: "AI Work",
    business: "AI Business",
    enterprise: "AI Enterprise",
    mixed: "Mọi đối tượng",
  }[audience ?? "mixed"] ?? "Mọi đối tượng";
}

function getLevelLabel(level?: string | null) {
  return {
    beginner: "Cơ bản",
    intermediate: "Trung cấp",
    advanced: "Nâng cao",
  }[level ?? "beginner"] ?? "Cơ bản";
}

function getFormatLabel(format?: string | null) {
  return {
    online: "Online",
    offline: "Offline",
    hybrid: "Kết hợp",
    in_house: "Nội bộ doanh nghiệp",
  }[format ?? "hybrid"] ?? "Kết hợp";
}

function getLeadType(audience?: string | null) {
  return {
    kids: "parent",
    student: "student",
    work: "student",
    business: "business_owner",
    enterprise: "company",
  }[audience ?? "student"] ?? "student";
}

function getTrackingPayload(courseSlug: string) {
  const params = new URLSearchParams(window.location.search);
  const affiliateCode = params.get("aff") ?? params.get("affiliate") ?? params.get("ref") ?? "";
  const referralCode = params.get("ref") ?? "";
  const utmSource = params.get("utm_source") ?? (affiliateCode ? "affiliate" : "website");
  const pageUrl = window.location.href;

  return {
    utm_source: utmSource,
    utm_medium: params.get("utm_medium") ?? "course_landing",
    utm_campaign: params.get("utm_campaign") ?? courseSlug,
    utm_content: params.get("utm_content") ?? "",
    utm_term: params.get("utm_term") ?? "",
    page_url: pageUrl,
    landing_page: pageUrl,
    referrer_url: document.referrer,
    affiliate_code: affiliateCode,
    referral_code: referralCode,
    click_id: params.get("click_id") ?? params.get("fbclid") ?? params.get("gclid") ?? "",
    first_touch_source: utmSource,
    last_touch_source: utmSource,
  };
}
