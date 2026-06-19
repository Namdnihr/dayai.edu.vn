"use client";

import { FormEvent, useState } from "react";

type CompanyPortalData = {
  organization: {
    name: string;
    short_name: string | null;
    tax_code: string | null;
    industry: string | null;
    company_size: string | null;
    status: string | null;
  };
  summary: {
    student_count: number;
    active_enrollment_count: number;
    average_progress_percent: number;
    total_vnd: number;
    paid_vnd: number;
    balance_vnd: number;
  };
  students: Array<{
    student_code: string;
    full_name: string | null;
    job_title: string | null;
    status: string | null;
    enrollments: Array<{
      enrollment_code: string;
      status: string;
      course: string | null;
      class_group: string | null;
    }>;
    attendance: {
      present: number;
      absent: number;
      late: number;
    };
    latest_assessment: {
      assessment: string | null;
      score: number | null;
      max_score: number;
      level: string | null;
    } | null;
    latest_progress: {
      title: string;
      overall_level: string | null;
      progress_percent: number;
      published_at: string | null;
    } | null;
    latest_certificate: {
      certificate_code: string;
      title: string;
      course: string | null;
      grade: string | null;
      issued_at: string | null;
    } | null;
  }>;
  orders: Array<{
    order_code: string;
    status: string;
    total_vnd: number;
    paid_vnd: number;
    balance_vnd: number;
  }>;
  notifications: Array<{
    title: string;
    body: string;
    notification_type: string;
    priority: string;
    published_at: string | null;
  }>;
};

export function CompanyPortalLookup() {
  const [data, setData] = useState<CompanyPortalData | null>(null);
  const [message, setMessage] = useState("");
  const [isSubmitting, setIsSubmitting] = useState(false);

  async function handleSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setIsSubmitting(true);
    setMessage("");
    setData(null);

    const formData = new FormData(event.currentTarget);

    try {
      const response = await fetch("/api/company-portal/lookup", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(Object.fromEntries(formData.entries())),
      });

      const result = await response.json();

      if (!response.ok) {
        throw new Error(result.message ?? "Không tra cứu được thông tin doanh nghiệp.");
      }

      setData(result as CompanyPortalData);
    } catch (error) {
      setMessage(error instanceof Error ? error.message : "Có lỗi xảy ra.");
    } finally {
      setIsSubmitting(false);
    }
  }

  return (
    <div className="space-y-6">
      <form onSubmit={handleSubmit} className="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-xl shadow-blue-950/5">
        <div className="grid gap-4 sm:grid-cols-2">
          <label className="grid gap-2">
            <span className="text-sm font-bold text-slate-700">Email HR</span>
            <input name="email" type="email" required defaultValue="hr@examplecorp.test" className="rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100" />
          </label>
          <label className="grid gap-2">
            <span className="text-sm font-bold text-slate-700">Mã công ty / MST</span>
            <input name="company_code" required defaultValue="EXAMPLE-CORP" className="rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100" />
          </label>
        </div>
        <button disabled={isSubmitting} className="mt-5 rounded-full bg-blue-600 px-7 py-3 font-black text-white shadow-lg shadow-blue-600/20 hover:bg-blue-700 disabled:opacity-60">
          {isSubmitting ? "Đang tra cứu..." : "Tra cứu doanh nghiệp"}
        </button>
        {message ? <p className="mt-4 text-sm font-semibold text-red-600">{message}</p> : null}
      </form>

      {data ? <CompanyPortalResult data={data} /> : null}
    </div>
  );
}

