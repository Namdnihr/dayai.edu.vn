import { ImageResponse } from "next/og";

export const alt = "DAYAI - Học AI Hôm Nay, Dẫn Đầu Tương Lai";
export const size = {
  width: 1200,
  height: 630,
};
export const contentType = "image/png";

export default function Image() {
  return new ImageResponse(
    (
      <div
        style={{
          width: "100%",
          height: "100%",
          display: "flex",
          flexDirection: "column",
          justifyContent: "space-between",
          padding: 72,
          background:
            "radial-gradient(circle at 18% 22%, rgba(0,174,239,0.32), transparent 32%), radial-gradient(circle at 82% 18%, rgba(245,180,0,0.26), transparent 26%), linear-gradient(135deg, #ffffff 0%, #eef7ff 48%, #ffffff 100%)",
          color: "#06132B",
          fontFamily: "Arial, sans-serif",
        }}
      >
        <div style={{ display: "flex", justifyContent: "space-between", alignItems: "center" }}>
          <div style={{ fontSize: 44, fontWeight: 900, letterSpacing: "-0.04em" }}>DAYAI</div>
          <div
            style={{
              border: "1px solid rgba(0,58,153,0.18)",
              borderRadius: 999,
              padding: "14px 24px",
              fontSize: 24,
              fontWeight: 700,
              color: "#003A99",
              background: "rgba(255,255,255,0.72)",
            }}
          >
            AI Learning Ecosystem
          </div>
        </div>

        <div style={{ display: "flex", flexDirection: "column" }}>
          <div style={{ fontSize: 86, fontWeight: 900, lineHeight: 0.96, letterSpacing: "-0.055em" }}>
            Học AI Hôm Nay
          </div>
          <div
            style={{
              marginTop: 14,
              fontSize: 86,
              fontWeight: 900,
              lineHeight: 0.96,
              letterSpacing: "-0.055em",
              color: "#003A99",
            }}
          >
            Dẫn Đầu Tương Lai
          </div>
          <div style={{ marginTop: 32, maxWidth: 880, fontSize: 30, lineHeight: 1.35, color: "#344256" }}>
            Khóa học AI, video academy và chương trình đào tạo AI cho cá nhân, phụ huynh và doanh nghiệp Việt Nam.
          </div>
        </div>

        <div style={{ display: "flex", gap: 18, fontSize: 24, color: "#003A99", fontWeight: 800 }}>
          <span>AI Kids</span>
          <span>•</span>
          <span>AI Student</span>
          <span>•</span>
          <span>AI Work</span>
          <span>•</span>
          <span>AI Enterprise</span>
        </div>
      </div>
    ),
    size,
  );
}
