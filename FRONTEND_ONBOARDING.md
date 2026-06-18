# Limosud Cars — Frontend Developer Onboarding

Welcome. This guide gets you productive on the **admin dashboard** without reading Laravel code or asking backend questions.

The backend team maintains **generated documentation** from real API runs (Newman + captured responses). Treat those docs as the source of truth for request/response shapes and user journeys.

---

## 1. What to read first

| Priority | Document | Purpose |
|----------|----------|---------|
| **1** | [`FRONTEND_WORKFLOWS.md`](FRONTEND_WORKFLOWS.md) | **Start here.** Page-by-page user journeys: which endpoints to call, in what order, and how the UI should behave. |
| **2** | [`FRONTEND_API_INTEGRATION_GUIDE.md`](FRONTEND_API_INTEGRATION_GUIDE.md) | Field-level API reference for all **96 endpoints** with captured JSON examples. |
| **3** | [`POSTMAN_TEST_WORKFLOW.txt`](POSTMAN_TEST_WORKFLOW.txt) | Human-readable QA catalog of every endpoint (purpose, headers, bodies, captured success responses). |

**Optional / legacy**

| Document | Notes |
|----------|-------|
| [`FRONTEND_API_WORKFLOW.txt`](FRONTEND_API_WORKFLOW.txt) | Older narrative API doc. Prefer the two generated guides above. |
| [`scripts/qa/README.md`](scripts/qa/README.md) | How backend/QA runs Newman locally (useful if you need fresh captures). |

---

## 2. Recommended learning order

### Week 1 — Foundation

1. **Authentication** — `FRONTEND_WORKFLOWS.md` → Authentication section  
2. **App shell** — permissions from `GET /admin/auth/me`, hide nav by `permission.slug`  
3. **Dashboard** — statistics, revenue, expenses widgets  
4. **Vehicles CRUD** — pagination, lookups, brands/categories, PATCH  

### Week 2 — Operations

5. **Customers** + document upload (FormData)  
6. **Locations** CRUD  
7. **Reservations** — create wizard, availability check, lifecycle actions (`confirm`, `start`, `complete`, `cancel`, `reject`)  
8. **Calendar** — `GET /admin/reservations-calendar?start=&end=`  

### Week 3 — Finance & fleet

9. **Payments** — register, update, cancel (no DELETE)  
10. **Contracts** — per-reservation only; generate, download PDF, upload signed  
11. **Maintenance**, **Expenses**, **Alerts**  

Use the **Frontend Development Roadmap** at the end of `FRONTEND_WORKFLOWS.md` for build phasing.

---

## 3. Environment setup

### Backend API (required for integration)

The admin frontend talks to the Laravel API. For local development:

1. Clone the repo and open the `backend/` folder.
2. Copy `.env.example` → `.env` and configure MySQL.
3. Install dependencies:
   ```bash
   cd backend
   composer install
   npm install
   php artisan key:generate
   php artisan migrate
   php artisan db:seed
   ```
4. Start the API:
   ```bash
   php artisan serve
   ```
   Default base URL: **`http://127.0.0.1:8000/api`**

5. Use local admin credentials from `backend/.env`:
   ```env
   ADMIN_EMAIL=...
   ADMIN_PASSWORD=...
   ```
   **Never commit `.env` or copy production credentials.**

### Frontend app

Point your frontend env at the API:

```env
VITE_API_BASE_URL=http://127.0.0.1:8000/api
# or equivalent for your stack
```

Use `Accept: application/json` on all requests. Send `Content-Type: application/json` for JSON bodies.

### Optional — QA data for realistic lists

To populate reservations, payments, contracts, etc. with rich test data:

```bash
cd backend
php artisan db:seed --class=QaDatabaseSeeder
```

This is **local/testing only** — blocked when `APP_ENV=production`.

---

## 4. Authentication

### Flow

```
POST /admin/auth/login     →  { access_token, user }
GET  /admin/auth/me        →  { user, permissions[] }  (validate session)
POST /admin/auth/logout    →  invalidate token
```

### Implementation checklist

| Step | Action |
|------|--------|
| Login | `POST /admin/auth/login` with `{ email, password }` |
| Store token | Save `access_token` (Sanctum personal access token) in memory or secure storage |
| Header | `Authorization: Bearer {access_token}` on all `/admin/*` routes except login |
| Bootstrap | On app load, if token exists → `GET /admin/auth/me` |
| Permissions | Gate menus/buttons using `user.permissions[].slug` |
| 401 | Clear token, redirect to login (preserve `?next=` route) |
| 403 | Toast + hide/disable action |
| Logout | `POST /admin/auth/logout`, then clear local auth state |

Full UI flows: `FRONTEND_WORKFLOWS.md` → **Authentication**.

### Public website routes

`GET/POST /public/*` endpoints do **not** require admin auth (customer booking flow).

---

## 5. Using the Postman collection

### Files

| File | Commit? | Purpose |
|------|---------|---------|
| `storage/qa/postman/LimosudCars_API_QA.postman_collection.json` | **Yes** | 96 requests with tests |
| `storage/qa/postman/LimosudCars_API_QA.postman_environment.example.json` | **Yes** | Template variables |
| `storage/qa/postman/LimosudCars_API_QA.postman_environment.json` | **No** (gitignored) | Generated local env with real password/token |

### Setup in Postman

1. Import the **collection** JSON.
2. Copy the **example** environment → rename locally.
3. From `backend/`, generate your real environment:
   ```bash
   php scripts/qa/export_qa_env.php
   ```
4. Import `LimosudCars_API_QA.postman_environment.json` (local only).
5. Run **Login** (endpoint 9) — Newman/Postman saves `admin_token` automatically.

### When to use Postman vs docs

