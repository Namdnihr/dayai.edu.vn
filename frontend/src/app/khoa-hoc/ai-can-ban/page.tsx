import type { Metadata } from "next";
import { CinematicAiLanding } from "@/components/landing/cinematic-ai-landing";

export const metadata: Metadata = {
  title: "Khóa học AI Căn Bản",
  description:
    "Landing page khóa học AI Căn Bản cho người mới: học thử, lộ trình thực hành và đăng ký tư vấn tại DAYAI.",
  alternates: {
    canonical: "/khoa-hoc/ai-can-ban",
  },
  openGraph: {
    title: "Khóa học AI Căn Bản | DAYAI",
    description:
      "Khóa học AI thực chiến cho phụ huynh, sinh viên, người đi làm, chủ doanh nghiệp và đội ngũ công ty.",
    url: "/khoa-hoc/ai-can-ban",
  },
};

export default function AiFundamentalsPage() {
  return <CinematicAiLanding />;
}
