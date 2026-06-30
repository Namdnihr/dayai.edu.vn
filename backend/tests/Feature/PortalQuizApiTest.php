<?php

namespace Tests\Feature;

use App\Models\Assessment;
use App\Models\AssessmentQuestion;
use App\Models\AssessmentResult;
use App\Models\Branch;
use App\Models\ClassGroup;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Person;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\QuizAttempt;
use App\Models\StudentProfile;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortalQuizApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_start_and_submit_auto_graded_portal_quiz(): void
    {
        $fixture = $this->createQuizFixture();
        $accessToken = $this->verifiedPortalAccessToken($fixture['phone'], $fixture['student']->student_code);

        $startResponse = $this->postJson("/api/portal/assessments/{$fixture['assessment']->id}/start", [
            'phone' => $fixture['phone'],
            'student_code' => $fixture['student']->student_code,
            'portal_access_token' => $accessToken,
        ]);

        $startResponse
            ->assertOk()
            ->assertJsonPath('assessment.title', 'Portal quiz')
            ->assertJsonPath('assessment.question_count', 1)
            ->assertJsonPath('attempt.status', 'in_progress');

        $attemptCode = $startResponse->json('attempt.attempt_code');

        $submitResponse = $this->postJson("/api/portal/quiz-attempts/{$attemptCode}/submit", [
            'phone' => $fixture['phone'],
            'student_code' => $fixture['student']->student_code,
            'portal_access_token' => $accessToken,
            'answers' => [
                [
                    'assessment_question_id' => $fixture['assessment_question']->id,
                    'selected_option_ids' => [$fixture['correct_option']->id],
                ],
            ],
        ]);

        $submitResponse
            ->assertOk()
            ->assertJsonPath('attempt.status', 'submitted')
            ->assertJsonPath('attempt.score', 2)
            ->assertJsonPath('attempt.correct_count', 1)
            ->assertJsonPath('attempt.answers.0.is_correct', true);

        $attempt = QuizAttempt::query()->firstOrFail();

        $this->assertSame('submitted', $attempt->status);
        $this->assertSame(2.0, (float) $attempt->score);
        $this->assertSame(1, AssessmentResult::query()->count());

        $result = AssessmentResult::query()->firstOrFail();

        $this->assertSame($fixture['assessment']->id, $result->assessment_id);
        $this->assertSame($fixture['student']->id, $result->student_profile_id);
        $this->assertSame('published', $result->status);
        $this->assertSame(2.0, (float) $result->score);

        $this->postJson("/api/portal/quiz-attempts/{$attemptCode}/submit", [
            'phone' => $fixture['phone'],
            'student_code' => $fixture['student']->student_code,
            'portal_access_token' => $accessToken,
            'answers' => [
                [
                    'assessment_question_id' => $fixture['assessment_question']->id,
                    'selected_option_ids' => [$fixture['wrong_option']->id],
                ],
            ],
        ])->assertUnprocessable();
    }

    public function test_student_cannot_start_quiz_for_unenrolled_course(): void
    {
        $fixture = $this->createQuizFixture();
        $accessToken = $this->verifiedPortalAccessToken($fixture['phone'], $fixture['student']->student_code);

        $otherCourse = Course::query()->create([
            'tenant_id' => $fixture['tenant']->id,
            'name' => 'Other course',
            'slug' => 'other-course',
            'course_code' => 'OTHER',
            'status' => 'published',
        ]);

        $otherAssessment = Assessment::query()->create([
            'tenant_id' => $fixture['tenant']->id,
            'course_id' => $otherCourse->id,
            'title' => 'Other quiz',
            'assessment_type' => 'quiz',
            'status' => 'published',
            'max_score' => 1,
            'assessment_at' => now(),
        ]);

        $this->postJson("/api/portal/assessments/{$otherAssessment->id}/start", [
            'phone' => $fixture['phone'],
            'student_code' => $fixture['student']->student_code,
            'portal_access_token' => $accessToken,
        ])->assertNotFound();
    }

    /**
     * @return array{
     *     tenant: Tenant,
     *     phone: string,
     *     student: StudentProfile,
     *     assessment: Assessment,
     *     assessment_question: AssessmentQuestion,
     *     correct_option: QuestionOption,
     *     wrong_option: QuestionOption
     * }
     */
    private function createQuizFixture(): array
    {
        $tenant = Tenant::query()->create([
            'name' => 'DAYAI',
            'code' => 'dayai',
            'status' => 'active',
        ]);

        $branch = Branch::query()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Main',
            'code' => 'main',
            'status' => 'active',
        ]);

        $phone = '0901999000';

        $person = Person::query()->create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch->id,
            'full_name' => 'Portal Quiz Student',
            'phone' => $phone,
        ]);

        $student = StudentProfile::query()->create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch->id,
            'person_id' => $person->id,
            'student_code' => 'HV-QUIZ',
            'student_type' => 'university_student',
            'status' => 'active',
        ]);

        $course = Course::query()->create([
            'tenant_id' => $tenant->id,
            'name' => 'AI Quiz',
            'slug' => 'ai-quiz',
            'course_code' => 'AI-QUIZ',
            'status' => 'published',
        ]);

        $classGroup = ClassGroup::query()->create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch->id,
            'course_id' => $course->id,
            'class_code' => 'QUIZ-01',
            'name' => 'Quiz Class',
            'status' => 'active',
        ]);

        $enrollment = Enrollment::query()->create([
            'tenant_id' => $tenant->id,
            'student_profile_id' => $student->id,
            'course_id' => $course->id,
            'class_group_id' => $classGroup->id,
            'enrollment_code' => 'ENR-QUIZ',
            'status' => 'active',
            'enrolled_at' => now(),
        ]);

        $assessment = Assessment::query()->create([
            'tenant_id' => $tenant->id,
            'course_id' => $course->id,
            'class_group_id' => $classGroup->id,
            'title' => 'Portal quiz',
            'assessment_type' => 'quiz',
            'status' => 'published',
            'max_score' => 2,
            'assessment_at' => now(),
        ]);

        $question = Question::query()->create([
            'tenant_id' => $tenant->id,
            'course_id' => $course->id,
            'question_type' => 'single_choice',
            'difficulty' => 'easy',
            'status' => 'published',
            'prompt' => 'Which answer is correct?',
            'default_score' => 2,
        ]);

        $correctOption = QuestionOption::query()->create([
            'tenant_id' => $tenant->id,
            'question_id' => $question->id,
            'sort_order' => 1,
            'content' => 'Correct',
            'is_correct' => true,
        ]);

        $wrongOption = QuestionOption::query()->create([
            'tenant_id' => $tenant->id,
            'question_id' => $question->id,
            'sort_order' => 2,
            'content' => 'Wrong',
            'is_correct' => false,
        ]);

        $assessmentQuestion = AssessmentQuestion::query()->create([
            'tenant_id' => $tenant->id,
            'assessment_id' => $assessment->id,
            'question_id' => $question->id,
            'sort_order' => 1,
            'score' => 2,
            'is_required' => true,
        ]);

        return [
            'tenant' => $tenant,
            'phone' => $phone,
            'student' => $student,
            'enrollment' => $enrollment,
            'assessment' => $assessment,
            'assessment_question' => $assessmentQuestion,
            'correct_option' => $correctOption,
            'wrong_option' => $wrongOption,
        ];
    }

    private function verifiedPortalAccessToken(string $phone, string $studentCode): string
    {
        $authRequest = $this->postJson('/api/portal/auth/request', [
            'phone' => $phone,
            'student_code' => $studentCode,
        ]);

        $authRequest->assertOk();

        $authVerify = $this->postJson('/api/portal/auth/verify', [
            'request_id' => $authRequest->json('request_id'),
            'code' => $authRequest->json('demo_otp'),
        ]);

        $authVerify->assertOk();

        return (string) $authVerify->json('portal_access_token');
    }
}
