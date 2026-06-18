# Limosud Cars — Frontend API Integration Guide

> Audience: frontend developers building the public website and admin dashboard.
> Source of truth for response shapes: Newman QA captured responses (`storage/qa/reports/captured-responses.json`).
> Last generated: 2026-06-13 15:24:30

---

## 1. Base API Setup

### Base URL

```
http://127.0.0.1:8000/api
```

Use an environment variable in the frontend, for example `VITE_API_BASE_URL` or `NEXT_PUBLIC_API_BASE_URL`.

### Required headers

| Context | Headers |
|--------|---------|
| JSON requests | `Accept: application/json`, `Content-Type: application/json` |
| Admin protected routes | `Authorization: Bearer {access_token}` |
| File uploads | `Accept: application/json`, `Authorization: Bearer {access_token}` — **do not** set `Content-Type` manually when using `FormData` |

### Auth token storage

- Login (`POST /api/admin/auth/login`) returns `access_token` (Sanctum personal access token).
- Store it in memory + `sessionStorage` or `localStorage` as `admin_token`.
- Attach it to every admin request except login.
- Clear it on `401` or after `POST /api/admin/auth/logout`.
- Use `GET /api/admin/auth/me` on app boot to restore user, roles, and permissions.

### Global error handling

| Status | Meaning | Frontend action |
|--------|---------|-----------------|
| `401` | Missing/invalid/revoked token | Clear token, redirect to login |
| `403` | Authenticated but missing permission | Show permission denied UI |
| `404` | Resource not found | Show not-found state |
| `422` | Validation failed | Render `errors[field][]` under form fields |
| `500` | Server error | Show retry/support message |

Standard error bodies:

```json
{"message":"Unauthenticated."}
```

```json
{"message":"Forbidden."}
```

```json
{"message":"The given data was invalid.","errors":{"email":["The email field is required."]}}
```

---

## 2. HTTP Method Usage

| Method | Frontend usage |
|--------|----------------|
| `GET` | Fetch lists, details, dashboards, lookups |
| `POST` | Create records or trigger actions (`confirm`, `start`, `generate`, `cancel`) |
| `PUT` | Full update forms (send all required editable fields) |
| `PATCH` | Partial update (send only changed fields) |
| `DELETE` | Soft delete where available; many finance records use status actions instead |

---

## 3. Frontend Integration Examples

### Login flow

```javascript
const payload = {"email":"admin@example.com","password":"***"};

const response = await fetch(`http://127.0.0.1:8000/api/admin/auth/login`, {
  method: 'POST',
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(payload)
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

