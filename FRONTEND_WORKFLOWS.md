# Limosud Cars — Frontend Workflows

> **Purpose:** Page-by-page user journeys for frontend developers.
> **Not** an API reference (`FRONTEND_API_INTEGRATION_GUIDE.md`) and **not** a QA document (`POSTMAN_TEST_WORKFLOW.txt`).
> **Sources:** Laravel `routes/api.php`, controllers/resources, Newman captured responses.
> **Generated:** 2026-06-13 15:08:55

## How to read this document

Each workflow explains **what the user does**, **which endpoints to call in order**, and **how the UI should react**.

### Conventions

- **Base URL:** `http://127.0.0.1:8000/api` (use env variable in the app).
- **Admin auth:** Sanctum bearer token from `POST /admin/auth/login`.
- **Permissions:** Gate menus and buttons using `user.permissions[].slug` from `GET /admin/auth/me`.
- **Lookups:** Load once via `GET /admin/lookups` and cache in global state for form selects.
- **Pagination:** List endpoints support `?page=` only. There is **no server-side search/filter/sort** today — implement those client-side on the current page or fetch additional pages.
- **Money fields:** Display as currency; API returns decimal strings like `"375.00"`.
- **Soft deletes:** `DELETE` returns `204` and hides records from lists.

### Standard error handling (all workflows)

| Status | UI behavior |
|--------|-------------|
| `401` | Clear token, redirect to login |
| `403` | Toast + hide/disable action; optional â€œno permissionâ€ page |
| `404` | Not-found empty state or redirect back to list |
| `422` | Inline field errors from `errors` object |
| `500` | Generic error banner with retry |
| Network offline | Offline banner; retry when connection returns |

---

## Global frontend flows

### Pagination

| Item | Details |
|------|---------|
| **User goal** | Navigate large admin lists. |
| **Preconditions** | User is authenticated; list permission granted. |
| **Entry point** | Any paginated table (vehicles, customers, reservations, etc.). |
| **Permissions** | Resource-specific `*.view` permission |

**Flow**

```
Open list page
↓
GET /admin/{resource}?page=1
↓
Render rows + pagination controls from meta/links
↓
User clicks page 2
↓
GET /admin/{resource}?page=2
↓
Replace table rows
```

**Endpoints**

- `GET /admin/vehicles?page={n}` (and equivalent list routes)

**Captured response snippet**

```json
{
    "data": [
        {
            "id": 3,
            "name": "QA Lifecycle Vehicle",
            "slug": "qa-lifecycle-vehicle",
            "model": "Sandero",
            "year": 2024,
            "plate_number": "QA-LIFE-01",
            "mileage": 15000,
            "current_mileage_updated_at": "2026-06-10T23:54:32.000000Z",
            "seats": 5,
            "doors": 5,
            "daily_price": "375.00",
            "weekly_price": "2200.00",
            "monthly_price": "8500.00",
            "deposit_amount": "3000.00",
            "description": "QA support vehicle.",
            "is_featured": false,
            "is_active": true,
            "brand": {
                "id": 7,
                "name": "Postman Brand",
                "slug": "postman-brand"
            },
  // ... truncated
```

**Frontend state**

- **Local:** `currentPage`, `lastPage`, `items[]` for current page.
- **Global:** none.
- **Discard:** previous page rows when page changes.

**UI recommendations**

- Skeleton rows while loading.
- Disable pagination buttons during fetch.
- Show â€œPage X of Yâ€ from `meta`.

**Error handling**

- `401`/`403` as standard.
- Empty `data[]`: show empty state, not an error.

**Edge cases / business rules**

- `per_page` is 15 on captured list responses.
- Use `links.next` / `meta.current_page` for navigation.

---

### Search (client-side)

| Item | Details |
|------|---------|
| **User goal** | Find a record in the current dataset. |
| **Preconditions** | List data loaded (one or more pages). |
| **Entry point** | Search box on list pages. |
| **Permissions** | N/A |

**Flow**

```
User types query
↓
Filter rendered rows locally (name, plate, phone, reservation_number)
↓
Optional: fetch more pages if product requires full-database search
```

**Endpoints**

- Initial load: `GET /admin/{resource}?page=1`

**Frontend state**

- **Local:** `searchQuery`, `filteredItems`.
- **Global:** none.

**UI recommendations**

- Debounce input 300ms.
- Show â€œNo matchesâ€ when filter returns zero rows.
- Clear button in search field.

**Error handling**

- No dedicated search API exists today.

**Edge cases / business rules**

**Important:** Backend does not expose `?search=` query params. Full search requires either client-side filtering of fetched pages or a future API enhancement.

---

### Filtering (client-side)

| Item | Details |
|------|---------|
| **User goal** | Narrow a list by status, brand, date, etc. |
| **Preconditions** | List loaded; lookup labels available. |
| **Entry point** | Filter chips/dropdowns on list or calendar views. |
| **Permissions** | reservations.view |

**Flow**

```
User selects filter
↓
Apply filter to in-memory collection OR reload calendar with date query
↓
Re-render list
```

**Endpoints**

- Lists: `GET /admin/{resource}`
- Calendar exception: `GET /admin/reservations-calendar?start=YYYY-MM-DD&end=YYYY-MM-DD`

**Request bodies**

Calendar filters only:

**Captured response snippet**

```json
{
    "data": [
        {
            "id": 1,
            "reservation_number": "RSV-QA-ADMIN-001",
            "customer": {
                "id": 1,
                "full_name": "Postman Customer",
                "nationality": "Moroccan",
                "phone": "+212611111111",
                "email": "postman.customer@example.com",
                "passport_or_cin": "PC123456",
                "driving_license_number": "PC-DL-001",
                "created_at": "2026-06-11T23:05:51.000000Z",
                "updated_at": "2026-06-11T23:05:51.000000Z"
            },
            "vehicle": {
                "id": 2,
                "name": "Postman Test Vehicle Updated",
                "slug": "postman-test-vehicle",
  // ... truncated
```

**Frontend state**

- **Local:** `activeFilters`, filtered collection.
- **Calendar:** `start`, `end` query state.

**UI recommendations**

- Show active filter chips.
- â€œClear filtersâ€ resets UI.

**Error handling**

- Invalid date range → validate before calling calendar endpoint.

**Edge cases / business rules**

Reservation calendar is the only list-like endpoint with meaningful server date filtering today.

---

### Sorting (client-side)

| Item | Details |
|------|---------|
| **User goal** | Reorder rows for display. |
| **Preconditions** | List data in memory. |
| **Entry point** | Column header click. |
| **Permissions** | N/A |

**Flow**

```
User clicks sortable column
↓
Sort local array by field
↓
Re-render table
```

**Endpoints**

- Data from paginated GET list endpoints

**Frontend state**

- **Local:** `sortField`, `sortDirection`.
- **Global:** none.

**UI recommendations**

- Show sort indicator on column.
- Default sort matches API (`latest()` — newest first).

**Error handling**

- Sorting only affects currently loaded page unless you fetch all pages.

**Edge cases / business rules**

Server does not accept `?sort=` parameters.

---

### Refresh

| Item | Details |
|------|---------|
| **User goal** | Reload data after an action or manual refresh. |
| **Preconditions** | User on any data screen. |
| **Entry point** | Refresh button or pull-to-refresh. |
| **Permissions** | N/A |

**Flow**

```
User triggers refresh
↓
Re-call current page endpoint(s)
↓
Replace stale state
↓
Toast optional: 'Data updated'
```

**Endpoints**

- Same endpoints as the screenâ€™s initial load

**Frontend state**

- **Local:** replace list/detail slice.
- **Global:** refresh `auth/me` only on app focus if needed.

**UI recommendations**

- Inline loading indicator; keep old data visible until success (stale-while-revalidate).

**Error handling**

- On failure, keep stale data and show retry toast.

---

### Optimistic updates

| Item | Details |
|------|---------|
| **User goal** | Make UI feel instant for low-risk toggles. |
| **Preconditions** | PATCH/POST action with predictable outcome. |
| **Entry point** | Toggle switches, status chips. |
| **Permissions** | Resource `*.update` permission |

**Flow**

```
User toggles valueb
↓
Update UI immediately
↓
PATCH /admin/{resource}/{id}
↓
On success: keep UI
↓
On failure: rollback UI + show error
```

**Endpoints**

- Example: `PATCH /admin/vehicles/{id}` with `{ is_featured: true }`

**Request bodies**

`{ "is_featured": false }` (example partial body)

**Captured response snippet**

```json
{
    "data": {
        "id": 2,
        "name": "Postman Test Vehicle Updated",
        "slug": "postman-test-vehicle",
        "model": "Sandero",
        "year": 2024,
        "plate_number": "PM-100-TEST",
        "mileage": 13500,
        "current_mileage_updated_at": "2026-06-10T23:54:32.000000Z",
        "seats": 5,
        "doors": 5,
        "daily_price": "390.00",
        "weekly_price": "2300.00",
        "monthly_price": "8800.00",
        "deposit_amount": "3000.00",
        "description": "Postman test vehicle.",
        "is_featured": false,
        "is_active": true,
        "brand": {
            "id": 7,
            "name": "Postman Brand",
            "slug": "postman-brand"
        },
        "category": {
  // ... truncated
```

**Frontend state**

- **Local:** optimistic snapshot for rollback.
- **Global:** none.

**UI recommendations**

- Subtle saving indicator on row.
- Rollback animation on error.

**Error handling**

- `422`: rollback and show field errors.
- `403`: rollback and permission toast.

**Edge cases / business rules**

Use only for reversible fields. Do **not** optimistically confirm reservations or register payments.

---

### Validation error (422)

| Item | Details |
|------|---------|
| **User goal** | Guide user to fix form input. |
| **Preconditions** | Form submit attempted. |
| **Entry point** | Any create/update form. |
| **Permissions** | Matching create/update permission |

**Flow**

```
Submit form
↓
POST/PUT/PATCH
↓
422 response
↓
Map errors[field] to inputs
↓
Focus first invalid field
```

**Endpoints**

- Any write endpoint

**Captured response snippet**

```json
{"message":"The given data was invalid.","errors":{"plate_number":["The plate number has already been taken."]}}
```

**Frontend state**

- **Local:** `fieldErrors` map.
- **Global:** none.

**UI recommendations**

- Red borders + helper text per field.
- Summary alert at top for long forms.

**Error handling**

- Do not clear unrelated valid fields.

**Edge cases / business rules**

Duplicate slug/plate_number is common on vehicles and locations.

---

### Generic server error (500)

| Item | Details |
|------|---------|
| **User goal** | Recover from unexpected backend failure. |
| **Preconditions** | Any API call. |
| **Entry point** | Global error boundary. |
| **Permissions** | N/A |

**Flow**

```
API returns 500
↓
Show non-technical message
↓
Offer Retry button
↓
Retry repeats last request
```

**Endpoints**

- Any

**Captured response snippet**

```json
{"message":"Server Error"}
```

**Frontend state**

- **Local:** `lastFailedRequest` for retry.
- **Global:** optional error telemetry.

**UI recommendations**

- Full-page or section error state.
- Avoid infinite retry loops.

**Error handling**

- Log correlation id if backend adds one later.

---

### Network error

| Item | Details |
|------|---------|
| **User goal** | Handle offline or timeout. |
| **Preconditions** | Fetch in flight. |
| **Entry point** | Any screen. |
| **Permissions** | N/A |

**Flow**

```
fetch throws / timeout
↓
Detect navigator.onLine
↓
Show offline banner
↓
Queue retry when online
```

**Endpoints**

- Any

**Frontend state**

- **Local:** `isOffline`, pending retries.
- **Global:** connection status.

**UI recommendations**

- Banner: â€œNo internet connectionâ€.
- Disable submit buttons offline.

**Error handling**

- Distinguish timeout vs DNS failure in logs only; same UI for user.

---

### Token expiration (401)

| Item | Details |
|------|---------|
| **User goal** | Return user to login safely. |
| **Preconditions** | Stored token invalid/expired/revoked. |
| **Entry point** | Any protected call. |
| **Permissions** | N/A |

**Flow**

```
API returns 401
↓
Clear admin_token + user cache
↓
Redirect to /login
↓
Preserve intended route in query ?next=
```

**Endpoints**

- Typically discovered on `GET /admin/auth/me` or any admin route

**Captured response snippet**

```json
{"message":"Unauthenticated."}
```

**Frontend state**

- **Global:** clear `auth` store.
- **Local:** discard protected screen state.

**UI recommendations**

- Toast: â€œSession expired, please sign in again.â€

**Error handling**

- Do not loop login → me → 401.

**Edge cases / business rules**

Logout (`POST /admin/auth/logout`) invalidates the current token.

---

## Authentication

### Login

| Item | Details |
|------|---------|
| **User goal** | Sign in to the admin dashboard. |
| **Preconditions** | User is logged out. |
| **Entry point** | /login |
| **Permissions** | None (public login route) |

**Flow**

```
User submits email/password
↓
POST /admin/auth/login
↓
Store access_token
↓
GET /admin/auth/me
↓
Store user + permissions
↓
Redirect to dashboard
```

**Endpoints**

- `POST /admin/auth/login`
- `GET /admin/auth/me`

**Request bodies**

Login body:
```json
{"email":"admin@limosudcars.local","password":"***"}
```

**Captured response snippet**

```json
{
    "token_type": "Bearer",
    "access_token": "<REDACTED_QA_TOKEN>",
    "user": {
        "id": 1,
        "name": "Limosud Cars Admin",
        "email": "admin@limosudcars.local",
        "phone": "06000000000",
        "is_active": true,
        "roles": [
            {
                "id": 1,
                "name": "Super Admin",
                "slug": "super_admin"
            }
        ],
        "permissions": [
            {
                "id": 49,
                "module": "alerts",
                "name": "Close alerts",
                "slug": "alerts.close"
            },
            {
                "id": 47,
                "module": "alerts",
                "name": "Create alerts",
                "slug": "alerts.create"
            },
            {
  // ... truncated
```

**Frontend state**

- **Global:** `token`, `user`, `permissions[]`.
- **Local:** clear login form.
- **Discard:** password from memory after submit.

**UI recommendations**

- Full-screen loader on submit.
- Success: redirect without toast (optional).
- Invalid credentials: inline form error.

**Error handling**

- `422`: invalid email/password format.
- Inactive user: validation/unauthorized per backend.

**Edge cases / business rules**

Token is Sanctum personal access token (`token_type: Bearer`).

---

### Restore session

| Item | Details |
|------|---------|
| **User goal** | Stay signed in after page reload. |
| **Preconditions** | Token may exist in storage. |
| **Entry point** | App bootstrap / protected route guard. |
| **Permissions** | Authenticated admin |

**Flow**

```
App starts
↓
Read admin_token
↓
If missing → /login
↓
GET /admin/auth/me
↓
If 200 → enter app
↓
If 401 → clear token → /login
```

**Endpoints**

- `GET /admin/auth/me`

**Captured response snippet**

```json
{
    "data": {
        "id": 1,
        "name": "Limosud Cars Admin",
        "email": "admin@limosudcars.local",
        "phone": "06000000000",
        "is_active": true,
        "roles": [
            {
                "id": 1,
                "name": "Super Admin",
                "slug": "super_admin"
            }
        ],
        "permissions": [
            {
                "id": 49,
                "module": "alerts",
                "name": "Close alerts",
                "slug": "alerts.close"
            },
            {
                "id": 47,
                "module": "alerts",
                "name": "Create alerts",
  // ... truncated
```

**Frontend state**

- **Global:** hydrate auth store from `/me`.
- **Local:** route loading flags.

**UI recommendations**

- App shell skeleton until `/me` completes.
- Avoid flashing login page when token is valid.

**Error handling**

- `401`: run token expiration workflow.

**Edge cases / business rules**

