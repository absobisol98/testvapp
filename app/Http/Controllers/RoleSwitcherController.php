<?php

namespace App\Http\Controllers;

use App\Models\UserRoleHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Spatie\Permission\Models\Role;

class RoleSwitcherController extends Controller
{
    public function switchRole(Request $request, $role)
    {
        $user = Auth::user();
        $previousRole = $user->active_role;
        $currentRoles = $user->roles()->pluck('name')->toArray();

        if (!method_exists($user, 'canSwitchRoles') || !$user->canSwitchRoles()) {
            Notification::make()
                ->title('Permission Denied')
                ->body('You do not have permission to switch roles.')
                ->danger()
                ->send();

            return redirect()->back();
        }

        if ($role === 'volunteer') {
            // Store current admin roles before switching
            $adminRoles = array_diff($currentRoles, ['volunteer']);

            if (!empty($adminRoles)) {
                // Save the current roles for switching back later
                UserRoleHistory::create([
                    'user_id' => $user->id,
                    'previous_role' => $previousRole,
                    'current_role' => 'volunteer',
                    'can_switch_back' => true,
                    'stored_roles' => json_encode($adminRoles) // Store roles for later
                ]);
            }

            // Remove all roles except volunteer
            foreach ($adminRoles as $adminRole) {
                $user->removeRole($adminRole);
            }

            // Ensure user has volunteer role
            if (!$user->hasRole('volunteer')) {
                $user->assignRole('volunteer');
            }

            // Switch to volunteer mode
            $user->volunteer = true;
            $user->active_role = 'volunteer';

            $message = 'Switched to Volunteer mode.';
            $currentRole = 'volunteer';
        } else if ($role === 'admin' ) {
            // Get the stored admin roles if available
            $latestHistory = UserRoleHistory::where('user_id', $user->id)
                ->where('current_role', 'volunteer')
                ->where('can_switch_back', true)
                ->latest()
                ->first();

            if ($latestHistory && !empty($latestHistory->stored_roles)) {
                $storedRoles = json_decode($latestHistory->stored_roles, true);

                // Remove volunteer role
                $user->removeRole('volunteer');

                // Restore the stored admin roles
                foreach ($storedRoles as $storedRole) {
                    $user->assignRole($storedRole);
                }

                // Set active role to the previous admin role
                $activeRole = $latestHistory->previous_role;
                $user->active_role = $activeRole;
                $currentRole = $activeRole;

                // Mark history as used
                $latestHistory->can_switch_back = false;
                $latestHistory->save();
            } else {
                // Fallback - get current admin roles
                $adminRoles = $user->roles()
                    ->where('name', '!=', 'volunteer')
                    ->pluck('name')
                    ->toArray();

                if (empty($adminRoles)) {
                    Notification::make()
                        ->title('Error')
                        ->body('No admin role found for this user.')
                        ->danger()
                        ->send();

                    return redirect()->back();
                }

                // Remove volunteer role
                $user->removeRole('volunteer');

                // If user has an active admin role already, use that
                if ($user->active_role && in_array($user->active_role, $adminRoles)) {
                    $activeRole = $user->active_role;
                } else {
                    // Otherwise use the first admin role
                    $activeRole = reset($adminRoles);
                }

                $user->active_role = $activeRole;
                $currentRole = $activeRole;
            }

            // Set volunteer flag to false
            $user->volunteer = false;

            // Format role name for display
            $roleLabel = match($user->active_role) {
                'super_admin' => 'Super Admin',
                'Ayala Super Admin' => 'Ayala Super Admin',
                'Business Unit Admin' => 'Business Unit Admin',
                'External Partner' => 'External Partner Admin',
                'admin' => 'Admin',
                default => ucwords(str_replace('_', ' ', $user->active_role)),
            };

            $message = "Switched to {$roleLabel} mode.";
        } else {
            // Direct role switching is not allowed
            Notification::make()
                ->title('Permission Denied')
                ->body('You cannot switch directly to a specific role. Use the provided switches.')
                ->danger()
                ->send();

            return redirect()->back();
        }

        $user->save();

        Notification::make()
            ->title('Role Switched')
            ->body($message)
            ->success()
            ->send();

        return redirect()->back();
    }
}
