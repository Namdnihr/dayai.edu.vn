<?php

namespace App\Filament\Resources\Courses\RelationManagers;

use App\Filament\Resources\VideoLessons\Schemas\VideoLessonFormComponents;
use App\Models\VideoLesson;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VideoLessonsRelationManager extends RelationManager
{
    protected static string $relationship = 'videoLessons';

    protected static ?string $title = 'Chương trình học';

    protected static ?string $modelLabel = 'video bài học';

    protected static ?string $pluralModelLabel = 'video bài học';

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        return (string) $ownerRecord->videoLessons()->count();
    }

    public function form(Schema $schema): Schema
    {
        $course = $this->getOwnerRecord();

        return $schema->components(VideoLessonFormComponents::make($course));
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->reorder())
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->groups([
                Group::make('courseModule.title')
                    ->label('Module')
                    ->getTitleFromRecordUsing(
                        fn (VideoLesson $record): string => $record->courseModule?->title
                            ?? 'Video chung / chưa phân module',
                    )
                    ->collapsible(),
            ])
            ->defaultGroup('courseModule.title')
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
                Action::make('uploadSeries')
                    ->label('Tải nhiều video')
                    ->color('success')
                    ->modalHeading('Tải một loạt bài video vào khóa học')
                    ->modalDescription('Chọn một module và tải nhiều video nhỏ. Hệ thống sẽ tự tạo một bài học cho mỗi tệp theo đúng thứ tự.')
                    ->modalSubmitActionLabel('Tạo các bài video')
                    ->schema([
                        Select::make('course_module_id')
                            ->label('Module nhận video')
                            ->options(fn (): array => $this->getOwnerRecord()
                                ->modules()
                                ->pluck('title', 'id')
                                ->all())
                            ->default(fn () => $this->getOwnerRecord()->modules()->value('id'))
                            ->searchable()
                            ->preload()
                            ->required(),
                        FileUpload::make('videos')
                            ->label('Các video bài học')
                            ->helperText('Có thể chọn tối đa 50 tệp MP4/WebM. Mỗi tệp tối đa 64 MB và sẽ trở thành một bài học riêng.')
                            ->disk('public')
                            ->directory('course-videos')
                            ->visibility('public')
                            ->acceptedFileTypes(['video/mp4', 'video/webm'])
                            ->maxSize(65536)
                            ->multiple()
                            ->maxFiles(50)
                            ->reorderable()
                            ->storeFileNamesIn('original_names')
                            ->moveFiles()
                            ->required()
                            ->columnSpanFull(),
                        Hidden::make('original_names'),
                        TextInput::make('title_prefix')
                            ->label('Tiền tố tên bài')
                            ->placeholder('Ví dụ: Bài')
                            ->helperText('Để trống nếu muốn dùng nguyên tên tệp.'),
                        TextInput::make('duration_minutes')
                            ->label('Thời lượng mặc định mỗi video')
                            ->suffix('phút')
                            ->numeric()
                            ->minValue(0)
                            ->default(5),
                        Select::make('access_level')
                            ->label('Quyền xem')
                            ->options([
                                'public' => 'Công khai',
                                'lead_magnet' => 'Học thử / đổi thông tin lead',
                                'student' => 'Chỉ học viên đã ghi danh',
                                'internal' => 'Nội bộ',
                            ])
                            ->default('student')
                            ->required(),
                        Select::make('status')
                            ->label('Trạng thái ban đầu')
                            ->options([
                                'draft' => 'Bản nháp',
                                'published' => 'Đã xuất bản',
                            ])
                            ->default('draft')
                            ->required(),
                    ])
                    ->action(function (array $data): void {
                        $course = $this->getOwnerRecord();
                        $module = $course->modules()->findOrFail($data['course_module_id']);
                        $videos = $data['videos'] ?? [];
                        $originalNames = $data['original_names'] ?? [];
                        $createdCount = 0;

                        DB::transaction(function () use (
                            $course,
                            $module,
                            $videos,
                            $originalNames,
                            $data,
                            &$createdCount,
                        ): void {
                            $nextSortOrder = (int) $module->videoLessons()->max('sort_order') + 1;

                            foreach ($videos as $key => $path) {
                                if (! is_string($path) || blank($path)) {
                                    continue;
                                }

                                $originalName = $originalNames[$key] ?? basename($path);
                                $baseName = pathinfo((string) $originalName, PATHINFO_FILENAME);
                                $readableName = Str::of($baseName)
                                    ->replace(['-', '_'], ' ')
                                    ->squish()
                                    ->toString();
                                $title = filled($data['title_prefix'] ?? null)
                                    ? trim($data['title_prefix']).' '.$nextSortOrder.': '.$readableName
                                    : Str::ucfirst($readableName);
                                $title = filled($title) ? $title : 'Bài học '.$nextSortOrder;
                                $slugBase = Str::slug($title) ?: 'bai-hoc';
                                $slug = $slugBase;
                                $suffix = 2;

                                while (VideoLesson::withTrashed()
                                    ->where('tenant_id', $course->tenant_id)
                                    ->where('slug', $slug)
                                    ->exists()) {
                                    $slug = $slugBase.'-'.$suffix;
                                    $suffix++;
                                }

                                VideoLesson::query()->create([
                                    'tenant_id' => $course->tenant_id,
                                    'course_id' => $course->getKey(),
                                    'course_module_id' => $module->getKey(),
                                    'sort_order' => $nextSortOrder,
                                    'title' => $title,
                                    'slug' => $slug,
                                    'status' => $data['status'],
                                    'video_provider' => 'internal',
                                    'video_storage_path' => $path,
                                    'duration_minutes' => $data['duration_minutes'] ?? null,
                                    'access_level' => $data['access_level'],
                                    'resources' => [],
                                    'metadata' => [
                                        'interactive_learning' => [
                                            'required_for_completion' => false,
                                            'checkpoints' => [],
                                            'timeline_notes' => [],
                                            'prompt_notes' => [],
                                        ],
                                    ],
                                    'published_at' => $data['status'] === 'published' ? now() : null,
                                ]);

                                $nextSortOrder++;
                                $createdCount++;
                            }
                        });

                        Notification::make()
                            ->title("Đã tạo {$createdCount} bài video")
                            ->body("Các video đã được xếp vào module “{$module->title}”. Bạn có thể mở từng bài để thêm câu hỏi và prompt.")
                            ->success()
                            ->send();
                    })
                    ->disabled(fn (): bool => ! $this->getOwnerRecord()->modules()->exists())
                    ->tooltip(fn (): ?string => $this->getOwnerRecord()->modules()->exists()
                        ? null
                        : 'Hãy tạo ít nhất một module trước khi tải video hàng loạt.'),
                CreateAction::make()
                    ->label('Thêm từng video')
                    ->slideOver()
                    ->modalWidth('7xl')
                    ->mutateDataUsing(function (array $data): array {
                        $data['tenant_id'] = $this->getOwnerRecord()->tenant_id;
                        $data['course_id'] = $this->getOwnerRecord()->getKey();

                        return $data;
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->slideOver()
                    ->modalWidth('7xl'),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
