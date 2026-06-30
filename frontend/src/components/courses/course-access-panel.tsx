"use client";

import Link from "next/link";
import { FormEvent, useState } from "react";
import { CatalogCourse } from "@/lib/course-catalog";

type AccessResult = {
  message?: string;
  access_status?: "active" | "payment_required";
  flow?: "free_funnel" | "paid";
  order_code?: string;
  invoice_code?: string;
  student_code?: string;
  phone?: string;
  email?: string;
  amount_vnd?: number;
  course_slug?: string;
  portal_url?: string;
};

type AccountUser = {
  full_name: string;
  email: string;
  phone?: string | null;
  email_verified: boolean;
  student_code?: string | null;
};

type AccountAuthResult = {
  message?: string;
  status?: "verification_required" | "verified" | "authenticated" | "already_verified";
  email?: string;
  demo_verification_token?: string | null;
  user?: AccountUser;
};

type CourseAccessPanelProps = {
  course: CatalogCourse;
  variant?: "compact" | "sidebar" | "hero";
  hideTrigger?: boolean;
  open?: boolean;
  onOpenChange?: (open: boolean) => void;
};

const initialForm = {
  full_name: "",
  phone: "",
  email: "",
  learning_goal: "",
};

const initialAccountForm = {
  full_name: "",
  email: "",
  phone: "",
  password: "",
  password_confirmation: "",
};

