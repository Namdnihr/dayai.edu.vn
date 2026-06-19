import type { Metadata } from "next";
import { Geist, Geist_Mono } from "next/font/google";
import "./globals.css";

const siteUrl = process.env.NEXT_PUBLIC_SITE_URL ?? "https://dayai.edu.vn";

const geistSans = Geist({
  variable: "--font-geist-sans",
  subsets: ["latin"],
});

const geistMono = Geist_Mono({
  variable: "--font-geist-mono",
  subsets: ["latin"],
});

export const metadata: Metadata = {
  metadataBase: new URL(siteUrl),
  title: {
    default: "DAYAI - Học AI thực chiến",
    template: "%s | DAYAI",
  },
  description:
    "DAYAI đào tạo AI thực chiến cho học sinh, sinh viên, chủ doanh nghiệp và đội ngũ công ty.",
  applicationName: "DAYAI",
  keywords: [
    "đào tạo AI",
    "khóa học AI",
    "AI cho doanh nghiệp",
    "Prompt Engineering",
    "học AI thực chiến",
  ],
  authors: [{ name: "DAYAI" }],
  creator: "DAYAI",
  publisher: "DAYAI",
  alternates: {
    canonical: "/",
  },
  openGraph: {
    type: "website",
    locale: "vi_VN",
    url: "/",
    siteName: "DAYAI",
    title: "DAYAI - Học AI thực chiến",
    description:
      "Khóa học AI, video bài giảng, kho kiến thức và chương trình AI cho doanh nghiệp.",
  },
  twitter: {
    card: "summary_large_image",
    title: "DAYAI - Học AI thực chiến",
    description:
      "Đào tạo AI thực chiến cho cá nhân, phụ huynh, sinh viên và doanh nghiệp.",
  },
  robots: {
    index: true,
    follow: true,
  },
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html
      lang="vi"
      className={`${geistSans.variable} ${geistMono.variable} h-full antialiased`}
    >
      <body className="min-h-full flex flex-col">{children}</body>
    </html>
  );
}
