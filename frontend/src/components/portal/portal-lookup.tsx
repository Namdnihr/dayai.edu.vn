"use client";

import { FormEvent, useCallback, useEffect, useRef, useState } from "react";
import type { ReactNode } from "react";

type PortalData = {
  student: {
    student_code: string;
    full_name: string | null;
    student_type: string | null;
    learning_goal: string | null;
    status: string | null;
    portal_access_role: string;
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
  available_assessments: Array<{
    id: string;
    title: string;
    assessment_type: string;
    course: string | null;
    module: string | null;
    video: string | null;
    description: string | null;
    max_score: number;
    question_count: number;
    assessment_at: string | null;
  }>;
  quiz_attempts: Array<{
    attempt_code: string;
    assessment_id: string;
    assessment: string | null;
    attempt_no: number;
    status: string;
    score: number | null;
    max_score: number;
    correct_count: number;
    question_count: number;
    submitted_at: string | null;
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

type ActiveQuiz = {
  attempt: {
    attempt_code: string;
    assessment_id: string;
    attempt_no: number;
    status: string;
    score: number | null;
    max_score: number;
    correct_count: number;
    question_count: number;
    started_at: string | null;
    submitted_at: string | null;
    answers?: Array<{
      question_id: string;
      prompt: string | null;
      selected_option_ids: string[];
      answer_text: string | null;
      is_correct: boolean | null;
      score_awarded: number;
    }>;
  };
  assessment: {
    id: string;
    title: string;
    assessment_type: string;
    description: string | null;
    max_score: number;
    question_count: number;
    questions: Array<{
      assessment_question_id: string;
      question_id: string;
      sort_order: number;
      score: number;
      question_type: string;
      difficulty: string;
      prompt: string;
      options: Array<{ id: string; sort_order: number; content: string }>;
    }>;
  };
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
  const didRestoreSession = useRef(false);

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
      window.sessionStorage.setItem(
        "dayai_portal_session",
        JSON.stringify({
          ...credentials,
          portal_access_token: result.portal_access_token,
        }),
      );
      setMessage("Xác thực thành công. Đang tải dữ liệu học viên...");
      await loadPortalData(result.portal_access_token);
    } catch (error) {
      setMessage(error instanceof Error ? error.message : "Có lỗi xảy ra.");
    } finally {
      setIsSubmitting(false);
    }
  }

  const loadPortalData = useCallback(async (accessToken = portalAccessToken, nextCredentials = credentials) => {
    const response = await fetch("/api/portal/lookup", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        ...nextCredentials,
        portal_access_token: accessToken,
      }),
    });

    const result = await response.json();

    if (!response.ok) {
      throw new Error(result.message ?? "Không tra cứu được thông tin.");
    }

    setData(result as PortalData);
  }, [credentials, portalAccessToken]);

  useEffect(() => {
    if (didRestoreSession.current) {
      return;
    }

    didRestoreSession.current = true;

    const params = new URLSearchParams(window.location.search);
    const urlCredentials = {
      phone: params.get("phone") ?? "",
      student_code: params.get("student_code") ?? "",
    };
    const hasUrlCredentials = Boolean(urlCredentials.phone && urlCredentials.student_code);
    const rawSession = window.sessionStorage.getItem("dayai_portal_session");
    const applyUrlCredentials = () => {
      queueMicrotask(() => {
        setCredentials(urlCredentials);
        setMessage("Đã điền thông tin học viên từ khóa học. Bấm gửi mã xác thực để vào portal.");
      });
    };

    if (!rawSession) {
      if (hasUrlCredentials) {
        applyUrlCredentials();
      }

      return;
    }

    try {
      const session = JSON.parse(rawSession) as { phone: string; student_code: string; portal_access_token: string };

      if (!session.phone || !session.student_code || !session.portal_access_token) {
        if (hasUrlCredentials) {
          applyUrlCredentials();
        }

        return;
      }

      const sessionMatchesUrl =
        !hasUrlCredentials ||
        (session.phone === urlCredentials.phone && session.student_code === urlCredentials.student_code);

      if (!sessionMatchesUrl) {
        applyUrlCredentials();
        return;
      }

      const restoredCredentials = hasUrlCredentials
        ? urlCredentials
        : {
            phone: session.phone,
            student_code: session.student_code,
          };

      queueMicrotask(() => {
        setCredentials(restoredCredentials);
        setPortalAccessToken(session.portal_access_token);
        setMessage("Đang khôi phục phiên học viên...");
        void loadPortalData(session.portal_access_token, restoredCredentials).catch((error) => {
          window.sessionStorage.removeItem("dayai_portal_session");
          setMessage(error instanceof Error ? error.message : "Phiên đăng nhập đã hết hạn. Vui lòng xác thực lại.");
        });
      });
    } catch {
      window.sessionStorage.removeItem("dayai_portal_session");

      if (hasUrlCredentials) {
        applyUrlCredentials();
      }
    }
  }, [loadPortalData]);

  function handleLogout() {
    window.sessionStorage.removeItem("dayai_portal_session");
    setData(null);
    setMessage("");
    setAuthRequestId("");
    setPortalAccessToken("");
    setDemoOtp("");
  }

  if (data) {
    return <PortalResult data={data} credentials={credentials} portalAccessToken={portalAccessToken} onLogout={handleLogout} onReload={() => loadPortalData(portalAccessToken)} />;
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
            <input
              name="phone"
              required
              value={credentials.phone}
              onChange={(event) => setCredentials((current) => ({ ...current, phone: event.target.value }))}
              className="rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-[#003A99] focus:ring-4 focus:ring-blue-100"
            />
          </label>
          <label className="grid gap-2">
            <span className="text-sm font-bold text-slate-700">Mã học viên</span>
            <input
              name="student_code"
              required
              value={credentials.student_code}
              onChange={(event) => setCredentials((current) => ({ ...current, student_code: event.target.value }))}
              className="rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-[#003A99] focus:ring-4 focus:ring-blue-100"
            />
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

    </div>
  );
}

