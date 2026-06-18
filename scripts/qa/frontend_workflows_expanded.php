<?php

/** @var callable $workflow */
/** @var callable $snippet */
/** @var array $captured */

// ─── Reservations ───────────────────────────────────────────────────────────

$lines[] = '## Reservations';
$lines[] = '';

$workflow(
    'Reservation list',
    'Browse reservations with status and payment badges.',
    '`reservations.view` permission.',
    '/admin/reservations',
    "Open Reservations page\n↓\nGET /admin/reservations?page=1\n↓\nRender table (number, customer, vehicle, dates, status, payment_status)\n↓\nUser changes page → GET ?page=n",
    '- `GET /admin/reservations`',
    '',
    $snippet($captured, 50, 25),
    '- **Local:** `reservations[]`, pagination meta.\n- **Global:** lookup maps for status/payment_status labels.\n- **Discard:** rows when page changes.',
    '- **Loading:** skeleton table rows.\n- **Empty:** “No reservations yet” + link to create (if `reservations.create`).\n- **Success toast:** none on list load.\n- Color-coded badges from `status.slug` and `payment_status.slug`.',
    '- `403`: hide nav item.\n- Empty `data[]`: empty state, not error.',
    'List is paginated (`per_page` 15) and ordered latest first. No server-side filter — filter client-side on loaded page if needed.',
    'reservations.view',
    '- **Page:** `ReservationsListPage`\n- **Components:** `ReservationsTable`, `StatusBadge`, `PaymentStatusBadge`, `Pagination`, `CreateReservationButton`'
);

$workflow(
    'Reservation details',
    'Inspect one reservation with payment balance and contract panel.',
    'Reservation id from list, calendar, or deep link.',
    '/admin/reservations/{id}',
    "Open detail route\n↓\nGET /admin/reservations/{id}\n↓\nParallel (if permitted):\n  GET /admin/reservations/{id}/payment-summary\n  GET /admin/reservations/{id}/contract\n↓\nRender header + timeline + action bar + payment card + contract card",
    "- `GET /admin/reservations/{id}`\n- `GET /admin/reservations/{id}/payment-summary` (`payments.view`)\n- `GET /admin/reservations/{id}/contract` (`contracts.view`, 404 if none)",
    '',
    $snippet($captured, 54, 30),
    '- **Local:** `reservation`, `paymentSummary`, `contract|null`.\n- **Global:** none.\n- **Route:** `{id}` param.',
    '- **Loading:** full-page skeleton or spinner on each panel.\n- **Empty contract:** “No contract generated yet” + Generate button.\n- **Success toast:** after lifecycle actions (see status workflows).',
    '- `404`: deleted reservation → back to list.\n- Contract `404`: treat as no contract, not fatal.',
    'Detail includes nested `customer`, `vehicle`, `pickup_location`, `dropoff_location`, pricing fields (`total_price`, `deposit_amount`), and timestamp fields (`confirmed_at`, `started_at`, etc.).',
    'reservations.view (+ payments.view / contracts.view for side panels)',
    '- **Page:** `ReservationDetailPage`\n- **Components:** `ReservationHeader`, `ReservationTimeline`, `LifecycleActionBar`, `PaymentSummaryCard`, `ContractPanel`, `NotesSection`'
);

