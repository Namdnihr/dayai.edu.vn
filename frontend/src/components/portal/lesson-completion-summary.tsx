"use client";

import Link from "next/link";
import { DayaiAlert, DayaiBadge, DayaiButton, DayaiPanel, dayaiButtonClasses } from "@/components/ui/dayai-ui";
import type { LessonCheckpoint, LessonCheckpointAnswer } from "@/components/portal/interactive-video-player";

export function LessonCompletionSummary({
  checkpoints,
  answers,
  notes,
  practiceCount,
  progressPercent,
  nextLesson,
  onRetry,
}: {
  checkpoints: LessonCheckpoint[];
  answers: LessonCheckpointAnswer[];
  notes: string;
  practiceCount: number;
  progressPercent: number;
  nextLesson: { title: string; slug: string } | null;
  onRetry: (checkpoint: LessonCheckpoint) => void;
}) {
  if (!checkpoints.length || answers.length < checkpoints.length) {
    return null;
  }

  const wrongAnswers = checkpoints.filter((checkpoint) => {
    const answer = answers.find((candidate) => candidate.checkpoint_id === checkpoint.id);
    return answer && !answer.is_correct;
  });
  const correctCount = checkpoints.length - wrongAnswers.length;

  return (
    <DayaiPanel
      eyebrow="Chốt kiến thức"
      title={progressPercent >= 95 ? "Bạn đã hoàn thành bài học" : "Bạn đã mở khóa phần cuối"}
      className="border-emerald-100 bg-gradient-to-br from-emerald-50 via-white to-cyan-50"
    >
      <div className="grid gap-3 sm:grid-cols-3">
        <SummaryStat label="Câu đúng" value={`${correctCount}/${checkpoints.length}`} />
        <SummaryStat label="Ghi chú" value={notes.trim() ? `${notes.trim().split(/\s+/).length} từ` : "Chưa có"} />
        <SummaryStat label="Bài thực hành" value={practiceCount ? `${practiceCount} đã lưu` : "Chưa có"} />
      </div>

      {wrongAnswers.length ? (
        <DayaiAlert tone="warning" className="mt-5">
          <strong>Còn {wrongAnswers.length} ý nên xem lại.</strong>
          <div className="mt-3 flex flex-wrap gap-2">
            {wrongAnswers.map((checkpoint) => (
              <DayaiButton
                key={checkpoint.id}
                size="sm"
                variant="secondary"
                onClick={() => onRetry(checkpoint)}
              >
                Ôn lại mốc {formatVideoTime(checkpoint.at_seconds)}
              </DayaiButton>
            ))}
          </div>
        </DayaiAlert>
      ) : (
        <DayaiAlert tone="success" className="mt-5">
          Bạn đã trả lời đúng toàn bộ checkpoint. Hãy lưu ít nhất một ý ứng dụng để biến kiến thức thành hành động.
        </DayaiAlert>
      )}

      <div className="mt-5 flex flex-wrap items-center gap-3">
        <DayaiBadge tone={progressPercent >= 95 ? "success" : "info"}>
          Tiến độ {progressPercent}%
        </DayaiBadge>
        {nextLesson ? (
          <Link
            href={`/portal/bai-hoc/${nextLesson.slug}`}
            className={dayaiButtonClasses({ size: "sm" })}
          >
            Sang bài tiếp theo
          </Link>
        ) : null}
      </div>
    </DayaiPanel>
  );
}

function SummaryStat({ label, value }: { label: string; value: string }) {
  return (
    <div className="rounded-2xl border border-emerald-100 bg-white px-4 py-3">
      <div className="text-xs font-black uppercase tracking-[0.12em] text-slate-500">{label}</div>
      <div className="mt-1 text-xl font-black text-slate-950">{value}</div>
    </div>
  );
}

function formatVideoTime(seconds: number) {
  const minutes = Math.floor(seconds / 60);
  const remainder = seconds % 60;

  return `${minutes}:${String(remainder).padStart(2, "0")}`;
}
