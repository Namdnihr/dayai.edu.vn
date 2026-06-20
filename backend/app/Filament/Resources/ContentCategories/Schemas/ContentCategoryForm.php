<?php

namespace App\Filament\Resources\ContentCategories\Schemas;

use App\Models\Tenant;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ContentCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tenant_id')
                    ->label('Đơn vị')
                    ->relationship('tenant', 'name')
                    ->default(fn () => Tenant::query()->value('id'))
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('name')
                    ->label('Tên danh mục')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255),
                Select::make('category_type')
                    ->label('Loại danh mục')
                    ->options([
                        'knowledge' => 'Kho kiến thức',
                        'video' => 'Video Academy',
                        'news' => 'Tin tức',
                        'resource' => 'Tài nguyên',
                    ])
                    ->default('knowledge')
                    ->required(),
                Textarea::make('description')
                    ->label('Mô tả')
                    ->columnSpanFull(),
                TextInput::make('seo_title')
                    ->label('SEO title')
                    ->maxLength(255),
                Textarea::make('seo_description')
                    ->label('SEO description')
                    ->maxLength(500)
                    ->columnSpanFull(),
                TextInput::make('canonical_url')
                    ->label('Canonical URL')
                    ->maxLength(1000)
                    ->columnSpanFull(),
                TextInput::make('sort_order')
                    ->label('Thứ tự')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->label('Đang hiển thị')
                    ->default(true),
            ]);
    }
}
