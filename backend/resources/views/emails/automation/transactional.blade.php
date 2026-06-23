<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $mailTitle }}</title>
</head>
<body style="margin:0;background:#f5f8ff;color:#0f172a;font-family:Arial,'Helvetica Neue',sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f5f8ff;padding:28px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;background:#ffffff;border:1px solid #dbeafe;border-radius:24px;overflow:hidden;">
                    <tr>
                        <td style="background:linear-gradient(135deg,#003A99,#00AEEF);padding:28px 32px;color:#ffffff;">
                            <div style="font-size:13px;letter-spacing:3px;text-transform:uppercase;font-weight:700;">DAYAI.EDU.VN</div>
                            <h1 style="margin:12px 0 0;font-size:28px;line-height:1.18;font-weight:800;">{{ $mailTitle }}</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            @foreach (preg_split("/\r\n|\n|\r/", $mailBody) as $line)
                                @if (trim($line) === '')
                                    <div style="height:12px;"></div>
                                @else
                                    <p style="margin:0 0 12px;font-size:16px;line-height:1.65;color:#334155;">{{ $line }}</p>
                                @endif
                            @endforeach

                            <div style="margin-top:28px;padding:18px 20px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:18px;">
                                <p style="margin:0;font-size:14px;line-height:1.6;color:#1e3a8a;">
                                    Đây là email tự động từ hệ thống DAYAI. Nếu cần hỗ trợ, vui lòng phản hồi email này hoặc liên hệ trung tâm.
                                </p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:22px 32px;background:#0f172a;color:#cbd5e1;">
                            <div style="font-size:14px;font-weight:700;color:#ffffff;">DAY AI - Học AI Hôm Nay, Dẫn Đầu Tương Lai</div>
                            <div style="margin-top:6px;font-size:12px;line-height:1.5;">Hệ sinh thái đào tạo AI cho trẻ em, sinh viên, người đi làm, chủ doanh nghiệp và doanh nghiệp.</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
