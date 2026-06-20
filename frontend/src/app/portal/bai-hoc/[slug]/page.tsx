import { PortalLessonPlayer } from "@/components/portal/portal-lesson-player";

export default async function PortalLessonPage({ params }: { params: Promise<{ slug: string }> }) {
  const { slug } = await params;

  return (
    <main className="min-h-screen bg-[#F6FAFF] px-6 py-10 text-slate-950">
      <div className="mx-auto max-w-6xl">
        <a href="/portal" className="text-sm font-bold text-[#003A99]">← Quay lại portal</a>
        <PortalLessonPlayer slug={slug} />
      </div>
    </main>
  );
}