Call `/me` once per session boot, not on every child route.

---

### Logout

| Item | Details |
|------|---------|
| **User goal** | End the admin session. |
| **Preconditions** | User is signed in. |
| **Entry point** | Header user menu → Logout. |
| **Permissions** | Authenticated admin |

**Flow**

```
User confirms logout
↓
POST /admin/auth/logout
↓
Clear token + user state
↓
Redirect to /login
```

**Endpoints**

- `POST /admin/auth/logout`

**Captured response snippet**

```json
{
    "message": "Logged out successfully."
}
```

**Frontend state**

- **Global:** wipe auth + cached lookups optional.
- **Local:** reset all protected routes.

**UI recommendations**

- Confirm dialog optional for shared terminals.
- Success toast: â€œSigned outâ€.

**Error handling**

- If logout fails (network), still clear local token to avoid stuck state.

**Edge cases / business rules**

Token is revoked server-side; old token must not be reused.

---

### Unauthorized (401) during navigation

| Item | Details |
|------|---------|
| **User goal** | Handle expired session mid-use. |
| **Preconditions** | Token became invalid. |
| **Entry point** | Any protected API call. |
| **Permissions** | N/A |

**Flow**

```
Protected fetch returns 401
↓
Clear auth store
↓
Redirect /login?next={currentPath}
```

**Endpoints**

- Any admin endpoint

**Captured response snippet**

```json
{"message":"Unauthenticated."}
```

**Frontend state**

- **Global:** clear auth.
- **Local:** drop in-flight form state or warn user.

**UI recommendations**

- Modal: â€œSession expiredâ€.

**Error handling**

- Same as global 401 workflow.

---

### Forbidden (403)

| Item | Details |
|------|---------|
| **User goal** | User lacks permission for an action. |
| **Preconditions** | Authenticated but missing slug. |
| **Entry point** | Clicking restricted menu/button. |
| **Permissions** | Varies per module |

**Flow**

```
Action call returns 403
↓
Show permission denied message
↓
Keep user on safe page
```

**Endpoints**

- Any permission-protected route

**Captured response snippet**

```json
{"message":"Forbidden."}
```

**Frontend state**

- **Global:** use permissions to prevent call when possible.
- **Local:** `can(permission)` guards.

**UI recommendations**

- Hide buttons when `permissions` lacks slug.
- If still triggered: toast â€œYou do not have permissionâ€.

**Error handling**

- Do not logout on 403 (user is authenticated).

**Edge cases / business rules**

Build navigation from `/me` permissions, not role names alone.

---

## Dashboard

### Dashboard initial load

| Item | Details |
|------|---------|
| **User goal** | Show KPIs and charts after login. |
| **Preconditions** | User authenticated; `dashboard.view` permission. |
| **Entry point** | /dashboard |
| **Permissions** | dashboard.view |

**Flow**

```
Enter dashboard
↓
Parallel fetch:
  GET /admin/dashboard/statistics?year=&month=
  GET /admin/dashboard/revenue?start_date=&end_date=&group_by=day
  GET /admin/dashboard/expenses?start_date=&end_date=&group_by=month
↓
Render KPI cards + charts
↓
Optional: GET /admin/alerts/pending for badge
```

**Endpoints**

- `GET /admin/dashboard/statistics`
- `GET /admin/dashboard/revenue`
- `GET /admin/dashboard/expenses`
- Optional: `GET /admin/alerts/pending`

**Request bodies**

Query examples: `year=2026&month=6`, `start_date=2026-06-01&end_date=2026-06-30`

**Captured response snippet**

```json
{
    "global_kpis": {
        "total_vehicles": 7,
        "available_vehicles": 6,
        "reserved_vehicles": 1,
        "rented_vehicles": 0,
        "vehicles_in_maintenance": 0,
        "vehicles_in_repair": 0,
        "out_of_service_vehicles": 0,
        "total_customers": 5,
        "total_reservations": 10,
        "pending_reservations": 7,
        "confirmed_reservations": 3,
        "in_progress_reservations": 0,
        "completed_reservations": 0,
        "cancelled_reservations": 0,
        "unpaid_reservations": 9,
        "partial_paid_reservations": 1,
        "paid_reservations": 0,
        "pending_alerts": 4,
        "monthly_revenue": 1000,
        "monthly_expenses": 1150,
        "monthly_net_profit": -150
    },
    "month": {
        "year": 2026,
        "month": 6
    }
}
```

**Frontend state**

- **Global:** optional dashboard date range.
- **Local:** KPI + chart datasets.

**UI recommendations**

- Skeleton cards.
- Chart empty states when totals are zero.

**Error handling**

- `422` on invalid dates: reset to current month.

**Edge cases / business rules**

Revenue counts only `paid` payments. Expenses include vehicle and general expenses.

---

### Dashboard refresh

| Item | Details |
|------|---------|
| **User goal** | Update KPIs after operational changes. |
| **Preconditions** | On dashboard page. |
| **Entry point** | Refresh control or return from another module. |
| **Permissions** | dashboard.view |

**Flow**

```
User clicks Refresh
↓
Re-fetch statistics + revenue + expenses
↓
Update widgets
```

**Endpoints**

- Same as dashboard initial load

**Frontend state**

- **Local:** replace dashboard datasets.

**UI recommendations**

- Small spinner on refresh icon.
- Toast optional.

**Error handling**

- Partial failure: show per-widget error.

---

## Vehicles

### List vehicles

| Item | Details |
|------|---------|
| **User goal** | Browse fleet inventory. |
| **Preconditions** | `vehicles.view` permission. |
| **Entry point** | /admin/vehicles |
| **Permissions** | vehicles.view |

**Flow**

```
Open Vehicles page
↓
GET /admin/vehicles?page=1
↓
Render table (name, plate, status, prices)
↓
User changes page → GET ?page=n
```

**Endpoints**

- `GET /admin/vehicles`

**Captured response snippet**

```json
{
    "data": [
        {
            "id": 3,
            "name": "QA Lifecycle Vehicle",
            "slug": "qa-lifecycle-vehicle",
            "model": "Sandero",
            "year": 2024,
            "plate_number": "QA-LIFE-01",
            "mileage": 15000,
            "current_mileage_updated_at": "2026-06-10T23:54:32.000000Z",
            "seats": 5,
            "doors": 5,
            "daily_price": "375.00",
            "weekly_price": "2200.00",
            "monthly_price": "8500.00",
            "deposit_amount": "3000.00",
            "description": "QA support vehicle.",
            "is_featured": false,
            "is_active": true,
            "brand": {
                "id": 7,
                "name": "Postman Brand",
                "slug": "postman-brand"
            },
            "category": {
                "id": 6,
                "name": "Postman Category",
                "slug": "postman-category"
            },
  // ... truncated
```

**Frontend state**

- **Local:** `vehicles[]`, pagination meta.
- **Global:** cache brands/categories from lookups for labels.

**UI recommendations**

- Row actions: View, Edit, Delete (permission-gated).
- Empty: â€œNo vehicles yetâ€ + Create CTA.

**Error handling**

- `403`: hide module from nav.

**Edge cases / business rules**

List does not include photos/documents; fetch detail for media.

---

### Search and filter vehicles

| Item | Details |
|------|---------|
| **User goal** | Find vehicles by plate, name, brand, or status without server-side query params. |
| **Preconditions** | `vehicles.view`; at least one page of vehicles loaded. |
| **Entry point** | /admin/vehicles |
| **Permissions** | vehicles.view |

**Flow**

```
GET /admin/vehicles?page=1
↓
Render table
↓
User types search text OR selects status/brand filters
↓
Filter `vehicles[]` in memory (client-side)
↓
Re-render filtered rows
↓
(Optional) fetch additional pages if matches may exist elsewhere
```

**Endpoints**

- `GET /admin/vehicles?page={n}` — **no** `?search=` or `?filter=` on API

**Captured response snippet**

```json
{
    "data": [
        {
            "id": 3,
            "name": "QA Lifecycle Vehicle",
            "slug": "qa-lifecycle-vehicle",
            "model": "Sandero",
            "year": 2024,
            "plate_number": "QA-LIFE-01",
            "mileage": 15000,
            "current_mileage_updated_at": "2026-06-10T23:54:32.000000Z",
            "seats": 5,
            "doors": 5,
            "daily_price": "375.00",
            "weekly_price": "2200.00",
            "monthly_price": "8500.00",
            "deposit_amount": "3000.00",
            "description": "QA support vehicle.",
            "is_featured": false,
            "is_active": true,
  // ... truncated
```

**Frontend state**

- **Local:** `searchQuery`, `activeFilters`, `allLoadedVehicles[]` (accumulated pages optional).
- **Global:** brand/status labels from lookups.
- **Discard:** filtered view when clearing search.

**UI recommendations**

- Search input debounced ~300ms.
- Filter chips with clear-all.
- Show â€œNo matches on this pageâ€ vs true empty fleet.

**Error handling**

- `401`/`403` as standard.
- Empty after filter: offer clear filters CTA.

**Edge cases / business rules**

- API only supports `?page=` pagination — search/filter is **client-side** on loaded data.
- For exhaustive search across all pages, loop pages until `meta.last_page` (use sparingly).

---

### View vehicle details

| Item | Details |
|------|---------|
| **User goal** | Inspect one vehicle including photos and documents. |
| **Preconditions** | Vehicle id from list or deep link. |
| **Entry point** | /admin/vehicles/{id} |
| **Permissions** | vehicles.view |

**Flow**

```
Open detail
↓
GET /admin/vehicles/{id}
↓
Render specs, photos[], documents[]
↓
Tabs: Maintenance history, Expenses (optional)
```

**Endpoints**

- `GET /admin/vehicles/{id}`
- Optional: `GET /admin/vehicles/{id}/maintenances`
- Optional: `GET /admin/vehicles/{id}/expenses`

**Captured response snippet**

```json
{
    "data": {
        "id": 2,
        "name": "Postman Test Vehicle",
        "slug": "postman-test-vehicle",
        "model": "Sandero",
        "year": 2024,
        "plate_number": "PM-100-TEST",
        "mileage": 13000,
        "current_mileage_updated_at": "2026-06-10T23:54:32.000000Z",
        "seats": 5,
        "doors": 5,
        "daily_price": "375.00",
        "weekly_price": "2200.00",
        "monthly_price": "8500.00",
        "deposit_amount": "3000.00",
        "description": "Postman test vehicle.",
        "is_featured": false,
        "is_active": true,
        "brand": {
            "id": 7,
            "name": "Postman Brand",
            "slug": "postman-brand"
        },
        "category": {
            "id": 6,
            "name": "Postman Category",
            "slug": "postman-category"
        },
        "status": {
            "id": 1,
            "name": "Available",
            "slug": "available"
        },
        "transmission_type": {
  // ... truncated
```

**Frontend state**

- **Local:** `vehicle` record.
- **Store id** in route param.

**UI recommendations**

- Gallery for `photos`.
- Documents list with expiry badges.

**Error handling**

- `404`: vehicle deleted → back to list.

**Edge cases / business rules**

`file_path` on documents is storage path — do not treat as public CDN URL unless storage strategy is added.

---

### Create vehicle

| Item | Details |
|------|---------|
| **User goal** | Add a new fleet vehicle. |
| **Preconditions** | `vehicles.create`; lookups loaded. |
| **Entry point** | /admin/vehicles/new |
| **Permissions** | vehicles.create |

**Flow**

```
Open create form
↓
Parallel load:
  GET /admin/lookups (statuses, transmission, fuel)
  GET /admin/brands?page=1
  GET /admin/categories?page=1
↓
User fills form
↓
POST /admin/vehicles
↓
201 → navigate to detail or list
↓
Success toast
```

**Endpoints**

- `GET /admin/lookups`
- `GET /admin/brands?page=1`
- `GET /admin/categories?page=1`
- `POST /admin/vehicles`

**Request bodies**

Example JSON body:
```json
{
  "brand_id": 7,
  "category_id": 6,
  "name": "QA Disposable Vehicle",
  "slug": "qa-disposable-vehicle",
  "model": "Sandero",
  "year": 2024,
  "plate_number": "QA-001",
  "mileage": 10000,
  "seats": 5,
  "doors": 5,
  "daily_price": "375.00",
  "weekly_price": "2200.00",
  "monthly_price": "8500.00",
  "deposit_amount": "3000.00",
  "status_slug": "available",
  "transmission_type_slug": "manual",
  "fuel_type_slug": "gasoline",
  "description": "Optional notes",
  "is_featured": false,
  "is_active": true
}
```

**Captured response snippet**

```json
{
    "data": {
        "id": 10,
        "name": "QA Disposable Vehicle 1781222073",
        "slug": "qa-disposable-vehicle-1781222073",
        "model": "Sandero",
        "year": 2024,
        "plate_number": "QA-1781222073",
        "mileage": 10000,
        "current_mileage_updated_at": "2026-06-10T10:00:00.000000Z",
        "seats": 5,
        "doors": 5,
        "daily_price": "350.00",
        "weekly_price": "2200.00",
        "monthly_price": "8500.00",
        "deposit_amount": "3000.00",
        "description": "Disposable QA vehicle.",
        "is_featured": false,
        "is_active": true,
        "brand": {
            "id": 7,
            "name": "Postman Brand",
            "slug": "postman-brand"
        },
        "category": {
            "id": 6,
            "name": "Postman Category",
            "slug": "postman-category"
        },
        "status": {
            "id": 1,
            "name": "Available",
            "slug": "available"
        },
        "transmission_type": {
  // ... truncated
```

**Frontend state**

- **Local:** form state.
- **After success:** store `data.id` for redirect.

**UI recommendations**

- Disable submit while saving.
- Slug auto-generate from name optional.

**Error handling**

- `422` duplicate slug/plate.

**Edge cases / business rules**

Use lookup slugs (`status_slug`, `transmission_type_slug`, `fuel_type_slug`).

---

### Full update vehicle (PUT)

| Item | Details |
|------|---------|
| **User goal** | Replace editable vehicle fields. |
| **Preconditions** | `vehicles.update`; vehicle loaded. |
| **Entry point** | /admin/vehicles/{id}/edit |
| **Permissions** | vehicles.update |

**Flow**

```
Load GET /admin/vehicles/{id}
↓
Populate form
↓
User saves
↓
PUT /admin/vehicles/{id}
↓
Refresh detail
↓
Toast success
```

**Endpoints**

- `GET /admin/vehicles/{id}`
- `PUT /admin/vehicles/{id}`

**Request bodies**

Send full validated payload (same fields as create).

**Captured response snippet**

```json
{
    "data": {
        "id": 2,
        "name": "Postman Test Vehicle Updated",
        "slug": "postman-test-vehicle",
        "model": "Sandero",
        "year": 2024,
        "plate_number": "PM-100-TEST",
        "mileage": 13000,
        "current_mileage_updated_at": "2026-06-10T23:54:32.000000Z",
        "seats": 5,
        "doors": 5,
        "daily_price": "375.00",
        "weekly_price": "2300.00",
        "monthly_price": "8800.00",
        "deposit_amount": "3000.00",
        "description": "Postman test vehicle.",
        "is_featured": false,
        "is_active": true,
        "brand": {
            "id": 7,
            "name": "Postman Brand",
            "slug": "postman-brand"
        },
        "category": {
            "id": 6,
            "name": "Postman Category",
            "slug": "postman-category"
        },
        "status": {
  // ... truncated
```

**Frontend state**

- **Local:** form ↔ API mapping.
- **Discard** dirty state after save.

**UI recommendations**

- Warn on navigate away with unsaved changes.

**Error handling**

- `422` on invalid slugs.

**Edge cases / business rules**

PUT uses same update handler as PATCH in Laravel.

---

### Partial update vehicle (PATCH)

