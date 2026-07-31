import { postBackendJson } from "@/lib/backend-api";

export async function POST(request: Request, { params }: { params: Promise<{ slug: string }> }) {
  const payload = await request.json();
  const { slug } = await params;

  return postBackendJson(`/portal/lessons/${slug}`, payload, "Không đọc được phản hồi bài học.");
}
