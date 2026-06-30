<?php

namespace App\Filament\Resources\Assessments\Schemas;

use App\Models\Tenant;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AssessmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tenant_id')
                    ->label('??n v?')
                    ->relationship('tenant', 'name')
                    ->default(fn () => Tenant::query()->value('id'))
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('course_id')
                    ->label('Kh?a h?c')
                    ->relationship('course', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('course_module_id')
                    ->label('Module')
                    ->relationship('courseModule', 'title')
                    ->searchable()
                    ->preload(),
                Select::make('video_lesson_id')
                    ->label('Video b?i h?c')
                    ->relationship('videoLesson', 'title')
                    ->searchable()
                    ->preload(),
                Select::make('class_group_id')
                    ->label('L?p h?c')
                    ->relationship('classGroup', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('title')
                    ->label('T?n b?i ??nh gi?')
                    ->required(),
                Select::make('assessment_type')
                    ->label('Lo?i ??nh gi?')
                    ->options([
                        'entry' => '??u v?o',
                        'quiz' => 'Quiz nhanh',
                        'practice' => 'B?i luy?n t?p',
                        'project' => 'D? ?n',
                        'final' => 'Cu?i kh?a',
                        'progress' => 'Ti?n b?',
                    ])
                    ->default('quiz')
                    ->required(),
                Select::make('status')
                    ->label('Tr?ng th?i')
                    ->options([
                        'draft' => 'Nh?p',
                        'published' => '?? c?ng b?',
                        'archived' => 'L?u tr?',
                    ])
                    ->default('draft')
                    ->required(),
                TextInput::make('max_score')
                    ->label('?i?m t?i ?a')
                    ->required()
                    ->numeric()
                    ->default(10),
                TextInput::make('weight_percent')
                    ->label('T? tr?ng %')
                    ->numeric(),
                DateTimePicker::make('assessment_at')
                    ->label('Th?i ?i?m ??nh gi?'),
                Textarea::make('description')
                    ->label('M? t? / h??ng d?n l?m b?i')
                    ->columnSpanFull(),
            ]);
    }
}
