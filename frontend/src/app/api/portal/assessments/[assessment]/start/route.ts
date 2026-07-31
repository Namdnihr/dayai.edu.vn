import { postBackendJson } from "@/lib/backend-api";

export async function POST(request: Request, { params }: { params: Promise<{ assessment: string }> }) {
  const payload = await request.json();
  const { assessment } = await params;

  return postBackendJson(`/portal/assessments/${assessment}/start`, payload, "Không đọc được phản hồi bài kiểm tra.");
}
