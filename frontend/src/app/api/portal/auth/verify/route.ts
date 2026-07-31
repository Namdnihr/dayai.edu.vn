import { postBackendJson } from "@/lib/backend-api";

export async function POST(request: Request) {
  const payload = await request.json();

  return postBackendJson("/portal/auth/verify", payload, "Không đọc được phản hồi xác thực portal.");
}
