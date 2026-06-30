"use client";

import { useEffect, useMemo, useState } from "react";
import type { ReactNode } from "react";

type PortalSession = {
  phone: string;
  student_code: string;
  portal_access_token: string;
};

type LessonProgress = {
  status: string;
  progress_percent: number;
  last_position_seconds: number;
  last_watched_at: string | null;
  completed_at: string | null;
};

type CompactLesson = {
  title: string;
  slug: string;
  duration_minutes: number | null;
  progress: LessonProgress;
};

type LessonData = CompactLesson & {
  summary: string | null;
  course: string | null;
  module: string | null;
  video_provider: string;
  video_url: string | null;
  resources: Array<{ title?: string; url?: string }>;
};

type LessonAssessment = {
  id: string;
  title: string;
  assessment_type: string;
  description: string | null;
  max_score: number;
  question_count: number;
  latest_attempt: {
    attempt_code: string;
    status: string;
    score: number | null;
    max_score: number;
    submitted_at: string | null;
  } | null;
};

type LessonResponse = {
  access_role: string;
  lesson: LessonData;
  navigation: {
    previous_lesson: CompactLesson | null;
    next_lesson: CompactLesson | null;
  };
  outline: Array<{
    module_id: string | null;
    module: string;
    lessons: CompactLesson[];
  }>;
  assessments: LessonAssessment[];
};

