<?php

namespace App\Filament\Resources\QuizAttempts\Pages;

use App\Filament\Resources\QuizAttempts\QuizAttemptResource;
use App\Models\QuizAttempt;
use App\Services\QuizAttemptScoringService;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewQuizAttempt extends ViewRecord
{
    protected static string $resource = QuizAttemptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('grade')->label("Ch\u{1ED1}t \u{111}i\u{1EC3}m")->color('success')->requiresConfirmation()->action(function (QuizAttempt $record): void {
                app(QuizAttemptScoringService::class)->recalculate($record, markGraded: true);
                $this->refreshFormData(['status', 'score', 'max_score', 'correct_count', 'question_count']);
                Notification::make()->title("\u{110}\u{E3} ch\u{1ED1}t \u{111}i\u{1EC3}m v\u{E0} c\u{1EAD}p nh\u{1EAD}t k\u{1EBF}t qu\u{1EA3}")->success()->send();
            }),
        ];
    }
}
