import { SeoLandingPage } from "@/components/seo/seo-landing-page";
import { findSitePage } from "@/lib/site-map";

export default function InstructorsPage() {
  return <SeoLandingPage page={findSitePage("/giang-vien/")!} />;
}