| Item | Details |
|------|---------|
| **User goal** | Quick field updates (status, mileage, price). |
| **Preconditions** | `vehicles.update`. |
| **Entry point** | Inline editors on list/detail. |
| **Permissions** | vehicles.update |

**Flow**

```
User edits field
↓
PATCH /admin/vehicles/{id} with changed fields only
↓
Merge response into UI
```

**Endpoints**

- `PATCH /admin/vehicles/{id}`

**Request bodies**

Example: `{ "status_slug": "maintenance", "mileage": 13500 }`

**Captured response snippet**

```json
{
    "data": {
        "id": 2,
        "name": "Postman Test Vehicle Updated",
        "slug": "postman-test-vehicle",
        "model": "Sandero",
        "year": 2024,
        "plate_number": "PM-100-TEST",
        "mileage": 13500,
        "current_mileage_updated_at": "2026-06-10T23:54:32.000000Z",
        "seats": 5,
        "doors": 5,
        "daily_price": "390.00",
        "weekly_price": "2300.00",
        "monthly_price": "8800.00",
        "deposit_amount": "3000.00",
        "description": "Postman test vehicle.",
        "is_featured": false,
        "is_active": true,
        "brand": {
            "id": 7,
            "name": "Postman Brand",
            "slug": "postman-brand"
        },
        "category": {
            "id": 6,
            "name": "Postman Category",
            "slug": "postman-category"
        },
        "status": {
  // ... truncated
```

**Frontend state**

- **Local:** patch single vehicle in list cache.

**UI recommendations**

- Inline save indicator.

**Error handling**

- `422` if status transition invalid.

**Edge cases / business rules**

Changing `status_slug` affects fleet availability displays.

---

### Delete vehicle

| Item | Details |
|------|---------|
| **User goal** | Remove vehicle from active fleet (soft delete). |
| **Preconditions** | `vehicles.delete`. |
| **Entry point** | Detail or row action. |
| **Permissions** | vehicles.delete |

**Flow**

```
User confirms delete
↓
DELETE /admin/vehicles/{id}
↓
204 → remove row / go to list
↓
Toast success
```

**Endpoints**

- `DELETE /admin/vehicles/{id}`

**Captured response snippet**

```json
HTTP 204 No Content
```

**Frontend state**

- **Local:** remove from list.
- **Route:** redirect to list.

**UI recommendations**

- Strong confirmation dialog.
- Explain soft delete.

**Error handling**

- `500` if related records block deletion.

**Edge cases / business rules**

Do not delete vehicles referenced by active reservations without business review.

---

### Vehicle images / files (read-only today)

| Item | Details |
|------|---------|
| **User goal** | Display existing photos and documents. |
| **Preconditions** | Vehicle detail loaded. |
| **Entry point** | Vehicle detail gallery. |
| **Permissions** | vehicles.view |

**Flow**

```
GET /admin/vehicles/{id}
↓
Render photos[] and documents[] from response
↓
(No upload step — not implemented in API)
```

**Endpoints**

- `GET /admin/vehicles/{id}` only

**Captured response snippet**

```json
{
    "data": {
        "id": 2,
        "name": "Postman Test Vehicle",
        "slug": "postman-test-vehicle",
        "model": "Sandero",
        "year": 2024,
        "plate_number": "PM-100-TEST",
        "mileage": 13000,
        "current_mileage_updated_at": "2026-06-10T23:54:32.000000Z",
        "seats": 5,
        "doors": 5,
        "daily_price": "375.00",
        "weekly_price": "2200.00",
        "monthly_price": "8500.00",
        "deposit_amount": "3000.00",
        "description": "Postman test vehicle.",
        "is_featured": false,
        "is_active": true,
        "brand": {
            "id": 7,
            "name": "Postman Brand",
            "slug": "postman-brand"
        },
        "category": {
  // ... truncated
```

**Frontend state**

- **Local:** display arrays from detail response.

**UI recommendations**

- Placeholder when `photos` empty.
- Hide upload UI unless backend adds endpoints.

**Error handling**

N/A

**Edge cases / business rules**

**There is no `POST` vehicle photo/document upload route in `routes/api.php`.** Do not build upload UI until backend exposes it. Customer documents and expense/contract uploads use different modules.

---

## Customers

### List customers

| Item | Details |
|------|---------|
| **User goal** | Browse and open customer records. |
| **Preconditions** | `customers.view` permission. |
| **Entry point** | /admin/customers |
| **Permissions** | customers.view |

**Flow**

```
Open Customers page
↓
GET /admin/customers?page=1
↓
Render table (full_name, phone, email)
↓
User changes page → GET ?page=n
```

**Endpoints**

- `GET /admin/customers`

**Captured response snippet**

```json
{
    "data": [
        {
            "id": 7,
            "full_name": "Postman Public Customer",
            "nationality": "Moroccan",
            "phone": "+212600000000",
            "email": "postman.public@example.com",
            "passport_or_cin": "PM123456",
            "driving_license_number": "PM-DL-001",
            "created_at": "2026-06-11T23:54:43.000000Z",
            "updated_at": "2026-06-11T23:54:43.000000Z"
        },
        {
            "id": 5,
            "full_name": "Postman Public Customer",
            "nationality": "Moroccan",
            "phone": "+212600000000",
            "email": "postman.public@example.com",
            "passport_or_cin": "PM123456",
            "driving_license_number": "PM-DL-001",
            "created_at": "2026-06-11T23:18:53.000000Z",
            "updated_at": "2026-06-11T23:18:53.000000Z"
        },
        {
  // ... truncated
```

**Frontend state**

- **Local:** `customers[]`, pagination meta.
- **Global:** none.
- **Discard:** rows when page changes.

**UI recommendations**

- Client-side search by name/phone on current page.
- Empty: â€œNo customers yetâ€ + Create CTA.

**Error handling**

- `403`: hide module from nav.
- Empty `data[]`: empty state, not error.

**Edge cases / business rules**

List excludes `documents[]`; load detail for files.

---

### View customer details

| Item | Details |
|------|---------|
| **User goal** | Inspect profile and uploaded documents. |
| **Preconditions** | Customer id from list or deep link. |
| **Entry point** | /admin/customers/{id} |
| **Permissions** | customers.view |

**Flow**

```
Click row or open deep link
↓
GET /admin/customers/{id}
↓
Render profile + documents[]
```

**Endpoints**

- `GET /admin/customers/{id}`

**Captured response snippet**

```json
{
    "data": {
        "id": 8,
        "full_name": "QA Disposable Customer 1781222073",
        "nationality": "Moroccan",
        "phone": "+212622222222",
        "email": "qa.disposable.1781222073@example.com",
        "passport_or_cin": "QA1781222073",
        "driving_license_number": "QA-DL-1781222073",
        "documents": [],
        "created_at": "2026-06-11T23:55:04.000000Z",
        "updated_at": "2026-06-11T23:55:04.000000Z"
    }
}
```

**Frontend state**

- **Local:** `customer` record with nested `documents`.
- **Route:** store `{id}` param.

**UI recommendations**

- Document list with type labels and expiry.
- Upload button if `customers.update`.

**Error handling**

- `404`: redirect to list with toast.

**Edge cases / business rules**

Documents include `file_path` (storage path) — use download strategy consistent with other modules.

---

### Create customer

| Item | Details |
|------|---------|
| **User goal** | Register a new customer. |
| **Preconditions** | `customers.create`. |
| **Entry point** | /admin/customers/new |
| **Permissions** | customers.create |

**Flow**

```
Open create form
↓
User fills fields
↓
POST /admin/customers
↓
201 → redirect to detail
↓
Success toast
```

**Endpoints**

- `POST /admin/customers`

**Request bodies**

```json
{
  "full_name": "Postman Customer",
  "nationality": "Moroccan",
  "phone": "+212611111111",
  "email": "postman.customer@example.com",
  "passport_or_cin": "PC123456",
  "driving_license_number": "PC-DL-001"
}
```

**Captured response snippet**

```json
{
    "data": {
        "id": 8,
        "full_name": "QA Disposable Customer 1781222073",
        "nationality": "Moroccan",
        "phone": "+212622222222",
        "email": "qa.disposable.1781222073@example.com",
        "passport_or_cin": "QA1781222073",
        "driving_license_number": "QA-DL-1781222073",
        "created_at": "2026-06-11T23:55:04.000000Z",
        "updated_at": "2026-06-11T23:55:04.000000Z"
    }
}
```

**Frontend state**

- **Local:** form state.
- **After success:** redirect using `data.id`.

**UI recommendations**

- Disable submit while saving.
- Phone format hint (+212â€¦).

**Error handling**

- `422` on duplicate phone/email if enforced.

**Edge cases / business rules**

Email is optional in captured create flow.

---

### Update customer

| Item | Details |
|------|---------|
| **User goal** | Edit customer profile fields. |
| **Preconditions** | `customers.update`; customer loaded. |
| **Entry point** | /admin/customers/{id}/edit |
| **Permissions** | customers.update |

**Flow**

```
Load GET /admin/customers/{id}
↓
Populate form
↓
User saves
↓
PUT or PATCH /admin/customers/{id}
↓
Refresh detail
↓
Toast success
```

**Endpoints**

- `GET /admin/customers/{id}`
- `PUT /admin/customers/{id}` or `PATCH /admin/customers/{id}`

**Request bodies**

Send changed fields (PATCH) or full record (PUT). Same fields as create.

**Captured response snippet**

```json
{
    "data": {
        "id": 8,
        "full_name": "QA Disposable Customer Updated",
        "nationality": "Moroccan",
        "phone": "+212622222222",
        "email": "qa.disposable.1781222073@example.com",
        "passport_or_cin": "QA1781222073",
        "driving_license_number": "QA-DL-1781222073",
        "documents": [],
        "created_at": "2026-06-11T23:55:04.000000Z",
        "updated_at": "2026-06-11T23:55:05.000000Z"
    }
}
```

**Frontend state**

- **Local:** form ↔ API mapping.
- **Discard** dirty state after save.

**UI recommendations**

- Warn on navigate away with unsaved changes.

**Error handling**

- `422` validation errors inline.

**Edge cases / business rules**

PUT and PATCH share the same Laravel update handler.

---

### Delete customer

| Item | Details |
|------|---------|
| **User goal** | Remove customer (soft delete). |
| **Preconditions** | `customers.delete`. |
| **Entry point** | Row action or detail page. |
| **Permissions** | customers.delete |

**Flow**

```
User confirms delete
↓
DELETE /admin/customers/{id}
↓
204 → remove row / navigate to list
↓
Toast success
```

**Endpoints**

- `DELETE /admin/customers/{id}`

**Captured response snippet**

```json
HTTP 204 No Content
```

**Frontend state**

- **Local:** remove from list cache.
- **Route:** redirect to list.

**UI recommendations**

- Strong confirmation dialog.

**Error handling**

- May fail if active reservations reference customer.

**Edge cases / business rules**

Soft delete — record hidden from lists.

---

### Upload customer document

| Item | Details |
|------|---------|
| **User goal** | Attach ID, license, or other document via multipart. |
| **Preconditions** | `customers.update`; customer detail open. |
| **Entry point** | /admin/customers/{id} |
| **Permissions** | customers.update |

**Flow**

```
User clicks Upload
↓
Pick file + document_type_slug + title
↓
Build FormData
↓
POST /admin/customers/{id}/documents
↓
Append returned document to list
↓
Toast success
```

**Endpoints**

- `POST /admin/customers/{id}/documents`

**Request bodies**

FormData fields:
- `document_type_slug` (from lookups)
- `title`
- `file` (binary)
- `expires_at` (optional, ISO date)

**Captured response snippet**

```json
{
    "data": {
        "id": 3,
        "document_type": {
            "id": 1,
            "name": "Passport",
            "slug": "passport"
        },
        "title": "Passport Scan",
        "file_path": "customer-documents/1/btJrNqe1gDR29NzileXqTF4lQtmRPU8soKcJk658.pdf",
        "expires_at": "2028-12-31T00:00:00.000000Z",
        "created_at": "2026-06-11T23:55:07.000000Z"
    }
}
```

**Frontend state**

- **Local:** append to `customer.documents`.
- **Do not** set `Content-Type` header manually.

**UI recommendations**

- Upload progress bar.
- Validate PDF/image client-side before send.

**Error handling**

- `422` on invalid mime or missing fields.

**Edge cases / business rules**

Separate from vehicle files — vehicles have no upload API today.

---

## Reservations

### Reservation list

| Item | Details |
|------|---------|
| **User goal** | Browse reservations with status and payment badges. |
| **Preconditions** | `reservations.view` permission. |
| **Entry point** | /admin/reservations |
| **Permissions** | reservations.view |

**Flow**

```
Open Reservations page
↓
GET /admin/reservations?page=1
↓
Render table (number, customer, vehicle, dates, status, payment_status)
↓
User changes page → GET ?page=n
```

**Endpoints**

- `GET /admin/reservations`

**Captured response snippet**

```json
{
    "data": [
        {
            "id": 12,
            "reservation_number": "RSV-20260611-2333",
            "customer": {
                "id": 7,
                "full_name": "Postman Public Customer",
                "nationality": "Moroccan",
                "phone": "+212600000000",
                "email": "postman.public@example.com",
                "passport_or_cin": "PM123456",
                "driving_license_number": "PM-DL-001",
                "created_at": "2026-06-11T23:54:43.000000Z",
                "updated_at": "2026-06-11T23:54:43.000000Z"
            },
            "vehicle": {
                "id": 1,
                "name": "Dacia Sandero 2024",
                "slug": "dacia-sandero-2024",
                "model": "Sandero",
                "year": 2024,
                "plate_number": "12345-A-10",
                "mileage": 12500,
                "current_mileage_updated_at": "2026-06-10T23:54:32.000000Z",
  // ... truncated
```

**Frontend state**

- **Local:** `reservations[]`, pagination meta.
- **Global:** lookup maps for status/payment_status labels.
- **Discard:** rows when page changes.

**UI recommendations**

- **Loading:** skeleton table rows.
- **Empty:** “No reservations yet” + link to create (if `reservations.create`).
- **Success toast:** none on list load.
- Color-coded badges from `status.slug` and `payment_status.slug`.

**Error handling**

- `403`: hide nav item.
- Empty `data[]`: empty state, not error.

**Recommended page structure**

- **Page:** `ReservationsListPage`
- **Components:** `ReservationsTable`, `StatusBadge`, `PaymentStatusBadge`, `Pagination`, `CreateReservationButton`

**Edge cases / business rules**

List is paginated (`per_page` 15) and ordered latest first. No server-side filter — filter client-side on loaded page if needed.

---

### Reservation details

| Item | Details |
|------|---------|
| **User goal** | Inspect one reservation with payment balance and contract panel. |
| **Preconditions** | Reservation id from list, calendar, or deep link. |
| **Entry point** | /admin/reservations/{id} |
| **Permissions** | reservations.view (+ payments.view / contracts.view for side panels) |

**Flow**

```
Open detail route
↓
GET /admin/reservations/{id}
↓
Parallel (if permitted):
  GET /admin/reservations/{id}/payment-summary
  GET /admin/reservations/{id}/contract
↓
Render header + timeline + action bar + payment card + contract card
```

**Endpoints**

- `GET /admin/reservations/{id}`
- `GET /admin/reservations/{id}/payment-summary` (`payments.view`)
- `GET /admin/reservations/{id}/contract` (`contracts.view`, 404 if none)

**Captured response snippet**

