<?php

namespace App\Filament\Resources\BusinessUnitResource\Pages;

use App\Filament\Resources\BusinessUnitResource;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\Action;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Filament\Notifications\Notification;

class ManageBusinessUnitAdmins extends Page implements HasTable
{
    use InteractsWithTable;
    use InteractsWithRecord;

    protected static string $resource = BusinessUnitResource::class;

    protected static string $view = 'filament.resources.business-unit-resource.pages.manage-business-unit-admins';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    // Add this method
    protected function getTableQuery(): Builder
    {
        return $this->getRecord()
            ->admins()
            ->select('users.*') // Explicitly select from users table
            ->getQuery();
    }

    // Keep this method
    public function table(Table $table): Table
{
    return $table
        ->query($this->getTableQuery())
        ->columns([
            TextColumn::make('firstname')
                ->label('First Name')
                ->sortable()
                ->searchable(),
            TextColumn::make('lastname')
                ->label('Last Name')
                ->sortable()
                ->searchable(),
            TextColumn::make('email')
                ->sortable()
                ->searchable(),
            TextColumn::make('created_at')
                ->dateTime('M d, Y h:i A')
                ->sortable(),
        ])
        ->actions([
            Action::make('remove')
                ->color('danger')
                ->icon('heroicon-o-trash')
                ->requiresConfirmation()
                ->action(fn ($record) => $this->removeAdmin($record)),
        ])
        ->headerActions([
            CreateAction::make('add_admin')
                ->label('Add New Admin')
                ->icon('heroicon-o-plus')
                ->form([
                    TextInput::make('firstname')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('lastname')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->unique(User::class)
                        ->maxLength(255),
                    TextInput::make('password')
                        ->password()
                        ->required()
                        ->minLength(8)
                        ->confirmed(),
                    TextInput::make('password_confirmation')
                        ->password()
                        ->required()
                        ->label('Confirm Password'),
                ])
                ->action(function (array $data) {
                    $this->createAdmin($data);
                }),
        ]);
}

    protected function createAdmin(array $data): void
    {
        try {
            \DB::beginTransaction();

            $userData = array_merge($data, [
                'email_verified_at' => now(),
                'cluster_id' => $this->getRecord()->cluster_id,
                'company_id' => $this->getRecord()->company_id,
                'password' => Hash::make($data['password']),
                'password_changed_at' => null
            ]);

            $user = User::create($userData);

            // Assign External Partner role
            $role = Role::where('name', 'External Partner')->first();
            $user->assignRole($role);

            // Add as admin to this business unit
            $this->getRecord()->admins()->attach($user->id);

            \DB::commit();

            Notification::make()
                ->title('Admin Added Successfully')
                ->success()
                ->send();

        } catch (\Exception $e) {
            \DB::rollBack();

            Notification::make()
                ->title('Error Adding Admin')
                ->danger()
                ->body($e->getMessage())
                ->send();
        }
    }

    protected function removeAdmin(User $user): void
    {
        $this->getRecord()->admins()->detach($user->id);

        Notification::make()
            ->title('Admin Removed Successfully')
            ->success()
            ->send();
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-users';
    }
}
