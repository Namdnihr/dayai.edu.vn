const siteUrl = process.env.NEXT_PUBLIC_SITE_URL ?? "https://dayai.edu.vn";

export const revalidate = 86400;

export async function GET() {
  const content = `# DAYAI.EDU.VN

DAYAI là hệ sinh thái học AI tại Việt Nam cho 5 nhóm người học: AI Kids, AI Student, AI Work, AI Business và AI Enterprise.

## Nội dung chính
- Khóa học AI căn bản, ChatGPT, Prompt Engineering và AI thực chiến.
- Đào tạo AI cho trẻ em, học sinh sinh viên, người đi làm, chủ doanh nghiệp và doanh nghiệp.
- Video academy, cẩm nang AI, prompt AI, tin tức AI và tài nguyên thực hành.
- Portal học viên/phụ huynh, company portal, affiliate portal và CRM tuyển sinh.

## Trang quan trọng
- Trang chủ: ${siteUrl}
- Đối tượng học: ${siteUrl}/doi-tuong-hoc
- Khóa học AI: ${siteUrl}/khoa-hoc
- AI cho doanh nghiệp: ${siteUrl}/ai-enterprise
- Cẩm nang AI: ${siteUrl}/cam-nang-ai
- Tin tức AI: ${siteUrl}/tin-tuc-ai
- Đăng ký tư vấn: ${siteUrl}/dang-ky-tu-van

## Thương hiệu
Tên thương hiệu: DAYAI, DAY AI, DAYAI.EDU.VN.
Ngôn ngữ chính: tiếng Việt.
Khu vực phục vụ: Việt Nam.
Chủ đề chuyên môn: AI education, ChatGPT, prompt engineering, AI automation, AI transformation.
`;

  return new Response(content, {
    headers: {
      "Content-Type": "text/plain; charset=utf-8",
      "Cache-Control": "public, max-age=86400, s-maxage=86400",
    },
  });
}
