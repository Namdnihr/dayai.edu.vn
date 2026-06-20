"use client";

import { FormEvent, useState } from "react";

type AffiliatePortalData = {
  partner: {
    name: string;
    code: string;
    partner_type: string;
    default_commission_percent: number;
    status: string;
  };
  summary: {
    link_count: number;
    click_count: number;
    lead_count: number;
    converted_lead_count: number;
    commission_count: number;
    pending_commission_vnd: number;
    approved_commission_vnd: number;
    paid_commission_vnd: number;
    total_commission_vnd: number;
  };
  links: Array<{
    code: string;
    campaign: string | null;
    target_url: string | null;
    commission_percent: number;
    click_count: number;
    commission_vnd: number;
  }>;
  recent_leads: Array<{
    full_name: string;
    phone: string | null;
    status: string;
    temperature: string | null;
    course_slug: string | null;
    referral_code: string | null;
    created_at: string | null;
  }>;
  recent_commissions: Array<{
    order_code: string | null;
    lead_name: string | null;
    link_code: string | null;
    status: string;
    order_total_vnd: number;
    commission_percent: number;
    commission_vnd: number;
    approved_at: string | null;
    paid_at: string | null;
  }>;
};

export function AffiliatePortalLookup() {
  const [data, setData] = useState<AffiliatePortalData | null>(null);
  const [message, setMessage] = useState("");
  const [isSubmitting, setIsSubmitting] = useState(false);

  async function handleSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setIsSubmitting(true);
    setMessage("");
    setData(null);

    const formData = new FormData(event.currentTarget);

    try {
      const response = await fetch("/api/affiliate-portal/lookup", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(Object.fromEntries(formData.entries())),
      });

      const result = await response.json();

      if (!response.ok) {
        throw new Error(result.message ?? "Không tra cứu được dữ liệu đối tác.");
      }

      setData(result as AffiliatePortalData);
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
            <span className="text-sm font-bold text-slate-700">Mã đối tác</span>
            <input name="partner_code" required defaultValue="PARTNER-A" className="rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100" />
          </label>
          <label className="grid gap-2">
            <span className="text-sm font-bold text-slate-700">Mã link chiến dịch</span>
            <input name="link_code" defaultValue="REF-A" className="rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100" />
          </label>
        </div>
        <p className="mt-3 text-sm leading-6 text-slate-500">
          Có thể để trống mã link để xem toàn bộ hiệu quả của đối tác.
        </p>
        <button disabled={isSubmitting} className="mt-5 rounded-full bg-[#003A99] px-7 py-3 font-black text-white shadow-lg shadow-blue-600/20 hover:bg-[#002B73] disabled:opacity-60">
          {isSubmitting ? "Đang tra cứu..." : "Xem hiệu quả affiliate"}
        </button>
        {message ? <p className="mt-4 text-sm font-semibold text-red-600">{message}</p> : null}
      </form>

      {data ? <AffiliatePortalResult data={data} /> : null}
    </div>
  );
}

