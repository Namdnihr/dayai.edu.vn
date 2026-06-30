import { CourseCatalogPage } from "@/components/courses/course-catalog-page";
import { CourseDetailPage } from "@/components/courses/course-detail-page";
import { SeoLandingPage } from "@/components/seo/seo-landing-page";
import { findCatalogCourse } from "@/lib/course-catalog";
import {
  generateSectionMetadata,
  generateSectionStaticParams,
  getSectionPage,
  SeoRouteParams,
} from "@/lib/seo-route";

const section = "khoa-hoc";

export function generateStaticParams() {
  return generateSectionStaticParams(section);
}

export async function generateMetadata({ params }: { params: Promise<SeoRouteParams> }) {
  return generateSectionMetadata(section, params);
}

export default async function CoursesPage({ params }: { params: Promise<SeoRouteParams> }) {
  const resolvedParams = await params;
  const slug = resolvedParams.slug ?? [];

  if (slug.length === 0) {
    return <CourseCatalogPage />;
  }

  if (slug.length === 1) {
    const course = findCatalogCourse(slug[0]);

    if (course) {
      return <CourseDetailPage course={course} />;
    }
  }

  return <SeoLandingPage page={await getSectionPage(section, Promise.resolve(resolvedParams))} />;
}
