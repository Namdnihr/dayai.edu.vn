import { NextResponse } from "next/server";

const backendUrl = process.env.BACKEND_API_URL ?? "http://localhost:8080/api";

export async function POST(request: Request) {
  const payload = await request.json();

  const response = await fetch(`${backendUrl}/course-access/free-enroll`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
    },
    body: JSON.stringify(payload),
  });

  const result = await response.json().catch(() => ({
    message: "Không đọc được phản hồi mở quyền học.",
  }));

  return NextResponse.json(result, {
    status: response.status,
  });
}