export function CourseAccessPanel({ course, variant = "sidebar", hideTrigger = false, open: controlledOpen, onOpenChange }: CourseAccessPanelProps) {
  const [internalOpen, setInternalOpen] = useState(false);
  const [form, setForm] = useState(initialForm);
  const [accountForm, setAccountForm] = useState(initialAccountForm);
  const [authMode, setAuthMode] = useState<"login" | "register">("login");
  const [submitting, setSubmitting] = useState(false);
  const [paying, setPaying] = useState(false);
  const [error, setError] = useState("");
  const [accountMessage, setAccountMessage] = useState("");
  const [pendingVerification, setPendingVerification] = useState<AccountAuthResult | null>(null);
  const [result, setResult] = useState<AccessResult | null>(null);
  const isOpen = controlledOpen ?? internalOpen;

  function setPanelOpen(nextOpen: boolean) {
    if (onOpenChange) {
      onOpenChange(nextOpen);
    } else {
      setInternalOpen(nextOpen);
    }
  }

  if (course.accessType === "consultation") {
    if (hideTrigger) {
      return null;
    }

    return (
      <div className={variant === "sidebar" ? "grid gap-3" : ""}>
        <Link href={`/dang-ky-tu-van/?course=${course.slug}`} className={`dayai-btn dayai-btn-primary ${buttonClassName(variant)}`}>
          {course.ctaLabel}
        </Link>
        {variant === "sidebar" ? (
          <p className="text-center text-xs font-semibold leading-5 text-[var(--dayai-text-subtle)]">{course.flowNote}</p>
        ) : null}
      </div>
    );
  }

  const submitLabel = course.accessType === "paid" ? "Tạo đơn thanh toán" : "Mở quyền học";
  const endpoint = course.accessType === "paid" ? "/api/course-access/checkout" : "/api/course-access/free-enroll";

  async function activateFreeCourse(user: AccountUser) {
    setSubmitting(true);
    setError("");

    const response = await fetch("/api/course-access/free-enroll", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
      body: JSON.stringify({
        flow: "free_funnel",
        course_slug: course.slug,
        course_title: course.title,
        course_description: course.description,
        price_vnd: course.priceAmountVnd,
        page_url: window.location.href,
        full_name: user.full_name,
        email: user.email,
        phone: user.phone ?? undefined,
      }),
    });

    const data = (await response.json().catch(() => null)) as AccessResult | null;
    setSubmitting(false);

    if (!response.ok || !data) {
      setError(data?.message ?? "Chưa mở được quyền học cho tài khoản này.");
      return;
    }

    setResult(data);
  }

  async function handleRegister(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setSubmitting(true);
    setError("");
    setAccountMessage("");
    setPendingVerification(null);

    const response = await fetch("/api/course-auth/register", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
      body: JSON.stringify(accountForm),
    });

    const data = (await response.json().catch(() => null)) as AccountAuthResult | null;
    setSubmitting(false);

    if (!response.ok || !data) {
      setError(data?.message ?? "Chưa đăng ký được tài khoản.");
      return;
    }

    if (data.status === "already_verified" && data.user) {
      setAuthMode("login");
      setAccountMessage("Email này đã xác nhận. Bạn đăng nhập để mở khóa học.");
      return;
    }

    setPendingVerification(data);
    setAccountMessage(data.message ?? "Vui lòng xác nhận email để kích hoạt tài khoản.");
  }

  async function handleVerifyEmail() {
    const email = pendingVerification?.email ?? accountForm.email;
    const token = pendingVerification?.demo_verification_token;

    if (!email || !token) {
      setError("Chưa có mã xác nhận email demo.");
      return;
    }

    setSubmitting(true);
    setError("");

    const response = await fetch("/api/course-auth/verify-email", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
      body: JSON.stringify({ email, token }),
    });

    const data = (await response.json().catch(() => null)) as AccountAuthResult | null;

    if (!response.ok || !data?.user) {
      setSubmitting(false);
      setError(data?.message ?? "Chưa xác nhận được email.");
      return;
    }

    setPendingVerification(null);
    setAccountMessage(data.message ?? "Email đã xác nhận.");
    await activateFreeCourse(data.user);
  }

  async function handleLogin(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setSubmitting(true);
    setError("");
    setAccountMessage("");

    const response = await fetch("/api/course-auth/login", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
      body: JSON.stringify({
        email: accountForm.email,
        password: accountForm.password,
      }),
    });

    const data = (await response.json().catch(() => null)) as AccountAuthResult | null;

    if (!response.ok || !data?.user) {
      setSubmitting(false);
      setError(data?.message ?? "Chưa đăng nhập được.");
      return;
    }

    setAccountMessage(data.message ?? "Đăng nhập thành công.");
    await activateFreeCourse(data.user);
  }

  async function handleSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setSubmitting(true);
    setError("");

    const response = await fetch(endpoint, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
      body: JSON.stringify({
        flow: course.accessType,
        course_slug: course.slug,
        course_title: course.title,
        course_description: course.description,
        price_vnd: course.priceAmountVnd,
        page_url: window.location.href,
        ...form,
      }),
    });

    const data = (await response.json().catch(() => null)) as AccessResult | null;
    setSubmitting(false);

    if (!response.ok || !data) {
      setError(data?.message ?? "Chưa xử lý được yêu cầu. Bạn thử lại giúp mình.");
      return;
    }

    setResult(data);
  }

  async function handleMarkPaid() {
    if (!result?.order_code) {
      return;
    }

    setPaying(true);
    setError("");

    const response = await fetch(`/api/course-access/checkout/${encodeURIComponent(result.order_code)}/mark-paid`, {
      method: "POST",
      headers: {
        Accept: "application/json",
      },
    });
    const data = (await response.json().catch(() => null)) as AccessResult | null;
    setPaying(false);

    if (!response.ok || !data) {
      setError(data?.message ?? "Chưa xác nhận được thanh toán test.");
      return;
    }

    setResult(data);
  }

  return (
    <>
      {!hideTrigger ? (
        <div className={variant === "sidebar" ? "grid gap-3" : ""}>
          <button
            type="button"
            onClick={() => {
              setPanelOpen(true);
              setError("");
            }}
            className={`dayai-btn dayai-btn-primary ${buttonClassName(variant)}`}
          >
            {course.ctaLabel}
          </button>
          {variant === "sidebar" ? (
            <p className="text-center text-xs font-semibold leading-5 text-[var(--dayai-text-subtle)]">{course.flowNote}</p>
          ) : null}
        </div>
      ) : null}

      {isOpen ? (
        <div data-course-access-modal={course.slug} className="fixed inset-0 z-[80] overflow-y-auto bg-slate-950/75 px-4 py-6 backdrop-blur-md">
          <div className="mx-auto flex min-h-full w-full max-w-xl items-center">
          <div className="max-h-[92vh] w-full max-w-xl overflow-y-auto rounded-[var(--dayai-radius-2xl)] border border-[var(--dayai-border)] bg-white p-5 shadow-[var(--dayai-shadow-md)] sm:p-6">
            <div className="flex items-start justify-between gap-4">
              <div>
                <div className="dayai-kicker">{course.accessType === "paid" ? "Checkout" : "Free funnel"}</div>
                <h2 className="mt-2 text-2xl font-black leading-tight">{course.title}</h2>
                <p className="mt-2 text-sm font-semibold leading-6 text-[var(--dayai-text-muted)]">{course.flowNote}</p>
              </div>
              <button
                type="button"
                onClick={() => setPanelOpen(false)}
                className="grid size-10 shrink-0 place-items-center rounded-full border border-[var(--dayai-border)] text-lg font-black text-[var(--dayai-text-muted)] transition hover:border-[var(--dayai-primary)] hover:text-[var(--dayai-primary)]"
                aria-label="Đóng"
              >
                ×
              </button>
            </div>

            {result?.access_status === "active" ? (
              <div className="mt-6 rounded-[var(--dayai-radius-xl)] border border-emerald-200 bg-emerald-50 p-5">
                <div className="text-lg font-black text-emerald-900">Đã mở quyền học</div>
                <p className="mt-2 text-sm font-semibold leading-6 text-emerald-800">{result.message}</p>
                <div className="mt-4 grid gap-2 rounded-[var(--dayai-radius-lg)] bg-white p-4 text-sm">
                  <div className="flex justify-between gap-4">
                    <span className="text-[var(--dayai-text-muted)]">{result.phone ? "SĐT đăng nhập" : "Email đăng nhập"}</span>
                    <b>{result.phone ?? result.email}</b>
                  </div>
                  <div className="flex justify-between gap-4">
                    <span className="text-[var(--dayai-text-muted)]">Mã học viên</span>
                    <b>{result.student_code}</b>
                  </div>
                </div>
                <Link href={portalHref(result)} className="dayai-btn dayai-btn-primary mt-5 w-full">
                  Vào portal học viên
                </Link>
                <p className="mt-3 text-xs font-semibold leading-5 text-emerald-900">
                  Ở portal, nhập thông tin học viên ở trên rồi bấm lấy mã OTP demo.
                </p>
              </div>
            ) : result?.access_status === "payment_required" ? (
              <div className="mt-6 rounded-[var(--dayai-radius-xl)] border border-amber-200 bg-amber-50 p-5">
                <div className="text-lg font-black text-amber-950">Đơn thanh toán đã tạo</div>
                <p className="mt-2 text-sm font-semibold leading-6 text-amber-900">{result.message}</p>
                <div className="mt-4 grid gap-2 rounded-[var(--dayai-radius-lg)] bg-white p-4 text-sm">
                  <div className="flex justify-between gap-4">
                    <span className="text-[var(--dayai-text-muted)]">Mã đơn</span>
                    <b>{result.order_code}</b>
                  </div>
                  <div className="flex justify-between gap-4">
                    <span className="text-[var(--dayai-text-muted)]">Số tiền</span>
                    <b>{formatCurrency(result.amount_vnd ?? course.priceAmountVnd)}</b>
                  </div>
                </div>
                <button type="button" onClick={handleMarkPaid} disabled={paying} className="dayai-btn dayai-btn-primary mt-5 w-full disabled:opacity-60">
                  {paying ? "Đang xác nhận..." : "Test: xác nhận đã thanh toán"}
                </button>
              </div>
            ) : course.accessType === "free_funnel" ? (
              <div className="mt-6 grid gap-4">
                <div className="rounded-[var(--dayai-radius-xl)] border border-[var(--dayai-border)] bg-[var(--dayai-bg-subtle)] p-4">
                  <div className="text-sm font-black text-[var(--dayai-text)]">Đăng nhập để học</div>
                  <p className="mt-2 text-sm leading-6 text-[var(--dayai-text-muted)]">
                    Khóa miễn phí sẽ mở sau khi tài khoản đã xác nhận email. Đây là tài khoản học viên, không phải form lead tư vấn.
                  </p>
                </div>

                <button
                  type="button"
                  disabled
                  className="rounded-[var(--dayai-radius-lg)] border border-[var(--dayai-border)] bg-white px-4 py-3 text-sm font-black text-[var(--dayai-text-subtle)] opacity-70"
                >
                  Đăng nhập bằng Google - chờ cấu hình OAuth
                </button>

                <div className="grid grid-cols-2 gap-2 rounded-[var(--dayai-radius-lg)] bg-[var(--dayai-bg-subtle)] p-1">
                  {[
                    ["login", "Đăng nhập"],
                    ["register", "Đăng ký"],
                  ].map(([mode, label]) => (
                    <button
                      key={mode}
                      type="button"
                      onClick={() => {
                        setAuthMode(mode as "login" | "register");
                        setError("");
                        setAccountMessage("");
                      }}
                      className={`rounded-[var(--dayai-radius-md)] px-4 py-2 text-sm font-black transition ${
                        authMode === mode ? "bg-white text-[var(--dayai-primary)] shadow-[var(--dayai-shadow-xs)]" : "text-[var(--dayai-text-muted)]"
                      }`}
                    >
                      {label}
                    </button>
                  ))}
                </div>

                {authMode === "login" ? (
                  <form onSubmit={handleLogin} className="grid gap-4">
                    <AccountField
                      label="Email"
                      type="email"
                      value={accountForm.email}
                      onChange={(value) => setAccountForm((current) => ({ ...current, email: value }))}
                      placeholder="ban@example.com"
                    />
                    <AccountField
                      label="Mật khẩu"
                      type="password"
                      value={accountForm.password}
                      onChange={(value) => setAccountForm((current) => ({ ...current, password: value }))}
                      placeholder="Tối thiểu 8 ký tự"
                    />
                    <button type="submit" disabled={submitting} className="dayai-btn dayai-btn-primary w-full disabled:opacity-60">
                      {submitting ? "Đang xử lý..." : "Đăng nhập & mở khóa học"}
                    </button>
                  </form>
                ) : (
                  <form onSubmit={handleRegister} className="grid gap-4">
                    <AccountField
                      label="Họ tên"
                      value={accountForm.full_name}
                      onChange={(value) => setAccountForm((current) => ({ ...current, full_name: value }))}
                      placeholder="Nguyễn Minh Anh"
                    />
                    <div className="grid gap-4 sm:grid-cols-2">
                      <AccountField
                        label="Email"
                        type="email"
                        value={accountForm.email}
                        onChange={(value) => setAccountForm((current) => ({ ...current, email: value }))}
                        placeholder="ban@example.com"
                      />
                      <AccountField
                        label="SĐT"
                        required={false}
                        value={accountForm.phone}
                        onChange={(value) => setAccountForm((current) => ({ ...current, phone: value }))}
                        placeholder="Tùy chọn"
                      />
                    </div>
                    <div className="grid gap-4 sm:grid-cols-2">
                      <AccountField
                        label="Mật khẩu"
                        type="password"
                        value={accountForm.password}
                        onChange={(value) => setAccountForm((current) => ({ ...current, password: value }))}
                        placeholder="Tối thiểu 8 ký tự"
                      />
                      <AccountField
                        label="Nhập lại mật khẩu"
                        type="password"
                        value={accountForm.password_confirmation}
                        onChange={(value) => setAccountForm((current) => ({ ...current, password_confirmation: value }))}
                        placeholder="Nhập lại mật khẩu"
                      />
                    </div>
                    <button type="submit" disabled={submitting} className="dayai-btn dayai-btn-primary w-full disabled:opacity-60">
                      {submitting ? "Đang tạo..." : "Tạo tài khoản"}
                    </button>
                  </form>
                )}

                {pendingVerification?.demo_verification_token ? (
                  <div className="rounded-[var(--dayai-radius-xl)] border border-amber-200 bg-amber-50 p-4">
                    <div className="text-sm font-black text-amber-950">Email verification demo</div>
                    <p className="mt-2 text-sm font-semibold leading-6 text-amber-900">
                      Local chưa gửi email thật, nên hệ thống trả mã xác nhận demo để test luồng kích hoạt.
                    </p>
                    <button type="button" onClick={handleVerifyEmail} disabled={submitting} className="dayai-btn dayai-btn-primary mt-4 w-full disabled:opacity-60">
                      {submitting ? "Đang xác nhận..." : "Xác nhận email demo & mở khóa học"}
                    </button>
                  </div>
                ) : null}

                {accountMessage ? <div className="rounded-[var(--dayai-radius-lg)] bg-blue-50 p-3 text-sm font-bold text-[var(--dayai-primary)]">{accountMessage}</div> : null}
                {error ? <div className="rounded-[var(--dayai-radius-lg)] border border-red-200 bg-red-50 p-3 text-sm font-bold text-red-700">{error}</div> : null}
              </div>
            ) : (
              <form onSubmit={handleSubmit} className="mt-6 grid gap-4">
                <label className="grid gap-2">
                  <span className="text-sm font-black">Họ và tên</span>
                  <input
                    required
                    value={form.full_name}
                    onChange={(event) => setForm((current) => ({ ...current, full_name: event.target.value }))}
                    className="form-control"
                    placeholder="Nguyễn Văn A"
                  />
                </label>
                <div className="grid gap-4 sm:grid-cols-2">
                  <label className="grid gap-2">
                    <span className="text-sm font-black">Số điện thoại</span>
                    <input
                      required
                      value={form.phone}
                      onChange={(event) => setForm((current) => ({ ...current, phone: event.target.value }))}
                      className="form-control"
                      placeholder="090..."
                    />
                  </label>
                  <label className="grid gap-2">
                    <span className="text-sm font-black">Email</span>
                    <input
                      type="email"
                      value={form.email}
                      onChange={(event) => setForm((current) => ({ ...current, email: event.target.value }))}
                      className="form-control"
                      placeholder="email@example.com"
                    />
                  </label>
                </div>
                <label className="grid gap-2">
                  <span className="text-sm font-black">Mục tiêu học</span>
                  <textarea
                    value={form.learning_goal}
                    onChange={(event) => setForm((current) => ({ ...current, learning_goal: event.target.value }))}
                    className="form-control min-h-24"
                    placeholder="Tôi muốn dùng AI cho học tập, công việc..."
                  />
                </label>
                <div className="rounded-[var(--dayai-radius-lg)] bg-[var(--dayai-bg-subtle)] p-4 text-sm font-semibold text-[var(--dayai-text-muted)]">
                  <div className="flex items-center justify-between gap-4">
                    <span>Học phí</span>
                    <b className="text-[var(--dayai-text)]">{course.price}</b>
                  </div>
                </div>
                {error ? <div className="rounded-[var(--dayai-radius-lg)] border border-red-200 bg-red-50 p-3 text-sm font-bold text-red-700">{error}</div> : null}
                <button type="submit" disabled={submitting} className="dayai-btn dayai-btn-primary w-full disabled:opacity-60">
                  {submitting ? "Đang xử lý..." : submitLabel}
                </button>
              </form>
            )}
          </div>
          </div>
        </div>
      ) : null}
    </>
  );
}

