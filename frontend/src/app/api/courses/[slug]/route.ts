import { NextResponse } from "next/server";

const backendUrl = process.env.BACKEND_API_URL ?? "http://localhost:8080/api";

export async function GET(
  _request: Request,
  { params }: { params: Promise<{ slug: string }> },
) {
  const { slug } = await params;
  const response = await fetch(`${backendUrl}/courses/${slug}`, {
    headers: {
      Accept: "application/json",
    },
    cache: "no-store",
  });

  const result = await response.json().catch(() => ({
    course: null,
  }));

  return NextResponse.json(result, {
    status: response.status,
  });
}
