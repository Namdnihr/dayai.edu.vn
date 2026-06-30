<?php

namespace App\Filament\Resources\QuizAttempts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class QuizAttemptForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('attempt_code')->label("M\u{E3} l\u{1B0}\u{1EE3}t l\u{E0}m")->disabled(),
            Select::make('status')->label("Tr\u{1EA1}ng th\u{E1}i")->options([
                    'in_progress' => "\u{110}ang l\u{E0}m",
                    'submitted' => "\u{110}\u{E3} n\u{1ED9}p",
                    'graded' => "\u{110}\u{E3} ch\u{1EA5}m",
                    'cancelled' => "\u{110}\u{E3} h\u{1EE7}y",
                ])->required(),
            Select::make('assessment_id')->label("B\u{E0}i ki\u{1EC3}m tra")->relationship('assessment', 'title')->disabled(),
            Select::make('student_profile_id')->label("H\u{1ECD}c vi\u{EA}n")->relationship('studentProfile', 'student_code')->disabled(),
            TextInput::make('attempt_no')->label("L\u{1EA7}n l\u{E0}m")->numeric()->disabled(),
            TextInput::make('score')->label("\u{110}i\u{1EC3}m")->numeric(),
            TextInput::make('max_score')->label("\u{110}i\u{1EC3}m t\u{1ED1}i \u{111}a")->numeric(),
            TextInput::make('correct_count')->label("S\u{1ED1} c\u{E2}u \u{111}\u{FA}ng")->numeric(),
            TextInput::make('question_count')->label("T\u{1ED5}ng c\u{E2}u")->numeric(),
            DateTimePicker::make('started_at')->label("B\u{1EAF}t \u{111}\u{1EA7}u"),
            DateTimePicker::make('submitted_at')->label("N\u{1ED9}p b\u{E0}i"),
        ]);
    }
}
