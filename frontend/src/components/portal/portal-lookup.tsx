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
    slug: string;
    summary: string | null;
    duration_minutes: number | null;
    access_level: string;
    thumbnail_url: string | null;
    progress_percent: number;
    progress_status: string;
  }>;
  lms: {
    overall_progress_percent: number;
    courses: Array<{
      enrollment_code: string;
      course: string;
      course_slug: string;
      status: string;
      progress_percent: number;
      modules: Array<{
        title: string;
        description: string | null;
        duration_minutes: number | null;
        learning_objectives: string[];
        lessons: Array<{
          title: string;
          slug: string;
          summary: string | null;
          duration_minutes: number | null;
          access_level: string;
          video_url: string | null;
          thumbnail_url: string | null;
          resources: Array<{ title?: string; url?: string }>;
          progress: {
            status: string;
            progress_percent: number;
            last_position_seconds: number;
            last_watched_at: string | null;
            completed_at: string | null;
          };
        }>;
      }>;
    }>;
  };
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
  const [authRequestId, setAuthRequestId] = useState("");
  const [demoOtp, setDemoOtp] = useState("");
  const [portalAccessToken, setPortalAccessToken] = useState("");
  const [credentials, setCredentials] = useState({
    phone: "0901888000",
    student_code: "HV-000001",
  });

  async function handleRequestCode(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setIsSubmitting(true);
    setMessage("");
    setData(null);
    setAuthRequestId("");
    setPortalAccessToken("");
    setDemoOtp("");

    const formData = new FormData(event.currentTarget);
    const nextCredentials = {
      phone: String(formData.get("phone") ?? ""),
      student_code: String(formData.get("student_code") ?? ""),
    };

    try {
      const response = await fetch("/api/portal/auth/request", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(nextCredentials),
      });

      const result = await response.json();

      if (!response.ok) {
        throw new Error(result.message ?? "Không gửi được mã xác thực.");
      }

      setCredentials(nextCredentials);
      setAuthRequestId(result.request_id);
      setDemoOtp(result.demo_otp ?? "");
      setMessage("Đã tạo mã xác thực portal. Vui lòng nhập mã để tiếp tục.");
    } catch (error) {
      setMessage(error instanceof Error ? error.message : "Có lỗi xảy ra.");
    } finally {
      setIsSubmitting(false);
    }
  }

  async function handleVerifyCode(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setIsSubmitting(true);
    setMessage("");
    setData(null);

    const formData = new FormData(event.currentTarget);

    try {
      const response = await fetch("/api/portal/auth/verify", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          request_id: authRequestId,
          code: String(formData.get("code") ?? ""),
        }),
      });

      const result = await response.json();

      if (!response.ok) {
        throw new Error(result.message ?? "Không xác thực được mã portal.");
      }

      setPortalAccessToken(result.portal_access_token);
      setMessage("Xác thực thành công. Đang tải dữ liệu học viên...");
      await loadPortalData(result.portal_access_token);
    } catch (error) {
      setMessage(error instanceof Error ? error.message : "Có lỗi xảy ra.");
    } finally {
      setIsSubmitting(false);
    }
  }

  async function loadPortalData(accessToken = portalAccessToken) {
    const response = await fetch("/api/portal/lookup", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        ...credentials,
        portal_access_token: accessToken,
      }),
    });

    const result = await response.json();

    if (!response.ok) {
      throw new Error(result.message ?? "Không tra cứu được thông tin.");
    }

    setData(result as PortalData);
  }

  return (
    <div className="space-y-6">
      <form onSubmit={handleRequestCode} className="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-xl shadow-blue-950/5">
        <div className="mb-5 rounded-3xl bg-blue-50 p-4 text-sm leading-6 text-slate-700">
          <strong className="text-[#003A99]">Bảo mật Sprint 28:</strong> Portal dùng mã xác thực một lần trước khi hiển thị lịch học, học phí và tiến độ.
        </div>
        <div className="grid gap-4 sm:grid-cols-2">
          <label className="grid gap-2">
            <span className="text-sm font-bold text-slate-700">Số điện thoại</span>
            <input name="phone" required defaultValue={credentials.phone} className="rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-[#003A99] focus:ring-4 focus:ring-blue-100" />
          </label>
          <label className="grid gap-2">
            <span className="text-sm font-bold text-slate-700">Mã học viên</span>
            <input name="student_code" required defaultValue={credentials.student_code} className="rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-[#003A99] focus:ring-4 focus:ring-blue-100" />
          </label>
        </div>
        <button disabled={isSubmitting} className="mt-5 rounded-full bg-[#003A99] px-7 py-3 font-black text-white shadow-lg shadow-blue-600/20 hover:bg-[#002B73] disabled:opacity-60">
          {isSubmitting ? "Đang gửi mã..." : "Gửi mã xác thực"}
        </button>
        {message ? <p className="mt-4 text-sm font-semibold text-red-600">{message}</p> : null}
      </form>

      {authRequestId ? (
        <form onSubmit={handleVerifyCode} className="rounded-[2rem] border border-blue-100 bg-white p-6 shadow-sm">
          <div className="text-sm font-black uppercase tracking-[0.18em] text-[#003A99]">Xác thực portal</div>
          <h3 className="mt-2 text-2xl font-black text-slate-950">Nhập mã 6 số</h3>
          <p className="mt-2 text-sm leading-6 text-slate-600">Mã có hiệu lực trong 10 phút. Khi cấu hình nhà cung cấp email/SMS/Zalo, mã sẽ được gửi qua kênh tương ứng.</p>
          {demoOtp ? <div className="mt-4 rounded-2xl bg-amber-50 px-4 py-3 text-sm font-bold text-amber-700">Mã demo local: {demoOtp}</div> : null}
          <div className="mt-5 flex flex-col gap-3 sm:flex-row">
            <input name="code" required inputMode="numeric" minLength={6} maxLength={6} placeholder="Nhập mã OTP" className="rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-[#003A99] focus:ring-4 focus:ring-blue-100" />
            <button disabled={isSubmitting} className="rounded-full bg-slate-950 px-7 py-3 font-black text-white hover:bg-slate-800 disabled:opacity-60">
              {isSubmitting ? "Đang xác thực..." : "Xác thực & xem portal"}
            </button>
          </div>
        </form>
      ) : null}

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

      <section className="overflow-hidden rounded-[2rem] border border-blue-100 bg-white shadow-sm">
        <div className="bg-gradient-to-r from-[#003A99] via-[#006FD6] to-[#00AEEF] p-6 text-white">
          <div className="text-sm font-black uppercase tracking-[0.2em] text-blue-100">Không gian học LMS</div>
          <div className="mt-3 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
              <h3 className="text-3xl font-black">Lộ trình video & tài liệu</h3>
              <p className="mt-2 max-w-2xl text-blue-50">Học viên theo dõi module, bài học, tài liệu tải về và tiến độ hoàn thành ngay trong portal.</p>
            </div>
            <div className="rounded-3xl bg-white/15 px-5 py-4 text-center backdrop-blur">
              <div className="text-4xl font-black">{data.lms.overall_progress_percent}%</div>
              <div className="mt-1 text-xs font-black uppercase tracking-[0.2em] text-blue-100">Tiến độ LMS</div>
            </div>
          </div>
        </div>
        <div className="space-y-5 p-6">
          {withEmpty(data.lms.courses, "Chưa có khóa học online được mở.", (course) => (
            <div key={course.enrollment_code} className="rounded-3xl border border-slate-200 bg-slate-50 p-5">
              <div className="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                  <div className="text-xs font-black uppercase tracking-[0.18em] text-[#003A99]">{formatStatus(course.status)}</div>
                  <h4 className="mt-1 text-2xl font-black text-slate-950">{course.course}</h4>
                </div>
                <div className="min-w-40">
                  <div className="text-right text-sm font-black text-slate-700">{course.progress_percent}% hoàn thành</div>
                  <div className="mt-2 h-2 overflow-hidden rounded-full bg-blue-100">
                    <div className="h-full rounded-full bg-[#00AEEF]" style={{ width: `${Math.min(course.progress_percent, 100)}%` }} />
                  </div>
                </div>
              </div>
              <div className="mt-5 space-y-4">
                {course.modules.map((module) => (
                  <div key={module.title} className="rounded-3xl bg-white p-4 shadow-sm">
                    <div className="flex flex-col gap-2 md:flex-row md:items-start md:justify-between">
                      <div>
                        <h5 className="font-black text-slate-950">{module.title}</h5>
                        <p className="mt-1 text-sm leading-6 text-slate-600">{module.description ?? "Module học thực hành của DAYAI."}</p>
                      </div>
                      <div className="rounded-full bg-blue-50 px-3 py-1 text-xs font-black text-[#003A99]">{module.duration_minutes ?? 0} phút</div>
                    </div>
                    <div className="mt-4 divide-y divide-slate-100">
                      {withEmpty(module.lessons, "Module này chưa có bài học.", (lesson) => (
                        <LessonRow key={lesson.slug} lesson={lesson} />
                      ))}
                    </div>
                  </div>
                ))}
              </div>
            </div>
          ))}
        </div>
      </section>

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
            <Row key={video.slug} title={video.title} description={video.summary ?? "Video bài học DAYAI"} meta={`${formatLessonStatus(video.progress_status)} · ${video.progress_percent}%`} />
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

function LessonRow({ lesson }: { lesson: PortalData["lms"]["courses"][number]["modules"][number]["lessons"][number] }) {
  return (
    <div className="grid gap-4 py-4 md:grid-cols-[1fr_auto] md:items-center">
      <div>
        <div className="flex flex-wrap items-center gap-2">
          <div className="font-bold text-slate-950">{lesson.title}</div>
          <span className="rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-black uppercase tracking-[0.12em] text-slate-500">{formatAccessLevel(lesson.access_level)}</span>
          {lesson.resources.length ? <span className="rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-black text-amber-700">{lesson.resources.length} tài liệu</span> : null}
        </div>
        <div className="mt-1 text-sm leading-6 text-slate-600">{lesson.summary ?? "Bài học video thực hành có theo dõi tiến độ."}</div>
        <div className="mt-3 h-2 overflow-hidden rounded-full bg-slate-100">
          <div className="h-full rounded-full bg-[#003A99]" style={{ width: `${Math.min(lesson.progress.progress_percent, 100)}%` }} />
        </div>
      </div>
      <div className="rounded-2xl bg-blue-50 px-4 py-3 text-right">
        <div className="text-sm font-black text-[#003A99]">{formatLessonStatus(lesson.progress.status)}</div>
        <div className="mt-1 text-xs text-slate-500">{lesson.duration_minutes ?? 0} phút · {lesson.progress.progress_percent}%</div>
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

function formatLessonStatus(status: string) {
  return {
    not_started: "Chưa học",
    in_progress: "Đang học",
    completed: "Hoàn thành",
  }[status] ?? status;
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
