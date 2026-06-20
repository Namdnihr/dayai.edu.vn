import type { Metadata } from "next";
import { CinematicAiLanding, type LandingCourse } from "@/components/landing/cinematic-ai-landing";

const backendUrl = process.env.BACKEND_API_URL ?? "http://localhost:8080/api";

async function getCourse(): Promise<LandingCourse | null> {
  try {
    const response = await fetch(`${backendUrl}/courses/ai-can-ban`, {
      cache: "no-store",
    });

    if (!response.ok) {
      return null;
    }

    const result = await response.json();
    return result.course ?? null;
  } catch {
    return null;
  }
}

export async function generateMetadata(): Promise<Metadata> {
  const course = await getCourse();
  const title = course?.name ? `${course.name} | DAYAI` : "Khóa học AI Căn Bản | DAYAI";
  const description =
    course?.short_description ??
    "Landing page khóa học AI Căn Bản cho người mới: học thử, lộ trình thực hành và đăng ký tư vấn tại DAYAI.";

  return {
    title: course?.seo?.title ?? title,
    description: course?.seo?.description ?? description,
    alternates: {
      canonical: course?.seo?.canonical_url ?? "/khoa-hoc/ai-can-ban",
    },
    openGraph: {
      title,
      description,
      url: "/khoa-hoc/ai-can-ban",
    },
  };
}

export default async function AiFundamentalsPage() {
  const course = await getCourse();

  return <CinematicAiLanding course={course ?? undefined} />;
}