Captured success response:

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
                "id": 48,
                "module": "alerts",
                "name": "Update alerts",
                "slug": "alerts.update"
            },
            {
                "id": 46,
                "module": "alerts",
                "name": "View alerts",
                "slug": "alerts.view"
            },
            {
                "id": 54,
                "module": "audit_logs",
                "name": "View audit logs",
                "slug": "audit_logs.view"
            },
            {
                "id": 32,
                "module": "contracts",
                "name": "Generate contracts",
                "slug": "contracts.generate"
            },
            {
                "id": 33,
                "module": "contracts",
                "name": "Update contracts",
                "slug": "contracts.update"
            },
            {
                "id": 31,
                "module": "contracts",
                "name": "View contracts",
                "slug": "contracts.view"
            },
            {
                "id": 17,
                "module": "customers",
                "name": "Create customers",
                "slug": "customers.create"
            },
            {
                "id": 19,
                "module": "customers",
                "name": "Delete customers",
                "slug": "customers.delete"
            },
            {
                "id": 18,
                "module": "customers",
                "name": "Update customers",
                "slug": "customers.update"
            },
            {
                "id": 16,
                "module": "customers",
                "name": "View customers",
                "slug": "customers.view"
            },
            {
                "id": 1,
                "module": "dashboard",
                "name": "View dashboard",
                "slug": "dashboard.view"
            },
            {
                "id": 43,
                "module": "expenses",
                "name": "Create expenses",
                "slug": "expenses.create"
            },
            {
                "id": 45,
                "module": "expenses",
                "name": "Delete expenses",
                "slug": "expenses.delete"
            },
            {
                "id": 44,
                "module": "expenses",
                "name": "Update expenses",
                "slug": "expenses.update"
            },
            {
                "id": 42,
                "module": "expenses",
                "name": "View expenses",
                "slug": "expenses.view"
            },
            {
                "id": 35,
                "module": "locations",
                "name": "Create locations",
                "slug": "locations.create"
            },
            {
                "id": 37,
                "module": "locations",
                "name": "Delete locations",
                "slug": "locations.delete"
            },
            {
                "id": 36,
                "module": "locations",
                "name": "Update locations",
                "slug": "locations.update"
            },
            {
                "id": 34,
                "module": "locations",
                "name": "View locations",
                "slug": "locations.view"
            },
            {
                "id": 39,
                "module": "maintenance",
                "name": "Create maintenance",
                "slug": "maintenance.create"
            },
            {
                "id": 41,
                "module": "maintenance",
                "name": "Delete maintenance",
                "slug": "maintenance.delete"
            },
            {
                "id": 40,
                "module": "maintenance",
                "name": "Update maintenance",
                "slug": "maintenance.update"
            },
            {
                "id": 38,
                "module": "maintenance",
                "name": "View maintenance",
                "slug": "maintenance.view"
            },
            {
                "id": 30,
                "module": "payments",
                "name": "Manage payments",
                "slug": "payments.manage"
            },
            {
                "id": 29,
                "module": "payments",
                "name": "View payments",
                "slug": "payments.view"
            },
            {
                "id": 11,
                "module": "permissions",
                "name": "Assign permissions",
                "slug": "permissions.assign"
            },
            {
                "id": 10,
                "module": "permissions",
                "name": "View permissions",
                "slug": "permissions.view"
            },
            {
                "id": 27,
                "module": "reservations",
                "name": "Cancel reservations",
                "slug": "reservations.cancel"
            },
            {
                "id": 26,
                "module": "reservations",
                "name": "Complete reservations",
                "slug": "reservations.complete"
            },
            {
                "id": 24,
                "module": "reservations",
                "name": "Confirm reservations",
                "slug": "reservations.confirm"
            },
            {
                "id": 21,
                "module": "reservations",
                "name": "Create reservations",
                "slug": "reservations.create"
            },
            {
                "id": 23,
                "module": "reservations",
                "name": "Delete reservations",
                "slug": "reservations.delete"
            },
            {
                "id": 28,
                "module": "reservations",
                "name": "Reject reservations",
                "slug": "reservations.reject"
            },
            {
                "id": 25,
                "module": "reservations",
                "name": "Start reservations",
                "slug": "reservations.start"
            },
            {
                "id": 22,
                "module": "reservations",
                "name": "Update reservations",
                "slug": "reservations.update"
            },
            {
                "id": 20,
                "module": "reservations",
                "name": "View reservations",
                "slug": "reservations.view"
            },
            {
                "id": 7,
                "module": "roles",
                "name": "Create roles",
                "slug": "roles.create"
            },
            {
                "id": 9,
                "module": "roles",
                "name": "Delete roles",
                "slug": "roles.delete"
            },
            {
                "id": 8,
                "module": "roles",
                "name": "Update roles",
                "slug": "roles.update"
            },
            {
                "id": 6,
                "module": "roles",
                "name": "View roles",
                "slug": "roles.view"
            },
            {
                "id": 51,
                "module": "site_pages",
                "name": "Create site pages",
                "slug": "site_pages.create"
            },
            {
                "id": 53,
                "module": "site_pages",
                "name": "Delete site pages",
                "slug": "site_pages.delete"
            },
            {
                "id": 52,
                "module": "site_pages",
                "name": "Update site pages",
                "slug": "site_pages.update"
            },
            {
                "id": 50,
                "module": "site_pages",
                "name": "View site pages",
                "slug": "site_pages.view"
            },
            {
                "id": 3,
                "module": "users",
                "name": "Create users",
                "slug": "users.create"
            },
            {
                "id": 5,
                "module": "users",
                "name": "Delete users",
                "slug": "users.delete"
            },
            {
                "id": 4,
                "module": "users",
                "name": "Update users",
                "slug": "users.update"
            },
            {
                "id": 2,
                "module": "users",
                "name": "View users",
                "slug": "users.view"
            },
            {
                "id": 56,
                "module": "vehicle_brands",
                "name": "Create vehicle brands",
                "slug": "vehicle_brands.create"
            },
            {
                "id": 58,
                "module": "vehicle_brands",
                "name": "Delete vehicle brands",
                "slug": "vehicle_brands.delete"
            },
            {
                "id": 57,
                "module": "vehicle_brands",
                "name": "Update vehicle brands",
                "slug": "vehicle_brands.update"
            },
            {
                "id": 55,
                "module": "vehicle_brands",
                "name": "View vehicle brands",
                "slug": "vehicle_brands.view"
            },
            {
                "id": 60,
                "module": "vehicle_categories",
                "name": "Create vehicle categories",
                "slug": "vehicle_categories.create"
            },
            {
                "id": 62,
                "module": "vehicle_categories",
                "name": "Delete vehicle categories",
                "slug": "vehicle_categories.delete"
            },
            {
                "id": 61,
                "module": "vehicle_categories",
                "name": "Update vehicle categories",
                "slug": "vehicle_categories.update"
            },
            {
                "id": 59,
                "module": "vehicle_categories",
                "name": "View vehicle categories",
                "slug": "vehicle_categories.view"
            },
            {
                "id": 13,
                "module": "vehicles",
                "name": "Create vehicles",
                "slug": "vehicles.create"
            },
            {
                "id": 15,
                "module": "vehicles",
                "name": "Delete vehicles",
                "slug": "vehicles.delete"
            },
            {
                "id": 14,
                "module": "vehicles",
                "name": "Update vehicles",
                "slug": "vehicles.update"
            },
            {
                "id": 12,
                "module": "vehicles",
                "name": "View vehicles",
                "slug": "vehicles.view"
            }
        ]
    }
}
```

```javascript
localStorage.setItem('admin_token', data.access_token);
```

### API client with Bearer token

```javascript
export async function api(path, options = {}) {
  const token = localStorage.getItem('admin_token');
  const headers = {
    Accept: 'application/json',
    ...(options.body && !(options.body instanceof FormData) ? { 'Content-Type': 'application/json' } : {}),
    ...(token ? { Authorization: `Bearer ${token}` } : {}),
    ...options.headers,
  };

  const response = await fetch(`${API_BASE}${path}`, { ...options, headers });

// API_BASE example: http://127.0.0.1:8000/api
// path examples: '/admin/vehicles', '/admin/auth/me'
  if (response.status === 401) {
    localStorage.removeItem('admin_token');
    window.location.href = '/login';
    return;
  }
  if (!response.ok) {
    throw await response.json().catch(() => ({ message: response.statusText }));
  }
  return response.status === 204 ? null : response.json();
}
```

### Logout flow

```javascript
await api('/admin/auth/logout', { method: 'POST' });
localStorage.removeItem('admin_token');
```

### List vehicles

```javascript
const page = await api('/admin/vehicles?page=1');
const vehicles = page.data;
```

### Create vehicle

```javascript
const created = await api('/admin/vehicles', {
  method: 'POST',
  body: JSON.stringify({ brand_id: 1, category_id: 1, status_slug: 'available', transmission_type_slug: 'manual', fuel_type_slug: 'diesel', name: 'Dacia Sandero', slug: 'dacia-sandero', model: 'Sandero', year: 2024, plate_number: '12345-A-10', mileage: 10000, seats: 5, doors: 5, daily_price: 350, weekly_price: 2200, monthly_price: 8500, deposit_amount: 3000, is_featured: false, is_active: true })
});
const vehicleId = created.data.id;
```

### Update vehicle with PUT

```javascript
await api(`/admin/vehicles/${vehicleId}`, { method: 'PUT', body: JSON.stringify(fullVehicleForm) });
```

### Update vehicle with PATCH

```javascript
await api(`/admin/vehicles/${vehicleId}`, { method: 'PATCH', body: JSON.stringify({ status_slug: 'maintenance', mileage: 13500 }) });
```

### Delete vehicle

```javascript
await api(`/admin/vehicles/${vehicleId}`, { method: 'DELETE' });
```

### Upload files with FormData

```javascript
const formData = new FormData();
formData.append('document_type_slug', 'passport');
formData.append('title', 'Passport Scan');
formData.append('file', file);
formData.append('expires_at', '2028-12-31');
await api(`/admin/customers/${customerId}/documents`, { method: 'POST', body: formData });
```

### Display 422 validation errors

```javascript
try {
  await api('/admin/vehicles', { method: 'POST', body: JSON.stringify(form) });
} catch (error) {
  if (error.errors) {
    Object.entries(error.errors).forEach(([field, messages]) => {
      setFieldError(field, messages[0]);
    });
  }
}
```

---

## 4. Lookup Slugs

Send lookup **slugs** in write requests instead of hardcoding lookup IDs when the API accepts `*_slug` fields.

Load options from `GET /api/public/lookups` (public site) or `GET /api/admin/lookups` (admin).

---

## 5. Endpoint Reference (96 endpoints)


### Module: Health

#### 1. `GET` `/api/health`

| Item | Value |
|------|-------|
| When to call | Checks that the API is running. |
| Auth | Public |
| Permission | `None` |

**Query params**

None.

**Path params**

None.

**Request body**

None.

**Captured success response**

```json
{
    "status": "ok",
    "app": "Limosud Cars API"
}
```

**Error responses**

500 if the application cannot boot.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Usually none unless navigating to detail/edit screens.

**Notes**

Use this before running a full Postman collection.

**Example call**

```javascript
const response = await fetch(`http://127.0.0.1:8000/api/health`, {
  method: 'GET',
  headers: {
    Accept: 'application/json'
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```


### Module: Public website

#### 2. `GET` `/api/public/lookups`

| Item | Value |
|------|-------|
| When to call | Returns safe public lookup data needed by public vehicle browsing and reservation forms. |
| Auth | Public |
| Permission | `None` |

**Query params**

None.

**Path params**

None.

**Request body**

None.

**Captured success response**

```json
{
    "vehicle_brands": [
        {
            "id": 1,
            "name": "Dacia",
            "slug": "dacia",
            "is_active": true
        },
        {
            "id": 3,
            "name": "Hyundai",
            "slug": "hyundai",
            "is_active": true
        },
        {
            "id": 6,
            "name": "Peugeot",
            "slug": "peugeot",
            "is_active": true
        },
        {
            "id": 7,
            "name": "Postman Brand",
            "slug": "postman-brand",
            "is_active": true
        },
        {
            "id": 2,
            "name": "Renault",
            "slug": "renault",
            "is_active": true
        },
        {
            "id": 4,
            "name": "Toyota",
            "slug": "toyota",
            "is_active": true
        },
        {
            "id": 5,
            "name": "Volkswagen",
            "slug": "volkswagen",
            "is_active": true
        }
    ],
    "vehicle_categories": [
        {
            "id": 2,
            "name": "Compact",
            "slug": "compact",
            "description": "Compact vehicles suitable for couples and small families.",
            "is_active": true
        },
        {
            "id": 1,
            "name": "Economy",
            "slug": "economy",
            "description": "Budget-friendly city cars for daily rentals.",
            "is_active": true
        },
        {
            "id": 4,
            "name": "Luxury",
            "slug": "luxury",
            "description": "Premium vehicles for business and comfort.",
            "is_active": true
        },
        {
            "id": 6,
            "name": "Postman Category",
            "slug": "postman-category",
            "description": "QA category for API testing.",
            "is_active": true
        },
        {
            "id": 3,
            "name": "SUV",
            "slug": "suv",
            "description": "SUV vehicles suitable for Dakhla roads and longer trips.",
            "is_active": true
        },
        {
            "id": 5,
            "name": "Van",
            "slug": "van",
            "description": "Spacious vans for groups and airport transfers.",
            "is_active": true
        }
    ],
    "transmission_types": [
        {
            "id": 2,
            "name": "Automatic",
            "slug": "automatic"
        },
        {
            "id": 1,
            "name": "Manual",
            "slug": "manual"
        }
    ],
    "fuel_types": [
        {
            "id": 2,
            "name": "Diesel",
            "slug": "diesel"
        },
        {
            "id": 4,
            "name": "Electric",
            "slug": "electric"
        },
        {
            "id": 1,
            "name": "Gasoline",
            "slug": "gasoline"
        },
        {
            "id": 3,
            "name": "Hybrid",
            "slug": "hybrid"
        }
    ],
    "locations": [
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
            }
        },
        {
            "id": 2,
            "name": "Dakhla Airport",
            "slug": "dakhla-airport",
            "address": "Dakhla Airport, Morocco",
            "delivery_fee": "150.00",
            "is_active": true,
            "location_type": {
                "id": 2,
                "name": "Airport",
                "slug": "airport"
            }
        },
        {
            "id": 3,
            "name": "Postman Location",
            "slug": "postman-location",
            "address": "Postman Street, Dakhla",
            "delivery_fee": "100.00",
            "is_active": true,
            "location_type": {
                "id": 1,
                "name": "Agency",
                "slug": "agency"
            }
        }
    ]
}
```

**Error responses**

500 if lookup tables are unavailable.

**Render in UI**

Lookup lists for select inputs (`slug` as value, `name` as label).

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Usually none unless navigating to detail/edit screens.

**Notes**

Public lookups expose only active brands, active categories, transmission types, fuel types, and active locations.

**Example call**

```javascript
const response = await fetch(`http://127.0.0.1:8000/api/public/lookups`, {
  method: 'GET',
  headers: {
    Accept: 'application/json'
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 3. `GET` `/api/public/locations`

| Item | Value |
|------|-------|
| When to call | Lists active pickup/dropoff locations for public reservations. |
| Auth | Public |
| Permission | `None` |

**Query params**

None.

**Path params**

None.

**Request body**

None.

**Captured success response**

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
                "id": 2,
                "name": "Airport",
                "slug": "airport"
            },
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        },
        {
            "id": 3,
            "name": "Postman Location",
            "slug": "postman-location",
            "address": "Postman Street, Dakhla",
            "delivery_fee": "100.00",
            "is_active": true,
            "location_type": {
                "id": 1,
                "name": "Agency",
                "slug": "agency"
            },
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        }
    ]
}
```

**Error responses**

500 for unexpected server errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Usually none unless navigating to detail/edit screens.

**Notes**

Only locations with is_active=true are returned. This endpoint is not paginated.

**Example call**

```javascript
const response = await fetch(`http://127.0.0.1:8000/api/public/locations`, {
  method: 'GET',
  headers: {
    Accept: 'application/json'
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 4. `GET` `/api/public/vehicles`

| Item | Value |
|------|-------|
| When to call | Lists active public vehicles with lookup relationships and photos. |
| Auth | Public |
| Permission | `None` |

**Query params**

page: optional page number.

**Path params**

None.

**Request body**

None.

**Captured success response**

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
            "status": {
                "id": 1,
                "name": "Available",
                "slug": "available"
            },
            "transmission_type": {
                "id": 1,
                "name": "Manual",
                "slug": "manual"
            },
            "fuel_type": {
                "id": 2,
                "name": "Diesel",
                "slug": "diesel"
            },
            "photos": []
        },
        {
            "id": 4,
            "name": "QA Cancel Vehicle",
            "slug": "qa-cancel-vehicle",
            "model": "Sandero",
            "year": 2024,
            "plate_number": "QA-CANCEL-01",
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
            "status": {
                "id": 2,
                "name": "Reserved",
                "slug": "reserved"
            },
            "transmission_type": {
                "id": 1,
                "name": "Manual",
                "slug": "manual"
            },
            "fuel_type": {
                "id": 2,
                "name": "Diesel",
                "slug": "diesel"
            },
            "photos": []
        },
        {
            "id": 5,
            "name": "QA Reject Vehicle",
            "slug": "qa-reject-vehicle",
            "model": "Sandero",
            "year": 2024,
            "plate_number": "QA-REJECT-01",
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
            "status": {
                "id": 1,
                "name": "Available",
                "slug": "available"
            },
            "transmission_type": {
                "id": 1,
                "name": "Manual",
                "slug": "manual"
            },
            "fuel_type": {
                "id": 2,
                "name": "Diesel",
                "slug": "diesel"
            },
            "photos": []
        },
        {
            "id": 6,
            "name": "QA Contract Vehicle",
            "slug": "qa-contract-vehicle",
            "model": "Sandero",
            "year": 2024,
            "plate_number": "QA-CONTRACT-01",
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
            "status": {
                "id": 1,
                "name": "Available",
                "slug": "available"
            },
            "transmission_type": {
                "id": 1,
                "name": "Manual",
                "slug": "manual"
            },
            "fuel_type": {
                "id": 2,
                "name": "Diesel",
                "slug": "diesel"
            },
            "photos": []
        },
        {
            "id": 7,
            "name": "QA Payment Vehicle",
            "slug": "qa-payment-vehicle",
            "model": "Sandero",
            "year": 2024,
            "plate_number": "QA-PAYMENT-01",
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
            "status": {
                "id": 1,
                "name": "Available",
                "slug": "available"
            },
            "transmission_type": {
                "id": 1,
                "name": "Manual",
                "slug": "manual"
            },
            "fuel_type": {
                "id": 2,
                "name": "Diesel",
                "slug": "diesel"
            },
            "photos": []
        },
        {
            "id": 1,
            "name": "Dacia Sandero 2024",
            "slug": "dacia-sandero-2024",
            "model": "Sandero",
            "year": 2024,
            "plate_number": "12345-A-10",
            "mileage": 12500,
            "current_mileage_updated_at": "2026-06-10T23:54:32.000000Z",
            "seats": 5,
            "doors": 5,
            "daily_price": "350.00",
            "weekly_price": "2200.00",
            "monthly_price": "8500.00",
            "deposit_amount": "3000.00",
            "description": "Reliable economy vehicle for Dakhla rentals.",
            "is_featured": true,
            "is_active": true,
            "brand": {
                "id": 1,
                "name": "Dacia",
                "slug": "dacia"
            },
            "category": {
                "id": 1,
                "name": "Economy",
                "slug": "economy"
            },
            "status": {
                "id": 1,
                "name": "Available",
                "slug": "available"
            },
            "transmission_type": {
                "id": 1,
                "name": "Manual",
                "slug": "manual"
            },
            "fuel_type": {
                "id": 2,
                "name": "Diesel",
                "slug": "diesel"
            },
            "photos": [
                {
                    "id": 1,
                    "path": "vehicles/photos/sandero.jpg",
                    "alt_text": "Dacia Sandero front view",
                    "sort_order": 1,
                    "is_primary": true
                }
            ]
        },
        {
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
                "id": 1,
                "name": "Manual",
                "slug": "manual"
            },
            "fuel_type": {
                "id": 2,
                "name": "Diesel",
                "slug": "diesel"
            },
            "photos": [
                {
                    "id": 2,
                    "path": "vehicles/photos/postman.jpg",
                    "alt_text": "Front view",
                    "sort_order": 1,
                    "is_primary": true
                }
            ]
        }
    ],
    "links": {
        "first": "http://127.0.0.1:8000/api/public/vehicles?page=1",
        "last": "http://127.0.0.1:8000/api/public/vehicles?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "page": null,
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/public/vehicles?page=1",
                "label": "1",
                "page": 1,
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "page": null,
                "active": false
            }
        ],
        "path": "http://127.0.0.1:8000/api/public/vehicles",
        "per_page": 15,
        "to": 7,
        "total": 7
    }
}
```

**Error responses**

500 for unexpected server errors.

**Render in UI**

Vehicle card/table fields, nested `brand`, `category`, `status`, `transmission_type`, `fuel_type`, money fields, `photos`, `documents` on detail.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Usually none unless navigating to detail/edit screens.

**Notes**

Only vehicles with is_active=true are returned.

**Example call**

```javascript
const response = await fetch(`http://127.0.0.1:8000/api/public/vehicles`, {
  method: 'GET',
  headers: {
    Accept: 'application/json'
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 5. `GET` `/api/public/vehicles/{vehicle}/availability`

| Item | Value |
|------|-------|
| When to call | Checks availability for one active vehicle. |
| Auth | Public |
| Permission | `None` |

**Query params**

start_datetime: required date/datetime, for example 2026-08-01 10:00:00.
end_datetime: required date/datetime, must be after start_datetime.

**Path params**

vehicle: numeric vehicle ID.

**Request body**

None.

**Captured success response**

```json
{
    "vehicle_id": 1,
    "available": true
}
```

**Error responses**

404 if the vehicle does not exist or is inactive.
422 if start_datetime/end_datetime are missing, invalid, or end_datetime is before start_datetime.
500 for unexpected server errors.

**Render in UI**

Vehicle card/table fields, nested `brand`, `category`, `status`, `transmission_type`, `fuel_type`, money fields, `photos`, `documents` on detail.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Path parameter `vehicle` from route or list selection.

**Notes**

Availability checks confirmed and in_progress reservations as blocking reservations.

**Example call**

```javascript
const response = await fetch(`http://127.0.0.1:8000/api/public/vehicles/${vehicle}/availability`, {
  method: 'GET',
  headers: {
    Accept: 'application/json'
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 6. `GET` `/api/public/vehicles/{slug}`

| Item | Value |
|------|-------|
| When to call | Shows one active public vehicle by slug. |
| Auth | Public |
| Permission | `None` |

**Query params**

None.

**Path params**

slug: vehicle slug, for example dacia-sandero-2024.

**Request body**

None.

**Captured success response**

```json
{
    "data": {
        "id": 1,
        "name": "Dacia Sandero 2024",
        "slug": "dacia-sandero-2024",
        "model": "Sandero",
        "year": 2024,
        "plate_number": "12345-A-10",
        "mileage": 12500,
        "current_mileage_updated_at": "2026-06-10T23:54:32.000000Z",
        "seats": 5,
        "doors": 5,
        "daily_price": "350.00",
        "weekly_price": "2200.00",
        "monthly_price": "8500.00",
        "deposit_amount": "3000.00",
        "description": "Reliable economy vehicle for Dakhla rentals.",
        "is_featured": true,
        "is_active": true,
        "brand": {
            "id": 1,
            "name": "Dacia",
            "slug": "dacia"
        },
        "category": {
            "id": 1,
            "name": "Economy",
            "slug": "economy"
        },
        "status": {
            "id": 1,
            "name": "Available",
            "slug": "available"
        },
        "transmission_type": {
            "id": 1,
            "name": "Manual",
            "slug": "manual"
        },
        "fuel_type": {
            "id": 2,
            "name": "Diesel",
            "slug": "diesel"
        },
        "photos": [
            {
                "id": 1,
                "path": "vehicles/photos/sandero.jpg",
                "alt_text": "Dacia Sandero front view",
                "sort_order": 1,
                "is_primary": true
            }
        ]
    }
}
```

**Error responses**

404 if no active vehicle matches the slug.
500 for unexpected server errors.

**Render in UI**

Vehicle card/table fields, nested `brand`, `category`, `status`, `transmission_type`, `fuel_type`, money fields, `photos`, `documents` on detail.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Path parameter `slug` from route or list selection.

**Notes**

Inactive vehicles are hidden even if the slug exists.

**Example call**

```javascript
const response = await fetch(`http://127.0.0.1:8000/api/public/vehicles/${slug}`, {
  method: 'GET',
  headers: {
    Accept: 'application/json'
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 7. `POST` `/api/public/reservations`

| Item | Value |
|------|-------|
| When to call | Creates a pending public website reservation and a customer record. |
| Auth | Public |
| Permission | `None` |

**Query params**

None.

**Path params**

None.

**Request body**

```json
{"customer":{"full_name":"Postman Public Customer","nationality":"Moroccan","phone":"+212600000000","email":"postman.public@example.com","passport_or_cin":"PM123456","driving_license_number":"PM-DL-001"},"vehicle_id":1,"pickup_location_id":1,"dropoff_location_id":1,"start_datetime":"2026-08-01 10:00:00","end_datetime":"2026-08-05 10:00:00","customer_notes":"Airport pickup please."}
```

**Captured success response**

```json
{
    "data": {
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
            "seats": 5,
            "doors": 5,
            "daily_price": "350.00",
            "weekly_price": "2200.00",
            "monthly_price": "8500.00",
            "deposit_amount": "3000.00",
            "description": "Reliable economy vehicle for Dakhla rentals.",
            "is_featured": true,
            "is_active": true,
            "brand": {
                "id": 1,
                "name": "Dacia",
                "slug": "dacia"
            },
            "category": {
                "id": 1,
                "name": "Economy",
                "slug": "economy"
            },
            "status": {
                "id": 1,
                "name": "Available",
                "slug": "available"
            },
            "transmission_type": {
                "id": 1,
                "name": "Manual",
                "slug": "manual"
            },
            "fuel_type": {
                "id": 2,
                "name": "Diesel",
                "slug": "diesel"
            }
        },
        "source": {
            "id": 1,
            "name": "Website",
            "slug": "website"
        },
        "status": {
            "id": 1,
            "name": "Pending",
            "slug": "pending"
        },
        "payment_status": {
            "id": 1,
            "name": "Unpaid",
            "slug": "unpaid"
        },
        "pickup_location": {
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
        "dropoff_location": {
            "id": 2,
            "name": "Dakhla Airport",
            "slug": "dakhla-airport",
            "address": "Dakhla Airport, Morocco",
            "delivery_fee": "150.00",
            "is_active": true,
            "location_type": {
                "id": 2,
                "name": "Airport",
                "slug": "airport"
            },
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        },
        "start_datetime": "2026-12-01T10:00:00.000000Z",
        "end_datetime": "2026-12-05T10:00:00.000000Z",
        "total_days": 4,
        "price_per_day": "350.00",
        "delivery_fee": "150.00",
        "deposit_amount": "3000.00",
        "total_price": "4550.00",
        "customer_notes": "Airport pickup please.",
        "admin_notes": null,
        "confirmed_at": null,
        "started_at": null,
        "completed_at": null,
        "cancelled_at": null,
        "created_at": "2026-06-11T23:54:43.000000Z",
        "updated_at": "2026-06-11T23:54:43.000000Z"
    }
}
```

**Error responses**

422 for invalid customer fields, invalid IDs, invalid dates, or overlapping confirmed/in_progress reservations.
500 for unexpected server errors.

**Render in UI**

Reservation number, customer, vehicle, status, payment_status, dates, pricing totals, notes, lifecycle timestamps.

**Send from UI**

customer, vehicle_id, pickup_location_id, dropoff_location_id, start_datetime, end_datetime, customer_notes

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Store `data.id` returned by create endpoints for follow-up screens.

**Notes**

Public reservations always start as status=pending, payment_status=unpaid, source=website. Pending reservations do not reserve the vehicle.

**Example call**

```javascript
const payload = {"customer":{"full_name":"Postman Public Customer","nationality":"Moroccan","phone":"+212600000000","email":"postman.public@example.com","passport_or_cin":"PM123456","driving_license_number":"PM-DL-001"},"vehicle_id":1,"pickup_location_id":1,"dropoff_location_id":1,"start_datetime":"2026-08-01 10:00:00","end_datetime":"2026-08-05 10:00:00","customer_notes":"Airport pickup please."};

const response = await fetch(`http://127.0.0.1:8000/api/public/reservations`, {
  method: 'POST',
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(payload)
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 8. `POST` `/api/public/reservations/check-availability`

| Item | Value |
|------|-------|
| When to call | Checks availability from the public reservation form. |
| Auth | Public |
| Permission | `None` |

**Query params**

None.

**Path params**

None.

**Request body**

```json
{"vehicle_id":1,"start_datetime":"2026-08-01 10:00:00","end_datetime":"2026-08-05 10:00:00"}
```

**Captured success response**

```json
{
    "available": true
}
```

**Error responses**

422 for invalid vehicle_id, missing dates, or end_datetime before start_datetime.
500 for unexpected server errors.

**Render in UI**

Reservation number, customer, vehicle, status, payment_status, dates, pricing totals, notes, lifecycle timestamps.

**Send from UI**

vehicle_id, start_datetime, end_datetime

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Usually none unless navigating to detail/edit screens.

**Notes**

Only confirmed and in_progress reservations block availability.

**Example call**

```javascript
const payload = {"vehicle_id":1,"start_datetime":"2026-08-01 10:00:00","end_datetime":"2026-08-05 10:00:00"};

const response = await fetch(`http://127.0.0.1:8000/api/public/reservations/check-availability`, {
  method: 'POST',
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(payload)
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```


### Module: Auth

#### 9. `POST` `/api/admin/auth/login`

| Item | Value |
|------|-------|
| When to call | Authenticates an admin user and returns a Laravel Sanctum bearer token. |
| Auth | Public |
| Permission | `None` |

**Query params**

None.

**Path params**

None.

**Request body**

```json
{"email":"{{admin_email}}","password":"{{admin_password}}"}
```

**Captured success response**

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
                "id": 48,
                "module": "alerts",
                "name": "Update alerts",
                "slug": "alerts.update"
            },
            {
                "id": 46,
                "module": "alerts",
                "name": "View alerts",
                "slug": "alerts.view"
            },
            {
                "id": 54,
                "module": "audit_logs",
                "name": "View audit logs",
                "slug": "audit_logs.view"
            },
            {
                "id": 32,
                "module": "contracts",
                "name": "Generate contracts",
                "slug": "contracts.generate"
            },
            {
                "id": 33,
                "module": "contracts",
                "name": "Update contracts",
                "slug": "contracts.update"
            },
            {
                "id": 31,
                "module": "contracts",
                "name": "View contracts",
                "slug": "contracts.view"
            },
            {
                "id": 17,
                "module": "customers",
                "name": "Create customers",
                "slug": "customers.create"
            },
            {
                "id": 19,
                "module": "customers",
                "name": "Delete customers",
                "slug": "customers.delete"
            },
            {
                "id": 18,
                "module": "customers",
                "name": "Update customers",
                "slug": "customers.update"
            },
            {
                "id": 16,
                "module": "customers",
                "name": "View customers",
                "slug": "customers.view"
            },
            {
                "id": 1,
                "module": "dashboard",
                "name": "View dashboard",
                "slug": "dashboard.view"
            },
            {
                "id": 43,
                "module": "expenses",
                "name": "Create expenses",
                "slug": "expenses.create"
            },
            {
                "id": 45,
                "module": "expenses",
                "name": "Delete expenses",
                "slug": "expenses.delete"
            },
            {
                "id": 44,
                "module": "expenses",
                "name": "Update expenses",
                "slug": "expenses.update"
            },
            {
                "id": 42,
                "module": "expenses",
                "name": "View expenses",
                "slug": "expenses.view"
            },
            {
                "id": 35,
                "module": "locations",
                "name": "Create locations",
                "slug": "locations.create"
            },
            {
                "id": 37,
                "module": "locations",
                "name": "Delete locations",
                "slug": "locations.delete"
            },
            {
                "id": 36,
                "module": "locations",
                "name": "Update locations",
                "slug": "locations.update"
            },
            {
                "id": 34,
                "module": "locations",
                "name": "View locations",
                "slug": "locations.view"
            },
            {
                "id": 39,
                "module": "maintenance",
                "name": "Create maintenance",
                "slug": "maintenance.create"
            },
            {
                "id": 41,
                "module": "maintenance",
                "name": "Delete maintenance",
                "slug": "maintenance.delete"
            },
            {
                "id": 40,
                "module": "maintenance",
                "name": "Update maintenance",
                "slug": "maintenance.update"
            },
            {
                "id": 38,
                "module": "maintenance",
                "name": "View maintenance",
                "slug": "maintenance.view"
            },
            {
                "id": 30,
                "module": "payments",
                "name": "Manage payments",
                "slug": "payments.manage"
            },
            {
                "id": 29,
                "module": "payments",
                "name": "View payments",
                "slug": "payments.view"
            },
            {
                "id": 11,
                "module": "permissions",
                "name": "Assign permissions",
                "slug": "permissions.assign"
            },
            {
                "id": 10,
                "module": "permissions",
                "name": "View permissions",
                "slug": "permissions.view"
            },
            {
                "id": 27,
                "module": "reservations",
                "name": "Cancel reservations",
                "slug": "reservations.cancel"
            },
            {
                "id": 26,
                "module": "reservations",
                "name": "Complete reservations",
                "slug": "reservations.complete"
            },
            {
                "id": 24,
                "module": "reservations",
                "name": "Confirm reservations",
                "slug": "reservations.confirm"
            },
            {
                "id": 21,
                "module": "reservations",
                "name": "Create reservations",
                "slug": "reservations.create"
            },
            {
                "id": 23,
                "module": "reservations",
                "name": "Delete reservations",
                "slug": "reservations.delete"
            },
            {
                "id": 28,
                "module": "reservations",
                "name": "Reject reservations",
                "slug": "reservations.reject"
            },
            {
                "id": 25,
                "module": "reservations",
                "name": "Start reservations",
                "slug": "reservations.start"
            },
            {
                "id": 22,
                "module": "reservations",
                "name": "Update reservations",
                "slug": "reservations.update"
            },
            {
                "id": 20,
                "module": "reservations",
                "name": "View reservations",
                "slug": "reservations.view"
            },
            {
                "id": 7,
                "module": "roles",
                "name": "Create roles",
                "slug": "roles.create"
            },
            {
                "id": 9,
                "module": "roles",
                "name": "Delete roles",
                "slug": "roles.delete"
            },
            {
                "id": 8,
                "module": "roles",
                "name": "Update roles",
                "slug": "roles.update"
            },
            {
                "id": 6,
                "module": "roles",
                "name": "View roles",
                "slug": "roles.view"
            },
            {
                "id": 51,
                "module": "site_pages",
                "name": "Create site pages",
                "slug": "site_pages.create"
            },
            {
                "id": 53,
                "module": "site_pages",
                "name": "Delete site pages",
                "slug": "site_pages.delete"
            },
            {
                "id": 52,
                "module": "site_pages",
                "name": "Update site pages",
                "slug": "site_pages.update"
            },
            {
                "id": 50,
                "module": "site_pages",
                "name": "View site pages",
                "slug": "site_pages.view"
            },
            {
                "id": 3,
                "module": "users",
                "name": "Create users",
                "slug": "users.create"
            },
            {
                "id": 5,
                "module": "users",
                "name": "Delete users",
                "slug": "users.delete"
            },
            {
                "id": 4,
                "module": "users",
                "name": "Update users",
                "slug": "users.update"
            },
            {
                "id": 2,
                "module": "users",
                "name": "View users",
                "slug": "users.view"
            },
            {
                "id": 56,
                "module": "vehicle_brands",
                "name": "Create vehicle brands",
                "slug": "vehicle_brands.create"
            },
            {
                "id": 58,
                "module": "vehicle_brands",
                "name": "Delete vehicle brands",
                "slug": "vehicle_brands.delete"
            },
            {
                "id": 57,
                "module": "vehicle_brands",
                "name": "Update vehicle brands",
                "slug": "vehicle_brands.update"
            },
            {
                "id": 55,
                "module": "vehicle_brands",
                "name": "View vehicle brands",
                "slug": "vehicle_brands.view"
            },
            {
                "id": 60,
                "module": "vehicle_categories",
                "name": "Create vehicle categories",
                "slug": "vehicle_categories.create"
            },
            {
                "id": 62,
                "module": "vehicle_categories",
                "name": "Delete vehicle categories",
                "slug": "vehicle_categories.delete"
            },
            {
                "id": 61,
                "module": "vehicle_categories",
                "name": "Update vehicle categories",
                "slug": "vehicle_categories.update"
            },
            {
                "id": 59,
                "module": "vehicle_categories",
                "name": "View vehicle categories",
                "slug": "vehicle_categories.view"
            },
            {
                "id": 13,
                "module": "vehicles",
                "name": "Create vehicles",
                "slug": "vehicles.create"
            },
            {
                "id": 15,
                "module": "vehicles",
                "name": "Delete vehicles",
                "slug": "vehicles.delete"
            },
            {
                "id": 14,
                "module": "vehicles",
                "name": "Update vehicles",
                "slug": "vehicles.update"
            },
            {
                "id": 12,
                "module": "vehicles",
                "name": "View vehicles",
                "slug": "vehicles.view"
            }
        ]
    }
}
```

**Error responses**

422 for invalid credentials or inactive user.
500 for unexpected server errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

email, password

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Store `access_token` and user permissions from response.

**Notes**

Save access_token into {{admin_token}}. The token is a Sanctum personal access token, not Passport.

**Example call**

```javascript
const payload = {"email":"{{admin_email}}","password":"{{admin_password}}"};

const response = await fetch(`http://127.0.0.1:8000/api/admin/auth/login`, {
  method: 'POST',
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(payload)
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 10. `GET` `/api/admin/auth/me`

| Item | Value |
|------|-------|
| When to call | Returns the authenticated admin user with loaded roles and permissions. |
| Auth | Admin Bearer token |
| Permission | `Authenticated admin` |

**Query params**

None.

**Path params**

None.

**Request body**

None.

**Captured success response**

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
                "slug": "alerts.create"
            },
            {
                "id": 48,
                "module": "alerts",
                "name": "Update alerts",
                "slug": "alerts.update"
            },
            {
                "id": 46,
                "module": "alerts",
                "name": "View alerts",
                "slug": "alerts.view"
            },
            {
                "id": 54,
                "module": "audit_logs",
                "name": "View audit logs",
                "slug": "audit_logs.view"
            },
            {
                "id": 32,
                "module": "contracts",
                "name": "Generate contracts",
                "slug": "contracts.generate"
            },
            {
                "id": 33,
                "module": "contracts",
                "name": "Update contracts",
                "slug": "contracts.update"
            },
            {
                "id": 31,
                "module": "contracts",
                "name": "View contracts",
                "slug": "contracts.view"
            },
            {
                "id": 17,
                "module": "customers",
                "name": "Create customers",
                "slug": "customers.create"
            },
            {
                "id": 19,
                "module": "customers",
                "name": "Delete customers",
                "slug": "customers.delete"
            },
            {
                "id": 18,
                "module": "customers",
                "name": "Update customers",
                "slug": "customers.update"
            },
            {
                "id": 16,
                "module": "customers",
                "name": "View customers",
                "slug": "customers.view"
            },
            {
                "id": 1,
                "module": "dashboard",
                "name": "View dashboard",
                "slug": "dashboard.view"
            },
            {
                "id": 43,
                "module": "expenses",
                "name": "Create expenses",
                "slug": "expenses.create"
            },
            {
                "id": 45,
                "module": "expenses",
                "name": "Delete expenses",
                "slug": "expenses.delete"
            },
            {
                "id": 44,
                "module": "expenses",
                "name": "Update expenses",
                "slug": "expenses.update"
            },
            {
                "id": 42,
                "module": "expenses",
                "name": "View expenses",
                "slug": "expenses.view"
            },
            {
                "id": 35,
                "module": "locations",
                "name": "Create locations",
                "slug": "locations.create"
            },
            {
                "id": 37,
                "module": "locations",
                "name": "Delete locations",
                "slug": "locations.delete"
            },
            {
                "id": 36,
                "module": "locations",
                "name": "Update locations",
                "slug": "locations.update"
            },
            {
                "id": 34,
                "module": "locations",
                "name": "View locations",
                "slug": "locations.view"
            },
            {
                "id": 39,
                "module": "maintenance",
                "name": "Create maintenance",
                "slug": "maintenance.create"
            },
            {
                "id": 41,
                "module": "maintenance",
                "name": "Delete maintenance",
                "slug": "maintenance.delete"
            },
            {
                "id": 40,
                "module": "maintenance",
                "name": "Update maintenance",
                "slug": "maintenance.update"
            },
            {
                "id": 38,
                "module": "maintenance",
                "name": "View maintenance",
                "slug": "maintenance.view"
            },
            {
                "id": 30,
                "module": "payments",
                "name": "Manage payments",
                "slug": "payments.manage"
            },
            {
                "id": 29,
                "module": "payments",
                "name": "View payments",
                "slug": "payments.view"
            },
            {
                "id": 11,
                "module": "permissions",
                "name": "Assign permissions",
                "slug": "permissions.assign"
            },
            {
                "id": 10,
                "module": "permissions",
                "name": "View permissions",
                "slug": "permissions.view"
            },
            {
                "id": 27,
                "module": "reservations",
                "name": "Cancel reservations",
                "slug": "reservations.cancel"
            },
            {
                "id": 26,
                "module": "reservations",
                "name": "Complete reservations",
                "slug": "reservations.complete"
            },
            {
                "id": 24,
                "module": "reservations",
                "name": "Confirm reservations",
                "slug": "reservations.confirm"
            },
            {
                "id": 21,
                "module": "reservations",
                "name": "Create reservations",
                "slug": "reservations.create"
            },
            {
                "id": 23,
                "module": "reservations",
                "name": "Delete reservations",
                "slug": "reservations.delete"
            },
            {
                "id": 28,
                "module": "reservations",
                "name": "Reject reservations",
                "slug": "reservations.reject"
            },
            {
                "id": 25,
                "module": "reservations",
                "name": "Start reservations",
                "slug": "reservations.start"
            },
            {
                "id": 22,
                "module": "reservations",
                "name": "Update reservations",
                "slug": "reservations.update"
            },
            {
                "id": 20,
                "module": "reservations",
                "name": "View reservations",
                "slug": "reservations.view"
            },
            {
                "id": 7,
                "module": "roles",
                "name": "Create roles",
                "slug": "roles.create"
            },
            {
                "id": 9,
                "module": "roles",
                "name": "Delete roles",
                "slug": "roles.delete"
            },
            {
                "id": 8,
                "module": "roles",
                "name": "Update roles",
                "slug": "roles.update"
            },
            {
                "id": 6,
                "module": "roles",
                "name": "View roles",
                "slug": "roles.view"
            },
            {
                "id": 51,
                "module": "site_pages",
                "name": "Create site pages",
                "slug": "site_pages.create"
            },
            {
                "id": 53,
                "module": "site_pages",
                "name": "Delete site pages",
                "slug": "site_pages.delete"
            },
            {
                "id": 52,
                "module": "site_pages",
                "name": "Update site pages",
                "slug": "site_pages.update"
            },
            {
                "id": 50,
                "module": "site_pages",
                "name": "View site pages",
                "slug": "site_pages.view"
            },
            {
                "id": 3,
                "module": "users",
                "name": "Create users",
                "slug": "users.create"
            },
            {
                "id": 5,
                "module": "users",
                "name": "Delete users",
                "slug": "users.delete"
            },
            {
                "id": 4,
                "module": "users",
                "name": "Update users",
                "slug": "users.update"
            },
            {
                "id": 2,
                "module": "users",
                "name": "View users",
                "slug": "users.view"
            },
            {
                "id": 56,
                "module": "vehicle_brands",
                "name": "Create vehicle brands",
                "slug": "vehicle_brands.create"
            },
            {
                "id": 58,
                "module": "vehicle_brands",
                "name": "Delete vehicle brands",
                "slug": "vehicle_brands.delete"
            },
            {
                "id": 57,
                "module": "vehicle_brands",
                "name": "Update vehicle brands",
                "slug": "vehicle_brands.update"
            },
            {
                "id": 55,
                "module": "vehicle_brands",
                "name": "View vehicle brands",
                "slug": "vehicle_brands.view"
            },
            {
                "id": 60,
                "module": "vehicle_categories",
                "name": "Create vehicle categories",
                "slug": "vehicle_categories.create"
            },
            {
                "id": 62,
                "module": "vehicle_categories",
                "name": "Delete vehicle categories",
                "slug": "vehicle_categories.delete"
            },
            {
                "id": 61,
                "module": "vehicle_categories",
                "name": "Update vehicle categories",
                "slug": "vehicle_categories.update"
            },
            {
                "id": 59,
                "module": "vehicle_categories",
                "name": "View vehicle categories",
                "slug": "vehicle_categories.view"
            },
            {
                "id": 13,
                "module": "vehicles",
                "name": "Create vehicles",
                "slug": "vehicles.create"
            },
            {
                "id": 15,
                "module": "vehicles",
                "name": "Delete vehicles",
                "slug": "vehicles.delete"
            },
            {
                "id": 14,
                "module": "vehicles",
                "name": "Update vehicles",
                "slug": "vehicles.update"
            },
            {
                "id": 12,
                "module": "vehicles",
                "name": "View vehicles",
                "slug": "vehicles.view"
            }
        ]
    }
}
```

**Error responses**

401 if the token is missing, invalid, or revoked.
500 for unexpected server errors.

**Render in UI**

`user.name`, `user.email`, `user.roles`, `user.permissions` for menu gating.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Usually none unless navigating to detail/edit screens.

**Notes**

Use this endpoint after login to confirm the token and permissions.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/auth/me`, {
  method: 'GET',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 11. `POST` `/api/admin/auth/logout`

| Item | Value |
|------|-------|
| When to call | Revokes the current Sanctum token. |
| Auth | Admin Bearer token |
| Permission | `Authenticated admin` |

**Query params**

None.

**Path params**

None.

**Request body**

None.

**Captured success response**

```json
{
    "message": "Logged out successfully."
}
```

**Error responses**

401 if the token is missing, invalid, or already revoked.
500 for unexpected server errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

None.

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Store `data.id` returned by create endpoints for follow-up screens.

**Notes**

After logout, the old token should no longer access admin routes.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/auth/logout`, {
  method: 'POST',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```


### Module: Lookups (admin)

#### 12. `GET` `/api/admin/lookups`

| Item | Value |
|------|-------|
| When to call | Returns all lookup data needed by admin forms. |
| Auth | Admin Bearer token |
| Permission | `Authenticated admin` |

**Query params**

None.

**Path params**

None.

**Request body**

None.

**Captured success response**

```json
{
    "vehicle_statuses": [
        {
            "id": 1,
            "name": "Available",
            "slug": "available"
        },
        {
            "id": 4,
            "name": "Maintenance",
            "slug": "maintenance"
        },
        {
            "id": 6,
            "name": "Out of Service",
            "slug": "out_of_service"
        },
        {
            "id": 3,
            "name": "Rented",
            "slug": "rented"
        },
        {
            "id": 5,
            "name": "Repair",
            "slug": "repair"
        },
        {
            "id": 2,
            "name": "Reserved",
            "slug": "reserved"
        },
        {
            "id": 7,
            "name": "Sold",
            "slug": "sold"
        }
    ],
    "transmission_types": [
        {
            "id": 2,
            "name": "Automatic",
            "slug": "automatic"
        },
        {
            "id": 1,
            "name": "Manual",
            "slug": "manual"
        }
    ],
    "fuel_types": [
        {
            "id": 2,
            "name": "Diesel",
            "slug": "diesel"
        },
        {
            "id": 4,
            "name": "Electric",
            "slug": "electric"
        },
        {
            "id": 1,
            "name": "Gasoline",
            "slug": "gasoline"
        },
        {
            "id": 3,
            "name": "Hybrid",
            "slug": "hybrid"
        }
    ],
    "reservation_statuses": [
        {
            "id": 5,
            "name": "Cancelled",
            "slug": "cancelled"
        },
        {
            "id": 4,
            "name": "Completed",
            "slug": "completed"
        },
        {
            "id": 2,
            "name": "Confirmed",
            "slug": "confirmed"
        },
        {
            "id": 3,
            "name": "In Progress",
            "slug": "in_progress"
        },
        {
            "id": 1,
            "name": "Pending",
            "slug": "pending"
        },
        {
            "id": 6,
            "name": "Rejected",
            "slug": "rejected"
        }
    ],
    "payment_statuses": [
        {
            "id": 4,
            "name": "Cancelled",
            "slug": "cancelled"
        },
        {
            "id": 5,
            "name": "Failed",
            "slug": "failed"
        },
        {
            "id": 3,
            "name": "Paid",
            "slug": "paid"
        },
        {
            "id": 2,
            "name": "Partial Paid",
            "slug": "partial_paid"
        },
        {
            "id": 6,
            "name": "Refunded",
            "slug": "refunded"
        },
        {
            "id": 1,
            "name": "Unpaid",
            "slug": "unpaid"
        }
    ],
    "payment_methods": [
        {
            "id": 2,
            "name": "Bank Transfer",
            "slug": "bank_transfer"
        },
        {
            "id": 1,
            "name": "Cash",
            "slug": "cash"
        },
        {
            "id": 5,
            "name": "Check",
            "slug": "check"
        },
        {
            "id": 3,
            "name": "Credit Card",
            "slug": "credit_card"
        },
        {
            "id": 4,
            "name": "Debit Card",
            "slug": "debit_card"
        },
        {
            "id": 6,
            "name": "Online",
            "slug": "online"
        }
    ],
    "payment_types": [
        {
            "id": 5,
            "name": "Refund",
            "slug": "refund"
        },
        {
            "id": 3,
            "name": "Remaining Balance",
            "slug": "remaining_balance"
        },
        {
            "id": 2,
            "name": "Rental Payment",
            "slug": "rental_payment"
        },
        {
            "id": 1,
            "name": "Reservation Deposit",
            "slug": "reservation_deposit"
        },
        {
            "id": 4,
            "name": "Security Deposit",
            "slug": "security_deposit"
        }
    ],
    "reservation_sources": [
        {
            "id": 5,
            "name": "Admin",
            "slug": "admin"
        },
        {
            "id": 6,
            "name": "Admin Manual",
            "slug": "admin_manual"
        },
        {
            "id": 7,
            "name": "Partner",
            "slug": "partner"
        },
        {
            "id": 2,
            "name": "Phone",
            "slug": "phone"
        },
        {
            "id": 4,
            "name": "Walk In",
            "slug": "walk_in"
        },
        {
            "id": 1,
            "name": "Website",
            "slug": "website"
        },
        {
            "id": 3,
            "name": "WhatsApp",
            "slug": "whatsapp"
        }
    ],
    "location_types": [
        {
            "id": 1,
            "name": "Agency",
            "slug": "agency"
        },
        {
            "id": 2,
            "name": "Airport",
            "slug": "airport"
        },
        {
            "id": 4,
            "name": "City Center",
            "slug": "city_center"
        },
        {
            "id": 5,
            "name": "Custom",
            "slug": "custom"
        },
        {
            "id": 3,
            "name": "Hotel",
            "slug": "hotel"
        }
    ],
    "maintenance_types": [
        {
            "id": 3,
            "name": "Brakes",
            "slug": "brakes"
        },
        {
            "id": 6,
            "name": "Cleaning",
            "slug": "cleaning"
        },
        {
            "id": 4,
            "name": "Inspection",
            "slug": "inspection"
        },
        {
            "id": 1,
            "name": "Oil Change",
            "slug": "oil_change"
        },
        {
            "id": 7,
            "name": "Other",
            "slug": "other"
        },
        {
            "id": 5,
            "name": "Repair",
            "slug": "repair"
        },
        {
            "id": 2,
            "name": "Tires",
            "slug": "tires"
        }
    ],
    "expense_categories": [
        {
            "id": 4,
            "name": "Cleaning",
            "slug": "cleaning"
        },
        {
            "id": 2,
            "name": "Fuel",
            "slug": "fuel"
        },
        {
            "id": 3,
            "name": "Insurance",
            "slug": "insurance"
        },
        {
            "id": 1,
            "name": "Maintenance",
            "slug": "maintenance"
        },
        {
            "id": 8,
            "name": "Other",
            "slug": "other"
        },
        {
            "id": 5,
            "name": "Parking",
            "slug": "parking"
        },
        {
            "id": 7,
            "name": "Taxes",
            "slug": "taxes"
        },
        {
            "id": 6,
            "name": "Tolls",
            "slug": "tolls"
        }
    ],
    "alert_types": [
        {
            "id": 2,
            "name": "Document Expiry",
            "slug": "document_expiry"
        },
        {
            "id": 3,
            "name": "Insurance Expiry",
            "slug": "insurance_expiry"
        },
        {
            "id": 1,
            "name": "Maintenance Due",
            "slug": "maintenance_due"
        },
        {
            "id": 7,
            "name": "Other",
            "slug": "other"
        },
        {
            "id": 4,
            "name": "Payment Due",
            "slug": "payment_due"
        },
        {
            "id": 5,
            "name": "Reservation Follow Up",
            "slug": "reservation_follow_up"
        },
        {
            "id": 6,
            "name": "Vehicle Status",
            "slug": "vehicle_status"
        }
    ],
    "alert_statuses": [
        {
            "id": 3,
            "name": "Done",
            "slug": "done"
        },
        {
            "id": 4,
            "name": "Ignored",
            "slug": "ignored"
        },
        {
            "id": 1,
            "name": "Pending",
            "slug": "pending"
        },
        {
            "id": 2,
            "name": "Seen",
            "slug": "seen"
        }
    ],
    "document_types": [
        {
            "id": 2,
            "name": "CIN",
            "slug": "cin"
        },
        {
            "id": 7,
            "name": "Contract",
            "slug": "contract"
        },
        {
            "id": 3,
            "name": "Driving License",
            "slug": "driving_license"
        },
        {
            "id": 5,
            "name": "Insurance",
            "slug": "insurance"
        },
        {
            "id": 8,
            "name": "Invoice",
            "slug": "invoice"
        },
        {
            "id": 9,
            "name": "Other",
            "slug": "other"
        },
        {
            "id": 1,
            "name": "Passport",
            "slug": "passport"
        },
        {
            "id": 6,
            "name": "Technical Inspection",
            "slug": "technical_inspection"
        },
        {
            "id": 4,
            "name": "Vehicle Registration",
            "slug": "vehicle_registration"
        }
    ],
    "contract_statuses": [
        {
            "id": 4,
            "name": "Cancelled",
            "slug": "cancelled"
        },
        {
            "id": 1,
            "name": "Draft",
            "slug": "draft"
        },
        {
            "id": 2,
            "name": "Generated",
            "slug": "generated"
        },
        {
            "id": 3,
            "name": "Signed",
            "slug": "signed"
        }
    ],
    "vehicle_brands": [
        {
            "id": 1,
            "name": "Dacia",
            "slug": "dacia",
            "is_active": true
        },
        {
            "id": 3,
            "name": "Hyundai",
            "slug": "hyundai",
            "is_active": true
        },
        {
            "id": 6,
            "name": "Peugeot",
            "slug": "peugeot",
            "is_active": true
        },
        {
            "id": 7,
            "name": "Postman Brand",
            "slug": "postman-brand",
            "is_active": true
        },
        {
            "id": 2,
            "name": "Renault",
            "slug": "renault",
            "is_active": true
        },
        {
            "id": 4,
            "name": "Toyota",
            "slug": "toyota",
            "is_active": true
        },
        {
            "id": 5,
            "name": "Volkswagen",
            "slug": "volkswagen",
            "is_active": true
        }
    ],
    "vehicle_categories": [
        {
            "id": 2,
            "name": "Compact",
            "slug": "compact",
            "description": "Compact vehicles suitable for couples and small families.",
            "is_active": true
        },
        {
            "id": 1,
            "name": "Economy",
            "slug": "economy",
            "description": "Budget-friendly city cars for daily rentals.",
            "is_active": true
        },
        {
            "id": 4,
            "name": "Luxury",
            "slug": "luxury",
            "description": "Premium vehicles for business and comfort.",
            "is_active": true
        },
        {
            "id": 6,
            "name": "Postman Category",
            "slug": "postman-category",
            "description": "QA category for API testing.",
            "is_active": true
        },
        {
            "id": 3,
            "name": "SUV",
            "slug": "suv",
            "description": "SUV vehicles suitable for Dakhla roads and longer trips.",
            "is_active": true
        },
        {
            "id": 5,
            "name": "Van",
            "slug": "van",
            "description": "Spacious vans for groups and airport transfers.",
            "is_active": true
        }
    ],
    "locations": [
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
            }
        },
        {
            "id": 2,
            "name": "Dakhla Airport",
            "slug": "dakhla-airport",
            "address": "Dakhla Airport, Morocco",
            "delivery_fee": "150.00",
            "is_active": true,
            "location_type": {
                "id": 2,
                "name": "Airport",
                "slug": "airport"
            }
        },
        {
            "id": 3,
            "name": "Postman Location",
            "slug": "postman-location",
            "address": "Postman Street, Dakhla",
            "delivery_fee": "100.00",
            "is_active": true,
            "location_type": {
                "id": 1,
                "name": "Agency",
                "slug": "agency"
            }
        }
    ]
}
```

**Error responses**

401 if unauthenticated.
500 if lookup tables are unavailable.

**Render in UI**

Lookup lists for select inputs (`slug` as value, `name` as label).

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Usually none unless navigating to detail/edit screens.

**Notes**

Forms should use slugs when APIs ask for *_slug fields.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/lookups`, {
  method: 'GET',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```


### Module: Dashboard

#### 13. `GET` `/api/admin/dashboard/statistics`

| Item | Value |
|------|-------|
| When to call | Returns global dashboard KPIs for the requested month. |
| Auth | Admin Bearer token |
| Permission | `dashboard.view` |

**Query params**

year: optional integer from 2000 to 2100.
month: optional integer from 1 to 12.

**Path params**

None.

**Request body**

None.

**Captured success response**

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

**Error responses**

401 if unauthenticated.
403 without dashboard.view.
422 for invalid year/month.
500 for unexpected server errors.

**Render in UI**

KPI numbers, grouped chart arrays, date range labels.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Usually none unless navigating to detail/edit screens.

**Notes**

Revenue counts only paid payments.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/dashboard/statistics`, {
  method: 'GET',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 14. `GET` `/api/admin/dashboard/revenue`

| Item | Value |
|------|-------|
| When to call | Returns revenue totals and grouped revenue. |
| Auth | Admin Bearer token |
| Permission | `dashboard.view` |

**Query params**

start_date: optional date.
end_date: optional date, must be after or equal to start_date.
group_by: optional string, day or month.

**Path params**

None.

**Request body**

None.

**Captured success response**

```json
{
    "daily_revenue": 0,
    "monthly_revenue": 1000,
    "yearly_revenue": 1000,
    "date_range_revenue": 1000,
    "date_range": {
        "start_date": "2026-06-01",
        "end_date": "2026-06-30"
    },
    "group_by": "day",
    "grouped_revenue": [
        {
            "period": "2026-06-10",
            "total_amount": 1000,
            "payment_count": 1
        }
    ]
}
```

**Error responses**

401 if unauthenticated.
403 without dashboard.view.
422 for invalid dates or group_by.
500 for unexpected server errors.

**Render in UI**

KPI numbers, grouped chart arrays, date range labels.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Usually none unless navigating to detail/edit screens.

**Notes**

Only payments with payment_status slug paid are included.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/dashboard/revenue`, {
  method: 'GET',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 15. `GET` `/api/admin/dashboard/expenses`

| Item | Value |
|------|-------|
| When to call | Returns expense totals, grouped expenses, category totals, and vehicle totals. |
| Auth | Admin Bearer token |
| Permission | `dashboard.view` |

**Query params**

start_date: optional date.
end_date: optional date, must be after or equal to start_date.
group_by: optional string, day or month.

**Path params**

None.

**Request body**

None.

**Captured success response**

```json
{
    "monthly_expenses": 1150,
    "date_range_expenses": 1150,
    "date_range": {
        "start_date": "2026-06-01",
        "end_date": "2026-06-30"
    },
    "group_by": "month",
    "grouped_expenses": [
        {
            "period": "2026-06",
            "total_amount": 1150,
            "expense_count": 3
        }
    ],
    "expenses_by_category": [
        {
            "slug": "maintenance",
            "name": "Maintenance",
            "total_amount": 900,
            "expense_count": 2
        },
        {
            "slug": "fuel",
            "name": "Fuel",
            "total_amount": 250,
            "expense_count": 1
        }
    ],
    "expenses_by_vehicle": [
        {
            "id": 2,
            "name": "Postman Test Vehicle",
            "plate_number": "PM-100-TEST",
            "total_amount": 1150,
            "expense_count": 3
        }
    ]
}
```

**Error responses**

401 if unauthenticated.
403 without dashboard.view.
422 for invalid dates or group_by.
500 for unexpected server errors.

**Render in UI**

KPI numbers, grouped chart arrays, date range labels.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Usually none unless navigating to detail/edit screens.

**Notes**

Expenses without a vehicle are grouped under General in expenses_by_vehicle.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/dashboard/expenses`, {
  method: 'GET',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```


### Module: Brands

#### 16. `GET` `/api/admin/vehicle-brands`

| Item | Value |
|------|-------|
| When to call | Lists vehicle brands. |
| Auth | Admin Bearer token |
| Permission | `vehicle_brands.view` |

**Query params**

page: optional page number.

**Path params**

None.

**Request body**

None.

**Captured success response**

```json
{
    "data": [
        {
            "id": 1,
            "name": "Dacia",
            "slug": "dacia",
            "is_active": true,
            "created_at": "2026-06-10T20:03:13.000000Z",
            "updated_at": "2026-06-10T20:03:13.000000Z"
        },
        {
            "id": 3,
            "name": "Hyundai",
            "slug": "hyundai",
            "is_active": true,
            "created_at": "2026-06-10T20:03:13.000000Z",
            "updated_at": "2026-06-10T20:03:13.000000Z"
        },
        {
            "id": 6,
            "name": "Peugeot",
            "slug": "peugeot",
            "is_active": true,
            "created_at": "2026-06-10T20:03:13.000000Z",
            "updated_at": "2026-06-10T20:03:13.000000Z"
        },
        {
            "id": 7,
            "name": "Postman Brand",
            "slug": "postman-brand",
            "is_active": true,
            "created_at": "2026-06-11T23:05:31.000000Z",
            "updated_at": "2026-06-11T23:05:31.000000Z"
        },
        {
            "id": 2,
            "name": "Renault",
            "slug": "renault",
            "is_active": true,
            "created_at": "2026-06-10T20:03:13.000000Z",
            "updated_at": "2026-06-10T20:03:13.000000Z"
        },
        {
            "id": 4,
            "name": "Toyota",
            "slug": "toyota",
            "is_active": true,
            "created_at": "2026-06-10T20:03:13.000000Z",
            "updated_at": "2026-06-10T20:03:13.000000Z"
        },
        {
            "id": 5,
            "name": "Volkswagen",
            "slug": "volkswagen",
            "is_active": true,
            "created_at": "2026-06-10T20:03:13.000000Z",
            "updated_at": "2026-06-10T20:03:13.000000Z"
        }
    ],
    "links": {
        "first": "http://127.0.0.1:8000/api/admin/vehicle-brands?page=1",
        "last": "http://127.0.0.1:8000/api/admin/vehicle-brands?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "page": null,
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/admin/vehicle-brands?page=1",
                "label": "1",
                "page": 1,
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "page": null,
                "active": false
            }
        ],
        "path": "http://127.0.0.1:8000/api/admin/vehicle-brands",
        "per_page": 15,
        "to": 7,
        "total": 7
    }
}
```

**Error responses**

401 if unauthenticated.
403 without vehicle_brands.view.
500 for unexpected server errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Usually none unless navigating to detail/edit screens.

**Notes**

Sorted by name.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/vehicle-brands`, {
  method: 'GET',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 17. `POST` `/api/admin/vehicle-brands`

| Item | Value |
|------|-------|
| When to call | Creates a vehicle brand. |
| Auth | Admin Bearer token |
| Permission | `vehicle_brands.create` |

**Query params**

None.

**Path params**

None.

**Request body**

```json
{"name":"Postman Brand","slug":"postman-brand","is_active":true}
```

**Captured success response**

```json
{
    "data": {
        "id": 10,
        "name": "QA Disposable Brand 1781222073",
        "slug": "qa-disposable-brand-1781222073",
        "is_active": true,
        "created_at": "2026-06-11T23:54:50.000000Z",
        "updated_at": "2026-06-11T23:54:50.000000Z"
    }
}
```

**Error responses**

401 if unauthenticated.
403 without vehicle_brands.create.
422 for missing name/slug or duplicate slug.
500 for unexpected server errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

name, slug, is_active

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Store `data.id` returned by create endpoints for follow-up screens.

**Notes**

Save data.id as vehicle_brand_id.

**Example call**

```javascript
const payload = {"name":"Postman Brand","slug":"postman-brand","is_active":true};

const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/vehicle-brands`, {
  method: 'POST',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {}),
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(payload)
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 18. `GET` `/api/admin/vehicle-brands/{brand}`

| Item | Value |
|------|-------|
| When to call | Shows one vehicle brand. |
| Auth | Admin Bearer token |
| Permission | `vehicle_brands.view` |

**Query params**

None.

**Path params**

brand: numeric brand ID.

**Request body**

None.

**Captured success response**

```json
{
    "data": {
        "id": 10,
        "name": "QA Disposable Brand 1781222073",
        "slug": "qa-disposable-brand-1781222073",
        "is_active": true,
        "created_at": "2026-06-11T23:54:50.000000Z",
        "updated_at": "2026-06-11T23:54:50.000000Z"
    }
}
```

**Error responses**

401 if unauthenticated.
403 without vehicle_brands.view.
404 for invalid brand ID.
500 for unexpected server errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Path parameter `brand` from route or list selection.

**Notes**

Soft-deleted brands are not resolved by route model binding.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/vehicle-brands/${brand}`, {
  method: 'GET',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 19. `PUT` `/api/admin/vehicle-brands/{brand}`

| Item | Value |
|------|-------|
| When to call | Updates a vehicle brand. |
| Auth | Admin Bearer token |
| Permission | `vehicle_brands.update` |

**Query params**

None.

**Path params**

brand: numeric brand ID.

**Request body**

```json
{"name":"Postman Brand Updated","slug":"postman-brand-updated","is_active":true}
```

**Captured success response**

```json
{
    "data": {
        "id": 10,
        "name": "QA Disposable Brand Updated",
        "slug": "qa-disposable-brand-1781222073",
        "is_active": true,
        "created_at": "2026-06-11T23:54:50.000000Z",
        "updated_at": "2026-06-11T23:54:51.000000Z"
    }
}
```

**Error responses**

401 if unauthenticated.
403 without vehicle_brands.update.
404 for invalid brand ID.
422 for duplicate slug.
500 for unexpected server errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

name, slug, is_active

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Path parameter `brand` from route or list selection.

**Notes**

The same controller method is used for PUT and PATCH.

**Example call**

```javascript
const payload = {"name":"Postman Brand Updated","slug":"postman-brand-updated","is_active":true};

const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/vehicle-brands/${brand}`, {
  method: 'PUT',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {}),
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(payload)
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 20. `PATCH` `/api/admin/vehicle-brands/{brand}`

| Item | Value |
|------|-------|
| When to call | Partially updates a vehicle brand. |
| Auth | Admin Bearer token |
| Permission | `vehicle_brands.update` |

**Query params**

None.

**Path params**

brand: numeric brand ID.

**Request body**

```json
{"is_active":false}
```

**Captured success response**

```json
{
    "data": {
        "id": 10,
        "name": "QA Disposable Brand Updated",
        "slug": "qa-disposable-brand-1781222073",
        "is_active": false,
        "created_at": "2026-06-11T23:54:50.000000Z",
        "updated_at": "2026-06-11T23:54:52.000000Z"
    }
}
```

**Error responses**

401 if unauthenticated.
403 without vehicle_brands.update.
404 for invalid brand ID.
422 for invalid field types.
500 for unexpected server errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

is_active

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Path parameter `brand` from route or list selection.

**Notes**

Partial updates can toggle public availability.

**Example call**

```javascript
const payload = {"is_active":false};

const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/vehicle-brands/${brand}`, {
  method: 'PATCH',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {}),
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(payload)
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 21. `DELETE` `/api/admin/vehicle-brands/{brand}`

| Item | Value |
|------|-------|
| When to call | Soft deletes a vehicle brand. |
| Auth | Admin Bearer token |
| Permission | `vehicle_brands.delete` |

**Query params**

None.

**Path params**

brand: numeric brand ID.

**Request body**

None.

**Captured success response**

```json
HTTP 204 No Content — empty body.
```

**Error responses**

401 if unauthenticated.
403 without vehicle_brands.delete.
404 for invalid brand ID.
500 if database constraints prevent deletion.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Path parameter `brand` from route or list selection.

**Notes**

Do not delete brands referenced by vehicles during the main happy-path test.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/vehicle-brands/${brand}`, {
  method: 'DELETE',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```


### Module: Categories

#### 22. `GET` `/api/admin/vehicle-categories`

| Item | Value |
|------|-------|
| When to call | Lists vehicle categories. |
| Auth | Admin Bearer token |
| Permission | `vehicle_categories.view` |

**Query params**

page: optional page number.

**Path params**

None.

**Request body**

None.

**Captured success response**

```json
{
    "data": [
        {
            "id": 2,
            "name": "Compact",
            "slug": "compact",
            "description": "Compact vehicles suitable for couples and small families.",
            "is_active": true,
            "created_at": "2026-06-10T20:03:13.000000Z",
            "updated_at": "2026-06-10T20:03:13.000000Z"
        },
        {
            "id": 1,
            "name": "Economy",
            "slug": "economy",
            "description": "Budget-friendly city cars for daily rentals.",
            "is_active": true,
            "created_at": "2026-06-10T20:03:13.000000Z",
            "updated_at": "2026-06-10T20:03:13.000000Z"
        },
        {
            "id": 4,
            "name": "Luxury",
            "slug": "luxury",
            "description": "Premium vehicles for business and comfort.",
            "is_active": true,
            "created_at": "2026-06-10T20:03:13.000000Z",
            "updated_at": "2026-06-10T20:03:13.000000Z"
        },
        {
            "id": 6,
            "name": "Postman Category",
            "slug": "postman-category",
            "description": "QA category for API testing.",
            "is_active": true,
            "created_at": "2026-06-11T23:05:31.000000Z",
            "updated_at": "2026-06-11T23:05:31.000000Z"
        },
        {
            "id": 3,
            "name": "SUV",
            "slug": "suv",
            "description": "SUV vehicles suitable for Dakhla roads and longer trips.",
            "is_active": true,
            "created_at": "2026-06-10T20:03:13.000000Z",
            "updated_at": "2026-06-10T20:03:13.000000Z"
        },
        {
            "id": 5,
            "name": "Van",
            "slug": "van",
            "description": "Spacious vans for groups and airport transfers.",
            "is_active": true,
            "created_at": "2026-06-10T20:03:13.000000Z",
            "updated_at": "2026-06-10T20:03:13.000000Z"
        }
    ],
    "links": {
        "first": "http://127.0.0.1:8000/api/admin/vehicle-categories?page=1",
        "last": "http://127.0.0.1:8000/api/admin/vehicle-categories?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "page": null,
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/admin/vehicle-categories?page=1",
                "label": "1",
                "page": 1,
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "page": null,
                "active": false
            }
        ],
        "path": "http://127.0.0.1:8000/api/admin/vehicle-categories",
        "per_page": 15,
        "to": 6,
        "total": 6
    }
}
```

**Error responses**

401 if unauthenticated.
403 without vehicle_categories.view.
500 for unexpected server errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Usually none unless navigating to detail/edit screens.

**Notes**

Sorted by name.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/vehicle-categories`, {
  method: 'GET',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 23. `POST` `/api/admin/vehicle-categories`

| Item | Value |
|------|-------|
| When to call | Creates a vehicle category. |
| Auth | Admin Bearer token |
| Permission | `vehicle_categories.create` |

**Query params**

None.

**Path params**

None.

**Request body**

```json
{"name":"Postman Category","slug":"postman-category","description":"Created from Postman QA.","is_active":true}
```

**Captured success response**

```json
{
    "data": {
        "id": 9,
        "name": "QA Disposable Category 1781222073",
        "slug": "qa-disposable-category-1781222073",
        "description": "Disposable QA category.",
        "is_active": true,
        "created_at": "2026-06-11T23:54:54.000000Z",
        "updated_at": "2026-06-11T23:54:54.000000Z"
    }
}
```

**Error responses**

401 if unauthenticated.
403 without vehicle_categories.create.
422 for missing name/slug or duplicate slug.
500 for unexpected server errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

name, slug, description, is_active

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Store `data.id` returned by create endpoints for follow-up screens.

**Notes**

Save data.id as vehicle_category_id.

**Example call**

```javascript
const payload = {"name":"Postman Category","slug":"postman-category","description":"Created from Postman QA.","is_active":true};

const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/vehicle-categories`, {
  method: 'POST',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {}),
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(payload)
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 24. `GET` `/api/admin/vehicle-categories/{category}`

| Item | Value |
|------|-------|
| When to call | Shows one vehicle category. |
| Auth | Admin Bearer token |
| Permission | `vehicle_categories.view` |

**Query params**

None.

**Path params**

category: numeric category ID.

**Request body**

None.

**Captured success response**

```json
{
    "data": {
        "id": 9,
        "name": "QA Disposable Category 1781222073",
        "slug": "qa-disposable-category-1781222073",
        "description": "Disposable QA category.",
        "is_active": true,
        "created_at": "2026-06-11T23:54:54.000000Z",
        "updated_at": "2026-06-11T23:54:54.000000Z"
    }
}
```

**Error responses**

401 if unauthenticated.
403 without vehicle_categories.view.
404 for invalid category ID.
500 for unexpected server errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Path parameter `category` from route or list selection.

**Notes**

Soft-deleted categories are not resolved by route model binding.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/vehicle-categories/${category}`, {
  method: 'GET',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 25. `PUT` `/api/admin/vehicle-categories/{category}`

| Item | Value |
|------|-------|
| When to call | Updates a vehicle category. |
| Auth | Admin Bearer token |
| Permission | `vehicle_categories.update` |

**Query params**

None.

**Path params**

category: numeric category ID.

**Request body**

```json
{"name":"Postman Category Updated","slug":"postman-category-updated","description":"Updated from Postman QA.","is_active":true}
```

**Captured success response**

```json
{
    "data": {
        "id": 9,
        "name": "QA Disposable Category Updated",
        "slug": "qa-disposable-category-1781222073",
        "description": "Updated disposable QA category.",
        "is_active": true,
        "created_at": "2026-06-11T23:54:54.000000Z",
        "updated_at": "2026-06-11T23:54:55.000000Z"
    }
}
```

**Error responses**

401 if unauthenticated.
403 without vehicle_categories.update.
404 for invalid category ID.
422 for duplicate slug.
500 for unexpected server errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

name, slug, description, is_active

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Path parameter `category` from route or list selection.

**Notes**

The same controller method is used for PUT and PATCH.

**Example call**

```javascript
const payload = {"name":"Postman Category Updated","slug":"postman-category-updated","description":"Updated from Postman QA.","is_active":true};

const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/vehicle-categories/${category}`, {
  method: 'PUT',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {}),
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(payload)
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 26. `PATCH` `/api/admin/vehicle-categories/{category}`

| Item | Value |
|------|-------|
| When to call | Partially updates a vehicle category. |
| Auth | Admin Bearer token |
| Permission | `vehicle_categories.update` |

**Query params**

None.

**Path params**

category: numeric category ID.

**Request body**

```json
{"description":"Partial update from Postman."}
```

**Captured success response**

```json
{
    "data": {
        "id": 9,
        "name": "QA Disposable Category Updated",
        "slug": "qa-disposable-category-1781222073",
        "description": "Updated disposable QA category.",
        "is_active": false,
        "created_at": "2026-06-11T23:54:54.000000Z",
        "updated_at": "2026-06-11T23:54:56.000000Z"
    }
}
```

**Error responses**

401 if unauthenticated.
403 without vehicle_categories.update.
404 for invalid category ID.
422 for invalid field types.
500 for unexpected server errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

description

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Path parameter `category` from route or list selection.

**Notes**

Partial updates are useful for description or is_active toggles.

**Example call**

```javascript
const payload = {"description":"Partial update from Postman."};

const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/vehicle-categories/${category}`, {
  method: 'PATCH',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {}),
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(payload)
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 27. `DELETE` `/api/admin/vehicle-categories/{category}`

| Item | Value |
|------|-------|
| When to call | Soft deletes a vehicle category. |
| Auth | Admin Bearer token |
| Permission | `vehicle_categories.delete` |

**Query params**

None.

**Path params**

category: numeric category ID.

**Request body**

None.

**Captured success response**

```json
HTTP 204 No Content — empty body.
```

**Error responses**

401 if unauthenticated.
403 without vehicle_categories.delete.
404 for invalid category ID.
500 if database constraints prevent deletion.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Path parameter `category` from route or list selection.

**Notes**

Do not delete categories referenced by vehicles during the main happy-path test.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/vehicle-categories/${category}`, {
  method: 'DELETE',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```


### Module: Vehicles

#### 28. `GET` `/api/admin/vehicles`

| Item | Value |
|------|-------|
| When to call | Lists admin vehicle inventory. |
| Auth | Admin Bearer token |
| Permission | `vehicles.view` |

**Query params**

page: optional page number.

**Path params**

None.

**Request body**

None.

**Captured success response**

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
            "status": {
                "id": 1,
                "name": "Available",
                "slug": "available"
            },
            "transmission_type": {
                "id": 1,
                "name": "Manual",
                "slug": "manual"
            },
            "fuel_type": {
                "id": 2,
                "name": "Diesel",
                "slug": "diesel"
            }
        },
        {
            "id": 4,
            "name": "QA Cancel Vehicle",
            "slug": "qa-cancel-vehicle",
            "model": "Sandero",
            "year": 2024,
            "plate_number": "QA-CANCEL-01",
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
            "status": {
                "id": 2,
                "name": "Reserved",
                "slug": "reserved"
            },
            "transmission_type": {
                "id": 1,
                "name": "Manual",
                "slug": "manual"
            },
            "fuel_type": {
                "id": 2,
                "name": "Diesel",
                "slug": "diesel"
            }
        },
        {
            "id": 5,
            "name": "QA Reject Vehicle",
            "slug": "qa-reject-vehicle",
            "model": "Sandero",
            "year": 2024,
            "plate_number": "QA-REJECT-01",
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
            "status": {
                "id": 1,
                "name": "Available",
                "slug": "available"
            },
            "transmission_type": {
                "id": 1,
                "name": "Manual",
                "slug": "manual"
            },
            "fuel_type": {
                "id": 2,
                "name": "Diesel",
                "slug": "diesel"
            }
        },
        {
            "id": 6,
            "name": "QA Contract Vehicle",
            "slug": "qa-contract-vehicle",
            "model": "Sandero",
            "year": 2024,
            "plate_number": "QA-CONTRACT-01",
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
            "status": {
                "id": 1,
                "name": "Available",
                "slug": "available"
            },
            "transmission_type": {
                "id": 1,
                "name": "Manual",
                "slug": "manual"
            },
            "fuel_type": {
                "id": 2,
                "name": "Diesel",
                "slug": "diesel"
            }
        },
        {
            "id": 7,
            "name": "QA Payment Vehicle",
            "slug": "qa-payment-vehicle",
            "model": "Sandero",
            "year": 2024,
            "plate_number": "QA-PAYMENT-01",
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
            "status": {
                "id": 1,
                "name": "Available",
                "slug": "available"
            },
            "transmission_type": {
                "id": 1,
                "name": "Manual",
                "slug": "manual"
            },
            "fuel_type": {
                "id": 2,
                "name": "Diesel",
                "slug": "diesel"
            }
        },
        {
            "id": 1,
            "name": "Dacia Sandero 2024",
            "slug": "dacia-sandero-2024",
            "model": "Sandero",
            "year": 2024,
            "plate_number": "12345-A-10",
            "mileage": 12500,
            "current_mileage_updated_at": "2026-06-10T23:54:32.000000Z",
            "seats": 5,
            "doors": 5,
            "daily_price": "350.00",
            "weekly_price": "2200.00",
            "monthly_price": "8500.00",
            "deposit_amount": "3000.00",
            "description": "Reliable economy vehicle for Dakhla rentals.",
            "is_featured": true,
            "is_active": true,
            "brand": {
                "id": 1,
                "name": "Dacia",
                "slug": "dacia"
            },
            "category": {
                "id": 1,
                "name": "Economy",
                "slug": "economy"
            },
            "status": {
                "id": 1,
                "name": "Available",
                "slug": "available"
            },
            "transmission_type": {
                "id": 1,
                "name": "Manual",
                "slug": "manual"
            },
            "fuel_type": {
                "id": 2,
                "name": "Diesel",
                "slug": "diesel"
            }
        },
        {
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
                "id": 1,
                "name": "Manual",
                "slug": "manual"
            },
            "fuel_type": {
                "id": 2,
                "name": "Diesel",
                "slug": "diesel"
            }
        }
    ],
    "links": {
        "first": "http://127.0.0.1:8000/api/admin/vehicles?page=1",
        "last": "http://127.0.0.1:8000/api/admin/vehicles?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "page": null,
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/admin/vehicles?page=1",
                "label": "1",
                "page": 1,
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "page": null,
                "active": false
            }
        ],
        "path": "http://127.0.0.1:8000/api/admin/vehicles",
        "per_page": 15,
        "to": 7,
        "total": 7
    }
}
```

**Error responses**

401 if unauthenticated.
403 without vehicles.view.
500 for unexpected server errors.

**Render in UI**

Vehicle card/table fields, nested `brand`, `category`, `status`, `transmission_type`, `fuel_type`, money fields, `photos`, `documents` on detail.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Usually none unless navigating to detail/edit screens.

**Notes**

List loads brand, category, status, transmission type, and fuel type. It does not load photos/documents.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/vehicles`, {
  method: 'GET',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 29. `POST` `/api/admin/vehicles`

| Item | Value |
|------|-------|
| When to call | Creates a vehicle and resolves lookup slugs to lookup IDs. |
| Auth | Admin Bearer token |
| Permission | `vehicles.create` |

**Query params**

None.

**Path params**

None.

**Request body**

```json
{"brand_id":1,"category_id":1,"status_slug":"available","transmission_type_slug":"manual","fuel_type_slug":"diesel","name":"Postman Test Vehicle","slug":"postman-test-vehicle","model":"Sandero","year":2024,"plate_number":"PM-100-TEST","mileage":10000,"current_mileage_updated_at":"2026-06-10 10:00:00","seats":5,"doors":5,"daily_price":350,"weekly_price":2200,"monthly_price":8500,"deposit_amount":3000,"description":"Postman test vehicle.","is_featured":false,"is_active":true}
```

**Captured success response**

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
            "id": 1,
            "name": "Manual",
            "slug": "manual"
        },
        "fuel_type": {
            "id": 2,
            "name": "Diesel",
            "slug": "diesel"
        }
    }
}
```

**Error responses**

401 if unauthenticated.
403 without vehicles.create.
422 for invalid lookup slug, invalid year, duplicate slug, or duplicate plate_number.
500 for unexpected server errors.

**Render in UI**

Vehicle card/table fields, nested `brand`, `category`, `status`, `transmission_type`, `fuel_type`, money fields, `photos`, `documents` on detail.

**Send from UI**

brand_id, category_id, status_slug, transmission_type_slug, fuel_type_slug, name, slug, model, year, plate_number, mileage, current_mileage_updated_at, seats, doors, daily_price, weekly_price, monthly_price, deposit_amount, description, is_featured, is_active

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Store `data.id` returned by create endpoints for follow-up screens.

**Notes**

Save data.id as vehicle_id. Decimal prices are serialized as strings by Eloquent casts.

**Example call**

```javascript
const payload = {"brand_id":1,"category_id":1,"status_slug":"available","transmission_type_slug":"manual","fuel_type_slug":"diesel","name":"Postman Test Vehicle","slug":"postman-test-vehicle","model":"Sandero","year":2024,"plate_number":"PM-100-TEST","mileage":10000,"current_mileage_updated_at":"2026-06-10 10:00:00","seats":5,"doors":5,"daily_price":350,"weekly_price":2200,"monthly_price":8500,"deposit_amount":3000,"description":"Postman test vehicle.","is_featured":false,"is_active":true};

