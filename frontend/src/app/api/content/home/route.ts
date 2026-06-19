import { NextResponse } from "next/server";

const backendUrl = process.env.BACKEND_API_URL ?? "http://localhost:8080/api";

export async function GET() {
  const response = await fetch(`${backendUrl}/content/home`, {
    headers: {
      Accept: "application/json",
    },
    cache: "no-store",
  });

  const result = await response.json().catch(() => ({
    knowledge_items: [],
    video_lessons: [],
  }));

  return NextResponse.json(result, {
    status: response.status,
  });
}
