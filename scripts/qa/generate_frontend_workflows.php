<?php

declare(strict_types=1);

$backendRoot = dirname(__DIR__, 2);
$outputPath = $backendRoot.'/FRONTEND_WORKFLOWS.md';
$capturedPath = $backendRoot.'/storage/qa/reports/captured-responses.json';

$captured = file_exists($capturedPath)
    ? json_decode(file_get_contents($capturedPath), true, 512, JSON_THROW_ON_ERROR)
    : [];

$snippet = static function (array $captured, int|string $key, int $maxLines = 40): string {
    if (! isset($captured[(string) $key]['body'])) {
        return '_No captured response available._';
    }

    $json = json_encode($captured[(string) $key]['body'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    $lines = explode("\n", $json);
    if (count($lines) > $maxLines) {
        $lines = array_slice($lines, 0, $maxLines);
        $lines[] = '  // ... truncated';
    }

    return implode("\n", $lines);
};

$workflow = static function (
    string $title,
    string $goal,
    string $preconditions,
    string $entry,
    string $flow,
    string $endpoints,
    string $bodies,
    string $response,
    string $state,
    string $ui,
    string $errors,
    string $edges,
    string $permissions,
    string $components = ''
) use (&$lines): void {
    $normalize = static fn (string $text): string => str_replace('\\n', "\n", $text);

    $lines[] = '### '.$title;
    $lines[] = '';
    $lines[] = '| Item | Details |';
    $lines[] = '|------|---------|';
    $lines[] = '| **User goal** | '.$goal.' |';
    $lines[] = '| **Preconditions** | '.$preconditions.' |';
    $lines[] = '| **Entry point** | '.$entry.' |';
    $lines[] = '| **Permissions** | '.$permissions.' |';
    $lines[] = '';
    $lines[] = '**Flow**';
    $lines[] = '';
    $lines[] = '```';
    $lines[] = trim($flow);
    $lines[] = '```';
    $lines[] = '';
    $lines[] = '**Endpoints**';
    $lines[] = '';
    foreach (explode("\n", trim($normalize($endpoints))) as $line) {
        $lines[] = $line;
    }
    $lines[] = '';
    if ($bodies !== '') {
        $lines[] = '**Request bodies**';
        $lines[] = '';
        foreach (explode("\n", trim($normalize($bodies))) as $line) {
            $lines[] = $line;
        }
        $lines[] = '';
    }
    if ($response !== '') {
        $lines[] = '**Captured response snippet**';
        $lines[] = '';
        $lines[] = '```json';
        $lines[] = $response;
        $lines[] = '```';
        $lines[] = '';
    }
    $lines[] = '**Frontend state**';
    $lines[] = '';
    foreach (explode("\n", trim($normalize($state))) as $line) {
        $lines[] = $line;
    }
    $lines[] = '';
    $lines[] = '**UI recommendations**';
    $lines[] = '';
    foreach (explode("\n", trim($normalize($ui))) as $line) {
        $lines[] = $line;
    }
    $lines[] = '';
    $lines[] = '**Error handling**';
    $lines[] = '';
    foreach (explode("\n", trim($normalize($errors))) as $line) {
        $lines[] = $line;
    }
    $lines[] = '';
    if ($components !== '') {
        $lines[] = '**Recommended page structure**';
        $lines[] = '';
        foreach (explode("\n", trim($normalize($components))) as $line) {
            $lines[] = $line;
        }
        $lines[] = '';
    }
    if ($edges !== '') {
        $lines[] = '**Edge cases / business rules**';
        $lines[] = '';
        foreach (explode("\n", trim($normalize($edges))) as $line) {
            $lines[] = $line;
        }
        $lines[] = '';
    }
    $lines[] = '---';
    $lines[] = '';
};

$lines = [];
$lines[] = '# Limosud Cars — Frontend Workflows';
$lines[] = '';
$lines[] = '> **Purpose:** Page-by-page user journeys for frontend developers.';
$lines[] = '> **Not** an API reference (`FRONTEND_API_INTEGRATION_GUIDE.md`) and **not** a QA document (`POSTMAN_TEST_WORKFLOW.txt`).';
$lines[] = '> **Sources:** Laravel `routes/api.php`, controllers/resources, Newman captured responses.';
$lines[] = '> **Generated:** '.date('Y-m-d H:i:s');
$lines[] = '';
$lines[] = '## How to read this document';
$lines[] = '';
$lines[] = 'Each workflow explains **what the user does**, **which endpoints to call in order**, and **how the UI should react**.';
$lines[] = '';
$lines[] = '### Conventions';
$lines[] = '';
$lines[] = '- **Base URL:** `http://127.0.0.1:8000/api` (use env variable in the app).';
$lines[] = '- **Admin auth:** Sanctum bearer token from `POST /admin/auth/login`.';
$lines[] = '- **Permissions:** Gate menus and buttons using `user.permissions[].slug` from `GET /admin/auth/me`.';
$lines[] = '- **Lookups:** Load once via `GET /admin/lookups` and cache in global state for form selects.';
$lines[] = '- **Pagination:** List endpoints support `?page=` only. There is **no server-side search/filter/sort** today — implement those client-side on the current page or fetch additional pages.';
$lines[] = '- **Money fields:** Display as currency; API returns decimal strings like `"375.00"`.';
$lines[] = '- **Soft deletes:** `DELETE` returns `204` and hides records from lists.';
$lines[] = '';
$lines[] = '### Standard error handling (all workflows)';
$lines[] = '';
$lines[] = '| Status | UI behavior |';
$lines[] = '|--------|-------------|';
$lines[] = '| `401` | Clear token, redirect to login |';
$lines[] = '| `403` | Toast + hide/disable action; optional â€œno permissionâ€ page |';
$lines[] = '| `404` | Not-found empty state or redirect back to list |';
$lines[] = '| `422` | Inline field errors from `errors` object |';
$lines[] = '| `500` | Generic error banner with retry |';
$lines[] = '| Network offline | Offline banner; retry when connection returns |';
$lines[] = '';
$lines[] = '---';
$lines[] = '';
$lines[] = '## Global frontend flows';
$lines[] = '';

$workflow(
    'Pagination',
    'Navigate large admin lists.',
    'User is authenticated; list permission granted.',
    'Any paginated table (vehicles, customers, reservations, etc.).',
    "Open list page\n↓\nGET /admin/{resource}?page=1\n↓\nRender rows + pagination controls from meta/links\n↓\nUser clicks page 2\n↓\nGET /admin/{resource}?page=2\n↓\nReplace table rows",
    '- `GET /admin/vehicles?page={n}` (and equivalent list routes)',
    '',
    $snippet($captured, 28, 25),
    '- **Local:** `currentPage`, `lastPage`, `items[]` for current page.\n- **Global:** none.\n- **Discard:** previous page rows when page changes.',
    '- Skeleton rows while loading.\n- Disable pagination buttons during fetch.\n- Show â€œPage X of Yâ€ from `meta`.',
    '- `401`/`403` as standard.\n- Empty `data[]`: show empty state, not an error.',
    '- `per_page` is 15 on captured list responses.\n- Use `links.next` / `meta.current_page` for navigation.',
    'Resource-specific `*.view` permission'
);

$workflow(
    'Search (client-side)',
    'Find a record in the current dataset.',
    'List data loaded (one or more pages).',
    'Search box on list pages.',
    "User types query\n↓\nFilter rendered rows locally (name, plate, phone, reservation_number)\n↓\nOptional: fetch more pages if product requires full-database search",
    '- Initial load: `GET /admin/{resource}?page=1`',
    '',
    '',
    '- **Local:** `searchQuery`, `filteredItems`.\n- **Global:** none.',
    '- Debounce input 300ms.\n- Show â€œNo matchesâ€ when filter returns zero rows.\n- Clear button in search field.',
    '- No dedicated search API exists today.',
    '**Important:** Backend does not expose `?search=` query params. Full search requires either client-side filtering of fetched pages or a future API enhancement.',
    'N/A'
);

$workflow(
    'Filtering (client-side)',
    'Narrow a list by status, brand, date, etc.',
    'List loaded; lookup labels available.',
    'Filter chips/dropdowns on list or calendar views.',
    "User selects filter\n↓\nApply filter to in-memory collection OR reload calendar with date query\n↓\nRe-render list",
    "- Lists: `GET /admin/{resource}`\n- Calendar exception: `GET /admin/reservations-calendar?start=YYYY-MM-DD&end=YYYY-MM-DD`",
    'Calendar filters only:',
    $snippet($captured, 53, 20),
    '- **Local:** `activeFilters`, filtered collection.\n- **Calendar:** `start`, `end` query state.',
    '- Show active filter chips.\n- â€œClear filtersâ€ resets UI.',
    '- Invalid date range → validate before calling calendar endpoint.',
    'Reservation calendar is the only list-like endpoint with meaningful server date filtering today.',
    'reservations.view'
);

$workflow(
    'Sorting (client-side)',
    'Reorder rows for display.',
    'List data in memory.',
    'Column header click.',
    "User clicks sortable column\n↓\nSort local array by field\n↓\nRe-render table",
    '- Data from paginated GET list endpoints',
    '',
    '',
    '- **Local:** `sortField`, `sortDirection`.\n- **Global:** none.',
    '- Show sort indicator on column.\n- Default sort matches API (`latest()` — newest first).',
    '- Sorting only affects currently loaded page unless you fetch all pages.',
    'Server does not accept `?sort=` parameters.',
    'N/A'
);

$workflow(
    'Refresh',
    'Reload data after an action or manual refresh.',
    'User on any data screen.',
    'Refresh button or pull-to-refresh.',
    "User triggers refresh\n↓\nRe-call current page endpoint(s)\n↓\nReplace stale state\n↓\nToast optional: 'Data updated'",
    '- Same endpoints as the screenâ€™s initial load',
    '',
    '',
    '- **Local:** replace list/detail slice.\n- **Global:** refresh `auth/me` only on app focus if needed.',
    '- Inline loading indicator; keep old data visible until success (stale-while-revalidate).',
    '- On failure, keep stale data and show retry toast.',
    '',
    'N/A'
);

$workflow(
    'Optimistic updates',
    'Make UI feel instant for low-risk toggles.',
    'PATCH/POST action with predictable outcome.',
    'Toggle switches, status chips.',
    "User toggles value\n↓\nUpdate UI immediately\n↓\nPATCH /admin/{resource}/{id}\n↓\nOn success: keep UI\n↓\nOn failure: rollback UI + show error",
    '- Example: `PATCH /admin/vehicles/{id}` with `{ is_featured: true }`',
    '`{ "is_featured": false }` (example partial body)',
    $snippet($captured, 32, 25),
    '- **Local:** optimistic snapshot for rollback.\n- **Global:** none.',
    '- Subtle saving indicator on row.\n- Rollback animation on error.',
    '- `422`: rollback and show field errors.\n- `403`: rollback and permission toast.',
    'Use only for reversible fields. Do **not** optimistically confirm reservations or register payments.',
    'Resource `*.update` permission'
);

$workflow(
    'Validation error (422)',
    'Guide user to fix form input.',
    'Form submit attempted.',
    'Any create/update form.',
    "Submit form\n↓\nPOST/PUT/PATCH\n↓\n422 response\n↓\nMap errors[field] to inputs\n↓\nFocus first invalid field",
    '- Any write endpoint',
    '',
    '{"message":"The given data was invalid.","errors":{"plate_number":["The plate number has already been taken."]}}',
    '- **Local:** `fieldErrors` map.\n- **Global:** none.',
    '- Red borders + helper text per field.\n- Summary alert at top for long forms.',
    '- Do not clear unrelated valid fields.',
    'Duplicate slug/plate_number is common on vehicles and locations.',
    'Matching create/update permission'
);

$workflow(
    'Generic server error (500)',
    'Recover from unexpected backend failure.',
    'Any API call.',
    'Global error boundary.',
    "API returns 500\n↓\nShow non-technical message\n↓\nOffer Retry button\n↓\nRetry repeats last request",
    '- Any',
    '',
    '{"message":"Server Error"}',
    '- **Local:** `lastFailedRequest` for retry.\n- **Global:** optional error telemetry.',
    '- Full-page or section error state.\n- Avoid infinite retry loops.',
    '- Log correlation id if backend adds one later.',
    '',
    'N/A'
);

$workflow(
    'Network error',
    'Handle offline or timeout.',
    'Fetch in flight.',
    'Any screen.',
    "fetch throws / timeout\n↓\nDetect navigator.onLine\n↓\nShow offline banner\n↓\nQueue retry when online",
    '- Any',
    '',
    '',
    '- **Local:** `isOffline`, pending retries.\n- **Global:** connection status.',
    '- Banner: â€œNo internet connectionâ€.\n- Disable submit buttons offline.',
    '- Distinguish timeout vs DNS failure in logs only; same UI for user.',
    '',
    'N/A'
);

$workflow(
    'Token expiration (401)',
    'Return user to login safely.',
    'Stored token invalid/expired/revoked.',
    'Any protected call.',
    "API returns 401\n↓\nClear admin_token + user cache\n↓\nRedirect to /login\n↓\nPreserve intended route in query ?next=",
    '- Typically discovered on `GET /admin/auth/me` or any admin route',
    '',
    '{"message":"Unauthenticated."}',
    '- **Global:** clear `auth` store.\n- **Local:** discard protected screen state.',
    '- Toast: â€œSession expired, please sign in again.â€',
    '- Do not loop login → me → 401.',
    'Logout (`POST /admin/auth/logout`) invalidates the current token.',
    'N/A'
);

$lines[] = '## Authentication';
$lines[] = '';

$workflow(
    'Login',
    'Sign in to the admin dashboard.',
    'User is logged out.',
    '/login',
    "User submits email/password\n↓\nPOST /admin/auth/login\n↓\nStore access_token\n↓\nGET /admin/auth/me\n↓\nStore user + permissions\n↓\nRedirect to dashboard",
    "- `POST /admin/auth/login`\n- `GET /admin/auth/me`",
    "Login body:\n```json\n{\"email\":\"admin@limosudcars.local\",\"password\":\"***\"}\n```",
    $snippet($captured, 9, 30),
    "- **Global:** `token`, `user`, `permissions[]`.\n- **Local:** clear login form.\n- **Discard:** password from memory after submit.",
    "- Full-screen loader on submit.\n- Success: redirect without toast (optional).\n- Invalid credentials: inline form error.",
    "- `422`: invalid email/password format.\n- Inactive user: validation/unauthorized per backend.",
    'Token is Sanctum personal access token (`token_type: Bearer`).',
    'None (public login route)'
);

$workflow(
    'Restore session',
    'Stay signed in after page reload.',
    'Token may exist in storage.',
    'App bootstrap / protected route guard.',
    "App starts\n↓\nRead admin_token\n↓\nIf missing → /login\n↓\nGET /admin/auth/me\n↓\nIf 200 → enter app\n↓\nIf 401 → clear token → /login",
    '- `GET /admin/auth/me`',
    '',
    $snippet($captured, 10, 25),
    '- **Global:** hydrate auth store from `/me`.\n- **Local:** route loading flags.',
    '- App shell skeleton until `/me` completes.\n- Avoid flashing login page when token is valid.',
    '- `401`: run token expiration workflow.',
    'Call `/me` once per session boot, not on every child route.',
    'Authenticated admin'
);

$workflow(
    'Logout',
    'End the admin session.',
    'User is signed in.',
    'Header user menu → Logout.',
    "User confirms logout\n↓\nPOST /admin/auth/logout\n↓\nClear token + user state\n↓\nRedirect to /login",
    '- `POST /admin/auth/logout`',
    '',
    $snippet($captured, 11, 10),
    '- **Global:** wipe auth + cached lookups optional.\n- **Local:** reset all protected routes.',
    '- Confirm dialog optional for shared terminals.\n- Success toast: â€œSigned outâ€.',
    '- If logout fails (network), still clear local token to avoid stuck state.',
    'Token is revoked server-side; old token must not be reused.',
    'Authenticated admin'
);

$workflow(
    'Unauthorized (401) during navigation',
    'Handle expired session mid-use.',
    'Token became invalid.',
    'Any protected API call.',
    "Protected fetch returns 401\n↓\nClear auth store\n↓\nRedirect /login?next={currentPath}",
    '- Any admin endpoint',
    '',
    '{"message":"Unauthenticated."}',
    '- **Global:** clear auth.\n- **Local:** drop in-flight form state or warn user.',
    '- Modal: â€œSession expiredâ€.',
    '- Same as global 401 workflow.',
    '',
    'N/A'
);

$workflow(
    'Forbidden (403)',
    'User lacks permission for an action.',
    'Authenticated but missing slug.',
    'Clicking restricted menu/button.',
    "Action call returns 403\n↓\nShow permission denied message\n↓\nKeep user on safe page",
    '- Any permission-protected route',
    '',
    '{"message":"Forbidden."}',
    '- **Global:** use permissions to prevent call when possible.
- **Local:** `can(permission)` guards.',
    '- Hide buttons when `permissions` lacks slug.
- If still triggered: toast â€œYou do not have permissionâ€.',
    '- Do not logout on 403 (user is authenticated).',
    'Build navigation from `/me` permissions, not role names alone.',
    'Varies per module'
);

$lines[] = '## Dashboard';
$lines[] = '';

$workflow(
    'Dashboard initial load',
    'Show KPIs and charts after login.',
    'User authenticated; `dashboard.view` permission.',
    '/dashboard',
    "Enter dashboard\n↓\nParallel fetch:\n  GET /admin/dashboard/statistics?year=&month=\n  GET /admin/dashboard/revenue?start_date=&end_date=&group_by=day\n  GET /admin/dashboard/expenses?start_date=&end_date=&group_by=month\n↓\nRender KPI cards + charts\n↓\nOptional: GET /admin/alerts/pending for badge",
    "- `GET /admin/dashboard/statistics`\n- `GET /admin/dashboard/revenue`\n- `GET /admin/dashboard/expenses`\n- Optional: `GET /admin/alerts/pending`",
    'Query examples: `year=2026&month=6`, `start_date=2026-06-01&end_date=2026-06-30`',
    $snippet($captured, 13, 35),
    '- **Global:** optional dashboard date range.\n- **Local:** KPI + chart datasets.',
    '- Skeleton cards.\n- Chart empty states when totals are zero.',
    '- `422` on invalid dates: reset to current month.',
    'Revenue counts only `paid` payments. Expenses include vehicle and general expenses.',
    'dashboard.view'
);

$workflow(
    'Dashboard refresh',
    'Update KPIs after operational changes.',
    'On dashboard page.',
    'Refresh control or return from another module.',
    "User clicks Refresh\n↓\nRe-fetch statistics + revenue + expenses\n↓\nUpdate widgets",
    '- Same as dashboard initial load',
    '',
    '',
    '- **Local:** replace dashboard datasets.',
    '- Small spinner on refresh icon.\n- Toast optional.',
    '- Partial failure: show per-widget error.',
    '',
    'dashboard.view'
);

// Continue with major modules - I'll append vehicles through roadmap in the write call using more workflow() calls

$lines[] = '## Vehicles';
$lines[] = '';

$workflow(
    'List vehicles',
    'Browse fleet inventory.',
    '`vehicles.view` permission.',
    '/admin/vehicles',
    "Open Vehicles page\n↓\nGET /admin/vehicles?page=1\n↓\nRender table (name, plate, status, prices)\n↓\nUser changes page → GET ?page=n",
    '- `GET /admin/vehicles`',
    '',
    $snippet($captured, 28, 30),
    '- **Local:** `vehicles[]`, pagination meta.\n- **Global:** cache brands/categories from lookups for labels.',
    '- Row actions: View, Edit, Delete (permission-gated).\n- Empty: â€œNo vehicles yetâ€ + Create CTA.',
    '- `403`: hide module from nav.',
    'List does not include photos/documents; fetch detail for media.',
    'vehicles.view'
);

$workflow(
    'Search and filter vehicles',
    'Find vehicles by plate, name, brand, or status without server-side query params.',
    '`vehicles.view`; at least one page of vehicles loaded.',
    '/admin/vehicles',
    "GET /admin/vehicles?page=1\n↓\nRender table\n↓\nUser types search text OR selects status/brand filters\n↓\nFilter `vehicles[]` in memory (client-side)\n↓\nRe-render filtered rows\n↓\n(Optional) fetch additional pages if matches may exist elsewhere",
    '- `GET /admin/vehicles?page={n}` — **no** `?search=` or `?filter=` on API',
    '',
    $snippet($captured, 28, 20),
    '- **Local:** `searchQuery`, `activeFilters`, `allLoadedVehicles[]` (accumulated pages optional).\n- **Global:** brand/status labels from lookups.\n- **Discard:** filtered view when clearing search.',
    '- Search input debounced ~300ms.\n- Filter chips with clear-all.\n- Show â€œNo matches on this pageâ€ vs true empty fleet.',
    '- `401`/`403` as standard.\n- Empty after filter: offer clear filters CTA.',
    '- API only supports `?page=` pagination — search/filter is **client-side** on loaded data.\n- For exhaustive search across all pages, loop pages until `meta.last_page` (use sparingly).',
    'vehicles.view'
);

$workflow(
    'View vehicle details',
    'Inspect one vehicle including photos and documents.',
    'Vehicle id from list or deep link.',
    '/admin/vehicles/{id}',
    "Open detail\n↓\nGET /admin/vehicles/{id}\n↓\nRender specs, photos[], documents[]\n↓\nTabs: Maintenance history, Expenses (optional)",
    "- `GET /admin/vehicles/{id}`\n- Optional: `GET /admin/vehicles/{id}/maintenances`\n- Optional: `GET /admin/vehicles/{id}/expenses`",
    '',
    $snippet($captured, 30, 35),
    '- **Local:** `vehicle` record.\n- **Store id** in route param.',
    '- Gallery for `photos`.\n- Documents list with expiry badges.',
    '- `404`: vehicle deleted → back to list.',
    '`file_path` on documents is storage path — do not treat as public CDN URL unless storage strategy is added.',
    'vehicles.view'
);

$workflow(
    'Create vehicle',
    'Add a new fleet vehicle.',
    '`vehicles.create`; lookups loaded.',
    '/admin/vehicles/new',
    "Open create form\n↓\nParallel load:\n  GET /admin/lookups (statuses, transmission, fuel)\n  GET /admin/brands?page=1\n  GET /admin/categories?page=1\n↓\nUser fills form\n↓\nPOST /admin/vehicles\n↓\n201 → navigate to detail or list\n↓\nSuccess toast",
    "- `GET /admin/lookups`\n- `GET /admin/brands?page=1`\n- `GET /admin/categories?page=1`\n- `POST /admin/vehicles`",
    "Example JSON body:\n```json\n{\n  \"brand_id\": 7,\n  \"category_id\": 6,\n  \"name\": \"QA Disposable Vehicle\",\n  \"slug\": \"qa-disposable-vehicle\",\n  \"model\": \"Sandero\",\n  \"year\": 2024,\n  \"plate_number\": \"QA-001\",\n  \"mileage\": 10000,\n  \"seats\": 5,\n  \"doors\": 5,\n  \"daily_price\": \"375.00\",\n  \"weekly_price\": \"2200.00\",\n  \"monthly_price\": \"8500.00\",\n  \"deposit_amount\": \"3000.00\",\n  \"status_slug\": \"available\",\n  \"transmission_type_slug\": \"manual\",\n  \"fuel_type_slug\": \"gasoline\",\n  \"description\": \"Optional notes\",\n  \"is_featured\": false,\n  \"is_active\": true\n}\n```",
    $snippet($captured, 29, 35),
    '- **Local:** form state.\n- **After success:** store `data.id` for redirect.',
    '- Disable submit while saving.\n- Slug auto-generate from name optional.',
    '- `422` duplicate slug/plate.',
    'Use lookup slugs (`status_slug`, `transmission_type_slug`, `fuel_type_slug`).',
    'vehicles.create'
);

$workflow(
    'Full update vehicle (PUT)',
    'Replace editable vehicle fields.',
    '`vehicles.update`; vehicle loaded.',
    '/admin/vehicles/{id}/edit',
    "Load GET /admin/vehicles/{id}\n↓\nPopulate form\n↓\nUser saves\n↓\nPUT /admin/vehicles/{id}\n↓\nRefresh detail\n↓\nToast success",
    '- `GET /admin/vehicles/{id}`\n- `PUT /admin/vehicles/{id}`',
    'Send full validated payload (same fields as create).',
    $snippet($captured, 31, 30),
    '- **Local:** form ↔ API mapping.\n- **Discard** dirty state after save.',
    '- Warn on navigate away with unsaved changes.',
    '- `422` on invalid slugs.',
    'PUT uses same update handler as PATCH in Laravel.',
    'vehicles.update'
);

$workflow(
    'Partial update vehicle (PATCH)',
    'Quick field updates (status, mileage, price).',
    '`vehicles.update`.',
    'Inline editors on list/detail.',
    "User edits field\n↓\nPATCH /admin/vehicles/{id} with changed fields only\n↓\nMerge response into UI",
    '- `PATCH /admin/vehicles/{id}`',
    'Example: `{ "status_slug": "maintenance", "mileage": 13500 }`',
    $snippet($captured, 32, 30),
    '- **Local:** patch single vehicle in list cache.',
    '- Inline save indicator.',
    '- `422` if status transition invalid.',
    'Changing `status_slug` affects fleet availability displays.',
    'vehicles.update'
);

$workflow(
    'Delete vehicle',
    'Remove vehicle from active fleet (soft delete).',
    '`vehicles.delete`.',
    'Detail or row action.',
    "User confirms delete\n↓\nDELETE /admin/vehicles/{id}\n↓\n204 → remove row / go to list\n↓\nToast success",
    '- `DELETE /admin/vehicles/{id}`',
    '',
    'HTTP 204 No Content',
    '- **Local:** remove from list.\n- **Route:** redirect to list.',
    '- Strong confirmation dialog.\n- Explain soft delete.',
    '- `500` if related records block deletion.',
    'Do not delete vehicles referenced by active reservations without business review.',
    'vehicles.delete'
);

$workflow(
    'Vehicle images / files (read-only today)',
    'Display existing photos and documents.',
    'Vehicle detail loaded.',
    'Vehicle detail gallery.',
    "GET /admin/vehicles/{id}\n↓\nRender photos[] and documents[] from response\n↓\n(No upload step — not implemented in API)",
    '- `GET /admin/vehicles/{id}` only',
    '',
    $snippet($captured, 30, 25),
    '- **Local:** display arrays from detail response.',
    '- Placeholder when `photos` empty.\n- Hide upload UI unless backend adds endpoints.',
    'N/A',
    '**There is no `POST` vehicle photo/document upload route in `routes/api.php`.** Do not build upload UI until backend exposes it. Customer documents and expense/contract uploads use different modules.',
    'vehicles.view'
);

// Customers — full workflow template
$lines[] = '## Customers';
$lines[] = '';

$workflow(
    'List customers',
    'Browse and open customer records.',
    '`customers.view` permission.',
    '/admin/customers',
    "Open Customers page\n↓\nGET /admin/customers?page=1\n↓\nRender table (full_name, phone, email)\n↓\nUser changes page → GET ?page=n",
    '- `GET /admin/customers`',
    '',
    $snippet($captured, 36, 25),
    '- **Local:** `customers[]`, pagination meta.\n- **Global:** none.\n- **Discard:** rows when page changes.',
    '- Client-side search by name/phone on current page.\n- Empty: â€œNo customers yetâ€ + Create CTA.',
    '- `403`: hide module from nav.\n- Empty `data[]`: empty state, not error.',
    'List excludes `documents[]`; load detail for files.',
    'customers.view'
);

$workflow(
    'View customer details',
    'Inspect profile and uploaded documents.',
    'Customer id from list or deep link.',
    '/admin/customers/{id}',
    "Click row or open deep link\n↓\nGET /admin/customers/{id}\n↓\nRender profile + documents[]",
    '- `GET /admin/customers/{id}`',
    '',
    $snippet($captured, 38, 30),
    '- **Local:** `customer` record with nested `documents`.\n- **Route:** store `{id}` param.',
    '- Document list with type labels and expiry.\n- Upload button if `customers.update`.',
    '- `404`: redirect to list with toast.',
    'Documents include `file_path` (storage path) — use download strategy consistent with other modules.',
    'customers.view'
);

$workflow(
    'Create customer',
    'Register a new customer.',
    '`customers.create`.',
    '/admin/customers/new',
    "Open create form\n↓\nUser fills fields\n↓\nPOST /admin/customers\n↓\n201 → redirect to detail\n↓\nSuccess toast",
    '- `POST /admin/customers`',
    '```json
{
  "full_name": "Postman Customer",
  "nationality": "Moroccan",
  "phone": "+212611111111",
  "email": "postman.customer@example.com",
  "passport_or_cin": "PC123456",
  "driving_license_number": "PC-DL-001"
}
```',
    $snippet($captured, 37, 25),
    '- **Local:** form state.\n- **After success:** redirect using `data.id`.',
    '- Disable submit while saving.\n- Phone format hint (+212â€¦).',
    '- `422` on duplicate phone/email if enforced.',
    'Email is optional in captured create flow.',
    'customers.create'
);

$workflow(
    'Update customer',
    'Edit customer profile fields.',
    '`customers.update`; customer loaded.',
    '/admin/customers/{id}/edit',
    "Load GET /admin/customers/{id}\n↓\nPopulate form\n↓\nUser saves\n↓\nPUT or PATCH /admin/customers/{id}\n↓\nRefresh detail\n↓\nToast success",
    "- `GET /admin/customers/{id}`\n- `PUT /admin/customers/{id}` or `PATCH /admin/customers/{id}`",
    'Send changed fields (PATCH) or full record (PUT). Same fields as create.',
    $snippet($captured, 39, 25),
    '- **Local:** form ↔ API mapping.\n- **Discard** dirty state after save.',
    '- Warn on navigate away with unsaved changes.',
    '- `422` validation errors inline.',
    'PUT and PATCH share the same Laravel update handler.',
    'customers.update'
);

$workflow(
    'Delete customer',
    'Remove customer (soft delete).',
    '`customers.delete`.',
    'Row action or detail page.',
    "User confirms delete\n↓\nDELETE /admin/customers/{id}\n↓\n204 → remove row / navigate to list\n↓\nToast success",
    '- `DELETE /admin/customers/{id}`',
    '',
    'HTTP 204 No Content',
    '- **Local:** remove from list cache.\n- **Route:** redirect to list.',
    '- Strong confirmation dialog.',
    '- May fail if active reservations reference customer.',
    'Soft delete — record hidden from lists.',
    'customers.delete'
);

$workflow(
    'Upload customer document',
    'Attach ID, license, or other document via multipart.',
    '`customers.update`; customer detail open.',
    '/admin/customers/{id}',
    "User clicks Upload\n↓\nPick file + document_type_slug + title\n↓\nBuild FormData\n↓\nPOST /admin/customers/{id}/documents\n↓\nAppend returned document to list\n↓\nToast success",
    '- `POST /admin/customers/{id}/documents`',
    "FormData fields:\n- `document_type_slug` (from lookups)\n- `title`\n- `file` (binary)\n- `expires_at` (optional, ISO date)",
    $snippet($captured, 42, 25),
    '- **Local:** append to `customer.documents`.\n- **Do not** set `Content-Type` header manually.',
    '- Upload progress bar.\n- Validate PDF/image client-side before send.',
    '- `422` on invalid mime or missing fields.',
    'Separate from vehicle files — vehicles have no upload API today.',
    'customers.update'
);

require __DIR__.'/frontend_workflows_expanded.php';

$remaining = <<<'MD'
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

MD;

$lines[] = $remaining;

file_put_contents($outputPath, implode("\n", $lines)."\n");
echo "Generated {$outputPath}\n";