export function PortalLessonPlayer({ slug }: { slug: string }) {
  const [payload, setPayload] = useState<LessonResponse | null>(null);
  const [message, setMessage] = useState("");
  const [progressPercent, setProgressPercent] = useState(0);
  const [positionSeconds, setPositionSeconds] = useState(0);
  const [isSaving, setIsSaving] = useState(false);

  const session = useMemo<PortalSession | null>(() => {
    if (typeof window === "undefined") {
      return null;
    }

    const rawSession = window.sessionStorage.getItem("dayai_portal_session");

    if (!rawSession) {
      return null;
    }

    try {
      return JSON.parse(rawSession) as PortalSession;
    } catch {
      return null;
    }
  }, []);

  useEffect(() => {
    async function loadLesson() {
      if (!session) {
        setMessage("Bạn cần xác thực lại ở trang portal trước khi vào học.");
        return;
      }

      const response = await fetch(`/api/portal/lessons/${slug}`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(session),
      });
      const result = await response.json();

      if (!response.ok) {
        setMessage(result.message ?? "Không tải được bài học.");
        return;
      }

      const nextPayload = result as LessonResponse;
      setPayload(nextPayload);
      setProgressPercent(nextPayload.lesson.progress.progress_percent);
      setPositionSeconds(nextPayload.lesson.progress.last_position_seconds);
    }

    void loadLesson();
  }, [session, slug]);

  async function saveProgress(nextPercent = progressPercent) {
    if (!session) {
      setMessage("Phiên portal đã hết hạn. Vui lòng xác thực lại.");
      return;
    }

    const normalizedPercent = Math.max(0, Math.min(100, Math.round(nextPercent)));
    setIsSaving(true);
    setMessage("");

    try {
      const response = await fetch(`/api/portal/lessons/${slug}/progress`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          ...session,
          progress_percent: normalizedPercent,
          last_position_seconds: Math.max(0, Math.round(positionSeconds)),
        }),
      });
      const result = await response.json();

      if (!response.ok) {
        throw new Error(result.message ?? "Không lưu được tiến độ.");
      }

      setProgressPercent(result.progress.progress_percent);
      setPayload((current) => current ? updateProgressInPayload(current, slug, result.progress) : current);
      setMessage(result.message ?? "Đã lưu tiến độ bài học.");
    } catch (error) {
      setMessage(error instanceof Error ? error.message : "Có lỗi xảy ra.");
    } finally {
      setIsSaving(false);
    }
  }

  if (!payload) {
    return (
      <section className="mt-6 rounded-[2rem] border border-blue-100 bg-white p-8 shadow-sm">
        <p className="w-fit rounded-full bg-blue-50 px-4 py-2 text-sm font-black text-[#003A99]">DAYAI LMS</p>
        <h1 className="mt-4 text-3xl font-black">Đang tải bài học</h1>
        <p className="mt-3 text-slate-600">{message || "DAYAI đang kiểm tra quyền truy cập LMS của bạn."}</p>
        <a href="/portal" className="mt-5 inline-flex rounded-full bg-[#003A99] px-5 py-3 text-sm font-black text-white">
          Về dashboard học viên
        </a>
      </section>
    );
  }

  const { lesson, navigation, outline, assessments } = payload;
  const embedUrl = normalizeVideoUrl(lesson.video_url);

  return (
    <section className="mt-6 overflow-hidden rounded-[2rem] border border-blue-100 bg-white shadow-xl shadow-blue-950/5">
      <div className="bg-[radial-gradient(circle_at_top_left,_#00AEEF33,_transparent_35%),linear-gradient(135deg,_#001B4D,_#003A99_55%,_#00AEEF)] p-6 text-white md:p-8">
        <div className="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
          <div>
            <div className="text-sm font-black uppercase tracking-[0.22em] text-blue-100">
              {lesson.course ?? "DAYAI LMS"} · {lesson.module ?? "Bài học"}
            </div>
            <h1 className="mt-3 max-w-4xl text-3xl font-black tracking-[-0.03em] md:text-5xl">{lesson.title}</h1>
            <p className="mt-4 max-w-3xl text-base leading-7 text-blue-50">{lesson.summary ?? "Bài học video thực hành trong hệ sinh thái DAYAI."}</p>
          </div>
          <div className="rounded-3xl bg-white/12 px-5 py-4 text-center backdrop-blur">
            <div className="text-4xl font-black">{progressPercent}%</div>
            <div className="mt-1 text-xs font-black uppercase tracking-[0.2em] text-blue-100">Tiến độ</div>
          </div>
        </div>
      </div>

      <div className="grid gap-6 p-5 lg:grid-cols-[minmax(0,1fr)_360px] lg:p-6">
        <div className="min-w-0 space-y-5">
          <div className="aspect-video overflow-hidden rounded-3xl bg-slate-950 shadow-2xl shadow-blue-950/20">
            {embedUrl ? (
              <iframe src={embedUrl} title={lesson.title} className="h-full w-full" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowFullScreen />
            ) : (
              <div className="flex h-full items-center justify-center px-8 text-center text-white">Chưa có video URL. Admin cần cập nhật link bài học.</div>
            )}
          </div>

          <div className="rounded-[2rem] border border-blue-100 bg-blue-50 p-5">
            <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
              <div>
                <div className="text-sm font-black uppercase tracking-[0.18em] text-[#003A99]">Lưu tiến độ học</div>
                <p className="mt-2 text-sm leading-6 text-slate-600">Cập nhật % hoàn thành và vị trí xem gần nhất để lần sau tiếp tục đúng bài.</p>
              </div>
              <button onClick={() => saveProgress(100)} disabled={isSaving} className="rounded-full bg-[#003A99] px-6 py-3 text-sm font-black text-white shadow-lg shadow-blue-700/20 disabled:opacity-60">
                Đánh dấu hoàn thành
              </button>
            </div>
            <div className="mt-5 h-3 overflow-hidden rounded-full bg-white">
              <div className="h-full rounded-full bg-[#00AEEF]" style={{ width: `${Math.min(progressPercent, 100)}%` }} />
            </div>
            <div className="mt-5 grid gap-3 md:grid-cols-[1fr_1fr_auto] md:items-end">
              <label className="grid gap-2 text-sm font-bold text-slate-700">
                % đã học
                <input type="number" min={0} max={100} value={progressPercent} onChange={(event) => setProgressPercent(Number(event.target.value))} className="rounded-2xl border border-slate-200 bg-white px-4 py-3 outline-none focus:border-[#003A99]" />
              </label>
              <label className="grid gap-2 text-sm font-bold text-slate-700">
                Vị trí xem gần nhất (giây)
                <input type="number" min={0} value={positionSeconds} onChange={(event) => setPositionSeconds(Number(event.target.value))} className="rounded-2xl border border-slate-200 bg-white px-4 py-3 outline-none focus:border-[#003A99]" />
              </label>
              <button onClick={() => saveProgress()} disabled={isSaving} className="rounded-full bg-slate-950 px-6 py-3 text-sm font-black text-white disabled:opacity-60">
                {isSaving ? "Đang lưu..." : "Lưu tiến độ"}
              </button>
            </div>
            {message ? <p className="mt-3 text-sm font-semibold text-[#003A99]">{message}</p> : null}
          </div>

          <div className="grid gap-4 md:grid-cols-2">
            <LessonNavCard label="Bài trước" lesson={navigation.previous_lesson} align="left" />
            <LessonNavCard label="Bài tiếp theo" lesson={navigation.next_lesson} align="right" />
          </div>
        </div>

        <aside className="space-y-5">
          <Panel title="Lộ trình khóa học">
            <div className="space-y-4">
              {outline.map((module) => (
                <div key={module.module_id ?? module.module} className="rounded-3xl border border-slate-200 bg-slate-50 p-4">
                  <div className="text-sm font-black text-slate-950">{module.module}</div>
                  <div className="mt-3 space-y-2">
                    {module.lessons.map((outlineLesson) => {
                      const isActive = outlineLesson.slug === slug;

                      return (
                        <a
                          key={outlineLesson.slug}
                          href={`/portal/bai-hoc/${outlineLesson.slug}`}
                          className={`block rounded-2xl px-3 py-3 text-sm transition ${isActive ? "bg-[#003A99] text-white" : "bg-white text-slate-700 hover:bg-blue-50 hover:text-[#003A99]"}`}
                        >
                          <div className="font-black">{outlineLesson.title}</div>
                          <div className={`mt-1 text-xs ${isActive ? "text-blue-100" : "text-slate-500"}`}>
                            {outlineLesson.duration_minutes ?? 0} phút · {formatLessonStatus(outlineLesson.progress.status)} · {outlineLesson.progress.progress_percent}%
                          </div>
                        </a>
                      );
                    })}
                  </div>
                </div>
              ))}
            </div>
          </Panel>

          <Panel title="Bài kiểm tra liên quan">
            {assessments.length ? (
              <div className="space-y-3">
                {assessments.map((assessment) => (
                  <div key={assessment.id} className="rounded-3xl border border-blue-100 bg-blue-50 p-4">
                    <div className="text-xs font-black uppercase tracking-[0.16em] text-[#003A99]">
                      {formatAssessmentType(assessment.assessment_type)} · {assessment.question_count} câu
                    </div>
                    <div className="mt-2 font-black text-slate-950">{assessment.title}</div>
                    <p className="mt-1 text-sm leading-6 text-slate-600">{assessment.description ?? "Bài kiểm tra giúp mentor đánh giá mức hiểu sau bài học."}</p>
                    {assessment.latest_attempt ? (
                      <div className="mt-3 rounded-2xl bg-white px-3 py-2 text-xs font-bold text-slate-600">
                        Lần gần nhất: {formatQuizStatus(assessment.latest_attempt.status)}
                        {assessment.latest_attempt.score !== null ? ` · ${assessment.latest_attempt.score}/${assessment.latest_attempt.max_score}` : ""}
                      </div>
                    ) : null}
                    <a href={`/portal?tab=tests`} className="mt-3 inline-flex rounded-full bg-[#003A99] px-4 py-2 text-xs font-black text-white">
                      Làm bài trong portal
                    </a>
                  </div>
                ))}
              </div>
            ) : (
              <p className="text-sm leading-6 text-slate-500">Bài học này chưa có bài kiểm tra riêng. Admin có thể gắn assessment vào video hoặc module trong hệ thống.</p>
            )}
          </Panel>

          <Panel title="Tài liệu bài học">
            {lesson.resources.length ? (
              <div className="space-y-3">
                {lesson.resources.map((resource, index) => (
                  <a key={`${resource.title}-${index}`} href={resource.url ?? "#"} className="block rounded-2xl bg-slate-50 px-4 py-3 text-sm font-bold text-slate-700 hover:bg-blue-50 hover:text-[#003A99]">
                    {resource.title ?? `Tài liệu ${index + 1}`}
                  </a>
                ))}
              </div>
            ) : (
              <p className="text-sm text-slate-500">Bài học này chưa có tài liệu tải về.</p>
            )}
          </Panel>
        </aside>
      </div>
    </section>
  );
}