$workflow(
    'Create reservation (admin)',
    'Book a vehicle for a customer; starts as pending/unpaid.',
    '`reservations.create`; customers, vehicles, locations available.',
    '/admin/reservations/new',
    "Open create wizard\n↓\nLoad selects:\n  GET /admin/lookups\n  GET /admin/customers?page=1\n  GET /admin/vehicles?page=1\n  GET /admin/locations?page=1\n↓\nUser picks customer, vehicle, locations, date range\n↓\nPOST /admin/reservations/check-availability\n↓\nIf available: POST /admin/reservations\n↓\n201 → navigate to /admin/reservations/{id}\n↓\nToast: “Reservation created”",
    "- `GET /admin/lookups`\n- `GET /admin/customers` (paginated)\n- `GET /admin/vehicles` (paginated)\n- `GET /admin/locations` (paginated)\n- `POST /admin/reservations/check-availability`\n- `POST /admin/reservations`",
    '**Check availability:**\n```json
{"vehicle_id":1,"start_datetime":"2026-08-01 10:00:00","end_datetime":"2026-08-05 10:00:00","ignore_reservation_id":null}
```\n\n**Create:**\n```json
{"customer_id":1,"vehicle_id":1,"pickup_location_id":1,"dropoff_location_id":1,"start_datetime":"2026-08-01 10:00:00","end_datetime":"2026-08-05 10:00:00","customer_notes":"Postman admin reservation.","admin_notes":"QA test."}
```',
    $snippet($captured, 51, 30),
    '- **Local:** wizard step state, `availabilityResult`.\n- **After success:** store `data.id` for redirect.\n- **Discard:** wizard on navigate away.',
    '- **Loading:** disable Next/Save while checking availability or submitting.\n- **Empty selects:** prompt to create customer/vehicle/location first.\n- **Success toast:** “Reservation created”.\n- Show pricing preview from create response (`total_price`, `total_days`).',
    '- `422` on overlap, invalid IDs, or invalid date order — show field errors.\n- If `available: false`, block submit and explain conflict.',
    '**Pending reservations do not reserve the vehicle** until confirmed. Source is `admin_manual` for admin-created bookings.',
    'reservations.create',
    '- **Page:** `ReservationCreatePage` (multi-step wizard)\n- **Components:** `CustomerSelect`, `VehicleSelect`, `LocationSelect`, `DateRangePicker`, `AvailabilityBanner`, `PricingPreview`'
);

$workflow(
    'Edit reservation',
    'Update dates, vehicle, locations, or notes; pricing recalculated server-side.',
    '`reservations.update`; reservation loaded.',
    '/admin/reservations/{id}/edit',
    "Load GET /admin/reservations/{id}\n↓\nPopulate form\n↓\nOn date/vehicle change: POST /admin/reservations/check-availability (pass ignore_reservation_id)\n↓\nPUT or PATCH /admin/reservations/{id}\n↓\nRefresh detail\n↓\nToast: “Reservation updated”",
    "- `GET /admin/reservations/{id}`\n- `POST /admin/reservations/check-availability`\n- `PUT /admin/reservations/{id}` or `PATCH /admin/reservations/{id}`",
    'PATCH example — send only changed fields. Full PUT sends same shape as create plus resolved ids.',
    $snippet($captured, 56, 25),
    '- **Local:** form ↔ API mapping.\n- **Discard** dirty state after save.',
    '- **Loading:** inline save spinner.\n- **Success toast:** “Reservation updated”.\n- Warn on unsaved changes.',
    '- `422` when overlapping confirmed/in_progress reservation for same vehicle.\n- Cannot edit terminal statuses (completed/cancelled/rejected) — hide form.',
    'Changing `vehicle_id` or dates triggers availability validation. Response includes recalculated `total_price`, `delivery_fee`, `total_days`.',
    'reservations.update',
    '- **Page:** `ReservationEditPage`\n- **Reuse:** create wizard components in edit mode'
);

$workflow(
    'Cancel reservation',
    'Cancel an active reservation and free the vehicle if applicable.',
    '`reservations.cancel`; reservation not completed/cancelled/rejected.',
    'Reservation detail action bar.',
    "User clicks Cancel\n↓\nConfirmation dialog\n↓\nPOST /admin/reservations/{id}/cancel\n↓\nUpdate status.slug → cancelled\n↓\nRefresh detail\n↓\nToast: “Reservation cancelled”",
    '- `POST /admin/reservations/{id}/cancel`',
    'No request body.',
    $snippet($captured, 61, 25),
    '- **Local:** merge updated `status` and `cancelled_at` into `reservation`.\n- **Disable** lifecycle buttons after cancel.',
    '- **Loading:** disable action bar during request.\n- **Confirmation:** explain vehicle may become available.\n- **Success toast:** “Reservation cancelled”.',
    '- `422` if already completed/cancelled/rejected.\n- `403` without `reservations.cancel`.',
    'Cancel is a dedicated POST action — **not** a generic status PATCH.',
    'reservations.cancel',
    '- **Component:** `CancelReservationButton` on `ReservationDetailPage`'
);

$workflow(
    'Confirm reservation',
    'Move pending reservation to confirmed; vehicle becomes reserved.',
    '`reservations.confirm`; `status.slug` is `pending`.',
    'Reservation detail action bar.',
    "User clicks Confirm\n↓\nPOST /admin/reservations/{id}/confirm\n↓\nstatus.slug → confirmed\n↓\nRefresh detail + payment summary\n↓\nToast: “Reservation confirmed”",
    '- `POST /admin/reservations/{id}/confirm`',
    'No request body.',
    $snippet($captured, 58, 25),
    '- **Local:** update `reservation.status`, `confirmed_at`.\n- **Show** contract generate + payment actions after confirm.',
    '- **Loading:** button spinner.\n- **Success toast:** “Reservation confirmed”.',
    '- `422` if not pending or business rules fail.',
    'Only valid from `pending`. Vehicle status may change to `reserved` after confirm.',
    'reservations.confirm',
    '- **Component:** `ConfirmReservationButton` (visible only when `status.slug === pending`)'
);

$workflow(
    'Start reservation (rental in progress)',
    'Mark confirmed rental as in progress when customer picks up vehicle.',
    '`reservations.start`; `status.slug` is `confirmed`.',
    'Reservation detail action bar.',
    "User clicks Start rental\n↓\nPOST /admin/reservations/{id}/start\n↓\nstatus.slug → in_progress\n↓\nToast: “Rental started”",
    '- `POST /admin/reservations/{id}/start`',
    'No request body.',
    $snippet($captured, 59, 25),
    '- **Local:** update `status`, `started_at`.',
    '- **Success toast:** “Rental started”.',
    '- `422` if not confirmed.',
    'Vehicle typically moves to `rented` status when rental starts.',
    'reservations.start',
    '- **Component:** `StartRentalButton` (visible when `status.slug === confirmed`)'
);

$workflow(
    'Complete reservation',
    'Close an in-progress rental.',
    '`reservations.complete`; `status.slug` is `in_progress`.',
    'Reservation detail action bar.',
    "User clicks Complete\n↓\nPOST /admin/reservations/{id}/complete\n↓\nstatus.slug → completed\n↓\nToast: “Rental completed”",
    '- `POST /admin/reservations/{id}/complete`',
    'No request body.',
    $snippet($captured, 60, 25),
    '- **Local:** update `status`, `completed_at`.\n- **Hide** all lifecycle actions except view.',
    '- **Success toast:** “Rental completed”.',
    '- `422` if not in_progress.',
    'Completed reservations are terminal — no further lifecycle actions.',
    'reservations.complete',
    '- **Component:** `CompleteRentalButton` (visible when `status.slug === in_progress`)'
);

$workflow(
    'Reject reservation',
    'Reject a pending booking (e.g. website request).',
    '`reservations.reject`; `status.slug` is `pending`.',
    'Reservation detail action bar.',
    "User clicks Reject\n↓\nConfirm dialog\n↓\nPOST /admin/reservations/{id}/reject\n↓\nstatus.slug → rejected\n↓\nToast: “Reservation rejected”",
    '- `POST /admin/reservations/{id}/reject`',
    'No request body.',
    $snippet($captured, 62, 25),
    '- **Local:** update `status`.',
    '- **Confirmation:** optional reason note (UI only — API has no body).\n- **Success toast:** “Reservation rejected”.',
    '- `422` if not pending.',
    'Rejected reservations are terminal.',
    'reservations.reject',
    '- **Component:** `RejectReservationButton` (pending only)'
);

$workflow(
    'Reservations calendar view',
    'Visualize reservations on a date-range calendar.',
    '`reservations.view`.',
    '/admin/reservations/calendar',
    "Open calendar page\n↓\nUser selects week/month range\n↓\nGET /admin/reservations-calendar?start=YYYY-MM-DD&end=YYYY-MM-DD\n↓\nPlot events from response\n↓\nClick event → navigate to /admin/reservations/{id}",
    '- `GET /admin/reservations-calendar?start={date}&end={date}`',
    '',
    $snippet($captured, 53, 25),
    '- **Local:** `calendarEvents[]`, `visibleRange`.\n- **Global:** none.',
    '- **Loading:** calendar skeleton.\n- **Empty:** “No reservations in this period”.\n- Color events by `status.slug`.',
    '- `422` on invalid date range — reset to current month.',
    'Calendar supports `start` and `end` query params (date strings). Each event includes reservation id, customer, vehicle, and datetimes.',
    'reservations.view',
    '- **Page:** `ReservationsCalendarPage`\n- **Components:** `CalendarToolbar` (range picker), `ReservationCalendar`, `CalendarEventChip`'
);

// ─── Payments ───────────────────────────────────────────────────────────────

$lines[] = '## Payments';
$lines[] = '';

$workflow(
    'Payments list',
    'Browse all payments across reservations.',
    '`payments.view` permission.',
    '/admin/payments',
    "Open Payments page\n↓\nGET /admin/payments?page=1\n↓\nRender table (reservation, amount, method, status, date)\n↓\nUser changes page → GET ?page=n",
    '- `GET /admin/payments`',
    '',
    $snippet($captured, 64, 25),
    '- **Local:** `payments[]`, pagination meta.\n- **Discard:** rows on page change.',
    '- **Loading:** skeleton rows.\n- **Empty:** “No payments recorded”.\n- Link reservation number to reservation detail.',
    '- `403`: hide module.\n- Payments are **not** soft-deleted.',
    'Each row embeds `reservation.reservation_number` and `total_price` for context.',
    'payments.view',
    '- **Page:** `PaymentsListPage`\n- **Components:** `PaymentsTable`, `PaymentStatusBadge`, `Pagination`'
);

$workflow(
    'Payment details',
    'Inspect a single payment record.',
    'Payment id from list or reservation context.',
    '/admin/payments/{id}',
    "Click row\n↓\nGET /admin/payments/{id}\n↓\nRender payment detail card",
    '- `GET /admin/payments/{id}`',
    '',
    $snippet($captured, 66, 25),
    '- **Local:** `payment` object.\n- **Route:** `{id}` param.',
    '- **Loading:** card skeleton.\n- Show link back to parent reservation.',
    '- `404`: redirect to payments list.',
    'Includes `paid_by_customer_name`, `reference`, `notes`, and nested lookup objects.',
    'payments.view',
    '- **Page:** `PaymentDetailPage`\n- **Components:** `PaymentDetailCard`, `ReservationLink`'
);

$workflow(
    'Register payment',
    'Record a payment against a reservation; recalculates reservation payment_status.',
    '`payments.manage`.',
    'Reservation detail “Add payment” or /admin/payments/new',
    "Open payment form (pre-fill reservation_id from context)\n↓\nUser enters amount, method, type, status, date\n↓\nPOST /admin/payments\n↓\nRefresh GET /admin/reservations/{id}/payment-summary\n↓\nToast: “Payment recorded”",
    "- `POST /admin/payments`\n- `GET /admin/reservations/{id}/payment-summary` (refresh)",
    '```json
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
```',
    $snippet($captured, 65, 25),
    '- **Local:** append to payments list on reservation detail.\n- **Update** `paymentSummary` from refresh call.',
    '- **Loading:** disable submit.\n- **Success toast:** “Payment recorded”.\n- Pre-fill `paid_by_customer_name` from reservation customer.',
    '- `422` on invalid slug, amount, or reservation.\n- Only `paid` status increases `paid_amount` in summary.',
    'Creating with `failed` or `refunded` does not increase balance. Reservation `payment_status` auto-updates (e.g. `partial_paid`, `paid`).',
    'payments.manage',
    '- **Component:** `PaymentFormModal` on reservation detail\n- **Page (optional):** `PaymentCreatePage`'
);

$workflow(
    'Update payment',
    'Correct amount, status, or metadata; recalculates reservation balances.',
    '`payments.manage`; payment loaded.',
    '/admin/payments/{id}/edit',
    "Load GET /admin/payments/{id}\n↓\nPopulate form\n↓\nPUT or PATCH /admin/payments/{id}\n↓\nRefresh payment summary on linked reservation\n↓\nToast: “Payment updated”",
    "- `GET /admin/payments/{id}`\n- `PUT /admin/payments/{id}` or `PATCH /admin/payments/{id}`",
    'PATCH — send only changed fields (e.g. `payment_status_slug`, `amount`, `notes`).',
    $snippet($captured, 68, 25),
    '- **Local:** replace payment in cache.\n- **Refresh** parent reservation payment summary.',
    '- **Success toast:** “Payment updated”.',
    '- `422` on invalid transitions or amounts.',
    'Changing `payment_status_slug` to `cancelled`/`refunded`/`failed` removes amount from `paid_amount`. Use dedicated cancel endpoint when appropriate.',
    'payments.manage',
    '- **Page:** `PaymentEditPage` or inline edit on detail'
);

$workflow(
    'Cancel payment',
    'Void a payment via status transition (no DELETE endpoint).',
    '`payments.manage`.',
    'Payment detail or reservation payments list.',
    "User clicks Cancel payment\n↓\nConfirm dialog\n↓\nPOST /admin/payments/{id}/cancel\n↓\npayment_status.slug → cancelled\n↓\nRefresh payment summary\n↓\nToast: “Payment cancelled”",
    '- `POST /admin/payments/{id}/cancel`',
    'No request body.',
    $snippet($captured, 69, 25),
    '- **Local:** update payment status in list.\n- **Recalculate** summary `paid_amount` and `remaining_amount`.',
    '- **Confirmation:** warn that balance will increase.\n- **Success toast:** “Payment cancelled”.',
    '- There is **no DELETE** for payments.',
    'For failed/refunded scenarios, you may also create or PATCH with `payment_status_slug` of `failed` or `refunded` — neither counts toward `paid_amount`.',
    'payments.manage',
    '- **Component:** `CancelPaymentButton` on payment row/detail'
);

// ─── Contracts ────────────────────────────────────────────────────────────────

$lines[] = '## Contracts';
$lines[] = '';
$lines[] = '> There is **no** `GET /admin/contracts` list endpoint. Contracts are always accessed **per reservation** via `GET /admin/reservations/{id}/contract`.';
$lines[] = '';

$workflow(
    'View contract on reservation',
    'Show contract metadata and PDF availability flags.',
    '`contracts.view`; reservation detail open.',
    'Contract panel on reservation detail.',
    "Reservation detail loaded\n↓\nGET /admin/reservations/{id}/contract\n↓\nRender contract_number, status, has_pdf, has_signed_pdf\n↓\nShow Generate / Download / Upload actions based on state",
    '- `GET /admin/reservations/{id}/contract`',
    '',
    $snippet($captured, 71, 25),
    '- **Local:** `contract` object or `null`.\n- **Store** `contract.id` for download/upload routes.',
    '- **Loading:** panel skeleton.\n- **Empty (404):** “No contract yet” + Generate CTA if permitted.\n- Display `contract_status` badge.',
    '- `404` on GET contract: treat as no contract (not an error page).\n- `403`: hide contract panel.',
    'Response includes `has_pdf` and `has_signed_pdf` booleans — use these to show/hide download buttons.',
    'contracts.view',
    '- **Component:** `ContractPanel` on `ReservationDetailPage`\n- **Subcomponents:** `ContractStatusBadge`, `ContractActions`'
);

$workflow(
    'Generate contract',
    'Create or regenerate PDF contract for a confirmed+ reservation.',
    '`contracts.generate`; reservation not pending/rejected/cancelled.',
    'Contract panel on reservation detail.',
    "User clicks Generate contract\n↓\nPOST /admin/reservations/{id}/contract/generate\n↓\nRefresh GET /admin/reservations/{id}/contract\n↓\nEnable Download button\n↓\nToast: “Contract generated”",
    "- `POST /admin/reservations/{id}/contract/generate`\n- `GET /admin/reservations/{id}/contract` (refresh)",
    'No request body.',
    $snippet($captured, 70, 25),
    '- **Local:** replace `contract` with response.\n- **Keep** same `contract_number` on regeneration.',
    '- **Loading:** disable Generate during request.\n- **Success toast:** “Contract generated”.',
    '- `422` if reservation still `pending`.\n- Must be confirmed or later status.',
    'Regeneration keeps the same `contract_number` per captured QA behavior.',
    'contracts.generate',
    '- **Component:** `GenerateContractButton` inside `ContractPanel`'
);

$workflow(
    'Upload signed contract',
    'Attach customer-signed PDF; moves contract status toward signed.',
    '`contracts.update`; contract exists.',
    'Contract panel upload area.',
    "User selects signed PDF\n↓\nBuild FormData with signed_pdf\n↓\nPOST /admin/contracts/{id}/signed\n↓\nRefresh contract panel\n↓\nToast: “Signed contract uploaded”",
    '- `POST /admin/contracts/{contract}/signed`',
    "FormData:\n- `signed_pdf` (file, PDF)",
    $snippet($captured, 73, 25),
    '- **Local:** update `contract` status and `has_signed_pdf`.\n- **Do not** set Content-Type header on multipart.',
    '- **Loading:** upload progress bar.\n- **Success toast:** “Signed contract uploaded”.',
    '- `422` on invalid file type.',
    'Field name is `signed_pdf`. Optional in API but required for meaningful upload.',
    'contracts.update',
    '- **Component:** `SignedContractUpload` inside `ContractPanel`'
);

$workflow(
    'Download contract PDF',
    'Download generated or signed PDF as blob.',
    '`contracts.view`; `has_pdf` or `has_signed_pdf` is true.',
    'Contract panel download button.',
    "User clicks Download\n↓\nGET /admin/contracts/{id}/download\n↓\nresponse.blob()\n↓\nTrigger browser save or open in PDF viewer",
    '- `GET /admin/contracts/{contract}/download`',
    '',
    '_Binary PDF response (endpoint 72). Use `Accept: application/pdf` and bearer token._',
    '- **Local:** temporary `blobUrl` for preview; revoke after use.',
    '- **Loading:** spinner on download button.\n- **Error:** toast if PDF not ready.',
    '- `404` if PDF not generated yet.',
    'Response is binary — not JSON. Handle with `fetch` + `blob()`, not `response.json()`.',
    'contracts.view',
    '- **Component:** `DownloadContractButton`\n- **Optional:** `PdfPreviewModal` using `URL.createObjectURL(blob)`'
);

$workflow(
    'Cancel contract',
    'Cancel an existing contract record.',
    '`contracts.update`.',
    'Contract panel danger action.',
    "User clicks Cancel contract\n↓\nConfirm dialog\n↓\nPOST /admin/contracts/{id}/cancel\n↓\nRefresh contract panel\n↓\nToast: “Contract cancelled”",
    '- `POST /admin/contracts/{contract}/cancel`',
    'No request body.',
    $snippet($captured, 74, 25),
    '- **Local:** update `contract` status.',
    '- **Confirmation:** warn contract will be invalidated.\n- **Success toast:** “Contract cancelled”.',
    '- `422` if contract cannot be cancelled in current state.',
    'Separate from reservation cancel — this cancels the contract entity only.',
    'contracts.update',
    '- **Component:** `CancelContractButton` (danger, in `ContractPanel`)'
);

// ─── Maintenance ──────────────────────────────────────────────────────────────

$lines[] = '## Maintenance';
$lines[] = '';

$workflow(
    'List maintenance records',
    'Browse fleet maintenance history.',
    '`maintenance.view`.',
    '/admin/maintenances',
    "Open Maintenance page\n↓\nGET /admin/maintenances?page=1\n↓\nRender table (vehicle, type, date, cost, next date)\n↓\nPaginate",
    '- `GET /admin/maintenances`',
    '',
    $snippet($captured, 75, 25),
    '- **Local:** `maintenances[]`, pagination.',
    '- **Loading:** skeleton table.\n- **Empty:** “No maintenance records”.',
    '- Standard auth errors.',
    'Each row embeds `vehicle` summary (name, plate).',
    'maintenance.view',
    '- **Page:** `MaintenanceListPage`\n- **Components:** `MaintenanceTable`, `Pagination`'
);

$workflow(
    'Upcoming maintenance widget',
    'Dashboard widget for due-soon maintenance.',
    '`maintenance.view`.',
    'Dashboard or maintenance module sidebar.',
    "Dashboard mount\n↓\nGET /admin/maintenances/upcoming?page=1\n↓\nRender upcoming list widget\n↓\nClick row → maintenance detail or vehicle detail",
    '- `GET /admin/maintenances/upcoming`',
    '',
    $snippet($captured, 77, 25),
    '- **Local:** `upcomingMaintenances[]`.\n- **Global (optional):** cache on dashboard refresh.',
    '- **Loading:** widget skeleton.\n- **Empty:** “No upcoming maintenance”.\n- Highlight overdue `next_maintenance_date`.',
    '- Standard errors.',
    'Only records with `next_maintenance_date` today or later are returned.',
    'maintenance.view',
    '- **Component:** `UpcomingMaintenanceWidget` on dashboard\n- **Page link:** `MaintenanceListPage`'
);

$workflow(
    'Create maintenance record',
    'Log service work; optionally update vehicle status and auto-create expense.',
    '`maintenance.create`.',
    '/admin/maintenances/new',
    "Open create form\n↓\nLoad vehicles + lookups (maintenance types)\n↓\nUser fills form\n↓\nPOST /admin/maintenances\n↓\n201 → detail or list\n↓\nToast: “Maintenance recorded”",
    "- `GET /admin/vehicles?page=1` (vehicle select)\n- `GET /admin/lookups` (maintenance types)\n- `POST /admin/maintenances`",
    '```json
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
```',
    $snippet($captured, 76, 25),
    '- **Local:** form state.\n- **After success:** redirect to detail `data.id`.',
    '- **Loading:** disable submit.\n- **Success toast:** “Maintenance recorded”.\n- Toggle for `create_expense` shows `expense_category_slug` field when true.',
    '- `422` if `create_expense: true` but missing `expense_category_slug`.\n- `vehicle_status_slug` only `maintenance` or `repair`.',
    'Optional side effects: vehicle status update + linked expense creation server-side.',
    'maintenance.create',
    '- **Page:** `MaintenanceCreatePage`\n- **Components:** `VehicleSelect`, `MaintenanceTypeSelect`, `CreateExpenseToggle`'
);

$workflow(
    'Edit maintenance record',
    'Update maintenance details.',
    '`maintenance.update`; record loaded.',
    '/admin/maintenances/{id}/edit',
    "GET /admin/maintenances/{id}\n↓\nPopulate form\n↓\nPUT or PATCH /admin/maintenances/{id}\n↓\nRefresh detail\n↓\nToast: “Maintenance updated”",
    "- `GET /admin/maintenances/{id}`\n- `PUT /admin/maintenances/{id}` or `PATCH /admin/maintenances/{id}`",
    'PATCH — send changed fields only.',
    $snippet($captured, 80, 25),
    '- **Local:** form ↔ API.',
    '- **Success toast:** “Maintenance updated”.',
    '- `422` validation errors.',
    'PUT and PATCH share same handler.',
    'maintenance.update',
    '- **Page:** `MaintenanceEditPage`'
);

$workflow(
    'Close / delete maintenance record',
    'Remove a maintenance record (no separate “close” endpoint).',
    '`maintenance.delete`.',
    'Maintenance detail or list row action.',
    "User confirms delete\n↓\nDELETE /admin/maintenances/{id}\n↓\n204 → remove from list\n↓\nToast: “Maintenance record deleted”",
    '- `DELETE /admin/maintenances/{id}`',
    '',
    'HTTP 204 No Content',
    '- **Local:** remove from list cache.',
    '- **Confirmation:** standard delete dialog.\n- **Success toast:** “Maintenance record deleted”.',
    '- `404` if already deleted.',
    'There is **no** separate close action — use DELETE.',
    'maintenance.delete',
    '- **Component:** `DeleteMaintenanceButton`'
);

$workflow(
    'Vehicle maintenance tab',
    'Show maintenance history for one vehicle.',
    '`maintenance.view`; vehicle detail open.',
    'Vehicle detail → Maintenance tab.',
    "User opens Maintenance tab\n↓\nGET /admin/vehicles/{vehicleId}/maintenances\n↓\nRender history table\n↓\nLink to create maintenance (pre-fill vehicle_id)",
    '- `GET /admin/vehicles/{vehicle}/maintenances`',
    '',
    $snippet($captured, 34, 25),
    '- **Local:** `vehicleMaintenances[]` on tab.\n- **Lazy-load** tab on first open.',
    '- **Loading:** tab skeleton.\n- **Empty:** “No maintenance for this vehicle” + Add CTA.',
    '- Standard errors.',
    'Reuses maintenance list resource shape per vehicle.',
    'maintenance.view',
    '- **Component:** `VehicleMaintenanceTab` on `VehicleDetailPage`'
);

// ─── Expenses ─────────────────────────────────────────────────────────────────

$lines[] = '## Expenses';
$lines[] = '';

$workflow(
    'List expenses',
    'Browse operational and vehicle-linked expenses.',
    '`expenses.view`.',
    '/admin/expenses',
    "Open Expenses page\n↓\nGET /admin/expenses?page=1\n↓\nRender table (category, amount, date, vehicle, has_invoice)\n↓\nPaginate",
    '- `GET /admin/expenses`',
    '',
    $snippet($captured, 82, 25),
    '- **Local:** `expenses[]`, pagination.',
    '- **Loading:** skeleton table.\n- **Empty:** “No expenses recorded”.',
    '- Standard auth errors.',
    'Show `has_invoice` boolean — `invoice_path` is not exposed in API.',
    'expenses.view',
    '- **Page:** `ExpensesListPage`\n- **Components:** `ExpensesTable`, `InvoiceIndicator`, `Pagination`'
);

$workflow(
    'Monthly expense summary',
    'Show aggregated expenses for a month (dashboard or expenses page).',
    '`expenses.view`.',
    'Expenses page header or dashboard widget.',
    "User selects year + month\n↓\nGET /admin/expenses/monthly-summary?year=2026&month=6\n↓\nRender totals by category",
    '- `GET /admin/expenses/monthly-summary?year={y}&month={m}`',
    '',
    $snippet($captured, 84, 25),
    '- **Local:** `monthlySummary`, `selectedYear`, `selectedMonth`.',
    '- **Loading:** summary card skeleton.\n- **Empty:** zero totals still render (not an error).',
    '- `422` on invalid year/month.',
    'Useful alongside dashboard expenses endpoint — this is expense-module specific.',
    'expenses.view',
    '- **Component:** `MonthlyExpenseSummaryCard` on `ExpensesListPage` or dashboard'
);

$workflow(
    'Create expense',
    'Record a cost with optional invoice upload.',
    '`expenses.create`.',
    '/admin/expenses/new',
    "Open create form\n↓\nLoad lookups (expense categories) + optional vehicles\n↓\nUser fills fields (+ optional invoice file)\n↓\nPOST /admin/expenses (JSON or FormData if invoice)\n↓\n201 → detail or list\n↓\nToast: “Expense created”",
    "- `GET /admin/lookups`\n- `GET /admin/vehicles?page=1` (optional vehicle link)\n- `POST /admin/expenses`",
    'JSON body (no invoice):\n```json
{
  "expense_category_slug": "fuel",
  "amount": 250,
  "expense_date": "2026-06-10",
  "vehicle_id": 2,
  "description": "Fuel refill."
}
```\n\nWith invoice: use FormData with same fields + `invoice` file.',
    $snippet($captured, 83, 25),
    '- **Local:** form state.\n- **After success:** redirect using `data.id`.',
    '- **Loading:** disable submit; upload progress if multipart.\n- **Success toast:** “Expense created”.',
    '- `422` on invalid category slug or amount.',
    'Invoice upload is optional on create. Newman QA uses JSON body; multipart supported when `invoice` present.',
    'expenses.create',
    '- **Page:** `ExpenseCreatePage`\n- **Components:** `ExpenseCategorySelect`, `VehicleSelect` (optional), `InvoiceUpload`'
);

$workflow(
    'View expense details',
    'Inspect one expense including invoice flag.',
    '`expenses.view`.',
    '/admin/expenses/{id}',
    "Click row\n↓\nGET /admin/expenses/{id}\n↓\nRender detail card",
    '- `GET /admin/expenses/{id}`',
    '',
    $snippet($captured, 85, 25),
    '- **Local:** `expense` object.',
    '- **Loading:** card skeleton.\n- Show `has_invoice` — no direct download URL in API.',
    '- `404`: back to list.',
    'Linked `vehicle` embedded when `vehicle_id` set.',
    'expenses.view',
    '- **Page:** `ExpenseDetailPage`'
);

$workflow(
    'Edit expense',
    'Update expense fields or replace invoice.',
    '`expenses.update`; expense loaded.',
    '/admin/expenses/{id}/edit',
    "GET /admin/expenses/{id}\n↓\nPopulate form\n↓\nPUT or PATCH /admin/expenses/{id}\n↓\nRefresh detail\n↓\nToast: “Expense updated”",
    "- `GET /admin/expenses/{id}`\n- `PUT /admin/expenses/{id}` or `PATCH /admin/expenses/{id}`",
    'PATCH changed fields. For invoice replacement use FormData on PUT/PATCH with `invoice` file.',
    $snippet($captured, 87, 25),
    '- **Local:** form ↔ API.',
    '- **Success toast:** “Expense updated”.',
    '- `422` validation errors.',
    'PUT/PATCH accept optional multipart for invoice replacement.',
    'expenses.update',
    '- **Page:** `ExpenseEditPage`'
);

$workflow(
    'Delete expense',
    'Remove an expense record.',
    '`expenses.delete`.',
    'Expense detail or list row.',
    "Confirm delete\n↓\nDELETE /admin/expenses/{id}\n↓\n204 → list\n↓\nToast: “Expense deleted”",
    '- `DELETE /admin/expenses/{id}`',
    '',
    'HTTP 204 No Content',
    '- **Local:** remove from list.',
    '- **Confirmation:** delete dialog.\n- **Success toast:** “Expense deleted”.',
    '- `404` if already deleted.',
    'Soft delete — hidden from lists.',
    'expenses.delete',
    '- **Component:** `DeleteExpenseButton`'
);

$workflow(
    'Vehicle expenses tab',
    'Show expenses linked to one vehicle.',
    '`expenses.view`; vehicle detail open.',
    'Vehicle detail → Expenses tab.',
    "Open Expenses tab\n↓\nGET /admin/vehicles/{vehicleId}/expenses\n↓\nRender expense list",
    '- `GET /admin/vehicles/{vehicle}/expenses`',
    '',
    $snippet($captured, 35, 25),
    '- **Local:** `vehicleExpenses[]`.\n- **Lazy-load** on tab open.',
    '- **Loading:** tab skeleton.\n- **Empty:** “No expenses for this vehicle”.',
    '- Standard errors.',
    'Same expense resource shape as main list, filtered by vehicle.',
    'expenses.view',
    '- **Component:** `VehicleExpensesTab` on `VehicleDetailPage`'
);

// ─── Alerts / notifications ───────────────────────────────────────────────────

$lines[] = '## Alerts / notifications';
$lines[] = '';
$lines[] = '> API uses `seen`, `done`, and `ignore` — not “read” or “dismiss”. Map UI labels accordingly.';
$lines[] = '';

$workflow(
    'List alerts',
    'Browse operational alerts (documents expiring, maintenance due, etc.).',
    '`alerts.view`.',
    '/admin/alerts',
    "Open Alerts page\n↓\nGET /admin/alerts?page=1\n↓\nRender table (type, message, status, dates)\n↓\nPaginate",
    '- `GET /admin/alerts`',
    '',
    $snippet($captured, 89, 25),
    '- **Local:** `alerts[]`, pagination.',
    '- **Loading:** skeleton rows.\n- **Empty:** “No alerts”.',
    '- Filter client-side by `alert_status.slug` on current page.',
    'Alert types and statuses come from nested lookup objects in each row.',
    'alerts.view',
    '- **Page:** `AlertsListPage`\n- **Components:** `AlertsTable`, `AlertStatusBadge`, `Pagination`'
);

$workflow(
    'Pending alerts badge',
    'Show count of pending alerts in nav or dashboard.',
    '`alerts.view`.',
    'App shell header / dashboard.',
    "App shell mount (or poll interval)\n↓\nGET /admin/alerts/pending\n↓\nShow badge count from response length or meta",
    '- `GET /admin/alerts/pending`',
    '',
    $snippet($captured, 90, 25),
    '- **Global:** `pendingAlertsCount` in app shell state.\n- **Refresh** on interval or after alert action.',
    '- **Loading:** small spinner on badge area only.\n- **Empty:** hide badge when count is 0.',
    '- Standard auth errors.',
    'Use for notification bell — lighter than full alerts list.',
    'alerts.view',
    '- **Component:** `PendingAlertsBadge` in `AppHeader`'
);

$workflow(
    'Mark alert as seen',
    'Acknowledge alert without closing it (maps to “read” in UI).',
    '`alerts.update`; alert `alert_status.slug` is `pending`.',
    'Alert row or detail.',
    "User opens alert or clicks Mark seen\n↓\nPATCH /admin/alerts/{id}/seen\n↓\nalert_status.slug → seen\n↓\nDecrement pending badge\n↓\nToast: “Alert marked as seen”",
    '- `PATCH /admin/alerts/{id}/seen`',
    'No request body.',
    $snippet($captured, 94, 25),
    '- **Local:** update alert status in list.\n- **Global:** decrement `pendingAlertsCount`.',
    '- **Success toast:** “Alert marked as seen”.',
    '- `422` if not currently `pending`.',
    'Only valid transition from `pending` → `seen`.',
    'alerts.update',
    '- **Component:** `MarkAlertSeenButton` on alert row'
);

$workflow(
    'Mark alert as done',
    'Resolve alert as completed.',
    '`alerts.update`; status is `pending` or `seen`.',
    'Alert row actions.',
    "User clicks Mark done\n↓\nPATCH /admin/alerts/{id}/done\n↓\nalert_status.slug → done\n↓\nToast: “Alert resolved”",
    '- `PATCH /admin/alerts/{id}/done`',
    'No request body.',
    $snippet($captured, 95, 25),
    '- **Local:** update or remove from pending list.',
    '- **Success toast:** “Alert resolved”.',
    '- `422` from invalid status.',
    'Valid from `pending` or `seen`.',
    'alerts.update',
    '- **Component:** `MarkAlertDoneButton`'
);

$workflow(
    'Ignore alert (dismiss)',
    'Dismiss alert without action (maps to “dismiss” in UI).',
    '`alerts.update`; status is `pending` or `seen`.',
    'Alert row actions.',
    "User clicks Ignore\n↓\nPATCH /admin/alerts/{id}/ignore\n↓\nalert_status.slug → ignored\n↓\nToast: “Alert dismissed”",
    '- `PATCH /admin/alerts/{id}/ignore`',
    'No request body.',
    $snippet($captured, 96, 25),
    '- **Local:** update status; remove from active filters.',
    '- **Success toast:** “Alert dismissed”.',
    '- `422` from invalid status.',
    'Valid from `pending` or `seen`. Terminal for workflow purposes.',
    'alerts.update',
    '- **Component:** `IgnoreAlertButton`'
);

$workflow(
    'Generate system alerts',
    'Trigger server-side alert generation (admin action).',
    '`alerts.create`.',
    'Alerts page admin action.',
    "User clicks Generate alerts\n↓\nPOST /admin/alerts/generate\n↓\nShow count of alerts created\n↓\nRefresh alerts list + pending badge\n↓\nToast: “{n} alerts generated” or “No new alerts”",
    '- `POST /admin/alerts/generate`',
    'No request body.',
    $snippet($captured, 92, 20),
    '- **Local:** refresh lists after response.',
    '- **Success toast:** show `total_created` from response.\n- **Empty result:** “No new alerts” (duplicate pending may yield 0).',
    '- Repeat calls may return `total_created: 0` if duplicates exist.',
    'Server evaluates business rules (expiring documents, upcoming maintenance, etc.).',
    'alerts.create',
    '- **Component:** `GenerateAlertsButton` on `AlertsListPage`'
);

// ─── Locations ────────────────────────────────────────────────────────────────

$lines[] = '## Locations';
$lines[] = '';

$workflow(
    'List locations',
    'Browse pickup/dropoff locations for reservations.',
    '`locations.view`.',
    '/admin/locations',
    "Open Locations page\n↓\nGET /admin/locations?page=1\n↓\nRender table (name, type, address, delivery_fee, active)\n↓\nPaginate",
    '- `GET /admin/locations`',
    '',
    $snippet($captured, 44, 25),
    '- **Local:** `locations[]`, pagination.\n- **Global:** cache active locations for reservation forms.',
    '- **Loading:** skeleton table.\n- **Empty:** “No locations” + Create CTA.',
    '- Admin list includes **inactive** locations (unlike public API).',
    'Used in reservation create/edit for `pickup_location_id` and `dropoff_location_id`.',
    'locations.view',
    '- **Page:** `LocationsListPage`\n- **Components:** `LocationsTable`, `ActiveBadge`, `Pagination`'
);

$workflow(
    'Create location',
    'Add a pickup/dropoff point.',
    '`locations.create`; lookups loaded.',
    '/admin/locations/new',
    "Open create form\n↓\nLoad GET /admin/lookups (location types)\n↓\nUser fills form\n↓\nPOST /admin/locations\n↓\n201 → detail or list\n↓\nToast: “Location created”",
    "- `GET /admin/lookups`\n- `POST /admin/locations`",
    '```json
{
  "location_type_slug": "agency",
  "name": "Postman Location",
  "slug": "postman-location",
  "address": "Postman Street, Dakhla",
  "delivery_fee": 0,
  "is_active": true
}
```',
    $snippet($captured, 45, 20),
    '- **Local:** form state.\n- **After success:** redirect using `data.id`.',
    '- **Loading:** disable submit.\n- **Success toast:** “Location created”.',
    '- `422` on duplicate slug or invalid `location_type_slug`.',
    'Inactive locations are hidden from `GET /public/locations` but visible in admin.',
    'locations.create',
    '- **Page:** `LocationCreatePage`\n- **Components:** `LocationTypeSelect`, `SlugInput`'
);

$workflow(
    'View location details',
    'Inspect one location.',
    '`locations.view`.',
    '/admin/locations/{id}',
    "Click row\n↓\nGET /admin/locations/{id}\n↓\nRender detail card",
    '- `GET /admin/locations/{id}`',
    '',
    $snippet($captured, 46, 20),
    '- **Local:** `location` object.',
    '- **Loading:** card skeleton.',
    '- `404`: back to list.',
    'Soft-deleted locations return 404.',
    'locations.view',
    '- **Page:** `LocationDetailPage`'
);

$workflow(
    'Update location',
    'Edit location fields (full or partial).',
    '`locations.update`; location loaded.',
    '/admin/locations/{id}/edit',
    "GET /admin/locations/{id}\n↓\nPopulate form\n↓\nPUT or PATCH /admin/locations/{id}\n↓\nRefresh detail\n↓\nToast: “Location updated”",
    "- `GET /admin/locations/{id}`\n- `PUT /admin/locations/{id}` or `PATCH /admin/locations/{id}`",
    'PATCH example: `{ "delivery_fee": 150, "is_active": false }`',
    $snippet($captured, 48, 20),
    '- **Local:** form ↔ API.\n- **Invalidate** cached location lists if used in forms.',
    '- **Success toast:** “Location updated”.',
    '- `422` validation errors.',
    'Deactivating (`is_active: false`) removes from public website but keeps in admin.',
    'locations.update',
    '- **Page:** `LocationEditPage`'
);

$workflow(
    'Delete location',
    'Remove location (soft delete).',
    '`locations.delete`.',
    'Location detail or list row.',
    "Confirm delete\n↓\nDELETE /admin/locations/{id}\n↓\n204 → list\n↓\nToast: “Location deleted”",
    '- `DELETE /admin/locations/{id}`',
    '',
    'HTTP 204 No Content',
    '- **Local:** remove from list.\n- **Invalidate** reservation form caches.',
    '- **Confirmation:** warn if used in upcoming reservations.\n- **Success toast:** “Location deleted”.',
    '- May fail if referenced by active reservations.',
    'Soft delete — 404 on subsequent GET.',
    'locations.delete',
    '- **Component:** `DeleteLocationButton`'
);
