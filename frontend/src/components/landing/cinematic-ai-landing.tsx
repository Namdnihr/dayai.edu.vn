"use client";

import Link from "next/link";
import { FormEvent, useEffect, useRef, useState } from "react";
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
  const videoRef = useRef<HTMLVideoElement>(null);
  const [videoOpacity, setVideoOpacity] = useState(0);
  const [submitState, setSubmitState] = useState<SubmitState>("idle");
  const [message, setMessage] = useState("");

  useEffect(() => {
    const video = videoRef.current;

    if (!video) {
      return;
    }

    let animationFrame = 0;
    let endedTimer: ReturnType<typeof setTimeout> | null = null;

    const updateVideoOpacity = () => {
      const { currentTime, duration } = video;

      if (Number.isFinite(duration) && duration > 0) {
        const fadeInOpacity = Math.min(currentTime / 0.5, 1);
        const fadeOutOpacity = Math.min((duration - currentTime) / 0.5, 1);

        setVideoOpacity(Math.max(0, Math.min(fadeInOpacity, fadeOutOpacity)));
      }

      animationFrame = requestAnimationFrame(updateVideoOpacity);
    };

    const handleEnded = () => {
      setVideoOpacity(0);

      endedTimer = setTimeout(() => {
        video.currentTime = 0;
        void video.play();
      }, 100);
    };

    video.addEventListener("ended", handleEnded);
    void video.play();
    animationFrame = requestAnimationFrame(updateVideoOpacity);

    return () => {
      cancelAnimationFrame(animationFrame);
      video.removeEventListener("ended", handleEnded);

      if (endedTimer) {
        clearTimeout(endedTimer);
      }
    };
  }, []);

  async function handleSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setSubmitState("submitting");
    setMessage("");

    const form = event.currentTarget;
    const formData = new FormData(form);
    const payload = Object.fromEntries(formData.entries());

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
      setMessage(
        error instanceof Error
          ? error.message
          : "Có lỗi xảy ra, vui lòng thử lại.",
      );
    }
  }

  return (
    <main className="relative min-h-screen overflow-hidden bg-white text-black">
      <div className="pointer-events-none absolute inset-0 z-0 overflow-hidden">
        <video
          ref={videoRef}
          muted
          playsInline
          preload="metadata"
          className="absolute inset-x-0 bottom-0 top-[320px] h-[calc(100%-320px)] w-full object-cover opacity-70 sm:top-[360px] sm:h-[calc(100%-360px)]"
          src={videoUrl}
          style={{ opacity: videoOpacity }}
        />
        <div className="absolute inset-0 bg-gradient-to-b from-white via-white/72 to-white" />
        <div className="absolute inset-0 bg-[radial-gradient(circle_at_50%_32%,rgba(0,58,153,0.14),transparent_30%),radial-gradient(circle_at_70%_78%,rgba(0,174,239,0.18),transparent_28%)]" />
        <div className="absolute inset-x-6 top-28 h-[520px] rounded-full border border-black/[0.04] bg-[linear-gradient(90deg,rgba(15,23,42,0.04)_1px,transparent_1px),linear-gradient(rgba(15,23,42,0.04)_1px,transparent_1px)] bg-[size:72px_72px] blur-[0.2px] [mask-image:radial-gradient(ellipse_at_center,black,transparent_70%)]" />
        <div className="absolute left-1/2 top-[44%] hidden size-[520px] -translate-x-1/2 -translate-y-1/2 rounded-full border border-blue-500/10 sm:block" />
        <div className="absolute left-1/2 top-[44%] hidden size-[340px] -translate-x-1/2 -translate-y-1/2 rounded-full border border-blue-500/10 sm:block" />
      </div>

      <div className="relative z-20">
        <PublicSiteHeader />
      </div>

      <section className="relative z-10 mx-auto flex min-h-[calc(100vh-88px)] max-w-7xl flex-col items-center justify-center px-5 pb-16 pt-[calc(8rem-75px)] text-center sm:px-8 lg:pb-24">
        <div className="animate-fade-rise max-w-7xl">
          <p className="mx-auto mb-6 w-fit rounded-full border border-[#003A99]/10 bg-white/70 px-5 py-2 text-sm font-bold text-[#003A99] shadow-sm backdrop-blur">
            {getAudienceLabel(normalizedCourse.audience_type)} · {getLevelLabel(normalizedCourse.level)} · {getFormatLabel(normalizedCourse.learning_format)}
          </p>
          <h1 className="font-display mx-auto max-w-6xl text-5xl font-semibold leading-[1.04] tracking-[-0.025em] text-black sm:text-7xl md:text-8xl">
            <span className="block">{normalizedCourse.name}</span>
            <span className="mt-1 block bg-gradient-to-r from-[#003A99] via-[#00AEEF] to-[#003A99] bg-clip-text text-transparent">
              {normalizedCourse.subtitle}
            </span>
          </h1>
        </div>

        <p className="animate-fade-rise-delay mt-8 max-w-2xl text-base leading-relaxed text-[#6F6F6F] sm:text-lg">
          {normalizedCourse.short_description}
        </p>

        <div className="animate-fade-rise-delay-2 mt-12 flex w-full flex-col items-center justify-center gap-3 sm:w-auto sm:flex-row">
          <Link
            href="#lead-form"
            className="w-full rounded-full bg-black px-12 py-4 text-center text-base font-semibold text-white transition-transform hover:scale-[1.03] sm:w-auto sm:px-14 sm:py-5"
          >
            {normalizedCourse.primary_cta}
          </Link>
          <Link
            href="#curriculum"
            className="w-full rounded-full border border-black/10 bg-white/70 px-12 py-4 text-center text-base font-semibold text-black shadow-sm backdrop-blur-md transition hover:border-black/25 sm:w-auto sm:px-14 sm:py-5"
          >
            Xem lộ trình
          </Link>
        </div>

        <div className="mt-14 grid w-full max-w-4xl gap-3 text-left sm:grid-cols-3">
          {normalizedCourse.outcomes.slice(0, 3).map((outcome) => (
            <div
              key={outcome}
              className="rounded-3xl border border-black/10 bg-white/62 p-5 text-sm leading-6 text-[#4B5563] shadow-[0_20px_70px_rgba(15,23,42,0.08)] backdrop-blur-xl"
            >
              {outcome}
            </div>
          ))}
        </div>
      </section>

      <section
        id="curriculum"
        className="relative z-10 mx-auto grid max-w-7xl gap-8 px-5 py-16 sm:px-8 lg:grid-cols-[0.9fr_1.1fr] lg:py-24"
      >
        <div>
          <h2 className="font-display text-5xl leading-[1.05] tracking-[-0.02em] sm:text-6xl">
            Lộ trình tinh gọn, học xong dùng được ngay.
          </h2>
          <p className="mt-6 max-w-xl text-lg leading-8 text-[#6F6F6F]">
            {normalizedCourse.description}
          </p>
          <div className="mt-8 grid gap-3 sm:grid-cols-3 lg:grid-cols-1">
            <StatCard label="Thời lượng" value={formatDuration(normalizedCourse.duration_hours)} />
            <StatCard label="Số buổi" value={formatSessions(normalizedCourse.default_session_count)} />
            <StatCard label="Học phí" value={normalizedCourse.price_label ?? "Liên hệ tư vấn"} />
          </div>
        </div>
        <div className="grid gap-3">
          {normalizedCourse.modules.map((item, index) => (
            <div
              key={`${item.sort_order}-${item.title}`}
              className="group flex items-start gap-5 rounded-[2rem] border border-black/10 bg-white/70 p-5 shadow-[0_18px_60px_rgba(15,23,42,0.07)] backdrop-blur-xl transition hover:-translate-y-1 hover:border-black/20"
            >
              <div className="font-display grid size-14 shrink-0 place-items-center rounded-full bg-black text-2xl text-white">
                {index + 1}
              </div>
              <div>
                <h3 className="text-lg font-semibold text-black">{item.title}</h3>
                {item.description ? (
                  <p className="mt-2 text-sm leading-6 text-[#6F6F6F]">{item.description}</p>
                ) : null}
              </div>
            </div>
          ))}
        </div>
      </section>

      <section
        id="outcomes"
        className="relative z-10 mx-auto max-w-7xl px-5 py-12 sm:px-8 lg:py-20"
      >
        <div className="rounded-[2rem] border border-black/10 bg-black p-6 text-white shadow-[0_30px_120px_rgba(15,23,42,0.22)] sm:p-10 lg:p-12">
          <div className="grid gap-10 lg:grid-cols-[0.85fr_1.15fr] lg:items-end">
            <div>
              <h2 className="font-display text-5xl leading-[1.05] tracking-[-0.02em] sm:text-6xl">
                Phù hợp cho đúng người, đúng mục tiêu học.
              </h2>
              <p className="mt-6 text-lg leading-8 text-white/65">
                DAYAI thiết kế khóa học theo hành trình tăng trưởng con người: học để hiểu, thực hành để làm được, và có định hướng ứng dụng sau khóa.
              </p>
            </div>
            <div className="grid gap-3 sm:grid-cols-2">
              {normalizedCourse.who_should_join.map((item) => (
                <div
                  key={item}
                  className="rounded-3xl border border-white/10 bg-white/[0.06] p-5 text-sm leading-6 text-white/75"
                >
                  {item}
                </div>
              ))}
            </div>
          </div>
        </div>
      </section>

      <section className="relative z-10 mx-auto grid max-w-7xl gap-10 px-5 pb-24 pt-10 sm:px-8 lg:grid-cols-[0.9fr_1.1fr] lg:items-start lg:pb-32">
        <div className="lg:sticky lg:top-10">
          <h2 className="font-display text-5xl leading-[1.05] tracking-[-0.02em] text-black sm:text-6xl">
            Nhận tư vấn khóa {normalizedCourse.name}.
          </h2>
          <p className="mt-6 max-w-xl text-lg leading-8 text-[#6F6F6F]">
            Để lại thông tin, đội ngũ DAYAI sẽ liên hệ tư vấn lịch học, lộ trình và hình thức phù hợp với nhu cầu của bạn.
          </p>
          <div className="mt-6 flex flex-wrap gap-2">
            {normalizedCourse.tools_covered.map((tool) => (
              <span key={tool} className="rounded-full border border-black/10 bg-white/75 px-4 py-2 text-sm font-semibold text-[#003A99] shadow-sm">
                {tool}
              </span>
            ))}
          </div>
        </div>

        <form
          id="lead-form"
          onSubmit={handleSubmit}
          className="rounded-[2rem] border border-black/10 bg-white/78 p-5 shadow-[0_30px_120px_rgba(15,23,42,0.12)] backdrop-blur-2xl sm:p-8"
        >
          <div className="grid gap-5">
            <LandingField label="Họ tên" name="full_name" placeholder="Nguyễn Minh Anh" required />
            <LandingField label="SĐT" name="phone" placeholder="0901 000 001" required />
            <LandingField label="Email" name="email" placeholder="ban@example.com" type="email" />
            <label className="grid gap-2">
              <span className="text-sm font-semibold text-black">Nhu cầu</span>
              <textarea
                name="learning_goal"
                rows={5}
                placeholder="Tôi muốn học AI để..."
                className="resize-none rounded-3xl border border-black/10 bg-white/80 px-5 py-4 text-base text-black outline-none transition placeholder:text-black/35 focus:border-black/30 focus:ring-4 focus:ring-blue-500/10"
              />
            </label>

            <input type="hidden" name="lead_type" value={getLeadType(normalizedCourse.audience_type)} />
            <input type="hidden" name="interested_course_id" value={normalizedCourse.course_code ?? normalizedCourse.slug} />
            <input type="hidden" name="request_type" value="trial" />
            <input type="hidden" name="preferred_contact_method" value="phone" />
            <input type="hidden" name="utm_source" value="website" />
            <input type="hidden" name="utm_medium" value="course_landing" />
            <input type="hidden" name="utm_campaign" value={normalizedCourse.slug} />

            <button
              type="submit"
              disabled={submitState === "submitting"}
              className="rounded-full bg-black px-8 py-4 text-base font-semibold text-white transition-transform hover:scale-[1.02] disabled:cursor-not-allowed disabled:opacity-60"
            >
              {submitState === "submitting" ? "Đang gửi..." : normalizedCourse.primary_cta}
            </button>

            {message ? (
              <p
                className={
                  submitState === "error"
                    ? "text-sm font-semibold text-red-600"
                    : "text-sm font-semibold text-emerald-600"
                }
              >
                {message}
              </p>
            ) : null}
          </div>
        </form>
      </section>
      <div className="relative z-10">
        <PublicSiteFooter />
      </div>
    </main>
  );
}

