import { NextResponse } from "next/server";

const backendUrl = (process.env.BACKEND_API_URL ?? "http://localhost:8080/api").replace(/\/$/, "");

export async function postBackendJson(path: string, payload: unknown, fallbackMessage: string) {
  try {
    const response = await fetch(`${backendUrl}${path}`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
      body: JSON.stringify(payload),
      cache: "no-store",
    });

    const result = await response.json().catch(() => ({
      message: fallbackMessage,
    }));

    return NextResponse.json(result, {
      status: response.status,
    });
  } catch (error) {
    console.error(`[DAYAI backend] POST ${path} failed`, error instanceof Error ? error.message : error);

    return NextResponse.json(
      {
        message: "Hệ thống học tập tạm thời chưa kết nối được. Vui lòng thử lại sau.",
      },
      {
        status: 503,
      },
    );
  }
}
