import type {
  ButtonHTMLAttributes,
  HTMLAttributes,
  InputHTMLAttributes,
  ReactNode,
} from "react";

type ClassValue = string | false | null | undefined;

export function cx(...classes: ClassValue[]) {
  return classes.filter(Boolean).join(" ");
}

export type DayaiButtonVariant = "primary" | "secondary" | "dark" | "danger" | "ghost";
export type DayaiButtonSize = "sm" | "md";

export function dayaiButtonClasses({
  variant = "primary",
  size = "md",
  block = false,
  className,
}: {
  variant?: DayaiButtonVariant;
  size?: DayaiButtonSize;
  block?: boolean;
  className?: string;
} = {}) {
  return cx(
    "dayai-btn",
    `dayai-btn-${variant}`,
    size === "sm" && "dayai-btn-sm",
    block && "dayai-btn-block",
    className,
  );
}

export function DayaiButton({
  variant = "primary",
  size = "md",
  block = false,
  className,
  type = "button",
  ...props
}: ButtonHTMLAttributes<HTMLButtonElement> & {
  variant?: DayaiButtonVariant;
  size?: DayaiButtonSize;
  block?: boolean;
}) {
  return (
    <button
      type={type}
      className={dayaiButtonClasses({ variant, size, block, className })}
      {...props}
    />
  );
}

export type DayaiTone = "neutral" | "info" | "success" | "warning" | "danger";

export function DayaiBadge({
  tone = "neutral",
  className,
  ...props
}: HTMLAttributes<HTMLSpanElement> & { tone?: DayaiTone }) {
  return <span className={cx("dayai-badge", `dayai-badge-${tone}`, className)} {...props} />;
}

export function DayaiAlert({
  tone = "info",
  className,
  ...props
}: HTMLAttributes<HTMLDivElement> & { tone?: Exclude<DayaiTone, "neutral"> }) {
  return (
    <div
      role={tone === "danger" ? "alert" : "status"}
      aria-live={tone === "danger" ? "assertive" : "polite"}
      className={cx("dayai-alert", `dayai-alert-${tone}`, className)}
      {...props}
    />
  );
}

export function DayaiInput({ className, ...props }: InputHTMLAttributes<HTMLInputElement>) {
  return <input className={cx("form-control", className)} {...props} />;
}

export function DayaiField({
  label,
  hint,
  className,
  children,
}: {
  label: ReactNode;
  hint?: ReactNode;
  className?: string;
  children: ReactNode;
}) {
  return (
    <label className={cx("dayai-field", className)}>
      <span className="dayai-label">{label}</span>
      {children}
      {hint ? <span className="dayai-field-hint">{hint}</span> : null}
    </label>
  );
}

export function DayaiPanel({
  title,
  eyebrow,
  action,
  children,
  className,
  contentClassName,
}: {
  title?: ReactNode;
  eyebrow?: ReactNode;
  action?: ReactNode;
  children: ReactNode;
  className?: string;
  contentClassName?: string;
}) {
  return (
    <section className={cx("dayai-panel", className)}>
      {title || eyebrow || action ? (
        <div className="dayai-panel-header">
          <div>
            {eyebrow ? <div className="dayai-kicker">{eyebrow}</div> : null}
            {title ? <h3 className="dayai-panel-title">{title}</h3> : null}
          </div>
          {action ? <div className="dayai-panel-action">{action}</div> : null}
        </div>
      ) : null}
      <div className={cx(title || eyebrow || action ? "dayai-panel-content" : undefined, contentClassName)}>
        {children}
      </div>
    </section>
  );
}

export function DayaiStat({
  label,
  value,
  tone = "info",
  className,
}: {
  label: ReactNode;
  value: ReactNode;
  tone?: "info" | "success" | "warning" | "danger";
  className?: string;
}) {
  return (
    <div className={cx("dayai-stat", `dayai-stat-${tone}`, className)}>
      <span className="dayai-stat-marker" aria-hidden="true" />
      <div className="dayai-stat-label">{label}</div>
      <div className="dayai-stat-value">{value}</div>
    </div>
  );
}

export function DayaiEmptyState({
  title,
  description,
  action,
  compact = false,
  className,
}: {
  title: ReactNode;
  description?: ReactNode;
  action?: ReactNode;
  compact?: boolean;
  className?: string;
}) {
  return (
    <div className={cx("dayai-empty", compact && "dayai-empty-compact", className)}>
      <div className="dayai-empty-title">{title}</div>
      {description ? <div className="dayai-empty-description">{description}</div> : null}
      {action ? <div className="dayai-empty-action">{action}</div> : null}
    </div>
  );
}

export function DayaiTabButton({
  active = false,
  badge,
  className,
  children,
  ...props
}: ButtonHTMLAttributes<HTMLButtonElement> & {
  active?: boolean;
  badge?: ReactNode;
}) {
  return (
    <button
      type="button"
      role="tab"
      aria-selected={active}
      className={cx("dayai-tab", active && "dayai-tab-active", className)}
      {...props}
    >
      <span>{children}</span>
      {badge ? (
        <DayaiBadge tone={active ? "info" : "neutral"} className="dayai-tab-badge">
          {badge}
        </DayaiBadge>
      ) : null}
    </button>
  );
}

export function DayaiFilterBar({ className, ...props }: HTMLAttributes<HTMLDivElement>) {
  return <div className={cx("dayai-filter-bar", className)} {...props} />;
}

export function DayaiTableShell({ className, ...props }: HTMLAttributes<HTMLDivElement>) {
  return <div className={cx("dayai-table-shell", className)} {...props} />;
}
