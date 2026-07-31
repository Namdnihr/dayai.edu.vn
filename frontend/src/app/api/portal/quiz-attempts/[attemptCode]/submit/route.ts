import { postBackendJson } from "@/lib/backend-api";

export async function POST(request: Request, { params }: { params: Promise<{ attemptCode: string }> }) {
  const payload = await request.json();
  const { attemptCode } = await params;

  return postBackendJson(`/portal/quiz-attempts/${attemptCode}/submit`, payload, "Không nộp được bài kiểm tra.");
}
