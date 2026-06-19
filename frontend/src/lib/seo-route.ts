import type { Metadata } from "next";
import { notFound } from "next/navigation";
import { findSitePage, sitePages } from "@/lib/site-map";

export type SeoRouteParams = {
  slug?: string[];
};

export function generateSectionStaticParams(section: string) {
  const sectionPrefix = `/${section}/`;

  return sitePages
    .filter((page) => page.path === sectionPrefix || page.path.startsWith(sectionPrefix))
    .map((page) => {
      const slug = page.path
        .replace(sectionPrefix, "")
        .replace(/\/$/, "")
        .split("/")
        .filter(Boolean);

      return { slug };
    });
}

export async function getSectionPage(
  section: string,
  params: Promise<SeoRouteParams>,
) {
  const resolvedParams = await params;
  const slug = resolvedParams.slug ?? [];
  const path = `/${section}/${slug.length ? `${slug.join("/")}/` : ""}`;
  const page = findSitePage(path);

  if (!page) {
    notFound();
  }

  return page;
}

export async function generateSectionMetadata(
  section: string,
  params: Promise<SeoRouteParams>,
): Promise<Metadata> {
  const page = await getSectionPage(section, params);

  return {
    title: page.title,
    description: page.description,
    alternates: {
      canonical: page.path,
    },
    openGraph: {
      title: `${page.title} | DAYAI`,
      description: page.description,
      url: page.path,
    },
  };
}
