import { NextResponse } from "next/server";

const backendUrl = process.env.BACKEND_API_URL ?? "http://localhost:8080/api";

export async function POST(request: Request) {
  const payload = await request.json();

  const response = await fetch(`${backendUrl}/company-portal/lookup`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
    },
    body: JSON.stringify(payload),
  });

  const result = await response.json().catch(() => ({
    message: "Không đọc được phản hồi từ cổng doanh nghiệp.",
  }));

  return NextResponse.json(result, {
    status: response.status,
  });
}
