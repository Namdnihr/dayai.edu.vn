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
    <form id="lead-form" onSubmit={handleSubmit} className="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-2xl shadow-blue-950/10 md:p-8">
      <div className="grid gap-5">
        <Field label="Họ tên" name="full_name" placeholder="Nguyễn Minh Anh" required />
        <Field label="Số điện thoại" name="phone" placeholder="0901 000 001" required />
        <Field label="Email" name="email" placeholder="ban@example.com" type="email" />

        <label className="grid gap-2">
          <span className="text-sm font-bold text-slate-700">Bạn thuộc nhóm nào?</span>
          <select name="lead_type" required className="rounded-2xl border border-slate-200 bg-white px-4 py-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
            <option value="parent">Phụ huynh mua cho con</option>
            <option value="student">Sinh viên tự đăng ký</option>
            <option value="business_owner">Chủ doanh nghiệp đi học</option>
            <option value="company">Công ty mua cho nhân sự</option>
          </select>
        </label>

        <Field label="Tên công ty nếu có" name="company_name" placeholder="DAYAI Co., Ltd" />

        <label className="grid gap-2">
          <span className="text-sm font-bold text-slate-700">Khóa quan tâm</span>
          <select name="interested_course_id" className="rounded-2xl border border-slate-200 bg-white px-4 py-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
            <option value="AI-FUNDAMENTALS">AI Căn Bản</option>
            <option value="PROMPT-ENGINEERING">Prompt Engineering</option>
            <option value="AI-BUSINESS">AI Cho Doanh Nghiệp</option>
          </select>
        </label>

        <label className="grid gap-2">
          <span className="text-sm font-bold text-slate-700">Nhu cầu học</span>
          <textarea name="learning_goal" rows={4} placeholder="Tôi muốn học AI để..." className="resize-none rounded-2xl border border-slate-200 px-4 py-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100" />
        </label>

        <label className="grid gap-2">
          <span className="text-sm font-bold text-slate-700">Hình thức đăng ký</span>
          <select name="request_type" className="rounded-2xl border border-slate-200 bg-white px-4 py-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
            <option value="consultation">Đăng ký tư vấn</option>
            <option value="trial">Đăng ký học thử</option>
          </select>
        </label>

        <input type="hidden" name="preferred_contact_method" value="phone" />
        <input type="hidden" name="utm_source" value={source} />
        <input type="hidden" name="utm_medium" value={medium} />
        <input type="hidden" name="utm_campaign" value={campaign} />

        <button
          type="submit"
          disabled={submitState === "submitting"}
          className="rounded-full bg-blue-600 px-7 py-4 font-black text-white shadow-xl shadow-blue-600/20 transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-60"
        >
          {submitState === "submitting" ? "Đang gửi..." : "Gửi đăng ký"}
        </button>

        {message ? (
          <p className={submitState === "error" ? "text-sm font-semibold text-red-600" : "text-sm font-semibold text-emerald-600"}>
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
      <span className="text-sm font-bold text-slate-700">{label}</span>
      <input
        name={name}
        required={required}
        type={type}
        placeholder={placeholder}
        className="rounded-2xl border border-slate-200 px-4 py-3 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
      />
    </label>
  );
}
