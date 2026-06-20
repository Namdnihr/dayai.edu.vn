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
  summary: {
    active_enrollments: number;
    next_session: {
      title: string | null;
      starts_at: string | null;
      location: string | null;
      status: string | null;
    } | null;
    latest_progress_percent: number | null;
    attendance: {
      total: number;
      present: number;
      absent: number;
      late: number;
      excused: number;
    };
    finance_balance_vnd: number;
    unread_notifications: number;
  };
  guardians: Array<{
    full_name: string | null;
    phone: string | null;
    relation_type: string | null;
  }>;
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
            <input name="phone" required defaultValue="0901888000" className="rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-[#003A99] focus:ring-4 focus:ring-blue-100" />
          </label>
          <label className="grid gap-2">
            <span className="text-sm font-bold text-slate-700">Mã học viên</span>
            <input name="student_code" required defaultValue="HV-000001" className="rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-[#003A99] focus:ring-4 focus:ring-blue-100" />
          </label>
        </div>
        <button disabled={isSubmitting} className="mt-5 rounded-full bg-[#003A99] px-7 py-3 font-black text-white shadow-lg shadow-blue-600/20 hover:bg-[#002B73] disabled:opacity-60">
          {isSubmitting ? "Đang tra cứu..." : "Tra cứu"}
        </button>
        {message ? <p className="mt-4 text-sm font-semibold text-red-600">{message}</p> : null}
      </form>

      {data ? <PortalResult data={data} /> : null}
    </div>
  );
}

function PortalResult({ data }: { data: PortalData }) {
  const latestProgress = data.progress_reports[0];

  return (
    <div className="space-y-5">
      <section className="overflow-hidden rounded-[2rem] bg-slate-950 p-6 text-white">
        <div className="text-sm font-bold uppercase tracking-[0.18em] text-blue-200">Học viên</div>
        <h2 className="mt-2 text-3xl font-black">{data.student.full_name}</h2>
        <p className="mt-2 text-slate-300">Mã: {data.student.student_code} · Trạng thái: {formatStatus(data.student.status)}</p>
        {data.student.learning_goal ? <p className="mt-4 leading-7 text-slate-300">{data.student.learning_goal}</p> : null}
      </section>

      <div className="grid gap-4 md:grid-cols-4">
        <Metric title="Khóa đang học" value={String(data.summary.active_enrollments)} />
        <Metric title="Tiến độ mới nhất" value={data.summary.latest_progress_percent !== null ? `${data.summary.latest_progress_percent}%` : "Chưa có"} />
        <Metric title="Buổi có mặt" value={`${data.summary.attendance.present}/${data.summary.attendance.total}`} />
        <Metric title="Công nợ" value={formatVnd(data.summary.finance_balance_vnd)} tone={data.summary.finance_balance_vnd > 0 ? "warning" : "success"} />
      </div>

      {data.summary.next_session ? (
        <section className="rounded-[2rem] border border-blue-100 bg-white p-6 shadow-sm">
          <div className="text-sm font-black uppercase tracking-[0.2em] text-[#003A99]">Buổi học kế tiếp</div>
          <h3 className="mt-2 text-2xl font-black text-slate-950">{data.summary.next_session.title ?? "Buổi học"}</h3>
          <p className="mt-2 text-slate-600">{formatDateTime(data.summary.next_session.starts_at)} · {data.summary.next_session.location ?? "Chưa có địa điểm"}</p>
        </section>
      ) : null}

      {latestProgress ? (
        <section className="rounded-[2rem] border border-blue-100 bg-gradient-to-br from-blue-50 to-white p-6 shadow-sm">
          <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
              <div className="text-sm font-black uppercase tracking-[0.2em] text-[#003A99]">Báo cáo tiến bộ</div>
              <h3 className="mt-2 text-2xl font-black text-slate-950">{latestProgress.title}</h3>
              <p className="mt-2 text-slate-600">{latestProgress.recommendation}</p>
            </div>
            <div className="rounded-3xl bg-white p-5 text-center shadow-sm">
              <div className="text-4xl font-black text-[#003A99]">{latestProgress.progress_percent}%</div>
              <div className="mt-1 text-xs font-bold uppercase tracking-[0.2em] text-slate-500">{formatLevel(latestProgress.overall_level)}</div>
            </div>
          </div>
          <div className="mt-5 h-3 overflow-hidden rounded-full bg-blue-100">
            <div className="h-full rounded-full bg-[#003A99]" style={{ width: `${Math.min(latestProgress.progress_percent, 100)}%` }} />
          </div>
          <div className="mt-5 grid gap-4 md:grid-cols-2">
            <MiniBox title="Điểm mạnh" value={latestProgress.strengths ?? "Chưa có nhận xét."} />
            <MiniBox title="Cần cải thiện" value={latestProgress.improvements ?? "Chưa có nhận xét."} />
          </div>
        </section>
      ) : null}

      <div className="grid gap-5 lg:grid-cols-2">
        <Card title="Khóa đang học">
          {withEmpty(data.enrollments, "Chưa có khóa học.", (enrollment) => (
            <Row key={enrollment.enrollment_code} title={enrollment.course ?? "Khóa học"} description={`${enrollment.class_group ?? "Chưa xếp lớp"} · ${enrollment.schedule_note ?? "Chưa có lịch"}`} meta={formatStatus(enrollment.status)} />
          ))}
        </Card>

        <Card title="Lịch học">
          {withEmpty(data.upcoming_sessions, "Chưa có lịch học sắp tới.", (session, index) => (
            <Row key={`${session.title}-${index}`} title={session.title ?? "Buổi học"} description={`${formatDateTime(session.starts_at)} · ${session.location ?? "Chưa có địa điểm"}`} meta={formatStatus(session.status)} />
          ))}
        </Card>

        <Card title="Điểm danh gần đây">
          {withEmpty(data.attendance, "Chưa có dữ liệu điểm danh.", (record, index) => (
            <Row key={`${record.session}-${index}`} title={record.session ?? "Buổi học"} description={record.teacher_note ?? record.class_group ?? ""} meta={formatAttendance(record.status)} />
          ))}
        </Card>

        <Card title="Học phí & công nợ">
          {withEmpty(data.finance.orders, "Chưa có đơn học phí.", (order) => (
            <Row key={order.order_code} title={order.order_code} description={`Tổng ${formatVnd(order.total_vnd)} · Đã thu ${formatVnd(order.paid_vnd)}`} meta={formatVnd(order.balance_vnd)} />
          ))}
        </Card>

        <Card title="Kết quả đánh giá">
          {withEmpty(data.assessment_results, "Chưa có kết quả đánh giá.", (result, index) => (
            <Row key={`${result.assessment}-${index}`} title={result.assessment ?? "Bài đánh giá"} description={result.feedback ?? result.strengths ?? "Chưa có nhận xét."} meta={`${result.score ?? "-"} / ${result.max_score}`} />
          ))}
        </Card>

        <Card title="Nhận xét giáo viên">
          {withEmpty(data.teacher_comments, "Chưa có nhận xét giáo viên.", (comment, index) => (
            <Row key={`${comment.title}-${index}`} title={comment.title ?? comment.session ?? "Nhận xét"} description={comment.comment} meta={`${formatCommentType(comment.comment_type)}${comment.rating ? ` · ${comment.rating}/5` : ""}`} />
          ))}
        </Card>

        <Card title="Thông báo từ trung tâm">
          {withEmpty(data.notifications, "Chưa có thông báo.", (notification) => (
            <Row key={notification.title} title={notification.title} description={notification.body} meta={`${formatNotificationType(notification.notification_type)} · ${formatPriority(notification.priority)}`} />
          ))}
        </Card>

        <Card title="Video học liên quan">
          {withEmpty(data.videos, "Chưa có video liên quan.", (video) => (
            <Row key={video.title} title={video.title} description={video.summary ?? "Video bài học DAYAI"} meta={`${formatAccessLevel(video.access_level)}${video.duration_minutes ? ` · ${video.duration_minutes} phút` : ""}`} />
          ))}
        </Card>

        <Card title="Chứng chỉ">
          {withEmpty(data.certificates, "Chưa có chứng chỉ.", (certificate) => (
            <Row key={certificate.certificate_code} title={certificate.title} description={`${certificate.course ?? "Khóa học"} · Mã xác thực: ${certificate.verification_token}`} meta={`${certificate.grade ?? "Đạt"}${certificate.final_score ? ` · ${certificate.final_score}/10` : ""}`} />
          ))}
        </Card>
      </div>
    </div>
  );
}