function Panel({ title, children }: { title: string; children: ReactNode }) {
  return (
    <section className="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-sm">
      <h3 className="text-lg font-black text-slate-950">{title}</h3>
      <div className="mt-4">{children}</div>
    </section>
  );
}

function LessonNavCard({ label, lesson, align }: { label: string; lesson: CompactLesson | null; align: "left" | "right" }) {
  if (!lesson) {
    return (
      <div className="rounded-[2rem] border border-dashed border-slate-200 bg-white p-5 text-sm text-slate-500">
        {align === "left" ? "Đây là bài đầu tiên trong lộ trình." : "Bạn đã tới bài cuối của lộ trình hiện tại."}
      </div>
    );
  }

  return (
    <a href={`/portal/bai-hoc/${lesson.slug}`} className="block rounded-[2rem] border border-blue-100 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg hover:shadow-blue-950/10">
      <div className="text-xs font-black uppercase tracking-[0.18em] text-[#003A99]">{label}</div>
      <div className="mt-2 font-black text-slate-950">{lesson.title}</div>
      <div className="mt-2 text-sm text-slate-500">{lesson.duration_minutes ?? 0} phút · {lesson.progress.progress_percent}%</div>
    </a>
  );
}

function updateProgressInPayload(payload: LessonResponse, slug: string, progress: LessonProgress): LessonResponse {
  return {
    ...payload,
    lesson: {
      ...payload.lesson,
      progress,
    },
    outline: payload.outline.map((module) => ({
      ...module,
      lessons: module.lessons.map((lesson) => lesson.slug === slug ? { ...lesson, progress } : lesson),
    })),
  };
}

