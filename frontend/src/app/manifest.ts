import type { MetadataRoute } from "next";

export default function manifest(): MetadataRoute.Manifest {
  return {
    name: "DAYAI.EDU.VN",
    short_name: "DAYAI",
    description: "Hệ sinh thái học AI tại Việt Nam.",
    start_url: "/",
    display: "standalone",
    background_color: "#ffffff",
    theme_color: "#003A99",
    lang: "vi-VN",
    categories: ["education", "productivity"],
    icons: [
      {
        src: "/favicon.ico",
        sizes: "any",
        type: "image/x-icon",
      },
    ],
  };
}
