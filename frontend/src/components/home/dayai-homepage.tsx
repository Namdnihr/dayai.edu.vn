"use client";

import Link from "next/link";
import { useMemo, useState } from "react";
import { PublicSiteFooter } from "@/components/public-site-footer";
import { menuGroups } from "@/lib/site-map";

const audiences = [
  {
    key: "kids",
    label: "AI Kids",
    title: "Cho trẻ em",
    description:
      "Làm quen AI qua tư duy sáng tạo, kể chuyện, hình ảnh và dự án nhỏ an toàn.",
  },
  {
    key: "student",
    label: "AI Student",
    title: "Cho học sinh, sinh viên",
    description:
      "Dùng AI để học nhanh hơn, nghiên cứu tốt hơn và chuẩn bị năng lực nghề nghiệp.",
  },
  {
    key: "work",
    label: "AI Work",
    title: "Cho người đi làm",
    description:
      "Tăng năng suất cá nhân với prompt, workflow, tài liệu, báo cáo và tự động hóa.",
  },
  {
    key: "business",
    label: "AI Business",
    title: "Cho chủ doanh nghiệp",
    description:
      "Ứng dụng AI vào vận hành, marketing, bán hàng, chăm sóc khách hàng và quản trị.",
  },
  {
    key: "enterprise",
    label: "AI Enterprise",
    title: "Cho doanh nghiệp",
    description:
      "Đào tạo đội ngũ theo phòng ban, theo dõi tiến độ và chuẩn hóa năng lực AI nội bộ.",
  },
];

const journey = [
  "Khởi động tư duy AI",
  "Làm chủ prompt",
  "Xây workflow cá nhân",
  "Ứng dụng theo vai trò",
  "Dẫn dắt đội nhóm bằng AI",
];

const courses = [
  {
    title: "AI Căn Bản",
    audience: "Người mới bắt đầu",
    duration: "6 buổi",
    color: "from-[#003A99] to-[#00AEEF]",
  },
  {
    title: "Prompt Engineering",
    audience: "Sinh viên, nhân sự văn phòng",
    duration: "4 buổi",
    color: "from-[#0F172A] to-[#003A99]",
  },
  {
    title: "AI Cho Công Việc",
    audience: "Người đi làm",
    duration: "8 buổi",
    color: "from-[#00AEEF] to-[#003A99]",
  },
  {
    title: "AI Cho Chủ Doanh Nghiệp",
    audience: "Founder, quản lý",
    duration: "2 ngày",
    color: "from-[#003A99] to-[#F5B400]",
  },
  {
    title: "AI Enterprise Training",
    audience: "HR, L&D, đội nhóm",
    duration: "Theo nhu cầu",
    color: "from-[#111827] to-[#00AEEF]",
  },
];

const stories = [
  {
    name: "Minh Anh",
    role: "Sinh viên năm 2",
    result: "Tạo trợ lý học tập cá nhân và rút ngắn thời gian làm báo cáo.",
  },
  {
    name: "Gia đình chị Hương",
    role: "Phụ huynh học viên Kids",
    result: "Con biết dùng AI để học tiếng Anh, kể chuyện và trình bày ý tưởng.",
  },
  {
    name: "Công ty thương mại B2B",
    role: "Đào tạo nội bộ",
    result: "Chuẩn hóa prompt bán hàng và quy trình chăm sóc khách hàng.",
  },
];

const instructors = [
  {
    name: "Nguyễn Minh",
    focus: "AI ứng dụng & Prompt Engineering",
  },
  {
    name: "Lê Hoàng",
    focus: "AI cho doanh nghiệp & vận hành",
  },
  {
    name: "Trần An",
    focus: "AI Kids & tư duy sáng tạo",
  },
];

const metrics = [
  { value: "5", label: "nhóm người học" },
  { value: "12+", label: "lộ trình đào tạo" },
  { value: "80%", label: "thời lượng thực hành" },
  { value: "1", label: "hệ sinh thái theo dõi" },
];