const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/vehicles`, {
  method: 'POST',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {}),
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(payload)
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 30. `GET` `/api/admin/vehicles/{vehicle}`

| Item | Value |
|------|-------|
| When to call | Shows one vehicle with photos and vehicle documents. |
| Auth | Admin Bearer token |
| Permission | `vehicles.view` |

**Query params**

None.

**Path params**

vehicle: numeric vehicle ID.

**Request body**

None.

**Captured success response**

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
            "id": 1,
            "name": "Manual",
            "slug": "manual"
        },
        "fuel_type": {
            "id": 2,
            "name": "Diesel",
            "slug": "diesel"
        },
        "photos": [
            {
                "id": 2,
                "path": "vehicles/photos/postman.jpg",
                "alt_text": "Front view",
                "sort_order": 1,
                "is_primary": true
            }
        ],
        "documents": [
            {
                "id": 1,
                "document_type": {
                    "id": 4,
                    "name": "Vehicle Registration",
                    "slug": "vehicle_registration"
                },
                "title": "Registration Card",
                "file_path": "vehicle-documents/2/registration.pdf",
                "expires_at": "2027-12-31T00:00:00.000000Z"
            }
        ]
    }
}
```

**Error responses**

401 if unauthenticated.
403 without vehicles.view.
404 for invalid vehicle ID.
500 for unexpected server errors.

