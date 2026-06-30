import { NextResponse } from "next/server";

const backendUrl = process.env.BACKEND_API_URL ?? "http://localhost:8080/api";

export const dynamic = "force-dynamic";

export async function GET() {
  try {
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
  } catch {
    return NextResponse.json({
      knowledge_items: [],
      video_lessons: [],
    });
  }
}