export function DayaiHomepage() {
  const [selectedAudience, setSelectedAudience] = useState(audiences[0]);
  const [advisorGoal, setAdvisorGoal] = useState("Tôi mới bắt đầu và muốn học AI bài bản");

  const advisorResult = useMemo(() => {
    if (selectedAudience.key === "kids") {
      return "Gợi ý: bắt đầu với AI Kids, học qua dự án sáng tạo ngắn, có phụ huynh theo dõi tiến độ.";
    }

    if (selectedAudience.key === "enterprise") {
      return "Gợi ý: đặt lịch khảo sát nhu cầu, chia nhóm theo phòng ban và triển khai workshop theo use case.";
    }

    if (selectedAudience.key === "business") {
      return "Gợi ý: chọn AI Business để xây workflow marketing, bán hàng, vận hành và CSKH.";
    }

    if (advisorGoal.toLowerCase().includes("công việc")) {
      return "Gợi ý: học AI Work, tập trung prompt, tài liệu, báo cáo và tự động hóa tác vụ hằng ngày.";
    }

    return "Gợi ý: bắt đầu với AI Căn Bản, sau đó chuyển sang Prompt Engineering hoặc AI Work.";
  }, [advisorGoal, selectedAudience.key]);

  return (
    <main className="min-h-screen overflow-hidden bg-white text-slate-950">
      <Header />
      <Hero />
      <AudienceSelection
        selectedKey={selectedAudience.key}
        onSelect={setSelectedAudience}
      />
      <LearningJourney />
      <FeaturedCourses />
      <AiPlayground
        selectedAudience={selectedAudience}
        advisorGoal={advisorGoal}
        advisorResult={advisorResult}
        onGoalChange={setAdvisorGoal}
      />
      <EcosystemVisualization />
      <SuccessMetrics />
      <StudentStories />
      <InstructorSection />
      <FinalCta />
      <PublicSiteFooter />
    </main>
  );
}

function Header() {
  return (
    <header className="fixed inset-x-0 top-0 z-50 border-b border-white/40 bg-white/78 backdrop-blur-2xl">
      <div className="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 sm:px-8">
        <Link href="/" className="font-display text-3xl tracking-tight text-black">
          DAYAI
        </Link>
        <nav className="hidden items-center gap-7 text-sm font-semibold text-slate-500 lg:flex">
          {menuGroups.map((menu) => (
            <Link key={menu.href} href={menu.href} className="transition hover:text-[#003A99]">
              {menu.label}
            </Link>
          ))}
        </nav>
        <a
          href="#final-cta"
          className="rounded-full bg-[#003A99] px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-[#003A99]/20 transition hover:scale-[1.03] hover:bg-[#002B73]"
        >
          Nhận tư vấn
        </a>
      </div>
    </header>
  );
}

function Hero() {
  return (
    <section className="relative flex min-h-screen items-center pt-24">
      <AnimatedEcosystemBackground />
      <div className="relative z-10 mx-auto max-w-7xl px-5 py-24 text-center sm:px-8">
        <p className="mx-auto mb-6 w-fit rounded-full border border-[#003A99]/10 bg-white/70 px-5 py-2 text-sm font-bold text-[#003A99] shadow-sm backdrop-blur">
          Hệ sinh thái học AI cho Việt Nam
        </p>
        <h1 className="mx-auto max-w-6xl text-5xl font-semibold leading-[1.02] tracking-[-0.035em] text-black sm:text-7xl lg:text-8xl">
          <span className="block">Học AI Hôm Nay</span>
          <span className="block bg-gradient-to-r from-[#003A99] via-[#00AEEF] to-[#003A99] bg-clip-text text-transparent">
            Dẫn Đầu Tương Lai
          </span>
        </h1>
        <p className="mx-auto mt-8 max-w-3xl text-base leading-8 text-slate-600 sm:text-lg">
          DAYAI.EDU.VN kết nối khóa học, video academy, cố vấn AI, cổng phụ
          huynh và chương trình doanh nghiệp để mỗi người có một hành trình học
          AI rõ ràng.
        </p>
        <div className="mx-auto mt-8 grid max-w-3xl gap-3 sm:grid-cols-3">
          {[
            ["Lộ trình", "Theo từng nhóm học"],
            ["Thực hành", "80% thời lượng"],
            ["Theo dõi", "Portal tiến độ"],
          ].map(([label, value]) => (
            <div
              key={label}
              className="dayai-float rounded-3xl border border-black/10 bg-white/72 px-5 py-4 text-left shadow-sm backdrop-blur"
            >
              <div className="text-xs font-bold uppercase tracking-[0.16em] text-[#003A99]">
                {label}
              </div>
              <div className="mt-1 font-bold text-slate-950">{value}</div>
            </div>
          ))}
        </div>

        <div className="mt-11 flex flex-col justify-center gap-3 sm:flex-row">
          <a
            href="#audience"
            className="rounded-full bg-[#003A99] px-10 py-4 font-bold text-white shadow-2xl shadow-[#003A99]/20 transition hover:scale-[1.03]"
          >
            Chọn lộ trình phù hợp
          </a>
          <a
            href="#playground"
            className="rounded-full border border-black/10 bg-white/75 px-10 py-4 font-bold text-black shadow-sm backdrop-blur transition hover:border-[#00AEEF]/50"
          >
            Thử AI tư vấn
          </a>
        </div>
      </div>
    </section>
  );
}

