# 14 — Sprint 34: Online Assessment & Quiz Engine

## Sprint goal

Build online quiz flow inside Student Portal and sync submitted results into Assessment Results.

## Scope

### Student portal

- Quiz list
- Start quiz
- Answer questions
- Submit quiz
- View score
- View correct count
- View attempt history

### Question types

- Single choice
- Multiple choice
- True/false

### Grading

- Auto-grade objective questions
- Single choice: exactly one correct option
- Multiple choice: selected set must match correct set unless partial credit is explicitly implemented
- True/false: selected boolean matches correct boolean

### Sync

Quiz result must sync to assessment result.

## Data relationships

```text
Course
  -> Module
    -> Lesson
      -> Quiz
        -> Question
          -> Options
        -> QuizAttempt
          -> Answers
          -> AssessmentResult
```

Quiz can be attached to:

- Lesson
- Module
- Course

At least one attachment context should be supported according to existing schema.

## Recommended statuses

Quiz status for student:

- Chưa làm
- Đang làm
- Đã nộp
- Đã đạt
- Chưa đạt

Attempt status:

- Đang làm
- Đã nộp
- Hết hạn nếu timer exists

## Quiz list UI

Each quiz card/row shows:

- Quiz title
- Course/module/lesson context
- Number of questions
- Time limit if available
- Last score
- Attempt count
- Status
- CTA

CTA rules:

- Chưa làm: `Bắt đầu`
- Đang làm: `Tiếp tục`
- Đã nộp: `Xem kết quả`
- Allow retake if business rule permits: `Làm lại`

## Start quiz UI

Before starting:

- Show title
- Show context
- Show question count
- Show time limit if any
- Show attempt policy
- Primary CTA: `Bắt đầu làm bài`

## Attempt UI

Question card:

- Question number
- Question text
- Question type label
- Options
- Save answer behavior if supported
- Progress indicator

Submit bar:

- Answered count
- Unanswered count
- Submit button

Before submit:

- Warn if unanswered questions exist.

## Result UI

Show:

- Score
- Percent
- Correct / total
- Submitted time
- Pass/fail if threshold exists
- Attempt history
- Review answers if allowed

Vietnamese copy examples:

```text
Bạn đạt 8/10 câu đúng.
Điểm số đã được đồng bộ vào báo cáo tiến bộ.
```

## Grading pseudocode

```ts
type QuestionType = 'single_choice' | 'multiple_choice' | 'true_false';

function gradeQuestion(question, selectedOptionIds) {
  const correctIds = new Set(question.options.filter(o => o.isCorrect).map(o => o.id));
  const selectedIds = new Set(selectedOptionIds);

  if (question.type === 'single_choice' || question.type === 'true_false') {
    return selectedIds.size === 1 && [...selectedIds].every(id => correctIds.has(id));
  }

  if (question.type === 'multiple_choice') {
    if (selectedIds.size !== correctIds.size) return false;
    return [...selectedIds].every(id => correctIds.has(id));
  }

  return false;
}
```

## Assessment result sync

After submit:

1. Calculate score.
2. Store quiz attempt.
3. Store answers.
4. Create/update assessment result.
5. Update progress report data if existing architecture supports it.
6. Show result page.

## Edge cases

Handle:

- Quiz has no questions
- Student is not enrolled
- Quiz not assigned to student/course
- Attempt already submitted
- Multiple submissions
- Network error on submit
- Missing answer
- Deleted/changed question after attempt start

## Acceptance criteria

Sprint 34 is done when:

- Student can see quiz list.
- Student can start a quiz.
- Student can answer single choice, multiple choice, true/false.
- Student can submit.
- Student can see score and correct count.
- Student can see attempt history.
- Result syncs to assessment result.
- Loading/empty/error states exist.
- Portal routes are noindex.
- OTP/security rules remain intact.
- UI text is Vietnamese.
- No broken Vietnamese encoding.
- Tests/build/lint are run if available.
