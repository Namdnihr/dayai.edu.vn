<?php

namespace App\Filament\Resources\GuardianRelations\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class GuardianRelationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tenant_id')
                    ->relationship('tenant', 'name')
                    ->required(),
                Select::make('guardian_person_id')
                    ->relationship('guardianPerson', 'id')
                    ->required(),
                Select::make('student_profile_id')
                    ->relationship('studentProfile', 'id')
                    ->required(),
                TextInput::make('relation_type')
                    ->required()
                    ->default('guardian'),
                Toggle::make('is_primary')
                    ->required(),
                Toggle::make('can_view_finance')
                    ->required(),
                Toggle::make('can_view_progress')
                    ->required(),
                Toggle::make('can_receive_notifications')
                    ->required(),
            ]);
    }
}