function AnimatedEcosystemBackground() {
  return (
    <div className="pointer-events-none absolute inset-0 overflow-hidden">
      <div className="absolute inset-0 bg-[linear-gradient(90deg,rgba(0,58,153,0.045)_1px,transparent_1px),linear-gradient(rgba(0,58,153,0.045)_1px,transparent_1px)] bg-[size:82px_82px] [mask-image:radial-gradient(ellipse_at_center,black,transparent_72%)]" />
      <div className="dayai-orbit absolute left-1/2 top-1/2 size-[760px] -translate-x-1/2 -translate-y-1/2 rounded-full border border-[#003A99]/10" />
      <div className="dayai-orbit dayai-orbit-slow absolute left-1/2 top-1/2 size-[520px] -translate-x-1/2 -translate-y-1/2 rounded-full border border-[#00AEEF]/14" />
      <div className="absolute left-[18%] top-[24%] size-32 rounded-full bg-[#00AEEF]/16 blur-3xl" />
      <div className="absolute right-[16%] top-[22%] size-36 rounded-full bg-[#F5B400]/18 blur-3xl" />
      <div className="absolute inset-x-0 bottom-0 h-72 bg-gradient-to-t from-blue-50 via-white/70 to-transparent" />
    </div>
  );
}

function AudienceSelection({
  selectedKey,
  onSelect,
}: {
  selectedKey: string;
  onSelect: (audience: (typeof audiences)[number]) => void;
}) {
  return (
    <section id="audience" className="mx-auto max-w-7xl px-5 py-20 sm:px-8">
      <SectionIntro
        title="Một hệ sinh thái, năm hành trình học"
        description="DAYAI không dạy AI đại trà. Mỗi nhóm người học có mục tiêu, tốc độ và cách thực hành khác nhau."
      />
      <div className="mt-10 grid gap-4 lg:grid-cols-5">
        {audiences.map((audience) => {
          const isSelected = selectedKey === audience.key;

          return (
            <button
              key={audience.key}
              type="button"
              onClick={() => onSelect(audience)}
              className={`rounded-[1.75rem] border p-5 text-left transition ${
                isSelected
                  ? "border-[#003A99] bg-[#003A99] text-white shadow-2xl shadow-[#003A99]/20"
                  : "border-black/10 bg-white text-black hover:-translate-y-1 hover:border-[#00AEEF]/50"
              }`}
            >
              <div className={isSelected ? "text-sm font-bold text-white/72" : "text-sm font-bold text-[#003A99]"}>
                {audience.label}
              </div>
              <div className={`mt-5 grid size-12 place-items-center rounded-2xl ${isSelected ? "bg-white/14 text-white" : "bg-blue-50 text-[#003A99]"}`}>
                <HumanGrowthIcon />
              </div>
              <h3 className="mt-4 text-xl font-black">{audience.title}</h3>
              <p className={isSelected ? "mt-3 text-sm leading-6 text-white/75" : "mt-3 text-sm leading-6 text-slate-600"}>
                {audience.description}
              </p>
            </button>
          );
        })}
      </div>
    </section>
  );
}

