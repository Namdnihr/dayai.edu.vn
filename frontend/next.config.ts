import type { NextConfig } from "next";

const nextConfig: NextConfig = {
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
