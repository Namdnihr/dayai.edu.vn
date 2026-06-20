import type { Metadata, Viewport } from "next";
import { Be_Vietnam_Pro, Geist_Mono } from "next/font/google";
import "./globals.css";

const siteUrl = process.env.NEXT_PUBLIC_SITE_URL ?? "https://dayai.edu.vn";

const beVietnamPro = Be_Vietnam_Pro({
  variable: "--font-body",
  subsets: ["latin", "vietnamese"],
  weight: ["400", "500", "600", "700", "800", "900"],
  display: "swap",
});

const geistMono = Geist_Mono({
  variable: "--font-geist-mono",
  subsets: ["latin"],
  display: "swap",
});

export const metadata: Metadata = {
  metadataBase: new URL(siteUrl),
  title: {
    default: "DAYAI - Học AI thực chiến cho người Việt",
    template: "%s | DAYAI",
  },
  description:
    "DAYAI là hệ sinh thái học AI tại Việt Nam cho trẻ em, học sinh sinh viên, người đi làm, chủ doanh nghiệp và đội ngũ công ty.",
  applicationName: "DAYAI",
  keywords: [
    "đào tạo AI",
    "khóa học AI",
    "học AI",
    "học ChatGPT",
    "AI cho doanh nghiệp",
    "Prompt Engineering",
    "học AI thực chiến",
    "đào tạo AI Việt Nam",
  ],
  authors: [{ name: "DAYAI" }],
  creator: "DAYAI",
  publisher: "DAYAI",
  category: "education",
  alternates: {
    canonical: "/",
    languages: {
      "vi-VN": "/",
    },
  },
  openGraph: {
    type: "website",
    locale: "vi_VN",
    url: "/",
    siteName: "DAYAI",
    title: "DAYAI - Học AI Hôm Nay, Dẫn Đầu Tương Lai",
    description:
      "Khóa học AI, video academy, kho kiến thức và chương trình đào tạo AI cho cá nhân, phụ huynh và doanh nghiệp Việt Nam.",
    images: [
      {
        url: "/opengraph-image",
        width: 1200,
        height: 630,
        alt: "DAYAI - Hệ sinh thái học AI cho Việt Nam",
      },
    ],
  },
  twitter: {
    card: "summary_large_image",
    title: "DAYAI - Học AI Hôm Nay, Dẫn Đầu Tương Lai",
    description:
      "Đào tạo AI thực chiến cho Kids, Students, Workers, Business Owners và Enterprises.",
    images: ["/opengraph-image"],
  },
  robots: {
    index: true,
    follow: true,
  },
  icons: {
    icon: "/favicon.ico",
  },
};

export const viewport: Viewport = {
  width: "device-width",
  initialScale: 1,
  themeColor: "#003A99",
  colorScheme: "light",
};

const organizationJsonLd = {
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "EducationalOrganization",
      "@id": `${siteUrl}/#organization`,
      name: "DAYAI",
      alternateName: "DAY AI",
      url: siteUrl,
      logo: `${siteUrl}/opengraph-image`,
      description:
        "Hệ sinh thái học AI tại Việt Nam cho trẻ em, học sinh sinh viên, người đi làm, chủ doanh nghiệp và doanh nghiệp.",
      areaServed: {
        "@type": "Country",
        name: "Việt Nam",
      },
      knowsAbout: [
        "Artificial Intelligence",
        "ChatGPT",
        "Prompt Engineering",
        "AI Automation",
        "AI Education",
      ],
      sameAs: [siteUrl],
    },
    {
      "@type": "WebSite",
      "@id": `${siteUrl}/#website`,
      url: siteUrl,
      name: "DAYAI.EDU.VN",
      inLanguage: "vi-VN",
      publisher: {
        "@id": `${siteUrl}/#organization`,
      },
      potentialAction: {
        "@type": "SearchAction",
        target: `${siteUrl}/tin-tuc-ai/?q={search_term_string}`,
        "query-input": "required name=search_term_string",
      },
    },
    {
      "@type": "ItemList",
      "@id": `${siteUrl}/#course-catalog`,
      name: "Lộ trình học AI tại DAYAI",
      itemListElement: [
        {
          "@type": "Course",
          position: 1,
          name: "AI Kids",
          description: "Lộ trình học AI an toàn, sáng tạo cho trẻ em.",
          provider: { "@id": `${siteUrl}/#organization` },
          url: `${siteUrl}/ai-kids/`,
        },
        {
          "@type": "Course",
          position: 2,
          name: "AI Student",
          description: "Ứng dụng AI trong học tập, nghiên cứu và định hướng nghề nghiệp.",
          provider: { "@id": `${siteUrl}/#organization` },
          url: `${siteUrl}/ai-student/`,
        },
        {
          "@type": "Course",
          position: 3,
          name: "AI Work",
          description: "AI cho người đi làm: tăng năng suất, báo cáo, content và workflow.",
          provider: { "@id": `${siteUrl}/#organization` },
          url: `${siteUrl}/ai-work/`,
        },
        {
          "@type": "Course",
          position: 4,
          name: "AI Business & Enterprise",
          description: "Đào tạo AI cho chủ doanh nghiệp, phòng ban và đội ngũ công ty.",
          provider: { "@id": `${siteUrl}/#organization` },
          url: `${siteUrl}/ai-enterprise/`,
        },
      ],
    },
  ],
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html
      lang="vi"
      className={`${beVietnamPro.variable} ${geistMono.variable} h-full antialiased`}
    >
      <body className="min-h-full flex flex-col">
        <script
          type="application/ld+json"
          dangerouslySetInnerHTML={{ __html: JSON.stringify(organizationJsonLd) }}
        />
        {children}
      </body>
    </html>
  );
}

