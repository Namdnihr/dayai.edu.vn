"use client";

import { FormEvent, useState } from "react";
import type { ReactNode } from "react";

type PortalData = {
  student: {
    student_code: string;
    full_name: string | null;
    student_type: string | null;
    learning_goal: string | null;
    status: string | null;
  };
  enrollments: Array<{
    enrollment_code: string;
    status: string;
    course: string | null;
    class_group: string | null;
    schedule_note: string | null;
    started_at: string | null;
    order_code: string | null;
  }>;
  upcoming_sessions: Array<{
    title: string | null;
    class_group: string | null;
    starts_at: string | null;
    ends_at: string | null;
    status: string | null;
    location: string | null;
  }>;
  attendance: Array<{
    session: string | null;
    class_group: string | null;
    status: string;
    checked_in_at: string | null;
    teacher_note: string | null;
  }>;
  assessment_results: Array<{
    assessment: string | null;
    assessment_type: string | null;
    score: number | null;
    max_score: number;
    level: string | null;
    feedback: string | null;
    strengths: string | null;
    improvements: string | null;
    teacher: string | null;
    assessed_at: string | null;
  }>;
  teacher_comments: Array<{
    title: string | null;
    comment_type: string;
    comment: string;
    rating: number | null;
    session: string | null;
    teacher: string | null;
    commented_at: string | null;
  }>;
  progress_reports: Array<{
    title: string;
    report_period: string;
    overall_level: string | null;
    progress_percent: number;
    strengths: string | null;
    improvements: string | null;
    recommendation: string | null;
    teacher: string | null;
    published_at: string | null;
  }>;
  finance: {
    customer: string | null;
    total_vnd: number;
    paid_vnd: number;
    balance_vnd: number;
    orders: Array<{
      order_code: string;
      status: string;
      total_vnd: number;
      paid_vnd: number;
      balance_vnd: number;
    }>;
  };
  videos: Array<{
    title: string;
    summary: string | null;
    duration_minutes: number | null;
    access_level: string;
  }>;
  notifications: Array<{
    title: string;
    body: string;
    notification_type: string;
    priority: string;
    published_at: string | null;
  }>;
  certificates: Array<{
    certificate_code: string;
    title: string;
    course: string | null;
    class_group: string | null;
    final_score: number | null;
    grade: string | null;
    issued_at: string | null;
    verification_token: string;
    file_path: string | null;
  }>;
};

export function PortalLookup() {
  const [data, setData] = useState<PortalData | null>(null);
  const [message, setMessage] = useState("");
  const [isSubmitting, setIsSubmitting] = useState(false);

  async function handleSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setIsSubmitting(true);
    setMessage("");
    setData(null);

    const formData = new FormData(event.currentTarget);

    try {
      const response = await fetch("/api/portal/lookup", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(Object.fromEntries(formData.entries())),
      });

      const result = await response.json();

      if (!response.ok) {
        throw new Error(result.message ?? "Không tra cứu được thông tin.");
      }

      setData(result as PortalData);
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
            <span className="text-sm font-bold text-slate-700">Số điện thoại</span>
            <input name="phone" required defaultValue="0901888000" className="rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100" />
          </label>
          <label className="grid gap-2">
            <span className="text-sm font-bold text-slate-700">Mã học viên</span>
            <input name="student_code" required defaultValue="HV-000001" className="rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100" />
          </label>
        </div>
        <button disabled={isSubmitting} className="mt-5 rounded-full bg-blue-600 px-7 py-3 font-black text-white shadow-lg shadow-blue-600/20 hover:bg-blue-700 disabled:opacity-60">
          {isSubmitting ? "Đang tra cứu..." : "Tra cứu"}
        </button>
        {message ? <p className="mt-4 text-sm font-semibold text-red-600">{message}</p> : null}
      </form>

      {data ? <PortalResult data={data} /> : null}
    </div>
  );
}

