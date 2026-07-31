<?php

namespace App\Filament\Resources\VideoLessons\Schemas;

use App\Models\Course;
use App\Models\CourseModule;
use App\Models\Tenant;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Str;

class VideoLessonFormComponents
{
    public static function make(?Course $course = null): array
    {
        return [
            Section::make('1. Vị trí trong khóa học')
                ->description('Chọn khóa học, module và thứ tự xuất hiện của bài.')
                ->schema([
                    ...self::ownershipFields($course),
                    Hidden::make('metadata.category_id'),
                    TextInput::make('sort_order')
                        ->label('Thứ tự bài học')
                        ->numeric()
                        ->minValue(0)
                        ->default(fn () => $course ? (int) $course->videoLessons()->max('sort_order') + 1 : 0),
                ])
                ->columns(2),

            Section::make('2. Nội dung bài học')
                ->description('Thông tin học viên nhìn thấy trong lộ trình.')
                ->schema([
                    TextInput::make('title')
                        ->label('Tên bài học')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (string $operation, ?string $state, callable $set) => $operation === 'create' && $state ? $set('slug', Str::slug($state)) : null)
                        ->maxLength(255)
                        ->columnSpanFull(),
                    TextInput::make('slug')
                        ->label('Đường dẫn bài học')
                        ->helperText('Tự sinh từ tên bài và có thể chỉnh trước khi xuất bản.')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('duration_minutes')
                        ->label('Thời lượng dự kiến')
                        ->suffix('phút')
                        ->numeric()
                        ->minValue(0),
                    Textarea::make('summary')
                        ->label('Tóm tắt nội dung')
                        ->rows(3)
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Section::make('3. Video và tài liệu')
                ->description('Tải video nội bộ hoặc dán liên kết từ nền tảng bên ngoài.')
                ->schema([
                    Select::make('video_provider')
                        ->label('Nguồn video')
                        ->options([
                            'internal' => 'Tải video lên DAYAI',
                            'youtube' => 'YouTube',
                            'vimeo' => 'Vimeo',
                            'other' => 'Liên kết khác',
                        ])
                        ->default('internal')
                        ->live()
                        ->required(),
                    FileUpload::make('video_storage_path')
                        ->label('Tệp video')
                        ->helperText('MP4/WebM, tối đa 64 MB trong môi trường hiện tại.')
                        ->disk('public')
                        ->directory('course-videos')
                        ->visibility('public')
                        ->acceptedFileTypes(['video/mp4', 'video/webm'])
                        ->maxSize(65536)
                        ->moveFiles()
                        ->downloadable()
                        ->visible(fn (callable $get): bool => $get('video_provider') === 'internal')
                        ->columnSpanFull(),
                    TextInput::make('video_url')
                        ->label('URL video')
                        ->placeholder('https://www.youtube.com/watch?v=...')
                        ->url()
                        ->maxLength(1000)
                        ->visible(fn (callable $get): bool => $get('video_provider') !== 'internal')
                        ->columnSpanFull(),
                    TextInput::make('thumbnail_url')
                        ->label('URL ảnh thumbnail')
                        ->url()
                        ->maxLength(1000)
                        ->columnSpanFull(),
                    Repeater::make('resources')
                        ->label('Tài liệu đi kèm')
                        ->addActionLabel('Thêm tài liệu')
                        ->reorderableWithButtons()
                        ->collapsible()
                        ->itemLabel(fn (array $state): ?string => $state['title'] ?? 'Tài liệu mới')
                        ->schema([
                            TextInput::make('title')
                                ->label('Tên tài liệu')
                                ->required()
                                ->live(onBlur: true),
                            Select::make('type')
                                ->label('Loại')
                                ->options([
                                    'worksheet' => 'Worksheet',
                                    'document' => 'Tài liệu',
                                    'prompt' => 'Prompt',
                                    'link' => 'Liên kết',
                                ])
                                ->default('document'),
                            FileUpload::make('file_path')
                                ->label('Tải tệp lên')
                                ->disk('public')
                                ->directory('course-resources')
                                ->visibility('public')
                                ->maxSize(20480)
                                ->moveFiles()
                                ->downloadable()
                                ->columnSpanFull(),
                            TextInput::make('url')
                                ->label('Hoặc URL bên ngoài')
                                ->url()
                                ->maxLength(1000)
                                ->columnSpanFull(),
                        ])
                        ->columns(2)
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Section::make('4. Câu hỏi tương tác trong video')
                ->description('Video tự dừng tại từng mốc và yêu cầu học viên trả lời trước khi tiếp tục.')
                ->schema([
                    Toggle::make('metadata.interactive_learning.required_for_completion')
                        ->label('Bắt buộc trả lời đủ để hoàn thành bài')
                        ->default(true)
                        ->columnSpanFull(),
                    Repeater::make('metadata.interactive_learning.checkpoints')
                        ->label('Checkpoint')
                        ->addActionLabel('Thêm câu hỏi')
                        ->reorderableWithButtons()
                        ->collapsible()
                        ->itemLabel(fn (array $state): ?string => $state['question'] ?? 'Câu hỏi mới')
                        ->schema([
                            Hidden::make('id')
                                ->default(fn (): string => 'checkpoint-'.Str::lower(Str::random(10))),
                            TextInput::make('at_seconds')
                                ->label('Xuất hiện tại')
                                ->helperText('Ví dụ: 90 = phút 1:30')
                                ->suffix('giây')
                                ->required()
                                ->numeric()
                                ->minValue(0),
                            TextInput::make('time_limit_seconds')
                                ->label('Thời gian trả lời')
                                ->suffix('giây')
                                ->required()
                                ->numeric()
                                ->minValue(5)
                                ->default(30),
                            Textarea::make('question')
                                ->label('Nội dung câu hỏi')
                                ->required()
                                ->live(onBlur: true)
                                ->rows(2)
                                ->columnSpanFull(),
                            Repeater::make('options')
                                ->label('Các đáp án')
                                ->addActionLabel('Thêm đáp án')
                                ->minItems(2)
                                ->maxItems(5)
                                ->defaultItems(3)
                                ->reorderableWithButtons()
                                ->schema([
                                    Hidden::make('id')
                                        ->default(fn (): string => 'option-'.Str::lower(Str::random(10))),
                                    TextInput::make('label')
                                        ->label('Nội dung đáp án')
                                        ->required()
                                        ->live(onBlur: true)
                                        ->columnSpanFull(),
                                ])
                                ->columnSpanFull(),
                            Select::make('correct_option_id')
                                ->label('Đáp án đúng')
                                ->options(function (callable $get): array {
                                    return collect($get('options') ?? [])
                                        ->filter(fn ($option): bool => filled($option['id'] ?? null) && filled($option['label'] ?? null))
                                        ->mapWithKeys(fn (array $option): array => [$option['id'] => $option['label']])
                                        ->all();
                                })
                                ->required(),
                            Textarea::make('explanation')
                                ->label('Giải thích sau khi trả lời')
                                ->required()
                                ->rows(2)
                                ->columnSpanFull(),
                        ])
                        ->columns(2)
                        ->columnSpanFull(),
                ]),

            Section::make('5. Nội dung theo mốc')
                ->description('Các ý chính có thể tìm kiếm và bấm để quay lại đúng đoạn video.')
                ->collapsed()
                ->schema([
                    Repeater::make('metadata.interactive_learning.timeline_notes')
                        ->label('Mốc nội dung')
                        ->addActionLabel('Thêm mốc nội dung')
                        ->reorderableWithButtons()
                        ->collapsible()
                        ->itemLabel(fn (array $state): ?string => $state['title'] ?? 'Mốc mới')
                        ->schema([
                            TextInput::make('at_seconds')
                                ->label('Thời điểm')
                                ->suffix('giây')
                                ->required()
                                ->numeric()
                                ->minValue(0),
                            TextInput::make('title')
                                ->label('Tiêu đề')
                                ->required()
                                ->live(onBlur: true),
                            Textarea::make('text')
                                ->label('Nội dung tóm tắt')
                                ->required()
                                ->rows(2)
                                ->columnSpanFull(),
                        ])
                        ->columns(2)
                        ->columnSpanFull(),
                ]),

            Section::make('6. Prompt thực hành')
                ->description('Câu lệnh để học viên sao chép và thực hành ngay trong bài.')
                ->collapsed()
                ->schema([
                    Repeater::make('metadata.interactive_learning.prompt_notes')
                        ->label('Prompt mẫu')
                        ->addActionLabel('Thêm prompt')
                        ->reorderableWithButtons()
                        ->collapsible()
                        ->itemLabel(fn (array $state): ?string => $state['title'] ?? 'Prompt mới')
                        ->schema([
                            Hidden::make('id')
                                ->default(fn (): string => 'prompt-'.Str::lower(Str::random(10))),
                            TextInput::make('title')
                                ->label('Tên bài thực hành')
                                ->required()
                                ->live(onBlur: true)
                                ->columnSpanFull(),
                            Textarea::make('prompt')
                                ->label('Câu lệnh mẫu')
                                ->required()
                                ->rows(5)
                                ->columnSpanFull(),
                        ])
                        ->columnSpanFull(),
                ]),

            Section::make('7. Quyền xem và xuất bản')
                ->schema([
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
                        ->label('Trạng thái')
                        ->options([
                            'draft' => 'Bản nháp',
                            'published' => 'Đã xuất bản',
                            'archived' => 'Lưu trữ',
                        ])
                        ->default('draft')
                        ->required(),
                    DateTimePicker::make('published_at')
                        ->label('Thời điểm xuất bản')
                        ->seconds(false),
                ])
                ->columns(3),

            Section::make('SEO nâng cao')
                ->collapsed()
                ->schema([
                    TextInput::make('seo_title')
                        ->label('SEO title')
                        ->maxLength(255),
                    TextInput::make('canonical_url')
                        ->label('Canonical URL')
                        ->url()
                        ->maxLength(1000),
                    Textarea::make('seo_description')
                        ->label('SEO description')
                        ->maxLength(500)
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ];
    }

    protected static function ownershipFields(?Course $course): array
    {
        if ($course) {
            return [
                Hidden::make('tenant_id')->default($course->tenant_id),
                Hidden::make('course_id')->default($course->getKey()),
                Select::make('course_module_id')
                    ->label('Module')
                    ->options(fn (): array => $course->modules()->pluck('title', 'id')->all())
                    ->searchable()
                    ->preload()
                    ->required(),
            ];
        }

        return [
            Select::make('tenant_id')
                ->label('Đơn vị')
                ->relationship('tenant', 'name')
                ->default(fn () => Tenant::query()->value('id'))
                ->searchable()
                ->preload()
                ->required(),
            Select::make('course_id')
                ->label('Khóa học')
                ->options(fn (callable $get): array => Course::query()
                    ->when($get('tenant_id'), fn ($query, $tenantId) => $query->where('tenant_id', $tenantId))
                    ->orderBy('name')
                    ->pluck('name', 'id')
                    ->all())
                ->searchable()
                ->preload()
                ->live()
                ->afterStateUpdated(fn (callable $set) => $set('course_module_id', null))
                ->required(),
            Select::make('course_module_id')
                ->label('Module')
                ->options(fn (callable $get): array => CourseModule::query()
                    ->when(
                        $get('course_id'),
                        fn ($query, $courseId) => $query->where('course_id', $courseId),
                        fn ($query) => $query->whereRaw('1 = 0'),
                    )
                    ->orderBy('sort_order')
                    ->pluck('title', 'id')
                    ->all())
                ->searchable()
                ->preload()
                ->disabled(fn (callable $get): bool => blank($get('course_id')))
                ->required(),
        ];
    }
}