function AffiliatePortalResult({ data }: { data: AffiliatePortalData }) {
  const conversionRate = data.summary.lead_count > 0
    ? Math.round((data.summary.converted_lead_count / data.summary.lead_count) * 100)
    : 0;

  return (
    <div className="space-y-5">
      <section className="overflow-hidden rounded-[2rem] bg-slate-950 p-6 text-white">
        <div className="flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
          <div>
            <div className="text-sm font-bold uppercase tracking-[0.2em] text-[#00AEEF]">Affiliate Partner</div>
            <h2 className="mt-2 text-3xl font-black">{data.partner.name}</h2>
            <p className="mt-2 text-slate-300">Mã {data.partner.code} · Hoa hồng mặc định {data.partner.default_commission_percent}%</p>
          </div>
          <div className="rounded-full border border-white/15 bg-white/10 px-4 py-2 text-sm font-bold">
            {formatPartnerType(data.partner.partner_type)}
          </div>
        </div>
      </section>

      <div className="grid gap-5 md:grid-cols-4">
        <Metric title="Link đang chạy" value={String(data.summary.link_count)} />
        <Metric title="Lượt click" value={String(data.summary.click_count)} />
        <Metric title="Lead ghi nhận" value={String(data.summary.lead_count)} />
        <Metric title="Tỷ lệ chuyển đổi" value={`${conversionRate}%`} />
      </div>

      <div className="grid gap-5 md:grid-cols-4">
        <Metric title="Hoa hồng chờ duyệt" value={formatVnd(data.summary.pending_commission_vnd)} tone="gold" />
        <Metric title="Hoa hồng đã duyệt" value={formatVnd(data.summary.approved_commission_vnd)} />
        <Metric title="Hoa hồng đã trả" value={formatVnd(data.summary.paid_commission_vnd)} />
        <Metric title="Tổng hoa hồng" value={formatVnd(data.summary.total_commission_vnd)} tone="blue" />
      </div>

      <section className="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
        <h3 className="text-xl font-black">Link & chiến dịch</h3>
        <div className="mt-4 grid gap-4">
          {data.links.map((link) => (
            <div key={link.code} className="rounded-3xl border border-slate-100 bg-slate-50 p-5">
              <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                  <div className="text-lg font-black">{link.code}</div>
                  <div className="mt-1 text-sm text-slate-500">{link.campaign ?? "Chiến dịch chung"} · Hoa hồng {link.commission_percent}%</div>
                  {link.target_url ? <div className="mt-2 break-all text-xs text-slate-400">{link.target_url}</div> : null}
                </div>
                <div className="grid grid-cols-2 gap-3 text-center">
                  <Info label="Click" value={String(link.click_count)} />
                  <Info label="Hoa hồng" value={formatVnd(link.commission_vnd)} />
                </div>
              </div>
            </div>
          ))}
          {data.links.length === 0 ? <p className="text-sm text-slate-500">Chưa có link đang hoạt động.</p> : null}
        </div>
      </section>

      <section className="grid gap-5 lg:grid-cols-2">
        <div className="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
          <h3 className="text-xl font-black">Lead gần đây</h3>
          <div className="mt-4 divide-y divide-slate-100">
            {data.recent_leads.map((lead) => (
              <div key={`${lead.phone}-${lead.created_at}`} className="py-4">
                <div className="flex items-start justify-between gap-4">
                  <div>
                    <div className="font-bold">{lead.full_name}</div>
                    <div className="mt-1 text-sm text-slate-500">{lead.phone ?? "Ẩn SĐT"} · {lead.course_slug ?? "Chưa rõ khóa"}</div>
                  </div>
                  <span className="rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">{formatLeadStatus(lead.status)}</span>
                </div>
              </div>
            ))}
            {data.recent_leads.length === 0 ? <p className="py-4 text-sm text-slate-500">Chưa có lead affiliate.</p> : null}
          </div>
        </div>

        <div className="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
          <h3 className="text-xl font-black">Hoa hồng gần đây</h3>
          <div className="mt-4 divide-y divide-slate-100">
            {data.recent_commissions.map((commission) => (
              <div key={`${commission.order_code}-${commission.link_code}`} className="py-4">
                <div className="flex items-start justify-between gap-4">
                  <div>
                    <div className="font-bold">{commission.order_code ?? "Đơn chưa rõ"}</div>
                    <div className="mt-1 text-sm text-slate-500">{commission.lead_name ?? "Lead"} · {commission.commission_percent}% của {formatVnd(commission.order_total_vnd)}</div>
                  </div>
                  <div className="text-right">
                    <div className="font-black text-[#003A99]">{formatVnd(commission.commission_vnd)}</div>
                    <div className="mt-1 text-xs font-bold text-slate-400">{formatCommissionStatus(commission.status)}</div>
                  </div>
                </div>
              </div>
            ))}
            {data.recent_commissions.length === 0 ? <p className="py-4 text-sm text-slate-500">Chưa phát sinh hoa hồng.</p> : null}
          </div>
        </div>
      </section>
    </div>
  );
}

function Metric({ title, value, tone = "default" }: { title: string; value: string; tone?: "default" | "blue" | "gold" }) {
  const toneClass = {
    default: "text-slate-950",
    blue: "text-[#003A99]",
    gold: "text-[#B98100]",
  }[tone];

  return (
    <div className="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
      <div className="text-sm text-slate-500">{title}</div>
      <div className={`mt-2 text-2xl font-black ${toneClass}`}>{value}</div>
    </div>
  );
}

function Info({ label, value }: { label: string; value: string }) {
  return (
    <div className="rounded-2xl bg-white px-4 py-3">
      <div className="text-xs font-bold uppercase tracking-wide text-slate-400">{label}</div>
      <div className="mt-1 font-black text-slate-800">{value}</div>
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

function formatPartnerType(type: string) {
  return {
    individual: "Cá nhân",
    creator: "Creator",
    agency: "Agency",
    business: "Doanh nghiệp",
  }[type] ?? type;
}

function formatLeadStatus(status: string) {
  return {
    new: "Mới",
    contacted: "Đã liên hệ",
    trial_requested: "Đăng ký học thử",
    registered: "Đã đăng ký",
    lost: "Không phù hợp",
  }[status] ?? status;
}

function formatCommissionStatus(status: string) {
  return {
    pending: "Chờ duyệt",
    approved: "Đã duyệt",
    paid: "Đã trả",
    rejected: "Từ chối",
  }[status] ?? status;
}