| Use Postman when… | Use generated docs when… |
|-------------------|--------------------------|
| Probing a new endpoint interactively | Building UI flows and forms |
| Verifying auth/headers | Copying exact field names and response shapes |
| Debugging a failing request | Understanding pagination, permissions, business rules |

---

## 6. Regenerating QA artifacts & docs

From `backend/` (API server running for Newman):

```bash
# Full pipeline: seed → Newman → update POSTMAN_TEST_WORKFLOW.txt → QA report
npm run qa:workflow
```

Individual steps:

```bash
php artisan db:seed --class=QaDatabaseSeeder   # fresh QA data
php scripts/qa/export_qa_env.php               # local Postman env (gitignored)
php scripts/qa/build_postman_collection.php    # rebuild collection
npm run qa:newman                              # run 96 requests, capture JSON
php scripts/qa/update_workflow_from_newman.php # refresh POSTMAN_TEST_WORKFLOW.txt
php scripts/qa/generate_qa_report.php          # storage/qa/reports/ (gitignored)
php scripts/qa/generate_frontend_integration_guide.php  # FRONTEND_API_INTEGRATION_GUIDE.md
php scripts/qa/generate_frontend_workflows.php          # FRONTEND_WORKFLOWS.md
```

**Security:** Login `access_token` values are **redacted** (`<REDACTED_QA_TOKEN>`) before writing committable docs.

### Secrets audit (before you commit docs)

```bash
php scripts/qa/audit_committable_secrets.php
```

Exit code `0` = no suspicious patterns in committable paths.

---

## 7. Understanding captured responses

### Where captures live

| Location | Git? | Contents |
|----------|------|----------|
| `storage/qa/reports/captured-responses.json` | **No** | Raw Newman bodies keyed by endpoint number |
| `storage/qa/reports/newman-results.json` | **No** | Full Newman run output |
| `POSTMAN_TEST_WORKFLOW.txt` | **Yes** | Redacted success examples inline per endpoint |
| `FRONTEND_API_INTEGRATION_GUIDE.md` | **Yes** | Redacted snippets + fetch examples |
| `FRONTEND_WORKFLOWS.md` | **Yes** | Redacted snippets per user journey |

### Endpoint numbering

Endpoint numbers (`9` = login, `50` = reservations list, etc.) match:

- `POSTMAN_TEST_WORKFLOW.txt` section headers  
- `scripts/qa/frontend_endpoint_modules.php`  
- Newman capture keys in `captured-responses.json`  

### What to trust

- **Response shapes** in generated docs come from real Newman runs against QA-seeded data.
- **Money** fields are decimal strings: `"375.00"`.
- **Dates** are ISO 8601 UTC in responses; some request bodies accept `"YYYY-MM-DD HH:mm:ss"`.
- **Pagination** uses Laravel format: `data`, `links`, `meta` with `per_page: 15`.

---

## 8. Common mistakes to avoid

| Mistake | Correct approach |
|---------|------------------|
| Inventing query params like `?search=` or `?sort=` | Only `?page=` exists on lists; search/filter/sort **client-side** |
| `PATCH` reservation status | Use action POSTs: `/confirm`, `/start`, `/complete`, `/cancel`, `/reject` |
| `DELETE` a payment | Use `POST /admin/payments/{id}/cancel` |
| `GET /admin/contracts` list | Contracts are per-reservation: `GET /admin/reservations/{id}/contract` |
| Upload vehicle photos | **No API** — vehicle media is read-only on detail today |
| Setting `Content-Type` on FormData | Let the browser set multipart boundary |
| Treating `failed`/`refunded` payments as paid | Only `paid` counts toward `payment_summary.paid_amount` |
| Alert “read/dismiss” | API uses `PATCH .../seen`, `.../done`, `.../ignore` |
| Assuming pending reserves vehicle | Vehicle reserved only after **confirm** |
| Hardcoding lookup IDs | Use `slug` fields (`status_slug`, `payment_method_slug`, etc.) |
| Committing generated env/reports | See `.gitignore` QA section — use example template only |

---

## 9. Where to find business rules

| Topic | Primary source |
|-------|----------------|
| User journey + UI behavior | `FRONTEND_WORKFLOWS.md` → **Edge cases / business rules** per workflow |
| Field validation + error codes | `FRONTEND_API_INTEGRATION_GUIDE.md` per endpoint |
| Endpoint purpose + Newman notes | `POSTMAN_TEST_WORKFLOW.txt` |
| Permission slugs | `GET /admin/auth/me` → `permissions[].slug`; also listed per workflow |
| Lookup slugs | `GET /admin/lookups` (cache globally for selects) |
| Reservation state machine | `FRONTEND_WORKFLOWS.md` → Reservations → Confirm/Start/Complete/Cancel/Reject |
| Payment balance rules | `FRONTEND_WORKFLOWS.md` → Payments; `GET .../payment-summary` |
| Contract eligibility | Confirmed+ only; `422` if still `pending` |
| Features **not** built yet | `FRONTEND_WORKFLOWS.md` → Roadmap → “Not available yet” (site pages, audit logs API, vehicle upload) |

---

## 10. Quick reference

| Item | Value |
|------|-------|
| API base (local) | `http://127.0.0.1:8000/api` |
| Admin auth | Sanctum bearer `access_token` |
| Total admin + public endpoints | **96** |
| Pagination | `?page=` only, 15 per page |
| Soft deletes | `DELETE` → `204`, hidden from lists |

---

## 11. Getting help

1. Search the three primary docs for the module name or route path.  
2. Reproduce in Postman with the QA collection.  
3. Re-run `npm run qa:workflow` if docs may be stale.  
4. Escalate to backend only for **undocumented** behavior or bugs — not for “which endpoint?” questions covered here.

Happy building.
