"use client";

import Link from "next/link";
import { useCallback, useEffect, useMemo, useRef, useState } from "react";
import type { ReactNode } from "react";
import {
  DayaiAlert,
  DayaiButton,
  DayaiEmptyState,
  DayaiField,
  DayaiInput,
  DayaiPanel,
  dayaiButtonClasses,
} from "@/components/ui/dayai-ui";
import {
  InteractiveVideoPlayer,
  type LessonCheckpoint,
  type LessonCheckpointAnswer,
  type LessonCheckpointOption,
  type LessonSeekRequest,
} from "@/components/portal/interactive-video-player";
import {
  LessonNotebook,
  type LessonPromptNote,
} from "@/components/portal/lesson-notebook";
import {
  LessonPracticeRoom,
  type LessonPracticeSession,
} from "@/components/portal/lesson-practice-room";
import {
  LessonTimeline,
  type LessonTimelineNote,
} from "@/components/portal/lesson-timeline";
import { LessonCompletionSummary } from "@/components/portal/lesson-completion-summary";

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
  checkpoint_answers: LessonCheckpointAnswer[];
  learner_notes: string;
  practice_sessions: LessonPracticeSession[];
  attention_metrics: {
    hidden_pause_count: number;
    idle_pause_count: number;
  };
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
  interactive_learning: {
    required_for_completion: boolean;
    checkpoints: LessonCheckpoint[];
    prompt_notes: LessonPromptNote[];
    timeline_notes: LessonTimelineNote[];
  } | null;
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

type SaveProgressOptions = {
  checkpointAnswers?: LessonCheckpointAnswer[];
  learnerNotes?: string;
  positionSeconds?: number;
  practiceSessions?: LessonPracticeSession[];
  attentionMetrics?: LessonProgress["attention_metrics"];
  silent?: boolean;
  saveKind?: "progress" | "notes" | "practice" | "attention";
};

