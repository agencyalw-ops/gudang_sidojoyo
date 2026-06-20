# Role-Based Access Control (RBAC) Plan for Gudang Sidojoyo

## Current State
- **Owner**: Can view dashboard (transaction history). Has routes for user management (`/admin/users`), but the navigation links are missing in `app.blade.php`.
- **Admin**: Can view dashboard (transaction history), manage products (`/admin/products`), and delete transactions (`/admin/transactions/delete/{id}`). Admin currently has a navigation link to `/admin/users` but the route is protected by `role:owner`, so they get a 403 error if they try to access it.
- Products routes (`/admin/products`) are under `Route::middleware('role:owner,admin')`.
- Admin dashboard and delete transaction are under `Route::middleware('role:admin')`.
- The `admin/dashboard.blade.php` includes a delete button for transactions.
- The `owner/dashboard.blade.php` does NOT include a delete button for transactions.

## Requirements
- **Owner**:
  - Manage products (add, edit, delete)
  - View history (dashboard)
  - Manage transactions (delete transactions)
  - Manage users (add, edit, delete)
- **Admin**:
  - Manage products (add, edit, delete)
  - View history (dashboard)
  - Manage transactions (delete transactions)
  - CANNOT manage users

## Changes Needed

### 1. Routes (`routes/web.php`)
- **Owner Dashboard**: Keep `/owner` for dashboard.
- **Admin Dashboard**: Keep `/admin` for dashboard.
- **Transactions Management**: Move `/admin/transactions/delete/{id}` to a shared middleware `role:owner,admin`.
- **User Management**: Keep `/admin/users` under `role:owner`.
- **Products Management**: Keep `/admin/products` under `role:owner,admin`.

### 2. Controllers
- **AdminController**: The `deleteTransaction` method is currently in `AdminController`. We will keep it there. The redirect after deletion needs to be dynamic based on the user's role, or simply redirect back to the previous page.
- **OwnerController**: The `dashboard` method is fine.

### 3. Views (`resources/views/layouts/app.blade.php`)
- Update the navbar logic:
  - **Owner**: Show Dashboard (`/owner`), Products (`/admin/products`), Users (`/admin/users`).
  - **Admin**: Show Dashboard (`/admin`), Products (`/admin/products`). (Remove Users link for Admin).

### 4. Views (`resources/views/admin/dashboard.blade.php` and `resources/views/owner/dashboard.blade.php`)
- **admin/dashboard.blade.php**: The delete button is already present and functional.
- **owner/dashboard.blade.php**: Add the transaction delete functionality to this dashboard, similar to `admin/dashboard.blade.php`.