**Render in UI**

Vehicle card/table fields, nested `brand`, `category`, `status`, `transmission_type`, `fuel_type`, money fields, `photos`, `documents` on detail.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Path parameter `vehicle` from route or list selection.

**Notes**

Admin vehicle show currently exposes vehicle document file_path.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/vehicles/${vehicle}`, {
  method: 'GET',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 31. `PUT` `/api/admin/vehicles/{vehicle}`

| Item | Value |
|------|-------|
| When to call | Updates a vehicle. |
| Auth | Admin Bearer token |
| Permission | `vehicles.update` |

**Query params**

None.

**Path params**

vehicle: numeric vehicle ID.

**Request body**

```json
{"brand_id":1,"category_id":1,"status_slug":"available","transmission_type_slug":"manual","fuel_type_slug":"diesel","name":"Postman Test Vehicle Updated","slug":"postman-test-vehicle","model":"Sandero","year":2024,"plate_number":"PM-100-TEST","mileage":13000,"seats":5,"doors":5,"daily_price":375,"weekly_price":2300,"monthly_price":8800,"deposit_amount":3000,"is_featured":false,"is_active":true}
```

**Captured success response**

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
            "id": 1,
            "name": "Available",
            "slug": "available"
        },
        "transmission_type": {
            "id": 1,
            "name": "Manual",
            "slug": "manual"
        },
        "fuel_type": {
            "id": 2,
            "name": "Diesel",
            "slug": "diesel"
        }
    }
}
```

**Error responses**

401 if unauthenticated.
403 without vehicles.update.
404 for invalid vehicle ID.
422 for invalid lookup slug, invalid year, duplicate slug, or duplicate plate_number.
500 for unexpected server errors.

**Render in UI**

Vehicle card/table fields, nested `brand`, `category`, `status`, `transmission_type`, `fuel_type`, money fields, `photos`, `documents` on detail.

**Send from UI**

brand_id, category_id, status_slug, transmission_type_slug, fuel_type_slug, name, slug, model, year, plate_number, mileage, seats, doors, daily_price, weekly_price, monthly_price, deposit_amount, is_featured, is_active

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Path parameter `vehicle` from route or list selection.

**Notes**

The update request supports partial fields even on PUT because validation fields are sometimes nullable/optional.

**Example call**

```javascript
const payload = {"brand_id":1,"category_id":1,"status_slug":"available","transmission_type_slug":"manual","fuel_type_slug":"diesel","name":"Postman Test Vehicle Updated","slug":"postman-test-vehicle","model":"Sandero","year":2024,"plate_number":"PM-100-TEST","mileage":13000,"seats":5,"doors":5,"daily_price":375,"weekly_price":2300,"monthly_price":8800,"deposit_amount":3000,"is_featured":false,"is_active":true};

const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/vehicles/${vehicle}`, {
  method: 'PUT',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {}),
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(payload)
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 32. `PATCH` `/api/admin/vehicles/{vehicle}`

| Item | Value |
|------|-------|
| When to call | Partially updates a vehicle. |
| Auth | Admin Bearer token |
| Permission | `vehicles.update` |

**Query params**

None.

**Path params**

vehicle: numeric vehicle ID.

**Request body**

```json
{"status_slug":"maintenance","mileage":13500,"daily_price":390}
```

**Captured success response**

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
            "id": 4,
            "name": "Maintenance",
            "slug": "maintenance"
        },
        "transmission_type": {
            "id": 1,
            "name": "Manual",
            "slug": "manual"
        },
        "fuel_type": {
            "id": 2,
            "name": "Diesel",
            "slug": "diesel"
        }
    }
}
```

**Error responses**

401 if unauthenticated.
403 without vehicles.update.
404 for invalid vehicle ID.
422 for invalid lookup slug or invalid values.
500 for unexpected server errors.

**Render in UI**

Vehicle card/table fields, nested `brand`, `category`, `status`, `transmission_type`, `fuel_type`, money fields, `photos`, `documents` on detail.

**Send from UI**

status_slug, mileage, daily_price

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Path parameter `vehicle` from route or list selection.

**Notes**

Use status_slug values from admin lookups.

**Example call**

```javascript
const payload = {"status_slug":"maintenance","mileage":13500,"daily_price":390};

