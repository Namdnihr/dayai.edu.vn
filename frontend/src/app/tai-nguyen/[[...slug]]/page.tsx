import { SeoLandingPage } from "@/components/seo/seo-landing-page";
import {
  generateSectionMetadata,
  generateSectionStaticParams,
  getSectionPage,
  SeoRouteParams,
} from "@/lib/seo-route";

const section = "tai-nguyen";

export function generateStaticParams() {
  return generateSectionStaticParams(section);
}

export async function generateMetadata({ params }: { params: Promise<SeoRouteParams> }) {
  return generateSectionMetadata(section, params);
}

export default async function ResourcesPage({ params }: { params: Promise<SeoRouteParams> }) {
  return <SeoLandingPage page={await getSectionPage(section, params)} />;
}