function CompanyPortalResult({ data }: { data: CompanyPortalData }) {
  return (
    <div className="space-y-5">
      <section className="rounded-[2rem] bg-slate-950 p-6 text-white">
        <div className="text-sm text-blue-200">Doanh nghiệp</div>
        <h2 className="mt-2 text-3xl font-black">{data.organization.name}</h2>
        <p className="mt-2 text-slate-300">Mã: {data.organization.tax_code ?? data.organization.short_name} · Quy mô: {data.organization.company_size ?? "Chưa rõ"}</p>
      </section>

      <div className="grid gap-5 md:grid-cols-3">
        <Metric title="Nhân sự học" value={String(data.summary.student_count)} />
        <Metric title="Đang học" value={String(data.summary.active_enrollment_count)} />
        <Metric title="TB tiến độ" value={`${data.summary.average_progress_percent}%`} />
      </div>

      <div className="grid gap-5 md:grid-cols-3">
        <Metric title="Tổng học phí" value={formatVnd(data.summary.total_vnd)} />
        <Metric title="Đã thanh toán" value={formatVnd(data.summary.paid_vnd)} />
        <Metric title="Công nợ" value={formatVnd(data.summary.balance_vnd)} />
      </div>

      <section className="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
        <h3 className="text-xl font-black">Danh sách nhân sự</h3>
        <div className="mt-4 space-y-4">
          {data.students.map((student) => (
            <div key={student.student_code} className="rounded-3xl border border-slate-100 p-5">
              <div className="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                <div>
                  <div className="text-lg font-black">{student.full_name}</div>
                  <div className="mt-1 text-sm text-slate-500">{student.student_code} · {student.job_title ?? "Nhân sự"}</div>
                  <div className="mt-3 text-sm leading-6 text-slate-600">
                    {student.enrollments.map((enrollment) => enrollment.course).filter(Boolean).join(", ") || "Chưa có khóa học"}
                  </div>
                </div>
                <div className="rounded-2xl bg-blue-50 px-4 py-3 text-center">
                  <div className="text-2xl font-black text-blue-700">{student.latest_progress?.progress_percent ?? 0}%</div>
                  <div className="text-xs font-bold text-blue-700">{formatLevel(student.latest_progress?.overall_level ?? null)}</div>
                </div>
              </div>
              <div className="mt-4 h-2 overflow-hidden rounded-full bg-slate-100">
                <div className="h-full rounded-full bg-blue-600" style={{ width: `${Math.min(student.latest_progress?.progress_percent ?? 0, 100)}%` }} />
              </div>
              <div className="mt-4 grid gap-3 text-sm md:grid-cols-3">
                <Info label="Điểm danh" value={`Có mặt ${student.attendance.present} · Muộn ${student.attendance.late} · Vắng ${student.attendance.absent}`} />
                <Info label="Đánh giá" value={student.latest_assessment ? `${student.latest_assessment.score}/${student.latest_assessment.max_score}` : "Chưa có"} />
                <Info label="Chứng chỉ" value={student.latest_certificate ? `${student.latest_certificate.grade ?? "Đạt"} · ${student.latest_certificate.certificate_code}` : "Chưa cấp"} />
              </div>
            </div>
          ))}
        </div>
      </section>

      <section className="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
        <h3 className="text-xl font-black">Đơn B2B</h3>
        <div className="mt-4 divide-y divide-slate-100">
          {data.orders.map((order) => (
            <div key={order.order_code} className="flex items-start justify-between gap-4 py-4">
              <div>
                <div className="font-bold">{order.order_code}</div>
                <div className="mt-1 text-sm text-slate-600">Tổng {formatVnd(order.total_vnd)} · Đã thu {formatVnd(order.paid_vnd)}</div>
              </div>
              <div className="rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">{formatVnd(order.balance_vnd)}</div>
            </div>
          ))}
        </div>
      </section>

      <section className="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
        <h3 className="text-xl font-black">Thông báo cho HR</h3>
        <div className="mt-4 divide-y divide-slate-100">
          {data.notifications.map((notification) => (
            <div key={notification.title} className="flex items-start justify-between gap-4 py-4">
              <div>
                <div className="font-bold">{notification.title}</div>
                <div className="mt-1 text-sm leading-6 text-slate-600">{notification.body}</div>
              </div>
              <div className="shrink-0 rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">
                {formatNotificationType(notification.notification_type)} · {formatPriority(notification.priority)}
              </div>
            </div>
          ))}
        </div>
      </section>
    </div>
  );
}

function Metric({ title, value }: { title: string; value: string }) {
  return (
    <div className="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
      <div className="text-sm text-slate-500">{title}</div>
      <div className="mt-2 text-2xl font-black text-slate-950">{value}</div>
    </div>
  );
}

function Info({ label, value }: { label: string; value: string }) {
  return (
    <div className="rounded-2xl bg-slate-50 p-3">
      <div className="text-xs font-bold uppercase tracking-wide text-slate-400">{label}</div>
      <div className="mt-1 font-bold text-slate-800">{value}</div>
    </div>
  );
}

function formatVnd(amount: number) {
  return new Intl.NumberFormat("vi-VN", {
    style: "currency",
    currency: "VND",
    maximumFractionDigits: 0,
  }).format(amount);
}

function formatLevel(level: string | null) {
  return {
    needs_support: "Cần hỗ trợ",
    on_track: "Đúng tiến độ",
    excellent: "Nổi bật",
  }[level ?? ""] ?? level ?? "Chưa rõ";
}

function formatNotificationType(type: string) {
  return {
    general: "Chung",
    schedule: "Lịch học",
    finance: "Học phí",
    progress: "Tiến độ",
    content: "Nội dung",
  }[type] ?? type;
}

function formatPriority(priority: string) {
  return {
    low: "Thấp",
    normal: "Thường",
    high: "Quan trọng",
    urgent: "Khẩn",
  }[priority] ?? priority;
}
