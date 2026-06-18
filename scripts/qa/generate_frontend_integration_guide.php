<?php

declare(strict_types=1);

$backendRoot = dirname(__DIR__, 2);
$workflowPath = $backendRoot.'/POSTMAN_TEST_WORKFLOW.txt';
$capturedPath = $backendRoot.'/storage/qa/reports/captured-responses.json';
$outputPath = $backendRoot.'/FRONTEND_API_INTEGRATION_GUIDE.md';

$workflow = file_get_contents($workflowPath);
if ($workflow === false) {
    fwrite(STDERR, "Cannot read workflow file.\n");
    exit(1);
}

$captured = file_exists($capturedPath)
    ? json_decode(file_get_contents($capturedPath), true, 512, JSON_THROW_ON_ERROR)
    : [];

$workflow = str_replace("\r\n", "\n", $workflow);

preg_match_all(
    '/^(\d+)\.\s+(GET|POST|PUT|PATCH|DELETE)\s+(\S+)\s+\[(PUBLIC|ADMIN)\]/m',
    $workflow,
    $matches,
    PREG_OFFSET_CAPTURE
);

$endpoints = [];
$count = count($matches[0]);

for ($i = 0; $i < $count; $i++) {
    $number = (int) $matches[1][$i][0];
    $method = $matches[2][$i][0];
    $path = $matches[3][$i][0];
    $auth = $matches[4][$i][0];
    $start = $matches[0][$i][1];
    $end = ($i + 1 < $count) ? $matches[0][$i + 1][1] : strlen($workflow);
    $section = substr($workflow, $start, $end - $start);

    $endpoints[] = [
        'number' => $number,
        'method' => $method,
        'path' => $path,
        'auth' => $auth,
        'section' => $section,
    ];
}

$permissions = require __DIR__.'/frontend_endpoint_permissions.php';
$modules = require __DIR__.'/frontend_endpoint_modules.php';
$multipartEndpoints = [42, 73, 83, 86, 87];
$noContentEndpoints = [21, 27, 33, 41, 43, 49, 57, 81, 88];
$binaryEndpoints = [72];

$extract = static function (string $section, string $label): string {
    if (! preg_match('/'.preg_quote($label, '/').':\s*\n(.*?)(?=\n\n|\z)/s', $section, $m)) {
        return '';
    }

    return trim($m[1]);
};

$extractJsonBlock = static function (string $section, string $afterLabel): ?string {
    if (! preg_match('/'.preg_quote($afterLabel, '/').':\s*```json\s*(.*?)\s*```/s', $section, $m)) {
        return null;
    }

    return trim($m[1]);
};