function PortalResult({ data }: { data: PortalData }) {
  return (
    <div className="space-y-5">
      <section className="rounded-[2rem] bg-slate-950 p-6 text-white">
        <div className="text-sm text-blue-200">Học viên</div>
        <h2 className="mt-2 text-3xl font-black">{data.student.full_name}</h2>
        <p className="mt-2 text-slate-300">Mã: {data.student.student_code} · Trạng thái: {formatStatus(data.student.status)}</p>
        {data.student.learning_goal ? <p className="mt-4 leading-7 text-slate-300">{data.student.learning_goal}</p> : null}
      </section>

      <div className="grid gap-5 md:grid-cols-3">
        <Metric title="Tổng học phí" value={formatVnd(data.finance.total_vnd)} />
        <Metric title="Đã thanh toán" value={formatVnd(data.finance.paid_vnd)} />
        <Metric title="Công nợ" value={formatVnd(data.finance.balance_vnd)} />
      </div>

      {data.progress_reports.length ? (
        <section className="rounded-[2rem] border border-blue-100 bg-gradient-to-br from-blue-50 to-white p-6 shadow-sm">
          <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
              <div className="text-sm font-black uppercase tracking-[0.2em] text-blue-600">Báo cáo tiến bộ</div>
              <h3 className="mt-2 text-2xl font-black text-slate-950">{data.progress_reports[0].title}</h3>
              <p className="mt-2 text-slate-600">{data.progress_reports[0].recommendation}</p>
            </div>
            <div className="rounded-3xl bg-white p-5 text-center shadow-sm">
              <div className="text-4xl font-black text-blue-600">{data.progress_reports[0].progress_percent}%</div>
              <div className="mt-1 text-xs font-bold uppercase tracking-[0.2em] text-slate-500">{formatLevel(data.progress_reports[0].overall_level)}</div>
            </div>
          </div>
          <div className="mt-5 h-3 overflow-hidden rounded-full bg-blue-100">
            <div className="h-full rounded-full bg-blue-600" style={{ width: `${Math.min(data.progress_reports[0].progress_percent, 100)}%` }} />
          </div>
          <div className="mt-5 grid gap-4 md:grid-cols-2">
            <MiniBox title="Điểm mạnh" value={data.progress_reports[0].strengths ?? "Chưa có nhận xét."} />
            <MiniBox title="Cần cải thiện" value={data.progress_reports[0].improvements ?? "Chưa có nhận xét."} />
          </div>
        </section>
      ) : null}

      <Card title="Khóa đang học">
        {data.enrollments.map((enrollment) => (
          <Row key={enrollment.enrollment_code} title={enrollment.course ?? "Khóa học"} description={`${enrollment.class_group ?? "Chưa xếp lớp"} · ${enrollment.schedule_note ?? "Chưa có lịch"}`} meta={formatStatus(enrollment.status)} />
        ))}
      </Card>

      <Card title="Lịch học">
        {data.upcoming_sessions.map((session, index) => (
          <Row key={`${session.title}-${index}`} title={session.title ?? "Buổi học"} description={`${session.starts_at ?? "Chưa có giờ"} · ${session.location ?? "Chưa có địa điểm"}`} meta={formatStatus(session.status)} />
        ))}
      </Card>

      <Card title="Điểm danh gần đây">
        {data.attendance.map((record, index) => (
          <Row key={`${record.session}-${index}`} title={record.session ?? "Buổi học"} description={record.teacher_note ?? record.class_group ?? ""} meta={formatAttendance(record.status)} />
        ))}
      </Card>

      <Card title="Kết quả đánh giá">
        {data.assessment_results.map((result, index) => (
          <Row
            key={`${result.assessment}-${index}`}
            title={result.assessment ?? "Bài đánh giá"}
            description={result.feedback ?? result.strengths ?? "Chưa có nhận xét."}
            meta={`${result.score ?? "-"} / ${result.max_score} · ${formatLevel(result.level)}`}
          />
        ))}
      </Card>

      <Card title="Nhận xét giáo viên">
        {data.teacher_comments.map((comment, index) => (
          <Row
            key={`${comment.title}-${index}`}
            title={comment.title ?? comment.session ?? "Nhận xét"}
            description={comment.comment}
            meta={`${formatCommentType(comment.comment_type)}${comment.rating ? ` · ${comment.rating}/5` : ""}`}
          />
        ))}
      </Card>

      <Card title="Thông báo từ trung tâm">
        {data.notifications.map((notification) => (
          <Row
            key={notification.title}
            title={notification.title}
            description={notification.body}
            meta={`${formatNotificationType(notification.notification_type)} · ${formatPriority(notification.priority)}`}
          />
        ))}
      </Card>

      <Card title="Chứng chỉ">
        {data.certificates.map((certificate) => (
          <Row
            key={certificate.certificate_code}
            title={certificate.title}
            description={`${certificate.course ?? "Khóa học"} · Mã xác thực: ${certificate.verification_token}`}
            meta={`${certificate.grade ?? "Đạt"}${certificate.final_score ? ` · ${certificate.final_score}/10` : ""}`}
          />
        ))}
      </Card>

      <Card title="Video học liên quan">
        {data.videos.map((video) => (
          <Row key={video.title} title={video.title} description={video.summary ?? "Video bài học DAYAI"} meta={`${formatAccessLevel(video.access_level)}${video.duration_minutes ? ` · ${video.duration_minutes} phút` : ""}`} />
        ))}
      </Card>
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

function MiniBox({ title, value }: { title: string; value: string }) {
  return (
    <div className="rounded-3xl border border-blue-100 bg-white p-4">
      <div className="text-sm font-black text-slate-900">{title}</div>
      <div className="mt-2 text-sm leading-6 text-slate-600">{value}</div>
    </div>
  );
}

function Card({ title, children }: { title: string; children: ReactNode }) {
  return (
    <section className="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
      <h3 className="text-xl font-black">{title}</h3>
      <div className="mt-4 divide-y divide-slate-100">{children}</div>
    </section>
  );
}

function Row({ title, description, meta }: { title: string; description: string; meta: string }) {
  return (
    <div className="flex items-start justify-between gap-4 py-4">
      <div>
        <div className="font-bold">{title}</div>
        <div className="mt-1 text-sm leading-6 text-slate-600">{description}</div>
      </div>
      <div className="shrink-0 rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">{meta}</div>
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

function formatStatus(status: string | null) {
  return {
    active: "Đang học",
    scheduled: "Đã lên lịch",
    present: "Có mặt",
    enrolling: "Đang tuyển sinh",
    partially_paid: "Thanh toán một phần",
    paid: "Đã thanh toán",
  }[status ?? ""] ?? status ?? "Chưa rõ";
}

function formatAttendance(status: string) {
  return {
    present: "Có mặt",
    absent: "Vắng",
    late: "Đi muộn",
    excused: "Xin nghỉ",
  }[status] ?? status;
}

function formatAccessLevel(accessLevel: string) {
  return {
    public: "Công khai",
    student: "Học viên",
    lead_magnet: "Đổi lead",
    internal: "Nội bộ",
  }[accessLevel] ?? accessLevel;
}

function formatLevel(level: string | null) {
  return {
    needs_support: "Cần hỗ trợ",
    on_track: "Đúng tiến độ",
    excellent: "Nổi bật",
  }[level ?? ""] ?? level ?? "Chưa rõ";
}

function formatCommentType(type: string) {
  return {
    session: "Theo buổi",
    progress: "Tiến bộ",
    behavior: "Thái độ",
    homework: "Bài tập",
    general: "Chung",
  }[type] ?? type;
}

function formatNotificationType(type: string) {
  return {
    general: "Chung",
    schedule: "Lịch học",
    finance: "Học phí",
    progress: "Tiến bộ",
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
