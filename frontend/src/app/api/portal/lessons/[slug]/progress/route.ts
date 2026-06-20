import { NextResponse } from "next/server";

const backendUrl = process.env.BACKEND_API_URL ?? "http://localhost:8080/api";

export async function POST(request: Request, { params }: { params: Promise<{ slug: string }> }) {
  const payload = await request.json();
  const { slug } = await params;

  const response = await fetch(`${backendUrl}/portal/lessons/${slug}/progress`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
    },
    body: JSON.stringify(payload),
  });

  const result = await response.json().catch(() => ({
    message: "Không cập nhật được tiến độ bài học.",
  }));

  return NextResponse.json(result, {
    status: response.status,
  });
}
