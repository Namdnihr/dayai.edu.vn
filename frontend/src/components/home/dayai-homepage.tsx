"use client";

import Image from "next/image";
import Link from "next/link";
import { CSSProperties, useMemo, useState } from "react";
import { PublicSiteFooter } from "@/components/public-site-footer";
import { PublicSiteHeader } from "@/components/public-site-header";

type Audience = {
  key: string;
  label: string;
  title: string;
  href: string;
  summary: string;
  token: string;
};

const audiences: Audience[] = [
  {
    key: "kids",
    label: "AI Kids",
    title: "Trẻ em",
    href: "/ai-kids/",
    summary: "Học AI an toàn qua kể chuyện, hình ảnh và dự án sáng tạo có phụ huynh theo dõi.",
    token: "var(--dayai-secondary)",
  },
  {
    key: "student",
    label: "AI Student",
    title: "Học sinh, sinh viên",
    href: "/ai-student/",
    summary: "Dùng AI để học nhanh hơn, làm slide, nghiên cứu và định hướng nghề nghiệp.",
    token: "var(--dayai-warning)",
  },
  {
    key: "work",
    label: "AI Work",
    title: "Người đi làm",
    href: "/ai-work/",
    summary: "Tăng năng suất với prompt, workflow, báo cáo và tự động hóa cá nhân.",
    token: "var(--dayai-success)",
  },
  {
    key: "business",
    label: "AI Business",
    title: "Chủ doanh nghiệp",
    href: "/ai-business/",
    summary: "Ứng dụng AI vào bán hàng, marketing, chăm sóc khách hàng và vận hành.",
    token: "var(--dayai-accent)",
  },
  {
    key: "enterprise",
    label: "AI Enterprise",
    title: "Đội ngũ công ty",
    href: "/ai-enterprise/",
    summary: "Đào tạo theo phòng ban, theo dõi tiến độ và chuẩn hóa năng lực AI nội bộ.",
    token: "var(--dayai-primary)",
  },
];

const courses = [
  {
    title: "AI Căn Bản",
    meta: "6 buổi | Người mới bắt đầu",
    href: "/khoa-hoc/ai-can-ban/",
    summary: "Nắm nền tảng AI, tư duy prompt và nguyên tắc dùng AI có trách nhiệm.",
  },
  {
    title: "Prompt Engineering",
    meta: "4 buổi | Học tập và công việc",
    href: "/khoa-hoc/khoa-hoc-chatgpt/",
    summary: "Biến yêu cầu mơ hồ thành prompt rõ mục tiêu, có ngữ cảnh và kiểm soát đầu ra.",
  },
  {
    title: "AI Cho Công Việc",
    meta: "8 buổi | Nhân sự văn phòng",
    href: "/ai-work/khoa-hoc-ai-cho-nguoi-di-lam/",
    summary: "Xây workflow cá nhân cho viết, tóm tắt, phân tích dữ liệu và báo cáo.",
  },
  {
    title: "AI Enterprise Training",
    meta: "Theo nhu cầu | HR và L&D",
    href: "/ai-enterprise/dao-tao-ai-cho-doanh-nghiep/",
    summary: "Thiết kế chương trình đào tạo AI theo phòng ban, KPI và quy trình vận hành.",
  },
];

const productPillars = [
  ["Public website", "Tư vấn, nội dung SEO, khóa học, giảng viên, tài nguyên AI"],
  ["Student portal", "Lịch học, LMS, quiz, học phí, thông báo, chứng chỉ"],
  ["Admin OS", "CRM, lớp học, tài chính, nội dung, affiliate, báo cáo"],
  ["Company portal", "Tiến độ nhân sự, điểm danh, đánh giá, công nợ B2B"],
];

const portalHighlights = [
  "Đăng nhập OTP cho học viên, phụ huynh và doanh nghiệp",
  "Dashboard tiếp tục học với bài học, buổi học và quiz đang chờ",
  "LMS video, tài liệu, tiến độ và trạng thái hoàn thành",
  "Quiz online có chấm tự động, lịch sử làm bài và kết quả",
  "Báo cáo tiến bộ, học phí, thông báo và chứng chỉ",
];

const resources = [
  { title: "Cẩm nang AI", href: "/cam-nang-ai/", desc: "Kiến thức nền tảng cho người mới bắt đầu." },
  { title: "Prompt AI", href: "/prompt-ai/", desc: "Thư viện prompt theo học tập, công việc và kinh doanh." },
  { title: "Công cụ AI", href: "/cong-cu-ai/", desc: "Hướng dẫn chọn và dùng công cụ AI đúng nhu cầu." },
  { title: "Tin tức AI", href: "/tin-tuc-ai/", desc: "Cập nhật xu hướng AI và thay đổi công cụ mới." },
];

