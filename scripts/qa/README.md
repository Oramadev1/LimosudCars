# Limosud Cars — Local QA API Workflow

This folder contains scripts for **manual local API testing only**.  
It is **not** wired into production deployment or CI/CD.

## Safety rules

- `QaDatabaseSeeder` and `QaApiSeeder` are **not** registered in `DatabaseSeeder`.
- Both QA seeders refuse to run when `APP_ENV=production`.
- Generated secrets and tokens must stay local:
  - `storage/qa/postman/LimosudCars_API_QA.postman_environment.json` → **gitignored**
  - `storage/qa/reports/` → **gitignored** (Newman JSON, captured responses, QA report)
  - `storage/qa/qa-manifest.json` → **gitignored**
- Safe to commit:
  - `POSTMAN_TEST_WORKFLOW.txt`
  - `FRONTEND_API_INTEGRATION_GUIDE.md`
  - `FRONTEND_WORKFLOWS.md`
  - `FRONTEND_ONBOARDING.md`
  - `storage/qa/postman/LimosudCars_API_QA.postman_collection.json`
  - `storage/qa/postman/LimosudCars_API_QA.postman_environment.example.json`
- `POSTMAN_TEST_WORKFLOW.txt` may be updated with captured responses, but login `access_token` values are **redacted** before write.

## Prerequisites

1. Local MySQL running (see `backend/.env`)
2. PHP dependencies installed (`composer install`)
3. Node dependencies installed (`npm install`)
4. Laravel app reachable at `http://127.0.0.1:8000`

Use QA credentials from `backend/.env`:

```env
ADMIN_EMAIL=<your local admin email>
ADMIN_PASSWORD=<your local admin password>
```

Read values from `backend/.env`. Do not copy production credentials into QA files.

## One-command local run (PowerShell)

From `backend/`:

```powershell
php artisan serve
```

In another terminal:

```powershell
npm run qa:workflow
```

This will:

1. Seed lookups + QA sample data (`QaDatabaseSeeder`)
2. Export a local Postman environment from `.env` + QA manifest
3. Build the 96-request Postman collection
4. Run Newman
5. Update `POSTMAN_TEST_WORKFLOW.txt` with redacted captured responses
6. Write `storage/qa/reports/QA_API_TEST_REPORT.txt`

## Step-by-step manual run

```powershell
cd backend

# 1) Bootstrap QA data (local/testing only)
php artisan db:seed --class=QaDatabaseSeeder

# 2) Start API if needed
php artisan serve

# 3) Build artifacts
php scripts/qa/export_qa_env.php
php scripts/qa/build_postman_collection.php

# 4) Run Newman
npm run qa:newman

# 5) Refresh docs + report
php scripts/qa/update_workflow_from_newman.php
php scripts/qa/generate_qa_report.php
```

## Postman files

| File | Purpose | Commit? |
|------|---------|---------|
| `LimosudCars_API_QA.postman_collection.json` | 96 endpoint requests | Yes |
| `LimosudCars_API_QA.postman_environment.example.json` | Template variables | Yes |
| `LimosudCars_API_QA.postman_environment.json` | Generated local env | **No** (gitignored) |

Import the collection into Postman, duplicate the example environment, then run `php scripts/qa/export_qa_env.php` to generate the real local environment file.

## Re-running safely

Each QA run mutates disposable QA records (brands, customers, reservations, etc.).  
For a clean rerun:

```powershell
php artisan db:seed --class=QaDatabaseSeeder
npm run qa:workflow
```

## What not to do

- Do not add `QaDatabaseSeeder` to `DatabaseSeeder`.
- Do not run QA seeders on production databases.
- Do not commit `storage/qa/reports/` or generated Postman environment files.
- Do not commit raw Newman output before token redaction.

## Frontend integration guide

Generate the frontend developer guide (uses captured Newman responses):

```powershell
php scripts/qa/generate_frontend_integration_guide.php
```

Output: `FRONTEND_API_INTEGRATION_GUIDE.md`

Generate the page-by-page frontend workflow guide:

```powershell
php scripts/qa/generate_frontend_workflows.php
```

Output: `FRONTEND_WORKFLOWS.md`

## Frontend onboarding

New frontend developers should start with:

`FRONTEND_ONBOARDING.md`

## Security audit before commit

```powershell
php scripts/qa/audit_committable_secrets.php
```

Scans committable docs/collection for accidental tokens or passwords. Writes `storage/qa/reports/COMMITTABLE_SECRETS_AUDIT.txt` (gitignored).

## Troubleshooting

| Issue | Fix |
|-------|-----|
| `401` after login | Logout runs last in the collection; rebuild with `php scripts/qa/build_postman_collection.php` |
| Missing QA IDs | Re-run `php artisan db:seed --class=QaApiSeeder` then `php scripts/qa/export_qa_env.php` |
| QA seeder blocked | Ensure `APP_ENV` is `local` or `testing`, not `production` |
| Newman not found | Run `npm install` in `backend/` |
