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
            Action::make('add_admin')
                ->label('Add Admin')
                ->icon('heroicon-o-plus')
                ->form([
                    \Filament\Forms\Components\Select::make('user_id')
                        ->label('User')
                        ->options(function () {
                            return User::where('cluster_id', $this->getRecord()->cluster_id)
                                // Exclude users who are already admins
                                ->whereNotIn('id', $this->getRecord()->admins()->pluck('users.id'))
                                ->get()
                                ->mapWithKeys(function ($user) {
                                    return [$user->id => "{$user->firstname} {$user->lastname} ({$user->email})"];
                                })
                                ->toArray();
                        })
                        ->searchable()
                        ->required(),
                ])
                ->action(function (array $data) {
                    $this->addExistingAdmin($data['user_id']);
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


    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-users';
    }

    protected function removeAdmin(User $user): void
    {
        try {
            \DB::beginTransaction();
            
            // Detach user from business unit admins
            $this->getRecord()->admins()->detach($user->id);
            
            // Check if user is still admin of any other business unit
            $isStillAdmin = $user->businessUnitsAdmin()->exists();
            
            // If not admin of any other business unit, switch role back to Volunteer
            if (!$isStillAdmin) {
                // Remove External Partner role
                if ($user->hasRole('External Partner')) {
                    $user->removeRole('External Partner');
                }
                
                // Assign Volunteer role
                if (!$user->hasRole('Volunteer')) {
                    $volunteerRole = Role::where('name', 'Volunteer')->first();
                    $user->assignRole($volunteerRole);
                }
                
                // Update volunteer column to 1
                $user->volunteer = 1;
                $user->save();
            }
            
            \DB::commit();
            
            Notification::make()
                ->title('Admin Removed Successfully')
                ->success()
                ->send();
                
        } catch (\Exception $e) {
            \DB::rollBack();
            
            Notification::make()
                ->title('Error Removing Admin')
                ->danger()
                ->body($e->getMessage())
                ->send();
        }
    }
    
    protected function addExistingAdmin(int $userId): void
    {
        try {
            \DB::beginTransaction();
            
            $user = User::findOrFail($userId);
            
            // Remove Volunteer role if it exists
            if ($user->hasRole('Volunteer')) {
                $user->removeRole('Volunteer');
            }
            
            // Assign External Partner role if not already assigned
            if (!$user->hasRole('External Partner')) {
                $role = Role::where('name', 'External Partner')->first();
                $user->assignRole($role);
            }
            
            // Set volunteer column to 0 since they're now an External Partner
            $user->volunteer = 0;
            $user->save();
            
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
}