function LearningJourney() {
  return (
    <section id="journey" className="bg-slate-950 py-24 text-white">
      <div className="mx-auto max-w-7xl px-5 sm:px-8">
        <SectionIntro
          dark
          title="Từ người mới đến người dẫn dắt AI"
          description="Lộ trình học được thiết kế như một hành trình tăng trưởng: hiểu, làm, áp dụng, tối ưu và dẫn dắt."
        />
        <div className="relative mt-12 overflow-hidden rounded-[2rem] border border-white/10 bg-white/[0.04] p-6">
          <div className="absolute left-8 right-8 top-1/2 hidden h-px bg-gradient-to-r from-[#003A99] via-[#00AEEF] to-[#F5B400] lg:block" />
          <div className="grid gap-4 lg:grid-cols-5">
            {journey.map((step, index) => (
              <div key={step} className="relative rounded-3xl border border-white/10 bg-slate-950/80 p-5">
                <div className="grid size-12 place-items-center rounded-full bg-white text-lg font-black text-[#003A99]">
                  <JourneyIcon />
                </div>
                <div className="mt-3 text-xs font-bold uppercase tracking-[0.18em] text-[#F5B400]">
                  Chặng {index + 1}
                </div>
                <h3 className="mt-3 text-lg font-bold">{step}</h3>
                <p className="mt-3 text-sm leading-6 text-white/55">
                  Hoàn thành chặng {index + 1} để mở năng lực AI tiếp theo.
                </p>
              </div>
            ))}
          </div>
        </div>
      </div>
    </section>
  );
}

function FeaturedCourses() {
  return (
    <section id="courses" className="py-24">
      <div className="mx-auto max-w-7xl px-5 sm:px-8">
        <SectionIntro
          title="Khóa học nổi bật"
          description="Trải nghiệm duyệt khóa theo kiểu thư viện nội dung cao cấp: rõ đối tượng, rõ kết quả, dễ chọn bước tiếp theo."
        />
      </div>
      <div className="mt-10 flex snap-x gap-5 overflow-x-auto px-5 pb-6 sm:px-8 lg:px-[max(2rem,calc((100vw-80rem)/2+2rem))]">
        {courses.map((course) => (
          <article
            key={course.title}
            className="min-h-[360px] w-[310px] shrink-0 snap-start overflow-hidden rounded-[2rem] bg-slate-950 text-white shadow-2xl shadow-slate-950/10 sm:w-[380px]"
          >
            <div className={`h-36 bg-gradient-to-br ${course.color}`} />
            <div className="p-6">
              <div className="text-sm font-bold text-[#F5B400]">{course.duration}</div>
              <div className="mb-5 mt-4 grid size-12 place-items-center rounded-2xl bg-white/10 text-[#F5B400]">
                <CourseIcon />
              </div>
              <h3 className="mt-3 font-display text-4xl leading-none">{course.title}</h3>
              <p className="mt-3 text-sm font-semibold text-white/60">{course.audience}</p>
              <Link
                href="/khoa-hoc/ai-can-ban"
                className="mt-8 inline-flex rounded-full bg-white px-5 py-3 text-sm font-bold text-black"
              >
                Xem landing khóa học
              </Link>
            </div>
          </article>
        ))}
      </div>
    </section>
  );
}

