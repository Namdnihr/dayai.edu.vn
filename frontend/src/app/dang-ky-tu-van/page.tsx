import { SeoLandingPage } from "@/components/seo/seo-landing-page";
import { findSitePage } from "@/lib/site-map";

export default function ConsultationPage() {
  return <SeoLandingPage page={findSitePage("/dang-ky-tu-van/")!} />;
}