function Metric({ title, value, tone = "default" }: { title: string; value: string; tone?: "default" | "warning" | "success" }) {
  const color = tone === "warning" ? "text-amber-600" : tone === "success" ? "text-emerald-600" : "text-slate-950";

  return (
    <div className="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
      <div className="text-sm text-slate-500">{title}</div>
      <div className={`mt-2 text-2xl font-black ${color}`}>{value}</div>
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
      <div className="shrink-0 rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-[#003A99]">{meta}</div>
    </div>
  );
}

function withEmpty<T>(items: T[], emptyText: string, render: (item: T, index: number) => ReactNode) {
  if (!items.length) {
    return <div className="py-4 text-sm text-slate-500">{emptyText}</div>;
  }

  return items.map(render);
}

function formatVnd(amount: number) {
  return new Intl.NumberFormat("vi-VN", {
    style: "currency",
    currency: "VND",
    maximumFractionDigits: 0,
  }).format(amount);
}

function formatDateTime(value: string | null) {
  if (!value) {
    return "Chưa có thời gian";
  }

  return new Intl.DateTimeFormat("vi-VN", {
    dateStyle: "short",
    timeStyle: "short",
  }).format(new Date(value));
}

function formatStatus(status: string | null) {
  return {
    active: "Đang học",
    scheduled: "Đã lên lịch",
    present: "Có mặt",
    enrolling: "Đang tuyển sinh",
    partially_paid: "Thanh toán một phần",
    paid: "Đã thanh toán",
    completed: "Hoàn thành",
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