```json
{
    "data": {
        "id": 13,
        "reservation_number": "RSV-20260611-1494",
        "customer": {
            "id": 1,
            "full_name": "Postman Customer",
            "nationality": "Moroccan",
            "phone": "+212611111111",
            "email": "postman.customer@example.com",
            "passport_or_cin": "PC123456",
            "driving_license_number": "PC-DL-001",
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        },
        "vehicle": {
            "id": 2,
            "name": "Postman Test Vehicle Updated",
            "slug": "postman-test-vehicle",
            "model": "Sandero",
            "year": 2024,
            "plate_number": "PM-100-TEST",
            "mileage": 13500,
            "current_mileage_updated_at": "2026-06-10T23:54:32.000000Z",
            "seats": 5,
            "doors": 5,
            "daily_price": "390.00",
            "weekly_price": "2300.00",
            "monthly_price": "8800.00",
            "deposit_amount": "3000.00",
  // ... truncated
```

**Frontend state**

- **Local:** `reservation`, `paymentSummary`, `contract|null`.
- **Global:** none.
- **Route:** `{id}` param.

**UI recommendations**

- **Loading:** full-page skeleton or spinner on each panel.
- **Empty contract:** “No contract generated yet” + Generate button.
- **Success toast:** after lifecycle actions (see status workflows).

**Error handling**

- `404`: deleted reservation → back to list.
- Contract `404`: treat as no contract, not fatal.

**Recommended page structure**

- **Page:** `ReservationDetailPage`
- **Components:** `ReservationHeader`, `ReservationTimeline`, `LifecycleActionBar`, `PaymentSummaryCard`, `ContractPanel`, `NotesSection`

**Edge cases / business rules**

Detail includes nested `customer`, `vehicle`, `pickup_location`, `dropoff_location`, pricing fields (`total_price`, `deposit_amount`), and timestamp fields (`confirmed_at`, `started_at`, etc.).

---

### Create reservation (admin)

| Item | Details |
|------|---------|
| **User goal** | Book a vehicle for a customer; starts as pending/unpaid. |
| **Preconditions** | `reservations.create`; customers, vehicles, locations available. |
| **Entry point** | /admin/reservations/new |
| **Permissions** | reservations.create |

**Flow**

```
Open create wizard
↓
Load selects:
  GET /admin/lookups
  GET /admin/customers?page=1
  GET /admin/vehicles?page=1
  GET /admin/locations?page=1
↓
User picks customer, vehicle, locations, date range
↓
POST /admin/reservations/check-availability
↓
If available: POST /admin/reservations
↓
201 → navigate to /admin/reservations/{id}
↓
Toast: “Reservation created”
```

**Endpoints**

- `GET /admin/lookups`
- `GET /admin/customers` (paginated)
- `GET /admin/vehicles` (paginated)
- `GET /admin/locations` (paginated)
- `POST /admin/reservations/check-availability`
- `POST /admin/reservations`

**Request bodies**

**Check availability:**
```json
{"vehicle_id":1,"start_datetime":"2026-08-01 10:00:00","end_datetime":"2026-08-05 10:00:00","ignore_reservation_id":null}
```

**Create:**
```json
{"customer_id":1,"vehicle_id":1,"pickup_location_id":1,"dropoff_location_id":1,"start_datetime":"2026-08-01 10:00:00","end_datetime":"2026-08-05 10:00:00","customer_notes":"Postman admin reservation.","admin_notes":"QA test."}
```

**Captured response snippet**

```json
{
    "data": {
        "id": 13,
        "reservation_number": "RSV-20260611-1494",
        "customer": {
            "id": 1,
            "full_name": "Postman Customer",
            "nationality": "Moroccan",
            "phone": "+212611111111",
            "email": "postman.customer@example.com",
            "passport_or_cin": "PC123456",
            "driving_license_number": "PC-DL-001",
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        },
        "vehicle": {
            "id": 2,
            "name": "Postman Test Vehicle Updated",
            "slug": "postman-test-vehicle",
            "model": "Sandero",
            "year": 2024,
            "plate_number": "PM-100-TEST",
            "mileage": 13500,
            "current_mileage_updated_at": "2026-06-10T23:54:32.000000Z",
            "seats": 5,
            "doors": 5,
            "daily_price": "390.00",
            "weekly_price": "2300.00",
            "monthly_price": "8800.00",
            "deposit_amount": "3000.00",
  // ... truncated
```

**Frontend state**

- **Local:** wizard step state, `availabilityResult`.
- **After success:** store `data.id` for redirect.
- **Discard:** wizard on navigate away.

**UI recommendations**

- **Loading:** disable Next/Save while checking availability or submitting.
- **Empty selects:** prompt to create customer/vehicle/location first.
- **Success toast:** “Reservation created”.
- Show pricing preview from create response (`total_price`, `total_days`).

**Error handling**

- `422` on overlap, invalid IDs, or invalid date order — show field errors.
- If `available: false`, block submit and explain conflict.

**Recommended page structure**

- **Page:** `ReservationCreatePage` (multi-step wizard)
- **Components:** `CustomerSelect`, `VehicleSelect`, `LocationSelect`, `DateRangePicker`, `AvailabilityBanner`, `PricingPreview`

**Edge cases / business rules**

**Pending reservations do not reserve the vehicle** until confirmed. Source is `admin_manual` for admin-created bookings.

---

### Edit reservation

| Item | Details |
|------|---------|
| **User goal** | Update dates, vehicle, locations, or notes; pricing recalculated server-side. |
| **Preconditions** | `reservations.update`; reservation loaded. |
| **Entry point** | /admin/reservations/{id}/edit |
| **Permissions** | reservations.update |

**Flow**

```
Load GET /admin/reservations/{id}
↓
Populate form
↓
On date/vehicle change: POST /admin/reservations/check-availability (pass ignore_reservation_id)
↓
PUT or PATCH /admin/reservations/{id}
↓
Refresh detail
↓
Toast: “Reservation updated”
```

**Endpoints**

- `GET /admin/reservations/{id}`
- `POST /admin/reservations/check-availability`
- `PUT /admin/reservations/{id}` or `PATCH /admin/reservations/{id}`

**Request bodies**

PATCH example — send only changed fields. Full PUT sends same shape as create plus resolved ids.

**Captured response snippet**

```json
{
    "data": {
        "id": 13,
        "reservation_number": "RSV-20260611-1494",
        "customer": {
            "id": 1,
            "full_name": "Postman Customer",
            "nationality": "Moroccan",
            "phone": "+212611111111",
            "email": "postman.customer@example.com",
            "passport_or_cin": "PC123456",
            "driving_license_number": "PC-DL-001",
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        },
        "vehicle": {
            "id": 2,
            "name": "Postman Test Vehicle Updated",
            "slug": "postman-test-vehicle",
            "model": "Sandero",
            "year": 2024,
            "plate_number": "PM-100-TEST",
            "mileage": 13500,
            "current_mileage_updated_at": "2026-06-10T23:54:32.000000Z",
            "seats": 5,
  // ... truncated
```

**Frontend state**

- **Local:** form ↔ API mapping.
- **Discard** dirty state after save.

**UI recommendations**

- **Loading:** inline save spinner.
- **Success toast:** “Reservation updated”.
- Warn on unsaved changes.

**Error handling**

- `422` when overlapping confirmed/in_progress reservation for same vehicle.
- Cannot edit terminal statuses (completed/cancelled/rejected) — hide form.

**Recommended page structure**

- **Page:** `ReservationEditPage`
- **Reuse:** create wizard components in edit mode

**Edge cases / business rules**

Changing `vehicle_id` or dates triggers availability validation. Response includes recalculated `total_price`, `delivery_fee`, `total_days`.

---

### Cancel reservation

| Item | Details |
|------|---------|
| **User goal** | Cancel an active reservation and free the vehicle if applicable. |
| **Preconditions** | `reservations.cancel`; reservation not completed/cancelled/rejected. |
| **Entry point** | Reservation detail action bar. |
| **Permissions** | reservations.cancel |

**Flow**

```
User clicks Cancel
↓
Confirmation dialog
↓
POST /admin/reservations/{id}/cancel
↓
Update status.slug → cancelled
↓
Refresh detail
↓
Toast: “Reservation cancelled”
```

**Endpoints**

- `POST /admin/reservations/{id}/cancel`

**Request bodies**

No request body.

**Captured response snippet**

```json
{
    "data": {
        "id": 3,
        "reservation_number": "RSV-QA-CANCEL-001",
        "customer": {
            "id": 1,
            "full_name": "Postman Customer",
            "nationality": "Moroccan",
            "phone": "+212611111111",
            "email": "postman.customer@example.com",
            "passport_or_cin": "PC123456",
            "driving_license_number": "PC-DL-001",
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        },
        "vehicle": {
            "id": 4,
            "name": "QA Cancel Vehicle",
            "slug": "qa-cancel-vehicle",
            "model": "Sandero",
            "year": 2024,
            "plate_number": "QA-CANCEL-01",
            "mileage": 15000,
            "current_mileage_updated_at": "2026-06-10T23:54:32.000000Z",
            "seats": 5,
  // ... truncated
```

**Frontend state**

- **Local:** merge updated `status` and `cancelled_at` into `reservation`.
- **Disable** lifecycle buttons after cancel.

**UI recommendations**

- **Loading:** disable action bar during request.
- **Confirmation:** explain vehicle may become available.
- **Success toast:** “Reservation cancelled”.

**Error handling**

- `422` if already completed/cancelled/rejected.
- `403` without `reservations.cancel`.

**Recommended page structure**

- **Component:** `CancelReservationButton` on `ReservationDetailPage`

**Edge cases / business rules**

Cancel is a dedicated POST action — **not** a generic status PATCH.

---

### Confirm reservation

| Item | Details |
|------|---------|
| **User goal** | Move pending reservation to confirmed; vehicle becomes reserved. |
| **Preconditions** | `reservations.confirm`; `status.slug` is `pending`. |
| **Entry point** | Reservation detail action bar. |
| **Permissions** | reservations.confirm |

**Flow**

```
User clicks Confirm
↓
POST /admin/reservations/{id}/confirm
↓
status.slug → confirmed
↓
Refresh detail + payment summary
↓
Toast: “Reservation confirmed”
```

**Endpoints**

- `POST /admin/reservations/{id}/confirm`

**Request bodies**

No request body.

**Captured response snippet**

```json
{
    "data": {
        "id": 2,
        "reservation_number": "RSV-QA-LIFECYCLE-001",
        "customer": {
            "id": 1,
            "full_name": "Postman Customer",
            "nationality": "Moroccan",
            "phone": "+212611111111",
            "email": "postman.customer@example.com",
            "passport_or_cin": "PC123456",
            "driving_license_number": "PC-DL-001",
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        },
        "vehicle": {
            "id": 3,
            "name": "QA Lifecycle Vehicle",
            "slug": "qa-lifecycle-vehicle",
            "model": "Sandero",
            "year": 2024,
            "plate_number": "QA-LIFE-01",
            "mileage": 15000,
            "current_mileage_updated_at": "2026-06-10T23:54:32.000000Z",
            "seats": 5,
  // ... truncated
```

**Frontend state**

- **Local:** update `reservation.status`, `confirmed_at`.
- **Show** contract generate + payment actions after confirm.

**UI recommendations**

- **Loading:** button spinner.
- **Success toast:** “Reservation confirmed”.

**Error handling**

- `422` if not pending or business rules fail.

**Recommended page structure**

- **Component:** `ConfirmReservationButton` (visible only when `status.slug === pending`)

**Edge cases / business rules**

Only valid from `pending`. Vehicle status may change to `reserved` after confirm.

---

### Start reservation (rental in progress)

| Item | Details |
|------|---------|
| **User goal** | Mark confirmed rental as in progress when customer picks up vehicle. |
| **Preconditions** | `reservations.start`; `status.slug` is `confirmed`. |
| **Entry point** | Reservation detail action bar. |
| **Permissions** | reservations.start |

**Flow**

```
User clicks Start rental
↓
POST /admin/reservations/{id}/start
↓
status.slug → in_progress
↓
Toast: “Rental started”
```

**Endpoints**

- `POST /admin/reservations/{id}/start`

**Request bodies**

No request body.

**Captured response snippet**

```json
{
    "data": {
        "id": 2,
        "reservation_number": "RSV-QA-LIFECYCLE-001",
        "customer": {
            "id": 1,
            "full_name": "Postman Customer",
            "nationality": "Moroccan",
            "phone": "+212611111111",
            "email": "postman.customer@example.com",
            "passport_or_cin": "PC123456",
            "driving_license_number": "PC-DL-001",
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        },
        "vehicle": {
            "id": 3,
            "name": "QA Lifecycle Vehicle",
            "slug": "qa-lifecycle-vehicle",
            "model": "Sandero",
            "year": 2024,
            "plate_number": "QA-LIFE-01",
            "mileage": 15000,
            "current_mileage_updated_at": "2026-06-10T23:54:32.000000Z",
            "seats": 5,
  // ... truncated
```

**Frontend state**

- **Local:** update `status`, `started_at`.

**UI recommendations**

- **Success toast:** “Rental started”.

**Error handling**

- `422` if not confirmed.

**Recommended page structure**

- **Component:** `StartRentalButton` (visible when `status.slug === confirmed`)

**Edge cases / business rules**

Vehicle typically moves to `rented` status when rental starts.

---

### Complete reservation

| Item | Details |
|------|---------|
| **User goal** | Close an in-progress rental. |
| **Preconditions** | `reservations.complete`; `status.slug` is `in_progress`. |
| **Entry point** | Reservation detail action bar. |
| **Permissions** | reservations.complete |

**Flow**

```
User clicks Complete
↓
POST /admin/reservations/{id}/complete
↓
status.slug → completed
↓
Toast: “Rental completed”
```

**Endpoints**

- `POST /admin/reservations/{id}/complete`

**Request bodies**

No request body.

**Captured response snippet**

```json
{
    "data": {
        "id": 2,
        "reservation_number": "RSV-QA-LIFECYCLE-001",
        "customer": {
            "id": 1,
            "full_name": "Postman Customer",
            "nationality": "Moroccan",
            "phone": "+212611111111",
            "email": "postman.customer@example.com",
            "passport_or_cin": "PC123456",
            "driving_license_number": "PC-DL-001",
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        },
        "vehicle": {
            "id": 3,
            "name": "QA Lifecycle Vehicle",
            "slug": "qa-lifecycle-vehicle",
            "model": "Sandero",
            "year": 2024,
            "plate_number": "QA-LIFE-01",
            "mileage": 15000,
            "current_mileage_updated_at": "2026-06-10T23:54:32.000000Z",
            "seats": 5,
  // ... truncated
```

**Frontend state**

- **Local:** update `status`, `completed_at`.
- **Hide** all lifecycle actions except view.

**UI recommendations**

- **Success toast:** “Rental completed”.

**Error handling**

- `422` if not in_progress.

**Recommended page structure**

- **Component:** `CompleteRentalButton` (visible when `status.slug === in_progress`)

**Edge cases / business rules**

Completed reservations are terminal — no further lifecycle actions.

---

### Reject reservation

| Item | Details |
|------|---------|
| **User goal** | Reject a pending booking (e.g. website request). |
| **Preconditions** | `reservations.reject`; `status.slug` is `pending`. |
| **Entry point** | Reservation detail action bar. |
| **Permissions** | reservations.reject |

**Flow**

```
User clicks Reject
↓
Confirm dialog
↓
POST /admin/reservations/{id}/reject
↓
status.slug → rejected
↓
Toast: “Reservation rejected”
```

**Endpoints**

- `POST /admin/reservations/{id}/reject`

**Request bodies**

No request body.

**Captured response snippet**

```json
{
    "data": {
        "id": 4,
        "reservation_number": "RSV-QA-REJECT-001",
        "customer": {
            "id": 1,
            "full_name": "Postman Customer",
            "nationality": "Moroccan",
            "phone": "+212611111111",
            "email": "postman.customer@example.com",
            "passport_or_cin": "PC123456",
            "driving_license_number": "PC-DL-001",
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        },
        "vehicle": {
            "id": 5,
            "name": "QA Reject Vehicle",
            "slug": "qa-reject-vehicle",
            "model": "Sandero",
            "year": 2024,
            "plate_number": "QA-REJECT-01",
            "mileage": 15000,
            "current_mileage_updated_at": "2026-06-10T23:54:32.000000Z",
            "seats": 5,
  // ... truncated
```

