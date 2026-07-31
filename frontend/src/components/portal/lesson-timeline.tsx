"use client";

import { useMemo, useState } from "react";
import { DayaiBadge, DayaiEmptyState, DayaiPanel } from "@/components/ui/dayai-ui";

export type LessonTimelineNote = {
  at_seconds: number;
  title: string;
  text: string;
};

export function LessonTimeline({
  notes,
  activeSeconds,
  onSeek,
}: {
  notes: LessonTimelineNote[];
  activeSeconds: number;
  onSeek: (seconds: number) => void;
}) {
  const [query, setQuery] = useState("");
  const filteredNotes = useMemo(() => {
    const normalizedQuery = query.trim().toLocaleLowerCase("vi");

    if (!normalizedQuery) {
      return notes;
    }

    return notes.filter((note) =>
      `${note.title} ${note.text}`.toLocaleLowerCase("vi").includes(normalizedQuery),
    );
  }, [notes, query]);
  const activeNote = [...notes]
    .sort((left, right) => left.at_seconds - right.at_seconds)
    .filter((note) => note.at_seconds <= activeSeconds + 1)
    .at(-1);

  return (
    <DayaiPanel
      eyebrow="Theo dõi dễ hơn"
      title="Nội dung chính theo mốc"
      className="border-cyan-100 bg-gradient-to-br from-cyan-50 via-white to-blue-50"
    >
      <div className="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <p className="max-w-2xl text-sm leading-6 text-slate-600">
          Đây là bản tóm tắt theo thời gian, không phải phụ đề nguyên văn. Bấm một mốc để quay lại đúng đoạn cần ôn.
        </p>
        <input
          type="search"
          value={query}
          onChange={(event) => setQuery(event.target.value)}
          placeholder="Tìm ý trong bài..."
          aria-label="Tìm trong nội dung theo mốc"
          className="form-control w-full sm:w-64"
        />
      </div>

      {filteredNotes.length ? (
        <div className="mt-5 grid gap-2">
          {filteredNotes.map((note) => {
            const isActive = activeNote?.at_seconds === note.at_seconds;

            return (
              <button
                key={`${note.at_seconds}-${note.title}`}
                type="button"
                onClick={() => onSeek(note.at_seconds)}
                className={`grid gap-3 rounded-2xl border p-3 text-left transition sm:grid-cols-[72px_1fr] ${
                  isActive
                    ? "border-cyan-300 bg-cyan-50 shadow-sm"
                    : "border-slate-200 bg-white hover:border-blue-300 hover:bg-blue-50"
                }`}
              >
                <DayaiBadge tone={isActive ? "info" : "neutral"}>
                  {formatVideoTime(note.at_seconds)}
                </DayaiBadge>
                <span>
                  <strong className="block text-sm text-slate-950">{note.title}</strong>
                  <span className="mt-1 block text-sm leading-6 text-slate-600">{note.text}</span>
                </span>
              </button>
            );
          })}
        </div>
      ) : (
        <DayaiEmptyState
          compact
          className="mt-5"
          title="Không tìm thấy nội dung phù hợp."
          description="Thử một từ khóa ngắn hơn hoặc xóa ô tìm kiếm."
        />
      )}
    </DayaiPanel>
  );
}

function formatVideoTime(seconds: number) {
  const minutes = Math.floor(seconds / 60);
  const remainder = seconds % 60;

  return `${minutes}:${String(remainder).padStart(2, "0")}`;
}