function AiPlayground({
  selectedAudience,
  advisorGoal,
  advisorResult,
  onGoalChange,
}: {
  selectedAudience: (typeof audiences)[number];
  advisorGoal: string;
  advisorResult: string;
  onGoalChange: (value: string) => void;
}) {
  return (
    <section id="playground" className="mx-auto max-w-7xl px-5 py-24 sm:px-8">
      <div className="grid gap-10 rounded-[2.5rem] border border-black/10 bg-blue-50 p-6 sm:p-10 lg:grid-cols-[0.85fr_1.15fr]">
        <div>
          <h2 className="font-display text-5xl leading-none tracking-[-0.03em] sm:text-6xl">
            AI Playground
          </h2>
          <p className="mt-6 text-lg leading-8 text-slate-600">
            Một cố vấn AI mô phỏng giúp người học chọn hướng đi trước khi gặp
            tư vấn viên DAYAI.
          </p>
        </div>
        <div className="rounded-[2rem] bg-white p-5 shadow-2xl shadow-[#003A99]/10">
          <div className="text-sm font-bold text-[#003A99]">
            Đang tư vấn cho: {selectedAudience.label}
          </div>
          <textarea
            value={advisorGoal}
            onChange={(event) => onGoalChange(event.target.value)}
            className="mt-4 min-h-32 w-full resize-none rounded-3xl border border-black/10 px-5 py-4 outline-none focus:border-[#00AEEF] focus:ring-4 focus:ring-[#00AEEF]/10"
            placeholder="Nhập mục tiêu học AI của bạn..."
          />
          <div className="mt-4 rounded-3xl bg-slate-950 p-5 text-white">
            <div className="text-xs font-bold uppercase tracking-[0.18em] text-[#F5B400]">
              Gợi ý lộ trình
            </div>
            <p className="mt-3 leading-7 text-white/80">{advisorResult}</p>
          </div>
        </div>
      </div>
    </section>
  );
}

function EcosystemVisualization() {
  return (
    <section className="bg-white py-24">
      <div className="mx-auto max-w-7xl px-5 sm:px-8">
        <SectionIntro
          title="Ecosystem Visualization"
          description="DAYAI kết nối học viên, phụ huynh, giảng viên, doanh nghiệp và dữ liệu tiến bộ thành một hệ thống học tập liên tục."
        />
        <div className="relative mt-12 min-h-[520px] overflow-hidden rounded-[2.5rem] border border-black/10 bg-slate-950 p-8 text-white">
          <div className="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(0,174,239,0.22),transparent_38%)]" />
          <div className="relative mx-auto grid min-h-[460px] max-w-4xl place-items-center">
            <div className="dayai-pulse grid size-40 place-items-center rounded-full bg-white text-center text-xl font-black text-[#003A99]">
              DAYAI
            </div>
            {["Kids", "Student", "Work", "Business", "Enterprise"].map((node, index) => (
              <div
                key={node}
                className="absolute rounded-full border border-white/15 bg-white/10 px-5 py-3 text-sm font-bold backdrop-blur"
                style={{
                  transform: `rotate(${index * 72}deg) translate(210px) rotate(-${index * 72}deg)`,
                }}
              >
                AI {node}
              </div>
            ))}
          </div>
        </div>
      </div>
    </section>
  );
}

function SuccessMetrics() {
  return (
    <section className="mx-auto max-w-7xl px-5 py-20 sm:px-8">
      <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        {metrics.map((metric) => (
          <div key={metric.label} className="rounded-[2rem] border border-black/10 p-7">
            <div className="font-display text-6xl leading-none text-[#003A99]">
              {metric.value}
            </div>
            <div className="mt-4 text-sm font-bold uppercase tracking-[0.16em] text-slate-500">
              {metric.label}
            </div>
          </div>
        ))}
      </div>
    </section>
  );
}

function StudentStories() {
  return (
    <section id="stories" className="bg-blue-50 py-24">
      <div className="mx-auto max-w-7xl px-5 sm:px-8">
        <SectionIntro
          title="Câu chuyện thành công"
          description="Không kể chuyện viển tưởng về AI. DAYAI tập trung vào thay đổi cụ thể trong học tập, công việc và vận hành."
        />
        <div className="mt-10 grid gap-5 lg:grid-cols-3">
          {stories.map((story) => (
            <article key={story.name} className="rounded-[2rem] bg-white p-7 shadow-sm">
              <div className="text-sm font-bold text-[#003A99]">{story.role}</div>
              <h3 className="mt-4 text-2xl font-black">{story.name}</h3>
              <p className="mt-5 leading-7 text-slate-600">{story.result}</p>
            </article>
          ))}
        </div>
      </div>
    </section>
  );
}