const instructorCards = [
  {
    name: "Nguyễn Minh",
    role: "Prompt Engineering và AI ứng dụng",
    image: "/images/instructor_minh.png",
  },
  {
    name: "Lê Hoàng",
    role: "AI cho doanh nghiệp và vận hành",
    image: "/images/instructor_hoang.png",
  },
  {
    name: "Trần An",
    role: "AI Kids và tư duy sáng tạo",
    image: "/images/instructor_an.png",
  },
];

export function DayaiHomepage() {
  const [selectedAudience, setSelectedAudience] = useState<Audience>(audiences[0]);

  const advisorText = useMemo(() => {
    if (selectedAudience.key === "kids") {
      return "Bắt đầu bằng AI Kids, học qua dự án nhỏ và để phụ huynh theo dõi tiến độ trong portal.";
    }

    if (selectedAudience.key === "enterprise") {
      return "Khởi động bằng khảo sát năng lực, chia nhóm theo phòng ban rồi theo dõi tiến độ qua HR portal.";
    }

    if (selectedAudience.key === "business") {
      return "Ưu tiên các use case tạo doanh thu: marketing, bán hàng, chăm sóc khách hàng và dashboard vận hành.";
    }

    if (selectedAudience.key === "work") {
      return "Tập trung prompt, workflow cá nhân, báo cáo và tự động hóa các tác vụ lặp lại.";
    }

    return "Bắt đầu từ AI Căn Bản, sau đó học Prompt Engineering và thực hành qua bài tập LMS.";
  }, [selectedAudience.key]);

  return (
    <main className="min-h-screen bg-[var(--dayai-bg-subtle)] text-[var(--dayai-text)]">
      <PublicSiteHeader />
      <Hero />
      <ProductSystem />
      <AudiencePath selectedKey={selectedAudience.key} onSelect={setSelectedAudience} advisorText={advisorText} />
      <CourseSection />
      <PortalSection />
      <ResourceSection />
      <InstructorSection />
      <FinalCta />
      <PublicSiteFooter />
    </main>
  );
}