**Frontend state**

- **Local:** update `status`.

**UI recommendations**

- **Confirmation:** optional reason note (UI only — API has no body).
- **Success toast:** “Reservation rejected”.

**Error handling**

- `422` if not pending.

**Recommended page structure**

- **Component:** `RejectReservationButton` (pending only)

**Edge cases / business rules**

Rejected reservations are terminal.

---

### Reservations calendar view

| Item | Details |
|------|---------|
| **User goal** | Visualize reservations on a date-range calendar. |
| **Preconditions** | `reservations.view`. |
| **Entry point** | /admin/reservations/calendar |
| **Permissions** | reservations.view |

**Flow**

```
Open calendar page
↓
User selects week/month range
↓
GET /admin/reservations-calendar?start=YYYY-MM-DD&end=YYYY-MM-DD
↓
Plot events from response
↓
Click event → navigate to /admin/reservations/{id}
```

**Endpoints**

- `GET /admin/reservations-calendar?start={date}&end={date}`

**Captured response snippet**

```json
{
    "data": [
        {
            "id": 1,
            "reservation_number": "RSV-QA-ADMIN-001",
            "customer": {
                "id": 1,
                "full_name": "Postman Customer",
                "nationality": "Moroccan",
                "phone": "+212611111111",
                "email": "postman.customer@example.com",
                "passport_or_cin": "PC123456",
                "driving_license_number": "PC-DL-001",
                "created_at": "2026-06-11T23:05:51.000000Z",
                "updated_at": "2026-06-11T23:05:51.000000Z"
            },
            "vehicle": {
                "id": 2,
                "name": "Postman Test Vehicle Updated",
                "slug": "postman-test-vehicle",
                "model": "Sandero",
                "year": 2024,
                "plate_number": "PM-100-TEST",
                "mileage": 13500,
                "current_mileage_updated_at": "2026-06-10T23:54:32.000000Z",
  // ... truncated
```

**Frontend state**

- **Local:** `calendarEvents[]`, `visibleRange`.
- **Global:** none.

**UI recommendations**

- **Loading:** calendar skeleton.
- **Empty:** “No reservations in this period”.
- Color events by `status.slug`.

**Error handling**

- `422` on invalid date range — reset to current month.

**Recommended page structure**

- **Page:** `ReservationsCalendarPage`
- **Components:** `CalendarToolbar` (range picker), `ReservationCalendar`, `CalendarEventChip`

**Edge cases / business rules**

Calendar supports `start` and `end` query params (date strings). Each event includes reservation id, customer, vehicle, and datetimes.

---

## Payments

### Payments list

| Item | Details |
|------|---------|
| **User goal** | Browse all payments across reservations. |
| **Preconditions** | `payments.view` permission. |
| **Entry point** | /admin/payments |
| **Permissions** | payments.view |

**Flow**

```
Open Payments page
↓
GET /admin/payments?page=1
↓
Render table (reservation, amount, method, status, date)
↓
User changes page → GET ?page=n
```

**Endpoints**

- `GET /admin/payments`

**Captured response snippet**

```json
{
    "data": [
        {
            "id": 3,
            "reservation": {
                "id": 6,
                "reservation_number": "RSV-QA-PAYMENT-001",
                "total_price": "4700.00"
            },
            "payment_method": {
                "id": 1,
                "name": "Cash",
                "slug": "cash"
            },
            "payment_type": {
                "id": 2,
                "name": "Rental Payment",
                "slug": "rental_payment"
            },
            "payment_status": {
                "id": 4,
                "name": "Cancelled",
                "slug": "cancelled"
            },
            "amount": "1000.00",
  // ... truncated
```

**Frontend state**

- **Local:** `payments[]`, pagination meta.
- **Discard:** rows on page change.

**UI recommendations**

- **Loading:** skeleton rows.
- **Empty:** “No payments recorded”.
- Link reservation number to reservation detail.

**Error handling**

- `403`: hide module.
- Payments are **not** soft-deleted.

**Recommended page structure**

- **Page:** `PaymentsListPage`
- **Components:** `PaymentsTable`, `PaymentStatusBadge`, `Pagination`

**Edge cases / business rules**

Each row embeds `reservation.reservation_number` and `total_price` for context.

---

### Payment details

| Item | Details |
|------|---------|
| **User goal** | Inspect a single payment record. |
| **Preconditions** | Payment id from list or reservation context. |
| **Entry point** | /admin/payments/{id} |
| **Permissions** | payments.view |

**Flow**

```
Click row
↓
GET /admin/payments/{id}
↓
Render payment detail card
```

**Endpoints**

- `GET /admin/payments/{id}`

**Captured response snippet**

```json
{
    "data": {
        "id": 4,
        "reservation": {
            "id": 6,
            "reservation_number": "RSV-QA-PAYMENT-001",
            "total_price": "4700.00"
        },
        "payment_method": {
            "id": 1,
            "name": "Cash",
            "slug": "cash"
        },
        "payment_type": {
            "id": 2,
            "name": "Rental Payment",
            "slug": "rental_payment"
        },
        "payment_status": {
            "id": 3,
            "name": "Paid",
            "slug": "paid"
        },
        "amount": "300.00",
        "payment_date": "2026-06-10T00:00:00.000000Z",
  // ... truncated
```

**Frontend state**

- **Local:** `payment` object.
- **Route:** `{id}` param.

**UI recommendations**

- **Loading:** card skeleton.
- Show link back to parent reservation.

**Error handling**

- `404`: redirect to payments list.

**Recommended page structure**

- **Page:** `PaymentDetailPage`
- **Components:** `PaymentDetailCard`, `ReservationLink`

**Edge cases / business rules**

Includes `paid_by_customer_name`, `reference`, `notes`, and nested lookup objects.

---

### Register payment

| Item | Details |
|------|---------|
| **User goal** | Record a payment against a reservation; recalculates reservation payment_status. |
| **Preconditions** | `payments.manage`. |
| **Entry point** | Reservation detail “Add payment” or /admin/payments/new |
| **Permissions** | payments.manage |

**Flow**

```
Open payment form (pre-fill reservation_id from context)
↓
User enters amount, method, type, status, date
↓
POST /admin/payments
↓
Refresh GET /admin/reservations/{id}/payment-summary
↓
Toast: “Payment recorded”
```

**Endpoints**

- `POST /admin/payments`
- `GET /admin/reservations/{id}/payment-summary` (refresh)

**Request bodies**

```json
{
  "reservation_id": 6,
  "payment_method_slug": "cash",
  "payment_type_slug": "rental_payment",
  "payment_status_slug": "paid",
  "amount": 300,
  "payment_date": "2026-06-10",
  "paid_by_customer_name": "Postman Customer",
  "reference": "PM-PAY-001",
  "notes": "Postman payment."
}
```

**Captured response snippet**

```json
{
    "data": {
        "id": 4,
        "reservation": {
            "id": 6,
            "reservation_number": "RSV-QA-PAYMENT-001",
            "total_price": "4700.00"
        },
        "payment_method": {
            "id": 1,
            "name": "Cash",
            "slug": "cash"
        },
        "payment_type": {
            "id": 2,
            "name": "Rental Payment",
            "slug": "rental_payment"
        },
        "payment_status": {
            "id": 3,
            "name": "Paid",
            "slug": "paid"
        },
        "amount": "300.00",
        "payment_date": "2026-06-10T00:00:00.000000Z",
  // ... truncated
```

**Frontend state**

- **Local:** append to payments list on reservation detail.
- **Update** `paymentSummary` from refresh call.

**UI recommendations**

- **Loading:** disable submit.
- **Success toast:** “Payment recorded”.
- Pre-fill `paid_by_customer_name` from reservation customer.

**Error handling**

- `422` on invalid slug, amount, or reservation.
- Only `paid` status increases `paid_amount` in summary.

**Recommended page structure**

- **Component:** `PaymentFormModal` on reservation detail
- **Page (optional):** `PaymentCreatePage`

**Edge cases / business rules**

Creating with `failed` or `refunded` does not increase balance. Reservation `payment_status` auto-updates (e.g. `partial_paid`, `paid`).

---

### Update payment

| Item | Details |
|------|---------|
| **User goal** | Correct amount, status, or metadata; recalculates reservation balances. |
| **Preconditions** | `payments.manage`; payment loaded. |
| **Entry point** | /admin/payments/{id}/edit |
| **Permissions** | payments.manage |

**Flow**

```
Load GET /admin/payments/{id}
↓
Populate form
↓
PUT or PATCH /admin/payments/{id}
↓
Refresh payment summary on linked reservation
↓
Toast: “Payment updated”
```

**Endpoints**

- `GET /admin/payments/{id}`
- `PUT /admin/payments/{id}` or `PATCH /admin/payments/{id}`

**Request bodies**

PATCH — send only changed fields (e.g. `payment_status_slug`, `amount`, `notes`).

**Captured response snippet**

```json
{
    "data": {
        "id": 4,
        "reservation": {
            "id": 6,
            "reservation_number": "RSV-QA-PAYMENT-001",
            "total_price": "4700.00"
        },
        "payment_method": {
            "id": 1,
            "name": "Cash",
            "slug": "cash"
        },
        "payment_type": {
            "id": 2,
            "name": "Rental Payment",
            "slug": "rental_payment"
        },
        "payment_status": {
            "id": 6,
            "name": "Refunded",
            "slug": "refunded"
        },
        "amount": "1000.00",
        "payment_date": "2026-06-10T00:00:00.000000Z",
  // ... truncated
```

**Frontend state**

- **Local:** replace payment in cache.
- **Refresh** parent reservation payment summary.

**UI recommendations**

- **Success toast:** “Payment updated”.

**Error handling**

- `422` on invalid transitions or amounts.

**Recommended page structure**

- **Page:** `PaymentEditPage` or inline edit on detail

**Edge cases / business rules**

Changing `payment_status_slug` to `cancelled`/`refunded`/`failed` removes amount from `paid_amount`. Use dedicated cancel endpoint when appropriate.

---

### Cancel payment

| Item | Details |
|------|---------|
| **User goal** | Void a payment via status transition (no DELETE endpoint). |
| **Preconditions** | `payments.manage`. |
| **Entry point** | Payment detail or reservation payments list. |
| **Permissions** | payments.manage |

**Flow**

```
User clicks Cancel payment
↓
Confirm dialog
↓
POST /admin/payments/{id}/cancel
↓
payment_status.slug → cancelled
↓
Refresh payment summary
↓
Toast: “Payment cancelled”
```

**Endpoints**

- `POST /admin/payments/{id}/cancel`

**Request bodies**

No request body.

**Captured response snippet**

```json
{
    "data": {
        "id": 4,
        "reservation": {
            "id": 6,
            "reservation_number": "RSV-QA-PAYMENT-001",
            "total_price": "4700.00"
        },
        "payment_method": {
            "id": 1,
            "name": "Cash",
            "slug": "cash"
        },
        "payment_type": {
            "id": 2,
            "name": "Rental Payment",
            "slug": "rental_payment"
        },
        "payment_status": {
            "id": 4,
            "name": "Cancelled",
            "slug": "cancelled"
        },
        "amount": "1000.00",
        "payment_date": "2026-06-10T00:00:00.000000Z",
  // ... truncated
```

**Frontend state**

- **Local:** update payment status in list.
- **Recalculate** summary `paid_amount` and `remaining_amount`.

**UI recommendations**

- **Confirmation:** warn that balance will increase.
- **Success toast:** “Payment cancelled”.

**Error handling**

- There is **no DELETE** for payments.

**Recommended page structure**

- **Component:** `CancelPaymentButton` on payment row/detail

**Edge cases / business rules**

For failed/refunded scenarios, you may also create or PATCH with `payment_status_slug` of `failed` or `refunded` — neither counts toward `paid_amount`.

---

## Contracts

> There is **no** `GET /admin/contracts` list endpoint. Contracts are always accessed **per reservation** via `GET /admin/reservations/{id}/contract`.

### View contract on reservation

| Item | Details |
|------|---------|
| **User goal** | Show contract metadata and PDF availability flags. |
| **Preconditions** | `contracts.view`; reservation detail open. |
| **Entry point** | Contract panel on reservation detail. |
| **Permissions** | contracts.view |

**Flow**

```
Reservation detail loaded
↓
GET /admin/reservations/{id}/contract
↓
Render contract_number, status, has_pdf, has_signed_pdf
↓
Show Generate / Download / Upload actions based on state
```

**Endpoints**

- `GET /admin/reservations/{id}/contract`

**Captured response snippet**

```json
{
    "data": {
        "id": 1,
        "reservation_id": 5,
        "contract_number": "CTR-20260611-6032",
        "status": {
            "id": 2,
            "name": "Generated",
            "slug": "generated"
        },
        "has_pdf": true,
        "has_signed_pdf": true,
        "generated_by": {
            "id": 1,
            "name": "Limosud Cars Admin",
            "email": "admin@limosudcars.local"
        },
        "generated_at": "2026-06-11T23:55:27.000000Z",
        "signed_at": "2026-06-11T23:19:44.000000Z",
        "created_at": "2026-06-11T23:16:49.000000Z",
        "updated_at": "2026-06-11T23:55:27.000000Z"
    }
}
```

**Frontend state**

- **Local:** `contract` object or `null`.
- **Store** `contract.id` for download/upload routes.

**UI recommendations**

- **Loading:** panel skeleton.
- **Empty (404):** “No contract yet” + Generate CTA if permitted.
- Display `contract_status` badge.

**Error handling**

- `404` on GET contract: treat as no contract (not an error page).
- `403`: hide contract panel.

**Recommended page structure**

- **Component:** `ContractPanel` on `ReservationDetailPage`
- **Subcomponents:** `ContractStatusBadge`, `ContractActions`

**Edge cases / business rules**

Response includes `has_pdf` and `has_signed_pdf` booleans — use these to show/hide download buttons.

---

### Generate contract

| Item | Details |
|------|---------|
| **User goal** | Create or regenerate PDF contract for a confirmed+ reservation. |
| **Preconditions** | `contracts.generate`; reservation not pending/rejected/cancelled. |
| **Entry point** | Contract panel on reservation detail. |
| **Permissions** | contracts.generate |

**Flow**

```
User clicks Generate contract
↓
POST /admin/reservations/{id}/contract/generate
↓
Refresh GET /admin/reservations/{id}/contract
↓
Enable Download button
↓
Toast: “Contract generated”
```

**Endpoints**

- `POST /admin/reservations/{id}/contract/generate`
- `GET /admin/reservations/{id}/contract` (refresh)

**Request bodies**

No request body.

**Captured response snippet**

```json
{
    "data": {
        "id": 1,
        "reservation_id": 5,
        "contract_number": "CTR-20260611-6032",
        "status": {
            "id": 2,
            "name": "Generated",
            "slug": "generated"
        },
        "has_pdf": true,
        "has_signed_pdf": true,
        "generated_by": {
            "id": 1,
            "name": "Limosud Cars Admin",
            "email": "admin@limosudcars.local"
        },
        "generated_at": "2026-06-11T23:55:27.000000Z",
        "signed_at": "2026-06-11T23:19:44.000000Z",
        "created_at": "2026-06-11T23:16:49.000000Z",
        "updated_at": "2026-06-11T23:55:27.000000Z"
    }
}
```

**Frontend state**

- **Local:** replace `contract` with response.
- **Keep** same `contract_number` on regeneration.

**UI recommendations**

- **Loading:** disable Generate during request.
- **Success toast:** “Contract generated”.

**Error handling**

- `422` if reservation still `pending`.
- Must be confirmed or later status.

