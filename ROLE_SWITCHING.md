# Role Switching & Role Definitions

This document explains the role system, intended permission boundaries, and how multi-role users can switch between roles without logging out.

## Role Definitions

| Role | Who they are | What they manage |
|------|-------------|-----------------|
| `Ayala Super Admin` | Ayala Foundation IT / system owners | Full access to everything — bypasses all permission checks (Filament Shield super-admin) |
| `admin` | BU staff who create and manage volunteer opportunities | Create/edit opportunities for their Business Unit, manage registrations, send announcements |
| `author` | Content team members | Create/publish blog posts and announcements **only** — NOT volunteer opportunities. Same dashboard as `admin` but Shield permissions should restrict them to content resources |
| `Facilitator` | Nominated by admins to run events | Scan QR attendance, manage attendees for events they are assigned to; cannot create opportunities |
| `External Partner` | External company admins | Manage opportunities and volunteers scoped to their Business Unit; have their own workspace view |
| `Volunteer` | End-user volunteers | Register for opportunities, view own profile and history, earn certificates |

> **`admin` vs `author`:** These are **not duplicates**. An `admin` manages volunteer opportunities; an `author` manages blog/content. A user can hold both if they do both jobs. Enforce the boundary via Filament Shield permissions — `author` should have access to the blog resource, NOT to EventResource create/edit.

## Default Active-Role Priority

When a user logs in, the first role that matches in this list becomes active:

```
Ayala Super Admin → admin → author → Facilitator → External Partner → Volunteer
```

## Dashboard per Role

| Active Role | Dashboard widgets shown |
|-------------|------------------------|
| `Ayala Super Admin` | AFI Admin Opportunities · Business Unit overview · Volunteers table |
| `admin` | Same as Ayala Super Admin |
| `author` | Same as admin (restrict via Shield permissions) |
| `Facilitator` | **My Facilitated Events** table (events they are nominated for) |
| `External Partner` | Partner ongoing opportunities · Partner opportunities · Facilitator list |
| `Volunteer` | Ads · Upcoming opportunities feed |

## How Role Switching Works

A dropdown appears in the topbar **only when the user holds more than one role**. Clicking a role:

1. Validates the user actually holds that role.
2. Updates `active_role` in the PHP session.
3. Reloads the current page so navigation, labels, and data scopes refresh.

### Where active-role checks are used

| File | Effect |
|------|--------|
| `dashboard.blade.php` | Shows role-specific widget blocks |
| `HeroBannerWidget` | Shows role-specific stat cards |
| `VolunteerResource::getNavigationLabel()` | "My Volunteer Profile" vs "Volunteers" |
| `VolunteerResource::getNavigationUrl()` | Own profile vs list index |
| `VolunteerResource` table query | Restricts to own record for non-admin roles |
| `EventResource::getNavigationLabel()` | "My Volunteer Opportunities" vs "Volunteer Opportunities" |
| `User::currentBU()` | Returns BU only when active role is External Partner |

### Rule of thumb

- Use `hasActiveRole()` for **view-level** differentiation (navigation, labels, dashboard blocks).
- Use `hasRole()` for **permission guards** (can they create/delete?) — these should be independent of which role is active.

## Key Files

```
app/Models/User.php                               — activeRole(), hasActiveRole(), switchRole(), availableRoles(), currentBU()
app/Http/Middleware/SetActiveRole.php             — seeds session on first authenticated request
app/Livewire/RoleSwitcher.php                    — Livewire component handling the dropdown
resources/views/livewire/role-switcher.blade.php  — dropdown UI
app/Providers/Filament/AdminPanelProvider.php    — registers middleware + topbar render hook
resources/views/filament/pages/dashboard.blade.php — role-aware widget blocks
```

## Adding a New Role

1. Create the role: `php artisan shield:generate` or a seeder/migration.
2. Add it to the priority list in `User::activeRole()`.
3. Add a dashboard block in `dashboard.blade.php` gated by `$activeRole === 'YourRole'`.
4. Use `hasActiveRole('YourRole')` in resources/pages to customise the experience.
