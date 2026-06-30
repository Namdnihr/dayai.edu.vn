import { NextResponse } from "next/server";

const backendUrl = process.env.BACKEND_API_URL ?? "http://localhost:8080/api";

export async function POST(request: Request, { params }: { params: Promise<{ attemptCode: string }> }) {
  const payload = await request.json();
  const { attemptCode } = await params;

  const response = await fetch(`${backendUrl}/portal/quiz-attempts/${attemptCode}/submit`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
    },
    body: JSON.stringify(payload),
  });

  const result = await response.json().catch(() => ({
    message: "Kh?ng n?p ???c b?i ki?m tra.",
  }));

  return NextResponse.json(result, {
    status: response.status,
  });
}
