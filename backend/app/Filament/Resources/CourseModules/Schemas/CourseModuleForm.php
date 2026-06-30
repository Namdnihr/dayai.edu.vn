<?php

namespace App\Filament\Resources\CourseModules\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CourseModuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tenant_id')
                    ->label('Đơn vị')
                    ->relationship('tenant', 'name')
                    ->required(),
                Select::make('course_id')
                    ->label('Khóa học')
                    ->relationship('course', 'name')
                    ->required(),
                TextInput::make('sort_order')
                    ->label('Thứ tự')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('title')
                    ->label('Tên module')
                    ->required(),
                Textarea::make('description')
                    ->label('Mô tả')
                    ->columnSpanFull(),
                TextInput::make('duration_minutes')
                    ->label('Thời lượng phút')
                    ->numeric(),
                TagsInput::make('learning_objectives')
                    ->label('Mục tiêu học tập')
                    ->columnSpanFull(),
            ]);
    }
}
