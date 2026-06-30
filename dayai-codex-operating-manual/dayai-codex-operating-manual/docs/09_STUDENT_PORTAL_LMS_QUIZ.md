# 09 — Student Portal, LMS and Quiz Rules

## Vai trò portal

Student/Guardian Portal là nơi học viên tiếp tục học, phụ huynh theo dõi tiến bộ, và hệ thống hiển thị kết quả học tập rõ ràng.

Portal phải thân thiện hơn admin.

## Portal visual direction

Reference blend:

- Coursera: learning clarity
- Notion: approachable layout
- Linear: progress and navigation polish

## Auth

Portal sử dụng OTP demo / token có hạn.

UX cần:

- Nhập email/số điện thoại hoặc mã truy cập
- Gửi OTP
- Nhập OTP
- Trạng thái gửi lại
- Rate limit message
- Login audit
- Không lộ token

Student và Guardian phải tách quyền.

## Portal dashboard

Recommended layout:

```text
Greeting
Primary course card / Continue learning
Upcoming sessions
Learning progress
Pending quiz
Recent score
Teacher comments
Finance summary
Notifications
Certificates
```

Primary CTA:

```text
Tiếp tục học
```

## Course card

Course card cần:

- Tên khóa
- Module hiện tại
- Tiến độ %
- Bài học tiếp theo
- Số buổi sắp tới
- Quiz đang chờ nếu có

## Lesson player

Route:

```text
/portal/bai-hoc/[slug]
```

Components:

- VideoPlayer
- LessonHeader
- ProgressBar
- MaterialList
- RelatedQuizCard
- NextLessonCTA

Progress data:

- percent
- last position
- completed
- updated at

UX:

- Resume progress automatically if allowed
- Show clear “Đánh dấu hoàn thành” or auto-complete logic
- Keep materials easy to find
- Related quiz should be near lesson completion area

## Quiz list

Route suggestion:

```text
/portal/quiz
```

Show:

- Assigned quiz
- Course/module/lesson relation
- Status: Chưa làm, Đang làm, Đã nộp, Cần làm lại nếu có
- Score if submitted
- Attempt count
- CTA: Bắt đầu / Làm lại / Xem kết quả

## Quiz attempt

Quiz attempt page needs:

- Quiz title
- Course/module/lesson context
- Attempt number
- Progress indicator
- Question card
- Answer options
- Submit action
- Warning if unanswered questions

Question types:

- Single choice
- Multiple choice
- True/false

## Quiz result

After submit, show:

- Score
- Correct count
- Total questions
- Percent
- Pass/fail if threshold exists
- Attempt history
- Review answers if allowed by policy

Result must sync to assessment result.

## Guardian view

Guardian can see:

- Student overview
- Progress reports
- Teacher comments intended for guardian
- Attendance
- Finance/công nợ
- Certificates
- Notifications

Guardian should not see admin-only data.

## Portal noindex

All portal pages must be noindex.

## Portal UI tone

Use supportive copy:

- “Bạn đang học tốt. Tiếp tục bài tiếp theo nhé.”
- “Bạn còn 2 bài kiểm tra cần hoàn thành.”
- “Phụ huynh có thể xem nhận xét mới từ giáo viên.”

Avoid admin-like harsh language.