function normalizeVideoUrl(url: string | null) {
  if (!url) {
    return null;
  }

  try {
    const parsedUrl = new URL(url);
    const host = parsedUrl.hostname.replace("www.", "");

    if (host === "youtube.com" && parsedUrl.pathname === "/watch") {
      const videoId = parsedUrl.searchParams.get("v");
      return videoId ? `https://www.youtube-nocookie.com/embed/${videoId}` : url;
    }

    if (host === "youtu.be") {
      const videoId = parsedUrl.pathname.replace("/", "");
      return videoId ? `https://www.youtube-nocookie.com/embed/${videoId}` : url;
    }

    return url;
  } catch {
    return url;
  }
}

function formatLessonStatus(status: string) {
  return {
    not_started: "Chưa học",
    in_progress: "Đang học",
    completed: "Hoàn thành",
  }[status] ?? status;
}

function formatAssessmentType(type: string) {
  return {
    entry: "Đầu vào",
    quiz: "Quiz nhanh",
    practice: "Luyện tập",
    project: "Dự án",
    final: "Cuối khóa",
    progress: "Tiến bộ",
  }[type] ?? type;
}

function formatQuizStatus(status: string) {
  return {
    in_progress: "Đang làm",
    submitted: "Đã nộp",
    graded: "Đã chấm",
    cancelled: "Đã hủy",
  }[status] ?? status;
}
