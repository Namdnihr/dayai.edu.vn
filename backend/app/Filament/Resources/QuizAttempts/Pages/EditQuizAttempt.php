<?php

namespace App\Filament\Resources\QuizAttempts\Pages;

use App\Filament\Resources\QuizAttempts\QuizAttemptResource;
use App\Models\QuizAttempt;
use App\Services\QuizAttemptScoringService;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditQuizAttempt extends EditRecord
{
    protected static string $resource = QuizAttemptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            Action::make('recalculate')->label("C\u{1EAD}p nh\u{1EAD}t t\u{1ED5}ng \u{111}i\u{1EC3}m")->color('info')->action(function (QuizAttempt $record): void {
                app(QuizAttemptScoringService::class)->recalculate($record);
                $this->fillForm();
                Notification::make()->title("\u{110}\u{E3} c\u{1EAD}p nh\u{1EAD}t t\u{1ED5}ng \u{111}i\u{1EC3}m")->success()->send();
            }),
            Action::make('grade')->label("Ch\u{1ED1}t \u{111}i\u{1EC3}m")->color('success')->requiresConfirmation()->action(function (QuizAttempt $record): void {
                app(QuizAttemptScoringService::class)->recalculate($record, markGraded: true);
                $this->fillForm();
                Notification::make()->title("\u{110}\u{E3} ch\u{1ED1}t \u{111}i\u{1EC3}m v\u{E0} c\u{1EAD}p nh\u{1EAD}t k\u{1EBF}t qu\u{1EA3}")->success()->send();
            }),
        ];
    }
}