const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/vehicles/${vehicle}`, {
  method: 'PATCH',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {}),
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(payload)
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 33. `DELETE` `/api/admin/vehicles/{vehicle}`

| Item | Value |
|------|-------|
| When to call | Soft deletes a vehicle. |
| Auth | Admin Bearer token |
| Permission | `vehicles.delete` |

**Query params**

None.

**Path params**

vehicle: numeric vehicle ID.

**Request body**

None.

**Captured success response**

```json
HTTP 204 No Content — empty body.
```

**Error responses**

401 if unauthenticated.
403 without vehicles.delete.
404 for invalid vehicle ID.
500 if related records prevent deletion.

**Render in UI**

Vehicle card/table fields, nested `brand`, `category`, `status`, `transmission_type`, `fuel_type`, money fields, `photos`, `documents` on detail.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Path parameter `vehicle` from route or list selection.

**Notes**

Avoid deleting vehicles needed by reservation/payment/maintenance tests.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/vehicles/${vehicle}`, {
  method: 'DELETE',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 34. `GET` `/api/admin/vehicles/{vehicle}/maintenances`

| Item | Value |
|------|-------|
| When to call | Lists maintenance records for one vehicle. |
| Auth | Admin Bearer token |
| Permission | `maintenance.view` |

**Query params**

page: optional page number.

**Path params**

vehicle: numeric vehicle ID.

**Request body**

None.

**Captured success response**

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
    "links": {
        "first": "http://127.0.0.1:8000/api/admin/vehicles/2/maintenances?page=1",
        "last": "http://127.0.0.1:8000/api/admin/vehicles/2/maintenances?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "page": null,
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/admin/vehicles/2/maintenances?page=1",
                "label": "1",
                "page": 1,
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "page": null,
                "active": false
            }
        ],
        "path": "http://127.0.0.1:8000/api/admin/vehicles/2/maintenances",
        "per_page": 15,
        "to": 1,
        "total": 1
    }
}
```

**Error responses**

401 if unauthenticated.
403 without maintenance.view.
404 for invalid vehicle ID.
500 for unexpected server errors.

**Render in UI**

Vehicle card/table fields, nested `brand`, `category`, `status`, `transmission_type`, `fuel_type`, money fields, `photos`, `documents` on detail.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Path parameter `vehicle` from route or list selection.

**Notes**

Same resource shape as maintenance list.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/vehicles/${vehicle}/maintenances`, {
  method: 'GET',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 35. `GET` `/api/admin/vehicles/{vehicle}/expenses`

| Item | Value |
|------|-------|
| When to call | Lists expense records for one vehicle. |
| Auth | Admin Bearer token |
| Permission | `expenses.view` |

**Query params**

page: optional page number.

**Path params**

vehicle: numeric vehicle ID.

**Request body**

None.

**Captured success response**

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
            "updated_at": "2026-06-11T23:19:46.000000Z"
        },
        {
            "id": 2,
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
            "created_at": "2026-06-11T23:16:54.000000Z",
            "updated_at": "2026-06-11T23:16:54.000000Z"
        },
        {
            "id": 1,
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
            "amount": "250.00",
            "expense_date": "2026-06-10T00:00:00.000000Z",
            "description": "Postman expense seeded.",
            "has_invoice": false,
            "created_by": {
                "id": 1,
                "name": "Limosud Cars Admin",
                "email": "admin@limosudcars.local"
            },
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        }
    ],
    "links": {
        "first": "http://127.0.0.1:8000/api/admin/vehicles/2/expenses?page=1",
        "last": "http://127.0.0.1:8000/api/admin/vehicles/2/expenses?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "page": null,
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/admin/vehicles/2/expenses?page=1",
                "label": "1",
                "page": 1,
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "page": null,
                "active": false
            }
        ],
        "path": "http://127.0.0.1:8000/api/admin/vehicles/2/expenses",
        "per_page": 15,
        "to": 3,
        "total": 3
    }
}
```

**Error responses**

401 if unauthenticated.
403 without expenses.view.
404 for invalid vehicle ID.
500 for unexpected server errors.

**Render in UI**

Vehicle card/table fields, nested `brand`, `category`, `status`, `transmission_type`, `fuel_type`, money fields, `photos`, `documents` on detail.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Path parameter `vehicle` from route or list selection.

**Notes**

Same resource shape as expense list.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/vehicles/${vehicle}/expenses`, {
  method: 'GET',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```


### Module: Customers

#### 36. `GET` `/api/admin/customers`

| Item | Value |
|------|-------|
| When to call | Lists customers. |
| Auth | Admin Bearer token |
| Permission | `customers.view` |

**Query params**

page: optional page number.

**Path params**

None.

**Request body**

None.

**Captured success response**

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
            "id": 3,
            "full_name": "Postman Public Customer",
            "nationality": "Moroccan",
            "phone": "+212600000000",
            "email": "postman.public@example.com",
            "passport_or_cin": "PM123456",
            "driving_license_number": "PM-DL-001",
            "created_at": "2026-06-11T23:15:51.000000Z",
            "updated_at": "2026-06-11T23:15:51.000000Z"
        },
        {
            "id": 2,
            "full_name": "Postman Public Customer",
            "nationality": "Moroccan",
            "phone": "+212600000000",
            "email": "postman.public@example.com",
            "passport_or_cin": "PM123456",
            "driving_license_number": "PM-DL-001",
            "created_at": "2026-06-11T23:12:43.000000Z",
            "updated_at": "2026-06-11T23:12:43.000000Z"
        },
        {
            "id": 1,
            "full_name": "Postman Customer",
            "nationality": "Moroccan",
            "phone": "+212611111111",
            "email": "postman.customer@example.com",
            "passport_or_cin": "PC123456",
            "driving_license_number": "PC-DL-001",
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        }
    ],
    "links": {
        "first": "http://127.0.0.1:8000/api/admin/customers?page=1",
        "last": "http://127.0.0.1:8000/api/admin/customers?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "page": null,
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/admin/customers?page=1",
                "label": "1",
                "page": 1,
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "page": null,
                "active": false
            }
        ],
        "path": "http://127.0.0.1:8000/api/admin/customers",
        "per_page": 15,
        "to": 5,
        "total": 5
    }
}
```

**Error responses**

401 if unauthenticated.
403 without customers.view.
500 for unexpected server errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Usually none unless navigating to detail/edit screens.

**Notes**

List does not load documents; detail does.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/customers`, {
  method: 'GET',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 37. `POST` `/api/admin/customers`

| Item | Value |
|------|-------|
| When to call | Creates a customer. |
| Auth | Admin Bearer token |
| Permission | `customers.create` |

**Query params**

None.

**Path params**

None.

**Request body**

```json
{"full_name":"Postman Customer","nationality":"Moroccan","phone":"+212611111111","email":"postman.customer@example.com","passport_or_cin":"PC123456","driving_license_number":"PC-DL-001"}
```

**Captured success response**

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

**Error responses**

401 if unauthenticated.
403 without customers.create.
422 for missing full_name, nationality, phone, or invalid email.
500 for unexpected server errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

full_name, nationality, phone, email, passport_or_cin, driving_license_number

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Store `data.id` returned by create endpoints for follow-up screens.

**Notes**

Save data.id as customer_id.

**Example call**

```javascript
const payload = {"full_name":"Postman Customer","nationality":"Moroccan","phone":"+212611111111","email":"postman.customer@example.com","passport_or_cin":"PC123456","driving_license_number":"PC-DL-001"};

const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/customers`, {
  method: 'POST',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {}),
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(payload)
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 38. `GET` `/api/admin/customers/{customer}`

| Item | Value |
|------|-------|
| When to call | Shows one customer with documents. |
| Auth | Admin Bearer token |
| Permission | `customers.view` |

**Query params**

None.

**Path params**

customer: numeric customer ID.

**Request body**

None.

**Captured success response**

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

**Error responses**

401 if unauthenticated.
403 without customers.view.
404 for invalid customer ID.
500 for unexpected server errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Path parameter `customer` from route or list selection.

**Notes**

Customer document file_path is returned for customer documents.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/customers/${customer}`, {
  method: 'GET',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 39. `PUT` `/api/admin/customers/{customer}`

| Item | Value |
|------|-------|
| When to call | Updates a customer. |
| Auth | Admin Bearer token |
| Permission | `customers.update` |

**Query params**

None.

**Path params**

customer: numeric customer ID.

**Request body**

```json
{"full_name":"Postman Customer Updated","nationality":"Moroccan","phone":"+212622222222","email":"postman.customer.updated@example.com","passport_or_cin":"PC123456","driving_license_number":"PC-DL-002"}
```

**Captured success response**

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

**Error responses**

401 if unauthenticated.
403 without customers.update.
404 for invalid customer ID.
422 for invalid email or invalid field values.
500 for unexpected server errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

full_name, nationality, phone, email, passport_or_cin, driving_license_number

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Path parameter `customer` from route or list selection.

**Notes**

The same controller method is used for PUT and PATCH.

**Example call**

```javascript
const payload = {"full_name":"Postman Customer Updated","nationality":"Moroccan","phone":"+212622222222","email":"postman.customer.updated@example.com","passport_or_cin":"PC123456","driving_license_number":"PC-DL-002"};

const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/customers/${customer}`, {
  method: 'PUT',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {}),
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(payload)
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 40. `PATCH` `/api/admin/customers/{customer}`

| Item | Value |
|------|-------|
| When to call | Partially updates a customer. |
| Auth | Admin Bearer token |
| Permission | `customers.update` |

**Query params**

None.

**Path params**

customer: numeric customer ID.

**Request body**

```json
{"phone":"+212633333333"}
```

**Captured success response**

```json
{
    "data": {
        "id": 8,
        "full_name": "QA Disposable Customer Updated",
        "nationality": "Moroccan",
        "phone": "+212633333333",
        "email": "qa.disposable.1781222073@example.com",
        "passport_or_cin": "QA1781222073",
        "driving_license_number": "QA-DL-1781222073",
        "documents": [],
        "created_at": "2026-06-11T23:55:04.000000Z",
        "updated_at": "2026-06-11T23:55:05.000000Z"
    }
}
```

**Error responses**

401 if unauthenticated.
403 without customers.update.
404 for invalid customer ID.
422 for invalid email or invalid field values.
500 for unexpected server errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

phone

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Path parameter `customer` from route or list selection.

**Notes**

Partial update is useful for phone/email edits.

**Example call**

```javascript
const payload = {"phone":"+212633333333"};

const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/customers/${customer}`, {
  method: 'PATCH',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {}),
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(payload)
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 41. `DELETE` `/api/admin/customers/{customer}`

| Item | Value |
|------|-------|
| When to call | Soft deletes a customer. |
| Auth | Admin Bearer token |
| Permission | `customers.delete` |

**Query params**

None.

**Path params**

customer: numeric customer ID.

**Request body**

None.

**Captured success response**

```json
HTTP 204 No Content — empty body.
```

**Error responses**

401 if unauthenticated.
403 without customers.delete.
404 for invalid customer ID.
500 if related records prevent deletion.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Path parameter `customer` from route or list selection.

**Notes**

Do not delete a customer needed by reservation tests.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/customers/${customer}`, {
  method: 'DELETE',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 42. `POST` `/api/admin/customers/{customer}/documents`

| Item | Value |
|------|-------|
| When to call | Uploads a customer document file and links it to a document type lookup. |
| Auth | Admin Bearer token |
| Permission | `customers.update` |

**Query params**

None.

**Path params**

customer: numeric customer ID.

**Request body**

Use `multipart/form-data`. See workflow notes for field names.

```
multipart/form-data:
document_type_slug=passport
title=Passport Scan
file=<pdf/jpg/jpeg/png/webp file>
expires_at=2028-12-31
```

**Captured success response**

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

**Error responses**

401 if unauthenticated.
403 without customers.update.
404 for invalid customer ID.
422 for missing file, invalid document_type_slug, invalid mime, or invalid expires_at.
500 for unexpected server errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

See multipart field list in request body section.

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Store `data.id` returned by create endpoints for follow-up screens.

**Notes**

Files are stored on the public disk under customer-documents/{customer_id}. Duplicate documents are currently allowed.

**Example call**

```javascript
const formData = new FormData();
// append fields and files

const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/customers/${customer}/documents`, {
  method: 'POST',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  },
  body: formData
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 43. `DELETE` `/api/admin/customer-documents/{document}`

| Item | Value |
|------|-------|
| When to call | Deletes the stored customer document file and soft deletes the document record. |
| Auth | Admin Bearer token |
| Permission | `customers.update` |

**Query params**

None.

**Path params**

document: numeric customer document ID.

**Request body**

None.

**Captured success response**

```json
HTTP 204 No Content — empty body.
```

**Error responses**

401 if unauthenticated.
403 without customers.update.
404 for invalid document ID.
500 for unexpected server errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Path parameter `document` from route or list selection.

**Notes**

The controller attempts to delete the physical file from the public disk before soft deleting the record.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/customer-documents/${document}`, {
  method: 'DELETE',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```


### Module: Locations

#### 44. `GET` `/api/admin/locations`

| Item | Value |
|------|-------|
| When to call | Lists admin locations. |
| Auth | Admin Bearer token |
| Permission | `locations.view` |

**Query params**

page: optional page number.

**Path params**

None.

**Request body**

None.

**Captured success response**

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
                "id": 2,
                "name": "Airport",
                "slug": "airport"
            },
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        },
        {
            "id": 3,
            "name": "Postman Location",
            "slug": "postman-location",
            "address": "Postman Street, Dakhla",
            "delivery_fee": "100.00",
            "is_active": true,
            "location_type": {
                "id": 1,
                "name": "Agency",
                "slug": "agency"
            },
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        }
    ],
    "links": {
        "first": "http://127.0.0.1:8000/api/admin/locations?page=1",
        "last": "http://127.0.0.1:8000/api/admin/locations?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "page": null,
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/admin/locations?page=1",
                "label": "1",
                "page": 1,
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "page": null,
                "active": false
            }
        ],
        "path": "http://127.0.0.1:8000/api/admin/locations",
        "per_page": 15,
        "to": 3,
        "total": 3
    }
}
```

**Error responses**

401 if unauthenticated.
403 without locations.view.
500 for unexpected server errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Usually none unless navigating to detail/edit screens.

**Notes**

Admin list includes active and inactive locations.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/locations`, {
  method: 'GET',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 45. `POST` `/api/admin/locations`

| Item | Value |
|------|-------|
| When to call | Creates a location and resolves location_type_slug. |
| Auth | Admin Bearer token |
| Permission | `locations.create` |

**Query params**

None.

**Path params**

None.

**Request body**

```json
{"location_type_slug":"agency","name":"Postman Location","slug":"postman-location","address":"Postman Street, Dakhla","delivery_fee":0,"is_active":true}
```

**Captured success response**

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

**Error responses**

401 if unauthenticated.
403 without locations.create.
422 for invalid location_type_slug or duplicate slug.
500 for unexpected server errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

location_type_slug, name, slug, address, delivery_fee, is_active

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Store `data.id` returned by create endpoints for follow-up screens.

**Notes**

Save data.id as location_id.

**Example call**

```javascript
const payload = {"location_type_slug":"agency","name":"Postman Location","slug":"postman-location","address":"Postman Street, Dakhla","delivery_fee":0,"is_active":true};

const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/locations`, {
  method: 'POST',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {}),
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(payload)
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 46. `GET` `/api/admin/locations/{location}`

| Item | Value |
|------|-------|
| When to call | Shows one location. |
| Auth | Admin Bearer token |
| Permission | `locations.view` |

**Query params**

None.

**Path params**

location: numeric location ID.

**Request body**

None.

**Captured success response**

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

**Error responses**

401 if unauthenticated.
403 without locations.view.
404 for invalid location ID.
500 for unexpected server errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Path parameter `location` from route or list selection.

**Notes**

Soft-deleted locations are not resolved by route model binding.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/locations/${location}`, {
  method: 'GET',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 47. `PUT` `/api/admin/locations/{location}`

| Item | Value |
|------|-------|
| When to call | Updates a location. |
| Auth | Admin Bearer token |
| Permission | `locations.update` |

**Query params**

None.

**Path params**

location: numeric location ID.

**Request body**

```json
{"location_type_slug":"agency","name":"Postman Location Updated","slug":"postman-location","address":"Updated Street, Dakhla","delivery_fee":100,"is_active":true}
```

**Captured success response**

```json
{
    "data": {
        "id": 6,
        "name": "QA Disposable Location Updated",
        "slug": "qa-disposable-location-1781222073",
        "address": "Updated QA Street, Dakhla",
        "delivery_fee": "75.00",
        "is_active": true,
        "location_type": {
            "id": 1,
            "name": "Agency",
            "slug": "agency"
        },
        "created_at": "2026-06-11T23:55:09.000000Z",
        "updated_at": "2026-06-11T23:55:10.000000Z"
    }
}
```

**Error responses**

401 if unauthenticated.
403 without locations.update.
404 for invalid location ID.
422 for invalid location_type_slug or duplicate slug.
500 for unexpected server errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

location_type_slug, name, slug, address, delivery_fee, is_active

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Path parameter `location` from route or list selection.

**Notes**

The same controller method is used for PUT and PATCH.

**Example call**

```javascript
const payload = {"location_type_slug":"agency","name":"Postman Location Updated","slug":"postman-location","address":"Updated Street, Dakhla","delivery_fee":100,"is_active":true};

const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/locations/${location}`, {
  method: 'PUT',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {}),
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(payload)
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 48. `PATCH` `/api/admin/locations/{location}`

| Item | Value |
|------|-------|
| When to call | Partially updates a location. |
| Auth | Admin Bearer token |
| Permission | `locations.update` |

**Query params**

None.

**Path params**

location: numeric location ID.

**Request body**

```json
{"delivery_fee":150,"is_active":false}
```

**Captured success response**

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

**Error responses**

401 if unauthenticated.
403 without locations.update.
404 for invalid location ID.
422 for invalid field values.
500 for unexpected server errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

delivery_fee, is_active

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Path parameter `location` from route or list selection.

**Notes**

Inactive locations are hidden from public location and public lookup endpoints.

**Example call**

```javascript
const payload = {"delivery_fee":150,"is_active":false};

const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/locations/${location}`, {
  method: 'PATCH',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {}),
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(payload)
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 49. `DELETE` `/api/admin/locations/{location}`

| Item | Value |
|------|-------|
| When to call | Soft deletes a location. |
| Auth | Admin Bearer token |
| Permission | `locations.delete` |

**Query params**

None.

**Path params**

location: numeric location ID.

**Request body**

None.

**Captured success response**

```json
HTTP 204 No Content — empty body.
```

**Error responses**

401 if unauthenticated.
403 without locations.delete.
404 for invalid location ID.
500 if related reservations prevent deletion.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Path parameter `location` from route or list selection.

**Notes**

Do not delete locations used by active reservation tests.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/locations/${location}`, {
  method: 'DELETE',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```


### Module: Reservations

#### 50. `GET` `/api/admin/reservations`

| Item | Value |
|------|-------|
| When to call | Lists reservations with customer, vehicle, lookup statuses, locations, and creator. |
| Auth | Admin Bearer token |
| Permission | `reservations.view` |

**Query params**

page: optional page number.

**Path params**

None.

**Request body**

None.

**Captured success response**

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
                "seats": 5,
                "doors": 5,
                "daily_price": "350.00",
                "weekly_price": "2200.00",
                "monthly_price": "8500.00",
                "deposit_amount": "3000.00",
                "description": "Reliable economy vehicle for Dakhla rentals.",
                "is_featured": true,
                "is_active": true,
                "brand": {
                    "id": 1,
                    "name": "Dacia",
                    "slug": "dacia"
                },
                "category": {
                    "id": 1,
                    "name": "Economy",
                    "slug": "economy"
                },
                "status": {
                    "id": 1,
                    "name": "Available",
                    "slug": "available"
                },
                "transmission_type": {
                    "id": 1,
                    "name": "Manual",
                    "slug": "manual"
                },
                "fuel_type": {
                    "id": 2,
                    "name": "Diesel",
                    "slug": "diesel"
                }
            },
            "source": {
                "id": 1,
                "name": "Website",
                "slug": "website"
            },
            "status": {
                "id": 1,
                "name": "Pending",
                "slug": "pending"
            },
            "payment_status": {
                "id": 1,
                "name": "Unpaid",
                "slug": "unpaid"
            },
            "pickup_location": {
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
            "dropoff_location": {
                "id": 2,
                "name": "Dakhla Airport",
                "slug": "dakhla-airport",
                "address": "Dakhla Airport, Morocco",
                "delivery_fee": "150.00",
                "is_active": true,
                "location_type": {
                    "id": 2,
                    "name": "Airport",
                    "slug": "airport"
                },
                "created_at": "2026-06-11T23:05:51.000000Z",
                "updated_at": "2026-06-11T23:05:51.000000Z"
            },
            "start_datetime": "2026-12-01T10:00:00.000000Z",
            "end_datetime": "2026-12-05T10:00:00.000000Z",
            "total_days": 4,
            "price_per_day": "350.00",
            "delivery_fee": "150.00",
            "deposit_amount": "3000.00",
            "total_price": "4550.00",
            "customer_notes": "Airport pickup please.",
            "admin_notes": null,
            "created_by": null,
            "confirmed_at": null,
            "started_at": null,
            "completed_at": null,
            "cancelled_at": null,
            "created_at": "2026-06-11T23:54:43.000000Z",
            "updated_at": "2026-06-11T23:54:43.000000Z"
        },
        {
            "id": 10,
            "reservation_number": "RSV-20260611-2314",
            "customer": {
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
            "vehicle": {
                "id": 1,
                "name": "Dacia Sandero 2024",
                "slug": "dacia-sandero-2024",
                "model": "Sandero",
                "year": 2024,
                "plate_number": "12345-A-10",
                "mileage": 12500,
                "current_mileage_updated_at": "2026-06-10T23:54:32.000000Z",
                "seats": 5,
                "doors": 5,
                "daily_price": "350.00",
                "weekly_price": "2200.00",
                "monthly_price": "8500.00",
                "deposit_amount": "3000.00",
                "description": "Reliable economy vehicle for Dakhla rentals.",
                "is_featured": true,
                "is_active": true,
                "brand": {
                    "id": 1,
                    "name": "Dacia",
                    "slug": "dacia"
                },
                "category": {
                    "id": 1,
                    "name": "Economy",
                    "slug": "economy"
                },
                "status": {
                    "id": 1,
                    "name": "Available",
                    "slug": "available"
                },
                "transmission_type": {
                    "id": 1,
                    "name": "Manual",
                    "slug": "manual"
                },
                "fuel_type": {
                    "id": 2,
                    "name": "Diesel",
                    "slug": "diesel"
                }
            },
            "source": {
                "id": 1,
                "name": "Website",
                "slug": "website"
            },
            "status": {
                "id": 1,
                "name": "Pending",
                "slug": "pending"
            },
            "payment_status": {
                "id": 1,
                "name": "Unpaid",
                "slug": "unpaid"
            },
            "pickup_location": {
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
            "dropoff_location": {
                "id": 2,
                "name": "Dakhla Airport",
                "slug": "dakhla-airport",
                "address": "Dakhla Airport, Morocco",
                "delivery_fee": "150.00",
                "is_active": true,
                "location_type": {
                    "id": 2,
                    "name": "Airport",
                    "slug": "airport"
                },
                "created_at": "2026-06-11T23:05:51.000000Z",
                "updated_at": "2026-06-11T23:05:51.000000Z"
            },
            "start_datetime": "2026-12-01T10:00:00.000000Z",
            "end_datetime": "2026-12-05T10:00:00.000000Z",
            "total_days": 4,
            "price_per_day": "350.00",
            "delivery_fee": "150.00",
            "deposit_amount": "3000.00",
            "total_price": "4550.00",
            "customer_notes": "Airport pickup please.",
            "admin_notes": null,
            "created_by": null,
            "confirmed_at": null,
            "started_at": null,
            "completed_at": null,
            "cancelled_at": null,
            "created_at": "2026-06-11T23:18:53.000000Z",
            "updated_at": "2026-06-11T23:18:53.000000Z"
        },
        {
            "id": 8,
            "reservation_number": "RSV-20260611-9743",
            "customer": {
                "id": 3,
                "full_name": "Postman Public Customer",
                "nationality": "Moroccan",
                "phone": "+212600000000",
                "email": "postman.public@example.com",
                "passport_or_cin": "PM123456",
                "driving_license_number": "PM-DL-001",
                "created_at": "2026-06-11T23:15:51.000000Z",
                "updated_at": "2026-06-11T23:15:51.000000Z"
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
                "seats": 5,
                "doors": 5,
                "daily_price": "350.00",
                "weekly_price": "2200.00",
                "monthly_price": "8500.00",
                "deposit_amount": "3000.00",
                "description": "Reliable economy vehicle for Dakhla rentals.",
                "is_featured": true,
                "is_active": true,
                "brand": {
                    "id": 1,
                    "name": "Dacia",
                    "slug": "dacia"
                },
                "category": {
                    "id": 1,
                    "name": "Economy",
                    "slug": "economy"
                },
                "status": {
                    "id": 1,
                    "name": "Available",
                    "slug": "available"
                },
                "transmission_type": {
                    "id": 1,
                    "name": "Manual",
                    "slug": "manual"
                },
                "fuel_type": {
                    "id": 2,
                    "name": "Diesel",
                    "slug": "diesel"
                }
            },
            "source": {
                "id": 1,
                "name": "Website",
                "slug": "website"
            },
            "status": {
                "id": 1,
                "name": "Pending",
                "slug": "pending"
            },
            "payment_status": {
                "id": 1,
                "name": "Unpaid",
                "slug": "unpaid"
            },
            "pickup_location": {
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
            "dropoff_location": {
                "id": 2,
                "name": "Dakhla Airport",
                "slug": "dakhla-airport",
                "address": "Dakhla Airport, Morocco",
                "delivery_fee": "150.00",
                "is_active": true,
                "location_type": {
                    "id": 2,
                    "name": "Airport",
                    "slug": "airport"
                },
                "created_at": "2026-06-11T23:05:51.000000Z",
                "updated_at": "2026-06-11T23:05:51.000000Z"
            },
            "start_datetime": "2026-12-01T10:00:00.000000Z",
            "end_datetime": "2026-12-05T10:00:00.000000Z",
            "total_days": 4,
            "price_per_day": "350.00",
            "delivery_fee": "150.00",
            "deposit_amount": "3000.00",
            "total_price": "4550.00",
            "customer_notes": "Airport pickup please.",
            "admin_notes": null,
            "created_by": null,
            "confirmed_at": null,
            "started_at": null,
            "completed_at": null,
            "cancelled_at": null,
            "created_at": "2026-06-11T23:15:51.000000Z",
            "updated_at": "2026-06-11T23:15:51.000000Z"
        },
        {
            "id": 7,
            "reservation_number": "RSV-20260611-3472",
            "customer": {
                "id": 2,
                "full_name": "Postman Public Customer",
                "nationality": "Moroccan",
                "phone": "+212600000000",
                "email": "postman.public@example.com",
                "passport_or_cin": "PM123456",
                "driving_license_number": "PM-DL-001",
                "created_at": "2026-06-11T23:12:43.000000Z",
                "updated_at": "2026-06-11T23:12:43.000000Z"
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
                "seats": 5,
                "doors": 5,
                "daily_price": "350.00",
                "weekly_price": "2200.00",
                "monthly_price": "8500.00",
                "deposit_amount": "3000.00",
                "description": "Reliable economy vehicle for Dakhla rentals.",
                "is_featured": true,
                "is_active": true,
                "brand": {
                    "id": 1,
                    "name": "Dacia",
                    "slug": "dacia"
                },
                "category": {
                    "id": 1,
                    "name": "Economy",
                    "slug": "economy"
                },
                "status": {
                    "id": 1,
                    "name": "Available",
                    "slug": "available"
                },
                "transmission_type": {
                    "id": 1,
                    "name": "Manual",
                    "slug": "manual"
                },
                "fuel_type": {
                    "id": 2,
                    "name": "Diesel",
                    "slug": "diesel"
                }
            },
            "source": {
                "id": 1,
                "name": "Website",
                "slug": "website"
            },
            "status": {
                "id": 1,
                "name": "Pending",
                "slug": "pending"
            },
            "payment_status": {
                "id": 1,
                "name": "Unpaid",
                "slug": "unpaid"
            },
            "pickup_location": {
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
            "dropoff_location": {
                "id": 2,
                "name": "Dakhla Airport",
                "slug": "dakhla-airport",
                "address": "Dakhla Airport, Morocco",
                "delivery_fee": "150.00",
                "is_active": true,
                "location_type": {
                    "id": 2,
                    "name": "Airport",
                    "slug": "airport"
                },
                "created_at": "2026-06-11T23:05:51.000000Z",
                "updated_at": "2026-06-11T23:05:51.000000Z"
            },
            "start_datetime": "2026-12-01T10:00:00.000000Z",
            "end_datetime": "2026-12-05T10:00:00.000000Z",
            "total_days": 4,
            "price_per_day": "350.00",
            "delivery_fee": "150.00",
            "deposit_amount": "3000.00",
            "total_price": "4550.00",
            "customer_notes": "Airport pickup please.",
            "admin_notes": null,
            "created_by": null,
            "confirmed_at": null,
            "started_at": null,
            "completed_at": null,
            "cancelled_at": null,
            "created_at": "2026-06-11T23:12:43.000000Z",
            "updated_at": "2026-06-11T23:12:43.000000Z"
        },
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
                    "id": 4,
                    "name": "Maintenance",
                    "slug": "maintenance"
                },
                "transmission_type": {
                    "id": 1,
                    "name": "Manual",
                    "slug": "manual"
                },
                "fuel_type": {
                    "id": 2,
                    "name": "Diesel",
                    "slug": "diesel"
                }
            },
            "source": {
                "id": 6,
                "name": "Admin Manual",
                "slug": "admin_manual"
            },
            "status": {
                "id": 1,
                "name": "Pending",
                "slug": "pending"
            },
            "payment_status": {
                "id": 1,
                "name": "Unpaid",
                "slug": "unpaid"
            },
            "pickup_location": {
                "id": 3,
                "name": "Postman Location",
                "slug": "postman-location",
                "address": "Postman Street, Dakhla",
                "delivery_fee": "100.00",
                "is_active": true,
                "location_type": {
                    "id": 1,
                    "name": "Agency",
                    "slug": "agency"
                },
                "created_at": "2026-06-11T23:05:51.000000Z",
                "updated_at": "2026-06-11T23:05:51.000000Z"
            },
            "dropoff_location": {
                "id": 3,
                "name": "Postman Location",
                "slug": "postman-location",
                "address": "Postman Street, Dakhla",
                "delivery_fee": "100.00",
                "is_active": true,
                "location_type": {
                    "id": 1,
                    "name": "Agency",
                    "slug": "agency"
                },
                "created_at": "2026-06-11T23:05:51.000000Z",
                "updated_at": "2026-06-11T23:05:51.000000Z"
            },
            "start_datetime": "2026-08-11T10:00:00.000000Z",
            "end_datetime": "2026-08-15T10:00:00.000000Z",
            "total_days": 4,
            "price_per_day": "375.00",
            "delivery_fee": "200.00",
            "deposit_amount": "3000.00",
            "total_price": "4700.00",
            "customer_notes": "Postman admin reservation.",
            "admin_notes": "QA test.",
            "created_by": {
                "id": 1,
                "name": "Limosud Cars Admin",
                "email": "admin@limosudcars.local"
            },
            "confirmed_at": null,
            "started_at": null,
            "completed_at": null,
            "cancelled_at": null,
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        },
        {
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
                "status": {
                    "id": 1,
                    "name": "Available",
                    "slug": "available"
                },
                "transmission_type": {
                    "id": 1,
                    "name": "Manual",
                    "slug": "manual"
                },
                "fuel_type": {
                    "id": 2,
                    "name": "Diesel",
                    "slug": "diesel"
                }
            },
            "source": {
                "id": 6,
                "name": "Admin Manual",
                "slug": "admin_manual"
            },
            "status": {
                "id": 1,
                "name": "Pending",
                "slug": "pending"
            },
            "payment_status": {
                "id": 1,
                "name": "Unpaid",
                "slug": "unpaid"
            },
            "pickup_location": {
                "id": 3,
                "name": "Postman Location",
                "slug": "postman-location",
                "address": "Postman Street, Dakhla",
                "delivery_fee": "100.00",
                "is_active": true,
                "location_type": {
                    "id": 1,
                    "name": "Agency",
                    "slug": "agency"
                },
                "created_at": "2026-06-11T23:05:51.000000Z",
                "updated_at": "2026-06-11T23:05:51.000000Z"
            },
            "dropoff_location": {
                "id": 3,
                "name": "Postman Location",
                "slug": "postman-location",
                "address": "Postman Street, Dakhla",
                "delivery_fee": "100.00",
                "is_active": true,
                "location_type": {
                    "id": 1,
                    "name": "Agency",
                    "slug": "agency"
                },
                "created_at": "2026-06-11T23:05:51.000000Z",
                "updated_at": "2026-06-11T23:05:51.000000Z"
            },
            "start_datetime": "2026-09-11T10:00:00.000000Z",
            "end_datetime": "2026-09-14T10:00:00.000000Z",
            "total_days": 3,
            "price_per_day": "375.00",
            "delivery_fee": "200.00",
            "deposit_amount": "3000.00",
            "total_price": "4325.00",
            "customer_notes": null,
            "admin_notes": "Lifecycle QA reservation.",
            "created_by": {
                "id": 1,
                "name": "Limosud Cars Admin",
                "email": "admin@limosudcars.local"
            },
            "confirmed_at": "2026-06-11T23:19:33.000000Z",
            "started_at": "2026-06-11T23:19:33.000000Z",
            "completed_at": "2026-06-11T23:19:34.000000Z",
            "cancelled_at": null,
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:54:32.000000Z"
        },
        {
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
                "status": {
                    "id": 2,
                    "name": "Reserved",
                    "slug": "reserved"
                },
                "transmission_type": {
                    "id": 1,
                    "name": "Manual",
                    "slug": "manual"
                },
                "fuel_type": {
                    "id": 2,
                    "name": "Diesel",
                    "slug": "diesel"
                }
            },
            "source": {
                "id": 6,
                "name": "Admin Manual",
                "slug": "admin_manual"
            },
            "status": {
                "id": 2,
                "name": "Confirmed",
                "slug": "confirmed"
            },
            "payment_status": {
                "id": 1,
                "name": "Unpaid",
                "slug": "unpaid"
            },
            "pickup_location": {
                "id": 3,
                "name": "Postman Location",
                "slug": "postman-location",
                "address": "Postman Street, Dakhla",
                "delivery_fee": "100.00",
                "is_active": true,
                "location_type": {
                    "id": 1,
                    "name": "Agency",
                    "slug": "agency"
                },
                "created_at": "2026-06-11T23:05:51.000000Z",
                "updated_at": "2026-06-11T23:05:51.000000Z"
            },
            "dropoff_location": {
                "id": 3,
                "name": "Postman Location",
                "slug": "postman-location",
                "address": "Postman Street, Dakhla",
                "delivery_fee": "100.00",
                "is_active": true,
                "location_type": {
                    "id": 1,
                    "name": "Agency",
                    "slug": "agency"
                },
                "created_at": "2026-06-11T23:05:51.000000Z",
                "updated_at": "2026-06-11T23:05:51.000000Z"
            },
            "start_datetime": "2026-10-11T10:00:00.000000Z",
            "end_datetime": "2026-10-14T10:00:00.000000Z",
            "total_days": 3,
            "price_per_day": "375.00",
            "delivery_fee": "200.00",
            "deposit_amount": "3000.00",
            "total_price": "4325.00",
            "customer_notes": null,
            "admin_notes": null,
            "created_by": {
                "id": 1,
                "name": "Limosud Cars Admin",
                "email": "admin@limosudcars.local"
            },
            "confirmed_at": "2026-06-11T22:54:32.000000Z",
            "started_at": null,
            "completed_at": null,
            "cancelled_at": "2026-06-11T23:19:35.000000Z",
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:54:32.000000Z"
        },
        {
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
                "status": {
                    "id": 1,
                    "name": "Available",
                    "slug": "available"
                },
                "transmission_type": {
                    "id": 1,
                    "name": "Manual",
                    "slug": "manual"
                },
                "fuel_type": {
                    "id": 2,
                    "name": "Diesel",
                    "slug": "diesel"
                }
            },
            "source": {
                "id": 1,
                "name": "Website",
                "slug": "website"
            },
            "status": {
                "id": 1,
                "name": "Pending",
                "slug": "pending"
            },
            "payment_status": {
                "id": 1,
                "name": "Unpaid",
                "slug": "unpaid"
            },
            "pickup_location": {
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
            "dropoff_location": {
                "id": 2,
                "name": "Dakhla Airport",
                "slug": "dakhla-airport",
                "address": "Dakhla Airport, Morocco",
                "delivery_fee": "150.00",
                "is_active": true,
                "location_type": {
                    "id": 2,
                    "name": "Airport",
                    "slug": "airport"
                },
                "created_at": "2026-06-11T23:05:51.000000Z",
                "updated_at": "2026-06-11T23:05:51.000000Z"
            },
            "start_datetime": "2026-11-11T10:00:00.000000Z",
            "end_datetime": "2026-11-13T10:00:00.000000Z",
            "total_days": 2,
            "price_per_day": "375.00",
            "delivery_fee": "150.00",
            "deposit_amount": "3000.00",
            "total_price": "3450.00",
            "customer_notes": "Reject flow QA.",
            "admin_notes": null,
            "created_by": null,
            "confirmed_at": null,
            "started_at": null,
            "completed_at": null,
            "cancelled_at": null,
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:54:32.000000Z"
        },
        {
            "id": 5,
            "reservation_number": "RSV-QA-CONTRACT-001",
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
                "id": 6,
                "name": "QA Contract Vehicle",
                "slug": "qa-contract-vehicle",
                "model": "Sandero",
                "year": 2024,
                "plate_number": "QA-CONTRACT-01",
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
                "status": {
                    "id": 1,
                    "name": "Available",
                    "slug": "available"
                },
                "transmission_type": {
                    "id": 1,
                    "name": "Manual",
                    "slug": "manual"
                },
                "fuel_type": {
                    "id": 2,
                    "name": "Diesel",
                    "slug": "diesel"
                }
            },
            "source": {
                "id": 6,
                "name": "Admin Manual",
                "slug": "admin_manual"
            },
            "status": {
                "id": 2,
                "name": "Confirmed",
                "slug": "confirmed"
            },
            "payment_status": {
                "id": 1,
                "name": "Unpaid",
                "slug": "unpaid"
            },
            "pickup_location": {
                "id": 3,
                "name": "Postman Location",
                "slug": "postman-location",
                "address": "Postman Street, Dakhla",
                "delivery_fee": "100.00",
                "is_active": true,
                "location_type": {
                    "id": 1,
                    "name": "Agency",
                    "slug": "agency"
                },
                "created_at": "2026-06-11T23:05:51.000000Z",
                "updated_at": "2026-06-11T23:05:51.000000Z"
            },
            "dropoff_location": {
                "id": 3,
                "name": "Postman Location",
                "slug": "postman-location",
                "address": "Postman Street, Dakhla",
                "delivery_fee": "100.00",
                "is_active": true,
                "location_type": {
                    "id": 1,
                    "name": "Agency",
                    "slug": "agency"
                },
                "created_at": "2026-06-11T23:05:51.000000Z",
                "updated_at": "2026-06-11T23:05:51.000000Z"
            },
            "start_datetime": "2026-12-11T10:00:00.000000Z",
            "end_datetime": "2026-12-15T10:00:00.000000Z",
            "total_days": 4,
            "price_per_day": "375.00",
            "delivery_fee": "200.00",
            "deposit_amount": "3000.00",
            "total_price": "4700.00",
            "customer_notes": null,
            "admin_notes": null,
            "created_by": {
                "id": 1,
                "name": "Limosud Cars Admin",
                "email": "admin@limosudcars.local"
            },
            "confirmed_at": "2026-06-11T21:54:32.000000Z",
            "started_at": null,
            "completed_at": null,
            "cancelled_at": null,
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:54:32.000000Z"
        },
        {
            "id": 6,
            "reservation_number": "RSV-QA-PAYMENT-001",
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
                "id": 7,
                "name": "QA Payment Vehicle",
                "slug": "qa-payment-vehicle",
                "model": "Sandero",
                "year": 2024,
                "plate_number": "QA-PAYMENT-01",
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
                "status": {
                    "id": 1,
                    "name": "Available",
                    "slug": "available"
                },
                "transmission_type": {
                    "id": 1,
                    "name": "Manual",
                    "slug": "manual"
                },
                "fuel_type": {
                    "id": 2,
                    "name": "Diesel",
                    "slug": "diesel"
                }
            },
            "source": {
                "id": 6,
                "name": "Admin Manual",
                "slug": "admin_manual"
            },
            "status": {
                "id": 2,
                "name": "Confirmed",
                "slug": "confirmed"
            },
            "payment_status": {
                "id": 2,
                "name": "Partial Paid",
                "slug": "partial_paid"
            },
            "pickup_location": {
                "id": 3,
                "name": "Postman Location",
                "slug": "postman-location",
                "address": "Postman Street, Dakhla",
                "delivery_fee": "100.00",
                "is_active": true,
                "location_type": {
                    "id": 1,
                    "name": "Agency",
                    "slug": "agency"
                },
                "created_at": "2026-06-11T23:05:51.000000Z",
                "updated_at": "2026-06-11T23:05:51.000000Z"
            },
            "dropoff_location": {
                "id": 3,
                "name": "Postman Location",
                "slug": "postman-location",
                "address": "Postman Street, Dakhla",
                "delivery_fee": "100.00",
                "is_active": true,
                "location_type": {
                    "id": 1,
                    "name": "Agency",
                    "slug": "agency"
                },
                "created_at": "2026-06-11T23:05:51.000000Z",
                "updated_at": "2026-06-11T23:05:51.000000Z"
            },
            "start_datetime": "2027-01-11T10:00:00.000000Z",
            "end_datetime": "2027-01-15T10:00:00.000000Z",
            "total_days": 4,
            "price_per_day": "375.00",
            "delivery_fee": "200.00",
            "deposit_amount": "3000.00",
            "total_price": "4700.00",
            "customer_notes": null,
            "admin_notes": null,
            "created_by": {
                "id": 1,
                "name": "Limosud Cars Admin",
                "email": "admin@limosudcars.local"
            },
            "confirmed_at": "2026-06-11T20:54:32.000000Z",
            "started_at": null,
            "completed_at": null,
            "cancelled_at": null,
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:54:32.000000Z"
        }
    ],
    "links": {
        "first": "http://127.0.0.1:8000/api/admin/reservations?page=1",
        "last": "http://127.0.0.1:8000/api/admin/reservations?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "page": null,
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/admin/reservations?page=1",
                "label": "1",
                "page": 1,
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "page": null,
                "active": false
            }
        ],
        "path": "http://127.0.0.1:8000/api/admin/reservations",
        "per_page": 15,
        "to": 10,
        "total": 10
    }
}
```

**Error responses**

401 if unauthenticated.
403 without reservations.view.
500 for unexpected server errors.

**Render in UI**

Reservation number, customer, vehicle, status, payment_status, dates, pricing totals, notes, lifecycle timestamps.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Usually none unless navigating to detail/edit screens.

**Notes**

List is paginated and ordered latest first.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/reservations`, {
  method: 'GET',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 51. `POST` `/api/admin/reservations`

| Item | Value |
|------|-------|
| When to call | Creates a pending admin manual reservation. |
| Auth | Admin Bearer token |
| Permission | `reservations.create` |

**Query params**

None.

**Path params**

None.

**Request body**

```json
{"customer_id":1,"vehicle_id":1,"pickup_location_id":1,"dropoff_location_id":1,"start_datetime":"2026-08-01 10:00:00","end_datetime":"2026-08-05 10:00:00","customer_notes":"Postman admin reservation.","admin_notes":"QA test."}
```

**Captured success response**

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
                "id": 4,
                "name": "Maintenance",
                "slug": "maintenance"
            },
            "transmission_type": {
                "id": 1,
                "name": "Manual",
                "slug": "manual"
            },
            "fuel_type": {
                "id": 2,
                "name": "Diesel",
                "slug": "diesel"
            }
        },
        "source": {
            "id": 6,
            "name": "Admin Manual",
            "slug": "admin_manual"
        },
        "status": {
            "id": 1,
            "name": "Pending",
            "slug": "pending"
        },
        "payment_status": {
            "id": 1,
            "name": "Unpaid",
            "slug": "unpaid"
        },
        "pickup_location": {
            "id": 3,
            "name": "Postman Location",
            "slug": "postman-location",
            "address": "Postman Street, Dakhla",
            "delivery_fee": "100.00",
            "is_active": true,
            "location_type": {
                "id": 1,
                "name": "Agency",
                "slug": "agency"
            },
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        },
        "dropoff_location": {
            "id": 3,
            "name": "Postman Location",
            "slug": "postman-location",
            "address": "Postman Street, Dakhla",
            "delivery_fee": "100.00",
            "is_active": true,
            "location_type": {
                "id": 1,
                "name": "Agency",
                "slug": "agency"
            },
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        },
        "start_datetime": "2026-11-01T10:00:00.000000Z",
        "end_datetime": "2026-11-05T10:00:00.000000Z",
        "total_days": 4,
        "price_per_day": "390.00",
        "delivery_fee": "200.00",
        "deposit_amount": "3000.00",
        "total_price": "4760.00",
        "customer_notes": "Postman admin reservation.",
        "admin_notes": "QA disposable reservation.",
        "created_by": {
            "id": 1,
            "name": "Limosud Cars Admin",
            "email": "admin@limosudcars.local"
        },
        "confirmed_at": null,
        "started_at": null,
        "completed_at": null,
        "cancelled_at": null,
        "created_at": "2026-06-11T23:55:13.000000Z",
        "updated_at": "2026-06-11T23:55:13.000000Z"
    }
}
```

**Error responses**

401 if unauthenticated.
403 without reservations.create.
422 for invalid IDs, invalid date order, or overlapping confirmed/in_progress reservation.
500 for unexpected server errors.

**Render in UI**

Reservation number, customer, vehicle, status, payment_status, dates, pricing totals, notes, lifecycle timestamps.

**Send from UI**

customer_id, vehicle_id, pickup_location_id, dropoff_location_id, start_datetime, end_datetime, customer_notes, admin_notes

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Store `data.id` returned by create endpoints for follow-up screens.

**Notes**

Admin-created reservations start pending and unpaid. Pending reservations do not reserve vehicles.

**Example call**

```javascript
const payload = {"customer_id":1,"vehicle_id":1,"pickup_location_id":1,"dropoff_location_id":1,"start_datetime":"2026-08-01 10:00:00","end_datetime":"2026-08-05 10:00:00","customer_notes":"Postman admin reservation.","admin_notes":"QA test."};

const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/reservations`, {
  method: 'POST',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {}),
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(payload)
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 52. `POST` `/api/admin/reservations/check-availability`

| Item | Value |
|------|-------|
| When to call | Checks vehicle availability for admin create/edit workflows. |
| Auth | Admin Bearer token |
| Permission | `reservations.view` |

**Query params**

None.

**Path params**

None.

**Request body**

```json
{"vehicle_id":1,"start_datetime":"2026-08-01 10:00:00","end_datetime":"2026-08-05 10:00:00","ignore_reservation_id":null}
```

**Captured success response**

```json
{
    "available": true
}
```

**Error responses**

401 if unauthenticated.
403 without reservations.view.
422 for invalid vehicle_id or invalid date order.
500 for unexpected server errors.

**Render in UI**

Reservation number, customer, vehicle, status, payment_status, dates, pricing totals, notes, lifecycle timestamps.

**Send from UI**

vehicle_id, start_datetime, end_datetime, ignore_reservation_id

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Usually none unless navigating to detail/edit screens.

**Notes**

ignore_reservation_id can be used when editing an existing reservation.

**Example call**

```javascript
const payload = {"vehicle_id":1,"start_datetime":"2026-08-01 10:00:00","end_datetime":"2026-08-05 10:00:00","ignore_reservation_id":null};