function Hero() {
  return (
    <section className="relative isolate overflow-hidden border-b border-[var(--dayai-border)] bg-white">
      <div className="absolute inset-0 dayai-muted-grid opacity-60" />
      <div className="absolute inset-x-0 top-0 h-40 bg-white" />

      <div className="dayai-container relative grid items-center gap-8 py-10 sm:gap-12 sm:py-16 lg:min-h-[720px] lg:grid-cols-[1fr_0.9fr] lg:py-24">
        <div className="animate-fade-rise max-w-3xl">
          <div className="dayai-chip">DAYAI Education Operating System</div>
          <h1 className="mt-4 text-3xl font-black leading-[1.06] text-[var(--dayai-text)] sm:mt-8 sm:text-6xl lg:text-7xl">
            DAYAI
            <span className="mt-4 block text-[var(--dayai-primary)]">Học AI hôm nay, dẫn đầu tương lai.</span>
          </h1>
          <p className="mt-4 max-w-2xl text-base leading-7 text-[var(--dayai-text-muted)] sm:mt-8 sm:text-lg sm:leading-8">
            DAYAI kết nối khóa học AI, LMS, quiz online, portal học viên, portal phụ huynh, HR portal và admin vận hành trong một hệ sinh thái đào tạo AI cho người Việt.
          </p>
          <div className="mt-6 flex flex-col gap-3 sm:mt-10 sm:flex-row">
            <Link href="/dang-ky-tu-van/" className="dayai-btn dayai-btn-primary">
              Nhận tư vấn lộ trình
            </Link>
            <Link href="/portal/" className="dayai-btn dayai-btn-secondary">
              Xem portal học viên
            </Link>
          </div>

          <div className="mt-6 flex items-center gap-4 rounded-[var(--dayai-radius-xl)] border border-[var(--dayai-border)] bg-white p-3 shadow-[var(--dayai-shadow-xs)] sm:hidden">
            <div className="relative size-20 shrink-0 overflow-hidden rounded-[var(--dayai-radius-lg)] bg-[var(--dayai-surface-muted)]">
              <Image
                src="/images/instructor_minh.png"
                alt="Giảng viên DAYAI"
                fill
                sizes="96px"
                className="object-cover"
              />
            </div>
            <div>
              <div className="text-sm font-black">LMS + Quiz + Portal</div>
              <div className="mt-2 text-sm leading-6 text-[var(--dayai-text-muted)]">
                Một dashboard cho việc học tiếp theo.
              </div>
            </div>
          </div>

          <div className="mt-12 hidden gap-4 sm:grid sm:grid-cols-3">
            {[
              ["5", "nhóm người học"],
              ["360°", "học tập và vận hành"],
              ["AI", "cho cá nhân và doanh nghiệp"],
            ].map(([value, label]) => (
              <div key={label} className="border-l-2 border-[var(--dayai-primary)] pl-4">
                <div className="text-3xl font-black text-[var(--dayai-text)]">{value}</div>
                <div className="mt-1 text-sm font-semibold text-[var(--dayai-text-muted)]">{label}</div>
              </div>
            ))}
          </div>
        </div>

        <div className="animate-fade-rise-delay relative hidden lg:block">
          <div className="relative overflow-hidden rounded-[var(--dayai-radius-2xl)] border border-[var(--dayai-border)] bg-white shadow-[var(--dayai-shadow-md)]">
            <div className="relative aspect-[4/3] bg-[var(--dayai-surface-muted)]">
              <Image
                src="/images/instructor_minh.png"
                alt="Giảng viên DAYAI hướng dẫn học AI"
                fill
                priority
                sizes="(min-width: 1024px) 44vw, 100vw"
                className="object-cover"
              />
            </div>
            <div className="grid gap-4 p-6">
              <div className="flex items-center justify-between gap-4 border-b border-[var(--dayai-border)] pb-4">
                <div>
                  <div className="text-sm font-black text-[var(--dayai-text)]">Học tiếp hôm nay</div>
                  <div className="mt-1 text-sm text-[var(--dayai-text-muted)]">Prompt Engineering | Buổi 3</div>
                </div>
                <span className="rounded-[var(--dayai-radius-full)] bg-[var(--dayai-surface-tint)] px-3 py-1 text-xs font-black text-[var(--dayai-primary)]">
                  68%
                </span>
              </div>
              <div className="grid gap-3 text-sm">
                {[
                  ["LMS", "Video và tài liệu đã mở"],
                  ["Quiz", "1 bài kiểm tra đang chờ"],
                  ["Portal", "Lịch học và thông báo mới"],
                ].map(([title, desc]) => (
                  <div key={title} className="flex items-center justify-between gap-4">
                    <span className="font-black text-[var(--dayai-text)]">{title}</span>
                    <span className="text-right text-[var(--dayai-text-muted)]">{desc}</span>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}

function ProductSystem() {
  return (
    <section className="dayai-section bg-white">
      <div className="dayai-container">
        <SectionIntro
          eyebrow="DAYAI gồm những gì"
          title="Không chỉ là website khóa học. Đây là hệ điều hành đào tạo AI."
          description="Các phần public, portal và admin được thiết kế để dùng cùng dữ liệu: tuyển sinh, lớp học, bài học, quiz, học phí, báo cáo và chăm sóc học viên."
        />
        <div className="mt-12 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
          {productPillars.map(([title, desc]) => (
            <div key={title} className="dayai-card min-h-52 p-6">
              <div className="text-sm font-black text-[var(--dayai-primary)]">{title}</div>
              <p className="mt-6 text-base font-black leading-7 text-[var(--dayai-text)]">{desc}</p>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}

function AudiencePath({
  selectedKey,
  onSelect,
  advisorText,
}: {
  selectedKey: string;
  onSelect: (audience: Audience) => void;
  advisorText: string;
}) {
  const selected = audiences.find((audience) => audience.key === selectedKey) ?? audiences[0];

  return (
    <section className="dayai-section">
      <div className="dayai-container">
        <SectionIntro
          eyebrow="Lộ trình theo vai trò"
          title="Mỗi người học cần một cách tiếp cận AI khác nhau."
          description="DAYAI không gom mọi người vào một lớp chung. Mỗi nhóm có mục tiêu, bài tập, portal theo dõi và tiêu chí tiến bộ riêng."
        />

        <div className="mt-12 grid gap-6 lg:grid-cols-[1fr_0.72fr]">
          <div className="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            {audiences.map((audience) => {
              const active = selectedKey === audience.key;
              const accentStyle = {
                "--audience-color": audience.token,
              } as CSSProperties;

              return (
                <button
                  key={audience.key}
                  type="button"
                  onClick={() => onSelect(audience)}
                  style={accentStyle}
                  className={`min-h-52 rounded-[var(--dayai-radius-xl)] border bg-white p-6 text-left shadow-[var(--dayai-shadow-xs)] transition hover:-translate-y-0.5 hover:shadow-[var(--dayai-shadow-sm)] ${
                    active ? "border-[var(--dayai-primary)] ring-4 ring-blue-100" : "border-[var(--dayai-border)]"
                  }`}
                >
                  <span className="inline-flex rounded-[var(--dayai-radius-full)] border border-[color-mix(in_srgb,var(--audience-color)_24%,white)] bg-[color-mix(in_srgb,var(--audience-color)_10%,white)] px-3 py-1 text-xs font-black text-[var(--audience-color)]">
                    {audience.label}
                  </span>
                  <h3 className="mt-6 text-xl font-black text-[var(--dayai-text)]">{audience.title}</h3>
                  <p className="mt-3 text-sm leading-6 text-[var(--dayai-text-muted)]">{audience.summary}</p>
                </button>
              );
            })}
          </div>

          <aside className="dayai-dark rounded-[var(--dayai-radius-2xl)] border border-[var(--dayai-border)] bg-[var(--dayai-bg)] p-8 text-[var(--dayai-text)] shadow-[var(--dayai-shadow-md)]">
            <div className="dayai-kicker text-[var(--dayai-accent)]">AI advisor</div>
            <h3 className="mt-4 text-3xl font-black">{selected.label}</h3>
            <p className="mt-4 text-sm leading-7 text-[var(--dayai-text-muted)]">{advisorText}</p>
            <Link href={selected.href} className="dayai-btn mt-8 bg-white text-[var(--dayai-primary)]">
              Xem lộ trình này
            </Link>
          </aside>
        </div>
      </div>
    </section>
  );
}

function CourseSection() {
  return (
    <section className="dayai-section border-y border-[var(--dayai-border)] bg-white">
      <div className="dayai-container">
        <SectionIntro
          eyebrow="Khóa học"
          title="Tập trung thực hành, đo được tiến độ."
          description="Khóa học được nối với LMS, bài kiểm tra và báo cáo tiến bộ để học viên không chỉ xem nội dung mà còn biết mình đang tiến tới đâu."
        />
        <div className="mt-12 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
          {courses.map((course) => (
            <Link
              key={course.title}
              href={course.href}
              className="dayai-card group min-h-72 p-6 transition hover:-translate-y-0.5 hover:border-[var(--dayai-primary)] hover:shadow-[var(--dayai-shadow-sm)]"
            >
              <div className="text-xs font-black text-[var(--dayai-primary)]">{course.meta}</div>
              <h3 className="mt-6 text-2xl font-black leading-tight text-[var(--dayai-text)] group-hover:text-[var(--dayai-primary)]">
                {course.title}
              </h3>
              <p className="mt-4 text-sm leading-7 text-[var(--dayai-text-muted)]">{course.summary}</p>
              <span className="mt-8 inline-flex text-sm font-black text-[var(--dayai-primary)]">Xem chi tiết</span>
            </Link>
          ))}
        </div>
      </div>
    </section>
  );
}

function PortalSection() {
  return (
    <section className="dayai-section">
      <div className="dayai-container grid gap-12 lg:grid-cols-[0.9fr_1.1fr] lg:items-center">
        <div>
          <SectionIntro
            eyebrow="Portal học viên"
            title="Không chỉ học video. Học viên có dashboard riêng."
            description="Sprint 34 đã đưa quiz online vào portal. Sprint 35 bắt đầu chuẩn hóa giao diện để học viên nhìn ngay được việc cần làm tiếp."
          />
          <div className="mt-8 flex flex-wrap gap-3">
            <Link href="/portal/" className="dayai-btn dayai-btn-primary">
              Mở portal học viên
            </Link>
            <Link href="/company-portal/" className="dayai-btn dayai-btn-secondary">
              Xem HR portal
            </Link>
          </div>
        </div>

        <div className="dayai-card p-6 shadow-[var(--dayai-shadow-md)]">
          <div className="flex items-center justify-between gap-4 border-b border-[var(--dayai-border)] pb-6">
            <div>
              <div className="dayai-kicker">Student portal</div>
              <div className="mt-2 text-2xl font-black">Học tiếp hôm nay</div>
            </div>
            <span className="rounded-[var(--dayai-radius-full)] bg-green-50 px-3 py-1 text-xs font-black text-green-700">
              Đang học
            </span>
          </div>
          <div className="mt-6 grid gap-4">
            {portalHighlights.map((item, index) => (
              <div key={item} className="flex items-start gap-4 border-b border-[var(--dayai-border)] pb-4 last:border-0 last:pb-0">
                <span className="grid size-8 shrink-0 place-items-center rounded-[var(--dayai-radius-full)] bg-[var(--dayai-primary)] text-xs font-black text-white">
                  {index + 1}
                </span>
                <span className="text-sm font-bold leading-6 text-[var(--dayai-text-muted)]">{item}</span>
              </div>
            ))}
          </div>
        </div>
      </div>
    </section>
  );
}

function ResourceSection() {
  return (
    <section className="dayai-dark dayai-section bg-[var(--dayai-bg)] text-[var(--dayai-text)]">
      <div className="dayai-container">
        <SectionIntro
          eyebrow="Tài nguyên"
          title="Kho kiến thức AI để nuôi dưỡng lead và hỗ trợ học viên."
          description="Các cụm nội dung giúp người học hiểu AI trước khi đăng ký và có tài liệu tham khảo trong quá trình học."
          inverse
        />
        <div className="mt-12 grid gap-4 md:grid-cols-2 lg:grid-cols-4">
          {resources.map((resource) => (
            <Link
              key={resource.href}
              href={resource.href}
              className="rounded-[var(--dayai-radius-xl)] border border-[var(--dayai-border)] bg-[var(--dayai-surface)] p-6 transition hover:-translate-y-0.5 hover:border-[var(--dayai-accent)]"
            >
              <h3 className="text-xl font-black">{resource.title}</h3>
              <p className="mt-4 text-sm leading-6 text-[var(--dayai-text-muted)]">{resource.desc}</p>
            </Link>
          ))}
        </div>
      </div>
    </section>
  );
}

function InstructorSection() {
  return (
    <section className="dayai-section bg-white">
      <div className="dayai-container">
        <SectionIntro
          eyebrow="Mentor"
          title="Giảng viên thực chiến, không chỉ dạy công cụ."
          description="Đội ngũ mentor hướng dẫn theo mục tiêu học tập, theo bài thực hành và theo năng lực cần đạt của từng nhóm học viên."
        />
        <div className="mt-12 grid gap-4 md:grid-cols-3">
          {instructorCards.map((instructor) => (
            <article key={instructor.name} className="dayai-card overflow-hidden">
              <div className="relative aspect-[4/3] bg-[var(--dayai-surface-muted)]">
                <Image
                  src={instructor.image}
                  alt={instructor.name}
                  fill
                  sizes="(min-width: 768px) 33vw, 100vw"
                  className="object-cover"
                />
              </div>
              <div className="p-6">
                <h3 className="text-xl font-black">{instructor.name}</h3>
                <p className="mt-2 text-sm font-semibold leading-6 text-[var(--dayai-text-muted)]">{instructor.role}</p>
              </div>
            </article>
          ))}
        </div>
      </div>
    </section>
  );
}

function FinalCta() {
  return (
    <section className="bg-white px-0 pb-24">
      <div className="dayai-container rounded-[var(--dayai-radius-2xl)] bg-[var(--dayai-primary)] px-6 py-16 text-center text-white shadow-[var(--dayai-shadow-md)] sm:px-10">
        <div className="mx-auto max-w-3xl">
          <div className="text-xs font-black uppercase">Bắt đầu sprint học AI của bạn</div>
          <h2 className="mt-4 text-4xl font-black leading-tight sm:text-5xl">
            Chọn đúng lộ trình trước khi chọn công cụ.
          </h2>
          <p className="mt-6 text-sm leading-7 text-blue-50">
            DAYAI giúp bạn xác định mục tiêu, học theo bài thực hành và theo dõi tiến bộ qua portal.
          </p>
          <Link href="/dang-ky-tu-van/" className="dayai-btn mt-8 bg-white text-[var(--dayai-primary)]">
            Đăng ký tư vấn
          </Link>
        </div>
      </div>
    </section>
  );
}

function SectionIntro({
  eyebrow,
  title,
  description,
  inverse = false,
}: {
  eyebrow: string;
  title: string;
  description: string;
  inverse?: boolean;
}) {
  return (
    <div className="max-w-3xl">
      <div className={`dayai-kicker ${inverse ? "text-[var(--dayai-accent)]" : ""}`}>{eyebrow}</div>
      <h2 className={`mt-4 text-3xl font-black leading-tight sm:text-4xl ${inverse ? "text-white" : "text-[var(--dayai-text)]"}`}>
        {title}
      </h2>
      <p className={`mt-5 text-sm leading-7 ${inverse ? "text-[var(--dayai-text-muted)]" : "text-[var(--dayai-text-muted)]"}`}>
        {description}
      </p>
    </div>
  );
}
