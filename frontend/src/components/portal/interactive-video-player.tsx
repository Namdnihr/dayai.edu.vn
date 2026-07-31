"use client";

import { useEffect, useMemo, useRef, useState } from "react";
import { DayaiAlert, DayaiBadge, DayaiButton } from "@/components/ui/dayai-ui";

export type LessonCheckpointOption = {
  id: string;
  label: string;
};

export type LessonCheckpoint = {
  id: string;
  at_seconds: number;
  time_limit_seconds: number;
  question: string;
  options: LessonCheckpointOption[];
  correct_option_id: string;
  explanation: string;
};

export type LessonCheckpointAnswer = {
  checkpoint_id: string;
  selected_option_id: string;
  is_correct: boolean;
  answered_at: string;
};

export type LessonSeekRequest = {
  id: number;
  seconds: number;
};

export function InteractiveVideoPlayer({
  title,
  videoUrl,
  checkpoints,
  answers,
  initialPositionSeconds,
  onPositionChange,
  onAnswer,
  onComplete,
  onAttentionPause,
  seekRequest,
}: {
  title: string;
  videoUrl: string;
  checkpoints: LessonCheckpoint[];
  answers: LessonCheckpointAnswer[];
  initialPositionSeconds: number;
  onPositionChange: (positionSeconds: number, progressPercent: number) => void;
  onAnswer: (checkpoint: LessonCheckpoint, option: LessonCheckpointOption) => void;
  onComplete: () => void;
  onAttentionPause: (reason: "hidden" | "idle") => void;
  seekRequest: LessonSeekRequest | null;
}) {
  const videoRef = useRef<HTMLVideoElement>(null);
  const stageRef = useRef<HTMLDivElement>(null);
  const lastReportedSecond = useRef(-1);
  const [activeCheckpoint, setActiveCheckpoint] = useState<LessonCheckpoint | null>(null);
  const [selectedOptionId, setSelectedOptionId] = useState("");
  const [timeRemaining, setTimeRemaining] = useState(30);
  const [attentionNotice, setAttentionNotice] = useState<"resume" | "hidden" | "idle" | null>(null);
  const [isFullscreen, setIsFullscreen] = useState(false);
  const lastActivityAt = useRef(Date.now());
  const sortedCheckpoints = useMemo(
    () => [...checkpoints].sort((left, right) => left.at_seconds - right.at_seconds),
    [checkpoints],
  );
  const answersByCheckpoint = useMemo(
    () => new Map(answers.map((answer) => [answer.checkpoint_id, answer])),
    [answers],
  );
  const activeAnswer = activeCheckpoint ? answersByCheckpoint.get(activeCheckpoint.id) : undefined;

  useEffect(() => {
    function handleFullscreenChange() {
      setIsFullscreen(document.fullscreenElement === stageRef.current);
    }

    document.addEventListener("fullscreenchange", handleFullscreenChange);

    return () => document.removeEventListener("fullscreenchange", handleFullscreenChange);
  }, []);

  useEffect(() => {
    function recordActivity() {
      lastActivityAt.current = Date.now();
    }

    function handleVisibilityChange() {
      const video = videoRef.current;

      if (document.hidden && video && !video.paused) {
        video.pause();
        setAttentionNotice("hidden");
        onAttentionPause("hidden");
      }
    }

    const activityEvents: Array<keyof WindowEventMap> = ["pointerdown", "keydown", "touchstart"];
    activityEvents.forEach((eventName) => window.addEventListener(eventName, recordActivity, { passive: true }));
    document.addEventListener("visibilitychange", handleVisibilityChange);
    const idleTimer = window.setInterval(() => {
      const video = videoRef.current;

      if (video && !video.paused && Date.now() - lastActivityAt.current >= 120_000) {
        video.pause();
        setAttentionNotice("idle");
        onAttentionPause("idle");
      }
    }, 5_000);

    return () => {
      activityEvents.forEach((eventName) => window.removeEventListener(eventName, recordActivity));
      document.removeEventListener("visibilitychange", handleVisibilityChange);
      window.clearInterval(idleTimer);
    };
  }, [onAttentionPause]);

  useEffect(() => {
    const video = videoRef.current;

    if (!video || !seekRequest) {
      return;
    }

    const frame = window.requestAnimationFrame(() => {
      video.pause();
      video.currentTime = Math.max(0, Math.min(seekRequest.seconds, Math.max(video.duration - 0.5, 0)));
      const pendingCheckpoint = sortedCheckpoints.find(
        (checkpoint) => Math.abs(checkpoint.at_seconds - seekRequest.seconds) < 1 && !answersByCheckpoint.has(checkpoint.id),
      );

      if (pendingCheckpoint) {
        openCheckpoint(pendingCheckpoint);
      } else {
        setAttentionNotice("resume");
      }
    });

    return () => window.cancelAnimationFrame(frame);
  // `seekRequest.id` intentionally makes repeated requests to the same second observable.
  // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [seekRequest?.id]);

  useEffect(() => {
    if (!activeCheckpoint || activeAnswer) {
      return;
    }

    const timer = window.setInterval(() => {
      setTimeRemaining((current) => Math.max(0, current - 1));
    }, 1000);

    return () => window.clearInterval(timer);
  }, [activeAnswer, activeCheckpoint]);

  function findPendingCheckpoint(currentTime: number) {
    return sortedCheckpoints.find(
      (checkpoint) => checkpoint.at_seconds <= currentTime + 0.2 && !answersByCheckpoint.has(checkpoint.id),
    );
  }

  function openCheckpoint(checkpoint: LessonCheckpoint) {
    const video = videoRef.current;

    if (video) {
      video.pause();

      if (video.currentTime > checkpoint.at_seconds + 1) {
        video.currentTime = checkpoint.at_seconds;
      }
    }

    setSelectedOptionId("");
    setTimeRemaining(checkpoint.time_limit_seconds || 30);
    setActiveCheckpoint(checkpoint);
  }

  function handleLoadedMetadata() {
    const video = videoRef.current;

    if (!video) {
      return;
    }

    const safeInitialPosition = Math.min(
      Math.max(initialPositionSeconds, 0),
      Math.max(video.duration - 0.5, 0),
    );
    const pendingCheckpoint = findPendingCheckpoint(safeInitialPosition);

    if (pendingCheckpoint) {
      video.currentTime = pendingCheckpoint.at_seconds;
      openCheckpoint(pendingCheckpoint);
      return;
    }

    video.currentTime = safeInitialPosition;

    if (safeInitialPosition >= 5) {
      setAttentionNotice("resume");
    }
  }

  function handleTimeUpdate() {
    const video = videoRef.current;

    if (!video) {
      return;
    }

    const currentSecond = Math.floor(video.currentTime);

    if (currentSecond !== lastReportedSecond.current) {
      lastReportedSecond.current = currentSecond;
      const progressPercent = video.duration
        ? Math.min(99, Math.round((video.currentTime / video.duration) * 100))
        : 0;
      onPositionChange(currentSecond, progressPercent);
    }

    const pendingCheckpoint = findPendingCheckpoint(video.currentTime);

    if (pendingCheckpoint && activeCheckpoint?.id !== pendingCheckpoint.id) {
      openCheckpoint(pendingCheckpoint);
    }
  }

  function handlePlay() {
    if (activeCheckpoint && !answersByCheckpoint.has(activeCheckpoint.id)) {
      videoRef.current?.pause();
      return;
    }

    if (attentionNotice) {
      videoRef.current?.pause();
    }
  }

  function handleEnded() {
    const pendingCheckpoint = sortedCheckpoints.find((checkpoint) => !answersByCheckpoint.has(checkpoint.id));

    if (pendingCheckpoint) {
      openCheckpoint(pendingCheckpoint);
      return;
    }

    onPositionChange(Math.round(videoRef.current?.duration ?? 0), 100);
    onComplete();
  }

  function handleSelectOption(option: LessonCheckpointOption) {
    if (!activeCheckpoint || activeAnswer) {
      return;
    }

    setSelectedOptionId(option.id);
    onAnswer(activeCheckpoint, option);
  }

  async function continueVideo() {
    setActiveCheckpoint(null);
    setSelectedOptionId("");

    try {
      await videoRef.current?.play();
    } catch {
      // Trình duyệt có thể yêu cầu học viên bấm Play lại.
    }
  }

  function resumeVideo() {
    setAttentionNotice(null);
    lastActivityAt.current = Date.now();

    window.setTimeout(() => {
      void videoRef.current?.play().catch(() => {
        // Trình duyệt có thể yêu cầu học viên bấm Play trên thanh điều khiển.
      });
    }, 0);
  }

  async function toggleFullscreen() {
    try {
      if (document.fullscreenElement === stageRef.current) {
        await document.exitFullscreen();
      } else {
        await stageRef.current?.requestFullscreen();
      }
    } catch {
      // Một số trình duyệt chỉ cho phép fullscreen ngay sau thao tác bấm của người dùng.
    }
  }

  const answeredCount = sortedCheckpoints.filter((checkpoint) => answersByCheckpoint.has(checkpoint.id)).length;
  const selectedAnswer = activeCheckpoint
    ? answersByCheckpoint.get(activeCheckpoint.id)
    : undefined;

  return (
    <div>
      <div
        ref={stageRef}
        className="interactive-video-stage relative aspect-video overflow-hidden rounded-[var(--dayai-radius-xl)] bg-slate-900"
      >
        <video
          ref={videoRef}
          src={videoUrl}
          title={title}
          className="h-full w-full bg-black object-contain"
          controls
          controlsList="nodownload nofullscreen noremoteplayback"
          disablePictureInPicture
          playsInline
          preload="metadata"
          onLoadedMetadata={handleLoadedMetadata}
          onTimeUpdate={handleTimeUpdate}
          onSeeking={handleTimeUpdate}
          onPlay={handlePlay}
          onEnded={handleEnded}
        >
          Trình duyệt của bạn chưa hỗ trợ phát video HTML5.
        </video>

        {!activeCheckpoint && !attentionNotice ? (
          <button
            type="button"
            onClick={toggleFullscreen}
            className="interactive-fullscreen-control absolute bottom-6 right-14 z-30 grid h-10 w-10 place-items-center rounded-sm bg-black/80 text-white transition hover:bg-black focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white/80"
            aria-label={isFullscreen ? "Thoát toàn màn hình" : "Mở toàn màn hình tương tác"}
            title={isFullscreen ? "Thoát toàn màn hình" : "Toàn màn hình"}
          >
            {isFullscreen ? <ExitFullscreenIcon /> : <EnterFullscreenIcon />}
          </button>
        ) : null}

        {activeCheckpoint ? (
          <div className="absolute inset-0 z-10 grid place-items-center overflow-y-auto bg-slate-950/92 p-4 backdrop-blur-sm md:p-8">
            <section
              role="dialog"
              aria-modal="true"
              aria-labelledby={`checkpoint-${activeCheckpoint.id}`}
              className="w-full max-w-2xl rounded-[var(--dayai-radius-2xl)] border border-white/15 bg-white p-5 shadow-2xl md:p-7"
            >
              <div className="flex flex-wrap items-center justify-between gap-3">
                <DayaiBadge tone="info">Câu hỏi tương tác</DayaiBadge>
                <DayaiBadge tone={timeRemaining > 0 ? "warning" : "danger"}>
                  {timeRemaining > 0 ? `${timeRemaining}s` : "Đang chờ trả lời"}
                </DayaiBadge>
              </div>
              <h2 id={`checkpoint-${activeCheckpoint.id}`} className="mt-4 text-xl font-black leading-8 text-slate-950 md:text-2xl">
                {activeCheckpoint.question}
              </h2>
              <p className="mt-2 text-sm leading-6 text-slate-600">
                Video đã tự động dừng. Chọn một đáp án để mở khóa phần tiếp theo.
              </p>

              <div className="mt-5 grid gap-3">
                {activeCheckpoint.options.map((option) => {
                  const isSelected = (selectedAnswer?.selected_option_id ?? selectedOptionId) === option.id;
                  const isCorrect = option.id === activeCheckpoint.correct_option_id;
                  const hasAnswered = Boolean(selectedAnswer || selectedOptionId);

                  return (
                    <button
                      key={option.id}
                      type="button"
                      disabled={Boolean(selectedAnswer)}
                      onClick={() => handleSelectOption(option)}
                      className={`rounded-2xl border px-4 py-3 text-left text-sm font-bold leading-6 transition ${
                        hasAnswered && isSelected
                          ? isCorrect
                            ? "border-emerald-400 bg-emerald-50 text-emerald-900"
                            : "border-red-300 bg-red-50 text-red-900"
                          : hasAnswered && isCorrect
                            ? "border-emerald-300 bg-emerald-50/70 text-emerald-900"
                            : "border-slate-200 bg-slate-50 text-slate-700 hover:border-blue-300 hover:bg-blue-50"
                      }`}
                    >
                      {option.label}
                    </button>
                  );
                })}
              </div>

              {selectedAnswer ? (
                <DayaiAlert tone={selectedAnswer.is_correct ? "success" : "warning"} className="mt-5">
                  <strong>{selectedAnswer.is_correct ? "Chính xác. " : "Chưa chính xác. "}</strong>
                  {activeCheckpoint.explanation}
                </DayaiAlert>
              ) : timeRemaining === 0 ? (
                <DayaiAlert tone="danger" className="mt-5">
                  Hết 30 giây nhưng video vẫn được giữ ở trạng thái dừng. Bạn cần chọn đáp án để tiếp tục.
                </DayaiAlert>
              ) : null}

              <DayaiButton
                className="mt-5"
                disabled={!selectedAnswer}
                onClick={continueVideo}
              >
                Tiếp tục video
              </DayaiButton>
            </section>
          </div>
        ) : null}

        {attentionNotice && !activeCheckpoint ? (
          <div className="absolute inset-0 z-10 grid place-items-center bg-slate-950/88 p-5 text-center backdrop-blur-sm">
            <div className="max-w-lg rounded-[var(--dayai-radius-2xl)] border border-white/15 bg-white p-6 shadow-2xl">
              <DayaiBadge tone={attentionNotice === "resume" ? "info" : "warning"}>
                {attentionNotice === "resume" ? "Tiếp tục bài học" : "Video đã tự dừng"}
              </DayaiBadge>
              <h2 className="mt-4 text-2xl font-black text-slate-950">
                {attentionNotice === "hidden"
                  ? "Bạn vừa quay lại bài học"
                  : attentionNotice === "idle"
                    ? "Bạn vẫn đang học chứ?"
                    : `Tiếp tục từ ${formatVideoTime(Math.round(videoRef.current?.currentTime ?? initialPositionSeconds))}`}
              </h2>
              <p className="mt-2 text-sm leading-6 text-slate-600">
                {attentionNotice === "hidden"
                  ? "Video đã dừng khi tab bị ẩn để không tính tiến độ khi bạn không theo dõi."
                  : attentionNotice === "idle"
                    ? "Không phát hiện tương tác trong 2 phút. Bấm tiếp tục khi bạn sẵn sàng."
                    : "Vị trí lần học trước đã được giữ lại cho bạn."}
              </p>
              <DayaiButton className="mt-5" onClick={resumeVideo}>
                Tiếp tục xem
              </DayaiButton>
            </div>
          </div>
        ) : null}
      </div>

      {sortedCheckpoints.length ? (
        <div className="mt-3 rounded-2xl bg-slate-900 px-4 py-3 text-white">
          <div className="flex items-center justify-between gap-4 text-xs font-black uppercase tracking-[0.14em] text-slate-300">
            <span>Checkpoint bắt buộc</span>
            <span>{answeredCount}/{sortedCheckpoints.length} đã trả lời</span>
          </div>
          <div className="mt-3 grid grid-cols-4 gap-2">
            {sortedCheckpoints.map((checkpoint) => {
              const answer = answersByCheckpoint.get(checkpoint.id);

              return (
                <div
                  key={checkpoint.id}
                  className={`rounded-full px-2 py-1 text-center text-xs font-black ${
                    answer
                      ? answer.is_correct
                        ? "bg-emerald-400 text-emerald-950"
                        : "bg-amber-300 text-amber-950"
                      : "bg-white/10 text-slate-300"
                  }`}
                  title={`${formatVideoTime(checkpoint.at_seconds)} · ${checkpoint.question}`}
                >
                  {answer ? "✓" : formatVideoTime(checkpoint.at_seconds)}
                </div>
              );
            })}
          </div>
        </div>
      ) : null}
    </div>
  );
}

function formatVideoTime(seconds: number) {
  const minutes = Math.floor(seconds / 60);
  const remainder = seconds % 60;

  return `${minutes}:${String(remainder).padStart(2, "0")}`;
}

function EnterFullscreenIcon() {
  return (
    <svg viewBox="0 0 24 24" aria-hidden="true" className="h-6 w-6 fill-none stroke-current" strokeWidth="2">
      <path d="M4 9V4h5M15 4h5v5M20 15v5h-5M9 20H4v-5" />
    </svg>
  );
}

function ExitFullscreenIcon() {
  return (
    <svg viewBox="0 0 24 24" aria-hidden="true" className="h-6 w-6 fill-none stroke-current" strokeWidth="2">
      <path d="M9 4v5H4M20 9h-5V4M15 20v-5h5M4 15h5v5" />
    </svg>
  );
}