const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/reservations/check-availability`, {
  method: 'POST',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {}),
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(payload)
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 53. `GET` `/api/admin/reservations-calendar`

| Item | Value |
|------|-------|
| When to call | Returns a non-paginated reservation collection for calendar displays. |
| Auth | Admin Bearer token |
| Permission | `reservations.view` |

**Query params**

start: optional date. Includes reservations ending on or after this value.
end: optional date. Includes reservations starting on or before this value.

**Path params**

None.

**Request body**

None.

**Captured success response**

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
                    "id": 4,
                    "name": "Maintenance",
                    "slug": "maintenance"
                },
                "transmission_type": {
                    "id": 1,
                    "name": "Manual",
                    "slug": "manual"
                },
                "fuel_type": {
                    "id": 2,
                    "name": "Diesel",
                    "slug": "diesel"
                }
            },
            "source": {
                "id": 6,
                "name": "Admin Manual",
                "slug": "admin_manual"
            },
            "status": {
                "id": 1,
                "name": "Pending",
                "slug": "pending"
            },
            "payment_status": {
                "id": 1,
                "name": "Unpaid",
                "slug": "unpaid"
            },
            "pickup_location": {
                "id": 3,
                "name": "Postman Location",
                "slug": "postman-location",
                "address": "Postman Street, Dakhla",
                "delivery_fee": "100.00",
                "is_active": true,
                "location_type": {
                    "id": 1,
                    "name": "Agency",
                    "slug": "agency"
                },
                "created_at": "2026-06-11T23:05:51.000000Z",
                "updated_at": "2026-06-11T23:05:51.000000Z"
            },
            "dropoff_location": {
                "id": 3,
                "name": "Postman Location",
                "slug": "postman-location",
                "address": "Postman Street, Dakhla",
                "delivery_fee": "100.00",
                "is_active": true,
                "location_type": {
                    "id": 1,
                    "name": "Agency",
                    "slug": "agency"
                },
                "created_at": "2026-06-11T23:05:51.000000Z",
                "updated_at": "2026-06-11T23:05:51.000000Z"
            },
            "start_datetime": "2026-08-11T10:00:00.000000Z",
            "end_datetime": "2026-08-15T10:00:00.000000Z",
            "total_days": 4,
            "price_per_day": "375.00",
            "delivery_fee": "200.00",
            "deposit_amount": "3000.00",
            "total_price": "4700.00",
            "customer_notes": "Postman admin reservation.",
            "admin_notes": "QA test.",
            "created_by": {
                "id": 1,
                "name": "Limosud Cars Admin",
                "email": "admin@limosudcars.local"
            },
            "confirmed_at": null,
            "started_at": null,
            "completed_at": null,
            "cancelled_at": null,
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        },
        {
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
                "status": {
                    "id": 1,
                    "name": "Available",
                    "slug": "available"
                },
                "transmission_type": {
                    "id": 1,
                    "name": "Manual",
                    "slug": "manual"
                },
                "fuel_type": {
                    "id": 2,
                    "name": "Diesel",
                    "slug": "diesel"
                }
            },
            "source": {
                "id": 6,
                "name": "Admin Manual",
                "slug": "admin_manual"
            },
            "status": {
                "id": 1,
                "name": "Pending",
                "slug": "pending"
            },
            "payment_status": {
                "id": 1,
                "name": "Unpaid",
                "slug": "unpaid"
            },
            "pickup_location": {
                "id": 3,
                "name": "Postman Location",
                "slug": "postman-location",
                "address": "Postman Street, Dakhla",
                "delivery_fee": "100.00",
                "is_active": true,
                "location_type": {
                    "id": 1,
                    "name": "Agency",
                    "slug": "agency"
                },
                "created_at": "2026-06-11T23:05:51.000000Z",
                "updated_at": "2026-06-11T23:05:51.000000Z"
            },
            "dropoff_location": {
                "id": 3,
                "name": "Postman Location",
                "slug": "postman-location",
                "address": "Postman Street, Dakhla",
                "delivery_fee": "100.00",
                "is_active": true,
                "location_type": {
                    "id": 1,
                    "name": "Agency",
                    "slug": "agency"
                },
                "created_at": "2026-06-11T23:05:51.000000Z",
                "updated_at": "2026-06-11T23:05:51.000000Z"
            },
            "start_datetime": "2026-09-11T10:00:00.000000Z",
            "end_datetime": "2026-09-14T10:00:00.000000Z",
            "total_days": 3,
            "price_per_day": "375.00",
            "delivery_fee": "200.00",
            "deposit_amount": "3000.00",
            "total_price": "4325.00",
            "customer_notes": null,
            "admin_notes": "Lifecycle QA reservation.",
            "created_by": {
                "id": 1,
                "name": "Limosud Cars Admin",
                "email": "admin@limosudcars.local"
            },
            "confirmed_at": "2026-06-11T23:19:33.000000Z",
            "started_at": "2026-06-11T23:19:33.000000Z",
            "completed_at": "2026-06-11T23:19:34.000000Z",
            "cancelled_at": null,
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:54:32.000000Z"
        },
        {
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
                "status": {
                    "id": 2,
                    "name": "Reserved",
                    "slug": "reserved"
                },
                "transmission_type": {
                    "id": 1,
                    "name": "Manual",
                    "slug": "manual"
                },
                "fuel_type": {
                    "id": 2,
                    "name": "Diesel",
                    "slug": "diesel"
                }
            },
            "source": {
                "id": 6,
                "name": "Admin Manual",
                "slug": "admin_manual"
            },
            "status": {
                "id": 2,
                "name": "Confirmed",
                "slug": "confirmed"
            },
            "payment_status": {
                "id": 1,
                "name": "Unpaid",
                "slug": "unpaid"
            },
            "pickup_location": {
                "id": 3,
                "name": "Postman Location",
                "slug": "postman-location",
                "address": "Postman Street, Dakhla",
                "delivery_fee": "100.00",
                "is_active": true,
                "location_type": {
                    "id": 1,
                    "name": "Agency",
                    "slug": "agency"
                },
                "created_at": "2026-06-11T23:05:51.000000Z",
                "updated_at": "2026-06-11T23:05:51.000000Z"
            },
            "dropoff_location": {
                "id": 3,
                "name": "Postman Location",
                "slug": "postman-location",
                "address": "Postman Street, Dakhla",
                "delivery_fee": "100.00",
                "is_active": true,
                "location_type": {
                    "id": 1,
                    "name": "Agency",
                    "slug": "agency"
                },
                "created_at": "2026-06-11T23:05:51.000000Z",
                "updated_at": "2026-06-11T23:05:51.000000Z"
            },
            "start_datetime": "2026-10-11T10:00:00.000000Z",
            "end_datetime": "2026-10-14T10:00:00.000000Z",
            "total_days": 3,
            "price_per_day": "375.00",
            "delivery_fee": "200.00",
            "deposit_amount": "3000.00",
            "total_price": "4325.00",
            "customer_notes": null,
            "admin_notes": null,
            "created_by": {
                "id": 1,
                "name": "Limosud Cars Admin",
                "email": "admin@limosudcars.local"
            },
            "confirmed_at": "2026-06-11T22:54:32.000000Z",
            "started_at": null,
            "completed_at": null,
            "cancelled_at": "2026-06-11T23:19:35.000000Z",
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:54:32.000000Z"
        },
        {
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
                    "id": 4,
                    "name": "Maintenance",
                    "slug": "maintenance"
                },
                "transmission_type": {
                    "id": 1,
                    "name": "Manual",
                    "slug": "manual"
                },
                "fuel_type": {
                    "id": 2,
                    "name": "Diesel",
                    "slug": "diesel"
                }
            },
            "source": {
                "id": 6,
                "name": "Admin Manual",
                "slug": "admin_manual"
            },
            "status": {
                "id": 1,
                "name": "Pending",
                "slug": "pending"
            },
            "payment_status": {
                "id": 1,
                "name": "Unpaid",
                "slug": "unpaid"
            },
            "pickup_location": {
                "id": 3,
                "name": "Postman Location",
                "slug": "postman-location",
                "address": "Postman Street, Dakhla",
                "delivery_fee": "100.00",
                "is_active": true,
                "location_type": {
                    "id": 1,
                    "name": "Agency",
                    "slug": "agency"
                },
                "created_at": "2026-06-11T23:05:51.000000Z",
                "updated_at": "2026-06-11T23:05:51.000000Z"
            },
            "dropoff_location": {
                "id": 3,
                "name": "Postman Location",
                "slug": "postman-location",
                "address": "Postman Street, Dakhla",
                "delivery_fee": "100.00",
                "is_active": true,
                "location_type": {
                    "id": 1,
                    "name": "Agency",
                    "slug": "agency"
                },
                "created_at": "2026-06-11T23:05:51.000000Z",
                "updated_at": "2026-06-11T23:05:51.000000Z"
            },
            "start_datetime": "2026-11-01T10:00:00.000000Z",
            "end_datetime": "2026-11-05T10:00:00.000000Z",
            "total_days": 4,
            "price_per_day": "390.00",
            "delivery_fee": "200.00",
            "deposit_amount": "3000.00",
            "total_price": "4760.00",
            "customer_notes": "Postman admin reservation.",
            "admin_notes": "QA disposable reservation.",
            "created_by": {
                "id": 1,
                "name": "Limosud Cars Admin",
                "email": "admin@limosudcars.local"
            },
            "confirmed_at": null,
            "started_at": null,
            "completed_at": null,
            "cancelled_at": null,
            "created_at": "2026-06-11T23:55:13.000000Z",
            "updated_at": "2026-06-11T23:55:13.000000Z"
        },
        {
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
                "status": {
                    "id": 1,
                    "name": "Available",
                    "slug": "available"
                },
                "transmission_type": {
                    "id": 1,
                    "name": "Manual",
                    "slug": "manual"
                },
                "fuel_type": {
                    "id": 2,
                    "name": "Diesel",
                    "slug": "diesel"
                }
            },
            "source": {
                "id": 1,
                "name": "Website",
                "slug": "website"
            },
            "status": {
                "id": 1,
                "name": "Pending",
                "slug": "pending"
            },
            "payment_status": {
                "id": 1,
                "name": "Unpaid",
                "slug": "unpaid"
            },
            "pickup_location": {
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
            "dropoff_location": {
                "id": 2,
                "name": "Dakhla Airport",
                "slug": "dakhla-airport",
                "address": "Dakhla Airport, Morocco",
                "delivery_fee": "150.00",
                "is_active": true,
                "location_type": {
                    "id": 2,
                    "name": "Airport",
                    "slug": "airport"
                },
                "created_at": "2026-06-11T23:05:51.000000Z",
                "updated_at": "2026-06-11T23:05:51.000000Z"
            },
            "start_datetime": "2026-11-11T10:00:00.000000Z",
            "end_datetime": "2026-11-13T10:00:00.000000Z",
            "total_days": 2,
            "price_per_day": "375.00",
            "delivery_fee": "150.00",
            "deposit_amount": "3000.00",
            "total_price": "3450.00",
            "customer_notes": "Reject flow QA.",
            "admin_notes": null,
            "created_by": null,
            "confirmed_at": null,
            "started_at": null,
            "completed_at": null,
            "cancelled_at": null,
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:54:32.000000Z"
        },
        {
            "id": 7,
            "reservation_number": "RSV-20260611-3472",
            "customer": {
                "id": 2,
                "full_name": "Postman Public Customer",
                "nationality": "Moroccan",
                "phone": "+212600000000",
                "email": "postman.public@example.com",
                "passport_or_cin": "PM123456",
                "driving_license_number": "PM-DL-001",
                "created_at": "2026-06-11T23:12:43.000000Z",
                "updated_at": "2026-06-11T23:12:43.000000Z"
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
                "seats": 5,
                "doors": 5,
                "daily_price": "350.00",
                "weekly_price": "2200.00",
                "monthly_price": "8500.00",
                "deposit_amount": "3000.00",
                "description": "Reliable economy vehicle for Dakhla rentals.",
                "is_featured": true,
                "is_active": true,
                "brand": {
                    "id": 1,
                    "name": "Dacia",
                    "slug": "dacia"
                },
                "category": {
                    "id": 1,
                    "name": "Economy",
                    "slug": "economy"
                },
                "status": {
                    "id": 1,
                    "name": "Available",
                    "slug": "available"
                },
                "transmission_type": {
                    "id": 1,
                    "name": "Manual",
                    "slug": "manual"
                },
                "fuel_type": {
                    "id": 2,
                    "name": "Diesel",
                    "slug": "diesel"
                }
            },
            "source": {
                "id": 1,
                "name": "Website",
                "slug": "website"
            },
            "status": {
                "id": 1,
                "name": "Pending",
                "slug": "pending"
            },
            "payment_status": {
                "id": 1,
                "name": "Unpaid",
                "slug": "unpaid"
            },
            "pickup_location": {
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
            "dropoff_location": {
                "id": 2,
                "name": "Dakhla Airport",
                "slug": "dakhla-airport",
                "address": "Dakhla Airport, Morocco",
                "delivery_fee": "150.00",
                "is_active": true,
                "location_type": {
                    "id": 2,
                    "name": "Airport",
                    "slug": "airport"
                },
                "created_at": "2026-06-11T23:05:51.000000Z",
                "updated_at": "2026-06-11T23:05:51.000000Z"
            },
            "start_datetime": "2026-12-01T10:00:00.000000Z",
            "end_datetime": "2026-12-05T10:00:00.000000Z",
            "total_days": 4,
            "price_per_day": "350.00",
            "delivery_fee": "150.00",
            "deposit_amount": "3000.00",
            "total_price": "4550.00",
            "customer_notes": "Airport pickup please.",
            "admin_notes": null,
            "created_by": null,
            "confirmed_at": null,
            "started_at": null,
            "completed_at": null,
            "cancelled_at": null,
            "created_at": "2026-06-11T23:12:43.000000Z",
            "updated_at": "2026-06-11T23:12:43.000000Z"
        },
        {
            "id": 8,
            "reservation_number": "RSV-20260611-9743",
            "customer": {
                "id": 3,
                "full_name": "Postman Public Customer",
                "nationality": "Moroccan",
                "phone": "+212600000000",
                "email": "postman.public@example.com",
                "passport_or_cin": "PM123456",
                "driving_license_number": "PM-DL-001",
                "created_at": "2026-06-11T23:15:51.000000Z",
                "updated_at": "2026-06-11T23:15:51.000000Z"
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
                "seats": 5,
                "doors": 5,
                "daily_price": "350.00",
                "weekly_price": "2200.00",
                "monthly_price": "8500.00",
                "deposit_amount": "3000.00",
                "description": "Reliable economy vehicle for Dakhla rentals.",
                "is_featured": true,
                "is_active": true,
                "brand": {
                    "id": 1,
                    "name": "Dacia",
                    "slug": "dacia"
                },
                "category": {
                    "id": 1,
                    "name": "Economy",
                    "slug": "economy"
                },
                "status": {
                    "id": 1,
                    "name": "Available",
                    "slug": "available"
                },
                "transmission_type": {
                    "id": 1,
                    "name": "Manual",
                    "slug": "manual"
                },
                "fuel_type": {
                    "id": 2,
                    "name": "Diesel",
                    "slug": "diesel"
                }
            },
            "source": {
                "id": 1,
                "name": "Website",
                "slug": "website"
            },
            "status": {
                "id": 1,
                "name": "Pending",
                "slug": "pending"
            },
            "payment_status": {
                "id": 1,
                "name": "Unpaid",
                "slug": "unpaid"
            },
            "pickup_location": {
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
            "dropoff_location": {
                "id": 2,
                "name": "Dakhla Airport",
                "slug": "dakhla-airport",
                "address": "Dakhla Airport, Morocco",
                "delivery_fee": "150.00",
                "is_active": true,
                "location_type": {
                    "id": 2,
                    "name": "Airport",
                    "slug": "airport"
                },
                "created_at": "2026-06-11T23:05:51.000000Z",
                "updated_at": "2026-06-11T23:05:51.000000Z"
            },
            "start_datetime": "2026-12-01T10:00:00.000000Z",
            "end_datetime": "2026-12-05T10:00:00.000000Z",
            "total_days": 4,
            "price_per_day": "350.00",
            "delivery_fee": "150.00",
            "deposit_amount": "3000.00",
            "total_price": "4550.00",
            "customer_notes": "Airport pickup please.",
            "admin_notes": null,
            "created_by": null,
            "confirmed_at": null,
            "started_at": null,
            "completed_at": null,
            "cancelled_at": null,
            "created_at": "2026-06-11T23:15:51.000000Z",
            "updated_at": "2026-06-11T23:15:51.000000Z"
        },
        {
            "id": 10,
            "reservation_number": "RSV-20260611-2314",
            "customer": {
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
            "vehicle": {
                "id": 1,
                "name": "Dacia Sandero 2024",
                "slug": "dacia-sandero-2024",
                "model": "Sandero",
                "year": 2024,
                "plate_number": "12345-A-10",
                "mileage": 12500,
                "current_mileage_updated_at": "2026-06-10T23:54:32.000000Z",
                "seats": 5,
                "doors": 5,
                "daily_price": "350.00",
                "weekly_price": "2200.00",
                "monthly_price": "8500.00",
                "deposit_amount": "3000.00",
                "description": "Reliable economy vehicle for Dakhla rentals.",
                "is_featured": true,
                "is_active": true,
                "brand": {
                    "id": 1,
                    "name": "Dacia",
                    "slug": "dacia"
                },
                "category": {
                    "id": 1,
                    "name": "Economy",
                    "slug": "economy"
                },
                "status": {
                    "id": 1,
                    "name": "Available",
                    "slug": "available"
                },
                "transmission_type": {
                    "id": 1,
                    "name": "Manual",
                    "slug": "manual"
                },
                "fuel_type": {
                    "id": 2,
                    "name": "Diesel",
                    "slug": "diesel"
                }
            },
            "source": {
                "id": 1,
                "name": "Website",
                "slug": "website"
            },
            "status": {
                "id": 1,
                "name": "Pending",
                "slug": "pending"
            },
            "payment_status": {
                "id": 1,
                "name": "Unpaid",
                "slug": "unpaid"
            },
            "pickup_location": {
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
            "dropoff_location": {
                "id": 2,
                "name": "Dakhla Airport",
                "slug": "dakhla-airport",
                "address": "Dakhla Airport, Morocco",
                "delivery_fee": "150.00",
                "is_active": true,
                "location_type": {
                    "id": 2,
                    "name": "Airport",
                    "slug": "airport"
                },
                "created_at": "2026-06-11T23:05:51.000000Z",
                "updated_at": "2026-06-11T23:05:51.000000Z"
            },
            "start_datetime": "2026-12-01T10:00:00.000000Z",
            "end_datetime": "2026-12-05T10:00:00.000000Z",
            "total_days": 4,
            "price_per_day": "350.00",
            "delivery_fee": "150.00",
            "deposit_amount": "3000.00",
            "total_price": "4550.00",
            "customer_notes": "Airport pickup please.",
            "admin_notes": null,
            "created_by": null,
            "confirmed_at": null,
            "started_at": null,
            "completed_at": null,
            "cancelled_at": null,
            "created_at": "2026-06-11T23:18:53.000000Z",
            "updated_at": "2026-06-11T23:18:53.000000Z"
        },
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
                "seats": 5,
                "doors": 5,
                "daily_price": "350.00",
                "weekly_price": "2200.00",
                "monthly_price": "8500.00",
                "deposit_amount": "3000.00",
                "description": "Reliable economy vehicle for Dakhla rentals.",
                "is_featured": true,
                "is_active": true,
                "brand": {
                    "id": 1,
                    "name": "Dacia",
                    "slug": "dacia"
                },
                "category": {
                    "id": 1,
                    "name": "Economy",
                    "slug": "economy"
                },
                "status": {
                    "id": 1,
                    "name": "Available",
                    "slug": "available"
                },
                "transmission_type": {
                    "id": 1,
                    "name": "Manual",
                    "slug": "manual"
                },
                "fuel_type": {
                    "id": 2,
                    "name": "Diesel",
                    "slug": "diesel"
                }
            },
            "source": {
                "id": 1,
                "name": "Website",
                "slug": "website"
            },
            "status": {
                "id": 1,
                "name": "Pending",
                "slug": "pending"
            },
            "payment_status": {
                "id": 1,
                "name": "Unpaid",
                "slug": "unpaid"
            },
            "pickup_location": {
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
            "dropoff_location": {
                "id": 2,
                "name": "Dakhla Airport",
                "slug": "dakhla-airport",
                "address": "Dakhla Airport, Morocco",
                "delivery_fee": "150.00",
                "is_active": true,
                "location_type": {
                    "id": 2,
                    "name": "Airport",
                    "slug": "airport"
                },
                "created_at": "2026-06-11T23:05:51.000000Z",
                "updated_at": "2026-06-11T23:05:51.000000Z"
            },
            "start_datetime": "2026-12-01T10:00:00.000000Z",
            "end_datetime": "2026-12-05T10:00:00.000000Z",
            "total_days": 4,
            "price_per_day": "350.00",
            "delivery_fee": "150.00",
            "deposit_amount": "3000.00",
            "total_price": "4550.00",
            "customer_notes": "Airport pickup please.",
            "admin_notes": null,
            "created_by": null,
            "confirmed_at": null,
            "started_at": null,
            "completed_at": null,
            "cancelled_at": null,
            "created_at": "2026-06-11T23:54:43.000000Z",
            "updated_at": "2026-06-11T23:54:43.000000Z"
        },
        {
            "id": 5,
            "reservation_number": "RSV-QA-CONTRACT-001",
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
                "id": 6,
                "name": "QA Contract Vehicle",
                "slug": "qa-contract-vehicle",
                "model": "Sandero",
                "year": 2024,
                "plate_number": "QA-CONTRACT-01",
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
                "status": {
                    "id": 1,
                    "name": "Available",
                    "slug": "available"
                },
                "transmission_type": {
                    "id": 1,
                    "name": "Manual",
                    "slug": "manual"
                },
                "fuel_type": {
                    "id": 2,
                    "name": "Diesel",
                    "slug": "diesel"
                }
            },
            "source": {
                "id": 6,
                "name": "Admin Manual",
                "slug": "admin_manual"
            },
            "status": {
                "id": 2,
                "name": "Confirmed",
                "slug": "confirmed"
            },
            "payment_status": {
                "id": 1,
                "name": "Unpaid",
                "slug": "unpaid"
            },
            "pickup_location": {
                "id": 3,
                "name": "Postman Location",
                "slug": "postman-location",
                "address": "Postman Street, Dakhla",
                "delivery_fee": "100.00",
                "is_active": true,
                "location_type": {
                    "id": 1,
                    "name": "Agency",
                    "slug": "agency"
                },
                "created_at": "2026-06-11T23:05:51.000000Z",
                "updated_at": "2026-06-11T23:05:51.000000Z"
            },
            "dropoff_location": {
                "id": 3,
                "name": "Postman Location",
                "slug": "postman-location",
                "address": "Postman Street, Dakhla",
                "delivery_fee": "100.00",
                "is_active": true,
                "location_type": {
                    "id": 1,
                    "name": "Agency",
                    "slug": "agency"
                },
                "created_at": "2026-06-11T23:05:51.000000Z",
                "updated_at": "2026-06-11T23:05:51.000000Z"
            },
            "start_datetime": "2026-12-11T10:00:00.000000Z",
            "end_datetime": "2026-12-15T10:00:00.000000Z",
            "total_days": 4,
            "price_per_day": "375.00",
            "delivery_fee": "200.00",
            "deposit_amount": "3000.00",
            "total_price": "4700.00",
            "customer_notes": null,
            "admin_notes": null,
            "created_by": {
                "id": 1,
                "name": "Limosud Cars Admin",
                "email": "admin@limosudcars.local"
            },
            "confirmed_at": "2026-06-11T21:54:32.000000Z",
            "started_at": null,
            "completed_at": null,
            "cancelled_at": null,
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:54:32.000000Z"
        }
    ]
}
```

**Error responses**

401 if unauthenticated.
403 without reservations.view.
500 for unexpected server errors.

**Render in UI**

Reservation number, customer, vehicle, status, payment_status, dates, pricing totals, notes, lifecycle timestamps.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Usually none unless navigating to detail/edit screens.

**Notes**

This route is not paginated.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/reservations-calendar`, {
  method: 'GET',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 54. `GET` `/api/admin/reservations/{reservation}`

| Item | Value |
|------|-------|
| When to call | Shows one reservation with all loaded relationships. |
| Auth | Admin Bearer token |
| Permission | `reservations.view` |

**Query params**

None.

**Path params**

reservation: numeric reservation ID.

**Request body**

None.

**Captured success response**

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
                "id": 4,
                "name": "Maintenance",
                "slug": "maintenance"
            },
            "transmission_type": {
                "id": 1,
                "name": "Manual",
                "slug": "manual"
            },
            "fuel_type": {
                "id": 2,
                "name": "Diesel",
                "slug": "diesel"
            }
        },
        "source": {
            "id": 6,
            "name": "Admin Manual",
            "slug": "admin_manual"
        },
        "status": {
            "id": 1,
            "name": "Pending",
            "slug": "pending"
        },
        "payment_status": {
            "id": 1,
            "name": "Unpaid",
            "slug": "unpaid"
        },
        "pickup_location": {
            "id": 3,
            "name": "Postman Location",
            "slug": "postman-location",
            "address": "Postman Street, Dakhla",
            "delivery_fee": "100.00",
            "is_active": true,
            "location_type": {
                "id": 1,
                "name": "Agency",
                "slug": "agency"
            },
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        },
        "dropoff_location": {
            "id": 3,
            "name": "Postman Location",
            "slug": "postman-location",
            "address": "Postman Street, Dakhla",
            "delivery_fee": "100.00",
            "is_active": true,
            "location_type": {
                "id": 1,
                "name": "Agency",
                "slug": "agency"
            },
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        },
        "start_datetime": "2026-11-01T10:00:00.000000Z",
        "end_datetime": "2026-11-05T10:00:00.000000Z",
        "total_days": 4,
        "price_per_day": "390.00",
        "delivery_fee": "200.00",
        "deposit_amount": "3000.00",
        "total_price": "4760.00",
        "customer_notes": "Postman admin reservation.",
        "admin_notes": "QA disposable reservation.",
        "created_by": {
            "id": 1,
            "name": "Limosud Cars Admin",
            "email": "admin@limosudcars.local"
        },
        "confirmed_at": null,
        "started_at": null,
        "completed_at": null,
        "cancelled_at": null,
        "created_at": "2026-06-11T23:55:13.000000Z",
        "updated_at": "2026-06-11T23:55:13.000000Z"
    }
}
```

**Error responses**

401 if unauthenticated.
403 without reservations.view.
404 for invalid reservation ID.
500 for unexpected server errors.

**Render in UI**

Reservation number, customer, vehicle, status, payment_status, dates, pricing totals, notes, lifecycle timestamps.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Path parameter `reservation` from route or list selection.

**Notes**

This response is the reference shape for most reservation action endpoints.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/reservations/${reservation}`, {
  method: 'GET',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 55. `PUT` `/api/admin/reservations/{reservation}`

| Item | Value |
|------|-------|
| When to call | Updates reservation booking inputs and recalculates pricing. |
| Auth | Admin Bearer token |
| Permission | `reservations.update` |

**Query params**

None.

**Path params**

reservation: numeric reservation ID.

**Request body**

```json
{"customer_id":1,"vehicle_id":1,"pickup_location_id":1,"dropoff_location_id":1,"start_datetime":"2026-08-02 10:00:00","end_datetime":"2026-08-06 10:00:00","admin_notes":"Postman updated reservation notes."}
```

**Captured success response**

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
                "id": 4,
                "name": "Maintenance",
                "slug": "maintenance"
            },
            "transmission_type": {
                "id": 1,
                "name": "Manual",
                "slug": "manual"
            },
            "fuel_type": {
                "id": 2,
                "name": "Diesel",
                "slug": "diesel"
            }
        },
        "source": {
            "id": 6,
            "name": "Admin Manual",
            "slug": "admin_manual"
        },
        "status": {
            "id": 1,
            "name": "Pending",
            "slug": "pending"
        },
        "payment_status": {
            "id": 1,
            "name": "Unpaid",
            "slug": "unpaid"
        },
        "pickup_location": {
            "id": 3,
            "name": "Postman Location",
            "slug": "postman-location",
            "address": "Postman Street, Dakhla",
            "delivery_fee": "100.00",
            "is_active": true,
            "location_type": {
                "id": 1,
                "name": "Agency",
                "slug": "agency"
            },
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        },
        "dropoff_location": {
            "id": 3,
            "name": "Postman Location",
            "slug": "postman-location",
            "address": "Postman Street, Dakhla",
            "delivery_fee": "100.00",
            "is_active": true,
            "location_type": {
                "id": 1,
                "name": "Agency",
                "slug": "agency"
            },
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        },
        "start_datetime": "2026-11-02T10:00:00.000000Z",
        "end_datetime": "2026-11-06T10:00:00.000000Z",
        "total_days": 4,
        "price_per_day": "390.00",
        "delivery_fee": "200.00",
        "deposit_amount": "3000.00",
        "total_price": "4760.00",
        "customer_notes": "Postman admin reservation.",
        "admin_notes": "Postman updated reservation notes.",
        "created_by": {
            "id": 1,
            "name": "Limosud Cars Admin",
            "email": "admin@limosudcars.local"
        },
        "confirmed_at": null,
        "started_at": null,
        "completed_at": null,
        "cancelled_at": null,
        "created_at": "2026-06-11T23:55:13.000000Z",
        "updated_at": "2026-06-11T23:55:16.000000Z"
    }
}
```

**Error responses**

401 if unauthenticated.
403 without reservations.update.
404 for invalid reservation ID.
422 for invalid IDs, date order, or overlaps.
500 for unexpected server errors.

**Render in UI**

Reservation number, customer, vehicle, status, payment_status, dates, pricing totals, notes, lifecycle timestamps.

**Send from UI**

customer_id, vehicle_id, pickup_location_id, dropoff_location_id, start_datetime, end_datetime, admin_notes

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Path parameter `reservation` from route or list selection.

**Notes**

Changing vehicle or dates triggers availability checks.

**Example call**

```javascript
const payload = {"customer_id":1,"vehicle_id":1,"pickup_location_id":1,"dropoff_location_id":1,"start_datetime":"2026-08-02 10:00:00","end_datetime":"2026-08-06 10:00:00","admin_notes":"Postman updated reservation notes."};

const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/reservations/${reservation}`, {
  method: 'PUT',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {}),
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(payload)
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 56. `PATCH` `/api/admin/reservations/{reservation}`

| Item | Value |
|------|-------|
| When to call | Partially updates a reservation. |
| Auth | Admin Bearer token |
| Permission | `reservations.update` |

**Query params**

None.

**Path params**

reservation: numeric reservation ID.

**Request body**

```json
{"admin_notes":"Partial update from Postman."}
```

