<?php

namespace App\Filament\Resources\TeacherProfiles\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TeacherProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tenant_id')
                    ->relationship('tenant', 'name')
                    ->required(),
                Select::make('branch_id')
                    ->relationship('branch', 'name'),
                Select::make('person_id')
                    ->relationship('person', 'id')
                    ->required(),
                TextInput::make('teacher_code')
                    ->required(),
                TextInput::make('title'),
                Textarea::make('bio')
                    ->columnSpanFull(),
                TextInput::make('specialties'),
                TextInput::make('status')
                    ->required()
                    ->default('active'),
            ]);
    }
}
