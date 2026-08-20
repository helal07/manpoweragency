<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-shield-check';

    protected static \UnitEnum|string|null $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'Roles & Permissions';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        $allPermissions = Permission::where('guard_name', 'admin')->get();

        $websiteContentPerms = $allPermissions->filter(fn ($p) => str_contains($p->name, 'hero') || str_contains($p->name, 'leader') || str_contains($p->name, 'service') || str_contains($p->name, 'client') || str_contains($p->name, 'about') || str_contains($p->name, 'contact') || str_contains($p->name, 'footer') || str_contains($p->name, 'website'))->pluck('name', 'id')->toArray();

        $recruitmentPerms = $allPermissions->filter(fn ($p) => str_contains($p->name, 'circular') || str_contains($p->name, 'application') || str_contains($p->name, 'applicant') || str_contains($p->name, 'notice'))->pluck('name', 'id')->toArray();

        $adminPerms = $allPermissions->filter(fn ($p) => str_contains($p->name, 'setting') || str_contains($p->name, 'custom_field') || str_contains($p->name, 'user') || str_contains($p->name, 'role'))->pluck('name', 'id')->toArray();

        return $schema
            ->components([
                Section::make('Role Details')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Role Name (Identifier)')
                                ->placeholder('e.g. site_manager, service_staff, hr_manager')
                                ->required()
                                ->unique(ignoreRecord: true),
                            Hidden::make('guard_name')
                                ->default('admin'),
                        ]),
                    ]),

                Section::make('Assigned Permissions')
                    ->description('Select which sections and capabilities this role has access to')
                    ->schema([
                        Section::make('Website Content Permissions')
                            ->description('Controls Hero Banners, Leaders, Services, Clients, About Page, Contact Info, and Footer')
                            ->collapsible()
                            ->schema([
                                CheckboxList::make('permissions')
                                    ->relationship('permissions', 'name', fn ($query) => $query->where('guard_name', 'admin')->whereIn('name', array_values($websiteContentPerms)))
                                    ->columns(2)
                                    ->bulkToggleable(),
                            ]),

                        Section::make('Recruitment Permissions')
                            ->description('Controls Job Circulars, Job Applications, Applicants, and Notices')
                            ->collapsible()
                            ->schema([
                                CheckboxList::make('permissions')
                                    ->relationship('permissions', 'name', fn ($query) => $query->where('guard_name', 'admin')->whereIn('name', array_values($recruitmentPerms)))
                                    ->columns(2)
                                    ->bulkToggleable(),
                            ]),

                        Section::make('Administration Permissions')
                            ->description('Controls Site Branding Settings, Custom Fields, Staff Users, and Role Management')
                            ->collapsible()
                            ->schema([
                                CheckboxList::make('permissions')
                                    ->relationship('permissions', 'name', fn ($query) => $query->where('guard_name', 'admin')->whereIn('name', array_values($adminPerms)))
                                    ->columns(2)
                                    ->bulkToggleable(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Role Name')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'super_admin' => 'danger',
                        'site_manager' => 'success',
                        'service_staff' => 'info',
                        default => 'warning',
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('permissions_count')
                    ->label('Total Permissions')
                    ->counts('permissions')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('users_count')
                    ->label('Assigned Staff Users')
                    ->counts('users')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make()
                    ->hidden(fn (Role $record) => $record->name === 'super_admin'),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
