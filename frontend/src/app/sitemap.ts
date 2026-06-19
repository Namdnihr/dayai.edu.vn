import type { MetadataRoute } from "next";
import { sitePages } from "@/lib/site-map";

const siteUrl = process.env.NEXT_PUBLIC_SITE_URL ?? "https://dayai.edu.vn";

export default function sitemap(): MetadataRoute.Sitemap {
  const now = new Date();

  return sitePages
    .filter((page) => page.group !== "Portal")
    .map((page) => ({
      url:
        page.path === "/"
          ? siteUrl
          : `${siteUrl}${page.path.replace(/\/$/, "")}`,
      lastModified: now,
      changeFrequency: page.priority === "P0" ? "weekly" : "monthly",
      priority: page.priority === "P0" ? 0.9 : page.priority === "P1" ? 0.7 : 0.5,
    }));
}
