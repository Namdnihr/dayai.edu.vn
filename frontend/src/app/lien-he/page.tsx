import { SeoLandingPage } from "@/components/seo/seo-landing-page";
import { findSitePage } from "@/lib/site-map";

export default function ContactPage() {
  return <SeoLandingPage page={findSitePage("/lien-he/")!} />;
}
