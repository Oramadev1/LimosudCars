$ErrorActionPreference = "Stop"
$BackendRoot = Split-Path -Parent (Split-Path -Parent $PSScriptRoot)
Set-Location $BackendRoot

if (Test-Path ".env") {
    $appEnv = (Get-Content ".env" | Where-Object { $_ -match '^\s*APP_ENV=' } | Select-Object -First 1)
    if ($appEnv -match 'APP_ENV\s*=\s*production') {
        throw "QA workflow is disabled when APP_ENV=production."
    }
}

Write-Host "==> Seeding QA database (local/testing only)"
php artisan db:seed --class=QaDatabaseSeeder

Write-Host "==> Exporting local Postman environment (gitignored output)"
php scripts/qa/export_qa_env.php

Write-Host "==> Building Postman collection"
php scripts/qa/build_postman_collection.php

Write-Host "==> Installing Newman (if needed)"
if (-not (Test-Path "node_modules/newman")) {
    npm install --save-dev newman
}

$ReportsDir = Join-Path $BackendRoot "storage/qa/reports"
if (-not (Test-Path $ReportsDir)) {
    New-Item -ItemType Directory -Path $ReportsDir | Out-Null
}

Write-Host "==> Running Newman"
npx newman run storage/qa/postman/LimosudCars_API_QA.postman_collection.json `
    -e storage/qa/postman/LimosudCars_API_QA.postman_environment.json `
    --reporters cli,json `
    --reporter-json-export storage/qa/reports/newman-results.json `
    --delay-request 100

Write-Host "==> Updating POSTMAN_TEST_WORKFLOW.txt with redacted captured responses"
php scripts/qa/update_workflow_from_newman.php

Write-Host "==> Generating final QA report (gitignored output)"
php scripts/qa/generate_qa_report.php

Write-Host "QA workflow completed."
Write-Host "Review: storage/qa/reports/QA_API_TEST_REPORT.txt"
Write-Host "Note: reports, captured responses, and generated Postman environment are gitignored."