function AccountField({
  label,
  value,
  onChange,
  placeholder,
  type = "text",
  required = true,
}: {
  label: string;
  value: string;
  onChange: (value: string) => void;
  placeholder: string;
  type?: string;
  required?: boolean;
}) {
  return (
    <label className="grid gap-2">
      <span className="text-sm font-black">{label}</span>
      <input
        required={required}
        type={type}
        value={value}
        onChange={(event) => onChange(event.target.value)}
        className="form-control"
        placeholder={placeholder}
      />
    </label>
  );
}

function formatCurrency(value: number) {
  return new Intl.NumberFormat("vi-VN", {
    style: "currency",
    currency: "VND",
    maximumFractionDigits: 0,
  }).format(value);
}

function buttonClassName(variant: CourseAccessPanelProps["variant"]) {
  if (variant === "sidebar") {
    return "w-full";
  }

  if (variant === "compact") {
    return "px-4 py-3 text-xs";
  }

  return "";
}

function portalHref(result: AccessResult) {
  const portalUrl = result.portal_url ?? "/portal";

  if (!result.phone || !result.student_code) {
    return portalUrl;
  }

  const params = new URLSearchParams({
    phone: result.phone,
    student_code: result.student_code,
    tab: "courses",
  });

  return `${portalUrl}?${params.toString()}`;
}
