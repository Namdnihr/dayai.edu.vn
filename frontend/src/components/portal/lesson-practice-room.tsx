"use client";

import { useMemo, useState } from "react";
import { DayaiAlert, DayaiBadge, DayaiButton, DayaiPanel, dayaiButtonClasses } from "@/components/ui/dayai-ui";
import type { LessonPromptNote } from "@/components/portal/lesson-notebook";

export type LessonPracticeSession = {
  prompt_id: string;
  result: string;
  reflection: string;
  confidence: number | null;
  updated_at: string;
};

export function LessonPracticeRoom({
  prompts,
  sessions,
  isSaving,
  onSave,
}: {
  prompts: LessonPromptNote[];
  sessions: LessonPracticeSession[];
  isSaving: boolean;
  onSave: (session: LessonPracticeSession) => void;
}) {
  const [activePromptId, setActivePromptId] = useState(prompts[0]?.id ?? "");
  const savedSession = useMemo(
    () => sessions.find((session) => session.prompt_id === activePromptId),
    [activePromptId, sessions],
  );
  const [drafts, setDrafts] = useState<Record<string, Omit<LessonPracticeSession, "prompt_id" | "updated_at">>>({});
  const activePrompt = prompts.find((prompt) => prompt.id === activePromptId);
  const draft = drafts[activePromptId] ?? {
    result: savedSession?.result ?? "",
    reflection: savedSession?.reflection ?? "",
    confidence: savedSession?.confidence ?? null,
  };

  function updateDraft(next: Partial<typeof draft>) {
    setDrafts((current) => ({
      ...current,
      [activePromptId]: {
        ...draft,
        ...next,
      },
    }));
  }

  if (!prompts.length) {
    return null;
  }

  return (
    <DayaiPanel
      eyebrow="Học bằng hành động"
      title="Phòng thực hành AI"
      className="border-amber-100 bg-gradient-to-br from-amber-50 via-white to-orange-50"
    >
      <div className="flex flex-wrap gap-2">
        {prompts.map((prompt, index) => (
          <button
            key={prompt.id}
            type="button"
            onClick={() => setActivePromptId(prompt.id)}
            className={`rounded-full px-4 py-2 text-sm font-black transition ${
              prompt.id === activePromptId
                ? "bg-slate-950 text-white"
                : "border border-slate-200 bg-white text-slate-700 hover:border-amber-300"
            }`}
          >
            Bài tập {index + 1}
          </button>
        ))}
      </div>

      {activePrompt ? (
        <div className="mt-5">
          <div className="flex flex-wrap items-center justify-between gap-3">
            <h3 className="font-black text-slate-950">{activePrompt.title}</h3>
            {savedSession ? <DayaiBadge tone="success">Đã lưu bài làm</DayaiBadge> : <DayaiBadge tone="warning">Chưa nộp</DayaiBadge>}
          </div>
          <pre className="mt-3 whitespace-pre-wrap rounded-2xl bg-slate-950 p-4 font-sans text-sm leading-6 text-slate-100">
            {activePrompt.prompt}
          </pre>
          <div className="mt-3 flex flex-wrap gap-2">
            <DayaiButton
              size="sm"
              variant="secondary"
              onClick={() => navigator.clipboard.writeText(activePrompt.prompt)}
            >
              Sao chép prompt
            </DayaiButton>
            <a
              href="https://chatgpt.com/"
              target="_blank"
              rel="noreferrer"
              className={dayaiButtonClasses({ size: "sm" })}
            >
              Mở công cụ AI ↗
            </a>
          </div>

          <DayaiAlert tone="info" className="mt-4">
            DAYAI chưa gửi dữ liệu sang mô hình AI trong trang này. Hãy chạy prompt ở công cụ bạn chọn, rồi dán kết quả vào dưới đây để lưu cùng bài học.
          </DayaiAlert>

          <div className="mt-5 grid gap-4">
            <label className="dayai-label">
              Kết quả AI trả về
              <textarea
                value={draft.result}
                onChange={(event) => updateDraft({ result: event.target.value })}
                placeholder="Dán câu trả lời của công cụ AI tại đây..."
                className="form-control mt-2 min-h-32 resize-y font-normal"
              />
            </label>
            <label className="dayai-label">
              Điều tôi đã kiểm chứng hoặc học được
              <textarea
                value={draft.reflection}
                onChange={(event) => updateDraft({ reflection: event.target.value })}
                placeholder="Ví dụ: thông tin nào đúng, điểm nào cần hỏi giảng viên, tôi sẽ áp dụng ra sao..."
                className="form-control mt-2 min-h-24 resize-y font-normal"
              />
            </label>
            <fieldset>
              <legend className="dayai-label">Mức tự tin sau khi thực hành</legend>
              <div className="mt-2 flex flex-wrap gap-2">
                {[1, 2, 3, 4, 5].map((score) => (
                  <button
                    key={score}
                    type="button"
                    onClick={() => updateDraft({ confidence: score })}
                    className={`h-10 w-10 rounded-full text-sm font-black transition ${
                      draft.confidence === score
                        ? "bg-amber-400 text-amber-950 ring-4 ring-amber-100"
                        : "border border-slate-200 bg-white text-slate-600 hover:border-amber-300"
                    }`}
                    aria-label={`Mức tự tin ${score} trên 5`}
                  >
                    {score}
                  </button>
                ))}
              </div>
            </fieldset>
          </div>

          <DayaiButton
            className="mt-5"
            variant="dark"
            disabled={isSaving || (!draft.result.trim() && !draft.reflection.trim())}
            onClick={() => onSave({
              prompt_id: activePrompt.id,
              result: draft.result.trim(),
              reflection: draft.reflection.trim(),
              confidence: draft.confidence,
              updated_at: new Date().toISOString(),
            })}
          >
            {isSaving ? "Đang lưu..." : savedSession ? "Cập nhật bài thực hành" : "Lưu bài thực hành"}
          </DayaiButton>
        </div>
      ) : null}
    </DayaiPanel>
  );
}