**Recommended page structure**

- **Component:** `GenerateContractButton` inside `ContractPanel`

**Edge cases / business rules**

Regeneration keeps the same `contract_number` per captured QA behavior.

---

### Upload signed contract

| Item | Details |
|------|---------|
| **User goal** | Attach customer-signed PDF; moves contract status toward signed. |
| **Preconditions** | `contracts.update`; contract exists. |
| **Entry point** | Contract panel upload area. |
| **Permissions** | contracts.update |

**Flow**

```
User selects signed PDF
↓
Build FormData with signed_pdf
↓
POST /admin/contracts/{id}/signed
↓
Refresh contract panel
↓
Toast: “Signed contract uploaded”
```

**Endpoints**

- `POST /admin/contracts/{contract}/signed`

**Request bodies**

FormData:
- `signed_pdf` (file, PDF)

**Captured response snippet**

```json
{
    "data": {
        "id": 1,
        "reservation_id": 5,
        "contract_number": "CTR-20260611-6032",
        "status": {
            "id": 3,
            "name": "Signed",
            "slug": "signed"
        },
        "has_pdf": true,
        "has_signed_pdf": true,
        "generated_by": {
            "id": 1,
            "name": "Limosud Cars Admin",
            "email": "admin@limosudcars.local"
        },
        "generated_at": "2026-06-11T23:55:27.000000Z",
        "signed_at": "2026-06-11T23:55:29.000000Z",
        "created_at": "2026-06-11T23:16:49.000000Z",
        "updated_at": "2026-06-11T23:55:29.000000Z"
    }
}
```

**Frontend state**

- **Local:** update `contract` status and `has_signed_pdf`.
- **Do not** set Content-Type header on multipart.

**UI recommendations**

- **Loading:** upload progress bar.
- **Success toast:** “Signed contract uploaded”.

**Error handling**

- `422` on invalid file type.

**Recommended page structure**

- **Component:** `SignedContractUpload` inside `ContractPanel`

**Edge cases / business rules**

Field name is `signed_pdf`. Optional in API but required for meaningful upload.

---

### Download contract PDF

| Item | Details |
|------|---------|
| **User goal** | Download generated or signed PDF as blob. |
| **Preconditions** | `contracts.view`; `has_pdf` or `has_signed_pdf` is true. |
| **Entry point** | Contract panel download button. |
| **Permissions** | contracts.view |

**Flow**

```
User clicks Download
↓
GET /admin/contracts/{id}/download
↓
response.blob()
↓
Trigger browser save or open in PDF viewer
```

**Endpoints**

- `GET /admin/contracts/{contract}/download`

**Captured response snippet**

```json
_Binary PDF response (endpoint 72). Use `Accept: application/pdf` and bearer token._
```

**Frontend state**

- **Local:** temporary `blobUrl` for preview; revoke after use.

**UI recommendations**

- **Loading:** spinner on download button.
- **Error:** toast if PDF not ready.

**Error handling**

- `404` if PDF not generated yet.

**Recommended page structure**

- **Component:** `DownloadContractButton`
- **Optional:** `PdfPreviewModal` using `URL.createObjectURL(blob)`

**Edge cases / business rules**

Response is binary — not JSON. Handle with `fetch` + `blob()`, not `response.json()`.

---

### Cancel contract

| Item | Details |
|------|---------|
| **User goal** | Cancel an existing contract record. |
| **Preconditions** | `contracts.update`. |
| **Entry point** | Contract panel danger action. |
| **Permissions** | contracts.update |

**Flow**

```
User clicks Cancel contract
↓
Confirm dialog
↓
POST /admin/contracts/{id}/cancel
↓
Refresh contract panel
↓
Toast: “Contract cancelled”
```

**Endpoints**

- `POST /admin/contracts/{contract}/cancel`

**Request bodies**

No request body.

**Captured response snippet**

```json
{
    "data": {
        "id": 1,
        "reservation_id": 5,
        "contract_number": "CTR-20260611-6032",
        "status": {
            "id": 4,
            "name": "Cancelled",
            "slug": "cancelled"
        },
        "has_pdf": true,
        "has_signed_pdf": true,
        "generated_by": {
            "id": 1,
            "name": "Limosud Cars Admin",
            "email": "admin@limosudcars.local"
        },
        "generated_at": "2026-06-11T23:55:27.000000Z",
        "signed_at": "2026-06-11T23:55:29.000000Z",
        "created_at": "2026-06-11T23:16:49.000000Z",
        "updated_at": "2026-06-11T23:55:30.000000Z"
    }
}
```

**Frontend state**

- **Local:** update `contract` status.

**UI recommendations**

- **Confirmation:** warn contract will be invalidated.
- **Success toast:** “Contract cancelled”.

**Error handling**

- `422` if contract cannot be cancelled in current state.

**Recommended page structure**

- **Component:** `CancelContractButton` (danger, in `ContractPanel`)

**Edge cases / business rules**

Separate from reservation cancel — this cancels the contract entity only.

---

## Maintenance

### List maintenance records

| Item | Details |
|------|---------|
| **User goal** | Browse fleet maintenance history. |
| **Preconditions** | `maintenance.view`. |
| **Entry point** | /admin/maintenances |
| **Permissions** | maintenance.view |

**Flow**

```
Open Maintenance page
↓
GET /admin/maintenances?page=1
↓
Render table (vehicle, type, date, cost, next date)
↓
Paginate
```

**Endpoints**

- `GET /admin/maintenances`

**Captured response snippet**

```json
{
    "data": [
        {
            "id": 1,
            "vehicle": {
                "id": 2,
                "name": "Postman Test Vehicle Updated",
                "slug": "postman-test-vehicle",
                "plate_number": "PM-100-TEST"
            },
            "maintenance_type": {
                "id": 1,
                "name": "Oil Change",
                "slug": "oil_change"
            },
            "maintenance_date": "2026-06-10T00:00:00.000000Z",
            "next_maintenance_date": "2026-07-11T00:00:00.000000Z",
            "mileage": 21000,
            "cost": "450.00",
            "garage_name": "Postman Garage",
            "notes": "Postman maintenance.",
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        }
    ],
  // ... truncated
```

**Frontend state**

- **Local:** `maintenances[]`, pagination.

**UI recommendations**

- **Loading:** skeleton table.
- **Empty:** “No maintenance records”.

**Error handling**

- Standard auth errors.

**Recommended page structure**

- **Page:** `MaintenanceListPage`
- **Components:** `MaintenanceTable`, `Pagination`

**Edge cases / business rules**

Each row embeds `vehicle` summary (name, plate).

---

### Upcoming maintenance widget

| Item | Details |
|------|---------|
| **User goal** | Dashboard widget for due-soon maintenance. |
| **Preconditions** | `maintenance.view`. |
| **Entry point** | Dashboard or maintenance module sidebar. |
| **Permissions** | maintenance.view |

**Flow**

```
Dashboard mount
↓
GET /admin/maintenances/upcoming?page=1
↓
Render upcoming list widget
↓
Click row → maintenance detail or vehicle detail
```

**Endpoints**

- `GET /admin/maintenances/upcoming`

**Captured response snippet**

```json
{
    "data": [
        {
            "id": 4,
            "vehicle": {
                "id": 2,
                "name": "Postman Test Vehicle Updated",
                "slug": "postman-test-vehicle",
                "plate_number": "PM-100-TEST"
            },
            "maintenance_type": {
                "id": 1,
                "name": "Oil Change",
                "slug": "oil_change"
            },
            "maintenance_date": "2026-06-10T00:00:00.000000Z",
            "next_maintenance_date": "2026-07-10T00:00:00.000000Z",
            "mileage": 21000,
            "cost": "450.00",
            "garage_name": "Postman Garage",
            "notes": "Postman maintenance.",
            "created_at": "2026-06-11T23:55:31.000000Z",
            "updated_at": "2026-06-11T23:55:31.000000Z"
        },
        {
  // ... truncated
```

**Frontend state**

- **Local:** `upcomingMaintenances[]`.
- **Global (optional):** cache on dashboard refresh.

**UI recommendations**

- **Loading:** widget skeleton.
- **Empty:** “No upcoming maintenance”.
- Highlight overdue `next_maintenance_date`.

**Error handling**

- Standard errors.

**Recommended page structure**

- **Component:** `UpcomingMaintenanceWidget` on dashboard
- **Page link:** `MaintenanceListPage`

**Edge cases / business rules**

Only records with `next_maintenance_date` today or later are returned.

---

### Create maintenance record

| Item | Details |
|------|---------|
| **User goal** | Log service work; optionally update vehicle status and auto-create expense. |
| **Preconditions** | `maintenance.create`. |
| **Entry point** | /admin/maintenances/new |
| **Permissions** | maintenance.create |

**Flow**

```
Open create form
↓
Load vehicles + lookups (maintenance types)
↓
User fills form
↓
POST /admin/maintenances
↓
201 → detail or list
↓
Toast: “Maintenance recorded”
```

**Endpoints**

- `GET /admin/vehicles?page=1` (vehicle select)
- `GET /admin/lookups` (maintenance types)
- `POST /admin/maintenances`

**Request bodies**

```json
{
  "vehicle_id": 1,
  "maintenance_type_slug": "oil_change",
  "maintenance_date": "2026-06-10",
  "next_maintenance_date": "2026-07-10",
  "mileage": 21000,
  "cost": 450,
  "garage_name": "Postman Garage",
  "notes": "Postman maintenance.",
  "vehicle_status_slug": "maintenance",
  "create_expense": true,
  "expense_category_slug": "maintenance"
}
```

**Captured response snippet**

```json
{
    "data": {
        "id": 4,
        "vehicle": {
            "id": 2,
            "name": "Postman Test Vehicle Updated",
            "slug": "postman-test-vehicle",
            "plate_number": "PM-100-TEST"
        },
        "maintenance_type": {
            "id": 1,
            "name": "Oil Change",
            "slug": "oil_change"
        },
        "maintenance_date": "2026-06-10T00:00:00.000000Z",
        "next_maintenance_date": "2026-07-10T00:00:00.000000Z",
        "mileage": 21000,
        "cost": "450.00",
        "garage_name": "Postman Garage",
        "notes": "Postman maintenance.",
        "created_at": "2026-06-11T23:55:31.000000Z",
        "updated_at": "2026-06-11T23:55:31.000000Z"
    }
}
```

**Frontend state**

- **Local:** form state.
- **After success:** redirect to detail `data.id`.

**UI recommendations**

- **Loading:** disable submit.
- **Success toast:** “Maintenance recorded”.
- Toggle for `create_expense` shows `expense_category_slug` field when true.

**Error handling**

- `422` if `create_expense: true` but missing `expense_category_slug`.
- `vehicle_status_slug` only `maintenance` or `repair`.

**Recommended page structure**

- **Page:** `MaintenanceCreatePage`
- **Components:** `VehicleSelect`, `MaintenanceTypeSelect`, `CreateExpenseToggle`

**Edge cases / business rules**

Optional side effects: vehicle status update + linked expense creation server-side.

---

### Edit maintenance record

| Item | Details |
|------|---------|
| **User goal** | Update maintenance details. |
| **Preconditions** | `maintenance.update`; record loaded. |
| **Entry point** | /admin/maintenances/{id}/edit |
| **Permissions** | maintenance.update |

**Flow**

```
GET /admin/maintenances/{id}
↓
Populate form
↓
PUT or PATCH /admin/maintenances/{id}
↓
Refresh detail
↓
Toast: “Maintenance updated”
```

**Endpoints**

- `GET /admin/maintenances/{id}`
- `PUT /admin/maintenances/{id}` or `PATCH /admin/maintenances/{id}`

**Request bodies**

PATCH — send changed fields only.

**Captured response snippet**

```json
{
    "data": {
        "id": 4,
        "vehicle": {
            "id": 2,
            "name": "Postman Test Vehicle Updated",
            "slug": "postman-test-vehicle",
            "plate_number": "PM-100-TEST"
        },
        "maintenance_type": {
            "id": 4,
            "name": "Inspection",
            "slug": "inspection"
        },
        "maintenance_date": "2026-06-11T00:00:00.000000Z",
        "next_maintenance_date": "2026-08-11T00:00:00.000000Z",
        "mileage": 21500,
        "cost": "475.00",
        "garage_name": "Postman Garage Updated",
        "notes": "Patched maintenance.",
        "created_at": "2026-06-11T23:55:31.000000Z",
        "updated_at": "2026-06-11T23:55:33.000000Z"
    }
}
```

**Frontend state**

- **Local:** form ↔ API.

**UI recommendations**

- **Success toast:** “Maintenance updated”.

**Error handling**

- `422` validation errors.

**Recommended page structure**

- **Page:** `MaintenanceEditPage`

**Edge cases / business rules**

PUT and PATCH share same handler.

---

### Close / delete maintenance record

| Item | Details |
|------|---------|
| **User goal** | Remove a maintenance record (no separate “close” endpoint). |
| **Preconditions** | `maintenance.delete`. |
| **Entry point** | Maintenance detail or list row action. |
| **Permissions** | maintenance.delete |

**Flow**

```
User confirms delete
↓
DELETE /admin/maintenances/{id}
↓
204 → remove from list
↓
Toast: “Maintenance record deleted”
```

**Endpoints**

- `DELETE /admin/maintenances/{id}`

**Captured response snippet**

```json
HTTP 204 No Content
```

**Frontend state**

- **Local:** remove from list cache.

**UI recommendations**

- **Confirmation:** standard delete dialog.
- **Success toast:** “Maintenance record deleted”.

**Error handling**

- `404` if already deleted.

**Recommended page structure**

- **Component:** `DeleteMaintenanceButton`

**Edge cases / business rules**

There is **no** separate close action — use DELETE.

---

### Vehicle maintenance tab

| Item | Details |
|------|---------|
| **User goal** | Show maintenance history for one vehicle. |
| **Preconditions** | `maintenance.view`; vehicle detail open. |
| **Entry point** | Vehicle detail → Maintenance tab. |
| **Permissions** | maintenance.view |

**Flow**

```
User opens Maintenance tab
↓
GET /admin/vehicles/{vehicleId}/maintenances
↓
Render history table
↓
Link to create maintenance (pre-fill vehicle_id)
```

**Endpoints**

- `GET /admin/vehicles/{vehicle}/maintenances`

**Captured response snippet**

```json
{
    "data": [
        {
            "id": 1,
            "vehicle": {
                "id": 2,
                "name": "Postman Test Vehicle Updated",
                "slug": "postman-test-vehicle",
                "plate_number": "PM-100-TEST"
            },
            "maintenance_type": {
                "id": 1,
                "name": "Oil Change",
                "slug": "oil_change"
            },
            "maintenance_date": "2026-06-10T00:00:00.000000Z",
            "next_maintenance_date": "2026-07-11T00:00:00.000000Z",
            "mileage": 21000,
            "cost": "450.00",
            "garage_name": "Postman Garage",
            "notes": "Postman maintenance.",
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        }
    ],
  // ... truncated
```

**Frontend state**

- **Local:** `vehicleMaintenances[]` on tab.
- **Lazy-load** tab on first open.

**UI recommendations**

- **Loading:** tab skeleton.
- **Empty:** “No maintenance for this vehicle” + Add CTA.

**Error handling**

- Standard errors.

**Recommended page structure**

- **Component:** `VehicleMaintenanceTab` on `VehicleDetailPage`

**Edge cases / business rules**

Reuses maintenance list resource shape per vehicle.

---

## Expenses

### List expenses

| Item | Details |
|------|---------|
| **User goal** | Browse operational and vehicle-linked expenses. |
| **Preconditions** | `expenses.view`. |
| **Entry point** | /admin/expenses |
| **Permissions** | expenses.view |

**Flow**

```
Open Expenses page
↓
GET /admin/expenses?page=1
↓
Render table (category, amount, date, vehicle, has_invoice)
↓
Paginate
```

