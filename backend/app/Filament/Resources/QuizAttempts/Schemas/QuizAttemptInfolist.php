<?php

namespace App\Filament\Resources\QuizAttempts\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class QuizAttemptInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextEntry::make('attempt_code')->label("M\u{E3} l\u{1B0}\u{1EE3}t l\u{E0}m"),
            TextEntry::make('assessment.title')->label("B\u{E0}i ki\u{1EC3}m tra"),
            TextEntry::make('studentProfile.student_code')->label("M\u{E3} h\u{1ECD}c vi\u{EA}n"),
            TextEntry::make('studentProfile.person.full_name')->label("H\u{1ECD}c vi\u{EA}n"),
            TextEntry::make('attempt_no')->label("L\u{1EA7}n l\u{E0}m"),
            TextEntry::make('status')->label("Tr\u{1EA1}ng th\u{E1}i")->badge(),
            TextEntry::make('score')->label("\u{110}i\u{1EC3}m"),
            TextEntry::make('max_score')->label("\u{110}i\u{1EC3}m t\u{1ED1}i \u{111}a"),
            TextEntry::make('correct_count')->label("S\u{1ED1} c\u{E2}u \u{111}\u{FA}ng"),
            TextEntry::make('question_count')->label("T\u{1ED5}ng c\u{E2}u"),
            TextEntry::make('started_at')->label("B\u{1EAF}t \u{111}\u{1EA7}u")->dateTime('d/m/Y H:i'),
            TextEntry::make('submitted_at')->label("N\u{1ED9}p b\u{E0}i")->dateTime('d/m/Y H:i'),
        ]);
    }
}
