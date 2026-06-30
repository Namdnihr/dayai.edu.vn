<?php

namespace App\Filament\Resources\QuestionBanks\Schemas;

use App\Models\Tenant;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class QuestionBankForm
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
            TextInput::make('name')
                ->label('T?n ng?n h?ng')
                ->required()
                ->maxLength(255),
            Select::make('bank_type')
                ->label('Ph?m vi')
                ->options([
                    'course' => 'Theo kh?a h?c',
                    'module' => 'Theo module',
                    'video' => 'Theo video',
                    'placement' => '??nh gi? ??u v?o',
                    'final' => 'Thi cu?i kh?a',
                    'general' => 'D?ng chung',
                ])
                ->default('course')
                ->required(),
            Select::make('status')
                ->label('Tr?ng th?i')
                ->options([
                    'active' => '?ang d?ng',
                    'draft' => 'Nh?p',
                    'archived' => 'L?u tr?',
                ])
                ->default('active')
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
            Select::make('audience_type')
                ->label('??i t??ng')
                ->options([
                    'kids' => 'AI Kids',
                    'student' => 'AI Student',
                    'work' => 'AI Work',
                    'business' => 'AI Business',
                    'enterprise' => 'AI Enterprise',
                ]),
            Select::make('level')
                ->label('C?p ??')
                ->options([
                    'beginner' => 'Beginner',
                    'foundation' => 'Foundation',
                    'intermediate' => 'Intermediate',
                    'advanced' => 'Advanced',
                ]),
            TagsInput::make('tags')
                ->label('Tags')
                ->columnSpanFull(),
            Textarea::make('description')
                ->label('M? t?')
                ->columnSpanFull(),
        ]);
    }
}