export function PortalLessonPlayer({ slug }: { slug: string }) {
  const [payload, setPayload] = useState<LessonResponse | null>(null);
  const [message, setMessage] = useState("");
  const [progressPercent, setProgressPercent] = useState(0);
  const [positionSeconds, setPositionSeconds] = useState(0);
  const [isSaving, setIsSaving] = useState(false);
  const [isFocusMode, setIsFocusMode] = useState(false);
  const [checkpointAnswers, setCheckpointAnswers] = useState<LessonCheckpointAnswer[]>([]);
  const [learnerNotes, setLearnerNotes] = useState("");
  const [practiceSessions, setPracticeSessions] = useState<LessonPracticeSession[]>([]);
  const [attentionMetrics, setAttentionMetrics] = useState<LessonProgress["attention_metrics"]>({
    hidden_pause_count: 0,
    idle_pause_count: 0,
  });
  const [notesSaveStatus, setNotesSaveStatus] = useState<"idle" | "dirty" | "saving" | "saved" | "error">("idle");
  const [seekRequest, setSeekRequest] = useState<LessonSeekRequest | null>(null);
  const notesHydratedRef = useRef(false);
  const saveRequestRef = useRef(0);

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
      setCheckpointAnswers(nextPayload.lesson.progress.checkpoint_answers ?? []);
      setLearnerNotes(nextPayload.lesson.progress.learner_notes ?? "");
      setPracticeSessions(nextPayload.lesson.progress.practice_sessions ?? []);
      setAttentionMetrics(nextPayload.lesson.progress.attention_metrics ?? {
        hidden_pause_count: 0,
        idle_pause_count: 0,
      });
      notesHydratedRef.current = true;
    }

    void loadLesson();
  }, [session, slug]);

  async function saveProgress(nextPercent = progressPercent, options: SaveProgressOptions = {}) {
    if (!session) {
      setMessage("Phiên portal đã hết hạn. Vui lòng xác thực lại.");
      return false;
    }

    const normalizedPercent = Math.max(0, Math.min(100, Math.round(nextPercent)));
    const requestId = ++saveRequestRef.current;
    setIsSaving(true);

    if (options.saveKind === "notes") {
      setNotesSaveStatus("saving");
    }

    if (!options.silent) {
      setMessage("");
    }

    try {
      const answersToSave = options.checkpointAnswers ?? checkpointAnswers;
      const response = await fetch(`/api/portal/lessons/${slug}/progress`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          ...session,
          progress_percent: normalizedPercent,
          last_position_seconds: Math.max(0, Math.round(options.positionSeconds ?? positionSeconds)),
          checkpoint_answers: answersToSave.map((answer) => ({
            checkpoint_id: answer.checkpoint_id,
            selected_option_id: answer.selected_option_id,
          })),
          ...(options.learnerNotes !== undefined ? { learner_notes: options.learnerNotes } : {}),
          ...(options.practiceSessions !== undefined ? { practice_sessions: options.practiceSessions } : {}),
          ...(options.attentionMetrics !== undefined ? { attention_metrics: options.attentionMetrics } : {}),
        }),
      });
      const result = await response.json();

      if (!response.ok) {
        throw new Error(result.message ?? "Không lưu được tiến độ.");
      }

      setProgressPercent(result.progress.progress_percent);
      setPayload((current) => current ? updateProgressInPayload(current, slug, result.progress) : current);
      setCheckpointAnswers(result.progress.checkpoint_answers ?? answersToSave);
      setPracticeSessions(result.progress.practice_sessions ?? options.practiceSessions ?? practiceSessions);
      setAttentionMetrics(result.progress.attention_metrics ?? options.attentionMetrics ?? attentionMetrics);

      if (options.learnerNotes !== undefined) {
        setLearnerNotes(result.progress.learner_notes ?? options.learnerNotes);
      }

      if (options.saveKind === "notes") {
        setNotesSaveStatus("saved");
      }

      if (!options.silent) {
        setMessage(result.message ?? "Đã lưu tiến độ bài học.");
      }

      return true;
    } catch (error) {
      if (options.saveKind === "notes") {
        setNotesSaveStatus("error");
      }
      setMessage(error instanceof Error ? error.message : "Có lỗi xảy ra.");
      return false;
    } finally {
      if (requestId === saveRequestRef.current) {
        setIsSaving(false);
      }
    }
  }

  function handleVideoPosition(nextPositionSeconds: number, nextProgressPercent: number) {
    setPositionSeconds(nextPositionSeconds);
    setProgressPercent(nextProgressPercent);
  }

  function handleCheckpointAnswer(checkpoint: LessonCheckpoint, option: LessonCheckpointOption) {
    const answer: LessonCheckpointAnswer = {
      checkpoint_id: checkpoint.id,
      selected_option_id: option.id,
      is_correct: option.id === checkpoint.correct_option_id,
      answered_at: new Date().toISOString(),
    };
    const nextAnswers = [
      ...checkpointAnswers.filter((current) => current.checkpoint_id !== checkpoint.id),
      answer,
    ];

    setCheckpointAnswers(nextAnswers);
    void saveProgress(Math.min(progressPercent, 94), {
      checkpointAnswers: nextAnswers,
      positionSeconds,
      silent: true,
    });
  }

  function handleVideoComplete() {
    void saveProgress(100, {
      checkpointAnswers,
      positionSeconds,
    });
  }

  function handleSaveNotes() {
    void saveProgress(progressPercent, {
      checkpointAnswers,
      learnerNotes,
      positionSeconds,
      saveKind: "notes",
    });
  }

  function handleNotesChange(nextNotes: string) {
    setLearnerNotes(nextNotes);

    if (notesHydratedRef.current) {
      setNotesSaveStatus("dirty");
    }
  }

  function handlePracticeSave(session: LessonPracticeSession) {
    const nextSessions = [
      ...practiceSessions.filter((current) => current.prompt_id !== session.prompt_id),
      session,
    ];

    setPracticeSessions(nextSessions);
    void saveProgress(progressPercent, {
      checkpointAnswers,
      practiceSessions: nextSessions,
      positionSeconds,
      saveKind: "practice",
    });
  }

  const handleAttentionPause = useCallback((reason: "hidden" | "idle") => {
    setAttentionMetrics((current) => {
      const nextMetrics = {
        ...current,
        [reason === "hidden" ? "hidden_pause_count" : "idle_pause_count"]:
          current[reason === "hidden" ? "hidden_pause_count" : "idle_pause_count"] + 1,
      };

      void saveProgress(progressPercent, {
        checkpointAnswers,
        attentionMetrics: nextMetrics,
        positionSeconds,
        silent: true,
        saveKind: "attention",
      });

      return nextMetrics;
    });
  // The callback deliberately reads the latest render state and is recreated when learning state changes.
  // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [checkpointAnswers, positionSeconds, progressPercent]);

  function handleSeek(seconds: number) {
    setSeekRequest({
      id: Date.now(),
      seconds,
    });
  }

  function handleRetryCheckpoint(checkpoint: LessonCheckpoint) {
    const nextAnswers = checkpointAnswers.filter((answer) => answer.checkpoint_id !== checkpoint.id);
    setCheckpointAnswers(nextAnswers);
    setSeekRequest({
      id: Date.now(),
      seconds: checkpoint.at_seconds,
    });
    void saveProgress(Math.min(progressPercent, 94), {
      checkpointAnswers: nextAnswers,
      positionSeconds: checkpoint.at_seconds,
      silent: true,
    });
  }

  useEffect(() => {
    if (!notesHydratedRef.current || notesSaveStatus !== "dirty") {
      return;
    }

    const timer = window.setTimeout(() => {
      void saveProgress(progressPercent, {
        checkpointAnswers,
        learnerNotes,
        positionSeconds,
        silent: true,
        saveKind: "notes",
      });
    }, 1000);

    return () => window.clearTimeout(timer);
  // Saving is intentionally debounced from note changes only.
  // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [learnerNotes, notesSaveStatus]);

  if (!payload) {
    return (
      <DayaiPanel className="mt-6 p-8">
        <p className="w-fit rounded-full bg-blue-50 px-4 py-2 text-sm font-black text-[var(--dayai-primary)]">DAYAI LMS</p>
        <h1 className="mt-4 text-3xl font-black">Đang tải bài học</h1>
        <p className="mt-3 text-slate-600">{message || "DAYAI đang kiểm tra quyền truy cập LMS của bạn."}</p>
        <Link href="/portal" className={dayaiButtonClasses({ className: "mt-5" })}>
          Về dashboard học viên
        </Link>
      </DayaiPanel>
    );
  }

  const { lesson, navigation, outline, assessments } = payload;
  const embedUrl = normalizeVideoUrl(lesson.video_url);
  const checkpoints = lesson.interactive_learning?.checkpoints ?? [];
  const promptNotes = lesson.interactive_learning?.prompt_notes ?? [];
  const timelineNotes = lesson.interactive_learning?.timeline_notes ?? [];
  const usesDirectVideo = lesson.video_provider === "internal" || isDirectVideoUrl(lesson.video_url);
  const allCheckpointsAnswered = checkpoints.every((checkpoint) =>
    checkpointAnswers.some((answer) => answer.checkpoint_id === checkpoint.id),
  );

  return (
    <section className={`${isFocusMode ? "mt-2" : "mt-6"} overflow-hidden rounded-[var(--dayai-radius-2xl)] border border-blue-100 bg-white shadow-xl shadow-blue-950/5`}>
      {!isFocusMode ? (
      <div className="relative overflow-hidden bg-[radial-gradient(circle_at_top_left,_#00AEEF33,_transparent_35%),linear-gradient(135deg,_#001B4D,_#003A99_55%,_#00AEEF)] p-5 text-white md:p-8">
        <div className="absolute inset-0 opacity-15 dayai-muted-grid" />
        <div className="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
          <div className="relative">
            <div className="text-sm font-black uppercase tracking-[0.22em] text-blue-100">
              {lesson.course ?? "DAYAI LMS"} · {lesson.module ?? "Bài học"}
            </div>
            <h1 className="mt-2 max-w-4xl text-2xl font-black tracking-[-0.03em] md:mt-3 md:text-5xl">{lesson.title}</h1>
            <p className="mt-3 hidden max-w-3xl text-base leading-7 text-blue-50 sm:block md:mt-4">{lesson.summary ?? "Bài học video thực hành trong hệ sinh thái DAYAI."}</p>
            <DayaiButton
              size="sm"
              variant="secondary"
              className="mt-4"
              onClick={() => setIsFocusMode(true)}
            >
              Chế độ tập trung
            </DayaiButton>
          </div>
          <div className="relative flex items-center justify-between rounded-2xl bg-white/12 px-4 py-3 text-left backdrop-blur sm:block sm:rounded-3xl sm:px-5 sm:py-4 sm:text-center">
            <div className="text-2xl font-black sm:text-4xl">{progressPercent}%</div>
            <div className="text-xs font-black uppercase tracking-[0.2em] text-blue-100 sm:mt-1">Tiến độ</div>
          </div>
        </div>
      </div>
      ) : (
        <div className="sticky top-0 z-20 flex items-center justify-between gap-3 border-b border-blue-100 bg-white/95 px-4 py-3 backdrop-blur">
          <div className="min-w-0">
            <div className="text-xs font-black uppercase tracking-[0.14em] text-[var(--dayai-primary)]">Đang học tập trung · {progressPercent}%</div>
            <div className="truncate font-black text-slate-950">{lesson.title}</div>
          </div>
          <DayaiButton size="sm" variant="secondary" onClick={() => setIsFocusMode(false)}>
            Thoát tập trung
          </DayaiButton>
        </div>
      )}

      <div className={`grid gap-6 p-4 lg:p-6 ${isFocusMode ? "xl:grid-cols-[minmax(0,1fr)_420px]" : "lg:grid-cols-[minmax(0,1fr)_360px]"}`}>
        <div className="min-w-0 space-y-5">
          <div className="overflow-hidden rounded-[var(--dayai-radius-2xl)] border border-slate-800 bg-slate-950 p-2 shadow-2xl shadow-blue-950/20">
            {usesDirectVideo && lesson.video_url ? (
              <InteractiveVideoPlayer
                title={lesson.title}
                videoUrl={lesson.video_url}
                checkpoints={checkpoints}
                answers={checkpointAnswers}
                initialPositionSeconds={lesson.progress.last_position_seconds}
                onPositionChange={handleVideoPosition}
                onAnswer={handleCheckpointAnswer}
                onComplete={handleVideoComplete}
                onAttentionPause={handleAttentionPause}
                seekRequest={seekRequest}
              />
            ) : (
              <div className="aspect-video overflow-hidden rounded-[var(--dayai-radius-xl)] bg-slate-900">
                {embedUrl ? (
                  <iframe src={embedUrl} title={lesson.title} className="h-full w-full" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowFullScreen />
                ) : (
                  <div className="flex h-full items-center justify-center px-8 text-center text-white">Chưa có video URL. Admin cần cập nhật link bài học.</div>
                )}
              </div>
            )}
          </div>

          {timelineNotes.length ? (
            <LessonTimeline
              notes={timelineNotes}
              activeSeconds={positionSeconds}
              onSeek={handleSeek}
            />
          ) : null}

          {!isFocusMode ? (
            <>
              <LessonNotebook
                prompts={promptNotes}
                notes={learnerNotes}
                isSaving={isSaving}
                saveStatus={notesSaveStatus}
                onNotesChange={handleNotesChange}
                onSave={handleSaveNotes}
              />
              <LessonPracticeRoom
                prompts={promptNotes}
                sessions={practiceSessions}
                isSaving={isSaving}
                onSave={handlePracticeSave}
              />
            </>
          ) : null}

          <div className="rounded-[var(--dayai-radius-2xl)] border border-blue-100 bg-blue-50 p-5">
            <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
              <div>
                <div className="text-sm font-black uppercase tracking-[0.18em] text-[var(--dayai-primary)]">Lưu tiến độ học</div>
                <p className="mt-2 text-sm leading-6 text-slate-600">Cập nhật % hoàn thành và vị trí xem gần nhất để lần sau tiếp tục đúng bài.</p>
              </div>
              {checkpoints.length ? (
                <DayaiButton variant="dark" onClick={() => saveProgress(progressPercent)} disabled={isSaving}>
                  {isSaving ? "Đang lưu..." : "Lưu vị trí hiện tại"}
                </DayaiButton>
              ) : (
                <DayaiButton onClick={() => saveProgress(100)} disabled={isSaving}>
                  Đánh dấu hoàn thành
                </DayaiButton>
              )}
            </div>
            <div className="mt-5 h-3 overflow-hidden rounded-full bg-white">
              <div className="h-full rounded-full bg-[#00AEEF]" style={{ width: `${Math.min(progressPercent, 100)}%` }} />
            </div>
            {checkpoints.length ? (
              <div className="mt-5 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                <MiniProgressStat label="Vị trí video" value={formatVideoTime(positionSeconds)} />
                <MiniProgressStat label="Câu đã trả lời" value={`${checkpointAnswers.length}/${checkpoints.length}`} />
                <MiniProgressStat label="Trạng thái" value={allCheckpointsAnswered ? "Đã mở khóa" : "Đang học"} />
                <MiniProgressStat
                  label="Bảo vệ tập trung"
                  value={`${attentionMetrics.hidden_pause_count + attentionMetrics.idle_pause_count} lần dừng`}
                />
                {!allCheckpointsAnswered ? (
                  <DayaiAlert tone="warning" className="sm:col-span-2 xl:col-span-4">
                    Video chỉ được đánh dấu hoàn thành sau khi bạn xem đến cuối và trả lời đủ {checkpoints.length} câu hỏi nhanh.
                  </DayaiAlert>
                ) : null}
              </div>
            ) : (
              <div className="mt-5 grid gap-3 md:grid-cols-[1fr_1fr_auto] md:items-end">
                <DayaiField label="% đã học">
                  <DayaiInput type="number" min={0} max={100} value={progressPercent} onChange={(event) => setProgressPercent(Number(event.target.value))} />
                </DayaiField>
                <DayaiField label="Vị trí xem gần nhất (giây)">
                  <DayaiInput type="number" min={0} value={positionSeconds} onChange={(event) => setPositionSeconds(Number(event.target.value))} />
                </DayaiField>
                <DayaiButton variant="dark" onClick={() => saveProgress()} disabled={isSaving}>
                  {isSaving ? "Đang lưu..." : "Lưu tiến độ"}
                </DayaiButton>
              </div>
            )}
            {message ? <DayaiAlert tone="info" className="mt-4">{message}</DayaiAlert> : null}
          </div>

          <LessonCompletionSummary
            checkpoints={checkpoints}
            answers={checkpointAnswers}
            notes={learnerNotes}
            practiceCount={practiceSessions.length}
            progressPercent={progressPercent}
            nextLesson={navigation.next_lesson}
            onRetry={handleRetryCheckpoint}
          />

          <div className="grid gap-4 md:grid-cols-2">
            <LessonNavCard label="Bài trước" lesson={navigation.previous_lesson} align="left" />
            <LessonNavCard label="Bài tiếp theo" lesson={navigation.next_lesson} align="right" />
          </div>
        </div>

        {isFocusMode ? (
          <aside className="space-y-5 xl:sticky xl:top-20 xl:self-start">
            <LessonNotebook
              prompts={promptNotes}
              notes={learnerNotes}
              isSaving={isSaving}
              saveStatus={notesSaveStatus}
              onNotesChange={handleNotesChange}
              onSave={handleSaveNotes}
            />
            <LessonPracticeRoom
              prompts={promptNotes}
              sessions={practiceSessions}
              isSaving={isSaving}
              onSave={handlePracticeSave}
            />
          </aside>
        ) : (
        <aside className="space-y-5">
          <Panel title="Lộ trình khóa học">
            <div className="space-y-4">
              {outline.map((courseModule) => (
                <div key={courseModule.module_id ?? courseModule.module} className="rounded-3xl border border-slate-200 bg-slate-50 p-4">
                  <div className="text-sm font-black text-slate-950">{courseModule.module}</div>
                  <div className="mt-3 space-y-2">
                    {courseModule.lessons.map((outlineLesson) => {
                      const isActive = outlineLesson.slug === slug;

                      return (
                        <Link
                          key={outlineLesson.slug}
                          href={`/portal/bai-hoc/${outlineLesson.slug}`}
                          className={`block rounded-2xl px-3 py-3 text-sm transition ${isActive ? "bg-[var(--dayai-primary)] text-white" : "bg-white text-slate-700 hover:bg-blue-50 hover:text-[var(--dayai-primary)]"}`}
                        >
                          <div className="font-black">{outlineLesson.title}</div>
                          <div className={`mt-1 text-xs ${isActive ? "text-blue-100" : "text-slate-500"}`}>
                            {outlineLesson.duration_minutes ?? 0} phút · {formatLessonStatus(outlineLesson.progress.status)} · {outlineLesson.progress.progress_percent}%
                          </div>
                        </Link>
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
                    <div className="text-xs font-black uppercase tracking-[0.16em] text-[var(--dayai-primary)]">
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
                    <Link href="/portal?tab=tests" className={dayaiButtonClasses({ size: "sm", className: "mt-3" })}>
                      Làm bài trong portal
                    </Link>
                  </div>
                ))}
              </div>
            ) : (
              <DayaiEmptyState
                compact
                title="Chưa có bài kiểm tra riêng"
                description="Admin có thể gắn assessment vào video hoặc module trong hệ thống."
              />
            )}
          </Panel>

          <Panel title="Tài liệu bài học">
            {lesson.resources.length ? (
              <div className="space-y-3">
                {lesson.resources.map((resource, index) => (
                  <a key={`${resource.title}-${index}`} href={resource.url ?? "#"} className="block rounded-2xl bg-slate-50 px-4 py-3 text-sm font-bold text-slate-700 transition hover:bg-blue-50 hover:text-[var(--dayai-primary)]">
                    {resource.title ?? `Tài liệu ${index + 1}`}
                  </a>
                ))}
              </div>
            ) : (
              <DayaiEmptyState compact title="Bài học này chưa có tài liệu tải về." />
            )}
          </Panel>
        </aside>
        )}
      </div>
    </section>
  );
}

function Panel({ title, children }: { title: string; children: ReactNode }) {
  return <DayaiPanel title={title} className="p-5">{children}</DayaiPanel>;
}

function MiniProgressStat({ label, value }: { label: string; value: string }) {
  return (
    <div className="rounded-2xl border border-blue-100 bg-white px-4 py-3">
      <div className="text-xs font-black uppercase tracking-[0.12em] text-slate-500">{label}</div>
      <div className="mt-1 font-black text-slate-950">{value}</div>
    </div>
  );
}

function LessonNavCard({ label, lesson, align }: { label: string; lesson: CompactLesson | null; align: "left" | "right" }) {
  if (!lesson) {
    return (
      <DayaiEmptyState
        compact
        title={align === "left" ? "Đây là bài đầu tiên trong lộ trình." : "Bạn đã tới bài cuối của lộ trình hiện tại."}
      />
    );
  }

  return (
    <Link href={`/portal/bai-hoc/${lesson.slug}`} className="block rounded-[var(--dayai-radius-2xl)] border border-blue-100 bg-white p-5 shadow-[var(--dayai-shadow-sm)] transition hover:-translate-y-0.5 hover:shadow-lg hover:shadow-blue-950/10">
      <div className="text-xs font-black uppercase tracking-[0.18em] text-[var(--dayai-primary)]">{label}</div>
      <div className="mt-2 font-black text-slate-950">{lesson.title}</div>
      <div className="mt-2 text-sm text-slate-500">{lesson.duration_minutes ?? 0} phút · {lesson.progress.progress_percent}%</div>
    </Link>
  );
}

function updateProgressInPayload(payload: LessonResponse, slug: string, progress: LessonProgress): LessonResponse {
  return {
    ...payload,
    lesson: {
      ...payload.lesson,
      progress,
    },
    outline: payload.outline.map((courseModule) => ({
      ...courseModule,
      lessons: courseModule.lessons.map((lesson) => lesson.slug === slug ? { ...lesson, progress } : lesson),
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

function isDirectVideoUrl(url: string | null) {
  if (!url) {
    return false;
  }

  return /\.(mp4|webm|ogg)(?:$|[?#])/i.test(url);
}

function formatVideoTime(seconds: number) {
  const safeSeconds = Math.max(0, Math.round(seconds));
  const minutes = Math.floor(safeSeconds / 60);
  const remainder = safeSeconds % 60;

  return `${minutes}:${String(remainder).padStart(2, "0")}`;
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
