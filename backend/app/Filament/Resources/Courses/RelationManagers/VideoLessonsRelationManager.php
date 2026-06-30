<?php

namespace App\Filament\Resources\Courses\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class VideoLessonsRelationManager extends RelationManager
{
    protected static string $relationship = 'videoLessons';

    protected static ?string $title = 'Video bài học';

    protected static ?string $modelLabel = 'video bài học';

    protected static ?string $pluralModelLabel = 'video bài học';

    public function form(Schema $schema): Schema
    {
        $course = $this->getOwnerRecord();

        return $schema
            ->components([
                Hidden::make('tenant_id')
                    ->default($course->tenant_id),
                Hidden::make('course_id')
                    ->default($course->getKey()),
                Select::make('course_module_id')
                    ->label('Module')
                    ->options(fn (): array => $course->modules()->pluck('title', 'id')->all())
                    ->searchable()
                    ->preload(),
                TextInput::make('sort_order')
                    ->label('Thứ tự')
                    ->numeric()
                    ->default(fn () => (int) $course->videoLessons()->max('sort_order') + 1),
                TextInput::make('title')
                    ->label('Tên bài học')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, ?string $state, callable $set) => $operation === 'create' && $state ? $set('slug', Str::slug($state)) : null)
                    ->maxLength(255),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255),
                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'draft' => 'Nháp',
                        'published' => 'Đã xuất bản',
                        'archived' => 'Lưu trữ',
                    ])
                    ->default('draft')
                    ->required(),
                Select::make('video_provider')
                    ->label('Nền tảng video')
                    ->options([
                        'youtube' => 'YouTube',
                        'vimeo' => 'Vimeo',
                        'internal' => 'Nội bộ',
                        'other' => 'Khác',
                    ])
                    ->default('youtube')
                    ->required(),
                TextInput::make('duration_minutes')
                    ->label('Thời lượng phút')
                    ->numeric(),
                Select::make('access_level')
                    ->label('Quyền xem')
                    ->options([
                        'public' => 'Công khai',
                        'lead_magnet' => 'Đổi thông tin lead',
                        'student' => 'Học viên',
                        'internal' => 'Nội bộ',
                    ])
                    ->default('student')
                    ->required(),
                TextInput::make('video_url')
                    ->label('Video URL')
                    ->maxLength(1000)
                    ->columnSpanFull(),
                TextInput::make('thumbnail_url')
                    ->label('Ảnh thumbnail')
                    ->maxLength(1000)
                    ->columnSpanFull(),
                Textarea::make('summary')
                    ->label('Tóm tắt nội dung')
                    ->columnSpanFull(),
                DateTimePicker::make('published_at')
                    ->label('Ngày xuất bản'),
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
                    ->label('Bài học')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('courseModule.title')
                    ->label('Module')
                    ->searchable(),
                TextColumn::make('video_provider')
                    ->label('Nền tảng')
                    ->badge(),
                TextColumn::make('duration_minutes')
                    ->label('Phút')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('access_level')
                    ->label('Quyền xem')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'public' => 'Công khai',
                        'lead_magnet' => 'Đổi lead',
                        'student' => 'Học viên',
                        'internal' => 'Nội bộ',
                        default => $state ?? '-',
                    }),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'published' => 'success',
                        'archived' => 'gray',
                        default => 'warning',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'published' => 'Đã xuất bản',
                        'archived' => 'Lưu trữ',
                        default => 'Nháp',
                    }),
                TextColumn::make('published_at')
                    ->label('Xuất bản')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'draft' => 'Nháp',
                        'published' => 'Đã xuất bản',
                        'archived' => 'Lưu trữ',
                    ]),
                SelectFilter::make('access_level')
                    ->label('Quyền xem')
                    ->options([
                        'public' => 'Công khai',
                        'lead_magnet' => 'Đổi lead',
                        'student' => 'Học viên',
                        'internal' => 'Nội bộ',
                    ]),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Thêm video')
                    ->mutateDataUsing(function (array $data): array {
                        $data['tenant_id'] = $this->getOwnerRecord()->tenant_id;
                        $data['course_id'] = $this->getOwnerRecord()->getKey();

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