function InstructorSection() {
  return (
    <section className="mx-auto max-w-7xl px-5 py-24 sm:px-8">
      <SectionIntro
        title="Giảng viên dẫn đường"
        description="Hồ sơ sạch, ít phô trương, tập trung vào chuyên môn và khả năng giúp người học đi từ hiểu đến làm được."
      />
      <div className="mt-10 grid gap-5 lg:grid-cols-3">
        {instructors.map((instructor) => (
          <article key={instructor.name} className="rounded-[2rem] border border-black/10 p-6">
            <div className="aspect-[4/3] rounded-[1.5rem] bg-gradient-to-br from-slate-100 to-blue-100" />
            <h3 className="mt-6 text-2xl font-black">{instructor.name}</h3>
            <p className="mt-2 text-slate-600">{instructor.focus}</p>
          </article>
        ))}
      </div>
    </section>
  );
}

function FinalCta() {
  return (
    <section id="final-cta" className="px-5 pb-24 sm:px-8">
      <div className="mx-auto max-w-7xl rounded-[2.75rem] bg-[#003A99] px-6 py-20 text-center text-white sm:px-10">
        <h2 className="font-display mx-auto max-w-4xl text-5xl leading-[1.05] tracking-[-0.02em] sm:text-7xl">
          Bắt đầu hành trình AI của bạn hôm nay
        </h2>
        <p className="mx-auto mt-6 max-w-2xl text-lg leading-8 text-white/72">
          Chọn đúng lộ trình ngay từ đầu để học AI có định hướng, có thực hành
          và có người đồng hành.
        </p>
        <Link
          href="/khoa-hoc/ai-can-ban"
          className="mt-10 inline-flex rounded-full bg-white px-10 py-4 font-bold text-[#003A99] transition hover:scale-[1.03]"
        >
          Xem khóa AI Căn Bản
        </Link>
      </div>
    </section>
  );
}

function SectionIntro({
  title,
  description,
  dark = false,
}: {
  title: string;
  description: string;
  dark?: boolean;
}) {
  return (
    <div className="max-w-3xl">
      <h2 className={`font-display text-5xl leading-[1.05] tracking-[-0.02em] sm:text-6xl ${dark ? "text-white" : "text-black"}`}>
        {title}
      </h2>
      <p className={`mt-5 text-lg leading-8 ${dark ? "text-white/62" : "text-slate-600"}`}>
        {description}
      </p>
    </div>
  );
}

function HumanGrowthIcon() {
  return (
    <svg aria-hidden="true" className="size-6" viewBox="0 0 24 24" fill="none">
      <path
        d="M5 18c4.5-.5 7.5-3.4 8.2-8.8M9 19c5.6-.4 9.2-3.8 10-10"
        stroke="currentColor"
        strokeLinecap="round"
        strokeWidth="1.8"
      />
      <path
        d="M5.5 13.5c2.9 0 5-2.1 5-5m4 1.5 4.5-1.5-1.2 4.5"
        stroke="currentColor"
        strokeLinecap="round"
        strokeLinejoin="round"
        strokeWidth="1.8"
      />
    </svg>
  );
}

function JourneyIcon() {
  return (
    <svg aria-hidden="true" className="size-6" viewBox="0 0 24 24" fill="none">
      <path
        d="M4 17c3.5-6 9-9 16-10"
        stroke="currentColor"
        strokeLinecap="round"
        strokeWidth="2"
      />
      <path
        d="M6 17h4v3H6zM13 12h4v8h-4z"
        fill="currentColor"
        opacity="0.18"
      />
      <circle cx="5" cy="17" r="1.6" fill="currentColor" />
      <circle cx="12" cy="11" r="1.6" fill="currentColor" />
      <circle cx="19" cy="7" r="1.6" fill="currentColor" />
    </svg>
  );
}

function CourseIcon() {
  return (
    <svg aria-hidden="true" className="size-6" viewBox="0 0 24 24" fill="none">
      <path
        d="M5 6.5A2.5 2.5 0 0 1 7.5 4H19v15H7.5A2.5 2.5 0 0 1 5 16.5z"
        stroke="currentColor"
        strokeLinejoin="round"
        strokeWidth="1.8"
      />
      <path d="M8 8h7M8 11h5" stroke="currentColor" strokeLinecap="round" strokeWidth="1.8" />
      <path d="M7 19a2 2 0 0 1 0-4h12" stroke="currentColor" strokeLinecap="round" strokeWidth="1.8" />
    </svg>
  );
}