$prettyCaptured = static function (array $captured, int $number) use ($binaryEndpoints, $noContentEndpoints): string {
    if (in_array($number, $binaryEndpoints, true)) {
        return 'Binary PDF file. Use `response.blob()` and create an object URL for preview/download.';
    }

    if (in_array($number, $noContentEndpoints, true)) {
        return 'HTTP 204 No Content — empty body.';
    }

    $key = (string) $number;
    if (! isset($captured[$key]['body'])) {
        return '_No captured response available._';
    }

    return json_encode($captured[$key]['body'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
};

$stateIds = static function (string $path, string $method): string {
    if (preg_match('/\{(\w+)\}/', $path, $m) && in_array($method, ['GET', 'PUT', 'PATCH', 'DELETE'], true)) {
        return 'Path parameter `'.$m[1].'` from route or list selection.';
    }

    if (in_array($method, ['POST'], true) && str_contains($path, '/auth/login')) {
        return 'Store `access_token` and user permissions from response.';
    }

    if ($method === 'POST' && ! str_contains($path, '/confirm') && ! str_contains($path, '/start') && ! str_contains($path, '/complete') && ! str_contains($path, '/cancel') && ! str_contains($path, '/reject') && ! str_contains($path, '/generate') && ! str_contains($path, '/signed') && ! str_contains($path, '/check-availability')) {
        return 'Store `data.id` returned by create endpoints for follow-up screens.';
    }

    return 'Usually none unless navigating to detail/edit screens.';
};

$fieldsToSend = static function (int $number, ?string $body, bool $isMultipart): string {
    if ($isMultipart) {
        return 'See multipart field list in request body section.';
    }

    if ($body === null || $body === 'None.') {
        return 'None.';
    }

    $decoded = json_decode($body, true);
    if (! is_array($decoded)) {
        return 'See request body JSON keys.';
    }

    return implode(', ', array_keys($decoded));
};

$fieldsNotToSend = static function (string $method): string {
    if (in_array($method, ['POST', 'PUT', 'PATCH'], true)) {
        return '`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.';
    }

    return 'N/A for read/delete/action endpoints without body.';
};

$fieldsToRender = static function (string $path, string $method): string {
    if (str_contains($path, '/dashboard/')) {
        return 'KPI numbers, grouped chart arrays, date range labels.';
    }

    if ($method === 'GET' && str_contains($path, '/auth/me')) {
        return '`user.name`, `user.email`, `user.roles`, `user.permissions` for menu gating.';
    }

    if (str_contains($path, '/lookups')) {
        return 'Lookup lists for select inputs (`slug` as value, `name` as label).';
    }

    if (str_contains($path, '/vehicles')) {
        return 'Vehicle card/table fields, nested `brand`, `category`, `status`, `transmission_type`, `fuel_type`, money fields, `photos`, `documents` on detail.';
    }

    if (str_contains($path, '/reservations')) {
        return 'Reservation number, customer, vehicle, status, payment_status, dates, pricing totals, notes, lifecycle timestamps.';
    }

    if (str_contains($path, '/payments')) {
        return 'Amount, method/type/status lookups, reservation reference, payment date, customer payer name.';
    }

    if (str_contains($path, '/contracts')) {
        return '`contract_number`, `status`, `has_pdf`, `has_signed_pdf`, `generated_at`, `signed_at`.';
    }

    if (str_contains($path, '/alerts')) {
        return 'Title, message, due_date, alert_type, alert_status, optional vehicle.';
    }

    return 'Use keys returned in `data` or top-level response object.';
};

$fetchExample = static function (string $method, string $path, ?string $body, bool $isMultipart, string $auth): string {
    $urlPath = preg_replace('/\{(\w+)\}/', '${$1}', $path) ?? $path;
    $url = '`http://127.0.0.1:8000'.$urlPath.'`';

    $headers = ["    Accept: 'application/json'"];
    if ($auth === 'ADMIN' && ! str_contains($path, '/auth/login')) {
        $headers[] = "    ...(token ? { Authorization: `Bearer \${token}` } : {})";
    }
    if (! $isMultipart && $body !== null && $body !== 'None.' && in_array($method, ['POST', 'PUT', 'PATCH'], true)) {
        $headers[] = "    'Content-Type': 'application/json'";
    }

    $headerBlock = implode(",\n", $headers);
    $parts = ["  method: '{$method}'", "  headers: {\n{$headerBlock}\n  }"];

    if ($isMultipart) {
        $parts[] = '  body: formData';
    } elseif ($body !== null && $body !== 'None.' && in_array($method, ['POST', 'PUT', 'PATCH'], true)) {
        $parts[] = '  body: JSON.stringify(payload)';
    }

    $opts = implode(",\n", $parts);

    $setup = '';
    if ($isMultipart) {
        $setup = "const formData = new FormData();\n// append fields and files\n\n";
    } elseif ($body !== null && $body !== 'None.' && in_array($method, ['POST', 'PUT', 'PATCH'], true)) {
        $setup = "const payload = ".$body.";\n\n";
    }

    $token = $auth === 'ADMIN' && ! str_contains($path, '/auth/login')
        ? "const token = localStorage.getItem('admin_token');\n"
        : '';

    return "```javascript\n{$setup}{$token}const response = await fetch({$url}, {\n{$opts}\n});\n\nif (!response.ok) {\n  const error = await response.json().catch(() => ({}));\n  throw error;\n}\n\nconst data = response.status === 204 ? null : await response.json();\n```";
};

$lines = [];
$lines[] = '# Limosud Cars — Frontend API Integration Guide';
$lines[] = '';
$lines[] = '> Audience: frontend developers building the public website and admin dashboard.';
$lines[] = '> Source of truth for response shapes: Newman QA captured responses (`storage/qa/reports/captured-responses.json`).';
$lines[] = '> Last generated: '.date('Y-m-d H:i:s');
$lines[] = '';
$lines[] = '---';
$lines[] = '';
$lines[] = '## 1. Base API Setup';
$lines[] = '';
$lines[] = '### Base URL';
$lines[] = '';
$lines[] = '```';
$lines[] = 'http://127.0.0.1:8000/api';
$lines[] = '```';
$lines[] = '';
$lines[] = 'Use an environment variable in the frontend, for example `VITE_API_BASE_URL` or `NEXT_PUBLIC_API_BASE_URL`.';
$lines[] = '';
$lines[] = '### Required headers';
$lines[] = '';
$lines[] = '| Context | Headers |';
$lines[] = '|--------|---------|';
$lines[] = '| JSON requests | `Accept: application/json`, `Content-Type: application/json` |';
$lines[] = '| Admin protected routes | `Authorization: Bearer {access_token}` |';
$lines[] = '| File uploads | `Accept: application/json`, `Authorization: Bearer {access_token}` — **do not** set `Content-Type` manually when using `FormData` |';
$lines[] = '';
$lines[] = '### Auth token storage';
$lines[] = '';
$lines[] = '- Login (`POST /api/admin/auth/login`) returns `access_token` (Sanctum personal access token).';
$lines[] = '- Store it in memory + `sessionStorage` or `localStorage` as `admin_token`.';
$lines[] = '- Attach it to every admin request except login.';
$lines[] = '- Clear it on `401` or after `POST /api/admin/auth/logout`.';
$lines[] = '- Use `GET /api/admin/auth/me` on app boot to restore user, roles, and permissions.';
$lines[] = '';
$lines[] = '### Global error handling';
$lines[] = '';
$lines[] = '| Status | Meaning | Frontend action |';
$lines[] = '|--------|---------|-----------------|';
$lines[] = '| `401` | Missing/invalid/revoked token | Clear token, redirect to login |';
$lines[] = '| `403` | Authenticated but missing permission | Show permission denied UI |';
$lines[] = '| `404` | Resource not found | Show not-found state |';
$lines[] = '| `422` | Validation failed | Render `errors[field][]` under form fields |';
$lines[] = '| `500` | Server error | Show retry/support message |';
$lines[] = '';
$lines[] = 'Standard error bodies:';
$lines[] = '';
$lines[] = '```json';
$lines[] = '{"message":"Unauthenticated."}';
$lines[] = '```';
$lines[] = '';
$lines[] = '```json';
$lines[] = '{"message":"Forbidden."}';
$lines[] = '```';
$lines[] = '';
$lines[] = '```json';
$lines[] = '{"message":"The given data was invalid.","errors":{"email":["The email field is required."]}}';
$lines[] = '```';
$lines[] = '';
$lines[] = '---';
$lines[] = '';
$lines[] = '## 2. HTTP Method Usage';
$lines[] = '';
$lines[] = '| Method | Frontend usage |';
$lines[] = '|--------|----------------|';
$lines[] = '| `GET` | Fetch lists, details, dashboards, lookups |';
$lines[] = '| `POST` | Create records or trigger actions (`confirm`, `start`, `generate`, `cancel`) |';
$lines[] = '| `PUT` | Full update forms (send all required editable fields) |';
$lines[] = '| `PATCH` | Partial update (send only changed fields) |';
$lines[] = '| `DELETE` | Soft delete where available; many finance records use status actions instead |';
$lines[] = '';
$lines[] = '---';
$lines[] = '';
$lines[] = '## 3. Frontend Integration Examples';
$lines[] = '';
$lines[] = '### Login flow';
$lines[] = '';
$lines[] = $fetchExample('POST', '/api/admin/auth/login', '{"email":"admin@example.com","password":"***"}', false, 'PUBLIC');
$lines[] = '';
$lines[] = 'Captured success response:';
$lines[] = '';
$lines[] = '```json';
$lines[] = $prettyCaptured($captured, 9);
$lines[] = '```';
$lines[] = '';
$lines[] = '```javascript';
$lines[] = "localStorage.setItem('admin_token', data.access_token);";
$lines[] = '```';
$lines[] = '';
$lines[] = '### API client with Bearer token';
$lines[] = '';
$lines[] = '```javascript';
$lines[] = 'export async function api(path, options = {}) {';
$lines[] = "  const token = localStorage.getItem('admin_token');";
$lines[] = '  const headers = {';
$lines[] = "    Accept: 'application/json',";
$lines[] = '    ...(options.body && !(options.body instanceof FormData) ? { \'Content-Type\': \'application/json\' } : {}),';
$lines[] = '    ...(token ? { Authorization: `Bearer ${token}` } : {}),';
$lines[] = '    ...options.headers,';
$lines[] = '  };';
$lines[] = '';
$lines[] = '  const response = await fetch(`${API_BASE}${path}`, { ...options, headers });';
$lines[] = '';
$lines[] = '// API_BASE example: http://127.0.0.1:8000/api';
$lines[] = "// path examples: '/admin/vehicles', '/admin/auth/me'";
$lines[] = '  if (response.status === 401) {';
$lines[] = "    localStorage.removeItem('admin_token');";
$lines[] = "    window.location.href = '/login';";
$lines[] = '    return;';
$lines[] = '  }';
$lines[] = '  if (!response.ok) {';
$lines[] = '    throw await response.json().catch(() => ({ message: response.statusText }));';
$lines[] = '  }';
$lines[] = '  return response.status === 204 ? null : response.json();';
$lines[] = '}';
$lines[] = '```';
$lines[] = '';
$lines[] = '### Logout flow';
$lines[] = '';
$lines[] = '```javascript';
$lines[] = "await api('/admin/auth/logout', { method: 'POST' });";
$lines[] = "localStorage.removeItem('admin_token');";
$lines[] = '```';
$lines[] = '';
$lines[] = '### List vehicles';
$lines[] = '';
$lines[] = '```javascript';
$lines[] = "const page = await api('/admin/vehicles?page=1');";
$lines[] = 'const vehicles = page.data;';
$lines[] = '```';
$lines[] = '';
$lines[] = '### Create vehicle';
$lines[] = '';
$lines[] = '```javascript';
$lines[] = 'const created = await api(\'/admin/vehicles\', {';
$lines[] = "  method: 'POST',";
$lines[] = "  body: JSON.stringify({ brand_id: 1, category_id: 1, status_slug: 'available', transmission_type_slug: 'manual', fuel_type_slug: 'diesel', name: 'Dacia Sandero', slug: 'dacia-sandero', model: 'Sandero', year: 2024, plate_number: '12345-A-10', mileage: 10000, seats: 5, doors: 5, daily_price: 350, weekly_price: 2200, monthly_price: 8500, deposit_amount: 3000, is_featured: false, is_active: true })";
$lines[] = '});';
$lines[] = 'const vehicleId = created.data.id;';
$lines[] = '```';
$lines[] = '';
$lines[] = '### Update vehicle with PUT';
$lines[] = '';
$lines[] = '```javascript';
$lines[] = 'await api(`/admin/vehicles/${vehicleId}`, { method: \'PUT\', body: JSON.stringify(fullVehicleForm) });';
$lines[] = '```';
$lines[] = '';
$lines[] = '### Update vehicle with PATCH';
$lines[] = '';
$lines[] = '```javascript';
$lines[] = 'await api(`/admin/vehicles/${vehicleId}`, { method: \'PATCH\', body: JSON.stringify({ status_slug: \'maintenance\', mileage: 13500 }) });';
$lines[] = '```';
$lines[] = '';
$lines[] = '### Delete vehicle';
$lines[] = '';
$lines[] = '```javascript';
$lines[] = 'await api(`/admin/vehicles/${vehicleId}`, { method: \'DELETE\' });';
$lines[] = '```';
$lines[] = '';
$lines[] = '### Upload files with FormData';
$lines[] = '';
$lines[] = '```javascript';
$lines[] = 'const formData = new FormData();';
$lines[] = "formData.append('document_type_slug', 'passport');";
$lines[] = "formData.append('title', 'Passport Scan');";
$lines[] = "formData.append('file', file);";
$lines[] = "formData.append('expires_at', '2028-12-31');";
$lines[] = 'await api(`/admin/customers/${customerId}/documents`, { method: \'POST\', body: formData });';
$lines[] = '```';
$lines[] = '';
$lines[] = '### Display 422 validation errors';
$lines[] = '';
$lines[] = '```javascript';
$lines[] = 'try {';
$lines[] = '  await api(\'/admin/vehicles\', { method: \'POST\', body: JSON.stringify(form) });';
$lines[] = '} catch (error) {';
$lines[] = '  if (error.errors) {';
$lines[] = '    Object.entries(error.errors).forEach(([field, messages]) => {';
$lines[] = '      setFieldError(field, messages[0]);';
$lines[] = '    });';
$lines[] = '  }';
$lines[] = '}';
$lines[] = '```';
$lines[] = '';
$lines[] = '---';
$lines[] = '';
$lines[] = '## 4. Lookup Slugs';
$lines[] = '';
$lines[] = 'Send lookup **slugs** in write requests instead of hardcoding lookup IDs when the API accepts `*_slug` fields.';
$lines[] = '';
$lines[] = 'Load options from `GET /api/public/lookups` (public site) or `GET /api/admin/lookups` (admin).';
$lines[] = '';
$lines[] = '---';
$lines[] = '';
$lines[] = '## 5. Endpoint Reference (96 endpoints)';
$lines[] = '';

$currentModule = null;

foreach ($endpoints as $endpoint) {
    $number = $endpoint['number'];
    $method = $endpoint['method'];
    $path = $endpoint['path'];
    $auth = $endpoint['auth'];
    $section = $endpoint['section'];
    $module = $modules[$number] ?? 'Other';

    if ($module !== $currentModule) {
        $currentModule = $module;
        $lines[] = '';
        $lines[] = '### Module: '.$module;
        $lines[] = '';
    }

    $purpose = $extract($section, 'Purpose');
    $headers = $extract($section, 'Headers');
    $query = $extract($section, 'Query Params');
    $urlParams = $extract($section, 'URL Params');
    $requestBodyRaw = $extract($section, 'Request Body');
    $errors = $extract($section, 'Error Responses');
    $notes = $extract($section, 'Notes');
    $requestJson = $extractJsonBlock($section, 'Request Body');
    $isMultipart = in_array($number, $multipartEndpoints, true) || str_contains($requestBodyRaw, 'multipart/form-data');
    $permission = $permissions[$number] ?? ($auth === 'PUBLIC' ? 'None' : 'Authenticated admin');

    $lines[] = '#### '.$number.'. `'.$method.'` `'.$path.'`';
    $lines[] = '';
    $lines[] = '| Item | Value |';
    $lines[] = '|------|-------|';
    $lines[] = '| When to call | '.$purpose.' |';
    $lines[] = '| Auth | '.($auth === 'PUBLIC' ? 'Public' : 'Admin Bearer token').' |';
    $lines[] = '| Permission | `'.$permission.'` |';
    $lines[] = '';
    $lines[] = '**Query params**';
    $lines[] = '';
    $lines[] = $query === '' || $query === 'None.' ? 'None.' : $query;
    $lines[] = '';
    $lines[] = '**Path params**';
    $lines[] = '';
    $lines[] = $urlParams === '' || $urlParams === 'None.' ? 'None.' : $urlParams;
    $lines[] = '';
    $lines[] = '**Request body**';
    $lines[] = '';
    if ($isMultipart) {
        $lines[] = 'Use `multipart/form-data`. See workflow notes for field names.';
        $lines[] = '';
        $lines[] = '```';
        $lines[] = trim($requestBodyRaw);
        $lines[] = '```';
    } elseif ($requestJson !== null) {
        $lines[] = '```json';
        $lines[] = $requestJson;
        $lines[] = '```';
    } else {
        $lines[] = 'None.';
    }
    $lines[] = '';
    $lines[] = '**Captured success response**';
    $lines[] = '';
    $lines[] = '```json';
    $lines[] = $prettyCaptured($captured, $number);
    $lines[] = '```';
    $lines[] = '';
    $lines[] = '**Error responses**';
    $lines[] = '';
    $lines[] = $errors === '' ? 'Standard errors from section 1.' : $errors;
    $lines[] = '';
    $lines[] = '**Render in UI**';
    $lines[] = '';
    $lines[] = $fieldsToRender($path, $method);
    $lines[] = '';
    $lines[] = '**Send from UI**';
    $lines[] = '';
    $lines[] = $fieldsToSend($number, $requestJson, $isMultipart);
    $lines[] = '';
    $lines[] = '**Do not send**';
    $lines[] = '';
    $lines[] = $fieldsNotToSend($method);
    $lines[] = '';
    $lines[] = '**Store in frontend state**';
    $lines[] = '';
    $lines[] = $stateIds($path, $method);
    $lines[] = '';
    if ($notes !== '') {
        $lines[] = '**Notes**';
        $lines[] = '';
        $lines[] = $notes;
        $lines[] = '';
    }
    $lines[] = '**Example call**';
    $lines[] = '';
    $lines[] = $fetchExample($method, $path, $requestJson, $isMultipart, $auth);
    $lines[] = '';
}

$lines[] = '---';
$lines[] = '';
$lines[] = '## 6. Module Summary';
$lines[] = '';
$lines[] = '| Module | Endpoints | Status |';
$lines[] = '|--------|-----------|--------|';
$summary = [
    'Health' => '1',
    'Auth' => '9–11',
    'Public website' => '2–8',
    'Dashboard' => '13–15',
    'Brands' => '16–21',
    'Categories' => '22–27',
    'Vehicles' => '28–35',
    'Customers' => '36–43',
    'Locations' => '44–49',
    'Reservations' => '50–62',
    'Payments' => '63–69',
    'Contracts' => '70–74',
    'Maintenance' => '75–81',
    'Expenses' => '82–88',
    'Alerts' => '89–96',
    'Lookups (admin)' => '12',
    'Site pages' => '—',
    'Audit logs' => '—',
];
foreach ($summary as $module => $range) {
    $status = in_array($module, ['Site pages', 'Audit logs'], true)
        ? 'Not exposed in `routes/api.php` yet'
        : 'Available';
    $lines[] = '| '.$module.' | '.$range.' | '.$status.' |';
}

$lines[] = '';
$lines[] = '### Site pages & audit logs';
$lines[] = '';
$lines[] = 'Permissions exist in `GET /api/admin/auth/me` (`site_pages.*`, `audit_logs.view`), but there are **no API routes** for these modules yet. Do not build frontend screens against invented endpoints.';
$lines[] = '';

file_put_contents($outputPath, implode("\n", $lines)."\n");
echo "Generated {$outputPath} with ".count($endpoints)." endpoints.\n";
