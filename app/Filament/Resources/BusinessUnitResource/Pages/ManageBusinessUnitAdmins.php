<?php

namespace App\Filament\Resources\BusinessUnitResource\Pages;

use App\Filament\Resources\BusinessUnitResource;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select; // Add this import
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
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

    // Keep existing getTableQuery method
    protected function getTableQuery(): Builder
    {
        return $this->getRecord()
            ->admins()
            ->select('users.*') // Explicitly select from users table
            ->getQuery();
    }

    // Modify the table method to replace CreateAction with regular Action
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
                Action::make('add_admin')
                    ->label('Add Admin')
                    ->icon('heroicon-o-plus')
                    ->form([
                        Select::make('user_id')
                            ->label('Select User')
                            ->options(function () {
                                return User::query()
                                    ->where('cluster_id', $this->getRecord()->cluster_id)
                                    ->where('company_id', $this->getRecord()->company_id)
                                    ->whereDoesntHave('businessUnits', function ($query) {
                                        $query->where('business_unit_id', $this->getRecord()->id);
                                    })
                                    ->get()
                                    ->mapWithKeys(function ($user) {
                                        return [$user->id => $user->firstname . ' ' . $user->lastname . ' (' . $user->email . ')'];
                                    });
                            })
                            ->searchable()
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $this->assignAdmin($data['user_id']);
                    }),
            ]);
    }

    // Add this new method for assigning admin role
    protected function assignAdmin(int $userId): void
    {
        try {
            \DB::beginTransaction();

            $user = User::findOrFail($userId);

            // Assign business_unit_admin role
            $role = Role::where('name', 'business_unit_admin')->first();
            if ($role) {
                $user->assignRole($role);
            }

            // Add as admin to this business unit
            $this->getRecord()->admins()->attach($userId);

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

    // Keep the existing removeAdmin method
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
