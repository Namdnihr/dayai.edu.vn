"use client";

import { useState } from "react";
import { DayaiAlert, DayaiButton, DayaiPanel } from "@/components/ui/dayai-ui";

export type LessonPromptNote = {
  id: string;
  title: string;
  prompt: string;
};

export function LessonNotebook({
  prompts,
  notes,
  isSaving,
  saveStatus,
  onNotesChange,
  onSave,
}: {
  prompts: LessonPromptNote[];
  notes: string;
  isSaving: boolean;
  saveStatus: "idle" | "dirty" | "saving" | "saved" | "error";
  onNotesChange: (notes: string) => void;
  onSave: () => void;
}) {
  const [copiedPromptId, setCopiedPromptId] = useState("");
  const [copyMessage, setCopyMessage] = useState("");

  async function copyPrompt(prompt: LessonPromptNote) {
    try {
      await navigator.clipboard.writeText(prompt.prompt);
      setCopiedPromptId(prompt.id);
      setCopyMessage(`Đã sao chép “${prompt.title}”. Bạn có thể dán ngay vào công cụ AI để thực hành.`);
      window.setTimeout(() => setCopiedPromptId(""), 2200);
    } catch {
      setCopyMessage("Trình duyệt chưa cho phép sao chép tự động. Bạn có thể bôi đen câu lệnh để sao chép thủ công.");
    }
  }

  return (
    <DayaiPanel
      eyebrow="Thực hành ngay"
      title="Sổ tay AI trong bài học"
      className="border-violet-100 bg-gradient-to-br from-violet-50 via-white to-blue-50"
    >
      <p className="text-sm leading-6 text-slate-600">
        Sao chép câu lệnh mẫu, mở công cụ AI ở tab khác và ghi lại kết quả hoặc điều bạn muốn hỏi giảng viên.
      </p>

      {prompts.length ? (
        <div className="mt-5 grid gap-3">
          {prompts.map((prompt, index) => (
            <article key={prompt.id} className="rounded-2xl border border-violet-100 bg-white p-4 shadow-sm">
              <div className="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                  <div className="text-xs font-black uppercase tracking-[0.16em] text-violet-600">
                    Câu lệnh {index + 1}
                  </div>
                  <h4 className="mt-1 font-black text-slate-950">{prompt.title}</h4>
                </div>
                <DayaiButton
                  size="sm"
                  variant={copiedPromptId === prompt.id ? "primary" : "secondary"}
                  onClick={() => copyPrompt(prompt)}
                >
                  {copiedPromptId === prompt.id ? "Đã sao chép" : "Sao chép câu lệnh"}
                </DayaiButton>
              </div>
              <pre className="mt-3 whitespace-pre-wrap rounded-xl bg-slate-950 p-4 font-sans text-sm leading-6 text-slate-100">
                {prompt.prompt}
              </pre>
            </article>
          ))}
        </div>
      ) : null}

      {copyMessage ? <DayaiAlert tone="success" className="mt-4">{copyMessage}</DayaiAlert> : null}

      <div className="mt-6">
        <label htmlFor="lesson-personal-notes" className="dayai-label">
          Ghi chú cá nhân
        </label>
        <textarea
          id="lesson-personal-notes"
          value={notes}
          onChange={(event) => onNotesChange(event.target.value)}
          placeholder="Ví dụ: câu hỏi cần hỏi mentor, kết quả thực hành prompt, ý tưởng áp dụng vào công việc..."
          className="form-control mt-2 min-h-36 resize-y"
        />
        <div className="mt-3 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
          <p className="text-xs leading-5 text-slate-500">
            {saveStatus === "saving"
              ? "Đang tự động lưu..."
              : saveStatus === "saved"
                ? "Đã tự động lưu ghi chú."
                : saveStatus === "error"
                  ? "Tự động lưu chưa thành công. Hãy bấm lưu lại."
                  : saveStatus === "dirty"
                    ? "Thay đổi sẽ tự lưu sau một giây."
                    : "Ghi chú được lưu riêng theo tài khoản học viên và bài học."}
          </p>
          <DayaiButton variant="dark" size="sm" disabled={isSaving} onClick={onSave}>
            {isSaving ? "Đang lưu..." : "Lưu ghi chú"}
          </DayaiButton>
        </div>
      </div>
    </DayaiPanel>
  );
}
