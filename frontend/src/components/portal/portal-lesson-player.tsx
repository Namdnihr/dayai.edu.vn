"use client";

import { useEffect, useMemo, useState } from "react";

type PortalSession = {
  phone: string;
  student_code: string;
  portal_access_token: string;
};

type LessonData = {
  title: string;
  slug: string;
  summary: string | null;
  course: string | null;
  module: string | null;
  video_provider: string;
  video_url: string | null;
  duration_minutes: number | null;
  resources: Array<{ title?: string; url?: string }>;
  progress: {
    status: string;
    progress_percent: number;
    last_position_seconds: number;
    last_watched_at: string | null;
    completed_at: string | null;
  };
};

export function PortalLessonPlayer({ slug }: { slug: string }) {
  const [lesson, setLesson] = useState<LessonData | null>(null);
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

      setLesson(result.lesson as LessonData);
      setProgressPercent(result.lesson.progress.progress_percent);
      setPositionSeconds(result.lesson.progress.last_position_seconds);
    }

    void loadLesson();
  }, [session, slug]);

  async function saveProgress(nextPercent = progressPercent) {
    if (!session) {
      setMessage("Phiên portal đã hết hạn. Vui lòng xác thực lại.");
      return;
    }

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
          progress_percent: nextPercent,
          last_position_seconds: positionSeconds,
        }),
      });
      const result = await response.json();

      if (!response.ok) {
        throw new Error(result.message ?? "Không lưu được tiến độ.");
      }

      setProgressPercent(result.progress.progress_percent);
      setMessage("Đã lưu tiến độ bài học.");
    } catch (error) {
      setMessage(error instanceof Error ? error.message : "Có lỗi xảy ra.");
    } finally {
      setIsSaving(false);
    }
  }

  if (!lesson) {
    return (
      <section className="mt-6 rounded-[2rem] border border-blue-100 bg-white p-8 shadow-sm">
        <h1 className="text-3xl font-black">Đang tải bài học</h1>
        <p className="mt-3 text-slate-600">{message || "DAYAI đang kiểm tra quyền truy cập LMS của bạn."}</p>
      </section>
    );
  }

  return (
    <section className="mt-6 overflow-hidden rounded-[2rem] border border-blue-100 bg-white shadow-xl shadow-blue-950/5">
      <div className="bg-slate-950 p-6 text-white">
        <div className="text-sm font-black uppercase tracking-[0.2em] text-blue-200">{lesson.course ?? "DAYAI LMS"} · {lesson.module ?? "Bài học"}</div>
        <h1 className="mt-3 text-4xl font-black">{lesson.title}</h1>
        <p className="mt-3 max-w-3xl text-slate-300">{lesson.summary ?? "Bài học video thực hành trong hệ sinh thái DAYAI."}</p>
      </div>

      <div className="grid gap-6 p-6 lg:grid-cols-[1fr_320px]">
        <div>
          <div className="aspect-video overflow-hidden rounded-3xl bg-slate-900">
            {lesson.video_url ? (
              <iframe src={lesson.video_url} title={lesson.title} className="h-full w-full" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowFullScreen />
            ) : (
              <div className="flex h-full items-center justify-center px-8 text-center text-white">Chưa có video URL. Admin cần cập nhật link bài học.</div>
            )}
          </div>

          <div className="mt-6 rounded-3xl bg-blue-50 p-5">
            <div className="flex items-center justify-between gap-4">
              <div>
                <div className="text-sm font-black text-[#003A99]">Tiến độ hiện tại</div>
                <div className="mt-1 text-3xl font-black text-slate-950">{progressPercent}%</div>
              </div>
              <button onClick={() => saveProgress(100)} disabled={isSaving} className="rounded-full bg-[#003A99] px-6 py-3 text-sm font-black text-white disabled:opacity-60">
                Đánh dấu hoàn thành
              </button>
            </div>
            <div className="mt-4 h-3 overflow-hidden rounded-full bg-white">
              <div className="h-full rounded-full bg-[#00AEEF]" style={{ width: `${Math.min(progressPercent, 100)}%` }} />
            </div>
          </div>
        </div>

        <aside className="space-y-4">
          <div className="rounded-3xl border border-slate-200 p-5">
            <div className="text-sm font-black uppercase tracking-[0.18em] text-slate-500">Cập nhật nhanh</div>
            <label className="mt-4 grid gap-2 text-sm font-bold text-slate-700">
              % đã học
              <input type="number" min={0} max={100} value={progressPercent} onChange={(event) => setProgressPercent(Number(event.target.value))} className="rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-[#003A99]" />
            </label>
            <label className="mt-3 grid gap-2 text-sm font-bold text-slate-700">
              Vị trí xem gần nhất (giây)
              <input type="number" min={0} value={positionSeconds} onChange={(event) => setPositionSeconds(Number(event.target.value))} className="rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-[#003A99]" />
            </label>
            <button onClick={() => saveProgress()} disabled={isSaving} className="mt-4 w-full rounded-full bg-slate-950 px-6 py-3 text-sm font-black text-white disabled:opacity-60">
              {isSaving ? "Đang lưu..." : "Lưu tiến độ"}
            </button>
            {message ? <p className="mt-3 text-sm font-semibold text-[#003A99]">{message}</p> : null}
          </div>

          <div className="rounded-3xl border border-slate-200 p-5">
            <div className="text-sm font-black uppercase tracking-[0.18em] text-slate-500">Tài liệu bài học</div>
            <div className="mt-4 space-y-3">
              {lesson.resources.length ? lesson.resources.map((resource, index) => (
                <a key={`${resource.title}-${index}`} href={resource.url ?? "#"} className="block rounded-2xl bg-slate-50 px-4 py-3 text-sm font-bold text-slate-700 hover:bg-blue-50">
                  {resource.title ?? `Tài liệu ${index + 1}`}
                </a>
              )) : <p className="text-sm text-slate-500">Bài học này chưa có tài liệu tải về.</p>}
            </div>
          </div>
        </aside>
      </div>
    </section>
  );
}
