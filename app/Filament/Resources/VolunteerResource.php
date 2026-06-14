<?php

namespace App\Filament\Resources;

use App\Actions\UserCreateField;
use App\Actions\VolunteerFields;
use App\Exports\VolunteerListExport;
use App\Filament\Resources\VolunteerResource\Pages;
use App\Filament\Resources\VolunteerResource\RelationManagers\EventsRelationManager;
use App\Imports\VolunteersImport;
use App\Models\User;
use App\Models\Volunteer;
use App\Filament\Resources\UserResource;
use App\Settings\MailSettings;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;

class VolunteerResource extends Resource
{
    protected static ?string $model = Volunteer::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell';

    public static function getNavigationUrl(): string
    {
        if (auth()->user()?->hasActiveRole('Volunteer')) {
            $volunteerId = auth()->id();
            return static::getUrl('view', ['record' => $volunteerId]);
        }

        return static::getUrl('index');
    }

    public static function getNavigationLabel(): string
    {
        if (auth()->user()?->hasActiveRole('Volunteer')) {
            return 'My Volunteer Profile';
        }
        return 'Volunteers';
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema( (new UserCreateField())->execute(false))
                    ->columnSpan(1),

                Forms\Components\Tabs::make()
                    ->schema([
                        Forms\Components\Tabs\Tab::make('Details')
                            ->icon('heroicon-o-information-circle')
                            ->schema((new VolunteerFields())->execute())
                            ->columns(),

                    ])
                    ->columnSpan([
                        'sm' => 1,
                        'lg' => 2
                    ]),
            ])
            ->columns(3);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $user = auth()->user();
                $activeRole = $user->activeRole();

                if ($user->isAdminRole()) {
                    // Ayala Super Admin / admin: see all volunteers
                    return $query;
                }

                if ($activeRole === 'External Partner') {
                    $bu = $user->currentBU();
                    if ($bu && $bu->cluster_id) {
                        // Scope to volunteers whose cluster matches this BU's cluster
                        return $query->where(function ($q) use ($bu, $user) {
                            $q->where('cluster_id', $bu->cluster_id)
                              ->orWhere('id', $user->id);
                        });
                    }
                    // No BU assigned — see only self
                    return $query->where('id', $user->id);
                }

                // Volunteer, Facilitator, and any other role — own record only
                return $query->where('id', $user->id);
            })
            ->columns([
                Tables\Columns\TextColumn::make('volunteer_id')
                    ->label('Volunteer ID')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('firstname')
                    ->label('Name')
                    ->formatStateUsing(fn (Model $record) => $record->firstname . ' ' . $record->lastname)
                    ->description(fn (Model $record) => $record->email)
                    ->searchable(['firstname', 'lastname']),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label('Verified At')
                    ->dateTime('M d, Y')
                    ->sortable(),
            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil-square')
                    ->url(fn (Volunteer $record) => UserResource::getUrl('edit', ['record' => $record->id]))
                    ->visible(fn (Volunteer $record) =>
                        auth()->user()->isAdminRole() ||
                        $record->id === auth()->id()
                    ),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => auth()->user()->isAdminRole()),
            ])
            ->headerActions([
                Tables\Actions\Action::make('download_template')
                    ->label('CSV Template')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('gray')
                    ->visible(fn () => auth()->user()->isAdminRole())
                    ->action(function () {
                        $headers = "volunteer_id,first_name,last_name,email,company,skills,emergency_contact_name,emergency_contact_number\n";
                        $example = "V001,Juan,Dela Cruz,juan@example.com,Ayala Corporation,\"Teaching,Mentoring\",Maria Dela Cruz,09171234567\n";
                        return Response::make($headers . $example, 200, [
                            'Content-Type'        => 'text/csv',
                            'Content-Disposition' => 'attachment; filename="volunteer-import-template.csv"',
                        ]);
                    }),

                Tables\Actions\Action::make('import_volunteers')
                    ->label('Import Volunteers')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('warning')
                    ->visible(fn () => auth()->user()->isAdminRole())
                    ->form([
                        Forms\Components\FileUpload::make('file')
                            ->label('CSV / Excel File')
                            ->acceptedFileTypes(['text/csv', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                            ->required()
                            ->disk('local')
                            ->directory('imports'),
                    ])
                    ->action(function (array $data) {
                        $import = new VolunteersImport();
                        Excel::import($import, storage_path('app/' . $data['file']));
                        Notification::make()
                            ->title('Import complete')
                            ->body("Created: {$import->created} · Updated: {$import->updated} · Skipped: {$import->skipped}")
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('export_all')
                    ->label('Export All')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->visible(fn () => auth()->user()->isAdminRole())
                    ->action(fn () => Excel::download(new VolunteerListExport(), 'volunteers-' . now()->format('Y-m-d') . '.xlsx')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('export')
                        ->label('Export Selected')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->visible(fn () => auth()->user()->isAdminRole())
                        ->action(fn (\Illuminate\Support\Collection $records) =>
                            Excel::download(
                                new VolunteerListExport($records->pluck('id')->toArray()),
                                'volunteers-selected-' . now()->format('Y-m-d') . '.xlsx'
                            )
                        ),
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()->isAdminRole()),
                ]),
            ]);
    }

    // public static function getRelations(): array
    // {
    //     return [
    //         EventsRelationManager::class
    //     ];
    // }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListVolunteers::route('/'),
            'create' => Pages\CreateVolunteer::route('/create'),
            'view'   => Pages\ViewVolunteer::route('/{record}'),
        ];
    }
}
