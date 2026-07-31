import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  allowedDevOrigins: ["127.0.0.1"],
  async rewrites() {
    return [
      {
        source: "/k01",
        destination: "/khoa-hoc/ai-can-ban",
      },
    ];
  },
};

export default nextConfig;
