import { NextResponse } from "next/server";

const backendUrl = process.env.BACKEND_API_URL ?? "http://localhost:8080/api";

export async function POST(request: Request, { params }: { params: Promise<{ assessment: string }> }) {
  const payload = await request.json();
  const { assessment } = await params;

  const response = await fetch(`${backendUrl}/portal/assessments/${assessment}/start`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
    },
    body: JSON.stringify(payload),
  });

  const result = await response.json().catch(() => ({
    message: "Kh?ng ??c ???c ph?n h?i b?i ki?m tra.",
  }));

  return NextResponse.json(result, {
    status: response.status,
  });
}