function mergeCourse(course: LandingCourse): Required<Omit<LandingCourse, "duration_hours" | "default_session_count" | "price_label" | "course_code">> & Pick<LandingCourse, "duration_hours" | "default_session_count" | "price_label" | "course_code"> {
  return {
    ...fallbackCourse,
    ...course,
    subtitle: course.subtitle || fallbackCourse.subtitle,
    short_description: course.short_description || fallbackCourse.short_description,
    description: course.description || fallbackCourse.description,
    outcomes: course.outcomes?.length ? course.outcomes : fallbackCourse.outcomes,
    who_should_join: course.who_should_join?.length ? course.who_should_join : fallbackCourse.who_should_join,
    prerequisites: course.prerequisites?.length ? course.prerequisites : fallbackCourse.prerequisites,
    tools_covered: course.tools_covered?.length ? course.tools_covered : fallbackCourse.tools_covered,
    primary_cta: course.primary_cta || fallbackCourse.primary_cta,
    modules: course.modules?.length ? course.modules : fallbackCourse.modules,
  } as Required<Omit<LandingCourse, "duration_hours" | "default_session_count" | "price_label" | "course_code">> & Pick<LandingCourse, "duration_hours" | "default_session_count" | "price_label" | "course_code">;
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
      <span className="text-sm font-semibold text-black">{label}</span>
      <input
        name={name}
        required={required}
        type={type}
        placeholder={placeholder}
        className="rounded-3xl border border-black/10 bg-white/80 px-5 py-4 text-base text-black outline-none transition placeholder:text-black/35 focus:border-black/30 focus:ring-4 focus:ring-blue-500/10"
      />
    </label>
  );
}

function StatCard({ label, value }: { label: string; value: string }) {
  return (
    <div className="rounded-3xl border border-black/10 bg-white/72 p-5 shadow-sm backdrop-blur">
      <div className="text-xs font-bold uppercase tracking-[0.16em] text-[#003A99]">{label}</div>
      <div className="mt-2 text-lg font-black text-black">{value}</div>
    </div>
  );
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
