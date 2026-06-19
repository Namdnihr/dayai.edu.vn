import { SeoLandingPage } from "@/components/seo/seo-landing-page";
import { findSitePage } from "@/lib/site-map";

export default function AboutDayaiPage() {
  return <SeoLandingPage page={findSitePage("/ve-day-ai/")!} />;
}