function PortalResult({ data, credentials, portalAccessToken, onLogout, onReload }: { data: PortalData; credentials: { phone: string; student_code: string }; portalAccessToken: string; onLogout: () => void; onReload: () => Promise<void> }) {
  const [activeTab, setActiveTab] = useState(() => {
    if (typeof window === "undefined") {
      return "overview";
    }

    const requestedTab = new URLSearchParams(window.location.search).get("tab");

    return ["overview", "courses", "schedule", "finance", "reports", "tests", "notifications", "certificates"].includes(requestedTab ?? "")
      ? requestedTab ?? "overview"
      : "overview";
  });
  const [activeQuiz, setActiveQuiz] = useState<ActiveQuiz | null>(null);
  const [quizMessage, setQuizMessage] = useState("");
  const [quizAnswers, setQuizAnswers] = useState<Record<string, string[]>>({});
  const [quizTexts, setQuizTexts] = useState<Record<string, string>>({});
  const [isQuizSubmitting, setIsQuizSubmitting] = useState(false);
  const latestProgress = data.progress_reports[0];
  const tabs = [
    { key: "overview", label: "Tổng quan", badge: data.summary.unread_notifications ? String(data.summary.unread_notifications) : undefined },
    { key: "courses", label: "Khóa học", badge: String(data.lms.courses.length) },
    { key: "schedule", label: "Lịch học", badge: String(data.upcoming_sessions.length) },
    { key: "finance", label: "Học phí", badge: data.summary.finance_balance_vnd > 0 ? "!" : undefined },
    { key: "reports", label: "Báo cáo" },
    { key: "tests", label: "Bài kiểm tra", badge: String(data.available_assessments.length) },
    { key: "notifications", label: "Thông báo", badge: String(data.notifications.length) },
    { key: "certificates", label: "Chứng chỉ", badge: String(data.certificates.length) },
  ];



  async function startQuiz(assessmentId: string) {
    setIsQuizSubmitting(true);
    setQuizMessage("");
    setActiveQuiz(null);
    setQuizAnswers({});
    setQuizTexts({});

    try {
      const response = await fetch(`/api/portal/assessments/${assessmentId}/start`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ ...credentials, portal_access_token: portalAccessToken }),
      });
      const result = await response.json();

      if (!response.ok) {
        throw new Error(result.message ?? "Không bắt đầu được bài kiểm tra.");
      }

      setActiveQuiz(result as ActiveQuiz);
      setQuizMessage("Đã tạo lượt làm bài. Hãy trả lời và nộp bài khi sẵn sàng.");
    } catch (error) {
      setQuizMessage(error instanceof Error ? error.message : "Có lỗi xảy ra khi mở bài kiểm tra.");
    } finally {
      setIsQuizSubmitting(false);
    }
  }

  function toggleOption(questionId: string, optionId: string, multiple: boolean) {
    setQuizAnswers((current) => {
      const selected = current[questionId] ?? [];
      const nextSelected = multiple
        ? selected.includes(optionId)
          ? selected.filter((id) => id !== optionId)
          : [...selected, optionId]
        : [optionId];

      return { ...current, [questionId]: nextSelected };
    });
  }

  async function submitQuiz() {
    if (!activeQuiz) {
      return;
    }

    setIsQuizSubmitting(true);
    setQuizMessage("");

    try {
      const response = await fetch(`/api/portal/quiz-attempts/${activeQuiz.attempt.attempt_code}/submit`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          ...credentials,
          portal_access_token: portalAccessToken,
          answers: activeQuiz.assessment.questions.map((question) => ({
            assessment_question_id: question.assessment_question_id,
            selected_option_ids: quizAnswers[question.assessment_question_id] ?? [],
            answer_text: quizTexts[question.assessment_question_id] ?? "",
          })),
        }),
      });
      const result = await response.json();

      if (!response.ok) {
        throw new Error(result.message ?? "Không nộp được bài kiểm tra.");
      }

      setActiveQuiz((current) => current ? { ...current, attempt: result.attempt } : current);
      setQuizMessage("Nộp bài thành công. Kết quả trắc nghiệm đã được chấm tự động.");
      await onReload();
    } catch (error) {
      setQuizMessage(error instanceof Error ? error.message : "Có lỗi xảy ra khi nộp bài.");
    } finally {
      setIsQuizSubmitting(false);
    }
  }


  return (
    <div className="grid gap-6 lg:grid-cols-[280px_minmax(0,1fr)]">
      <aside className="lg:sticky lg:top-24 lg:self-start">
        <section className="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-sm">
          <div className="bg-slate-950 p-5 text-white">
            <div className="text-xs font-black uppercase tracking-[0.2em] text-blue-200">DAYAI LMS</div>
            <h2 className="mt-2 text-2xl font-black leading-tight">{cleanText(data.student.full_name) ?? "Học viên DAYAI"}</h2>
            <p className="mt-2 text-sm text-slate-300">{data.student.student_code} · {formatPortalRole(data.student.portal_access_role)}</p>
          </div>

          <nav className="space-y-1 p-3">
            {tabs.map((tab) => (
              <button
                key={tab.key}
                type="button"
                onClick={() => {
                  setActiveTab(tab.key);

                  if (typeof window !== "undefined") {
                    const url = new URL(window.location.href);
                    url.searchParams.set("tab", tab.key);
                    window.history.replaceState(null, "", url);
                  }
                }}
                className={`flex w-full items-center justify-between rounded-2xl px-4 py-3 text-left text-sm font-black transition ${
                  activeTab === tab.key
                    ? "bg-[#003A99] text-white shadow-lg shadow-blue-900/15"
                    : "text-slate-600 hover:bg-slate-50 hover:text-[#003A99]"
                }`}
              >
                <span>{tab.label}</span>
                {tab.badge ? (
                  <span className={`rounded-full px-2 py-0.5 text-xs ${activeTab === tab.key ? "bg-white/20 text-white" : "bg-blue-50 text-[#003A99]"}`}>
                    {tab.badge}
                  </span>
                ) : null}
              </button>
            ))}
          </nav>

          <div className="border-t border-slate-100 p-4">
            <button
              type="button"
              onClick={onLogout}
              className="w-full rounded-full border border-slate-200 px-5 py-2.5 text-sm font-black text-slate-700 transition hover:border-[#003A99]/40 hover:bg-blue-50 hover:text-[#003A99]"
            >
              Đăng xuất
            </button>
          </div>
        </section>
      </aside>

      <main className="min-w-0 space-y-5">
        <section className="rounded-[2rem] border border-blue-100 bg-white p-6 shadow-sm">
          <div className="flex flex-col gap-5 md:flex-row md:items-start md:justify-between">
            <div>
              <div className="text-sm font-black uppercase tracking-[0.2em] text-[#003A99]">Dashboard học tập</div>
              <h1 className="mt-2 text-3xl font-black tracking-[-0.03em] text-slate-950 md:text-4xl">{tabTitle(activeTab)}</h1>
              {data.student.learning_goal ? <p className="mt-3 max-w-3xl leading-7 text-slate-600">{cleanText(data.student.learning_goal)}</p> : null}
            </div>
            <div className="rounded-3xl bg-blue-50 px-5 py-4 text-center">
              <div className="text-3xl font-black text-[#003A99]">{data.lms.overall_progress_percent}%</div>
              <div className="mt-1 text-xs font-black uppercase tracking-[0.2em] text-slate-500">Tiến độ LMS</div>
            </div>
          </div>
        </section>

        {activeTab === "overview" ? (
          <div className="space-y-5">
            <div className="grid gap-4 md:grid-cols-4">
              <Metric title="Khóa đang học" value={String(data.summary.active_enrollments)} />
              <Metric title="Tiến độ mới nhất" value={data.summary.latest_progress_percent !== null ? `${data.summary.latest_progress_percent}%` : "Chưa có"} />
              <Metric title="Buổi có mặt" value={`${data.summary.attendance.present}/${data.summary.attendance.total}`} />
              <Metric title="Công nợ" value={formatVnd(data.summary.finance_balance_vnd)} tone={data.summary.finance_balance_vnd > 0 ? "warning" : "success"} />
            </div>

            <div className="grid gap-5 xl:grid-cols-[1.1fr_0.9fr]">
              {data.summary.next_session ? (
                <section className="rounded-[2rem] border border-blue-100 bg-white p-6 shadow-sm">
                  <div className="text-sm font-black uppercase tracking-[0.2em] text-[#003A99]">Buổi học kế tiếp</div>
                  <h3 className="mt-2 text-2xl font-black text-slate-950">{cleanText(data.summary.next_session.title) ?? "Buổi học"}</h3>
                  <p className="mt-2 text-slate-600">{formatDateTime(data.summary.next_session.starts_at)} · {cleanText(data.summary.next_session.location) ?? "Chưa có địa điểm"}</p>
                </section>
              ) : null}

              {latestProgress ? (
                <section className="rounded-[2rem] border border-blue-100 bg-gradient-to-br from-blue-50 to-white p-6 shadow-sm">
                  <div className="flex items-start justify-between gap-4">
                    <div>
                      <div className="text-sm font-black uppercase tracking-[0.2em] text-[#003A99]">Báo cáo tiến bộ</div>
                      <h3 className="mt-2 text-2xl font-black text-slate-950">{cleanText(latestProgress.title)}</h3>
                    </div>
                    <div className="rounded-3xl bg-white px-4 py-3 text-center shadow-sm">
                      <div className="text-3xl font-black text-[#003A99]">{latestProgress.progress_percent}%</div>
                    </div>
                  </div>
                  <p className="mt-3 text-slate-600">{cleanText(latestProgress.recommendation)}</p>
                  <div className="mt-5 h-3 overflow-hidden rounded-full bg-blue-100">
                    <div className="h-full rounded-full bg-[#003A99]" style={{ width: `${Math.min(latestProgress.progress_percent, 100)}%` }} />
                  </div>
                </section>
              ) : null}
            </div>
          </div>
        ) : null}

        {activeTab === "courses" ? (
          <section className="overflow-hidden rounded-[2rem] border border-blue-100 bg-white shadow-sm">
            <div className="bg-gradient-to-r from-[#003A99] via-[#006FD6] to-[#00AEEF] p-6 text-white">
              <div className="text-sm font-black uppercase tracking-[0.2em] text-blue-100">Không gian học LMS</div>
              <h3 className="mt-2 text-3xl font-black">Khóa học, video & tài liệu</h3>
              <p className="mt-2 max-w-2xl text-blue-50">Theo dõi module, bài học, tài liệu tải về và tiến độ hoàn thành trong một màn hình.</p>
            </div>
            <div className="space-y-5 p-6">
              {withEmpty(data.lms.courses, "Chưa có khóa học online được mở.", (course) => (
                <div key={course.enrollment_code} className="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                  <div className="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                      <div className="text-xs font-black uppercase tracking-[0.18em] text-[#003A99]">{formatStatus(course.status)}</div>
                      <h4 className="mt-1 text-2xl font-black text-slate-950">{cleanText(course.course)}</h4>
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
                            <h5 className="font-black text-slate-950">{cleanText(module.title)}</h5>
                            <p className="mt-1 text-sm leading-6 text-slate-600">{cleanText(module.description) ?? "Module học thực hành của DAYAI."}</p>
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
        ) : null}

        {activeTab === "schedule" ? (
          <div className="grid gap-5 xl:grid-cols-2">
            <Card title="Khóa đang học">
              {withEmpty(data.enrollments, "Chưa có khóa học.", (enrollment) => (
                <Row key={enrollment.enrollment_code} title={cleanText(enrollment.course) ?? "Khóa học"} description={`${cleanText(enrollment.class_group) ?? "Chưa xếp lớp"} · ${cleanText(enrollment.schedule_note) ?? "Chưa có lịch"}`} meta={formatStatus(enrollment.status)} />
              ))}
            </Card>
            <Card title="Lịch học">
              {withEmpty(data.upcoming_sessions, "Chưa có lịch học sắp tới.", (session, index) => (
                <Row key={`${session.title}-${index}`} title={cleanText(session.title) ?? "Buổi học"} description={`${formatDateTime(session.starts_at)} · ${cleanText(session.location) ?? "Chưa có địa điểm"}`} meta={formatStatus(session.status)} />
              ))}
            </Card>
            <Card title="Điểm danh gần đây">
              {withEmpty(data.attendance, "Chưa có dữ liệu điểm danh.", (record, index) => (
                <Row key={`${record.session}-${index}`} title={cleanText(record.session) ?? "Buổi học"} description={cleanText(record.teacher_note) ?? cleanText(record.class_group) ?? ""} meta={formatAttendance(record.status)} />
              ))}
            </Card>
          </div>
        ) : null}

        {activeTab === "finance" ? (
          <Card title="Học phí & công nợ">
            {withEmpty(data.finance.orders, "Chưa có đơn học phí.", (order) => (
              <Row key={order.order_code} title={order.order_code} description={`Tổng ${formatVnd(order.total_vnd)} · Đã thu ${formatVnd(order.paid_vnd)}`} meta={formatVnd(order.balance_vnd)} />
            ))}
          </Card>
        ) : null}

        {activeTab === "reports" ? (
          <div className="grid gap-5 xl:grid-cols-2">
            {latestProgress ? (
              <section className="rounded-[2rem] border border-blue-100 bg-gradient-to-br from-blue-50 to-white p-6 shadow-sm xl:col-span-2">
                <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                  <div>
                    <div className="text-sm font-black uppercase tracking-[0.2em] text-[#003A99]">Báo cáo tiến bộ</div>
                    <h3 className="mt-2 text-2xl font-black text-slate-950">{cleanText(latestProgress.title)}</h3>
                    <p className="mt-2 text-slate-600">{cleanText(latestProgress.recommendation)}</p>
                  </div>
                  <div className="rounded-3xl bg-white p-5 text-center shadow-sm">
                    <div className="text-4xl font-black text-[#003A99]">{latestProgress.progress_percent}%</div>
                    <div className="mt-1 text-xs font-bold uppercase tracking-[0.2em] text-slate-500">{formatLevel(latestProgress.overall_level)}</div>
                  </div>
                </div>
                <div className="mt-5 grid gap-4 md:grid-cols-2">
                  <MiniBox title="Điểm mạnh" value={cleanText(latestProgress.strengths) ?? "Chưa có nhận xét."} />
                  <MiniBox title="Cần cải thiện" value={cleanText(latestProgress.improvements) ?? "Chưa có nhận xét."} />
                </div>
              </section>
            ) : null}
            <Card title="Kết quả đánh giá">
              {withEmpty(data.assessment_results, "Chưa có kết quả đánh giá.", (result, index) => (
                <Row key={`${result.assessment}-${index}`} title={cleanText(result.assessment) ?? "Bài đánh giá"} description={cleanText(result.feedback) ?? cleanText(result.strengths) ?? "Chưa có nhận xét."} meta={`${result.score ?? "-"} / ${result.max_score}`} />
              ))}
            </Card>
            <Card title="Nhận xét giáo viên">
              {withEmpty(data.teacher_comments, "Chưa có nhận xét giáo viên.", (comment, index) => (
                <Row key={`${comment.title}-${index}`} title={cleanText(comment.title) ?? cleanText(comment.session) ?? "Nhận xét"} description={cleanText(comment.comment) ?? ""} meta={`${formatCommentType(comment.comment_type)}${comment.rating ? ` · ${comment.rating}/5` : ""}`} />
              ))}
            </Card>
          </div>
        ) : null}



        {activeTab === "tests" ? (
          <div className="grid gap-5 xl:grid-cols-[0.9fr_1.1fr]">
            <Card title="Bài kiểm tra khả dụng">
              {withEmpty(data.available_assessments, "Chưa có bài kiểm tra nào được mở.", (assessment) => (
                <div key={assessment.id} className="grid gap-4 py-4 md:grid-cols-[1fr_auto] md:items-center">
                  <div>
                    <div className="text-xs font-black uppercase tracking-[0.18em] text-[#003A99]">{formatAssessmentType(assessment.assessment_type)} · {assessment.question_count} câu</div>
                    <h4 className="mt-1 font-black text-slate-950">{cleanText(assessment.title)}</h4>
                    <p className="mt-1 text-sm leading-6 text-slate-600">{cleanText(assessment.description) ?? `${cleanText(assessment.course) ?? "Khóa học DAYAI"}${assessment.module ? ` · ${cleanText(assessment.module)}` : ""}`}</p>
                  </div>
                  <button type="button" disabled={isQuizSubmitting || assessment.question_count === 0} onClick={() => startQuiz(assessment.id)} className="rounded-full bg-[#003A99] px-5 py-2.5 text-sm font-black text-white hover:bg-[#002B73] disabled:opacity-50">
                    Làm bài
                  </button>
                </div>
              ))}
            </Card>

            <section className="rounded-[2rem] border border-blue-100 bg-white p-6 shadow-sm">
              <h3 className="text-xl font-black">Phòng làm bài</h3>
              {quizMessage ? <div className="mt-4 rounded-2xl bg-blue-50 px-4 py-3 text-sm font-bold text-[#003A99]">{quizMessage}</div> : null}
              {activeQuiz ? (
                <div className="mt-5 space-y-5">
                  <div className="rounded-3xl bg-slate-950 p-5 text-white">
                    <div className="text-xs font-black uppercase tracking-[0.2em] text-blue-200">Attempt {activeQuiz.attempt.attempt_no}</div>
                    <h4 className="mt-2 text-2xl font-black">{cleanText(activeQuiz.assessment.title)}</h4>
                    <p className="mt-2 text-sm text-slate-300">{activeQuiz.assessment.question_count} câu · {activeQuiz.attempt.max_score} điểm</p>
                    {activeQuiz.attempt.status === "submitted" ? (
                      <div className="mt-4 rounded-2xl bg-white/10 px-4 py-3 text-sm font-black text-white">
                        Kết quả: {activeQuiz.attempt.score ?? 0}/{activeQuiz.attempt.max_score} điểm · Đúng {activeQuiz.attempt.correct_count}/{activeQuiz.attempt.question_count}
                      </div>
                    ) : null}
                  </div>

                  {activeQuiz.assessment.questions.map((question, index) => {
                    const isMultiple = question.question_type === "multiple_choice";
                    const selected = quizAnswers[question.assessment_question_id] ?? [];

                    return (
                      <div key={question.assessment_question_id} className="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                        <div className="flex flex-wrap items-center gap-2 text-xs font-black uppercase tracking-[0.16em] text-slate-500">
                          <span>Câu {index + 1}</span>
                          <span>·</span>
                          <span>{formatQuestionType(question.question_type)}</span>
                          <span>·</span>
                          <span>{question.score} điểm</span>
                        </div>
                        <h4 className="mt-3 text-lg font-black text-slate-950">{cleanText(question.prompt)}</h4>

                        {question.options.length ? (
                          <div className="mt-4 grid gap-3">
                            {question.options.map((option) => (
                              <label key={option.id} className={`flex cursor-pointer items-start gap-3 rounded-2xl border px-4 py-3 text-sm transition ${selected.includes(option.id) ? "border-[#003A99] bg-blue-50" : "border-slate-200 bg-white hover:border-blue-200"}`}>
                                <input
                                  type={isMultiple ? "checkbox" : "radio"}
                                  name={question.assessment_question_id}
                                  checked={selected.includes(option.id)}
                                  disabled={activeQuiz.attempt.status === "submitted"}
                                  onChange={() => toggleOption(question.assessment_question_id, option.id, isMultiple)}
                                  className="mt-1"
                                />
                                <span>{cleanText(option.content)}</span>
                              </label>
                            ))}
                          </div>
                        ) : (
                          <textarea
                            disabled={activeQuiz.attempt.status === "submitted"}
                            value={quizTexts[question.assessment_question_id] ?? ""}
                            onChange={(event) => setQuizTexts((current) => ({ ...current, [question.assessment_question_id]: event.target.value }))}
                            placeholder="Nhập câu trả lời của bạn..."
                            className="mt-4 min-h-32 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 outline-none focus:border-[#003A99] focus:ring-4 focus:ring-blue-100"
                          />
                        )}
                      </div>
                    );
                  })}

                  {activeQuiz.attempt.status !== "submitted" ? (
                    <button type="button" disabled={isQuizSubmitting} onClick={submitQuiz} className="rounded-full bg-slate-950 px-7 py-3 font-black text-white hover:bg-slate-800 disabled:opacity-50">
                      {isQuizSubmitting ? "Đang nộp bài..." : "Nộp bài kiểm tra"}
                    </button>
                  ) : null}
                </div>
              ) : (
                <div className="mt-4 rounded-3xl bg-slate-50 p-6 text-sm leading-6 text-slate-600">Chọn một bài kiểm tra bên trái để bắt đầu. Hệ thống sẽ tạo lượt làm bài riêng và lưu kết quả vào database.</div>
              )}
            </section>

            <Card title="Lịch sử làm bài">
              {withEmpty(data.quiz_attempts, "Chưa có lượt làm bài.", (attempt) => (
                <Row key={attempt.attempt_code} title={cleanText(attempt.assessment) ?? "Bài kiểm tra"} description={`Lần ${attempt.attempt_no} · ${formatQuizStatus(attempt.status)}${attempt.submitted_at ? ` · ${formatDateTime(attempt.submitted_at)}` : ""}`} meta={attempt.score !== null ? `${attempt.score}/${attempt.max_score}` : "Đang làm"} />
              ))}
            </Card>
          </div>
        ) : null}

        {activeTab === "notifications" ? (
          <Card title="Thông báo từ trung tâm">
            {withEmpty(data.notifications, "Chưa có thông báo.", (notification) => (
              <Row key={notification.title} title={cleanText(notification.title) ?? "Thông báo"} description={cleanText(notification.body) ?? ""} meta={`${formatNotificationType(notification.notification_type)} · ${formatPriority(notification.priority)}`} />
            ))}
          </Card>
        ) : null}

        {activeTab === "certificates" ? (
          <div className="grid gap-5 xl:grid-cols-2">
            <Card title="Chứng chỉ">
              {withEmpty(data.certificates, "Chưa có chứng chỉ.", (certificate) => (
                <Row key={certificate.certificate_code} title={cleanText(certificate.title) ?? "Chứng chỉ"} description={`${cleanText(certificate.course) ?? "Khóa học"} · Mã xác thực: ${certificate.verification_token}`} meta={`${cleanText(certificate.grade) ?? "Đạt"}${certificate.final_score ? ` · ${certificate.final_score}/10` : ""}`} />
              ))}
            </Card>
            <Card title="Video học liên quan">
              {withEmpty(data.videos, "Chưa có video liên quan.", (video) => (
                <Row key={video.slug} title={cleanText(video.title) ?? "Video bài học"} description={cleanText(video.summary) ?? "Video bài học DAYAI"} meta={`${formatLessonStatus(video.progress_status)} · ${video.progress_percent}%`} />
              ))}
            </Card>
          </div>
        ) : null}
      </main>
    </div>
  );
}

function tabTitle(activeTab: string) {
  return {
    overview: "Tổng quan học tập",
    courses: "Khóa học của tôi",
    schedule: "Lịch học & điểm danh",
    finance: "Học phí & công nợ",
    reports: "Báo cáo tiến bộ",
    tests: "Bài kiểm tra & quiz",
    notifications: "Thông báo",
    certificates: "Chứng chỉ & tài nguyên",
  }[activeTab] ?? "Dashboard học viên";
}

function LessonRow({ lesson }: { lesson: PortalData["lms"]["courses"][number]["modules"][number]["lessons"][number] }) {
  return (
    <div className="grid gap-4 py-4 md:grid-cols-[1fr_auto] md:items-center">
      <div>
        <div className="flex flex-wrap items-center gap-2">
          <div className="font-bold text-slate-950">{cleanText(lesson.title)}</div>
          <span className="rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-black uppercase tracking-[0.12em] text-slate-500">{formatAccessLevel(lesson.access_level)}</span>
          {lesson.resources.length ? <span className="rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-black text-amber-700">{lesson.resources.length} tài liệu</span> : null}
        </div>
        <div className="mt-1 text-sm leading-6 text-slate-600">{cleanText(lesson.summary) ?? "Bài học video thực hành có theo dõi tiến độ."}</div>
        <div className="mt-3 h-2 overflow-hidden rounded-full bg-slate-100">
          <div className="h-full rounded-full bg-[#003A99]" style={{ width: `${Math.min(lesson.progress.progress_percent, 100)}%` }} />
        </div>
      </div>
      <div className="rounded-2xl bg-blue-50 px-4 py-3 text-right">
        <div className="text-sm font-black text-[#003A99]">{formatLessonStatus(lesson.progress.status)}</div>
        <div className="mt-1 text-xs text-slate-500">{lesson.duration_minutes ?? 0} phút · {lesson.progress.progress_percent}%</div>
        <a href={`/portal/bai-hoc/${lesson.slug}`} className="mt-3 inline-flex rounded-full bg-[#003A99] px-4 py-2 text-xs font-black text-white hover:bg-[#002B73]">
          Vào học
        </a>
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

function cleanText(value: string | null | undefined) {
  if (!value) {
    return value;
  }

  if (!/[ÃÂÆÄáºá»]/.test(value)) {
    return value;
  }

  try {
    const bytes = Uint8Array.from(value, (character) => character.charCodeAt(0) & 0xff);
    return new TextDecoder("utf-8", { fatal: true }).decode(bytes);
  } catch {
    return value;
  }
}


function formatAssessmentType(type: string) {
  return {
    entry: "Đầu vào",
    quiz: "Quiz nhanh",
    practice: "Luyện tập",
    project: "Dự án",
    final: "Cuối khóa",
    progress: "Tiến bộ",
  }[type] ?? type;
}

function formatQuestionType(type: string) {
  return {
    single_choice: "Một đáp án",
    multiple_choice: "Nhiều đáp án",
    true_false: "Đúng / Sai",
    short_answer: "Trả lời ngắn",
    essay: "Tự luận",
    project: "Dự án",
  }[type] ?? type;
}

function formatQuizStatus(status: string) {
  return {
    in_progress: "Đang làm",
    submitted: "Đã nộp",
    graded: "Đã chấm",
    cancelled: "Đã hủy",
  }[status] ?? status;
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

function formatPortalRole(role: string) {
  return {
    student: "Học viên",
    guardian: "Phụ huynh",
  }[role] ?? role;
}
