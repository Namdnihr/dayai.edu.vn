import { NextResponse } from "next/server";

const backendUrl = process.env.BACKEND_API_URL ?? "http://localhost:8080/api";

export async function POST(_request: Request, { params }: { params: Promise<{ orderCode: string }> }) {
  const { orderCode } = await params;

  const response = await fetch(`${backendUrl}/course-access/checkout/${encodeURIComponent(orderCode)}/mark-paid`, {
    method: "POST",
    headers: {
      Accept: "application/json",
    },
  });

  const result = await response.json().catch(() => ({
    message: "Không đọc được phản hồi xác nhận thanh toán.",
  }));

  return NextResponse.json(result, {
    status: response.status,
  });
}