**Captured success response**

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
                "id": 4,
                "name": "Maintenance",
                "slug": "maintenance"
            },
            "transmission_type": {
                "id": 1,
                "name": "Manual",
                "slug": "manual"
            },
            "fuel_type": {
                "id": 2,
                "name": "Diesel",
                "slug": "diesel"
            }
        },
        "source": {
            "id": 6,
            "name": "Admin Manual",
            "slug": "admin_manual"
        },
        "status": {
            "id": 1,
            "name": "Pending",
            "slug": "pending"
        },
        "payment_status": {
            "id": 1,
            "name": "Unpaid",
            "slug": "unpaid"
        },
        "pickup_location": {
            "id": 3,
            "name": "Postman Location",
            "slug": "postman-location",
            "address": "Postman Street, Dakhla",
            "delivery_fee": "100.00",
            "is_active": true,
            "location_type": {
                "id": 1,
                "name": "Agency",
                "slug": "agency"
            },
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        },
        "dropoff_location": {
            "id": 3,
            "name": "Postman Location",
            "slug": "postman-location",
            "address": "Postman Street, Dakhla",
            "delivery_fee": "100.00",
            "is_active": true,
            "location_type": {
                "id": 1,
                "name": "Agency",
                "slug": "agency"
            },
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        },
        "start_datetime": "2026-11-02T10:00:00.000000Z",
        "end_datetime": "2026-11-06T10:00:00.000000Z",
        "total_days": 4,
        "price_per_day": "390.00",
        "delivery_fee": "200.00",
        "deposit_amount": "3000.00",
        "total_price": "4760.00",
        "customer_notes": "Postman admin reservation.",
        "admin_notes": "Partial update from Postman.",
        "created_by": {
            "id": 1,
            "name": "Limosud Cars Admin",
            "email": "admin@limosudcars.local"
        },
        "confirmed_at": null,
        "started_at": null,
        "completed_at": null,
        "cancelled_at": null,
        "created_at": "2026-06-11T23:55:13.000000Z",
        "updated_at": "2026-06-11T23:55:16.000000Z"
    }
}
```

**Error responses**

401 if unauthenticated.
403 without reservations.update.
404 for invalid reservation ID.
422 for invalid values or overlap if booking fields change.
500 for unexpected server errors.

**Render in UI**

Reservation number, customer, vehicle, status, payment_status, dates, pricing totals, notes, lifecycle timestamps.

**Send from UI**

admin_notes

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Path parameter `reservation` from route or list selection.

**Notes**

Use PATCH for notes or small edits.

**Example call**

```javascript
const payload = {"admin_notes":"Partial update from Postman."};

const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/reservations/${reservation}`, {
  method: 'PATCH',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {}),
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(payload)
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 57. `DELETE` `/api/admin/reservations/{reservation}`

| Item | Value |
|------|-------|
| When to call | Soft deletes a reservation. |
| Auth | Admin Bearer token |
| Permission | `reservations.delete` |

**Query params**

None.

**Path params**

reservation: numeric reservation ID.

**Request body**

None.

**Captured success response**

```json
HTTP 204 No Content — empty body.
```

**Error responses**

401 if unauthenticated.
403 without reservations.delete.
404 for invalid reservation ID.
500 for unexpected server errors.

**Render in UI**

Reservation number, customer, vehicle, status, payment_status, dates, pricing totals, notes, lifecycle timestamps.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Path parameter `reservation` from route or list selection.

**Notes**

Lifecycle action endpoints are preferred over deletion for business state changes.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/reservations/${reservation}`, {
  method: 'DELETE',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 58. `POST` `/api/admin/reservations/{reservation}/confirm`

| Item | Value |
|------|-------|
| When to call | Confirms a pending reservation and updates the vehicle status to reserved. |
| Auth | Admin Bearer token |
| Permission | `reservations.confirm` |

**Query params**

None.

**Path params**

reservation: numeric reservation ID.

**Request body**

None.

**Captured success response**

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
            "status": {
                "id": 2,
                "name": "Reserved",
                "slug": "reserved"
            },
            "transmission_type": {
                "id": 1,
                "name": "Manual",
                "slug": "manual"
            },
            "fuel_type": {
                "id": 2,
                "name": "Diesel",
                "slug": "diesel"
            }
        },
        "source": {
            "id": 6,
            "name": "Admin Manual",
            "slug": "admin_manual"
        },
        "status": {
            "id": 2,
            "name": "Confirmed",
            "slug": "confirmed"
        },
        "payment_status": {
            "id": 1,
            "name": "Unpaid",
            "slug": "unpaid"
        },
        "pickup_location": {
            "id": 3,
            "name": "Postman Location",
            "slug": "postman-location",
            "address": "Postman Street, Dakhla",
            "delivery_fee": "100.00",
            "is_active": true,
            "location_type": {
                "id": 1,
                "name": "Agency",
                "slug": "agency"
            },
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        },
        "dropoff_location": {
            "id": 3,
            "name": "Postman Location",
            "slug": "postman-location",
            "address": "Postman Street, Dakhla",
            "delivery_fee": "100.00",
            "is_active": true,
            "location_type": {
                "id": 1,
                "name": "Agency",
                "slug": "agency"
            },
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        },
        "start_datetime": "2026-09-11T10:00:00.000000Z",
        "end_datetime": "2026-09-14T10:00:00.000000Z",
        "total_days": 3,
        "price_per_day": "375.00",
        "delivery_fee": "200.00",
        "deposit_amount": "3000.00",
        "total_price": "4325.00",
        "customer_notes": null,
        "admin_notes": "Lifecycle QA reservation.",
        "created_by": {
            "id": 1,
            "name": "Limosud Cars Admin",
            "email": "admin@limosudcars.local"
        },
        "confirmed_at": "2026-06-11T23:55:18.000000Z",
        "started_at": "2026-06-11T23:19:33.000000Z",
        "completed_at": "2026-06-11T23:19:34.000000Z",
        "cancelled_at": null,
        "created_at": "2026-06-11T23:05:51.000000Z",
        "updated_at": "2026-06-11T23:55:18.000000Z"
    }
}
```

**Error responses**

401 if unauthenticated.
403 without reservations.confirm.
404 for invalid reservation ID.
422 if reservation is not pending or vehicle is unavailable.
500 for unexpected server errors.

**Render in UI**

Reservation number, customer, vehicle, status, payment_status, dates, pricing totals, notes, lifecycle timestamps.

**Send from UI**

None.

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Usually none unless navigating to detail/edit screens.

**Notes**

Availability is rechecked inside a database transaction.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/reservations/${reservation}/confirm`, {
  method: 'POST',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 59. `POST` `/api/admin/reservations/{reservation}/start`

| Item | Value |
|------|-------|
| When to call | Starts a confirmed reservation and updates the vehicle status to rented. |
| Auth | Admin Bearer token |
| Permission | `reservations.start` |

**Query params**

None.

**Path params**

reservation: numeric reservation ID.

**Request body**

None.

**Captured success response**

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
            "status": {
                "id": 3,
                "name": "Rented",
                "slug": "rented"
            },
            "transmission_type": {
                "id": 1,
                "name": "Manual",
                "slug": "manual"
            },
            "fuel_type": {
                "id": 2,
                "name": "Diesel",
                "slug": "diesel"
            }
        },
        "source": {
            "id": 6,
            "name": "Admin Manual",
            "slug": "admin_manual"
        },
        "status": {
            "id": 3,
            "name": "In Progress",
            "slug": "in_progress"
        },
        "payment_status": {
            "id": 1,
            "name": "Unpaid",
            "slug": "unpaid"
        },
        "pickup_location": {
            "id": 3,
            "name": "Postman Location",
            "slug": "postman-location",
            "address": "Postman Street, Dakhla",
            "delivery_fee": "100.00",
            "is_active": true,
            "location_type": {
                "id": 1,
                "name": "Agency",
                "slug": "agency"
            },
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        },
        "dropoff_location": {
            "id": 3,
            "name": "Postman Location",
            "slug": "postman-location",
            "address": "Postman Street, Dakhla",
            "delivery_fee": "100.00",
            "is_active": true,
            "location_type": {
                "id": 1,
                "name": "Agency",
                "slug": "agency"
            },
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        },
        "start_datetime": "2026-09-11T10:00:00.000000Z",
        "end_datetime": "2026-09-14T10:00:00.000000Z",
        "total_days": 3,
        "price_per_day": "375.00",
        "delivery_fee": "200.00",
        "deposit_amount": "3000.00",
        "total_price": "4325.00",
        "customer_notes": null,
        "admin_notes": "Lifecycle QA reservation.",
        "created_by": {
            "id": 1,
            "name": "Limosud Cars Admin",
            "email": "admin@limosudcars.local"
        },
        "confirmed_at": "2026-06-11T23:55:18.000000Z",
        "started_at": "2026-06-11T23:55:18.000000Z",
        "completed_at": "2026-06-11T23:19:34.000000Z",
        "cancelled_at": null,
        "created_at": "2026-06-11T23:05:51.000000Z",
        "updated_at": "2026-06-11T23:55:18.000000Z"
    }
}
```

**Error responses**

401 if unauthenticated.
403 without reservations.start.
404 for invalid reservation ID.
422 if reservation is not confirmed.
500 for unexpected server errors.

**Render in UI**

Reservation number, customer, vehicle, status, payment_status, dates, pricing totals, notes, lifecycle timestamps.

**Send from UI**

None.

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Usually none unless navigating to detail/edit screens.

**Notes**

Start before confirm is not allowed.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/reservations/${reservation}/start`, {
  method: 'POST',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 60. `POST` `/api/admin/reservations/{reservation}/complete`

| Item | Value |
|------|-------|
| When to call | Completes an in-progress reservation and updates the vehicle status to available. |
| Auth | Admin Bearer token |
| Permission | `reservations.complete` |

**Query params**

None.

**Path params**

reservation: numeric reservation ID.

**Request body**

None.

**Captured success response**

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
            "status": {
                "id": 1,
                "name": "Available",
                "slug": "available"
            },
            "transmission_type": {
                "id": 1,
                "name": "Manual",
                "slug": "manual"
            },
            "fuel_type": {
                "id": 2,
                "name": "Diesel",
                "slug": "diesel"
            }
        },
        "source": {
            "id": 6,
            "name": "Admin Manual",
            "slug": "admin_manual"
        },
        "status": {
            "id": 4,
            "name": "Completed",
            "slug": "completed"
        },
        "payment_status": {
            "id": 1,
            "name": "Unpaid",
            "slug": "unpaid"
        },
        "pickup_location": {
            "id": 3,
            "name": "Postman Location",
            "slug": "postman-location",
            "address": "Postman Street, Dakhla",
            "delivery_fee": "100.00",
            "is_active": true,
            "location_type": {
                "id": 1,
                "name": "Agency",
                "slug": "agency"
            },
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        },
        "dropoff_location": {
            "id": 3,
            "name": "Postman Location",
            "slug": "postman-location",
            "address": "Postman Street, Dakhla",
            "delivery_fee": "100.00",
            "is_active": true,
            "location_type": {
                "id": 1,
                "name": "Agency",
                "slug": "agency"
            },
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        },
        "start_datetime": "2026-09-11T10:00:00.000000Z",
        "end_datetime": "2026-09-14T10:00:00.000000Z",
        "total_days": 3,
        "price_per_day": "375.00",
        "delivery_fee": "200.00",
        "deposit_amount": "3000.00",
        "total_price": "4325.00",
        "customer_notes": null,
        "admin_notes": "Lifecycle QA reservation.",
        "created_by": {
            "id": 1,
            "name": "Limosud Cars Admin",
            "email": "admin@limosudcars.local"
        },
        "confirmed_at": "2026-06-11T23:55:18.000000Z",
        "started_at": "2026-06-11T23:55:18.000000Z",
        "completed_at": "2026-06-11T23:55:19.000000Z",
        "cancelled_at": null,
        "created_at": "2026-06-11T23:05:51.000000Z",
        "updated_at": "2026-06-11T23:55:19.000000Z"
    }
}
```

**Error responses**

401 if unauthenticated.
403 without reservations.complete.
404 for invalid reservation ID.
422 if reservation is not in_progress.
500 for unexpected server errors.

**Render in UI**

Reservation number, customer, vehicle, status, payment_status, dates, pricing totals, notes, lifecycle timestamps.

**Send from UI**

None.

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Usually none unless navigating to detail/edit screens.

**Notes**

Complete before start is not allowed.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/reservations/${reservation}/complete`, {
  method: 'POST',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 61. `POST` `/api/admin/reservations/{reservation}/cancel`

| Item | Value |
|------|-------|
| When to call | Cancels a reservation and frees the vehicle if it was reserved or rented. |
| Auth | Admin Bearer token |
| Permission | `reservations.cancel` |

**Query params**

None.

**Path params**

reservation: numeric reservation ID.

**Request body**

None.

**Captured success response**

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
            "status": {
                "id": 1,
                "name": "Available",
                "slug": "available"
            },
            "transmission_type": {
                "id": 1,
                "name": "Manual",
                "slug": "manual"
            },
            "fuel_type": {
                "id": 2,
                "name": "Diesel",
                "slug": "diesel"
            }
        },
        "source": {
            "id": 6,
            "name": "Admin Manual",
            "slug": "admin_manual"
        },
        "status": {
            "id": 5,
            "name": "Cancelled",
            "slug": "cancelled"
        },
        "payment_status": {
            "id": 1,
            "name": "Unpaid",
            "slug": "unpaid"
        },
        "pickup_location": {
            "id": 3,
            "name": "Postman Location",
            "slug": "postman-location",
            "address": "Postman Street, Dakhla",
            "delivery_fee": "100.00",
            "is_active": true,
            "location_type": {
                "id": 1,
                "name": "Agency",
                "slug": "agency"
            },
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        },
        "dropoff_location": {
            "id": 3,
            "name": "Postman Location",
            "slug": "postman-location",
            "address": "Postman Street, Dakhla",
            "delivery_fee": "100.00",
            "is_active": true,
            "location_type": {
                "id": 1,
                "name": "Agency",
                "slug": "agency"
            },
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        },
        "start_datetime": "2026-10-11T10:00:00.000000Z",
        "end_datetime": "2026-10-14T10:00:00.000000Z",
        "total_days": 3,
        "price_per_day": "375.00",
        "delivery_fee": "200.00",
        "deposit_amount": "3000.00",
        "total_price": "4325.00",
        "customer_notes": null,
        "admin_notes": null,
        "created_by": {
            "id": 1,
            "name": "Limosud Cars Admin",
            "email": "admin@limosudcars.local"
        },
        "confirmed_at": "2026-06-11T22:54:32.000000Z",
        "started_at": null,
        "completed_at": null,
        "cancelled_at": "2026-06-11T23:55:20.000000Z",
        "created_at": "2026-06-11T23:05:51.000000Z",
        "updated_at": "2026-06-11T23:55:20.000000Z"
    }
}
```

**Error responses**

401 if unauthenticated.
403 without reservations.cancel.
404 for invalid reservation ID.
422 if reservation is completed, cancelled, or rejected.
500 for unexpected server errors.

**Render in UI**

Reservation number, customer, vehicle, status, payment_status, dates, pricing totals, notes, lifecycle timestamps.

**Send from UI**

None.

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Usually none unless navigating to detail/edit screens.

**Notes**

Use a separate reservation to test cancel, not the completed happy-path reservation.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/reservations/${reservation}/cancel`, {
  method: 'POST',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 62. `POST` `/api/admin/reservations/{reservation}/reject`

| Item | Value |
|------|-------|
| When to call | Rejects a pending reservation request. |
| Auth | Admin Bearer token |
| Permission | `reservations.reject` |

**Query params**

None.

**Path params**

reservation: numeric reservation ID.

**Request body**

None.

**Captured success response**

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
            "status": {
                "id": 1,
                "name": "Available",
                "slug": "available"
            },
            "transmission_type": {
                "id": 1,
                "name": "Manual",
                "slug": "manual"
            },
            "fuel_type": {
                "id": 2,
                "name": "Diesel",
                "slug": "diesel"
            }
        },
        "source": {
            "id": 1,
            "name": "Website",
            "slug": "website"
        },
        "status": {
            "id": 6,
            "name": "Rejected",
            "slug": "rejected"
        },
        "payment_status": {
            "id": 1,
            "name": "Unpaid",
            "slug": "unpaid"
        },
        "pickup_location": {
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
        "dropoff_location": {
            "id": 2,
            "name": "Dakhla Airport",
            "slug": "dakhla-airport",
            "address": "Dakhla Airport, Morocco",
            "delivery_fee": "150.00",
            "is_active": true,
            "location_type": {
                "id": 2,
                "name": "Airport",
                "slug": "airport"
            },
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        },
        "start_datetime": "2026-11-11T10:00:00.000000Z",
        "end_datetime": "2026-11-13T10:00:00.000000Z",
        "total_days": 2,
        "price_per_day": "375.00",
        "delivery_fee": "150.00",
        "deposit_amount": "3000.00",
        "total_price": "3450.00",
        "customer_notes": "Reject flow QA.",
        "admin_notes": null,
        "created_by": null,
        "confirmed_at": null,
        "started_at": null,
        "completed_at": null,
        "cancelled_at": null,
        "created_at": "2026-06-11T23:05:51.000000Z",
        "updated_at": "2026-06-11T23:55:20.000000Z"
    }
}
```

**Error responses**

401 if unauthenticated.
403 without reservations.reject.
404 for invalid reservation ID.
422 if reservation is not pending.
500 for unexpected server errors.

**Render in UI**

Reservation number, customer, vehicle, status, payment_status, dates, pricing totals, notes, lifecycle timestamps.

**Send from UI**

None.

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Usually none unless navigating to detail/edit screens.

**Notes**

Use a separate pending reservation to test reject.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/reservations/${reservation}/reject`, {
  method: 'POST',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```


### Module: Payments

#### 63. `GET` `/api/admin/reservations/{reservation}/payment-summary`

| Item | Value |
|------|-------|
| When to call | Returns calculated payment totals for a reservation. |
| Auth | Admin Bearer token |
| Permission | `payments.view` |

**Query params**

None.

**Path params**

reservation: numeric reservation ID.

**Request body**

None.

**Captured success response**

```json
{
    "reservation_id": 6,
    "reservation_number": "RSV-QA-PAYMENT-001",
    "total_price": 4700,
    "paid_amount": 1000,
    "remaining_amount": 3700,
    "payment_status": {
        "id": 2,
        "name": "Partial Paid",
        "slug": "partial_paid"
    }
}
```

**Error responses**

401 if unauthenticated.
403 without payments.view.
404 for invalid reservation ID.
500 for unexpected server errors.

**Render in UI**

Reservation number, customer, vehicle, status, payment_status, dates, pricing totals, notes, lifecycle timestamps.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Path parameter `reservation` from route or list selection.

**Notes**

Only payments with payment_status slug paid count toward paid_amount.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/reservations/${reservation}/payment-summary`, {
  method: 'GET',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 64. `GET` `/api/admin/payments`

| Item | Value |
|------|-------|
| When to call | Lists payments. |
| Auth | Admin Bearer token |
| Permission | `payments.view` |

**Query params**

page: optional page number.

**Path params**

None.

**Request body**

None.

**Captured success response**

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
            "payment_date": "2026-06-10T00:00:00.000000Z",
            "paid_by_customer_name": "Postman Customer",
            "reference": "PM-PAY-FULL-1781219924",
            "notes": "Refunded from Postman",
            "created_by": {
                "id": 1,
                "name": "Limosud Cars Admin",
                "email": "admin@limosudcars.local"
            },
            "created_at": "2026-06-11T23:19:38.000000Z",
            "updated_at": "2026-06-11T23:19:40.000000Z"
        },
        {
            "id": 2,
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
            "paid_by_customer_name": "Postman Customer",
            "reference": "PM-PAY-FULL-1781219738",
            "notes": "Refunded from Postman",
            "created_by": {
                "id": 1,
                "name": "Limosud Cars Admin",
                "email": "admin@limosudcars.local"
            },
            "created_at": "2026-06-11T23:16:40.000000Z",
            "updated_at": "2026-06-11T23:16:42.000000Z"
        },
        {
            "id": 1,
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
            "amount": "1000.00",
            "payment_date": "2026-06-10T00:00:00.000000Z",
            "paid_by_customer_name": "Postman Customer",
            "reference": "PM-PAY-SEEDED-001",
            "notes": "Seeded QA payment.",
            "created_by": {
                "id": 1,
                "name": "Limosud Cars Admin",
                "email": "admin@limosudcars.local"
            },
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        }
    ],
    "links": {
        "first": "http://127.0.0.1:8000/api/admin/payments?page=1",
        "last": "http://127.0.0.1:8000/api/admin/payments?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "page": null,
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/admin/payments?page=1",
                "label": "1",
                "page": 1,
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "page": null,
                "active": false
            }
        ],
        "path": "http://127.0.0.1:8000/api/admin/payments",
        "per_page": 15,
        "to": 3,
        "total": 3
    }
}
```

**Error responses**

401 if unauthenticated.
403 without payments.view.
500 for unexpected server errors.

**Render in UI**

Amount, method/type/status lookups, reservation reference, payment date, customer payer name.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Usually none unless navigating to detail/edit screens.

**Notes**

Payments are not soft-deleted. Cancellation is done by status transition.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/payments`, {
  method: 'GET',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 65. `POST` `/api/admin/payments`

| Item | Value |
|------|-------|
| When to call | Creates a payment and recalculates reservation payment status. |
| Auth | Admin Bearer token |
| Permission | `payments.manage` |

**Query params**

None.

**Path params**

None.

**Request body**

```json
{"reservation_id":1,"payment_method_slug":"cash","payment_type_slug":"rental_payment","payment_status_slug":"paid","amount":300,"payment_date":"2026-06-10","paid_by_customer_name":"Postman Customer","reference":"PM-PAY-001","notes":"Postman payment."}
```

**Captured success response**

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
        "paid_by_customer_name": "Postman Customer",
        "reference": "PM-PAY-1781222073",
        "notes": "Postman payment.",
        "created_by": {
            "id": 1,
            "name": "Limosud Cars Admin",
            "email": "admin@limosudcars.local"
        },
        "created_at": "2026-06-11T23:55:22.000000Z",
        "updated_at": "2026-06-11T23:55:22.000000Z"
    }
}
```

**Error responses**

401 if unauthenticated.
403 without payments.manage.
422 for invalid reservation, invalid lookup slug, invalid amount, or invalid payment date.
500 for unexpected server errors.

**Render in UI**

Amount, method/type/status lookups, reservation reference, payment date, customer payer name.

**Send from UI**

reservation_id, payment_method_slug, payment_type_slug, payment_status_slug, amount, payment_date, paid_by_customer_name, reference, notes

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Store `data.id` returned by create endpoints for follow-up screens.

**Notes**

Paid payments count toward reservation paid_amount. Failed/refunded/cancelled do not.

**Example call**

```javascript
const payload = {"reservation_id":1,"payment_method_slug":"cash","payment_type_slug":"rental_payment","payment_status_slug":"paid","amount":300,"payment_date":"2026-06-10","paid_by_customer_name":"Postman Customer","reference":"PM-PAY-001","notes":"Postman payment."};

const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/payments`, {
  method: 'POST',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {}),
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(payload)
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 66. `GET` `/api/admin/payments/{payment}`

| Item | Value |
|------|-------|
| When to call | Shows one payment. |
| Auth | Admin Bearer token |
| Permission | `payments.view` |

**Query params**

None.

**Path params**

payment: numeric payment ID.

**Request body**

None.

**Captured success response**

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
        "paid_by_customer_name": "Postman Customer",
        "reference": "PM-PAY-1781222073",
        "notes": "Postman payment.",
        "created_by": {
            "id": 1,
            "name": "Limosud Cars Admin",
            "email": "admin@limosudcars.local"
        },
        "created_at": "2026-06-11T23:55:22.000000Z",
        "updated_at": "2026-06-11T23:55:22.000000Z"
    }
}
```

**Error responses**

401 if unauthenticated.
403 without payments.view.
404 for invalid payment ID.
500 for unexpected server errors.

**Render in UI**

Amount, method/type/status lookups, reservation reference, payment date, customer payer name.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Path parameter `payment` from route or list selection.

**Notes**

Payments do not use soft deletes.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/payments/${payment}`, {
  method: 'GET',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 67. `PUT` `/api/admin/payments/{payment}`

| Item | Value |
|------|-------|
| When to call | Updates a payment and recalculates reservation payment status. |
| Auth | Admin Bearer token |
| Permission | `payments.manage` |

**Query params**

None.

**Path params**

payment: numeric payment ID.

**Request body**

```json
{"reservation_id":1,"payment_method_slug":"cash","payment_type_slug":"rental_payment","payment_status_slug":"paid","amount":1000,"payment_date":"2026-06-10","reference":"PM-PAY-FULL"}
```

**Captured success response**

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
        "amount": "1000.00",
        "payment_date": "2026-06-10T00:00:00.000000Z",
        "paid_by_customer_name": "Postman Customer",
        "reference": "PM-PAY-FULL-1781222073",
        "notes": "Postman payment.",
        "created_by": {
            "id": 1,
            "name": "Limosud Cars Admin",
            "email": "admin@limosudcars.local"
        },
        "created_at": "2026-06-11T23:55:22.000000Z",
        "updated_at": "2026-06-11T23:55:24.000000Z"
    }
}
```

**Error responses**

401 if unauthenticated.
403 without payments.manage.
404 for invalid payment ID.
422 for invalid lookup slug or invalid amount.
500 for unexpected server errors.

**Render in UI**

Amount, method/type/status lookups, reservation reference, payment date, customer payer name.

**Send from UI**

reservation_id, payment_method_slug, payment_type_slug, payment_status_slug, amount, payment_date, reference

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Path parameter `payment` from route or list selection.

**Notes**

Updates run through PaymentService and database transactions.

**Example call**

```javascript
const payload = {"reservation_id":1,"payment_method_slug":"cash","payment_type_slug":"rental_payment","payment_status_slug":"paid","amount":1000,"payment_date":"2026-06-10","reference":"PM-PAY-FULL"};

const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/payments/${payment}`, {
  method: 'PUT',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {}),
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(payload)
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 68. `PATCH` `/api/admin/payments/{payment}`

| Item | Value |
|------|-------|
| When to call | Partially updates a payment. |
| Auth | Admin Bearer token |
| Permission | `payments.manage` |

**Query params**

None.

**Path params**

payment: numeric payment ID.

**Request body**

```json
{"payment_status_slug":"refunded","notes":"Refunded from Postman"}
```

**Captured success response**

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
        "paid_by_customer_name": "Postman Customer",
        "reference": "PM-PAY-FULL-1781222073",
        "notes": "Refunded from Postman",
        "created_by": {
            "id": 1,
            "name": "Limosud Cars Admin",
            "email": "admin@limosudcars.local"
        },
        "created_at": "2026-06-11T23:55:22.000000Z",
        "updated_at": "2026-06-11T23:55:24.000000Z"
    }
}
```

**Error responses**

401 if unauthenticated.
403 without payments.manage.
404 for invalid payment ID.
422 for invalid payment_status_slug or invalid amount.
500 for unexpected server errors.

**Render in UI**

Amount, method/type/status lookups, reservation reference, payment date, customer payer name.

**Send from UI**

payment_status_slug, notes

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Path parameter `payment` from route or list selection.

**Notes**

Refunded payments do not count toward paid_amount.

**Example call**

```javascript
const payload = {"payment_status_slug":"refunded","notes":"Refunded from Postman"};

const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/payments/${payment}`, {
  method: 'PATCH',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {}),
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(payload)
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 69. `POST` `/api/admin/payments/{payment}/cancel`

| Item | Value |
|------|-------|
| When to call | Cancels a payment without deleting the financial record. |
| Auth | Admin Bearer token |
| Permission | `payments.manage` |

**Query params**

None.

**Path params**

payment: numeric payment ID.

**Request body**

None.

**Captured success response**

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
        "paid_by_customer_name": "Postman Customer",
        "reference": "PM-PAY-FULL-1781222073",
        "notes": "Refunded from Postman",
        "created_by": {
            "id": 1,
            "name": "Limosud Cars Admin",
            "email": "admin@limosudcars.local"
        },
        "created_at": "2026-06-11T23:55:22.000000Z",
        "updated_at": "2026-06-11T23:55:25.000000Z"
    }
}
```

**Error responses**

401 if unauthenticated.
403 without payments.manage.
404 for invalid payment ID.
500 for unexpected server errors.

**Render in UI**

Amount, method/type/status lookups, reservation reference, payment date, customer payer name.

**Send from UI**

None.

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Usually none unless navigating to detail/edit screens.

**Notes**

Cancelled payments do not count toward reservation paid_amount.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/payments/${payment}/cancel`, {
  method: 'POST',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```


### Module: Contracts

#### 70. `POST` `/api/admin/reservations/{reservation}/contract/generate`

| Item | Value |
|------|-------|
| When to call | Generates or regenerates a reservation contract PDF. |
| Auth | Admin Bearer token |
| Permission | `contracts.generate` |

**Query params**

None.

**Path params**

reservation: numeric reservation ID.

**Request body**

None.

**Captured success response**

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

**Error responses**

401 if unauthenticated.
403 without contracts.generate.
404 for invalid reservation ID.
422 if reservation is pending, rejected, or cancelled.
500 for PDF generation/storage errors.

**Render in UI**

Reservation number, customer, vehicle, status, payment_status, dates, pricing totals, notes, lifecycle timestamps.

**Send from UI**

None.

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Usually none unless navigating to detail/edit screens.

**Notes**

