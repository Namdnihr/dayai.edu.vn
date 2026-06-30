<?php

namespace App\Filament\Resources\Questions\Schemas;

use App\Models\Tenant;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class QuestionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('tenant_id')
                ->label('??n v?')
                ->relationship('tenant', 'name')
                ->default(fn () => Tenant::query()->value('id'))
                ->searchable()
                ->preload()
                ->required(),
            Select::make('question_bank_id')
                ->label('Ng?n h?ng c?u h?i')
                ->relationship('questionBank', 'name')
                ->searchable()
                ->preload(),
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
            Select::make('question_type')
                ->label('Lo?i c?u h?i')
                ->options([
                    'single_choice' => 'M?t ??p ?n ??ng',
                    'multiple_choice' => 'Nhi?u ??p ?n ??ng',
                    'true_false' => '??ng / Sai',
                    'short_answer' => 'Tr? l?i ng?n',
                    'essay' => 'T? lu?n',
                    'project' => 'B?i t?p d? ?n',
                ])
                ->default('single_choice')
                ->required(),
            Select::make('difficulty')
                ->label('?? kh?')
                ->options([
                    'easy' => 'D?',
                    'medium' => 'Trung b?nh',
                    'hard' => 'Kh?',
                    'challenge' => 'Th? th?ch',
                ])
                ->default('easy')
                ->required(),
            Select::make('status')
                ->label('Tr?ng th?i')
                ->options([
                    'draft' => 'Nh?p',
                    'published' => '?? duy?t',
                    'archived' => 'L?u tr?',
                ])
                ->default('draft')
                ->required(),
            TextInput::make('default_score')
                ->label('?i?m m?c ??nh')
                ->numeric()
                ->default(1)
                ->required(),
            TextInput::make('time_limit_seconds')
                ->label('Gi?i h?n gi?y')
                ->numeric(),
            Textarea::make('prompt')
                ->label('N?i dung c?u h?i')
                ->required()
                ->columnSpanFull(),
            Textarea::make('explanation')
                ->label('Gi?i th?ch ??p ?n')
                ->columnSpanFull(),
            TagsInput::make('tags')
                ->label('Tags')
                ->columnSpanFull(),
            Repeater::make('options')
                ->label('??p ?n l?a ch?n')
                ->relationship('options')
                ->schema([
                    Hidden::make('tenant_id')->default(fn () => Tenant::query()->value('id')),
                    TextInput::make('sort_order')->label('Th? t?')->numeric()->default(1),
                    Textarea::make('content')->label('N?i dung ??p ?n')->required()->columnSpanFull(),
                    Toggle::make('is_correct')->label('??p ?n ??ng'),
                    Textarea::make('feedback')->label('Ph?n h?i')->columnSpanFull(),
                ])
                ->columns(2)
                ->defaultItems(4)
                ->columnSpanFull(),
        ]);
    }
}