**Endpoints**

- `GET /admin/expenses`

**Captured response snippet**

```json
{
    "data": [
        {
            "id": 5,
            "vehicle": {
                "id": 2,
                "name": "Postman Test Vehicle Updated",
                "slug": "postman-test-vehicle",
                "plate_number": "PM-100-TEST"
            },
            "expense_category": {
                "id": 1,
                "name": "Maintenance",
                "slug": "maintenance"
            },
            "amount": "450.00",
            "expense_date": "2026-06-10T00:00:00.000000Z",
            "description": "Maintenance cost: Postman Garage",
            "has_invoice": false,
            "created_by": {
                "id": 1,
                "name": "Limosud Cars Admin",
                "email": "admin@limosudcars.local"
            },
            "created_at": "2026-06-11T23:55:31.000000Z",
  // ... truncated
```

**Frontend state**

- **Local:** `expenses[]`, pagination.

**UI recommendations**

- **Loading:** skeleton table.
- **Empty:** “No expenses recorded”.

**Error handling**

- Standard auth errors.

**Recommended page structure**

- **Page:** `ExpensesListPage`
- **Components:** `ExpensesTable`, `InvoiceIndicator`, `Pagination`

**Edge cases / business rules**

Show `has_invoice` boolean — `invoice_path` is not exposed in API.

---

### Monthly expense summary

| Item | Details |
|------|---------|
| **User goal** | Show aggregated expenses for a month (dashboard or expenses page). |
| **Preconditions** | `expenses.view`. |
| **Entry point** | Expenses page header or dashboard widget. |
| **Permissions** | expenses.view |

**Flow**

```
User selects year + month
↓
GET /admin/expenses/monthly-summary?year=2026&month=6
↓
Render totals by category
```

**Endpoints**

- `GET /admin/expenses/monthly-summary?year={y}&month={m}`

**Captured response snippet**

```json
{
    "year": "2026",
    "month": "6",
    "total_amount": 1800,
    "expense_count": 5,
    "by_category": [
        {
            "slug": "fuel",
            "name": "Fuel",
            "total_amount": 450,
            "expense_count": 2
        },
        {
            "slug": "maintenance",
            "name": "Maintenance",
            "total_amount": 1350,
            "expense_count": 3
        }
    ]
}
```

**Frontend state**

- **Local:** `monthlySummary`, `selectedYear`, `selectedMonth`.

**UI recommendations**

- **Loading:** summary card skeleton.
- **Empty:** zero totals still render (not an error).

**Error handling**

- `422` on invalid year/month.

**Recommended page structure**

- **Component:** `MonthlyExpenseSummaryCard` on `ExpensesListPage` or dashboard

**Edge cases / business rules**

Useful alongside dashboard expenses endpoint — this is expense-module specific.

---

### Create expense

| Item | Details |
|------|---------|
| **User goal** | Record a cost with optional invoice upload. |
| **Preconditions** | `expenses.create`. |
| **Entry point** | /admin/expenses/new |
| **Permissions** | expenses.create |

**Flow**

```
Open create form
↓
Load lookups (expense categories) + optional vehicles
↓
User fills fields (+ optional invoice file)
↓
POST /admin/expenses (JSON or FormData if invoice)
↓
201 → detail or list
↓
Toast: “Expense created”
```

**Endpoints**

- `GET /admin/lookups`
- `GET /admin/vehicles?page=1` (optional vehicle link)
- `POST /admin/expenses`

**Request bodies**

JSON body (no invoice):
```json
{
  "expense_category_slug": "fuel",
  "amount": 250,
  "expense_date": "2026-06-10",
  "vehicle_id": 2,
  "description": "Fuel refill."
}
```

With invoice: use FormData with same fields + `invoice` file.

**Captured response snippet**

```json
{
    "data": {
        "id": 6,
        "vehicle": {
            "id": 2,
            "name": "Postman Test Vehicle Updated",
            "slug": "postman-test-vehicle",
            "plate_number": "PM-100-TEST"
        },
        "expense_category": {
            "id": 2,
            "name": "Fuel",
            "slug": "fuel"
        },
        "amount": "200.00",
        "expense_date": "2026-06-10T00:00:00.000000Z",
        "description": "Postman fuel expense.",
        "has_invoice": false,
        "created_by": {
            "id": 1,
            "name": "Limosud Cars Admin",
            "email": "admin@limosudcars.local"
        },
        "created_at": "2026-06-11T23:55:35.000000Z",
        "updated_at": "2026-06-11T23:55:35.000000Z"
  // ... truncated
```

**Frontend state**

- **Local:** form state.
- **After success:** redirect using `data.id`.

**UI recommendations**

- **Loading:** disable submit; upload progress if multipart.
- **Success toast:** “Expense created”.

**Error handling**

- `422` on invalid category slug or amount.

**Recommended page structure**

- **Page:** `ExpenseCreatePage`
- **Components:** `ExpenseCategorySelect`, `VehicleSelect` (optional), `InvoiceUpload`

**Edge cases / business rules**

Invoice upload is optional on create. Newman QA uses JSON body; multipart supported when `invoice` present.

---

### View expense details

| Item | Details |
|------|---------|
| **User goal** | Inspect one expense including invoice flag. |
| **Preconditions** | `expenses.view`. |
| **Entry point** | /admin/expenses/{id} |
| **Permissions** | expenses.view |

**Flow**

```
Click row
↓
GET /admin/expenses/{id}
↓
Render detail card
```

**Endpoints**

- `GET /admin/expenses/{id}`

**Captured response snippet**

```json
{
    "data": {
        "id": 6,
        "vehicle": {
            "id": 2,
            "name": "Postman Test Vehicle Updated",
            "slug": "postman-test-vehicle",
            "plate_number": "PM-100-TEST"
        },
        "expense_category": {
            "id": 2,
            "name": "Fuel",
            "slug": "fuel"
        },
        "amount": "200.00",
        "expense_date": "2026-06-10T00:00:00.000000Z",
        "description": "Postman fuel expense.",
        "has_invoice": false,
        "created_by": {
            "id": 1,
            "name": "Limosud Cars Admin",
            "email": "admin@limosudcars.local"
        },
        "created_at": "2026-06-11T23:55:35.000000Z",
        "updated_at": "2026-06-11T23:55:35.000000Z"
  // ... truncated
```

**Frontend state**

- **Local:** `expense` object.

**UI recommendations**

- **Loading:** card skeleton.
- Show `has_invoice` — no direct download URL in API.

**Error handling**

- `404`: back to list.

**Recommended page structure**

- **Page:** `ExpenseDetailPage`

**Edge cases / business rules**

Linked `vehicle` embedded when `vehicle_id` set.

---

### Edit expense

| Item | Details |
|------|---------|
| **User goal** | Update expense fields or replace invoice. |
| **Preconditions** | `expenses.update`; expense loaded. |
| **Entry point** | /admin/expenses/{id}/edit |
| **Permissions** | expenses.update |

**Flow**

```
GET /admin/expenses/{id}
↓
Populate form
↓
PUT or PATCH /admin/expenses/{id}
↓
Refresh detail
↓
Toast: “Expense updated”
```

**Endpoints**

- `GET /admin/expenses/{id}`
- `PUT /admin/expenses/{id}` or `PATCH /admin/expenses/{id}`

**Request bodies**

PATCH changed fields. For invoice replacement use FormData on PUT/PATCH with `invoice` file.

**Captured response snippet**

```json
{
    "data": {
        "id": 6,
        "vehicle": {
            "id": 2,
            "name": "Postman Test Vehicle Updated",
            "slug": "postman-test-vehicle",
            "plate_number": "PM-100-TEST"
        },
        "expense_category": {
            "id": 1,
            "name": "Maintenance",
            "slug": "maintenance"
        },
        "amount": "275.00",
        "expense_date": "2026-06-10T00:00:00.000000Z",
        "description": "Postman expense updated.",
        "has_invoice": false,
        "created_by": {
            "id": 1,
            "name": "Limosud Cars Admin",
            "email": "admin@limosudcars.local"
        },
        "created_at": "2026-06-11T23:55:35.000000Z",
        "updated_at": "2026-06-11T23:55:38.000000Z"
  // ... truncated
```

**Frontend state**

- **Local:** form ↔ API.

**UI recommendations**

- **Success toast:** “Expense updated”.

**Error handling**

- `422` validation errors.

**Recommended page structure**

- **Page:** `ExpenseEditPage`

**Edge cases / business rules**

PUT/PATCH accept optional multipart for invoice replacement.

---

### Delete expense

| Item | Details |
|------|---------|
| **User goal** | Remove an expense record. |
| **Preconditions** | `expenses.delete`. |
| **Entry point** | Expense detail or list row. |
| **Permissions** | expenses.delete |

**Flow**

```
Confirm delete
↓
DELETE /admin/expenses/{id}
↓
204 → list
↓
Toast: “Expense deleted”
```

**Endpoints**

- `DELETE /admin/expenses/{id}`

**Captured response snippet**

```json
HTTP 204 No Content
```

**Frontend state**

- **Local:** remove from list.

**UI recommendations**

- **Confirmation:** delete dialog.
- **Success toast:** “Expense deleted”.

**Error handling**

- `404` if already deleted.

**Recommended page structure**

- **Component:** `DeleteExpenseButton`

**Edge cases / business rules**

Soft delete — hidden from lists.

---

### Vehicle expenses tab

| Item | Details |
|------|---------|
| **User goal** | Show expenses linked to one vehicle. |
| **Preconditions** | `expenses.view`; vehicle detail open. |
| **Entry point** | Vehicle detail → Expenses tab. |
| **Permissions** | expenses.view |

**Flow**

```
Open Expenses tab
↓
GET /admin/vehicles/{vehicleId}/expenses
↓
Render expense list
```

**Endpoints**

- `GET /admin/vehicles/{vehicle}/expenses`

**Captured response snippet**

```json
{
    "data": [
        {
            "id": 3,
            "vehicle": {
                "id": 2,
                "name": "Postman Test Vehicle Updated",
                "slug": "postman-test-vehicle",
                "plate_number": "PM-100-TEST"
            },
            "expense_category": {
                "id": 1,
                "name": "Maintenance",
                "slug": "maintenance"
            },
            "amount": "450.00",
            "expense_date": "2026-06-10T00:00:00.000000Z",
            "description": "Maintenance cost: Postman Garage",
            "has_invoice": false,
            "created_by": {
                "id": 1,
                "name": "Limosud Cars Admin",
                "email": "admin@limosudcars.local"
            },
            "created_at": "2026-06-11T23:19:46.000000Z",
  // ... truncated
```

**Frontend state**

- **Local:** `vehicleExpenses[]`.
- **Lazy-load** on tab open.

**UI recommendations**

- **Loading:** tab skeleton.
- **Empty:** “No expenses for this vehicle”.

**Error handling**

- Standard errors.

**Recommended page structure**

- **Component:** `VehicleExpensesTab` on `VehicleDetailPage`

**Edge cases / business rules**

Same expense resource shape as main list, filtered by vehicle.

---

## Alerts / notifications

> API uses `seen`, `done`, and `ignore` — not “read” or “dismiss”. Map UI labels accordingly.

### List alerts

| Item | Details |
|------|---------|
| **User goal** | Browse operational alerts (documents expiring, maintenance due, etc.). |
| **Preconditions** | `alerts.view`. |
| **Entry point** | /admin/alerts |
| **Permissions** | alerts.view |

**Flow**

```
Open Alerts page
↓
GET /admin/alerts?page=1
↓
Render table (type, message, status, dates)
↓
Paginate
```

**Endpoints**

- `GET /admin/alerts`

**Captured response snippet**

```json
{
    "data": [
        {
            "id": 4,
            "vehicle": {
                "id": 2,
                "name": "Postman Test Vehicle Updated",
                "slug": "postman-test-vehicle",
                "plate_number": "PM-100-TEST"
            },
            "alert_type": {
                "id": 1,
                "name": "Maintenance Due",
                "slug": "maintenance_due"
            },
            "alert_status": {
                "id": 1,
                "name": "Pending",
                "slug": "pending"
            },
            "title": "Postman Maintenance Alert 1781219738",
            "message": "Postman alert message.",
            "due_date": "2026-12-01T00:00:00.000000Z",
            "created_at": "2026-06-11T23:17:05.000000Z",
            "updated_at": "2026-06-11T23:17:05.000000Z"
  // ... truncated
```

**Frontend state**

- **Local:** `alerts[]`, pagination.

**UI recommendations**

- **Loading:** skeleton rows.
- **Empty:** “No alerts”.

**Error handling**

- Filter client-side by `alert_status.slug` on current page.

**Recommended page structure**

- **Page:** `AlertsListPage`
- **Components:** `AlertsTable`, `AlertStatusBadge`, `Pagination`

**Edge cases / business rules**

Alert types and statuses come from nested lookup objects in each row.

---

### Pending alerts badge

| Item | Details |
|------|---------|
| **User goal** | Show count of pending alerts in nav or dashboard. |
| **Preconditions** | `alerts.view`. |
| **Entry point** | App shell header / dashboard. |
| **Permissions** | alerts.view |

**Flow**

```
App shell mount (or poll interval)
↓
GET /admin/alerts/pending
↓
Show badge count from response length or meta
```

**Endpoints**

- `GET /admin/alerts/pending`

**Captured response snippet**

```json
{
    "data": [
        {
            "id": 1,
            "vehicle": {
                "id": 2,
                "name": "Postman Test Vehicle Updated",
                "slug": "postman-test-vehicle",
                "plate_number": "PM-100-TEST"
            },
            "alert_type": {
                "id": 1,
                "name": "Maintenance Due",
                "slug": "maintenance_due"
            },
            "alert_status": {
                "id": 1,
                "name": "Pending",
                "slug": "pending"
            },
            "title": "Postman Maintenance Alert",
            "message": "Postman alert message.",
            "due_date": "2026-07-11T00:00:00.000000Z",
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:54:32.000000Z"
  // ... truncated
```

**Frontend state**

- **Global:** `pendingAlertsCount` in app shell state.
- **Refresh** on interval or after alert action.

**UI recommendations**

- **Loading:** small spinner on badge area only.
- **Empty:** hide badge when count is 0.

**Error handling**

- Standard auth errors.

**Recommended page structure**

- **Component:** `PendingAlertsBadge` in `AppHeader`

**Edge cases / business rules**

Use for notification bell — lighter than full alerts list.

---

### Mark alert as seen

| Item | Details |
|------|---------|
| **User goal** | Acknowledge alert without closing it (maps to “read” in UI). |
| **Preconditions** | `alerts.update`; alert `alert_status.slug` is `pending`. |
| **Entry point** | Alert row or detail. |
| **Permissions** | alerts.update |

**Flow**

```
User opens alert or clicks Mark seen
↓
PATCH /admin/alerts/{id}/seen
↓
alert_status.slug → seen
↓
Decrement pending badge
↓
Toast: “Alert marked as seen”
```

**Endpoints**

- `PATCH /admin/alerts/{id}/seen`

**Request bodies**

No request body.

**Captured response snippet**

```json
{
    "data": {
        "id": 1,
        "vehicle": {
            "id": 2,
            "name": "Postman Test Vehicle Updated",
            "slug": "postman-test-vehicle",
            "plate_number": "PM-100-TEST"
        },
        "alert_type": {
            "id": 1,
            "name": "Maintenance Due",
            "slug": "maintenance_due"
        },
        "alert_status": {
            "id": 2,
            "name": "Seen",
            "slug": "seen"
        },
        "title": "Postman Maintenance Alert",
        "message": "Postman alert message.",
        "due_date": "2026-07-11T00:00:00.000000Z",
        "created_at": "2026-06-11T23:05:51.000000Z",
        "updated_at": "2026-06-11T23:55:43.000000Z"
    }
  // ... truncated
```

