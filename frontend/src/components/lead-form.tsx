"use client";

import { FormEvent, useState } from "react";

type SubmitState = "idle" | "submitting" | "success" | "error";

export function LeadForm({
  campaign = "dayai_portal",
  source = "website",
  medium = "website_form",
}: {
  campaign?: string;
  source?: string;
  medium?: string;
}) {
  const [submitState, setSubmitState] = useState<SubmitState>("idle");
  const [message, setMessage] = useState("");

  async function handleSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setSubmitState("submitting");
    setMessage("");

    const form = event.currentTarget;
    const formData = new FormData(form);
    const payload = Object.fromEntries(formData.entries());

    try {
      const response = await fetch("/api/leads", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(payload),
      });

      const result = await response.json();

      if (!response.ok) {
        throw new Error(result.message ?? "Không thể gửi thông tin.");
      }

      form.reset();
      setSubmitState("success");
      setMessage("Đã gửi thông tin. DAYAI sẽ liên hệ tư vấn sớm.");
    } catch (error) {
      setSubmitState("error");
      setMessage(error instanceof Error ? error.message : "Có lỗi xảy ra, vui lòng thử lại.");
    }
  }

  return (
    <form
      id="lead-form"
      onSubmit={handleSubmit}
      className="overflow-hidden rounded-[var(--dayai-radius-2xl)] border border-white/20 bg-white text-[var(--dayai-text)] shadow-[var(--dayai-shadow-md)]"
    >
      <div className="border-b border-[var(--dayai-border)] bg-[var(--dayai-bg-subtle)] p-6 md:p-8">
        <div className="flex flex-col gap-5 md:flex-row md:items-start md:justify-between">
          <div>
            <div className="dayai-kicker">Form tư vấn</div>
            <h3 className="mt-3 text-2xl font-black leading-tight">Nhận lộ trình học AI phù hợp</h3>
            <p className="mt-3 max-w-xl text-sm leading-6 text-[var(--dayai-text-muted)]">
              Điền vài thông tin chính, đội ngũ DAYAI sẽ gợi ý khóa học, lịch học và hình thức học phù hợp.
            </p>
          </div>
          <div className="grid gap-2 text-sm font-bold text-[var(--dayai-text-muted)]">
            <span className="rounded-[var(--dayai-radius-full)] border border-[var(--dayai-border)] bg-white px-3 py-2">
              Phản hồi trong 24 giờ
            </span>
            <span className="rounded-[var(--dayai-radius-full)] border border-[var(--dayai-border)] bg-white px-3 py-2">
              Không chia sẻ dữ liệu
            </span>
          </div>
        </div>
      </div>

      <div className="grid gap-6 p-6 md:p-8">
        <div className="grid gap-5 md:grid-cols-2">
          <Field label="Họ tên" name="full_name" placeholder="Nguyễn Minh Anh" required />
          <Field label="Số điện thoại" name="phone" placeholder="0901 000 001" required />
          <Field label="Email" name="email" placeholder="ban@example.com" type="email" />
          <Field label="Tên công ty nếu có" name="company_name" placeholder="DAYAI Co., Ltd" />
        </div>

        <div className="grid gap-5 md:grid-cols-2">
          <SelectField
            label="Bạn thuộc nhóm nào?"
            name="lead_type"
            required
            options={[
              ["parent", "Phụ huynh mua cho con"],
              ["student", "Học sinh, sinh viên tự đăng ký"],
              ["business_owner", "Chủ doanh nghiệp đi học"],
              ["company", "Công ty mua cho nhân sự"],
            ]}
          />
          <SelectField
            label="Khóa quan tâm"
            name="interested_course_id"
            options={[
              ["AI-FUNDAMENTALS", "AI Căn Bản"],
              ["PROMPT-ENGINEERING", "Prompt Engineering"],
              ["AI-BUSINESS", "AI Cho Doanh Nghiệp"],
            ]}
          />
        </div>

        <label className="grid gap-2">
          <span className="text-sm font-black text-[var(--dayai-text)]">Nhu cầu học</span>
          <textarea
            name="learning_goal"
            rows={4}
            placeholder="Tôi muốn học AI để..."
            className="form-control resize-none"
          />
        </label>

        <SelectField
          label="Hình thức đăng ký"
          name="request_type"
          options={[
            ["consultation", "Đăng ký tư vấn"],
            ["trial", "Đăng ký học thử"],
          ]}
        />

        <input type="hidden" name="preferred_contact_method" value="phone" />
        <input type="hidden" name="utm_source" value={source} />
        <input type="hidden" name="utm_medium" value={medium} />
        <input type="hidden" name="utm_campaign" value={campaign} />

        <div className="rounded-[var(--dayai-radius-xl)] border border-[var(--dayai-border)] bg-[var(--dayai-bg-subtle)] p-4">
          <button
            type="submit"
            disabled={submitState === "submitting"}
            className="dayai-btn dayai-btn-primary w-full disabled:cursor-not-allowed disabled:opacity-60"
          >
            {submitState === "submitting" ? "Đang gửi..." : "Gửi đăng ký tư vấn"}
          </button>
          <p className="mt-3 text-center text-xs font-semibold leading-5 text-[var(--dayai-text-subtle)]">
            DAYAI chỉ dùng thông tin này để tư vấn lộ trình học và lịch học phù hợp.
          </p>
        </div>

        {message ? (
          <p
            className={
              submitState === "error"
                ? "rounded-[var(--dayai-radius-lg)] bg-red-50 p-4 text-sm font-semibold text-[var(--dayai-danger)]"
                : "rounded-[var(--dayai-radius-lg)] bg-green-50 p-4 text-sm font-semibold text-[var(--dayai-success)]"
            }
          >
            {message}
          </p>
        ) : null}
      </div>
    </form>
  );
}

function Field({
  label,
  name,
  placeholder,
  required = false,
  type = "text",
}: {
  label: string;
  name: string;
  placeholder: string;
  required?: boolean;
  type?: string;
}) {
  return (
    <label className="grid gap-2">
      <span className="text-sm font-black text-[var(--dayai-text)]">{label}</span>
      <input name={name} required={required} type={type} placeholder={placeholder} className="form-control" />
    </label>
  );
}

function SelectField({
  label,
  name,
  options,
  required = false,
}: {
  label: string;
  name: string;
  options: Array<[string, string]>;
  required?: boolean;
}) {
  return (
    <label className="grid gap-2">
      <span className="text-sm font-black text-[var(--dayai-text)]">{label}</span>
      <select name={name} required={required} className="form-control">
        {options.map(([value, label]) => (
          <option key={value} value={value}>
            {label}
          </option>
        ))}
      </select>
    </label>
  );
}
