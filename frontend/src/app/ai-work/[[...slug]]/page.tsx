import { SeoLandingPage } from "@/components/seo/seo-landing-page";
import {
  generateSectionMetadata,
  generateSectionStaticParams,
  getSectionPage,
  SeoRouteParams,
} from "@/lib/seo-route";

const section = "ai-work";

export function generateStaticParams() {
  return generateSectionStaticParams(section);
}

export async function generateMetadata({ params }: { params: Promise<SeoRouteParams> }) {
  return generateSectionMetadata(section, params);
}

export default async function AiWorkPage({ params }: { params: Promise<SeoRouteParams> }) {
  return <SeoLandingPage page={await getSectionPage(section, params)} />;
}
