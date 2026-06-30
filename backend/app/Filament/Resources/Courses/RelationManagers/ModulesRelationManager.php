<?php

namespace App\Filament\Resources\Courses\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ModulesRelationManager extends RelationManager
{
    protected static string $relationship = 'modules';

    protected static ?string $title = 'Module khóa học';

    protected static ?string $modelLabel = 'module khóa học';

    protected static ?string $pluralModelLabel = 'module khóa học';

    public function form(Schema $schema): Schema
    {
        $course = $this->getOwnerRecord();

        return $schema
            ->components([
                Hidden::make('tenant_id')
                    ->default($course->tenant_id),
                TextInput::make('sort_order')
                    ->label('Thứ tự')
                    ->required()
                    ->numeric()
                    ->default(fn () => (int) $course->modules()->max('sort_order') + 1),
                TextInput::make('title')
                    ->label('Tên module')
                    ->required()
                    ->maxLength(255),
                TextInput::make('duration_minutes')
                    ->label('Thời lượng phút')
                    ->numeric(),
                Textarea::make('description')
                    ->label('Mô tả')
                    ->columnSpanFull(),
                TagsInput::make('learning_objectives')
                    ->label('Mục tiêu học tập')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('sort_order')
                    ->label('Thứ tự')
                    ->sortable(),
                TextColumn::make('title')
                    ->label('Tên module')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('duration_minutes')
                    ->label('Phút')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('video_lessons_count')
                    ->label('Số video')
                    ->counts('videoLessons'),
                TextColumn::make('updated_at')
                    ->label('Cập nhật')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Thêm module')
                    ->mutateDataUsing(function (array $data): array {
                        $data['tenant_id'] = $this->getOwnerRecord()->tenant_id;

                        return $data;
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