Allowed reservation statuses: confirmed, in_progress, completed. Regeneration keeps the same contract_number.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/reservations/${reservation}/contract/generate`, {
  method: 'POST',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 71. `GET` `/api/admin/reservations/{reservation}/contract`

| Item | Value |
|------|-------|
| When to call | Returns contract metadata for a reservation. |
| Auth | Admin Bearer token |
| Permission | `contracts.view` |

**Query params**

None.

**Path params**

reservation: numeric reservation ID.

**Request body**

None.

**Captured success response**

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

**Error responses**

401 if unauthenticated.
403 without contracts.view.
404 if reservation or contract does not exist.
500 for unexpected server errors.

**Render in UI**

Reservation number, customer, vehicle, status, payment_status, dates, pricing totals, notes, lifecycle timestamps.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Path parameter `reservation` from route or list selection.

**Notes**

Private pdf_path and signed_pdf_path are not exposed.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/reservations/${reservation}/contract`, {
  method: 'GET',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 72. `GET` `/api/admin/contracts/{contract}/download`

| Item | Value |
|------|-------|
| When to call | Downloads the generated contract PDF from private storage. |
| Auth | Admin Bearer token |
| Permission | `contracts.view` |

**Query params**

None.

**Path params**

contract: numeric contract ID.

**Request body**

None.

**Captured success response**

```json
Binary PDF file. Use `response.blob()` and create an object URL for preview/download.
```

**Error responses**

401 if unauthenticated.
403 without contracts.view.
404 if contract or PDF file is missing.
500 for storage errors.

**Render in UI**

`contract_number`, `status`, `has_pdf`, `has_signed_pdf`, `generated_at`, `signed_at`.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Path parameter `contract` from route or list selection.

**Notes**

Use this endpoint instead of trying to read private storage paths.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/contracts/${contract}/download`, {
  method: 'GET',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 73. `POST` `/api/admin/contracts/{contract}/signed`

| Item | Value |
|------|-------|
| When to call | Uploads a signed PDF or marks the contract as signed. |
| Auth | Admin Bearer token |
| Permission | `contracts.update` |

**Query params**

None.

**Path params**

contract: numeric contract ID.

**Request body**

Use `multipart/form-data`. See workflow notes for field names.

```
multipart/form-data:
signed_pdf=<optional PDF file>
```

**Captured success response**

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

**Error responses**

401 if unauthenticated.
403 without contracts.update.
404 for invalid contract ID.
422 for non-PDF signed_pdf.
500 for storage errors.

**Render in UI**

`contract_number`, `status`, `has_pdf`, `has_signed_pdf`, `generated_at`, `signed_at`.

**Send from UI**

See multipart field list in request body section.

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Usually none unless navigating to detail/edit screens.

**Notes**

signed_pdf is optional; omitting it still marks the contract signed.

**Example call**

```javascript
const formData = new FormData();
// append fields and files

const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/contracts/${contract}/signed`, {
  method: 'POST',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  },
  body: formData
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 74. `POST` `/api/admin/contracts/{contract}/cancel`

| Item | Value |
|------|-------|
| When to call | Marks a contract as cancelled. |
| Auth | Admin Bearer token |
| Permission | `contracts.update` |

**Query params**

None.

**Path params**

contract: numeric contract ID.

**Request body**

None.

**Captured success response**

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

**Error responses**

401 if unauthenticated.
403 without contracts.update.
404 for invalid contract ID.
500 for unexpected server errors.

**Render in UI**

`contract_number`, `status`, `has_pdf`, `has_signed_pdf`, `generated_at`, `signed_at`.

**Send from UI**

None.

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Usually none unless navigating to detail/edit screens.

**Notes**

This changes contract status only.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/contracts/${contract}/cancel`, {
  method: 'POST',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```


### Module: Maintenance

#### 75. `GET` `/api/admin/maintenances`

| Item | Value |
|------|-------|
| When to call | Lists vehicle maintenance records. |
| Auth | Admin Bearer token |
| Permission | `maintenance.view` |

**Query params**

page: optional page number.

**Path params**

None.

**Request body**

None.

**Captured success response**

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
    "links": {
        "first": "http://127.0.0.1:8000/api/admin/maintenances?page=1",
        "last": "http://127.0.0.1:8000/api/admin/maintenances?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "page": null,
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/admin/maintenances?page=1",
                "label": "1",
                "page": 1,
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "page": null,
                "active": false
            }
        ],
        "path": "http://127.0.0.1:8000/api/admin/maintenances",
        "per_page": 15,
        "to": 1,
        "total": 1
    }
}
```

**Error responses**

401 if unauthenticated.
403 without maintenance.view.
500 for unexpected server errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Usually none unless navigating to detail/edit screens.

**Notes**

Uses VehicleMaintenanceResource.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/maintenances`, {
  method: 'GET',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 76. `POST` `/api/admin/maintenances`

| Item | Value |
|------|-------|
| When to call | Creates a maintenance record and can optionally update vehicle status and create an expense. |
| Auth | Admin Bearer token |
| Permission | `maintenance.create` |

**Query params**

None.

**Path params**

None.

**Request body**

```json
{"vehicle_id":1,"maintenance_type_slug":"oil_change","maintenance_date":"2026-06-10","next_maintenance_date":"2026-07-10","mileage":21000,"cost":450,"garage_name":"Postman Garage","notes":"Postman maintenance.","vehicle_status_slug":"maintenance","create_expense":true,"expense_category_slug":"maintenance"}
```

**Captured success response**

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

**Error responses**

401 if unauthenticated.
403 without maintenance.create.
422 for invalid maintenance_type_slug, invalid vehicle_id, invalid vehicle_status_slug, or missing expense_category_slug when create_expense is true.
500 for unexpected server errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

vehicle_id, maintenance_type_slug, maintenance_date, next_maintenance_date, mileage, cost, garage_name, notes, vehicle_status_slug, create_expense, expense_category_slug

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Store `data.id` returned by create endpoints for follow-up screens.

**Notes**

vehicle_status_slug can only be maintenance or repair.

**Example call**

```javascript
const payload = {"vehicle_id":1,"maintenance_type_slug":"oil_change","maintenance_date":"2026-06-10","next_maintenance_date":"2026-07-10","mileage":21000,"cost":450,"garage_name":"Postman Garage","notes":"Postman maintenance.","vehicle_status_slug":"maintenance","create_expense":true,"expense_category_slug":"maintenance"};

const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/maintenances`, {
  method: 'POST',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {}),
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(payload)
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 77. `GET` `/api/admin/maintenances/upcoming`

| Item | Value |
|------|-------|
| When to call | Lists upcoming maintenance records. |
| Auth | Admin Bearer token |
| Permission | `maintenance.view` |

**Query params**

page: optional page number.

**Path params**

None.

**Request body**

None.

**Captured success response**

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
    "links": {
        "first": "http://127.0.0.1:8000/api/admin/maintenances/upcoming?page=1",
        "last": "http://127.0.0.1:8000/api/admin/maintenances/upcoming?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "page": null,
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/admin/maintenances/upcoming?page=1",
                "label": "1",
                "page": 1,
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "page": null,
                "active": false
            }
        ],
        "path": "http://127.0.0.1:8000/api/admin/maintenances/upcoming",
        "per_page": 15,
        "to": 2,
        "total": 2
    }
}
```

**Error responses**

401 if unauthenticated.
403 without maintenance.view.
500 for unexpected server errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Usually none unless navigating to detail/edit screens.

**Notes**

Only records with next_maintenance_date today or later are returned.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/maintenances/upcoming`, {
  method: 'GET',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 78. `GET` `/api/admin/maintenances/{maintenance}`

| Item | Value |
|------|-------|
| When to call | Shows one maintenance record. |
| Auth | Admin Bearer token |
| Permission | `maintenance.view` |

**Query params**

None.

**Path params**

maintenance: numeric maintenance ID.

**Request body**

None.

**Captured success response**

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

**Error responses**

401 if unauthenticated.
403 without maintenance.view.
404 for invalid maintenance ID.
500 for unexpected server errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Path parameter `maintenance` from route or list selection.

**Notes**

Soft-deleted records are not resolved.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/maintenances/${maintenance}`, {
  method: 'GET',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 79. `PUT` `/api/admin/maintenances/{maintenance}`

| Item | Value |
|------|-------|
| When to call | Updates a maintenance record. |
| Auth | Admin Bearer token |
| Permission | `maintenance.update` |

**Query params**

None.

**Path params**

maintenance: numeric maintenance ID.

**Request body**

```json
{"vehicle_id":1,"maintenance_type_slug":"oil_change","maintenance_date":"2026-06-10","next_maintenance_date":"2026-08-10","mileage":21500,"cost":500,"garage_name":"Postman Garage","notes":"Postman maintenance updated.","vehicle_status_slug":"repair"}
```

**Captured success response**

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
        "cost": "500.00",
        "garage_name": "Postman Garage Updated",
        "notes": "Updated maintenance.",
        "created_at": "2026-06-11T23:55:31.000000Z",
        "updated_at": "2026-06-11T23:55:33.000000Z"
    }
}
```

**Error responses**

401 if unauthenticated.
403 without maintenance.update.
404 for invalid maintenance ID.
422 for invalid lookup/date/status values.
500 for unexpected server errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

vehicle_id, maintenance_type_slug, maintenance_date, next_maintenance_date, mileage, cost, garage_name, notes, vehicle_status_slug

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Path parameter `maintenance` from route or list selection.

**Notes**

vehicle_status_slug can update the vehicle only to maintenance or repair.

**Example call**

```javascript
const payload = {"vehicle_id":1,"maintenance_type_slug":"oil_change","maintenance_date":"2026-06-10","next_maintenance_date":"2026-08-10","mileage":21500,"cost":500,"garage_name":"Postman Garage","notes":"Postman maintenance updated.","vehicle_status_slug":"repair"};

const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/maintenances/${maintenance}`, {
  method: 'PUT',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {}),
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(payload)
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 80. `PATCH` `/api/admin/maintenances/{maintenance}`

| Item | Value |
|------|-------|
| When to call | Partially updates a maintenance record. |
| Auth | Admin Bearer token |
| Permission | `maintenance.update` |

**Query params**

None.

**Path params**

maintenance: numeric maintenance ID.

**Request body**

```json
{"next_maintenance_date":"2026-08-10","cost":500}
```

**Captured success response**

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

**Error responses**

401 if unauthenticated.
403 without maintenance.update.
404 for invalid maintenance ID.
422 for invalid date/cost.
500 for unexpected server errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

next_maintenance_date, cost

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Path parameter `maintenance` from route or list selection.

**Notes**

Use PATCH for cost/date corrections.

**Example call**

```javascript
const payload = {"next_maintenance_date":"2026-08-10","cost":500};

const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/maintenances/${maintenance}`, {
  method: 'PATCH',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {}),
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(payload)
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 81. `DELETE` `/api/admin/maintenances/{maintenance}`

| Item | Value |
|------|-------|
| When to call | Soft deletes a maintenance record. |
| Auth | Admin Bearer token |
| Permission | `maintenance.delete` |

**Query params**

None.

**Path params**

maintenance: numeric maintenance ID.

**Request body**

None.

**Captured success response**

```json
HTTP 204 No Content — empty body.
```

**Error responses**

401 if unauthenticated.
403 without maintenance.delete.
404 for invalid maintenance ID.
500 for unexpected server errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Path parameter `maintenance` from route or list selection.

**Notes**

Related expense records are not automatically deleted.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/maintenances/${maintenance}`, {
  method: 'DELETE',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```


### Module: Expenses

#### 82. `GET` `/api/admin/expenses`

| Item | Value |
|------|-------|
| When to call | Lists expenses. |
| Auth | Admin Bearer token |
| Permission | `expenses.view` |

**Query params**

page: optional page number.

**Path params**

None.

**Request body**

None.

**Captured success response**

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
            "updated_at": "2026-06-11T23:55:31.000000Z"
        },
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
            "updated_at": "2026-06-11T23:19:46.000000Z"
        },
        {
            "id": 2,
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
            "created_at": "2026-06-11T23:16:54.000000Z",
            "updated_at": "2026-06-11T23:16:54.000000Z"
        },
        {
            "id": 1,
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
            "amount": "250.00",
            "expense_date": "2026-06-10T00:00:00.000000Z",
            "description": "Postman expense seeded.",
            "has_invoice": false,
            "created_by": {
                "id": 1,
                "name": "Limosud Cars Admin",
                "email": "admin@limosudcars.local"
            },
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:05:51.000000Z"
        }
    ],
    "links": {
        "first": "http://127.0.0.1:8000/api/admin/expenses?page=1",
        "last": "http://127.0.0.1:8000/api/admin/expenses?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "page": null,
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/admin/expenses?page=1",
                "label": "1",
                "page": 1,
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "page": null,
                "active": false
            }
        ],
        "path": "http://127.0.0.1:8000/api/admin/expenses",
        "per_page": 15,
        "to": 4,
        "total": 4
    }
}
```

**Error responses**

401 if unauthenticated.
403 without expenses.view.
500 for unexpected server errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Usually none unless navigating to detail/edit screens.

**Notes**

invoice_path is not exposed; has_invoice is exposed.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/expenses`, {
  method: 'GET',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 83. `POST` `/api/admin/expenses`

| Item | Value |
|------|-------|
| When to call | Creates an expense, optionally with a private invoice file. |
| Auth | Admin Bearer token |
| Permission | `expenses.create` |

**Query params**

None.

**Path params**

None.

**Request body**

Use `multipart/form-data`. See workflow notes for field names.

```
JSON:
```json
{"vehicle_id":1,"expense_category_slug":"fuel","amount":200,"expense_date":"2026-06-10","description":"Postman fuel expense."}
```
multipart/form-data:
vehicle_id=1
expense_category_slug=fuel
amount=200
expense_date=2026-06-10
description=Postman fuel expense with invoice
invoice=<pdf/jpg/jpeg/png/webp file>
```

**Captured success response**

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
    }
}
```

**Error responses**

401 if unauthenticated.
403 without expenses.create.
422 for invalid category slug, negative amount, invalid date, or invalid invoice mime.
500 for storage errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

See multipart field list in request body section.

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Store `data.id` returned by create endpoints for follow-up screens.

**Notes**

vehicle_id is nullable for general expenses.

**Example call**

```javascript
const formData = new FormData();
// append fields and files

const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/expenses`, {
  method: 'POST',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  },
  body: formData
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 84. `GET` `/api/admin/expenses/monthly-summary`

| Item | Value |
|------|-------|
| When to call | Returns monthly expense totals grouped by category. |
| Auth | Admin Bearer token |
| Permission | `expenses.view` |

**Query params**

year: optional integer from 2000 to 2100.
month: optional integer from 1 to 12.

**Path params**

None.

**Request body**

None.

**Captured success response**

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

**Error responses**

401 if unauthenticated.
403 without expenses.view.
422 for invalid year/month.
500 for unexpected server errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Usually none unless navigating to detail/edit screens.

**Notes**

Uses expense_date year/month filters.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/expenses/monthly-summary`, {
  method: 'GET',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 85. `GET` `/api/admin/expenses/{expense}`

| Item | Value |
|------|-------|
| When to call | Shows one expense. |
| Auth | Admin Bearer token |
| Permission | `expenses.view` |

**Query params**

None.

**Path params**

expense: numeric expense ID.

**Request body**

None.

**Captured success response**

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
    }
}
```

**Error responses**

401 if unauthenticated.
403 without expenses.view.
404 for invalid expense ID.
500 for unexpected server errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Path parameter `expense` from route or list selection.

**Notes**

Soft-deleted expenses are not resolved.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/expenses/${expense}`, {
  method: 'GET',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 86. `PUT` `/api/admin/expenses/{expense}`

| Item | Value |
|------|-------|
| When to call | Updates an expense and can replace invoice file. |
| Auth | Admin Bearer token |
| Permission | `expenses.update` |

**Query params**

None.

**Path params**

expense: numeric expense ID.

**Request body**

Use `multipart/form-data`. See workflow notes for field names.

```
```json
{"vehicle_id":1,"expense_category_slug":"fuel","amount":250,"expense_date":"2026-06-10","description":"Postman expense updated."}
```
```

**Captured success response**

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
        "amount": "250.00",
        "expense_date": "2026-06-10T00:00:00.000000Z",
        "description": "Postman expense updated.",
        "has_invoice": false,
        "created_by": {
            "id": 1,
            "name": "Limosud Cars Admin",
            "email": "admin@limosudcars.local"
        },
        "created_at": "2026-06-11T23:55:35.000000Z",
        "updated_at": "2026-06-11T23:55:37.000000Z"
    }
}
```

**Error responses**

401 if unauthenticated.
403 without expenses.update.
404 for invalid expense ID.
422 for invalid category slug, invalid amount, invalid date, or invalid invoice mime.
500 for storage errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

See multipart field list in request body section.

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Path parameter `expense` from route or list selection.

**Notes**

When a replacement invoice is uploaded, the old local invoice is deleted.

**Example call**

```javascript
const formData = new FormData();
// append fields and files

const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/expenses/${expense}`, {
  method: 'PUT',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  },
  body: formData
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 87. `PATCH` `/api/admin/expenses/{expense}`

| Item | Value |
|------|-------|
| When to call | Partially updates an expense. |
| Auth | Admin Bearer token |
| Permission | `expenses.update` |

**Query params**

None.

**Path params**

expense: numeric expense ID.

**Request body**

Use `multipart/form-data`. See workflow notes for field names.

```
```json
{"expense_category_slug":"maintenance","amount":275}
```
```

**Captured success response**

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
    }
}
```

**Error responses**

401 if unauthenticated.
403 without expenses.update.
404 for invalid expense ID.
422 for invalid category slug or invalid amount.
500 for unexpected server errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

See multipart field list in request body section.

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Path parameter `expense` from route or list selection.

**Notes**

Use PATCH for category, amount, date, or description corrections.

**Example call**

```javascript
const formData = new FormData();
// append fields and files

const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/expenses/${expense}`, {
  method: 'PATCH',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  },
  body: formData
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 88. `DELETE` `/api/admin/expenses/{expense}`

| Item | Value |
|------|-------|
| When to call | Soft deletes an expense. |
| Auth | Admin Bearer token |
| Permission | `expenses.delete` |

**Query params**

None.

**Path params**

expense: numeric expense ID.

**Request body**

None.

**Captured success response**

```json
HTTP 204 No Content — empty body.
```

**Error responses**

401 if unauthenticated.
403 without expenses.delete.
404 for invalid expense ID.
500 for unexpected server errors.

**Render in UI**

Use keys returned in `data` or top-level response object.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Path parameter `expense` from route or list selection.

**Notes**

Financial records are soft deleted and permission controlled.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/expenses/${expense}`, {
  method: 'DELETE',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```


### Module: Alerts

#### 89. `GET` `/api/admin/alerts`

| Item | Value |
|------|-------|
| When to call | Lists alerts. |
| Auth | Admin Bearer token |
| Permission | `alerts.view` |

**Query params**

page: optional page number.

**Path params**

None.

**Request body**

None.

**Captured success response**

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
        },
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
        },
        {
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
                "id": 1,
                "name": "Pending",
                "slug": "pending"
            },
            "title": "QA Alert Done Target",
            "message": "Alert for done transition.",
            "due_date": "2026-08-11T00:00:00.000000Z",
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:54:32.000000Z"
        },
        {
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
                "id": 1,
                "name": "Pending",
                "slug": "pending"
            },
            "title": "QA Alert Ignore Target",
            "message": "Alert for ignore transition.",
            "due_date": "2026-09-11T00:00:00.000000Z",
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:54:32.000000Z"
        }
    ],
    "links": {
        "first": "http://127.0.0.1:8000/api/admin/alerts?page=1",
        "last": "http://127.0.0.1:8000/api/admin/alerts?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "page": null,
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/admin/alerts?page=1",
                "label": "1",
                "page": 1,
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "page": null,
                "active": false
            }
        ],
        "path": "http://127.0.0.1:8000/api/admin/alerts",
        "per_page": 15,
        "to": 4,
        "total": 4
    }
}
```

**Error responses**

401 if unauthenticated.
403 without alerts.view.
500 for unexpected server errors.

**Render in UI**

Title, message, due_date, alert_type, alert_status, optional vehicle.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Usually none unless navigating to detail/edit screens.

**Notes**

Alerts may be vehicle-specific or general.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/alerts`, {
  method: 'GET',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 90. `GET` `/api/admin/alerts/pending`

| Item | Value |
|------|-------|
| When to call | Lists pending alerts ordered by due_date. |
| Auth | Admin Bearer token |
| Permission | `alerts.view` |

**Query params**

page: optional page number.

**Path params**

None.

**Request body**

None.

**Captured success response**

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
        },
        {
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
                "id": 1,
                "name": "Pending",
                "slug": "pending"
            },
            "title": "QA Alert Done Target",
            "message": "Alert for done transition.",
            "due_date": "2026-08-11T00:00:00.000000Z",
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:54:32.000000Z"
        },
        {
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
                "id": 1,
                "name": "Pending",
                "slug": "pending"
            },
            "title": "QA Alert Ignore Target",
            "message": "Alert for ignore transition.",
            "due_date": "2026-09-11T00:00:00.000000Z",
            "created_at": "2026-06-11T23:05:51.000000Z",
            "updated_at": "2026-06-11T23:54:32.000000Z"
        },
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
        }
    ],
    "links": {
        "first": "http://127.0.0.1:8000/api/admin/alerts/pending?page=1",
        "last": "http://127.0.0.1:8000/api/admin/alerts/pending?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "page": null,
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/admin/alerts/pending?page=1",
                "label": "1",
                "page": 1,
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "page": null,
                "active": false
            }
        ],
        "path": "http://127.0.0.1:8000/api/admin/alerts/pending",
        "per_page": 15,
        "to": 4,
        "total": 4
    }
}
```

**Error responses**

401 if unauthenticated.
403 without alerts.view.
500 for unexpected server errors.

**Render in UI**

Title, message, due_date, alert_type, alert_status, optional vehicle.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Usually none unless navigating to detail/edit screens.

**Notes**

Only alert_status.slug=pending is returned.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/alerts/pending`, {
  method: 'GET',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 91. `POST` `/api/admin/alerts`

| Item | Value |
|------|-------|
| When to call | Creates a manual alert. |
| Auth | Admin Bearer token |
| Permission | `alerts.create` |

**Query params**

None.

**Path params**

None.

**Request body**

```json
{"vehicle_id":1,"alert_type_slug":"maintenance_due","alert_status_slug":"pending","title":"Postman Maintenance Alert","message":"Postman alert message.","due_date":"2026-07-01"}
```

**Captured success response**

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
    }
}
```

**Error responses**

401 if unauthenticated.
403 without alerts.create.
422 for invalid alert_type_slug, invalid alert_status_slug, missing title, or invalid due_date.
500 for unexpected server errors.

**Render in UI**

Title, message, due_date, alert_type, alert_status, optional vehicle.

**Send from UI**

vehicle_id, alert_type_slug, alert_status_slug, title, message, due_date

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Store `data.id` returned by create endpoints for follow-up screens.

**Notes**

AlertService prevents duplicate pending alerts for the same vehicle/type/due_date.

**Example call**

```javascript
const payload = {"vehicle_id":1,"alert_type_slug":"maintenance_due","alert_status_slug":"pending","title":"Postman Maintenance Alert","message":"Postman alert message.","due_date":"2026-07-01"};

const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/alerts`, {
  method: 'POST',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {}),
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(payload)
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 92. `POST` `/api/admin/alerts/generate`

| Item | Value |
|------|-------|
| When to call | Generates maintenance and document expiry alerts. |
| Auth | Admin Bearer token |
| Permission | `alerts.create` |

**Query params**

None.

**Path params**

None.

**Request body**

None.

**Captured success response**

```json
{
    "maintenance_alerts_created": 0,
    "document_expiry_alerts_created": 0,
    "total_created": 0
}
```

**Error responses**

401 if unauthenticated.
403 without alerts.create.
500 for unexpected server errors.

**Render in UI**

Title, message, due_date, alert_type, alert_status, optional vehicle.

**Send from UI**

None.

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Usually none unless navigating to detail/edit screens.

**Notes**

Repeated calls may return total_created=0 because duplicate pending alerts are prevented.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/alerts/generate`, {
  method: 'POST',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 93. `GET` `/api/admin/alerts/{alert}`

| Item | Value |
|------|-------|
| When to call | Shows one alert. |
| Auth | Admin Bearer token |
| Permission | `alerts.view` |

**Query params**

None.

**Path params**

alert: numeric alert ID.

**Request body**

None.

**Captured success response**

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
            "id": 1,
            "name": "Pending",
            "slug": "pending"
        },
        "title": "Postman Maintenance Alert",
        "message": "Postman alert message.",
        "due_date": "2026-07-11T00:00:00.000000Z",
        "created_at": "2026-06-11T23:05:51.000000Z",
        "updated_at": "2026-06-11T23:54:32.000000Z"
    }
}
```

**Error responses**

401 if unauthenticated.
403 without alerts.view.
404 for invalid alert ID.
500 for unexpected server errors.

**Render in UI**

Title, message, due_date, alert_type, alert_status, optional vehicle.

**Send from UI**

None.

**Do not send**

N/A for read/delete/action endpoints without body.

**Store in frontend state**

Path parameter `alert` from route or list selection.

**Notes**

vehicle can be null for general alerts.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/alerts/${alert}`, {
  method: 'GET',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 94. `PATCH` `/api/admin/alerts/{alert}/seen`

| Item | Value |
|------|-------|
| When to call | Marks a pending alert as seen. |
| Auth | Admin Bearer token |
| Permission | `alerts.update` |

**Query params**

None.

**Path params**

alert: numeric alert ID.

**Request body**

None.

**Captured success response**

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
}
```

**Error responses**

401 if unauthenticated.
403 without alerts.update.
404 for invalid alert ID.
422 for invalid transition.
500 for unexpected server errors.

**Render in UI**

Title, message, due_date, alert_type, alert_status, optional vehicle.

**Send from UI**

None.

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Path parameter `alert` from route or list selection.

**Notes**

Allowed transition: pending -> seen.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/alerts/${alert}/seen`, {
  method: 'PATCH',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 95. `PATCH` `/api/admin/alerts/{alert}/done`

| Item | Value |
|------|-------|
| When to call | Marks a pending or seen alert as done. |
| Auth | Admin Bearer token |
| Permission | `alerts.update` |

**Query params**

None.

**Path params**

alert: numeric alert ID.

**Request body**

None.

**Captured success response**

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
}
```

**Error responses**

401 if unauthenticated.
403 without alerts.update.
404 for invalid alert ID.
422 for invalid transition.
500 for unexpected server errors.

**Render in UI**

Title, message, due_date, alert_type, alert_status, optional vehicle.

**Send from UI**

None.

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Path parameter `alert` from route or list selection.

**Notes**

Allowed transitions: pending -> done, seen -> done.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/alerts/${alert}/done`, {
  method: 'PATCH',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

#### 96. `PATCH` `/api/admin/alerts/{alert}/ignore`

| Item | Value |
|------|-------|
| When to call | Ignores a pending or seen alert. |
| Auth | Admin Bearer token |
| Permission | `alerts.update` |

**Query params**

None.

**Path params**

alert: numeric alert ID.

**Request body**

None.

**Captured success response**

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
}
```

**Error responses**

401 if unauthenticated.
403 without alerts.update.
404 for invalid alert ID.
422 for invalid transition.
500 for unexpected server errors.

**Render in UI**

Title, message, due_date, alert_type, alert_status, optional vehicle.

**Send from UI**

None.

**Do not send**

`id`, `created_at`, `updated_at`, nested resource objects, `file_path`, `pdf_path`, `signed_pdf_path`, `invoice_path`, server-calculated totals unless API explicitly accepts them.

**Store in frontend state**

Path parameter `alert` from route or list selection.

**Notes**

Allowed transitions: pending -> ignored, seen -> ignored.

**Example call**

```javascript
const token = localStorage.getItem('admin_token');
const response = await fetch(`http://127.0.0.1:8000/api/admin/alerts/${alert}/ignore`, {
  method: 'PATCH',
  headers: {
    Accept: 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  }
});

if (!response.ok) {
  const error = await response.json().catch(() => ({}));
  throw error;
}

const data = response.status === 204 ? null : await response.json();
```

---

## 6. Module Summary

| Module | Endpoints | Status |
|--------|-----------|--------|
| Health | 1 | Available |
| Auth | 9–11 | Available |
| Public website | 2–8 | Available |
| Dashboard | 13–15 | Available |
| Brands | 16–21 | Available |
| Categories | 22–27 | Available |
| Vehicles | 28–35 | Available |
| Customers | 36–43 | Available |
| Locations | 44–49 | Available |
| Reservations | 50–62 | Available |
| Payments | 63–69 | Available |
| Contracts | 70–74 | Available |
| Maintenance | 75–81 | Available |
| Expenses | 82–88 | Available |
| Alerts | 89–96 | Available |
| Lookups (admin) | 12 | Available |
| Site pages | — | Not exposed in `routes/api.php` yet |
| Audit logs | — | Not exposed in `routes/api.php` yet |

### Site pages & audit logs

Permissions exist in `GET /api/admin/auth/me` (`site_pages.*`, `audit_logs.view`), but there are **no API routes** for these modules yet. Do not build frontend screens against invented endpoints.