**Frontend state**

- **Local:** update alert status in list.
- **Global:** decrement `pendingAlertsCount`.

**UI recommendations**

- **Success toast:** “Alert marked as seen”.

**Error handling**

- `422` if not currently `pending`.

**Recommended page structure**

- **Component:** `MarkAlertSeenButton` on alert row

**Edge cases / business rules**

Only valid transition from `pending` → `seen`.

---

### Mark alert as done

| Item | Details |
|------|---------|
| **User goal** | Resolve alert as completed. |
| **Preconditions** | `alerts.update`; status is `pending` or `seen`. |
| **Entry point** | Alert row actions. |
| **Permissions** | alerts.update |

**Flow**

```
User clicks Mark done
↓
PATCH /admin/alerts/{id}/done
↓
alert_status.slug → done
↓
Toast: “Alert resolved”
```

**Endpoints**

- `PATCH /admin/alerts/{id}/done`

**Request bodies**

No request body.

**Captured response snippet**

```json
{
    "data": {
        "id": 2,
        "vehicle": {
            "id": 2,
            "name": "Postman Test Vehicle Updated",
            "slug": "postman-test-vehicle",
            "plate_number": "PM-100-TEST"
        },
        "alert_type": {
            "id": 1,
            "name": "Maintenance Due",
            "slug": "maintenance_due"
        },
        "alert_status": {
            "id": 3,
            "name": "Done",
            "slug": "done"
        },
        "title": "QA Alert Done Target",
        "message": "Alert for done transition.",
        "due_date": "2026-08-11T00:00:00.000000Z",
        "created_at": "2026-06-11T23:05:51.000000Z",
        "updated_at": "2026-06-11T23:55:43.000000Z"
    }
  // ... truncated
```

**Frontend state**

- **Local:** update or remove from pending list.

**UI recommendations**

- **Success toast:** “Alert resolved”.

**Error handling**

- `422` from invalid status.

**Recommended page structure**

- **Component:** `MarkAlertDoneButton`

**Edge cases / business rules**

Valid from `pending` or `seen`.

---

### Ignore alert (dismiss)

| Item | Details |
|------|---------|
| **User goal** | Dismiss alert without action (maps to “dismiss” in UI). |
| **Preconditions** | `alerts.update`; status is `pending` or `seen`. |
| **Entry point** | Alert row actions. |
| **Permissions** | alerts.update |

**Flow**

```
User clicks Ignore
↓
PATCH /admin/alerts/{id}/ignore
↓
alert_status.slug → ignored
↓
Toast: “Alert dismissed”
```

**Endpoints**

- `PATCH /admin/alerts/{id}/ignore`

**Request bodies**

No request body.

**Captured response snippet**

```json
{
    "data": {
        "id": 3,
        "vehicle": {
            "id": 2,
            "name": "Postman Test Vehicle Updated",
            "slug": "postman-test-vehicle",
            "plate_number": "PM-100-TEST"
        },
        "alert_type": {
            "id": 1,
            "name": "Maintenance Due",
            "slug": "maintenance_due"
        },
        "alert_status": {
            "id": 4,
            "name": "Ignored",
            "slug": "ignored"
        },
        "title": "QA Alert Ignore Target",
        "message": "Alert for ignore transition.",
        "due_date": "2026-09-11T00:00:00.000000Z",
        "created_at": "2026-06-11T23:05:51.000000Z",
        "updated_at": "2026-06-11T23:55:44.000000Z"
    }
  // ... truncated
```

**Frontend state**

- **Local:** update status; remove from active filters.

**UI recommendations**

- **Success toast:** “Alert dismissed”.

**Error handling**

- `422` from invalid status.

**Recommended page structure**

- **Component:** `IgnoreAlertButton`

**Edge cases / business rules**

Valid from `pending` or `seen`. Terminal for workflow purposes.

---

### Generate system alerts

| Item | Details |
|------|---------|
| **User goal** | Trigger server-side alert generation (admin action). |
| **Preconditions** | `alerts.create`. |
| **Entry point** | Alerts page admin action. |
| **Permissions** | alerts.create |

**Flow**

```
User clicks Generate alerts
↓
POST /admin/alerts/generate
↓
Show count of alerts created
↓
Refresh alerts list + pending badge
↓
Toast: “{n} alerts generated” or “No new alerts”
```

**Endpoints**

- `POST /admin/alerts/generate`

**Request bodies**

No request body.

**Captured response snippet**

```json
{
    "maintenance_alerts_created": 0,
    "document_expiry_alerts_created": 0,
    "total_created": 0
}
```

**Frontend state**

- **Local:** refresh lists after response.

**UI recommendations**

- **Success toast:** show `total_created` from response.
- **Empty result:** “No new alerts” (duplicate pending may yield 0).

**Error handling**

- Repeat calls may return `total_created: 0` if duplicates exist.

**Recommended page structure**

- **Component:** `GenerateAlertsButton` on `AlertsListPage`

**Edge cases / business rules**

Server evaluates business rules (expiring documents, upcoming maintenance, etc.).

---

## Locations

### List locations

| Item | Details |
|------|---------|
| **User goal** | Browse pickup/dropoff locations for reservations. |
| **Preconditions** | `locations.view`. |
| **Entry point** | /admin/locations |
| **Permissions** | locations.view |

**Flow**

```
Open Locations page
↓
GET /admin/locations?page=1
↓
Render table (name, type, address, delivery_fee, active)
↓
Paginate
```

**Endpoints**

- `GET /admin/locations`

**Captured response snippet**

```json
{
    "data": [
        {
            "id": 1,
            "name": "Dakhla Agency",
            "slug": "dakhla-agency",
            "address": "Dakhla Center, Morocco",
            "delivery_fee": "0.00",
            "is_active": true,
            "location_type": {
                "id": 1,
                "name": "Agency",
                "slug": "agency"
            },
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        },
        {
            "id": 2,
            "name": "Dakhla Airport",
            "slug": "dakhla-airport",
            "address": "Dakhla Airport, Morocco",
            "delivery_fee": "150.00",
            "is_active": true,
            "location_type": {
  // ... truncated
```

**Frontend state**

- **Local:** `locations[]`, pagination.
- **Global:** cache active locations for reservation forms.

**UI recommendations**

- **Loading:** skeleton table.
- **Empty:** “No locations” + Create CTA.

**Error handling**

- Admin list includes **inactive** locations (unlike public API).

**Recommended page structure**

- **Page:** `LocationsListPage`
- **Components:** `LocationsTable`, `ActiveBadge`, `Pagination`

**Edge cases / business rules**

Used in reservation create/edit for `pickup_location_id` and `dropoff_location_id`.

---

### Create location

| Item | Details |
|------|---------|
| **User goal** | Add a pickup/dropoff point. |
| **Preconditions** | `locations.create`; lookups loaded. |
| **Entry point** | /admin/locations/new |
| **Permissions** | locations.create |

**Flow**

```
Open create form
↓
Load GET /admin/lookups (location types)
↓
User fills form
↓
POST /admin/locations
↓
201 → detail or list
↓
Toast: “Location created”
```

**Endpoints**

- `GET /admin/lookups`
- `POST /admin/locations`

**Request bodies**

```json
{
  "location_type_slug": "agency",
  "name": "Postman Location",
  "slug": "postman-location",
  "address": "Postman Street, Dakhla",
  "delivery_fee": 0,
  "is_active": true
}
```

**Captured response snippet**

```json
{
    "data": {
        "id": 6,
        "name": "QA Disposable Location 1781222073",
        "slug": "qa-disposable-location-1781222073",
        "address": "QA Street, Dakhla",
        "delivery_fee": "50.00",
        "is_active": true,
        "location_type": {
            "id": 1,
            "name": "Agency",
            "slug": "agency"
        },
        "created_at": "2026-06-11T23:55:09.000000Z",
        "updated_at": "2026-06-11T23:55:09.000000Z"
    }
}
```

**Frontend state**

- **Local:** form state.
- **After success:** redirect using `data.id`.

**UI recommendations**

- **Loading:** disable submit.
- **Success toast:** “Location created”.

**Error handling**

- `422` on duplicate slug or invalid `location_type_slug`.

**Recommended page structure**

- **Page:** `LocationCreatePage`
- **Components:** `LocationTypeSelect`, `SlugInput`

**Edge cases / business rules**

Inactive locations are hidden from `GET /public/locations` but visible in admin.

---

### View location details

| Item | Details |
|------|---------|
| **User goal** | Inspect one location. |
| **Preconditions** | `locations.view`. |
| **Entry point** | /admin/locations/{id} |
| **Permissions** | locations.view |

**Flow**

```
Click row
↓
GET /admin/locations/{id}
↓
Render detail card
```

**Endpoints**

- `GET /admin/locations/{id}`

**Captured response snippet**

```json
{
    "data": {
        "id": 6,
        "name": "QA Disposable Location 1781222073",
        "slug": "qa-disposable-location-1781222073",
        "address": "QA Street, Dakhla",
        "delivery_fee": "50.00",
        "is_active": true,
        "location_type": {
            "id": 1,
            "name": "Agency",
            "slug": "agency"
        },
        "created_at": "2026-06-11T23:55:09.000000Z",
        "updated_at": "2026-06-11T23:55:09.000000Z"
    }
}
```

**Frontend state**

- **Local:** `location` object.

**UI recommendations**

- **Loading:** card skeleton.

**Error handling**

- `404`: back to list.

**Recommended page structure**

- **Page:** `LocationDetailPage`

**Edge cases / business rules**

Soft-deleted locations return 404.

---

### Update location

| Item | Details |
|------|---------|
| **User goal** | Edit location fields (full or partial). |
| **Preconditions** | `locations.update`; location loaded. |
| **Entry point** | /admin/locations/{id}/edit |
| **Permissions** | locations.update |

**Flow**

```
GET /admin/locations/{id}
↓
Populate form
↓
PUT or PATCH /admin/locations/{id}
↓
Refresh detail
↓
Toast: “Location updated”
```

**Endpoints**

- `GET /admin/locations/{id}`
- `PUT /admin/locations/{id}` or `PATCH /admin/locations/{id}`

**Request bodies**

PATCH example: `{ "delivery_fee": 150, "is_active": false }`

**Captured response snippet**

```json
{
    "data": {
        "id": 6,
        "name": "QA Disposable Location Updated",
        "slug": "qa-disposable-location-1781222073",
        "address": "Updated QA Street, Dakhla",
        "delivery_fee": "80.00",
        "is_active": false,
        "location_type": {
            "id": 1,
            "name": "Agency",
            "slug": "agency"
        },
        "created_at": "2026-06-11T23:55:09.000000Z",
        "updated_at": "2026-06-11T23:55:11.000000Z"
    }
}
```

**Frontend state**

- **Local:** form ↔ API.
- **Invalidate** cached location lists if used in forms.

**UI recommendations**

- **Success toast:** “Location updated”.

**Error handling**

- `422` validation errors.

**Recommended page structure**

- **Page:** `LocationEditPage`

**Edge cases / business rules**

Deactivating (`is_active: false`) removes from public website but keeps in admin.

---

### Delete location

| Item | Details |
|------|---------|
| **User goal** | Remove location (soft delete). |
| **Preconditions** | `locations.delete`. |
| **Entry point** | Location detail or list row. |
| **Permissions** | locations.delete |

**Flow**

```
Confirm delete
↓
DELETE /admin/locations/{id}
↓
204 → list
↓
Toast: “Location deleted”
```

**Endpoints**

- `DELETE /admin/locations/{id}`

**Captured response snippet**

```json
HTTP 204 No Content
```

**Frontend state**

- **Local:** remove from list.
- **Invalidate** reservation form caches.

**UI recommendations**

- **Confirmation:** warn if used in upcoming reservations.
- **Success toast:** “Location deleted”.

**Error handling**

- May fail if referenced by active reservations.

**Recommended page structure**

- **Component:** `DeleteLocationButton`

**Edge cases / business rules**

Soft delete — 404 on subsequent GET.

---

## Files & uploads

### FormData workflow (all uploads)
```
Build FormData
↓
append text fields + File blobs
↓
fetch/axios with Authorization bearer
↓
Do NOT set Content-Type manually
↓
Parse JSON response
```

**Upload endpoints that exist today:**
- `POST /admin/customers/{customer}/documents`
- `POST /admin/expenses` (optional `invoice`)
- `PUT`/`PATCH /admin/expenses/{id}` (replace invoice)
- `POST /admin/contracts/{contract}/signed` (optional `signed_pdf`)

### Upload progress
Use `axios.onUploadProgress` or `fetch` + `ReadableStream` tracking; show percent bar on modal.

### File preview
- Customer documents: show icon by mime; open in new tab only if you have a public URL strategy.
- Contracts: use download endpoint blob for PDF preview.

### Download workflow
```
GET /admin/contracts/{id}/download
↓
const blob = await response.blob()
↓
const url = URL.createObjectURL(blob)
↓
trigger browser download / embed viewer
```

---

## Public website (customer-facing)

### Browse and book flow
```
Landing
↓
GET /public/lookups + GET /public/locations + GET /public/vehicles
↓
Vehicle detail: GET /public/vehicles/{slug}
↓
Availability: GET /public/vehicles/{id}/availability?start_datetime=&end_datetime=
↓
Submit: POST /public/reservations
```

**No admin token.** Captured endpoints `2`â€“`8`.  
**Edge:** public reservation creates `pending` website booking.

---

## Frontend Development Roadmap

Build in phases to reduce integration risk.

### Phase 1 — Foundation (complexity: **Medium**)
| Feature | Why first |
|---------|-----------|
| Authentication (login, session restore, logout) | Gates entire admin app |
| App shell + permission-based navigation | Uses `/admin/auth/me` |
| Dashboard KPIs | Validates charts + date filters |
| Vehicles CRUD | Core entity; exercises lookups, pagination, PATCH |

### Phase 2 — Operations (complexity: **High**)
| Feature | Why second |
|---------|------------|
| Customers + document upload | Needed for reservations |
| Locations CRUD | Needed for pickup/dropoff |
| Reservations list/detail/create/edit | Central business workflow |
| Reservation lifecycle actions | Multiple stateful POST endpoints |

### Phase 3 — Finance & legal (complexity: **High**)
| Feature | Why third |
|---------|-----------|
| Payments list/register/update/cancel | Tied to reservation balances |
| Contract generate/download/sign | PDF + multipart |

### Phase 4 — Fleet support (complexity: **Medium**)
| Feature | Why fourth |
|---------|------------|
| Maintenance + upcoming widget | Vehicle ops |
| Expenses + monthly summary | Cost tracking |
| Alerts (pending, seen, done, ignore) | Operational notifications |

### Not available yet (do not build UI)
| Module | Status |
|--------|--------|
| Site pages | Permissions exist; **no API routes** |
| Audit logs | `audit_logs.view` only; **no API routes** |
| Vehicle photo/document upload | Read-only on vehicle detail |

### Complexity legend
- **Low:** single GET, no writes (e.g., health check page)
- **Medium:** CRUD with JSON + pagination
- **High:** multi-step flows, file uploads, state machines, financial recalculation

---

## Quick reference: endpoint count by module

| Module | Admin + public endpoints |
|--------|--------------------------|
| Health | 1 |
| Public website | 7 |
| Auth | 3 |
| Lookups | 2 |
| Dashboard | 3 |
| Brands | 6 |
| Categories | 6 |
| Vehicles | 8 |
| Customers | 8 |
| Locations | 6 |
| Reservations | 13 |
| Payments | 7 |
| Contracts | 5 |
| Maintenance | 7 |
| Expenses | 7 |
| Alerts | 8 |
| **Total** | **96** |

For request/response field-level detail, see `FRONTEND_API_INTEGRATION_GUIDE.md`.

