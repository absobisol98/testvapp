# Role Switching

This document explains how multi-role users can switch between their roles without logging out.

## Overview

A user can hold multiple Spatie Permission roles (e.g., `admin` **and** `Volunteer`). At any time one role is "active," which controls what navigation items, labels, and data scopes they see inside the Filament admin panel.

The active role is stored in the PHP session and persists across page loads until the user switches again or logs out.

## Roles Available

| Role | What they see |
|------|---------------|
| `super_admin` | Full access to everything |
| `Ayala Super Admin` | Same as super_admin |
| `admin` | Manage events, volunteers, reports |
| `Facilitator` | Assigned event management |
| `External Partner` | Company-scoped view |
| `Volunteer` | Own profile & registrations only |

## How It Works

### Default role on login

When a user logs in, `SetActiveRole` middleware runs on the first authenticated request and writes the default active role to the session. Priority order:

```
super_admin → Ayala Super Admin → admin → Facilitator → External Partner → Volunteer
```

The highest-priority role the user **actually holds** becomes the default.

### Switching roles

A dropdown appears in the top-right of the topbar **only when the user holds more than one role**. Clicking a role name calls `RoleSwitcher::switchRole()`, which:

1. Validates the user actually holds that role.
2. Updates `active_role` in the session.
3. Reloads the current page so navigation and data scopes refresh.

### Where active-role checks are used

| File | Method | Effect |
|------|--------|--------|
| `VolunteerResource` | `getNavigationLabel()` | Shows "My Volunteer Profile" vs "Volunteers" |
| `VolunteerResource` | `getNavigationUrl()` | Links directly to own profile vs the list |
| `VolunteerResource` | `table()->modifyQueryUsing()` | Restricts list to own record when not an admin role |
| `EventResource` | `getNavigationLabel()` | Shows "My Volunteer Opportunities" vs "Volunteer Opportunities" |

### Adding new role-aware behaviour

Use `auth()->user()->hasActiveRole('RoleName')` anywhere you previously used `hasRole('RoleName')` for **view-level** differentiation. Keep using `hasRole()` for **permission guards** (creating, deleting, etc.) where the check should be independent of which role is currently active.

## Key Files

```
app/Models/User.php                              — activeRole(), hasActiveRole(), switchRole(), availableRoles()
app/Http/Middleware/SetActiveRole.php            — seeds session on first authenticated request
app/Livewire/RoleSwitcher.php                   — Livewire component handling the dropdown
resources/views/livewire/role-switcher.blade.php — dropdown UI
app/Providers/Filament/AdminPanelProvider.php   — registers middleware + topbar render hook
```

## Adding a New Role

1. Create the role via `php artisan shield:generate` or a seeder.
2. Decide where it falls in the priority list in `User::activeRole()` and add it.
3. Use `hasActiveRole('YourRole')` in resources/pages to customise the experience.
