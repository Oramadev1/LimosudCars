<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Laravel API Documentation</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .bash-example code { display: none; }
                    body .content .javascript-example code { display: none; }
            </style>

    <script>
        var tryItOutBaseUrl = "http://localhost";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-5.11.0.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-5.11.0.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                    <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
                            </ul>
                    <ul id="tocify-header-authenticating-requests" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authenticating-requests">
                    <a href="#authenticating-requests">Authenticating requests</a>
                </li>
                            </ul>
                    <ul id="tocify-header-admin-auth" class="tocify-header">
                <li class="tocify-item level-1" data-unique="admin-auth">
                    <a href="#admin-auth">Admin Auth</a>
                </li>
                                    <ul id="tocify-subheader-admin-auth" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="admin-auth-POSTapi-admin-auth-login">
                                <a href="#admin-auth-POSTapi-admin-auth-login">Authenticate an admin user and issue a Sanctum token.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-auth-GETapi-admin-auth-me">
                                <a href="#admin-auth-GETapi-admin-auth-me">Return the authenticated admin user with roles and permissions.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-auth-POSTapi-admin-auth-logout">
                                <a href="#admin-auth-POSTapi-admin-auth-logout">Revoke the current Sanctum token.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-alerts" class="tocify-header">
                <li class="tocify-item level-1" data-unique="alerts">
                    <a href="#alerts">Alerts</a>
                </li>
                                    <ul id="tocify-subheader-alerts" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="alerts-GETapi-admin-alerts">
                                <a href="#alerts-GETapi-admin-alerts">List alerts.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="alerts-GETapi-admin-alerts-pending">
                                <a href="#alerts-GETapi-admin-alerts-pending">List pending alerts.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="alerts-POSTapi-admin-alerts">
                                <a href="#alerts-POSTapi-admin-alerts">Create an alert.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="alerts-POSTapi-admin-alerts-generate">
                                <a href="#alerts-POSTapi-admin-alerts-generate">Generate maintenance and document expiry alerts.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="alerts-GETapi-admin-alerts--alert_id-">
                                <a href="#alerts-GETapi-admin-alerts--alert_id-">Display an alert.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="alerts-PATCHapi-admin-alerts--alert_id--seen">
                                <a href="#alerts-PATCHapi-admin-alerts--alert_id--seen">Mark a pending alert as seen.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="alerts-PATCHapi-admin-alerts--alert_id--done">
                                <a href="#alerts-PATCHapi-admin-alerts--alert_id--done">Mark a pending or seen alert as done.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="alerts-PATCHapi-admin-alerts--alert_id--ignore">
                                <a href="#alerts-PATCHapi-admin-alerts--alert_id--ignore">Ignore a pending or seen alert.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-contracts" class="tocify-header">
                <li class="tocify-item level-1" data-unique="contracts">
                    <a href="#contracts">Contracts</a>
                </li>
                                    <ul id="tocify-subheader-contracts" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="contracts-POSTapi-admin-reservations--reservation_id--contract-generate">
                                <a href="#contracts-POSTapi-admin-reservations--reservation_id--contract-generate">Generate or regenerate a reservation contract.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="contracts-GETapi-admin-reservations--reservation_id--contract">
                                <a href="#contracts-GETapi-admin-reservations--reservation_id--contract">Display the reservation contract.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="contracts-GETapi-admin-contracts--contract_id--download">
                                <a href="#contracts-GETapi-admin-contracts--contract_id--download">Download the generated contract PDF from private storage.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="contracts-POSTapi-admin-contracts--contract_id--signed">
                                <a href="#contracts-POSTapi-admin-contracts--contract_id--signed">Upload a signed PDF or mark the contract as signed.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="contracts-POSTapi-admin-contracts--contract_id--cancel">
                                <a href="#contracts-POSTapi-admin-contracts--contract_id--cancel">Mark the contract as cancelled.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-customers" class="tocify-header">
                <li class="tocify-item level-1" data-unique="customers">
                    <a href="#customers">Customers</a>
                </li>
                                    <ul id="tocify-subheader-customers" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="customers-GETapi-admin-customers">
                                <a href="#customers-GETapi-admin-customers">List customers for the admin dashboard.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="customers-POSTapi-admin-customers">
                                <a href="#customers-POSTapi-admin-customers">Store a new customer.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="customers-GETapi-admin-customers--customer_id-">
                                <a href="#customers-GETapi-admin-customers--customer_id-">Display a customer.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="customers-PUTapi-admin-customers--customer_id-">
                                <a href="#customers-PUTapi-admin-customers--customer_id-">Update a customer.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="customers-PATCHapi-admin-customers--customer_id-">
                                <a href="#customers-PATCHapi-admin-customers--customer_id-">Update a customer.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="customers-DELETEapi-admin-customers--customer_id-">
                                <a href="#customers-DELETEapi-admin-customers--customer_id-">Soft delete a customer.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="customers-POSTapi-admin-customers--customer_id--documents">
                                <a href="#customers-POSTapi-admin-customers--customer_id--documents">Upload a customer document and link it to a document type lookup.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="customers-DELETEapi-admin-customer-documents--document_id-">
                                <a href="#customers-DELETEapi-admin-customer-documents--document_id-">Delete a stored customer document file and soft delete the record.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-dashboard" class="tocify-header">
                <li class="tocify-item level-1" data-unique="dashboard">
                    <a href="#dashboard">Dashboard</a>
                </li>
                                    <ul id="tocify-subheader-dashboard" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="dashboard-GETapi-admin-dashboard-statistics">
                                <a href="#dashboard-GETapi-admin-dashboard-statistics">Get global dashboard KPIs.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="dashboard-GETapi-admin-dashboard-revenue">
                                <a href="#dashboard-GETapi-admin-dashboard-revenue">Get revenue reporting totals.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="dashboard-GETapi-admin-dashboard-expenses">
                                <a href="#dashboard-GETapi-admin-dashboard-expenses">Get expense reporting totals.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-expenses" class="tocify-header">
                <li class="tocify-item level-1" data-unique="expenses">
                    <a href="#expenses">Expenses</a>
                </li>
                                    <ul id="tocify-subheader-expenses" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="expenses-GETapi-admin-vehicles--vehicle_id--expenses">
                                <a href="#expenses-GETapi-admin-vehicles--vehicle_id--expenses">List expenses for one vehicle.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="expenses-GETapi-admin-expenses">
                                <a href="#expenses-GETapi-admin-expenses">List expenses.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="expenses-POSTapi-admin-expenses">
                                <a href="#expenses-POSTapi-admin-expenses">Create an expense.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="expenses-GETapi-admin-expenses-monthly-summary">
                                <a href="#expenses-GETapi-admin-expenses-monthly-summary">Get monthly expense totals by category.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="expenses-GETapi-admin-expenses--expense_id-">
                                <a href="#expenses-GETapi-admin-expenses--expense_id-">Display an expense.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="expenses-PUTapi-admin-expenses--expense_id-">
                                <a href="#expenses-PUTapi-admin-expenses--expense_id-">Update an expense.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="expenses-PATCHapi-admin-expenses--expense_id-">
                                <a href="#expenses-PATCHapi-admin-expenses--expense_id-">Update an expense.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="expenses-DELETEapi-admin-expenses--expense_id-">
                                <a href="#expenses-DELETEapi-admin-expenses--expense_id-">Soft delete an expense.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-locations" class="tocify-header">
                <li class="tocify-item level-1" data-unique="locations">
                    <a href="#locations">Locations</a>
                </li>
                                    <ul id="tocify-subheader-locations" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="locations-GETapi-admin-locations">
                                <a href="#locations-GETapi-admin-locations">List locations for the admin dashboard.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="locations-POSTapi-admin-locations">
                                <a href="#locations-POSTapi-admin-locations">Store a new location.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="locations-GETapi-admin-locations--location_id-">
                                <a href="#locations-GETapi-admin-locations--location_id-">Display a location.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="locations-PUTapi-admin-locations--location_id-">
                                <a href="#locations-PUTapi-admin-locations--location_id-">Update a location.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="locations-PATCHapi-admin-locations--location_id-">
                                <a href="#locations-PATCHapi-admin-locations--location_id-">Update a location.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="locations-DELETEapi-admin-locations--location_id-">
                                <a href="#locations-DELETEapi-admin-locations--location_id-">Soft delete a location.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-maintenance" class="tocify-header">
                <li class="tocify-item level-1" data-unique="maintenance">
                    <a href="#maintenance">Maintenance</a>
                </li>
                                    <ul id="tocify-subheader-maintenance" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="maintenance-GETapi-admin-vehicles--vehicle_id--maintenances">
                                <a href="#maintenance-GETapi-admin-vehicles--vehicle_id--maintenances">List maintenance records for one vehicle.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="maintenance-GETapi-admin-maintenances">
                                <a href="#maintenance-GETapi-admin-maintenances">List maintenance records.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="maintenance-POSTapi-admin-maintenances">
                                <a href="#maintenance-POSTapi-admin-maintenances">Create a maintenance record.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="maintenance-GETapi-admin-maintenances-upcoming">
                                <a href="#maintenance-GETapi-admin-maintenances-upcoming">List upcoming maintenance records.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="maintenance-GETapi-admin-maintenances--maintenance_id-">
                                <a href="#maintenance-GETapi-admin-maintenances--maintenance_id-">Display a maintenance record.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="maintenance-PUTapi-admin-maintenances--maintenance_id-">
                                <a href="#maintenance-PUTapi-admin-maintenances--maintenance_id-">Update a maintenance record.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="maintenance-PATCHapi-admin-maintenances--maintenance_id-">
                                <a href="#maintenance-PATCHapi-admin-maintenances--maintenance_id-">Update a maintenance record.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="maintenance-DELETEapi-admin-maintenances--maintenance_id-">
                                <a href="#maintenance-DELETEapi-admin-maintenances--maintenance_id-">Soft delete a maintenance record.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-payments" class="tocify-header">
                <li class="tocify-item level-1" data-unique="payments">
                    <a href="#payments">Payments</a>
                </li>
                                    <ul id="tocify-subheader-payments" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="payments-GETapi-admin-reservations--reservation_id--payment-summary">
                                <a href="#payments-GETapi-admin-reservations--reservation_id--payment-summary">Return calculated payment totals for a reservation.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="payments-GETapi-admin-payments">
                                <a href="#payments-GETapi-admin-payments">List payments for the admin dashboard.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="payments-POSTapi-admin-payments">
                                <a href="#payments-POSTapi-admin-payments">Store a new payment and recalculate reservation payment status.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="payments-GETapi-admin-payments--payment_id-">
                                <a href="#payments-GETapi-admin-payments--payment_id-">Display a payment.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="payments-PUTapi-admin-payments--payment_id-">
                                <a href="#payments-PUTapi-admin-payments--payment_id-">Update a payment and recalculate reservation payment status.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="payments-PATCHapi-admin-payments--payment_id-">
                                <a href="#payments-PATCHapi-admin-payments--payment_id-">Update a payment and recalculate reservation payment status.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="payments-POSTapi-admin-payments--payment_id--cancel">
                                <a href="#payments-POSTapi-admin-payments--payment_id--cancel">Safely cancel a payment without deleting the financial record.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-public" class="tocify-header">
                <li class="tocify-item level-1" data-unique="public">
                    <a href="#public">Public</a>
                </li>
                                    <ul id="tocify-subheader-public" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="public-GETapi-public-lookups">
                                <a href="#public-GETapi-public-lookups">Public safe lookup data.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="public-GETapi-public-locations">
                                <a href="#public-GETapi-public-locations">List active public pickup/dropoff locations.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="public-GETapi-public-vehicles">
                                <a href="#public-GETapi-public-vehicles">List active vehicles for the public website.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="public-GETapi-public-vehicles--vehicle_id--availability">
                                <a href="#public-GETapi-public-vehicles--vehicle_id--availability">Check availability for an active vehicle.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="public-GETapi-public-vehicles--slug-">
                                <a href="#public-GETapi-public-vehicles--slug-">Show an active vehicle by slug.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="public-POSTapi-public-reservations">
                                <a href="#public-POSTapi-public-reservations">Create a public pending reservation request.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="public-POSTapi-public-reservations-check-availability">
                                <a href="#public-POSTapi-public-reservations-check-availability">Check vehicle availability for public visitors.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="public-GETapi-admin-lookups">
                                <a href="#public-GETapi-admin-lookups">Admin lookup data.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-reservations" class="tocify-header">
                <li class="tocify-item level-1" data-unique="reservations">
                    <a href="#reservations">Reservations</a>
                </li>
                                    <ul id="tocify-subheader-reservations" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="reservations-GETapi-admin-reservations">
                                <a href="#reservations-GETapi-admin-reservations">List reservations for the admin dashboard.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="reservations-POSTapi-admin-reservations">
                                <a href="#reservations-POSTapi-admin-reservations">Store a manually created admin reservation.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="reservations-POSTapi-admin-reservations-check-availability">
                                <a href="#reservations-POSTapi-admin-reservations-check-availability">Check vehicle availability for admin workflows.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="reservations-GETapi-admin-reservations-calendar">
                                <a href="#reservations-GETapi-admin-reservations-calendar">Return reservations in a lightweight calendar collection.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="reservations-GETapi-admin-reservations--reservation_id-">
                                <a href="#reservations-GETapi-admin-reservations--reservation_id-">Display a reservation.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="reservations-PUTapi-admin-reservations--reservation_id-">
                                <a href="#reservations-PUTapi-admin-reservations--reservation_id-">Update a reservation and recalculate pricing when booking inputs change.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="reservations-PATCHapi-admin-reservations--reservation_id-">
                                <a href="#reservations-PATCHapi-admin-reservations--reservation_id-">Update a reservation and recalculate pricing when booking inputs change.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="reservations-DELETEapi-admin-reservations--reservation_id-">
                                <a href="#reservations-DELETEapi-admin-reservations--reservation_id-">Soft delete a reservation.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="reservations-POSTapi-admin-reservations--reservation_id--confirm">
                                <a href="#reservations-POSTapi-admin-reservations--reservation_id--confirm">Confirm a pending reservation and reserve the vehicle.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="reservations-POSTapi-admin-reservations--reservation_id--start">
                                <a href="#reservations-POSTapi-admin-reservations--reservation_id--start">Start a confirmed reservation.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="reservations-POSTapi-admin-reservations--reservation_id--complete">
                                <a href="#reservations-POSTapi-admin-reservations--reservation_id--complete">Complete an in-progress reservation.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="reservations-POSTapi-admin-reservations--reservation_id--cancel">
                                <a href="#reservations-POSTapi-admin-reservations--reservation_id--cancel">Cancel a reservation and free reserved/rented vehicles.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="reservations-POSTapi-admin-reservations--reservation_id--reject">
                                <a href="#reservations-POSTapi-admin-reservations--reservation_id--reject">Reject a pending reservation request.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-vehicle-brands" class="tocify-header">
                <li class="tocify-item level-1" data-unique="vehicle-brands">
                    <a href="#vehicle-brands">Vehicle Brands</a>
                </li>
                                    <ul id="tocify-subheader-vehicle-brands" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="vehicle-brands-GETapi-admin-vehicle-brands">
                                <a href="#vehicle-brands-GETapi-admin-vehicle-brands">List vehicle brands.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="vehicle-brands-POSTapi-admin-vehicle-brands">
                                <a href="#vehicle-brands-POSTapi-admin-vehicle-brands">Create a vehicle brand.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="vehicle-brands-GETapi-admin-vehicle-brands--brand_id-">
                                <a href="#vehicle-brands-GETapi-admin-vehicle-brands--brand_id-">Display a vehicle brand.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="vehicle-brands-PUTapi-admin-vehicle-brands--brand_id-">
                                <a href="#vehicle-brands-PUTapi-admin-vehicle-brands--brand_id-">Update a vehicle brand.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="vehicle-brands-PATCHapi-admin-vehicle-brands--brand_id-">
                                <a href="#vehicle-brands-PATCHapi-admin-vehicle-brands--brand_id-">Update a vehicle brand.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="vehicle-brands-DELETEapi-admin-vehicle-brands--brand_id-">
                                <a href="#vehicle-brands-DELETEapi-admin-vehicle-brands--brand_id-">Soft delete a vehicle brand.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-vehicle-categories" class="tocify-header">
                <li class="tocify-item level-1" data-unique="vehicle-categories">
                    <a href="#vehicle-categories">Vehicle Categories</a>
                </li>
                                    <ul id="tocify-subheader-vehicle-categories" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="vehicle-categories-GETapi-admin-vehicle-categories">
                                <a href="#vehicle-categories-GETapi-admin-vehicle-categories">List vehicle categories.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="vehicle-categories-POSTapi-admin-vehicle-categories">
                                <a href="#vehicle-categories-POSTapi-admin-vehicle-categories">Create a vehicle category.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="vehicle-categories-GETapi-admin-vehicle-categories--category_id-">
                                <a href="#vehicle-categories-GETapi-admin-vehicle-categories--category_id-">Display a vehicle category.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="vehicle-categories-PUTapi-admin-vehicle-categories--category_id-">
                                <a href="#vehicle-categories-PUTapi-admin-vehicle-categories--category_id-">Update a vehicle category.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="vehicle-categories-PATCHapi-admin-vehicle-categories--category_id-">
                                <a href="#vehicle-categories-PATCHapi-admin-vehicle-categories--category_id-">Update a vehicle category.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="vehicle-categories-DELETEapi-admin-vehicle-categories--category_id-">
                                <a href="#vehicle-categories-DELETEapi-admin-vehicle-categories--category_id-">Soft delete a vehicle category.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-vehicles" class="tocify-header">
                <li class="tocify-item level-1" data-unique="vehicles">
                    <a href="#vehicles">Vehicles</a>
                </li>
                                    <ul id="tocify-subheader-vehicles" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="vehicles-GETapi-admin-vehicles">
                                <a href="#vehicles-GETapi-admin-vehicles">List vehicles for the admin dashboard.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="vehicles-POSTapi-admin-vehicles">
                                <a href="#vehicles-POSTapi-admin-vehicles">Store a new vehicle.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="vehicles-GETapi-admin-vehicles--vehicle_id-">
                                <a href="#vehicles-GETapi-admin-vehicles--vehicle_id-">Display a vehicle.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="vehicles-PUTapi-admin-vehicles--vehicle_id-">
                                <a href="#vehicles-PUTapi-admin-vehicles--vehicle_id-">Update a vehicle.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="vehicles-PATCHapi-admin-vehicles--vehicle_id-">
                                <a href="#vehicles-PATCHapi-admin-vehicles--vehicle_id-">Update a vehicle.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="vehicles-DELETEapi-admin-vehicles--vehicle_id-">
                                <a href="#vehicles-DELETEapi-admin-vehicles--vehicle_id-">Soft delete a vehicle.</a>
                            </li>
                                                                        </ul>
                            </ul>
            </div>

    <ul class="toc-footer" id="toc-footer">
                    <li style="padding-bottom: 5px;"><a href="{{ route("scribe.postman") }}">View Postman collection</a></li>
                            <li style="padding-bottom: 5px;"><a href="{{ route("scribe.openapi") }}">View OpenAPI spec</a></li>
                <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
    </ul>

    <ul class="toc-footer" id="last-updated">
        <li>Last updated: June 10, 2026</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<p>REST API documentation for the Limosud Cars Laravel backend.</p>
<aside>
    <strong>Base URL</strong>: <code>http://localhost</code>
</aside>
<pre><code>Public endpoints do not require authentication. Admin endpoints require a Laravel Sanctum bearer token and the permission listed in each endpoint description.

&lt;aside&gt;Use the admin login endpoint to retrieve a Sanctum token, then send it as &lt;code&gt;Authorization: Bearer {token}&lt;/code&gt;.&lt;/aside&gt;</code></pre>

        <h1 id="authenticating-requests">Authenticating requests</h1>
<p>To authenticate requests, include an <strong><code>Authorization</code></strong> header with the value <strong><code>"Bearer {SANCTUM_TOKEN}"</code></strong>.</p>
<p>All authenticated endpoints are marked with a <code>requires authentication</code> badge in the documentation below.</p>
<p>Admin endpoints use Laravel Sanctum personal access tokens. Send the token as <code>Authorization: Bearer {SANCTUM_TOKEN}</code>.</p>

        <h1 id="admin-auth">Admin Auth</h1>

    <p>Endpoints for issuing and revoking Laravel Sanctum admin API tokens.</p>

                                <h2 id="admin-auth-POSTapi-admin-auth-login">Authenticate an admin user and issue a Sanctum token.</h2>

<p>
</p>



<span id="example-requests-POSTapi-admin-auth-login">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/admin/auth/login" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"admin@limosudcars.local\",
    \"password\": \"password\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/auth/login"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "admin@limosudcars.local",
    "password": "password"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-admin-auth-login">
</span>
<span id="execution-results-POSTapi-admin-auth-login" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-admin-auth-login"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-auth-login"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-admin-auth-login" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-auth-login">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-admin-auth-login" data-method="POST"
      data-path="api/admin/auth/login"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-auth-login', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-admin-auth-login"
                    onclick="tryItOut('POSTapi-admin-auth-login');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-admin-auth-login"
                    onclick="cancelTryOut('POSTapi-admin-auth-login');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-admin-auth-login"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/admin/auth/login</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-admin-auth-login"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-admin-auth-login"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-admin-auth-login"
               value="admin@limosudcars.local"
               data-component="body">
    <br>
<p>Admin email address. Example: <code>admin@limosudcars.local</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-admin-auth-login"
               value="password"
               data-component="body">
    <br>
<p>Admin password. Example: <code>password</code></p>
        </div>
        </form>

                    <h2 id="admin-auth-GETapi-admin-auth-me">Return the authenticated admin user with roles and permissions.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Send a Sanctum bearer token in the Authorization header.</p>

<span id="example-requests-GETapi-admin-auth-me">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/auth/me" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/auth/me"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-auth-me">
    </span>
<span id="execution-results-GETapi-admin-auth-me" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-auth-me"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-auth-me"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-auth-me" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-auth-me">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-auth-me" data-method="GET"
      data-path="api/admin/auth/me"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-auth-me', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-auth-me"
                    onclick="tryItOut('GETapi-admin-auth-me');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-auth-me"
                    onclick="cancelTryOut('GETapi-admin-auth-me');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-auth-me"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/auth/me</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-auth-me"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-auth-me"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-auth-me"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="admin-auth-POSTapi-admin-auth-logout">Revoke the current Sanctum token.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Send a Sanctum bearer token in the Authorization header.</p>

<span id="example-requests-POSTapi-admin-auth-logout">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/admin/auth/logout" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/auth/logout"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-admin-auth-logout">
</span>
<span id="execution-results-POSTapi-admin-auth-logout" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-admin-auth-logout"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-auth-logout"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-admin-auth-logout" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-auth-logout">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-admin-auth-logout" data-method="POST"
      data-path="api/admin/auth/logout"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-auth-logout', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-admin-auth-logout"
                    onclick="tryItOut('POSTapi-admin-auth-logout');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-admin-auth-logout"
                    onclick="cancelTryOut('POSTapi-admin-auth-logout');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-admin-auth-logout"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/admin/auth/logout</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-admin-auth-logout"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-admin-auth-logout"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-admin-auth-logout"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                <h1 id="alerts">Alerts</h1>

    <p>Admin alert endpoints. Requires the matching <code>alerts.*</code> permission listed on each endpoint.</p>

                                <h2 id="alerts-GETapi-admin-alerts">List alerts.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>alerts.view</code>.</p>

<span id="example-requests-GETapi-admin-alerts">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/alerts" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/alerts"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-alerts">
    </span>
<span id="execution-results-GETapi-admin-alerts" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-alerts"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-alerts"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-alerts" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-alerts">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-alerts" data-method="GET"
      data-path="api/admin/alerts"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-alerts', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-alerts"
                    onclick="tryItOut('GETapi-admin-alerts');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-alerts"
                    onclick="cancelTryOut('GETapi-admin-alerts');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-alerts"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/alerts</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-alerts"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-alerts"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-alerts"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="alerts-GETapi-admin-alerts-pending">List pending alerts.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>alerts.view</code>.</p>

<span id="example-requests-GETapi-admin-alerts-pending">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/alerts/pending" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/alerts/pending"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-alerts-pending">
    </span>
<span id="execution-results-GETapi-admin-alerts-pending" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-alerts-pending"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-alerts-pending"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-alerts-pending" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-alerts-pending">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-alerts-pending" data-method="GET"
      data-path="api/admin/alerts/pending"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-alerts-pending', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-alerts-pending"
                    onclick="tryItOut('GETapi-admin-alerts-pending');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-alerts-pending"
                    onclick="cancelTryOut('GETapi-admin-alerts-pending');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-alerts-pending"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/alerts/pending</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-alerts-pending"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-alerts-pending"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-alerts-pending"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="alerts-POSTapi-admin-alerts">Create an alert.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>alerts.create</code>.</p>

<span id="example-requests-POSTapi-admin-alerts">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/admin/alerts" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"vehicle_id\": 1,
    \"alert_type_slug\": \"maintenance_due\",
    \"alert_status_slug\": \"pending\",
    \"title\": \"Oil change due\",
    \"message\": \"Schedule oil change.\",
    \"due_date\": \"2026-07-01\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/alerts"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "vehicle_id": 1,
    "alert_type_slug": "maintenance_due",
    "alert_status_slug": "pending",
    "title": "Oil change due",
    "message": "Schedule oil change.",
    "due_date": "2026-07-01"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-admin-alerts">
</span>
<span id="execution-results-POSTapi-admin-alerts" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-admin-alerts"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-alerts"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-admin-alerts" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-alerts">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-admin-alerts" data-method="POST"
      data-path="api/admin/alerts"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-alerts', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-admin-alerts"
                    onclick="tryItOut('POSTapi-admin-alerts');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-admin-alerts"
                    onclick="cancelTryOut('POSTapi-admin-alerts');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-admin-alerts"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/admin/alerts</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-admin-alerts"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-admin-alerts"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-admin-alerts"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>vehicle_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="vehicle_id"                data-endpoint="POSTapi-admin-alerts"
               value="1"
               data-component="body">
    <br>
<p>optional Vehicle ID. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>alert_type_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="alert_type_slug"                data-endpoint="POSTapi-admin-alerts"
               value="maintenance_due"
               data-component="body">
    <br>
<p>Alert type slug. Example: <code>maintenance_due</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>alert_status_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="alert_status_slug"                data-endpoint="POSTapi-admin-alerts"
               value="pending"
               data-component="body">
    <br>
<p>optional Alert status slug. Defaults to pending. Example: <code>pending</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>title</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="title"                data-endpoint="POSTapi-admin-alerts"
               value="Oil change due"
               data-component="body">
    <br>
<p>Alert title. Example: <code>Oil change due</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>message</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="message"                data-endpoint="POSTapi-admin-alerts"
               value="Schedule oil change."
               data-component="body">
    <br>
<p>optional Alert message. Example: <code>Schedule oil change.</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>due_date</code></b>&nbsp;&nbsp;
<small>date</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="due_date"                data-endpoint="POSTapi-admin-alerts"
               value="2026-07-01"
               data-component="body">
    <br>
<p>optional Alert due date. Example: <code>2026-07-01</code></p>
        </div>
        </form>

                    <h2 id="alerts-POSTapi-admin-alerts-generate">Generate maintenance and document expiry alerts.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>alerts.create</code>.</p>

<span id="example-requests-POSTapi-admin-alerts-generate">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/admin/alerts/generate" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/alerts/generate"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-admin-alerts-generate">
</span>
<span id="execution-results-POSTapi-admin-alerts-generate" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-admin-alerts-generate"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-alerts-generate"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-admin-alerts-generate" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-alerts-generate">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-admin-alerts-generate" data-method="POST"
      data-path="api/admin/alerts/generate"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-alerts-generate', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-admin-alerts-generate"
                    onclick="tryItOut('POSTapi-admin-alerts-generate');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-admin-alerts-generate"
                    onclick="cancelTryOut('POSTapi-admin-alerts-generate');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-admin-alerts-generate"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/admin/alerts/generate</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-admin-alerts-generate"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-admin-alerts-generate"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-admin-alerts-generate"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="alerts-GETapi-admin-alerts--alert_id-">Display an alert.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>alerts.view</code>.</p>

<span id="example-requests-GETapi-admin-alerts--alert_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/alerts/17" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/alerts/17"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-alerts--alert_id-">
    </span>
<span id="execution-results-GETapi-admin-alerts--alert_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-alerts--alert_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-alerts--alert_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-alerts--alert_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-alerts--alert_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-alerts--alert_id-" data-method="GET"
      data-path="api/admin/alerts/{alert_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-alerts--alert_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-alerts--alert_id-"
                    onclick="tryItOut('GETapi-admin-alerts--alert_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-alerts--alert_id-"
                    onclick="cancelTryOut('GETapi-admin-alerts--alert_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-alerts--alert_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/alerts/{alert_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-alerts--alert_id-"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-alerts--alert_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-alerts--alert_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>alert_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="alert_id"                data-endpoint="GETapi-admin-alerts--alert_id-"
               value="17"
               data-component="url">
    <br>
<p>The ID of the alert. Example: <code>17</code></p>
            </div>
                    </form>

                    <h2 id="alerts-PATCHapi-admin-alerts--alert_id--seen">Mark a pending alert as seen.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>alerts.update</code>.</p>

<span id="example-requests-PATCHapi-admin-alerts--alert_id--seen">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PATCH \
    "http://localhost/api/admin/alerts/17/seen" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/alerts/17/seen"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "PATCH",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PATCHapi-admin-alerts--alert_id--seen">
</span>
<span id="execution-results-PATCHapi-admin-alerts--alert_id--seen" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PATCHapi-admin-alerts--alert_id--seen"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PATCHapi-admin-alerts--alert_id--seen"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PATCHapi-admin-alerts--alert_id--seen" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PATCHapi-admin-alerts--alert_id--seen">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PATCHapi-admin-alerts--alert_id--seen" data-method="PATCH"
      data-path="api/admin/alerts/{alert_id}/seen"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PATCHapi-admin-alerts--alert_id--seen', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PATCHapi-admin-alerts--alert_id--seen"
                    onclick="tryItOut('PATCHapi-admin-alerts--alert_id--seen');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PATCHapi-admin-alerts--alert_id--seen"
                    onclick="cancelTryOut('PATCHapi-admin-alerts--alert_id--seen');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PATCHapi-admin-alerts--alert_id--seen"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/admin/alerts/{alert_id}/seen</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PATCHapi-admin-alerts--alert_id--seen"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PATCHapi-admin-alerts--alert_id--seen"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PATCHapi-admin-alerts--alert_id--seen"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>alert_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="alert_id"                data-endpoint="PATCHapi-admin-alerts--alert_id--seen"
               value="17"
               data-component="url">
    <br>
<p>The ID of the alert. Example: <code>17</code></p>
            </div>
                    </form>

                    <h2 id="alerts-PATCHapi-admin-alerts--alert_id--done">Mark a pending or seen alert as done.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>alerts.update</code>.</p>

<span id="example-requests-PATCHapi-admin-alerts--alert_id--done">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PATCH \
    "http://localhost/api/admin/alerts/17/done" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/alerts/17/done"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "PATCH",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PATCHapi-admin-alerts--alert_id--done">
</span>
<span id="execution-results-PATCHapi-admin-alerts--alert_id--done" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PATCHapi-admin-alerts--alert_id--done"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PATCHapi-admin-alerts--alert_id--done"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PATCHapi-admin-alerts--alert_id--done" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PATCHapi-admin-alerts--alert_id--done">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PATCHapi-admin-alerts--alert_id--done" data-method="PATCH"
      data-path="api/admin/alerts/{alert_id}/done"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PATCHapi-admin-alerts--alert_id--done', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PATCHapi-admin-alerts--alert_id--done"
                    onclick="tryItOut('PATCHapi-admin-alerts--alert_id--done');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PATCHapi-admin-alerts--alert_id--done"
                    onclick="cancelTryOut('PATCHapi-admin-alerts--alert_id--done');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PATCHapi-admin-alerts--alert_id--done"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/admin/alerts/{alert_id}/done</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PATCHapi-admin-alerts--alert_id--done"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PATCHapi-admin-alerts--alert_id--done"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PATCHapi-admin-alerts--alert_id--done"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>alert_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="alert_id"                data-endpoint="PATCHapi-admin-alerts--alert_id--done"
               value="17"
               data-component="url">
    <br>
<p>The ID of the alert. Example: <code>17</code></p>
            </div>
                    </form>

                    <h2 id="alerts-PATCHapi-admin-alerts--alert_id--ignore">Ignore a pending or seen alert.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>alerts.update</code>.</p>

<span id="example-requests-PATCHapi-admin-alerts--alert_id--ignore">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PATCH \
    "http://localhost/api/admin/alerts/17/ignore" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/alerts/17/ignore"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "PATCH",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PATCHapi-admin-alerts--alert_id--ignore">
</span>
<span id="execution-results-PATCHapi-admin-alerts--alert_id--ignore" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PATCHapi-admin-alerts--alert_id--ignore"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PATCHapi-admin-alerts--alert_id--ignore"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PATCHapi-admin-alerts--alert_id--ignore" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PATCHapi-admin-alerts--alert_id--ignore">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PATCHapi-admin-alerts--alert_id--ignore" data-method="PATCH"
      data-path="api/admin/alerts/{alert_id}/ignore"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PATCHapi-admin-alerts--alert_id--ignore', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PATCHapi-admin-alerts--alert_id--ignore"
                    onclick="tryItOut('PATCHapi-admin-alerts--alert_id--ignore');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PATCHapi-admin-alerts--alert_id--ignore"
                    onclick="cancelTryOut('PATCHapi-admin-alerts--alert_id--ignore');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PATCHapi-admin-alerts--alert_id--ignore"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/admin/alerts/{alert_id}/ignore</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PATCHapi-admin-alerts--alert_id--ignore"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PATCHapi-admin-alerts--alert_id--ignore"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PATCHapi-admin-alerts--alert_id--ignore"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>alert_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="alert_id"                data-endpoint="PATCHapi-admin-alerts--alert_id--ignore"
               value="17"
               data-component="url">
    <br>
<p>The ID of the alert. Example: <code>17</code></p>
            </div>
                    </form>

                <h1 id="contracts">Contracts</h1>

    <p>Admin contract generation, download, signing, and cancellation endpoints.</p>

                                <h2 id="contracts-POSTapi-admin-reservations--reservation_id--contract-generate">Generate or regenerate a reservation contract.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>contracts.generate</code>.</p>

<span id="example-requests-POSTapi-admin-reservations--reservation_id--contract-generate">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/admin/reservations/17/contract/generate" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/reservations/17/contract/generate"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-admin-reservations--reservation_id--contract-generate">
</span>
<span id="execution-results-POSTapi-admin-reservations--reservation_id--contract-generate" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-admin-reservations--reservation_id--contract-generate"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-reservations--reservation_id--contract-generate"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-admin-reservations--reservation_id--contract-generate" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-reservations--reservation_id--contract-generate">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-admin-reservations--reservation_id--contract-generate" data-method="POST"
      data-path="api/admin/reservations/{reservation_id}/contract/generate"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-reservations--reservation_id--contract-generate', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-admin-reservations--reservation_id--contract-generate"
                    onclick="tryItOut('POSTapi-admin-reservations--reservation_id--contract-generate');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-admin-reservations--reservation_id--contract-generate"
                    onclick="cancelTryOut('POSTapi-admin-reservations--reservation_id--contract-generate');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-admin-reservations--reservation_id--contract-generate"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/admin/reservations/{reservation_id}/contract/generate</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-admin-reservations--reservation_id--contract-generate"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-admin-reservations--reservation_id--contract-generate"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-admin-reservations--reservation_id--contract-generate"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>reservation_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="reservation_id"                data-endpoint="POSTapi-admin-reservations--reservation_id--contract-generate"
               value="17"
               data-component="url">
    <br>
<p>The ID of the reservation. Example: <code>17</code></p>
            </div>
                    </form>

                    <h2 id="contracts-GETapi-admin-reservations--reservation_id--contract">Display the reservation contract.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>contracts.view</code>.</p>

<span id="example-requests-GETapi-admin-reservations--reservation_id--contract">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/reservations/17/contract" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/reservations/17/contract"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-reservations--reservation_id--contract">
    </span>
<span id="execution-results-GETapi-admin-reservations--reservation_id--contract" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-reservations--reservation_id--contract"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-reservations--reservation_id--contract"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-reservations--reservation_id--contract" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-reservations--reservation_id--contract">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-reservations--reservation_id--contract" data-method="GET"
      data-path="api/admin/reservations/{reservation_id}/contract"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-reservations--reservation_id--contract', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-reservations--reservation_id--contract"
                    onclick="tryItOut('GETapi-admin-reservations--reservation_id--contract');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-reservations--reservation_id--contract"
                    onclick="cancelTryOut('GETapi-admin-reservations--reservation_id--contract');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-reservations--reservation_id--contract"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/reservations/{reservation_id}/contract</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-reservations--reservation_id--contract"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-reservations--reservation_id--contract"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-reservations--reservation_id--contract"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>reservation_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="reservation_id"                data-endpoint="GETapi-admin-reservations--reservation_id--contract"
               value="17"
               data-component="url">
    <br>
<p>The ID of the reservation. Example: <code>17</code></p>
            </div>
                    </form>

                    <h2 id="contracts-GETapi-admin-contracts--contract_id--download">Download the generated contract PDF from private storage.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>contracts.view</code>.</p>

<span id="example-requests-GETapi-admin-contracts--contract_id--download">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/contracts/17/download" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/contracts/17/download"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-contracts--contract_id--download">
    </span>
<span id="execution-results-GETapi-admin-contracts--contract_id--download" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-contracts--contract_id--download"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-contracts--contract_id--download"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-contracts--contract_id--download" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-contracts--contract_id--download">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-contracts--contract_id--download" data-method="GET"
      data-path="api/admin/contracts/{contract_id}/download"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-contracts--contract_id--download', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-contracts--contract_id--download"
                    onclick="tryItOut('GETapi-admin-contracts--contract_id--download');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-contracts--contract_id--download"
                    onclick="cancelTryOut('GETapi-admin-contracts--contract_id--download');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-contracts--contract_id--download"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/contracts/{contract_id}/download</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-contracts--contract_id--download"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-contracts--contract_id--download"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-contracts--contract_id--download"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>contract_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="contract_id"                data-endpoint="GETapi-admin-contracts--contract_id--download"
               value="17"
               data-component="url">
    <br>
<p>The ID of the contract. Example: <code>17</code></p>
            </div>
                    </form>

                    <h2 id="contracts-POSTapi-admin-contracts--contract_id--signed">Upload a signed PDF or mark the contract as signed.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>contracts.update</code>.</p>

<span id="example-requests-POSTapi-admin-contracts--contract_id--signed">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/admin/contracts/17/signed" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "signed_pdf=@C:\Users\dell\AppData\Local\Temp\php7017.tmp" </code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/contracts/17/signed"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('signed_pdf', document.querySelector('input[name="signed_pdf"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-admin-contracts--contract_id--signed">
</span>
<span id="execution-results-POSTapi-admin-contracts--contract_id--signed" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-admin-contracts--contract_id--signed"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-contracts--contract_id--signed"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-admin-contracts--contract_id--signed" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-contracts--contract_id--signed">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-admin-contracts--contract_id--signed" data-method="POST"
      data-path="api/admin/contracts/{contract_id}/signed"
      data-authed="1"
      data-hasfiles="1"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-contracts--contract_id--signed', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-admin-contracts--contract_id--signed"
                    onclick="tryItOut('POSTapi-admin-contracts--contract_id--signed');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-admin-contracts--contract_id--signed"
                    onclick="cancelTryOut('POSTapi-admin-contracts--contract_id--signed');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-admin-contracts--contract_id--signed"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/admin/contracts/{contract_id}/signed</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-admin-contracts--contract_id--signed"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-admin-contracts--contract_id--signed"
               value="multipart/form-data"
               data-component="header">
    <br>
<p>Example: <code>multipart/form-data</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-admin-contracts--contract_id--signed"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>contract_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="contract_id"                data-endpoint="POSTapi-admin-contracts--contract_id--signed"
               value="17"
               data-component="url">
    <br>
<p>The ID of the contract. Example: <code>17</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>signed_pdf</code></b>&nbsp;&nbsp;
<small>file</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="file" style="display: none"
                              name="signed_pdf"                data-endpoint="POSTapi-admin-contracts--contract_id--signed"
               value=""
               data-component="body">
    <br>
<p>optional Signed PDF file. If omitted, the contract is marked signed without upload. Example: <code>C:\Users\dell\AppData\Local\Temp\php7017.tmp</code></p>
        </div>
        </form>

                    <h2 id="contracts-POSTapi-admin-contracts--contract_id--cancel">Mark the contract as cancelled.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>contracts.update</code>.</p>

<span id="example-requests-POSTapi-admin-contracts--contract_id--cancel">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/admin/contracts/17/cancel" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/contracts/17/cancel"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-admin-contracts--contract_id--cancel">
</span>
<span id="execution-results-POSTapi-admin-contracts--contract_id--cancel" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-admin-contracts--contract_id--cancel"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-contracts--contract_id--cancel"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-admin-contracts--contract_id--cancel" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-contracts--contract_id--cancel">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-admin-contracts--contract_id--cancel" data-method="POST"
      data-path="api/admin/contracts/{contract_id}/cancel"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-contracts--contract_id--cancel', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-admin-contracts--contract_id--cancel"
                    onclick="tryItOut('POSTapi-admin-contracts--contract_id--cancel');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-admin-contracts--contract_id--cancel"
                    onclick="cancelTryOut('POSTapi-admin-contracts--contract_id--cancel');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-admin-contracts--contract_id--cancel"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/admin/contracts/{contract_id}/cancel</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-admin-contracts--contract_id--cancel"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-admin-contracts--contract_id--cancel"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-admin-contracts--contract_id--cancel"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>contract_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="contract_id"                data-endpoint="POSTapi-admin-contracts--contract_id--cancel"
               value="17"
               data-component="url">
    <br>
<p>The ID of the contract. Example: <code>17</code></p>
            </div>
                    </form>

                <h1 id="customers">Customers</h1>

    <p>Admin customer profile and customer document endpoints. Requires the matching <code>customers.*</code> permission listed on each endpoint.</p>

                                <h2 id="customers-GETapi-admin-customers">List customers for the admin dashboard.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>customers.view</code>.</p>

<span id="example-requests-GETapi-admin-customers">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/customers" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/customers"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-customers">
    </span>
<span id="execution-results-GETapi-admin-customers" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-customers"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-customers"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-customers" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-customers">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-customers" data-method="GET"
      data-path="api/admin/customers"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-customers', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-customers"
                    onclick="tryItOut('GETapi-admin-customers');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-customers"
                    onclick="cancelTryOut('GETapi-admin-customers');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-customers"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/customers</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-customers"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-customers"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-customers"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="customers-POSTapi-admin-customers">Store a new customer.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>customers.create</code>.</p>

<span id="example-requests-POSTapi-admin-customers">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/admin/customers" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"full_name\": \"Ahmed Dakhla\",
    \"nationality\": \"Moroccan\",
    \"phone\": \"+212600000000\",
    \"email\": \"customer@example.com\",
    \"passport_or_cin\": \"AA123456\",
    \"driving_license_number\": \"DL-2026-001\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/customers"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "full_name": "Ahmed Dakhla",
    "nationality": "Moroccan",
    "phone": "+212600000000",
    "email": "customer@example.com",
    "passport_or_cin": "AA123456",
    "driving_license_number": "DL-2026-001"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-admin-customers">
</span>
<span id="execution-results-POSTapi-admin-customers" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-admin-customers"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-customers"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-admin-customers" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-customers">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-admin-customers" data-method="POST"
      data-path="api/admin/customers"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-customers', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-admin-customers"
                    onclick="tryItOut('POSTapi-admin-customers');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-admin-customers"
                    onclick="cancelTryOut('POSTapi-admin-customers');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-admin-customers"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/admin/customers</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-admin-customers"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-admin-customers"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-admin-customers"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>full_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="full_name"                data-endpoint="POSTapi-admin-customers"
               value="Ahmed Dakhla"
               data-component="body">
    <br>
<p>Customer full name. Example: <code>Ahmed Dakhla</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>nationality</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="nationality"                data-endpoint="POSTapi-admin-customers"
               value="Moroccan"
               data-component="body">
    <br>
<p>Customer nationality. Example: <code>Moroccan</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>phone</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="phone"                data-endpoint="POSTapi-admin-customers"
               value="+212600000000"
               data-component="body">
    <br>
<p>Customer phone number. Example: <code>+212600000000</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-admin-customers"
               value="customer@example.com"
               data-component="body">
    <br>
<p>optional Customer email. Example: <code>customer@example.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>passport_or_cin</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="passport_or_cin"                data-endpoint="POSTapi-admin-customers"
               value="AA123456"
               data-component="body">
    <br>
<p>optional Passport or CIN. Example: <code>AA123456</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>driving_license_number</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="driving_license_number"                data-endpoint="POSTapi-admin-customers"
               value="DL-2026-001"
               data-component="body">
    <br>
<p>optional Driving license number. Example: <code>DL-2026-001</code></p>
        </div>
        </form>

                    <h2 id="customers-GETapi-admin-customers--customer_id-">Display a customer.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>customers.view</code>.</p>

<span id="example-requests-GETapi-admin-customers--customer_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/customers/17" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/customers/17"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-customers--customer_id-">
    </span>
<span id="execution-results-GETapi-admin-customers--customer_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-customers--customer_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-customers--customer_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-customers--customer_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-customers--customer_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-customers--customer_id-" data-method="GET"
      data-path="api/admin/customers/{customer_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-customers--customer_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-customers--customer_id-"
                    onclick="tryItOut('GETapi-admin-customers--customer_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-customers--customer_id-"
                    onclick="cancelTryOut('GETapi-admin-customers--customer_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-customers--customer_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/customers/{customer_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-customers--customer_id-"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-customers--customer_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-customers--customer_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>customer_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="customer_id"                data-endpoint="GETapi-admin-customers--customer_id-"
               value="17"
               data-component="url">
    <br>
<p>The ID of the customer. Example: <code>17</code></p>
            </div>
                    </form>

                    <h2 id="customers-PUTapi-admin-customers--customer_id-">Update a customer.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>customers.update</code>.</p>

<span id="example-requests-PUTapi-admin-customers--customer_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost/api/admin/customers/17" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"full_name\": \"vmqeopfuudtdsufvyvddq\",
    \"nationality\": \"amniihfqcoynlazghdtqt\",
    \"phone\": \"+212611111111\",
    \"email\": \"updated@example.com\",
    \"passport_or_cin\": \"smsjuryvojcybzvrbyick\",
    \"driving_license_number\": \"DL-2026-002\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/customers/17"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "full_name": "vmqeopfuudtdsufvyvddq",
    "nationality": "amniihfqcoynlazghdtqt",
    "phone": "+212611111111",
    "email": "updated@example.com",
    "passport_or_cin": "smsjuryvojcybzvrbyick",
    "driving_license_number": "DL-2026-002"
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-admin-customers--customer_id-">
</span>
<span id="execution-results-PUTapi-admin-customers--customer_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-admin-customers--customer_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-admin-customers--customer_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-admin-customers--customer_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-admin-customers--customer_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-admin-customers--customer_id-" data-method="PUT"
      data-path="api/admin/customers/{customer_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-admin-customers--customer_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-admin-customers--customer_id-"
                    onclick="tryItOut('PUTapi-admin-customers--customer_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-admin-customers--customer_id-"
                    onclick="cancelTryOut('PUTapi-admin-customers--customer_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-admin-customers--customer_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/admin/customers/{customer_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-admin-customers--customer_id-"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-admin-customers--customer_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-admin-customers--customer_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>customer_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="customer_id"                data-endpoint="PUTapi-admin-customers--customer_id-"
               value="17"
               data-component="url">
    <br>
<p>The ID of the customer. Example: <code>17</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>full_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="full_name"                data-endpoint="PUTapi-admin-customers--customer_id-"
               value="vmqeopfuudtdsufvyvddq"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>vmqeopfuudtdsufvyvddq</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>nationality</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="nationality"                data-endpoint="PUTapi-admin-customers--customer_id-"
               value="amniihfqcoynlazghdtqt"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>amniihfqcoynlazghdtqt</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>phone</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="phone"                data-endpoint="PUTapi-admin-customers--customer_id-"
               value="+212611111111"
               data-component="body">
    <br>
<p>optional Customer phone number. Example: <code>+212611111111</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="PUTapi-admin-customers--customer_id-"
               value="updated@example.com"
               data-component="body">
    <br>
<p>optional Customer email. Example: <code>updated@example.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>passport_or_cin</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="passport_or_cin"                data-endpoint="PUTapi-admin-customers--customer_id-"
               value="smsjuryvojcybzvrbyick"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>smsjuryvojcybzvrbyick</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>driving_license_number</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="driving_license_number"                data-endpoint="PUTapi-admin-customers--customer_id-"
               value="DL-2026-002"
               data-component="body">
    <br>
<p>optional Driving license number. Example: <code>DL-2026-002</code></p>
        </div>
        </form>

                    <h2 id="customers-PATCHapi-admin-customers--customer_id-">Update a customer.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>customers.update</code>.</p>

<span id="example-requests-PATCHapi-admin-customers--customer_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PATCH \
    "http://localhost/api/admin/customers/17" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"full_name\": \"vmqeopfuudtdsufvyvddq\",
    \"nationality\": \"amniihfqcoynlazghdtqt\",
    \"phone\": \"+212611111111\",
    \"email\": \"updated@example.com\",
    \"passport_or_cin\": \"smsjuryvojcybzvrbyick\",
    \"driving_license_number\": \"DL-2026-002\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/customers/17"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "full_name": "vmqeopfuudtdsufvyvddq",
    "nationality": "amniihfqcoynlazghdtqt",
    "phone": "+212611111111",
    "email": "updated@example.com",
    "passport_or_cin": "smsjuryvojcybzvrbyick",
    "driving_license_number": "DL-2026-002"
};

fetch(url, {
    method: "PATCH",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PATCHapi-admin-customers--customer_id-">
</span>
<span id="execution-results-PATCHapi-admin-customers--customer_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PATCHapi-admin-customers--customer_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PATCHapi-admin-customers--customer_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PATCHapi-admin-customers--customer_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PATCHapi-admin-customers--customer_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PATCHapi-admin-customers--customer_id-" data-method="PATCH"
      data-path="api/admin/customers/{customer_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PATCHapi-admin-customers--customer_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PATCHapi-admin-customers--customer_id-"
                    onclick="tryItOut('PATCHapi-admin-customers--customer_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PATCHapi-admin-customers--customer_id-"
                    onclick="cancelTryOut('PATCHapi-admin-customers--customer_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PATCHapi-admin-customers--customer_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/admin/customers/{customer_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PATCHapi-admin-customers--customer_id-"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PATCHapi-admin-customers--customer_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PATCHapi-admin-customers--customer_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>customer_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="customer_id"                data-endpoint="PATCHapi-admin-customers--customer_id-"
               value="17"
               data-component="url">
    <br>
<p>The ID of the customer. Example: <code>17</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>full_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="full_name"                data-endpoint="PATCHapi-admin-customers--customer_id-"
               value="vmqeopfuudtdsufvyvddq"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>vmqeopfuudtdsufvyvddq</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>nationality</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="nationality"                data-endpoint="PATCHapi-admin-customers--customer_id-"
               value="amniihfqcoynlazghdtqt"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>amniihfqcoynlazghdtqt</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>phone</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="phone"                data-endpoint="PATCHapi-admin-customers--customer_id-"
               value="+212611111111"
               data-component="body">
    <br>
<p>optional Customer phone number. Example: <code>+212611111111</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="PATCHapi-admin-customers--customer_id-"
               value="updated@example.com"
               data-component="body">
    <br>
<p>optional Customer email. Example: <code>updated@example.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>passport_or_cin</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="passport_or_cin"                data-endpoint="PATCHapi-admin-customers--customer_id-"
               value="smsjuryvojcybzvrbyick"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>smsjuryvojcybzvrbyick</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>driving_license_number</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="driving_license_number"                data-endpoint="PATCHapi-admin-customers--customer_id-"
               value="DL-2026-002"
               data-component="body">
    <br>
<p>optional Driving license number. Example: <code>DL-2026-002</code></p>
        </div>
        </form>

                    <h2 id="customers-DELETEapi-admin-customers--customer_id-">Soft delete a customer.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>customers.delete</code>.</p>

<span id="example-requests-DELETEapi-admin-customers--customer_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost/api/admin/customers/17" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/customers/17"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-admin-customers--customer_id-">
</span>
<span id="execution-results-DELETEapi-admin-customers--customer_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-admin-customers--customer_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-admin-customers--customer_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-admin-customers--customer_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-admin-customers--customer_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-admin-customers--customer_id-" data-method="DELETE"
      data-path="api/admin/customers/{customer_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-admin-customers--customer_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-admin-customers--customer_id-"
                    onclick="tryItOut('DELETEapi-admin-customers--customer_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-admin-customers--customer_id-"
                    onclick="cancelTryOut('DELETEapi-admin-customers--customer_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-admin-customers--customer_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/admin/customers/{customer_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="DELETEapi-admin-customers--customer_id-"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-admin-customers--customer_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-admin-customers--customer_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>customer_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="customer_id"                data-endpoint="DELETEapi-admin-customers--customer_id-"
               value="17"
               data-component="url">
    <br>
<p>The ID of the customer. Example: <code>17</code></p>
            </div>
                    </form>

                    <h2 id="customers-POSTapi-admin-customers--customer_id--documents">Upload a customer document and link it to a document type lookup.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>customers.update</code>.</p>

<span id="example-requests-POSTapi-admin-customers--customer_id--documents">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/admin/customers/17/documents" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "document_type_slug=passport"\
    --form "title=Passport scan"\
    --form "expires_at=2028-12-31"\
    --form "file=@C:\Users\dell\AppData\Local\Temp\php6F29.tmp" </code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/customers/17/documents"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('document_type_slug', 'passport');
body.append('title', 'Passport scan');
body.append('expires_at', '2028-12-31');
body.append('file', document.querySelector('input[name="file"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-admin-customers--customer_id--documents">
</span>
<span id="execution-results-POSTapi-admin-customers--customer_id--documents" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-admin-customers--customer_id--documents"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-customers--customer_id--documents"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-admin-customers--customer_id--documents" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-customers--customer_id--documents">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-admin-customers--customer_id--documents" data-method="POST"
      data-path="api/admin/customers/{customer_id}/documents"
      data-authed="1"
      data-hasfiles="1"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-customers--customer_id--documents', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-admin-customers--customer_id--documents"
                    onclick="tryItOut('POSTapi-admin-customers--customer_id--documents');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-admin-customers--customer_id--documents"
                    onclick="cancelTryOut('POSTapi-admin-customers--customer_id--documents');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-admin-customers--customer_id--documents"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/admin/customers/{customer_id}/documents</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-admin-customers--customer_id--documents"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-admin-customers--customer_id--documents"
               value="multipart/form-data"
               data-component="header">
    <br>
<p>Example: <code>multipart/form-data</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-admin-customers--customer_id--documents"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>customer_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="customer_id"                data-endpoint="POSTapi-admin-customers--customer_id--documents"
               value="17"
               data-component="url">
    <br>
<p>The ID of the customer. Example: <code>17</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>document_type_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="document_type_slug"                data-endpoint="POSTapi-admin-customers--customer_id--documents"
               value="passport"
               data-component="body">
    <br>
<p>Document type slug. Example: <code>passport</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>title</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="title"                data-endpoint="POSTapi-admin-customers--customer_id--documents"
               value="Passport scan"
               data-component="body">
    <br>
<p>optional Document title. Example: <code>Passport scan</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>file</code></b>&nbsp;&nbsp;
<small>file</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="file" style="display: none"
                              name="file"                data-endpoint="POSTapi-admin-customers--customer_id--documents"
               value=""
               data-component="body">
    <br>
<p>PDF or image document file. Example: <code>C:\Users\dell\AppData\Local\Temp\php6F29.tmp</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>expires_at</code></b>&nbsp;&nbsp;
<small>date</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="expires_at"                data-endpoint="POSTapi-admin-customers--customer_id--documents"
               value="2028-12-31"
               data-component="body">
    <br>
<p>optional Expiry date. Example: <code>2028-12-31</code></p>
        </div>
        </form>

                    <h2 id="customers-DELETEapi-admin-customer-documents--document_id-">Delete a stored customer document file and soft delete the record.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>customers.update</code>.</p>

<span id="example-requests-DELETEapi-admin-customer-documents--document_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost/api/admin/customer-documents/17" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/customer-documents/17"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-admin-customer-documents--document_id-">
</span>
<span id="execution-results-DELETEapi-admin-customer-documents--document_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-admin-customer-documents--document_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-admin-customer-documents--document_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-admin-customer-documents--document_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-admin-customer-documents--document_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-admin-customer-documents--document_id-" data-method="DELETE"
      data-path="api/admin/customer-documents/{document_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-admin-customer-documents--document_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-admin-customer-documents--document_id-"
                    onclick="tryItOut('DELETEapi-admin-customer-documents--document_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-admin-customer-documents--document_id-"
                    onclick="cancelTryOut('DELETEapi-admin-customer-documents--document_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-admin-customer-documents--document_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/admin/customer-documents/{document_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="DELETEapi-admin-customer-documents--document_id-"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-admin-customer-documents--document_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-admin-customer-documents--document_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>document_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="document_id"                data-endpoint="DELETEapi-admin-customer-documents--document_id-"
               value="17"
               data-component="url">
    <br>
<p>The ID of the document. Example: <code>17</code></p>
            </div>
                    </form>

                <h1 id="dashboard">Dashboard</h1>

    <p>Admin dashboard statistics and reporting endpoints. Requires <code>dashboard.view</code>.</p>

                                <h2 id="dashboard-GETapi-admin-dashboard-statistics">Get global dashboard KPIs.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>dashboard.view</code>.</p>

<span id="example-requests-GETapi-admin-dashboard-statistics">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/dashboard/statistics?year=2026&amp;month=6" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"year\": 21,
    \"month\": 6
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/dashboard/statistics"
);

const params = {
    "year": "2026",
    "month": "6",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "year": 21,
    "month": 6
};

fetch(url, {
    method: "GET",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-dashboard-statistics">
    </span>
<span id="execution-results-GETapi-admin-dashboard-statistics" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-dashboard-statistics"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-dashboard-statistics"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-dashboard-statistics" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-dashboard-statistics">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-dashboard-statistics" data-method="GET"
      data-path="api/admin/dashboard/statistics"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-dashboard-statistics', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-dashboard-statistics"
                    onclick="tryItOut('GETapi-admin-dashboard-statistics');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-dashboard-statistics"
                    onclick="cancelTryOut('GETapi-admin-dashboard-statistics');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-dashboard-statistics"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/dashboard/statistics</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-dashboard-statistics"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-dashboard-statistics"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-dashboard-statistics"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>year</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="year"                data-endpoint="GETapi-admin-dashboard-statistics"
               value="2026"
               data-component="query">
    <br>
<p>optional KPI year. Example: <code>2026</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>month</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="month"                data-endpoint="GETapi-admin-dashboard-statistics"
               value="6"
               data-component="query">
    <br>
<p>optional KPI month. Example: <code>6</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>year</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="year"                data-endpoint="GETapi-admin-dashboard-statistics"
               value="21"
               data-component="body">
    <br>
<p>Must be at least 2000. Must not be greater than 2100. Example: <code>21</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>month</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="month"                data-endpoint="GETapi-admin-dashboard-statistics"
               value="6"
               data-component="body">
    <br>
<p>Must be at least 1. Must not be greater than 12. Example: <code>6</code></p>
        </div>
        </form>

                    <h2 id="dashboard-GETapi-admin-dashboard-revenue">Get revenue reporting totals.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>dashboard.view</code>.</p>

<span id="example-requests-GETapi-admin-dashboard-revenue">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/dashboard/revenue?start_date=2026-06-01&amp;end_date=2026-06-30&amp;group_by=day" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/dashboard/revenue"
);

const params = {
    "start_date": "2026-06-01",
    "end_date": "2026-06-30",
    "group_by": "day",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-dashboard-revenue">
    </span>
<span id="execution-results-GETapi-admin-dashboard-revenue" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-dashboard-revenue"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-dashboard-revenue"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-dashboard-revenue" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-dashboard-revenue">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-dashboard-revenue" data-method="GET"
      data-path="api/admin/dashboard/revenue"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-dashboard-revenue', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-dashboard-revenue"
                    onclick="tryItOut('GETapi-admin-dashboard-revenue');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-dashboard-revenue"
                    onclick="cancelTryOut('GETapi-admin-dashboard-revenue');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-dashboard-revenue"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/dashboard/revenue</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-dashboard-revenue"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-dashboard-revenue"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-dashboard-revenue"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>start_date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="start_date"                data-endpoint="GETapi-admin-dashboard-revenue"
               value="2026-06-01"
               data-component="query">
    <br>
<p>date optional Start date. Example: <code>2026-06-01</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>end_date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="end_date"                data-endpoint="GETapi-admin-dashboard-revenue"
               value="2026-06-30"
               data-component="query">
    <br>
<p>date optional End date. Example: <code>2026-06-30</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>group_by</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="group_by"                data-endpoint="GETapi-admin-dashboard-revenue"
               value="day"
               data-component="query">
    <br>
<p>optional Group revenue by day or month. Example: <code>day</code></p>
            </div>
                </form>

                    <h2 id="dashboard-GETapi-admin-dashboard-expenses">Get expense reporting totals.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>dashboard.view</code>.</p>

<span id="example-requests-GETapi-admin-dashboard-expenses">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/dashboard/expenses?start_date=2026-06-01&amp;end_date=2026-06-30&amp;group_by=month" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/dashboard/expenses"
);

const params = {
    "start_date": "2026-06-01",
    "end_date": "2026-06-30",
    "group_by": "month",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-dashboard-expenses">
    </span>
<span id="execution-results-GETapi-admin-dashboard-expenses" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-dashboard-expenses"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-dashboard-expenses"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-dashboard-expenses" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-dashboard-expenses">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-dashboard-expenses" data-method="GET"
      data-path="api/admin/dashboard/expenses"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-dashboard-expenses', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-dashboard-expenses"
                    onclick="tryItOut('GETapi-admin-dashboard-expenses');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-dashboard-expenses"
                    onclick="cancelTryOut('GETapi-admin-dashboard-expenses');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-dashboard-expenses"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/dashboard/expenses</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-dashboard-expenses"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-dashboard-expenses"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-dashboard-expenses"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>start_date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="start_date"                data-endpoint="GETapi-admin-dashboard-expenses"
               value="2026-06-01"
               data-component="query">
    <br>
<p>date optional Start date. Example: <code>2026-06-01</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>end_date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="end_date"                data-endpoint="GETapi-admin-dashboard-expenses"
               value="2026-06-30"
               data-component="query">
    <br>
<p>date optional End date. Example: <code>2026-06-30</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>group_by</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="group_by"                data-endpoint="GETapi-admin-dashboard-expenses"
               value="month"
               data-component="query">
    <br>
<p>optional Group expenses by day or month. Example: <code>month</code></p>
            </div>
                </form>

                <h1 id="expenses">Expenses</h1>

    <p>Admin expense endpoints. Requires the matching <code>expenses.*</code> permission listed on each endpoint.</p>

                                <h2 id="expenses-GETapi-admin-vehicles--vehicle_id--expenses">List expenses for one vehicle.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>expenses.view</code>.</p>

<span id="example-requests-GETapi-admin-vehicles--vehicle_id--expenses">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/vehicles/17/expenses" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/vehicles/17/expenses"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-vehicles--vehicle_id--expenses">
    </span>
<span id="execution-results-GETapi-admin-vehicles--vehicle_id--expenses" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-vehicles--vehicle_id--expenses"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-vehicles--vehicle_id--expenses"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-vehicles--vehicle_id--expenses" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-vehicles--vehicle_id--expenses">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-vehicles--vehicle_id--expenses" data-method="GET"
      data-path="api/admin/vehicles/{vehicle_id}/expenses"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-vehicles--vehicle_id--expenses', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-vehicles--vehicle_id--expenses"
                    onclick="tryItOut('GETapi-admin-vehicles--vehicle_id--expenses');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-vehicles--vehicle_id--expenses"
                    onclick="cancelTryOut('GETapi-admin-vehicles--vehicle_id--expenses');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-vehicles--vehicle_id--expenses"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/vehicles/{vehicle_id}/expenses</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-vehicles--vehicle_id--expenses"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-vehicles--vehicle_id--expenses"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-vehicles--vehicle_id--expenses"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>vehicle_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="vehicle_id"                data-endpoint="GETapi-admin-vehicles--vehicle_id--expenses"
               value="17"
               data-component="url">
    <br>
<p>The ID of the vehicle. Example: <code>17</code></p>
            </div>
                    </form>

                    <h2 id="expenses-GETapi-admin-expenses">List expenses.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>expenses.view</code>.</p>

<span id="example-requests-GETapi-admin-expenses">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/expenses" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/expenses"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-expenses">
    </span>
<span id="execution-results-GETapi-admin-expenses" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-expenses"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-expenses"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-expenses" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-expenses">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-expenses" data-method="GET"
      data-path="api/admin/expenses"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-expenses', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-expenses"
                    onclick="tryItOut('GETapi-admin-expenses');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-expenses"
                    onclick="cancelTryOut('GETapi-admin-expenses');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-expenses"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/expenses</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-expenses"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-expenses"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-expenses"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="expenses-POSTapi-admin-expenses">Create an expense.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>expenses.create</code>.</p>

<span id="example-requests-POSTapi-admin-expenses">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/admin/expenses" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "vehicle_id=1"\
    --form "expense_category_slug=fuel"\
    --form "amount=200"\
    --form "expense_date=2026-06-10"\
    --form "description=Diesel refill."\
    --form "invoice=@C:\Users\dell\AppData\Local\Temp\php7057.tmp" </code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/expenses"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('vehicle_id', '1');
body.append('expense_category_slug', 'fuel');
body.append('amount', '200');
body.append('expense_date', '2026-06-10');
body.append('description', 'Diesel refill.');
body.append('invoice', document.querySelector('input[name="invoice"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-admin-expenses">
</span>
<span id="execution-results-POSTapi-admin-expenses" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-admin-expenses"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-expenses"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-admin-expenses" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-expenses">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-admin-expenses" data-method="POST"
      data-path="api/admin/expenses"
      data-authed="1"
      data-hasfiles="1"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-expenses', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-admin-expenses"
                    onclick="tryItOut('POSTapi-admin-expenses');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-admin-expenses"
                    onclick="cancelTryOut('POSTapi-admin-expenses');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-admin-expenses"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/admin/expenses</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-admin-expenses"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-admin-expenses"
               value="multipart/form-data"
               data-component="header">
    <br>
<p>Example: <code>multipart/form-data</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-admin-expenses"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>vehicle_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="vehicle_id"                data-endpoint="POSTapi-admin-expenses"
               value="1"
               data-component="body">
    <br>
<p>optional Vehicle ID. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>expense_category_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="expense_category_slug"                data-endpoint="POSTapi-admin-expenses"
               value="fuel"
               data-component="body">
    <br>
<p>Expense category slug. Example: <code>fuel</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>amount</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="amount"                data-endpoint="POSTapi-admin-expenses"
               value="200"
               data-component="body">
    <br>
<p>Expense amount. Example: <code>200</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>expense_date</code></b>&nbsp;&nbsp;
<small>date</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="expense_date"                data-endpoint="POSTapi-admin-expenses"
               value="2026-06-10"
               data-component="body">
    <br>
<p>Expense date. Example: <code>2026-06-10</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="POSTapi-admin-expenses"
               value="Diesel refill."
               data-component="body">
    <br>
<p>optional Expense description. Example: <code>Diesel refill.</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>invoice</code></b>&nbsp;&nbsp;
<small>file</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="file" style="display: none"
                              name="invoice"                data-endpoint="POSTapi-admin-expenses"
               value=""
               data-component="body">
    <br>
<p>optional PDF or image invoice file. Example: <code>C:\Users\dell\AppData\Local\Temp\php7057.tmp</code></p>
        </div>
        </form>

                    <h2 id="expenses-GETapi-admin-expenses-monthly-summary">Get monthly expense totals by category.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>expenses.view</code>.</p>

<span id="example-requests-GETapi-admin-expenses-monthly-summary">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/expenses/monthly-summary?year=2026&amp;month=6" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"year\": 21,
    \"month\": 6
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/expenses/monthly-summary"
);

const params = {
    "year": "2026",
    "month": "6",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "year": 21,
    "month": 6
};

fetch(url, {
    method: "GET",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-expenses-monthly-summary">
    </span>
<span id="execution-results-GETapi-admin-expenses-monthly-summary" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-expenses-monthly-summary"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-expenses-monthly-summary"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-expenses-monthly-summary" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-expenses-monthly-summary">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-expenses-monthly-summary" data-method="GET"
      data-path="api/admin/expenses/monthly-summary"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-expenses-monthly-summary', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-expenses-monthly-summary"
                    onclick="tryItOut('GETapi-admin-expenses-monthly-summary');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-expenses-monthly-summary"
                    onclick="cancelTryOut('GETapi-admin-expenses-monthly-summary');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-expenses-monthly-summary"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/expenses/monthly-summary</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-expenses-monthly-summary"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-expenses-monthly-summary"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-expenses-monthly-summary"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>year</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="year"                data-endpoint="GETapi-admin-expenses-monthly-summary"
               value="2026"
               data-component="query">
    <br>
<p>optional Year to summarize. Example: <code>2026</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>month</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="month"                data-endpoint="GETapi-admin-expenses-monthly-summary"
               value="6"
               data-component="query">
    <br>
<p>optional Month to summarize. Example: <code>6</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>year</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="year"                data-endpoint="GETapi-admin-expenses-monthly-summary"
               value="21"
               data-component="body">
    <br>
<p>Must be at least 2000. Must not be greater than 2100. Example: <code>21</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>month</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="month"                data-endpoint="GETapi-admin-expenses-monthly-summary"
               value="6"
               data-component="body">
    <br>
<p>Must be at least 1. Must not be greater than 12. Example: <code>6</code></p>
        </div>
        </form>

                    <h2 id="expenses-GETapi-admin-expenses--expense_id-">Display an expense.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>expenses.view</code>.</p>

<span id="example-requests-GETapi-admin-expenses--expense_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/expenses/17" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/expenses/17"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-expenses--expense_id-">
    </span>
<span id="execution-results-GETapi-admin-expenses--expense_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-expenses--expense_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-expenses--expense_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-expenses--expense_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-expenses--expense_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-expenses--expense_id-" data-method="GET"
      data-path="api/admin/expenses/{expense_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-expenses--expense_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-expenses--expense_id-"
                    onclick="tryItOut('GETapi-admin-expenses--expense_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-expenses--expense_id-"
                    onclick="cancelTryOut('GETapi-admin-expenses--expense_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-expenses--expense_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/expenses/{expense_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-expenses--expense_id-"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-expenses--expense_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-expenses--expense_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>expense_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="expense_id"                data-endpoint="GETapi-admin-expenses--expense_id-"
               value="17"
               data-component="url">
    <br>
<p>The ID of the expense. Example: <code>17</code></p>
            </div>
                    </form>

                    <h2 id="expenses-PUTapi-admin-expenses--expense_id-">Update an expense.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>expenses.update</code>.</p>

<span id="example-requests-PUTapi-admin-expenses--expense_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost/api/admin/expenses/17" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "vehicle_id=17"\
    --form "expense_category_slug=maintenance"\
    --form "amount=250"\
    --form "expense_date=2026-06-10T20:50:28"\
    --form "description=Updated invoice details."\
    --form "invoice=@C:\Users\dell\AppData\Local\Temp\php7079.tmp" </code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/expenses/17"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('vehicle_id', '17');
body.append('expense_category_slug', 'maintenance');
body.append('amount', '250');
body.append('expense_date', '2026-06-10T20:50:28');
body.append('description', 'Updated invoice details.');
body.append('invoice', document.querySelector('input[name="invoice"]').files[0]);

fetch(url, {
    method: "PUT",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-admin-expenses--expense_id-">
</span>
<span id="execution-results-PUTapi-admin-expenses--expense_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-admin-expenses--expense_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-admin-expenses--expense_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-admin-expenses--expense_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-admin-expenses--expense_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-admin-expenses--expense_id-" data-method="PUT"
      data-path="api/admin/expenses/{expense_id}"
      data-authed="1"
      data-hasfiles="1"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-admin-expenses--expense_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-admin-expenses--expense_id-"
                    onclick="tryItOut('PUTapi-admin-expenses--expense_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-admin-expenses--expense_id-"
                    onclick="cancelTryOut('PUTapi-admin-expenses--expense_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-admin-expenses--expense_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/admin/expenses/{expense_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-admin-expenses--expense_id-"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-admin-expenses--expense_id-"
               value="multipart/form-data"
               data-component="header">
    <br>
<p>Example: <code>multipart/form-data</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-admin-expenses--expense_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>expense_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="expense_id"                data-endpoint="PUTapi-admin-expenses--expense_id-"
               value="17"
               data-component="url">
    <br>
<p>The ID of the expense. Example: <code>17</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>vehicle_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="vehicle_id"                data-endpoint="PUTapi-admin-expenses--expense_id-"
               value="17"
               data-component="body">
    <br>
<p>Must match an existing stored value. Example: <code>17</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>expense_category_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="expense_category_slug"                data-endpoint="PUTapi-admin-expenses--expense_id-"
               value="maintenance"
               data-component="body">
    <br>
<p>optional Expense category slug. Example: <code>maintenance</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>amount</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="amount"                data-endpoint="PUTapi-admin-expenses--expense_id-"
               value="250"
               data-component="body">
    <br>
<p>optional Expense amount. Example: <code>250</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>expense_date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="expense_date"                data-endpoint="PUTapi-admin-expenses--expense_id-"
               value="2026-06-10T20:50:28"
               data-component="body">
    <br>
<p>Must be a valid date. Example: <code>2026-06-10T20:50:28</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="PUTapi-admin-expenses--expense_id-"
               value="Updated invoice details."
               data-component="body">
    <br>
<p>optional Expense description. Example: <code>Updated invoice details.</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>invoice</code></b>&nbsp;&nbsp;
<small>file</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="file" style="display: none"
                              name="invoice"                data-endpoint="PUTapi-admin-expenses--expense_id-"
               value=""
               data-component="body">
    <br>
<p>optional Replacement invoice file. Example: <code>C:\Users\dell\AppData\Local\Temp\php7079.tmp</code></p>
        </div>
        </form>

                    <h2 id="expenses-PATCHapi-admin-expenses--expense_id-">Update an expense.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>expenses.update</code>.</p>

<span id="example-requests-PATCHapi-admin-expenses--expense_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PATCH \
    "http://localhost/api/admin/expenses/17" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "vehicle_id=17"\
    --form "expense_category_slug=maintenance"\
    --form "amount=250"\
    --form "expense_date=2026-06-10T20:50:28"\
    --form "description=Updated invoice details."\
    --form "invoice=@C:\Users\dell\AppData\Local\Temp\php708A.tmp" </code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/expenses/17"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('vehicle_id', '17');
body.append('expense_category_slug', 'maintenance');
body.append('amount', '250');
body.append('expense_date', '2026-06-10T20:50:28');
body.append('description', 'Updated invoice details.');
body.append('invoice', document.querySelector('input[name="invoice"]').files[0]);

fetch(url, {
    method: "PATCH",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PATCHapi-admin-expenses--expense_id-">
</span>
<span id="execution-results-PATCHapi-admin-expenses--expense_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PATCHapi-admin-expenses--expense_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PATCHapi-admin-expenses--expense_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PATCHapi-admin-expenses--expense_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PATCHapi-admin-expenses--expense_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PATCHapi-admin-expenses--expense_id-" data-method="PATCH"
      data-path="api/admin/expenses/{expense_id}"
      data-authed="1"
      data-hasfiles="1"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PATCHapi-admin-expenses--expense_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PATCHapi-admin-expenses--expense_id-"
                    onclick="tryItOut('PATCHapi-admin-expenses--expense_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PATCHapi-admin-expenses--expense_id-"
                    onclick="cancelTryOut('PATCHapi-admin-expenses--expense_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PATCHapi-admin-expenses--expense_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/admin/expenses/{expense_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PATCHapi-admin-expenses--expense_id-"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PATCHapi-admin-expenses--expense_id-"
               value="multipart/form-data"
               data-component="header">
    <br>
<p>Example: <code>multipart/form-data</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PATCHapi-admin-expenses--expense_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>expense_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="expense_id"                data-endpoint="PATCHapi-admin-expenses--expense_id-"
               value="17"
               data-component="url">
    <br>
<p>The ID of the expense. Example: <code>17</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>vehicle_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="vehicle_id"                data-endpoint="PATCHapi-admin-expenses--expense_id-"
               value="17"
               data-component="body">
    <br>
<p>Must match an existing stored value. Example: <code>17</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>expense_category_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="expense_category_slug"                data-endpoint="PATCHapi-admin-expenses--expense_id-"
               value="maintenance"
               data-component="body">
    <br>
<p>optional Expense category slug. Example: <code>maintenance</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>amount</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="amount"                data-endpoint="PATCHapi-admin-expenses--expense_id-"
               value="250"
               data-component="body">
    <br>
<p>optional Expense amount. Example: <code>250</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>expense_date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="expense_date"                data-endpoint="PATCHapi-admin-expenses--expense_id-"
               value="2026-06-10T20:50:28"
               data-component="body">
    <br>
<p>Must be a valid date. Example: <code>2026-06-10T20:50:28</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="PATCHapi-admin-expenses--expense_id-"
               value="Updated invoice details."
               data-component="body">
    <br>
<p>optional Expense description. Example: <code>Updated invoice details.</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>invoice</code></b>&nbsp;&nbsp;
<small>file</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="file" style="display: none"
                              name="invoice"                data-endpoint="PATCHapi-admin-expenses--expense_id-"
               value=""
               data-component="body">
    <br>
<p>optional Replacement invoice file. Example: <code>C:\Users\dell\AppData\Local\Temp\php708A.tmp</code></p>
        </div>
        </form>

                    <h2 id="expenses-DELETEapi-admin-expenses--expense_id-">Soft delete an expense.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>expenses.delete</code>.</p>

<span id="example-requests-DELETEapi-admin-expenses--expense_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost/api/admin/expenses/17" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/expenses/17"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-admin-expenses--expense_id-">
</span>
<span id="execution-results-DELETEapi-admin-expenses--expense_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-admin-expenses--expense_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-admin-expenses--expense_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-admin-expenses--expense_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-admin-expenses--expense_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-admin-expenses--expense_id-" data-method="DELETE"
      data-path="api/admin/expenses/{expense_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-admin-expenses--expense_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-admin-expenses--expense_id-"
                    onclick="tryItOut('DELETEapi-admin-expenses--expense_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-admin-expenses--expense_id-"
                    onclick="cancelTryOut('DELETEapi-admin-expenses--expense_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-admin-expenses--expense_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/admin/expenses/{expense_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="DELETEapi-admin-expenses--expense_id-"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-admin-expenses--expense_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-admin-expenses--expense_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>expense_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="expense_id"                data-endpoint="DELETEapi-admin-expenses--expense_id-"
               value="17"
               data-component="url">
    <br>
<p>The ID of the expense. Example: <code>17</code></p>
            </div>
                    </form>

                <h1 id="locations">Locations</h1>

    <p>Admin location endpoints. Requires the matching <code>locations.*</code> permission listed on each endpoint.</p>

                                <h2 id="locations-GETapi-admin-locations">List locations for the admin dashboard.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>locations.view</code>.</p>

<span id="example-requests-GETapi-admin-locations">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/locations" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/locations"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-locations">
    </span>
<span id="execution-results-GETapi-admin-locations" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-locations"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-locations"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-locations" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-locations">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-locations" data-method="GET"
      data-path="api/admin/locations"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-locations', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-locations"
                    onclick="tryItOut('GETapi-admin-locations');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-locations"
                    onclick="cancelTryOut('GETapi-admin-locations');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-locations"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/locations</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-locations"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-locations"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-locations"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="locations-POSTapi-admin-locations">Store a new location.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>locations.create</code>.</p>

<span id="example-requests-POSTapi-admin-locations">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/admin/locations" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"location_type_slug\": \"agency\",
    \"name\": \"Dakhla Agency\",
    \"slug\": \"dakhla-agency\",
    \"address\": \"Avenue Mohammed V, Dakhla\",
    \"delivery_fee\": 100,
    \"is_active\": true
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/locations"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "location_type_slug": "agency",
    "name": "Dakhla Agency",
    "slug": "dakhla-agency",
    "address": "Avenue Mohammed V, Dakhla",
    "delivery_fee": 100,
    "is_active": true
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-admin-locations">
</span>
<span id="execution-results-POSTapi-admin-locations" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-admin-locations"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-locations"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-admin-locations" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-locations">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-admin-locations" data-method="POST"
      data-path="api/admin/locations"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-locations', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-admin-locations"
                    onclick="tryItOut('POSTapi-admin-locations');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-admin-locations"
                    onclick="cancelTryOut('POSTapi-admin-locations');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-admin-locations"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/admin/locations</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-admin-locations"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-admin-locations"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-admin-locations"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>location_type_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="location_type_slug"                data-endpoint="POSTapi-admin-locations"
               value="agency"
               data-component="body">
    <br>
<p>Location type slug. Example: <code>agency</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-admin-locations"
               value="Dakhla Agency"
               data-component="body">
    <br>
<p>Location name. Example: <code>Dakhla Agency</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="slug"                data-endpoint="POSTapi-admin-locations"
               value="dakhla-agency"
               data-component="body">
    <br>
<p>Unique location slug. Example: <code>dakhla-agency</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="address"                data-endpoint="POSTapi-admin-locations"
               value="Avenue Mohammed V, Dakhla"
               data-component="body">
    <br>
<p>optional Location address. Example: <code>Avenue Mohammed V, Dakhla</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>delivery_fee</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="delivery_fee"                data-endpoint="POSTapi-admin-locations"
               value="100"
               data-component="body">
    <br>
<p>optional Delivery fee. Example: <code>100</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_active</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="POSTapi-admin-locations" style="display: none">
            <input type="radio" name="is_active"
                   value="true"
                   data-endpoint="POSTapi-admin-locations"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-admin-locations" style="display: none">
            <input type="radio" name="is_active"
                   value="false"
                   data-endpoint="POSTapi-admin-locations"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>optional Whether public users can choose this location. Example: <code>true</code></p>
        </div>
        </form>

                    <h2 id="locations-GETapi-admin-locations--location_id-">Display a location.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>locations.view</code>.</p>

<span id="example-requests-GETapi-admin-locations--location_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/locations/17" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/locations/17"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-locations--location_id-">
    </span>
<span id="execution-results-GETapi-admin-locations--location_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-locations--location_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-locations--location_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-locations--location_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-locations--location_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-locations--location_id-" data-method="GET"
      data-path="api/admin/locations/{location_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-locations--location_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-locations--location_id-"
                    onclick="tryItOut('GETapi-admin-locations--location_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-locations--location_id-"
                    onclick="cancelTryOut('GETapi-admin-locations--location_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-locations--location_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/locations/{location_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-locations--location_id-"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-locations--location_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-locations--location_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>location_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="location_id"                data-endpoint="GETapi-admin-locations--location_id-"
               value="17"
               data-component="url">
    <br>
<p>The ID of the location. Example: <code>17</code></p>
            </div>
                    </form>

                    <h2 id="locations-PUTapi-admin-locations--location_id-">Update a location.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>locations.update</code>.</p>

<span id="example-requests-PUTapi-admin-locations--location_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost/api/admin/locations/17" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"location_type_slug\": \"consequatur\",
    \"name\": \"Dakhla Airport\",
    \"slug\": \"mniihfqcoynlazghdtqtq\",
    \"address\": \"xbajwbpilpmufinllwloa\",
    \"delivery_fee\": 150,
    \"is_active\": true
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/locations/17"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "location_type_slug": "consequatur",
    "name": "Dakhla Airport",
    "slug": "mniihfqcoynlazghdtqtq",
    "address": "xbajwbpilpmufinllwloa",
    "delivery_fee": 150,
    "is_active": true
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-admin-locations--location_id-">
</span>
<span id="execution-results-PUTapi-admin-locations--location_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-admin-locations--location_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-admin-locations--location_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-admin-locations--location_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-admin-locations--location_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-admin-locations--location_id-" data-method="PUT"
      data-path="api/admin/locations/{location_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-admin-locations--location_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-admin-locations--location_id-"
                    onclick="tryItOut('PUTapi-admin-locations--location_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-admin-locations--location_id-"
                    onclick="cancelTryOut('PUTapi-admin-locations--location_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-admin-locations--location_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/admin/locations/{location_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-admin-locations--location_id-"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-admin-locations--location_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-admin-locations--location_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>location_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="location_id"                data-endpoint="PUTapi-admin-locations--location_id-"
               value="17"
               data-component="url">
    <br>
<p>The ID of the location. Example: <code>17</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>location_type_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="location_type_slug"                data-endpoint="PUTapi-admin-locations--location_id-"
               value="consequatur"
               data-component="body">
    <br>
<p>Must match an existing stored value. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PUTapi-admin-locations--location_id-"
               value="Dakhla Airport"
               data-component="body">
    <br>
<p>optional Location name. Example: <code>Dakhla Airport</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="slug"                data-endpoint="PUTapi-admin-locations--location_id-"
               value="mniihfqcoynlazghdtqtq"
               data-component="body">
    <br>
<p>Must contain only letters, numbers, dashes and underscores. Must not be greater than 255 characters. Example: <code>mniihfqcoynlazghdtqtq</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="address"                data-endpoint="PUTapi-admin-locations--location_id-"
               value="xbajwbpilpmufinllwloa"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>xbajwbpilpmufinllwloa</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>delivery_fee</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="delivery_fee"                data-endpoint="PUTapi-admin-locations--location_id-"
               value="150"
               data-component="body">
    <br>
<p>optional Delivery fee. Example: <code>150</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_active</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="PUTapi-admin-locations--location_id-" style="display: none">
            <input type="radio" name="is_active"
                   value="true"
                   data-endpoint="PUTapi-admin-locations--location_id-"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="PUTapi-admin-locations--location_id-" style="display: none">
            <input type="radio" name="is_active"
                   value="false"
                   data-endpoint="PUTapi-admin-locations--location_id-"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>optional Whether public users can choose this location. Example: <code>true</code></p>
        </div>
        </form>

                    <h2 id="locations-PATCHapi-admin-locations--location_id-">Update a location.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>locations.update</code>.</p>

<span id="example-requests-PATCHapi-admin-locations--location_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PATCH \
    "http://localhost/api/admin/locations/17" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"location_type_slug\": \"consequatur\",
    \"name\": \"Dakhla Airport\",
    \"slug\": \"mniihfqcoynlazghdtqtq\",
    \"address\": \"xbajwbpilpmufinllwloa\",
    \"delivery_fee\": 150,
    \"is_active\": true
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/locations/17"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "location_type_slug": "consequatur",
    "name": "Dakhla Airport",
    "slug": "mniihfqcoynlazghdtqtq",
    "address": "xbajwbpilpmufinllwloa",
    "delivery_fee": 150,
    "is_active": true
};

fetch(url, {
    method: "PATCH",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PATCHapi-admin-locations--location_id-">
</span>
<span id="execution-results-PATCHapi-admin-locations--location_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PATCHapi-admin-locations--location_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PATCHapi-admin-locations--location_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PATCHapi-admin-locations--location_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PATCHapi-admin-locations--location_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PATCHapi-admin-locations--location_id-" data-method="PATCH"
      data-path="api/admin/locations/{location_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PATCHapi-admin-locations--location_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PATCHapi-admin-locations--location_id-"
                    onclick="tryItOut('PATCHapi-admin-locations--location_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PATCHapi-admin-locations--location_id-"
                    onclick="cancelTryOut('PATCHapi-admin-locations--location_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PATCHapi-admin-locations--location_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/admin/locations/{location_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PATCHapi-admin-locations--location_id-"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PATCHapi-admin-locations--location_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PATCHapi-admin-locations--location_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>location_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="location_id"                data-endpoint="PATCHapi-admin-locations--location_id-"
               value="17"
               data-component="url">
    <br>
<p>The ID of the location. Example: <code>17</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>location_type_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="location_type_slug"                data-endpoint="PATCHapi-admin-locations--location_id-"
               value="consequatur"
               data-component="body">
    <br>
<p>Must match an existing stored value. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PATCHapi-admin-locations--location_id-"
               value="Dakhla Airport"
               data-component="body">
    <br>
<p>optional Location name. Example: <code>Dakhla Airport</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="slug"                data-endpoint="PATCHapi-admin-locations--location_id-"
               value="mniihfqcoynlazghdtqtq"
               data-component="body">
    <br>
<p>Must contain only letters, numbers, dashes and underscores. Must not be greater than 255 characters. Example: <code>mniihfqcoynlazghdtqtq</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="address"                data-endpoint="PATCHapi-admin-locations--location_id-"
               value="xbajwbpilpmufinllwloa"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>xbajwbpilpmufinllwloa</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>delivery_fee</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="delivery_fee"                data-endpoint="PATCHapi-admin-locations--location_id-"
               value="150"
               data-component="body">
    <br>
<p>optional Delivery fee. Example: <code>150</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_active</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="PATCHapi-admin-locations--location_id-" style="display: none">
            <input type="radio" name="is_active"
                   value="true"
                   data-endpoint="PATCHapi-admin-locations--location_id-"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="PATCHapi-admin-locations--location_id-" style="display: none">
            <input type="radio" name="is_active"
                   value="false"
                   data-endpoint="PATCHapi-admin-locations--location_id-"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>optional Whether public users can choose this location. Example: <code>true</code></p>
        </div>
        </form>

                    <h2 id="locations-DELETEapi-admin-locations--location_id-">Soft delete a location.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>locations.delete</code>.</p>

<span id="example-requests-DELETEapi-admin-locations--location_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost/api/admin/locations/17" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/locations/17"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-admin-locations--location_id-">
</span>
<span id="execution-results-DELETEapi-admin-locations--location_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-admin-locations--location_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-admin-locations--location_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-admin-locations--location_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-admin-locations--location_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-admin-locations--location_id-" data-method="DELETE"
      data-path="api/admin/locations/{location_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-admin-locations--location_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-admin-locations--location_id-"
                    onclick="tryItOut('DELETEapi-admin-locations--location_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-admin-locations--location_id-"
                    onclick="cancelTryOut('DELETEapi-admin-locations--location_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-admin-locations--location_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/admin/locations/{location_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="DELETEapi-admin-locations--location_id-"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-admin-locations--location_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-admin-locations--location_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>location_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="location_id"                data-endpoint="DELETEapi-admin-locations--location_id-"
               value="17"
               data-component="url">
    <br>
<p>The ID of the location. Example: <code>17</code></p>
            </div>
                    </form>

                <h1 id="maintenance">Maintenance</h1>

    <p>Admin vehicle maintenance endpoints. Requires the matching <code>maintenance.*</code> permission listed on each endpoint.</p>

                                <h2 id="maintenance-GETapi-admin-vehicles--vehicle_id--maintenances">List maintenance records for one vehicle.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>maintenance.view</code>.</p>

<span id="example-requests-GETapi-admin-vehicles--vehicle_id--maintenances">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/vehicles/17/maintenances" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/vehicles/17/maintenances"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-vehicles--vehicle_id--maintenances">
    </span>
<span id="execution-results-GETapi-admin-vehicles--vehicle_id--maintenances" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-vehicles--vehicle_id--maintenances"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-vehicles--vehicle_id--maintenances"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-vehicles--vehicle_id--maintenances" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-vehicles--vehicle_id--maintenances">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-vehicles--vehicle_id--maintenances" data-method="GET"
      data-path="api/admin/vehicles/{vehicle_id}/maintenances"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-vehicles--vehicle_id--maintenances', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-vehicles--vehicle_id--maintenances"
                    onclick="tryItOut('GETapi-admin-vehicles--vehicle_id--maintenances');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-vehicles--vehicle_id--maintenances"
                    onclick="cancelTryOut('GETapi-admin-vehicles--vehicle_id--maintenances');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-vehicles--vehicle_id--maintenances"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/vehicles/{vehicle_id}/maintenances</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-vehicles--vehicle_id--maintenances"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-vehicles--vehicle_id--maintenances"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-vehicles--vehicle_id--maintenances"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>vehicle_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="vehicle_id"                data-endpoint="GETapi-admin-vehicles--vehicle_id--maintenances"
               value="17"
               data-component="url">
    <br>
<p>The ID of the vehicle. Example: <code>17</code></p>
            </div>
                    </form>

                    <h2 id="maintenance-GETapi-admin-maintenances">List maintenance records.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>maintenance.view</code>.</p>

<span id="example-requests-GETapi-admin-maintenances">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/maintenances" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/maintenances"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-maintenances">
    </span>
<span id="execution-results-GETapi-admin-maintenances" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-maintenances"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-maintenances"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-maintenances" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-maintenances">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-maintenances" data-method="GET"
      data-path="api/admin/maintenances"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-maintenances', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-maintenances"
                    onclick="tryItOut('GETapi-admin-maintenances');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-maintenances"
                    onclick="cancelTryOut('GETapi-admin-maintenances');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-maintenances"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/maintenances</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-maintenances"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-maintenances"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-maintenances"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="maintenance-POSTapi-admin-maintenances">Create a maintenance record.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>maintenance.create</code>.</p>

<span id="example-requests-POSTapi-admin-maintenances">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/admin/maintenances" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"vehicle_id\": 1,
    \"maintenance_type_slug\": \"oil_change\",
    \"maintenance_date\": \"2026-06-10\",
    \"next_maintenance_date\": \"2026-07-10\",
    \"mileage\": 21000,
    \"cost\": 450,
    \"garage_name\": \"Dakhla Garage\",
    \"notes\": \"Routine service.\",
    \"vehicle_status_slug\": \"maintenance\",
    \"create_expense\": true,
    \"expense_category_slug\": \"maintenance\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/maintenances"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "vehicle_id": 1,
    "maintenance_type_slug": "oil_change",
    "maintenance_date": "2026-06-10",
    "next_maintenance_date": "2026-07-10",
    "mileage": 21000,
    "cost": 450,
    "garage_name": "Dakhla Garage",
    "notes": "Routine service.",
    "vehicle_status_slug": "maintenance",
    "create_expense": true,
    "expense_category_slug": "maintenance"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-admin-maintenances">
</span>
<span id="execution-results-POSTapi-admin-maintenances" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-admin-maintenances"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-maintenances"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-admin-maintenances" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-maintenances">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-admin-maintenances" data-method="POST"
      data-path="api/admin/maintenances"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-maintenances', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-admin-maintenances"
                    onclick="tryItOut('POSTapi-admin-maintenances');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-admin-maintenances"
                    onclick="cancelTryOut('POSTapi-admin-maintenances');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-admin-maintenances"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/admin/maintenances</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-admin-maintenances"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-admin-maintenances"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-admin-maintenances"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>vehicle_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="vehicle_id"                data-endpoint="POSTapi-admin-maintenances"
               value="1"
               data-component="body">
    <br>
<p>Vehicle ID. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>maintenance_type_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="maintenance_type_slug"                data-endpoint="POSTapi-admin-maintenances"
               value="oil_change"
               data-component="body">
    <br>
<p>Maintenance type slug. Example: <code>oil_change</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>maintenance_date</code></b>&nbsp;&nbsp;
<small>date</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="maintenance_date"                data-endpoint="POSTapi-admin-maintenances"
               value="2026-06-10"
               data-component="body">
    <br>
<p>Maintenance date. Example: <code>2026-06-10</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>next_maintenance_date</code></b>&nbsp;&nbsp;
<small>date</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="next_maintenance_date"                data-endpoint="POSTapi-admin-maintenances"
               value="2026-07-10"
               data-component="body">
    <br>
<p>optional Next scheduled maintenance date. Example: <code>2026-07-10</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>mileage</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="mileage"                data-endpoint="POSTapi-admin-maintenances"
               value="21000"
               data-component="body">
    <br>
<p>optional Vehicle mileage. Example: <code>21000</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>cost</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="cost"                data-endpoint="POSTapi-admin-maintenances"
               value="450"
               data-component="body">
    <br>
<p>optional Maintenance cost. Example: <code>450</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>garage_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="garage_name"                data-endpoint="POSTapi-admin-maintenances"
               value="Dakhla Garage"
               data-component="body">
    <br>
<p>optional Garage name. Example: <code>Dakhla Garage</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>notes</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="notes"                data-endpoint="POSTapi-admin-maintenances"
               value="Routine service."
               data-component="body">
    <br>
<p>optional Maintenance notes. Example: <code>Routine service.</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>vehicle_status_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="vehicle_status_slug"                data-endpoint="POSTapi-admin-maintenances"
               value="maintenance"
               data-component="body">
    <br>
<p>optional Set vehicle status to maintenance or repair. Example: <code>maintenance</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>create_expense</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="POSTapi-admin-maintenances" style="display: none">
            <input type="radio" name="create_expense"
                   value="true"
                   data-endpoint="POSTapi-admin-maintenances"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-admin-maintenances" style="display: none">
            <input type="radio" name="create_expense"
                   value="false"
                   data-endpoint="POSTapi-admin-maintenances"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>optional Create an expense from the maintenance cost. Example: <code>true</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>expense_category_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="expense_category_slug"                data-endpoint="POSTapi-admin-maintenances"
               value="maintenance"
               data-component="body">
    <br>
<p>optional Expense category slug when create_expense is true. Example: <code>maintenance</code></p>
        </div>
        </form>

                    <h2 id="maintenance-GETapi-admin-maintenances-upcoming">List upcoming maintenance records.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>maintenance.view</code>.</p>

<span id="example-requests-GETapi-admin-maintenances-upcoming">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/maintenances/upcoming" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/maintenances/upcoming"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-maintenances-upcoming">
    </span>
<span id="execution-results-GETapi-admin-maintenances-upcoming" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-maintenances-upcoming"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-maintenances-upcoming"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-maintenances-upcoming" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-maintenances-upcoming">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-maintenances-upcoming" data-method="GET"
      data-path="api/admin/maintenances/upcoming"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-maintenances-upcoming', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-maintenances-upcoming"
                    onclick="tryItOut('GETapi-admin-maintenances-upcoming');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-maintenances-upcoming"
                    onclick="cancelTryOut('GETapi-admin-maintenances-upcoming');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-maintenances-upcoming"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/maintenances/upcoming</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-maintenances-upcoming"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-maintenances-upcoming"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-maintenances-upcoming"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="maintenance-GETapi-admin-maintenances--maintenance_id-">Display a maintenance record.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>maintenance.view</code>.</p>

<span id="example-requests-GETapi-admin-maintenances--maintenance_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/maintenances/17" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/maintenances/17"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-maintenances--maintenance_id-">
    </span>
<span id="execution-results-GETapi-admin-maintenances--maintenance_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-maintenances--maintenance_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-maintenances--maintenance_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-maintenances--maintenance_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-maintenances--maintenance_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-maintenances--maintenance_id-" data-method="GET"
      data-path="api/admin/maintenances/{maintenance_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-maintenances--maintenance_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-maintenances--maintenance_id-"
                    onclick="tryItOut('GETapi-admin-maintenances--maintenance_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-maintenances--maintenance_id-"
                    onclick="cancelTryOut('GETapi-admin-maintenances--maintenance_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-maintenances--maintenance_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/maintenances/{maintenance_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-maintenances--maintenance_id-"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-maintenances--maintenance_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-maintenances--maintenance_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>maintenance_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="maintenance_id"                data-endpoint="GETapi-admin-maintenances--maintenance_id-"
               value="17"
               data-component="url">
    <br>
<p>The ID of the maintenance. Example: <code>17</code></p>
            </div>
                    </form>

                    <h2 id="maintenance-PUTapi-admin-maintenances--maintenance_id-">Update a maintenance record.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>maintenance.update</code>.</p>

<span id="example-requests-PUTapi-admin-maintenances--maintenance_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost/api/admin/maintenances/17" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"vehicle_id\": 17,
    \"maintenance_type_slug\": \"consequatur\",
    \"maintenance_date\": \"2026-06-10T20:50:28\",
    \"next_maintenance_date\": \"2026-08-10\",
    \"mileage\": 45,
    \"cost\": 500,
    \"garage_name\": \"eopfuudtdsufvyvddqamn\",
    \"notes\": \"consequatur\",
    \"vehicle_status_slug\": \"repair\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/maintenances/17"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "vehicle_id": 17,
    "maintenance_type_slug": "consequatur",
    "maintenance_date": "2026-06-10T20:50:28",
    "next_maintenance_date": "2026-08-10",
    "mileage": 45,
    "cost": 500,
    "garage_name": "eopfuudtdsufvyvddqamn",
    "notes": "consequatur",
    "vehicle_status_slug": "repair"
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-admin-maintenances--maintenance_id-">
</span>
<span id="execution-results-PUTapi-admin-maintenances--maintenance_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-admin-maintenances--maintenance_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-admin-maintenances--maintenance_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-admin-maintenances--maintenance_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-admin-maintenances--maintenance_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-admin-maintenances--maintenance_id-" data-method="PUT"
      data-path="api/admin/maintenances/{maintenance_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-admin-maintenances--maintenance_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-admin-maintenances--maintenance_id-"
                    onclick="tryItOut('PUTapi-admin-maintenances--maintenance_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-admin-maintenances--maintenance_id-"
                    onclick="cancelTryOut('PUTapi-admin-maintenances--maintenance_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-admin-maintenances--maintenance_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/admin/maintenances/{maintenance_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-admin-maintenances--maintenance_id-"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-admin-maintenances--maintenance_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-admin-maintenances--maintenance_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>maintenance_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="maintenance_id"                data-endpoint="PUTapi-admin-maintenances--maintenance_id-"
               value="17"
               data-component="url">
    <br>
<p>The ID of the maintenance. Example: <code>17</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>vehicle_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="vehicle_id"                data-endpoint="PUTapi-admin-maintenances--maintenance_id-"
               value="17"
               data-component="body">
    <br>
<p>Must match an existing stored value. Example: <code>17</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>maintenance_type_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="maintenance_type_slug"                data-endpoint="PUTapi-admin-maintenances--maintenance_id-"
               value="consequatur"
               data-component="body">
    <br>
<p>Must match an existing stored value. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>maintenance_date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="maintenance_date"                data-endpoint="PUTapi-admin-maintenances--maintenance_id-"
               value="2026-06-10T20:50:28"
               data-component="body">
    <br>
<p>Must be a valid date. Example: <code>2026-06-10T20:50:28</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>next_maintenance_date</code></b>&nbsp;&nbsp;
<small>date</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="next_maintenance_date"                data-endpoint="PUTapi-admin-maintenances--maintenance_id-"
               value="2026-08-10"
               data-component="body">
    <br>
<p>optional Next scheduled maintenance date. Example: <code>2026-08-10</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>mileage</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="mileage"                data-endpoint="PUTapi-admin-maintenances--maintenance_id-"
               value="45"
               data-component="body">
    <br>
<p>Must be at least 0. Example: <code>45</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>cost</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="cost"                data-endpoint="PUTapi-admin-maintenances--maintenance_id-"
               value="500"
               data-component="body">
    <br>
<p>optional Maintenance cost. Example: <code>500</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>garage_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="garage_name"                data-endpoint="PUTapi-admin-maintenances--maintenance_id-"
               value="eopfuudtdsufvyvddqamn"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>eopfuudtdsufvyvddqamn</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>notes</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="notes"                data-endpoint="PUTapi-admin-maintenances--maintenance_id-"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>vehicle_status_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="vehicle_status_slug"                data-endpoint="PUTapi-admin-maintenances--maintenance_id-"
               value="repair"
               data-component="body">
    <br>
<p>optional Set vehicle status to maintenance or repair. Example: <code>repair</code></p>
        </div>
        </form>

                    <h2 id="maintenance-PATCHapi-admin-maintenances--maintenance_id-">Update a maintenance record.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>maintenance.update</code>.</p>

<span id="example-requests-PATCHapi-admin-maintenances--maintenance_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PATCH \
    "http://localhost/api/admin/maintenances/17" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"vehicle_id\": 17,
    \"maintenance_type_slug\": \"consequatur\",
    \"maintenance_date\": \"2026-06-10T20:50:28\",
    \"next_maintenance_date\": \"2026-08-10\",
    \"mileage\": 45,
    \"cost\": 500,
    \"garage_name\": \"eopfuudtdsufvyvddqamn\",
    \"notes\": \"consequatur\",
    \"vehicle_status_slug\": \"repair\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/maintenances/17"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "vehicle_id": 17,
    "maintenance_type_slug": "consequatur",
    "maintenance_date": "2026-06-10T20:50:28",
    "next_maintenance_date": "2026-08-10",
    "mileage": 45,
    "cost": 500,
    "garage_name": "eopfuudtdsufvyvddqamn",
    "notes": "consequatur",
    "vehicle_status_slug": "repair"
};

fetch(url, {
    method: "PATCH",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PATCHapi-admin-maintenances--maintenance_id-">
</span>
<span id="execution-results-PATCHapi-admin-maintenances--maintenance_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PATCHapi-admin-maintenances--maintenance_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PATCHapi-admin-maintenances--maintenance_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PATCHapi-admin-maintenances--maintenance_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PATCHapi-admin-maintenances--maintenance_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PATCHapi-admin-maintenances--maintenance_id-" data-method="PATCH"
      data-path="api/admin/maintenances/{maintenance_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PATCHapi-admin-maintenances--maintenance_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PATCHapi-admin-maintenances--maintenance_id-"
                    onclick="tryItOut('PATCHapi-admin-maintenances--maintenance_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PATCHapi-admin-maintenances--maintenance_id-"
                    onclick="cancelTryOut('PATCHapi-admin-maintenances--maintenance_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PATCHapi-admin-maintenances--maintenance_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/admin/maintenances/{maintenance_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PATCHapi-admin-maintenances--maintenance_id-"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PATCHapi-admin-maintenances--maintenance_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PATCHapi-admin-maintenances--maintenance_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>maintenance_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="maintenance_id"                data-endpoint="PATCHapi-admin-maintenances--maintenance_id-"
               value="17"
               data-component="url">
    <br>
<p>The ID of the maintenance. Example: <code>17</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>vehicle_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="vehicle_id"                data-endpoint="PATCHapi-admin-maintenances--maintenance_id-"
               value="17"
               data-component="body">
    <br>
<p>Must match an existing stored value. Example: <code>17</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>maintenance_type_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="maintenance_type_slug"                data-endpoint="PATCHapi-admin-maintenances--maintenance_id-"
               value="consequatur"
               data-component="body">
    <br>
<p>Must match an existing stored value. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>maintenance_date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="maintenance_date"                data-endpoint="PATCHapi-admin-maintenances--maintenance_id-"
               value="2026-06-10T20:50:28"
               data-component="body">
    <br>
<p>Must be a valid date. Example: <code>2026-06-10T20:50:28</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>next_maintenance_date</code></b>&nbsp;&nbsp;
<small>date</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="next_maintenance_date"                data-endpoint="PATCHapi-admin-maintenances--maintenance_id-"
               value="2026-08-10"
               data-component="body">
    <br>
<p>optional Next scheduled maintenance date. Example: <code>2026-08-10</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>mileage</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="mileage"                data-endpoint="PATCHapi-admin-maintenances--maintenance_id-"
               value="45"
               data-component="body">
    <br>
<p>Must be at least 0. Example: <code>45</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>cost</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="cost"                data-endpoint="PATCHapi-admin-maintenances--maintenance_id-"
               value="500"
               data-component="body">
    <br>
<p>optional Maintenance cost. Example: <code>500</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>garage_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="garage_name"                data-endpoint="PATCHapi-admin-maintenances--maintenance_id-"
               value="eopfuudtdsufvyvddqamn"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>eopfuudtdsufvyvddqamn</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>notes</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="notes"                data-endpoint="PATCHapi-admin-maintenances--maintenance_id-"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>vehicle_status_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="vehicle_status_slug"                data-endpoint="PATCHapi-admin-maintenances--maintenance_id-"
               value="repair"
               data-component="body">
    <br>
<p>optional Set vehicle status to maintenance or repair. Example: <code>repair</code></p>
        </div>
        </form>

                    <h2 id="maintenance-DELETEapi-admin-maintenances--maintenance_id-">Soft delete a maintenance record.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>maintenance.delete</code>.</p>

<span id="example-requests-DELETEapi-admin-maintenances--maintenance_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost/api/admin/maintenances/17" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/maintenances/17"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-admin-maintenances--maintenance_id-">
</span>
<span id="execution-results-DELETEapi-admin-maintenances--maintenance_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-admin-maintenances--maintenance_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-admin-maintenances--maintenance_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-admin-maintenances--maintenance_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-admin-maintenances--maintenance_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-admin-maintenances--maintenance_id-" data-method="DELETE"
      data-path="api/admin/maintenances/{maintenance_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-admin-maintenances--maintenance_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-admin-maintenances--maintenance_id-"
                    onclick="tryItOut('DELETEapi-admin-maintenances--maintenance_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-admin-maintenances--maintenance_id-"
                    onclick="cancelTryOut('DELETEapi-admin-maintenances--maintenance_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-admin-maintenances--maintenance_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/admin/maintenances/{maintenance_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="DELETEapi-admin-maintenances--maintenance_id-"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-admin-maintenances--maintenance_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-admin-maintenances--maintenance_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>maintenance_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="maintenance_id"                data-endpoint="DELETEapi-admin-maintenances--maintenance_id-"
               value="17"
               data-component="url">
    <br>
<p>The ID of the maintenance. Example: <code>17</code></p>
            </div>
                    </form>

                <h1 id="payments">Payments</h1>

    <p>Admin payment endpoints. Requires <code>payments.view</code> or <code>payments.manage</code> as listed on each endpoint.</p>

                                <h2 id="payments-GETapi-admin-reservations--reservation_id--payment-summary">Return calculated payment totals for a reservation.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>payments.view</code>.</p>

<span id="example-requests-GETapi-admin-reservations--reservation_id--payment-summary">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/reservations/17/payment-summary" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/reservations/17/payment-summary"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-reservations--reservation_id--payment-summary">
    </span>
<span id="execution-results-GETapi-admin-reservations--reservation_id--payment-summary" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-reservations--reservation_id--payment-summary"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-reservations--reservation_id--payment-summary"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-reservations--reservation_id--payment-summary" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-reservations--reservation_id--payment-summary">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-reservations--reservation_id--payment-summary" data-method="GET"
      data-path="api/admin/reservations/{reservation_id}/payment-summary"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-reservations--reservation_id--payment-summary', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-reservations--reservation_id--payment-summary"
                    onclick="tryItOut('GETapi-admin-reservations--reservation_id--payment-summary');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-reservations--reservation_id--payment-summary"
                    onclick="cancelTryOut('GETapi-admin-reservations--reservation_id--payment-summary');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-reservations--reservation_id--payment-summary"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/reservations/{reservation_id}/payment-summary</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-reservations--reservation_id--payment-summary"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-reservations--reservation_id--payment-summary"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-reservations--reservation_id--payment-summary"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>reservation_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="reservation_id"                data-endpoint="GETapi-admin-reservations--reservation_id--payment-summary"
               value="17"
               data-component="url">
    <br>
<p>The ID of the reservation. Example: <code>17</code></p>
            </div>
                    </form>

                    <h2 id="payments-GETapi-admin-payments">List payments for the admin dashboard.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>payments.view</code>.</p>

<span id="example-requests-GETapi-admin-payments">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/payments" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/payments"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-payments">
    </span>
<span id="execution-results-GETapi-admin-payments" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-payments"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-payments"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-payments" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-payments">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-payments" data-method="GET"
      data-path="api/admin/payments"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-payments', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-payments"
                    onclick="tryItOut('GETapi-admin-payments');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-payments"
                    onclick="cancelTryOut('GETapi-admin-payments');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-payments"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/payments</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-payments"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-payments"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-payments"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="payments-POSTapi-admin-payments">Store a new payment and recalculate reservation payment status.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>payments.manage</code>.</p>

<span id="example-requests-POSTapi-admin-payments">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/admin/payments" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"reservation_id\": 1,
    \"payment_method_slug\": \"cash\",
    \"payment_type_slug\": \"rental_payment\",
    \"payment_status_slug\": \"paid\",
    \"amount\": 300,
    \"payment_date\": \"2026-06-10\",
    \"paid_by_customer_name\": \"Ahmed Dakhla\",
    \"reference\": \"PAY-001\",
    \"notes\": \"Advance payment.\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/payments"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "reservation_id": 1,
    "payment_method_slug": "cash",
    "payment_type_slug": "rental_payment",
    "payment_status_slug": "paid",
    "amount": 300,
    "payment_date": "2026-06-10",
    "paid_by_customer_name": "Ahmed Dakhla",
    "reference": "PAY-001",
    "notes": "Advance payment."
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-admin-payments">
</span>
<span id="execution-results-POSTapi-admin-payments" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-admin-payments"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-payments"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-admin-payments" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-payments">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-admin-payments" data-method="POST"
      data-path="api/admin/payments"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-payments', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-admin-payments"
                    onclick="tryItOut('POSTapi-admin-payments');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-admin-payments"
                    onclick="cancelTryOut('POSTapi-admin-payments');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-admin-payments"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/admin/payments</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-admin-payments"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-admin-payments"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-admin-payments"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>reservation_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="reservation_id"                data-endpoint="POSTapi-admin-payments"
               value="1"
               data-component="body">
    <br>
<p>Reservation ID. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>payment_method_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="payment_method_slug"                data-endpoint="POSTapi-admin-payments"
               value="cash"
               data-component="body">
    <br>
<p>Payment method slug. Example: <code>cash</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>payment_type_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="payment_type_slug"                data-endpoint="POSTapi-admin-payments"
               value="rental_payment"
               data-component="body">
    <br>
<p>Payment type slug. Example: <code>rental_payment</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>payment_status_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="payment_status_slug"                data-endpoint="POSTapi-admin-payments"
               value="paid"
               data-component="body">
    <br>
<p>Payment status slug. Example: <code>paid</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>amount</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="amount"                data-endpoint="POSTapi-admin-payments"
               value="300"
               data-component="body">
    <br>
<p>Payment amount. Example: <code>300</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>payment_date</code></b>&nbsp;&nbsp;
<small>date</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="payment_date"                data-endpoint="POSTapi-admin-payments"
               value="2026-06-10"
               data-component="body">
    <br>
<p>Payment date. Example: <code>2026-06-10</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>paid_by_customer_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="paid_by_customer_name"                data-endpoint="POSTapi-admin-payments"
               value="Ahmed Dakhla"
               data-component="body">
    <br>
<p>optional Name of the paying customer. Example: <code>Ahmed Dakhla</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>reference</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="reference"                data-endpoint="POSTapi-admin-payments"
               value="PAY-001"
               data-component="body">
    <br>
<p>optional External reference. Example: <code>PAY-001</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>notes</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="notes"                data-endpoint="POSTapi-admin-payments"
               value="Advance payment."
               data-component="body">
    <br>
<p>optional Internal payment notes. Example: <code>Advance payment.</code></p>
        </div>
        </form>

                    <h2 id="payments-GETapi-admin-payments--payment_id-">Display a payment.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>payments.view</code>.</p>

<span id="example-requests-GETapi-admin-payments--payment_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/payments/17" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/payments/17"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-payments--payment_id-">
    </span>
<span id="execution-results-GETapi-admin-payments--payment_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-payments--payment_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-payments--payment_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-payments--payment_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-payments--payment_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-payments--payment_id-" data-method="GET"
      data-path="api/admin/payments/{payment_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-payments--payment_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-payments--payment_id-"
                    onclick="tryItOut('GETapi-admin-payments--payment_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-payments--payment_id-"
                    onclick="cancelTryOut('GETapi-admin-payments--payment_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-payments--payment_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/payments/{payment_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-payments--payment_id-"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-payments--payment_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-payments--payment_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>payment_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="payment_id"                data-endpoint="GETapi-admin-payments--payment_id-"
               value="17"
               data-component="url">
    <br>
<p>The ID of the payment. Example: <code>17</code></p>
            </div>
                    </form>

                    <h2 id="payments-PUTapi-admin-payments--payment_id-">Update a payment and recalculate reservation payment status.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>payments.manage</code>.</p>

<span id="example-requests-PUTapi-admin-payments--payment_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost/api/admin/payments/17" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"reservation_id\": 17,
    \"payment_method_slug\": \"consequatur\",
    \"payment_type_slug\": \"consequatur\",
    \"payment_status_slug\": \"paid\",
    \"amount\": 1000,
    \"payment_date\": \"2026-06-10T20:50:28\",
    \"paid_by_customer_name\": \"qeopfuudtdsufvyvddqam\",
    \"reference\": \"FULL-001\",
    \"notes\": \"consequatur\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/payments/17"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "reservation_id": 17,
    "payment_method_slug": "consequatur",
    "payment_type_slug": "consequatur",
    "payment_status_slug": "paid",
    "amount": 1000,
    "payment_date": "2026-06-10T20:50:28",
    "paid_by_customer_name": "qeopfuudtdsufvyvddqam",
    "reference": "FULL-001",
    "notes": "consequatur"
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-admin-payments--payment_id-">
</span>
<span id="execution-results-PUTapi-admin-payments--payment_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-admin-payments--payment_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-admin-payments--payment_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-admin-payments--payment_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-admin-payments--payment_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-admin-payments--payment_id-" data-method="PUT"
      data-path="api/admin/payments/{payment_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-admin-payments--payment_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-admin-payments--payment_id-"
                    onclick="tryItOut('PUTapi-admin-payments--payment_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-admin-payments--payment_id-"
                    onclick="cancelTryOut('PUTapi-admin-payments--payment_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-admin-payments--payment_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/admin/payments/{payment_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-admin-payments--payment_id-"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-admin-payments--payment_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-admin-payments--payment_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>payment_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="payment_id"                data-endpoint="PUTapi-admin-payments--payment_id-"
               value="17"
               data-component="url">
    <br>
<p>The ID of the payment. Example: <code>17</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>reservation_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="reservation_id"                data-endpoint="PUTapi-admin-payments--payment_id-"
               value="17"
               data-component="body">
    <br>
<p>Must match an existing stored value. Example: <code>17</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>payment_method_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="payment_method_slug"                data-endpoint="PUTapi-admin-payments--payment_id-"
               value="consequatur"
               data-component="body">
    <br>
<p>Must match an existing stored value. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>payment_type_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="payment_type_slug"                data-endpoint="PUTapi-admin-payments--payment_id-"
               value="consequatur"
               data-component="body">
    <br>
<p>Must match an existing stored value. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>payment_status_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="payment_status_slug"                data-endpoint="PUTapi-admin-payments--payment_id-"
               value="paid"
               data-component="body">
    <br>
<p>optional Payment status slug. Example: <code>paid</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>amount</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="amount"                data-endpoint="PUTapi-admin-payments--payment_id-"
               value="1000"
               data-component="body">
    <br>
<p>optional Payment amount. Example: <code>1000</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>payment_date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="payment_date"                data-endpoint="PUTapi-admin-payments--payment_id-"
               value="2026-06-10T20:50:28"
               data-component="body">
    <br>
<p>Must be a valid date. Example: <code>2026-06-10T20:50:28</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>paid_by_customer_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="paid_by_customer_name"                data-endpoint="PUTapi-admin-payments--payment_id-"
               value="qeopfuudtdsufvyvddqam"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>qeopfuudtdsufvyvddqam</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>reference</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="reference"                data-endpoint="PUTapi-admin-payments--payment_id-"
               value="FULL-001"
               data-component="body">
    <br>
<p>optional External reference. Example: <code>FULL-001</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>notes</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="notes"                data-endpoint="PUTapi-admin-payments--payment_id-"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
        </form>

                    <h2 id="payments-PATCHapi-admin-payments--payment_id-">Update a payment and recalculate reservation payment status.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>payments.manage</code>.</p>

<span id="example-requests-PATCHapi-admin-payments--payment_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PATCH \
    "http://localhost/api/admin/payments/17" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"reservation_id\": 17,
    \"payment_method_slug\": \"consequatur\",
    \"payment_type_slug\": \"consequatur\",
    \"payment_status_slug\": \"paid\",
    \"amount\": 1000,
    \"payment_date\": \"2026-06-10T20:50:28\",
    \"paid_by_customer_name\": \"qeopfuudtdsufvyvddqam\",
    \"reference\": \"FULL-001\",
    \"notes\": \"consequatur\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/payments/17"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "reservation_id": 17,
    "payment_method_slug": "consequatur",
    "payment_type_slug": "consequatur",
    "payment_status_slug": "paid",
    "amount": 1000,
    "payment_date": "2026-06-10T20:50:28",
    "paid_by_customer_name": "qeopfuudtdsufvyvddqam",
    "reference": "FULL-001",
    "notes": "consequatur"
};

fetch(url, {
    method: "PATCH",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PATCHapi-admin-payments--payment_id-">
</span>
<span id="execution-results-PATCHapi-admin-payments--payment_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PATCHapi-admin-payments--payment_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PATCHapi-admin-payments--payment_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PATCHapi-admin-payments--payment_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PATCHapi-admin-payments--payment_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PATCHapi-admin-payments--payment_id-" data-method="PATCH"
      data-path="api/admin/payments/{payment_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PATCHapi-admin-payments--payment_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PATCHapi-admin-payments--payment_id-"
                    onclick="tryItOut('PATCHapi-admin-payments--payment_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PATCHapi-admin-payments--payment_id-"
                    onclick="cancelTryOut('PATCHapi-admin-payments--payment_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PATCHapi-admin-payments--payment_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/admin/payments/{payment_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PATCHapi-admin-payments--payment_id-"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PATCHapi-admin-payments--payment_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PATCHapi-admin-payments--payment_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>payment_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="payment_id"                data-endpoint="PATCHapi-admin-payments--payment_id-"
               value="17"
               data-component="url">
    <br>
<p>The ID of the payment. Example: <code>17</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>reservation_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="reservation_id"                data-endpoint="PATCHapi-admin-payments--payment_id-"
               value="17"
               data-component="body">
    <br>
<p>Must match an existing stored value. Example: <code>17</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>payment_method_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="payment_method_slug"                data-endpoint="PATCHapi-admin-payments--payment_id-"
               value="consequatur"
               data-component="body">
    <br>
<p>Must match an existing stored value. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>payment_type_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="payment_type_slug"                data-endpoint="PATCHapi-admin-payments--payment_id-"
               value="consequatur"
               data-component="body">
    <br>
<p>Must match an existing stored value. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>payment_status_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="payment_status_slug"                data-endpoint="PATCHapi-admin-payments--payment_id-"
               value="paid"
               data-component="body">
    <br>
<p>optional Payment status slug. Example: <code>paid</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>amount</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="amount"                data-endpoint="PATCHapi-admin-payments--payment_id-"
               value="1000"
               data-component="body">
    <br>
<p>optional Payment amount. Example: <code>1000</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>payment_date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="payment_date"                data-endpoint="PATCHapi-admin-payments--payment_id-"
               value="2026-06-10T20:50:28"
               data-component="body">
    <br>
<p>Must be a valid date. Example: <code>2026-06-10T20:50:28</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>paid_by_customer_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="paid_by_customer_name"                data-endpoint="PATCHapi-admin-payments--payment_id-"
               value="qeopfuudtdsufvyvddqam"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>qeopfuudtdsufvyvddqam</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>reference</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="reference"                data-endpoint="PATCHapi-admin-payments--payment_id-"
               value="FULL-001"
               data-component="body">
    <br>
<p>optional External reference. Example: <code>FULL-001</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>notes</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="notes"                data-endpoint="PATCHapi-admin-payments--payment_id-"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
        </form>

                    <h2 id="payments-POSTapi-admin-payments--payment_id--cancel">Safely cancel a payment without deleting the financial record.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>payments.manage</code>.</p>

<span id="example-requests-POSTapi-admin-payments--payment_id--cancel">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/admin/payments/17/cancel" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/payments/17/cancel"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-admin-payments--payment_id--cancel">
</span>
<span id="execution-results-POSTapi-admin-payments--payment_id--cancel" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-admin-payments--payment_id--cancel"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-payments--payment_id--cancel"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-admin-payments--payment_id--cancel" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-payments--payment_id--cancel">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-admin-payments--payment_id--cancel" data-method="POST"
      data-path="api/admin/payments/{payment_id}/cancel"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-payments--payment_id--cancel', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-admin-payments--payment_id--cancel"
                    onclick="tryItOut('POSTapi-admin-payments--payment_id--cancel');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-admin-payments--payment_id--cancel"
                    onclick="cancelTryOut('POSTapi-admin-payments--payment_id--cancel');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-admin-payments--payment_id--cancel"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/admin/payments/{payment_id}/cancel</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-admin-payments--payment_id--cancel"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-admin-payments--payment_id--cancel"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-admin-payments--payment_id--cancel"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>payment_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="payment_id"                data-endpoint="POSTapi-admin-payments--payment_id--cancel"
               value="17"
               data-component="url">
    <br>
<p>The ID of the payment. Example: <code>17</code></p>
            </div>
                    </form>

                <h1 id="public">Public</h1>

    <p>Lookup data for frontend forms.</p>

                                <h2 id="public-GETapi-public-lookups">Public safe lookup data.</h2>

<p>
</p>



<span id="example-requests-GETapi-public-lookups">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/public/lookups" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/public/lookups"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-public-lookups">
    </span>
<span id="execution-results-GETapi-public-lookups" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-public-lookups"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-public-lookups"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-public-lookups" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-public-lookups">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-public-lookups" data-method="GET"
      data-path="api/public/lookups"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-public-lookups', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-public-lookups"
                    onclick="tryItOut('GETapi-public-lookups');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-public-lookups"
                    onclick="cancelTryOut('GETapi-public-lookups');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-public-lookups"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/public/lookups</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-public-lookups"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-public-lookups"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="public-GETapi-public-locations">List active public pickup/dropoff locations.</h2>

<p>
</p>



<span id="example-requests-GETapi-public-locations">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/public/locations" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/public/locations"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-public-locations">
    </span>
<span id="execution-results-GETapi-public-locations" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-public-locations"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-public-locations"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-public-locations" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-public-locations">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-public-locations" data-method="GET"
      data-path="api/public/locations"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-public-locations', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-public-locations"
                    onclick="tryItOut('GETapi-public-locations');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-public-locations"
                    onclick="cancelTryOut('GETapi-public-locations');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-public-locations"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/public/locations</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-public-locations"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-public-locations"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="public-GETapi-public-vehicles">List active vehicles for the public website.</h2>

<p>
</p>



<span id="example-requests-GETapi-public-vehicles">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/public/vehicles" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/public/vehicles"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-public-vehicles">
    </span>
<span id="execution-results-GETapi-public-vehicles" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-public-vehicles"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-public-vehicles"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-public-vehicles" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-public-vehicles">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-public-vehicles" data-method="GET"
      data-path="api/public/vehicles"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-public-vehicles', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-public-vehicles"
                    onclick="tryItOut('GETapi-public-vehicles');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-public-vehicles"
                    onclick="cancelTryOut('GETapi-public-vehicles');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-public-vehicles"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/public/vehicles</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-public-vehicles"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-public-vehicles"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="public-GETapi-public-vehicles--vehicle_id--availability">Check availability for an active vehicle.</h2>

<p>
</p>



<span id="example-requests-GETapi-public-vehicles--vehicle_id--availability">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/public/vehicles/17/availability?start_datetime=2026-07-01+10%3A00%3A00&amp;end_datetime=2026-07-05+10%3A00%3A00" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"start_datetime\": \"2026-06-10T20:50:28\",
    \"end_datetime\": \"2107-07-10\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/public/vehicles/17/availability"
);

const params = {
    "start_datetime": "2026-07-01 10:00:00",
    "end_datetime": "2026-07-05 10:00:00",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "start_datetime": "2026-06-10T20:50:28",
    "end_datetime": "2107-07-10"
};

fetch(url, {
    method: "GET",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-public-vehicles--vehicle_id--availability">
    </span>
<span id="execution-results-GETapi-public-vehicles--vehicle_id--availability" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-public-vehicles--vehicle_id--availability"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-public-vehicles--vehicle_id--availability"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-public-vehicles--vehicle_id--availability" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-public-vehicles--vehicle_id--availability">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-public-vehicles--vehicle_id--availability" data-method="GET"
      data-path="api/public/vehicles/{vehicle_id}/availability"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-public-vehicles--vehicle_id--availability', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-public-vehicles--vehicle_id--availability"
                    onclick="tryItOut('GETapi-public-vehicles--vehicle_id--availability');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-public-vehicles--vehicle_id--availability"
                    onclick="cancelTryOut('GETapi-public-vehicles--vehicle_id--availability');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-public-vehicles--vehicle_id--availability"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/public/vehicles/{vehicle_id}/availability</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-public-vehicles--vehicle_id--availability"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-public-vehicles--vehicle_id--availability"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>vehicle_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="vehicle_id"                data-endpoint="GETapi-public-vehicles--vehicle_id--availability"
               value="17"
               data-component="url">
    <br>
<p>The ID of the vehicle. Example: <code>17</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>start_datetime</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="start_datetime"                data-endpoint="GETapi-public-vehicles--vehicle_id--availability"
               value="2026-07-01 10:00:00"
               data-component="query">
    <br>
<p>Start datetime. Example: <code>2026-07-01 10:00:00</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>end_datetime</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="end_datetime"                data-endpoint="GETapi-public-vehicles--vehicle_id--availability"
               value="2026-07-05 10:00:00"
               data-component="query">
    <br>
<p>End datetime. Example: <code>2026-07-05 10:00:00</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>start_datetime</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="start_datetime"                data-endpoint="GETapi-public-vehicles--vehicle_id--availability"
               value="2026-06-10T20:50:28"
               data-component="body">
    <br>
<p>Must be a valid date. Example: <code>2026-06-10T20:50:28</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>end_datetime</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="end_datetime"                data-endpoint="GETapi-public-vehicles--vehicle_id--availability"
               value="2107-07-10"
               data-component="body">
    <br>
<p>Must be a valid date. Must be a date after <code>start_datetime</code>. Example: <code>2107-07-10</code></p>
        </div>
        </form>

                    <h2 id="public-GETapi-public-vehicles--slug-">Show an active vehicle by slug.</h2>

<p>
</p>



<span id="example-requests-GETapi-public-vehicles--slug-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/public/vehicles/17" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/public/vehicles/17"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-public-vehicles--slug-">
    </span>
<span id="execution-results-GETapi-public-vehicles--slug-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-public-vehicles--slug-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-public-vehicles--slug-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-public-vehicles--slug-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-public-vehicles--slug-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-public-vehicles--slug-" data-method="GET"
      data-path="api/public/vehicles/{slug}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-public-vehicles--slug-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-public-vehicles--slug-"
                    onclick="tryItOut('GETapi-public-vehicles--slug-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-public-vehicles--slug-"
                    onclick="cancelTryOut('GETapi-public-vehicles--slug-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-public-vehicles--slug-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/public/vehicles/{slug}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-public-vehicles--slug-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-public-vehicles--slug-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>slug</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="slug"                data-endpoint="GETapi-public-vehicles--slug-"
               value="17"
               data-component="url">
    <br>
<p>The slug of the vehicle. Example: <code>17</code></p>
            </div>
                    </form>

                    <h2 id="public-POSTapi-public-reservations">Create a public pending reservation request.</h2>

<p>
</p>



<span id="example-requests-POSTapi-public-reservations">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/public/reservations" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"customer\": {
        \"full_name\": \"Ahmed Dakhla\",
        \"nationality\": \"Moroccan\",
        \"phone\": \"+212600000000\",
        \"email\": \"customer@example.com\",
        \"passport_or_cin\": \"AA123456\",
        \"driving_license_number\": \"DL-2026-001\"
    },
    \"vehicle_id\": 1,
    \"pickup_location_id\": 1,
    \"dropoff_location_id\": 2,
    \"start_datetime\": \"2026-07-01 10:00:00\",
    \"end_datetime\": \"2026-07-05 10:00:00\",
    \"customer_notes\": \"Airport pickup please.\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/public/reservations"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "customer": {
        "full_name": "Ahmed Dakhla",
        "nationality": "Moroccan",
        "phone": "+212600000000",
        "email": "customer@example.com",
        "passport_or_cin": "AA123456",
        "driving_license_number": "DL-2026-001"
    },
    "vehicle_id": 1,
    "pickup_location_id": 1,
    "dropoff_location_id": 2,
    "start_datetime": "2026-07-01 10:00:00",
    "end_datetime": "2026-07-05 10:00:00",
    "customer_notes": "Airport pickup please."
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-public-reservations">
</span>
<span id="execution-results-POSTapi-public-reservations" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-public-reservations"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-public-reservations"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-public-reservations" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-public-reservations">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-public-reservations" data-method="POST"
      data-path="api/public/reservations"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-public-reservations', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-public-reservations"
                    onclick="tryItOut('POSTapi-public-reservations');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-public-reservations"
                    onclick="cancelTryOut('POSTapi-public-reservations');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-public-reservations"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/public/reservations</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-public-reservations"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-public-reservations"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>customer</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
<br>

            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>full_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="customer.full_name"                data-endpoint="POSTapi-public-reservations"
               value="Ahmed Dakhla"
               data-component="body">
    <br>
<p>Customer full name. Example: <code>Ahmed Dakhla</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>nationality</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="customer.nationality"                data-endpoint="POSTapi-public-reservations"
               value="Moroccan"
               data-component="body">
    <br>
<p>Customer nationality. Example: <code>Moroccan</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>phone</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="customer.phone"                data-endpoint="POSTapi-public-reservations"
               value="+212600000000"
               data-component="body">
    <br>
<p>Customer phone number. Example: <code>+212600000000</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="customer.email"                data-endpoint="POSTapi-public-reservations"
               value="customer@example.com"
               data-component="body">
    <br>
<p>optional Customer email. Example: <code>customer@example.com</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>passport_or_cin</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="customer.passport_or_cin"                data-endpoint="POSTapi-public-reservations"
               value="AA123456"
               data-component="body">
    <br>
<p>optional Passport or CIN. Example: <code>AA123456</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>driving_license_number</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="customer.driving_license_number"                data-endpoint="POSTapi-public-reservations"
               value="DL-2026-001"
               data-component="body">
    <br>
<p>optional Driving license number. Example: <code>DL-2026-001</code></p>
                    </div>
                                    </details>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>vehicle_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="vehicle_id"                data-endpoint="POSTapi-public-reservations"
               value="1"
               data-component="body">
    <br>
<p>Vehicle ID. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>pickup_location_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="pickup_location_id"                data-endpoint="POSTapi-public-reservations"
               value="1"
               data-component="body">
    <br>
<p>Pickup location ID. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>dropoff_location_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="dropoff_location_id"                data-endpoint="POSTapi-public-reservations"
               value="2"
               data-component="body">
    <br>
<p>Dropoff location ID. Example: <code>2</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>start_datetime</code></b>&nbsp;&nbsp;
<small>datetime</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="start_datetime"                data-endpoint="POSTapi-public-reservations"
               value="2026-07-01 10:00:00"
               data-component="body">
    <br>
<p>Reservation start datetime. Example: <code>2026-07-01 10:00:00</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>end_datetime</code></b>&nbsp;&nbsp;
<small>datetime</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="end_datetime"                data-endpoint="POSTapi-public-reservations"
               value="2026-07-05 10:00:00"
               data-component="body">
    <br>
<p>Reservation end datetime. Example: <code>2026-07-05 10:00:00</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>customer_notes</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="customer_notes"                data-endpoint="POSTapi-public-reservations"
               value="Airport pickup please."
               data-component="body">
    <br>
<p>optional Customer notes. Example: <code>Airport pickup please.</code></p>
        </div>
        </form>

                    <h2 id="public-POSTapi-public-reservations-check-availability">Check vehicle availability for public visitors.</h2>

<p>
</p>



<span id="example-requests-POSTapi-public-reservations-check-availability">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/public/reservations/check-availability" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"vehicle_id\": 1,
    \"start_datetime\": \"2026-07-01 10:00:00\",
    \"end_datetime\": \"2026-07-05 10:00:00\",
    \"ignore_reservation_id\": 17
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/public/reservations/check-availability"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "vehicle_id": 1,
    "start_datetime": "2026-07-01 10:00:00",
    "end_datetime": "2026-07-05 10:00:00",
    "ignore_reservation_id": 17
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-public-reservations-check-availability">
</span>
<span id="execution-results-POSTapi-public-reservations-check-availability" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-public-reservations-check-availability"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-public-reservations-check-availability"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-public-reservations-check-availability" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-public-reservations-check-availability">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-public-reservations-check-availability" data-method="POST"
      data-path="api/public/reservations/check-availability"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-public-reservations-check-availability', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-public-reservations-check-availability"
                    onclick="tryItOut('POSTapi-public-reservations-check-availability');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-public-reservations-check-availability"
                    onclick="cancelTryOut('POSTapi-public-reservations-check-availability');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-public-reservations-check-availability"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/public/reservations/check-availability</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-public-reservations-check-availability"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-public-reservations-check-availability"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>vehicle_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="vehicle_id"                data-endpoint="POSTapi-public-reservations-check-availability"
               value="1"
               data-component="body">
    <br>
<p>Vehicle ID to check. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>start_datetime</code></b>&nbsp;&nbsp;
<small>datetime</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="start_datetime"                data-endpoint="POSTapi-public-reservations-check-availability"
               value="2026-07-01 10:00:00"
               data-component="body">
    <br>
<p>Start datetime. Example: <code>2026-07-01 10:00:00</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>end_datetime</code></b>&nbsp;&nbsp;
<small>datetime</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="end_datetime"                data-endpoint="POSTapi-public-reservations-check-availability"
               value="2026-07-05 10:00:00"
               data-component="body">
    <br>
<p>End datetime. Example: <code>2026-07-05 10:00:00</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>ignore_reservation_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="ignore_reservation_id"                data-endpoint="POSTapi-public-reservations-check-availability"
               value="17"
               data-component="body">
    <br>
<p>Must match an existing stored value. Example: <code>17</code></p>
        </div>
        </form>

                    <h2 id="public-GETapi-admin-lookups">Admin lookup data.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires an authenticated admin token.</p>

<span id="example-requests-GETapi-admin-lookups">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/lookups" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/lookups"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-lookups">
    </span>
<span id="execution-results-GETapi-admin-lookups" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-lookups"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-lookups"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-lookups" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-lookups">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-lookups" data-method="GET"
      data-path="api/admin/lookups"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-lookups', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-lookups"
                    onclick="tryItOut('GETapi-admin-lookups');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-lookups"
                    onclick="cancelTryOut('GETapi-admin-lookups');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-lookups"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/lookups</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-lookups"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-lookups"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-lookups"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                <h1 id="reservations">Reservations</h1>

    <p>Admin reservation endpoints. Requires the matching <code>reservations.*</code> permission listed on each endpoint.</p>

                                <h2 id="reservations-GETapi-admin-reservations">List reservations for the admin dashboard.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>reservations.view</code>.</p>

<span id="example-requests-GETapi-admin-reservations">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/reservations" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/reservations"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-reservations">
    </span>
<span id="execution-results-GETapi-admin-reservations" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-reservations"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-reservations"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-reservations" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-reservations">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-reservations" data-method="GET"
      data-path="api/admin/reservations"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-reservations', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-reservations"
                    onclick="tryItOut('GETapi-admin-reservations');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-reservations"
                    onclick="cancelTryOut('GETapi-admin-reservations');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-reservations"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/reservations</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-reservations"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-reservations"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-reservations"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="reservations-POSTapi-admin-reservations">Store a manually created admin reservation.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>reservations.create</code>.</p>

<span id="example-requests-POSTapi-admin-reservations">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/admin/reservations" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"customer_id\": 1,
    \"vehicle_id\": 1,
    \"pickup_location_id\": 1,
    \"dropoff_location_id\": 2,
    \"start_datetime\": \"2026-07-01 10:00:00\",
    \"end_datetime\": \"2026-07-05 10:00:00\",
    \"customer_notes\": \"Airport pickup.\",
    \"admin_notes\": \"Confirm license before pickup.\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/reservations"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "customer_id": 1,
    "vehicle_id": 1,
    "pickup_location_id": 1,
    "dropoff_location_id": 2,
    "start_datetime": "2026-07-01 10:00:00",
    "end_datetime": "2026-07-05 10:00:00",
    "customer_notes": "Airport pickup.",
    "admin_notes": "Confirm license before pickup."
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-admin-reservations">
</span>
<span id="execution-results-POSTapi-admin-reservations" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-admin-reservations"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-reservations"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-admin-reservations" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-reservations">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-admin-reservations" data-method="POST"
      data-path="api/admin/reservations"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-reservations', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-admin-reservations"
                    onclick="tryItOut('POSTapi-admin-reservations');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-admin-reservations"
                    onclick="cancelTryOut('POSTapi-admin-reservations');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-admin-reservations"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/admin/reservations</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-admin-reservations"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-admin-reservations"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-admin-reservations"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>customer_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="customer_id"                data-endpoint="POSTapi-admin-reservations"
               value="1"
               data-component="body">
    <br>
<p>Existing customer ID. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>vehicle_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="vehicle_id"                data-endpoint="POSTapi-admin-reservations"
               value="1"
               data-component="body">
    <br>
<p>Existing vehicle ID. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>pickup_location_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="pickup_location_id"                data-endpoint="POSTapi-admin-reservations"
               value="1"
               data-component="body">
    <br>
<p>Pickup location ID. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>dropoff_location_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="dropoff_location_id"                data-endpoint="POSTapi-admin-reservations"
               value="2"
               data-component="body">
    <br>
<p>Dropoff location ID. Example: <code>2</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>start_datetime</code></b>&nbsp;&nbsp;
<small>datetime</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="start_datetime"                data-endpoint="POSTapi-admin-reservations"
               value="2026-07-01 10:00:00"
               data-component="body">
    <br>
<p>Reservation start datetime. Example: <code>2026-07-01 10:00:00</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>end_datetime</code></b>&nbsp;&nbsp;
<small>datetime</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="end_datetime"                data-endpoint="POSTapi-admin-reservations"
               value="2026-07-05 10:00:00"
               data-component="body">
    <br>
<p>Reservation end datetime. Example: <code>2026-07-05 10:00:00</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>customer_notes</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="customer_notes"                data-endpoint="POSTapi-admin-reservations"
               value="Airport pickup."
               data-component="body">
    <br>
<p>optional Customer-facing notes. Example: <code>Airport pickup.</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>admin_notes</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="admin_notes"                data-endpoint="POSTapi-admin-reservations"
               value="Confirm license before pickup."
               data-component="body">
    <br>
<p>optional Internal admin notes. Example: <code>Confirm license before pickup.</code></p>
        </div>
        </form>

                    <h2 id="reservations-POSTapi-admin-reservations-check-availability">Check vehicle availability for admin workflows.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>reservations.view</code>.</p>

<span id="example-requests-POSTapi-admin-reservations-check-availability">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/admin/reservations/check-availability" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"vehicle_id\": 1,
    \"start_datetime\": \"2026-07-01 10:00:00\",
    \"end_datetime\": \"2026-07-05 10:00:00\",
    \"ignore_reservation_id\": 10
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/reservations/check-availability"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "vehicle_id": 1,
    "start_datetime": "2026-07-01 10:00:00",
    "end_datetime": "2026-07-05 10:00:00",
    "ignore_reservation_id": 10
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-admin-reservations-check-availability">
</span>
<span id="execution-results-POSTapi-admin-reservations-check-availability" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-admin-reservations-check-availability"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-reservations-check-availability"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-admin-reservations-check-availability" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-reservations-check-availability">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-admin-reservations-check-availability" data-method="POST"
      data-path="api/admin/reservations/check-availability"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-reservations-check-availability', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-admin-reservations-check-availability"
                    onclick="tryItOut('POSTapi-admin-reservations-check-availability');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-admin-reservations-check-availability"
                    onclick="cancelTryOut('POSTapi-admin-reservations-check-availability');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-admin-reservations-check-availability"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/admin/reservations/check-availability</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-admin-reservations-check-availability"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-admin-reservations-check-availability"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-admin-reservations-check-availability"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>vehicle_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="vehicle_id"                data-endpoint="POSTapi-admin-reservations-check-availability"
               value="1"
               data-component="body">
    <br>
<p>Vehicle ID to check. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>start_datetime</code></b>&nbsp;&nbsp;
<small>datetime</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="start_datetime"                data-endpoint="POSTapi-admin-reservations-check-availability"
               value="2026-07-01 10:00:00"
               data-component="body">
    <br>
<p>Start datetime. Example: <code>2026-07-01 10:00:00</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>end_datetime</code></b>&nbsp;&nbsp;
<small>datetime</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="end_datetime"                data-endpoint="POSTapi-admin-reservations-check-availability"
               value="2026-07-05 10:00:00"
               data-component="body">
    <br>
<p>End datetime. Example: <code>2026-07-05 10:00:00</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>ignore_reservation_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="ignore_reservation_id"                data-endpoint="POSTapi-admin-reservations-check-availability"
               value="10"
               data-component="body">
    <br>
<p>optional Reservation ID to ignore when updating. Example: <code>10</code></p>
        </div>
        </form>

                    <h2 id="reservations-GETapi-admin-reservations-calendar">Return reservations in a lightweight calendar collection.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>reservations.view</code>.</p>

<span id="example-requests-GETapi-admin-reservations-calendar">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/reservations-calendar?start=2026-07-01&amp;end=2026-07-31" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/reservations-calendar"
);

const params = {
    "start": "2026-07-01",
    "end": "2026-07-31",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-reservations-calendar">
    </span>
<span id="execution-results-GETapi-admin-reservations-calendar" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-reservations-calendar"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-reservations-calendar"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-reservations-calendar" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-reservations-calendar">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-reservations-calendar" data-method="GET"
      data-path="api/admin/reservations-calendar"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-reservations-calendar', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-reservations-calendar"
                    onclick="tryItOut('GETapi-admin-reservations-calendar');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-reservations-calendar"
                    onclick="cancelTryOut('GETapi-admin-reservations-calendar');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-reservations-calendar"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/reservations-calendar</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-reservations-calendar"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-reservations-calendar"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-reservations-calendar"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>start</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="start"                data-endpoint="GETapi-admin-reservations-calendar"
               value="2026-07-01"
               data-component="query">
    <br>
<p>date optional Include reservations ending on or after this date. Example: <code>2026-07-01</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>end</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="end"                data-endpoint="GETapi-admin-reservations-calendar"
               value="2026-07-31"
               data-component="query">
    <br>
<p>date optional Include reservations starting on or before this date. Example: <code>2026-07-31</code></p>
            </div>
                </form>

                    <h2 id="reservations-GETapi-admin-reservations--reservation_id-">Display a reservation.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>reservations.view</code>.</p>

<span id="example-requests-GETapi-admin-reservations--reservation_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/reservations/17" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/reservations/17"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-reservations--reservation_id-">
    </span>
<span id="execution-results-GETapi-admin-reservations--reservation_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-reservations--reservation_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-reservations--reservation_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-reservations--reservation_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-reservations--reservation_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-reservations--reservation_id-" data-method="GET"
      data-path="api/admin/reservations/{reservation_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-reservations--reservation_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-reservations--reservation_id-"
                    onclick="tryItOut('GETapi-admin-reservations--reservation_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-reservations--reservation_id-"
                    onclick="cancelTryOut('GETapi-admin-reservations--reservation_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-reservations--reservation_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/reservations/{reservation_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-reservations--reservation_id-"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-reservations--reservation_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-reservations--reservation_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>reservation_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="reservation_id"                data-endpoint="GETapi-admin-reservations--reservation_id-"
               value="17"
               data-component="url">
    <br>
<p>The ID of the reservation. Example: <code>17</code></p>
            </div>
                    </form>

                    <h2 id="reservations-PUTapi-admin-reservations--reservation_id-">Update a reservation and recalculate pricing when booking inputs change.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>reservations.update</code>.</p>

<span id="example-requests-PUTapi-admin-reservations--reservation_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost/api/admin/reservations/17" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"customer_id\": 17,
    \"vehicle_id\": 1,
    \"pickup_location_id\": 1,
    \"dropoff_location_id\": 2,
    \"start_datetime\": \"2026-07-02 10:00:00\",
    \"end_datetime\": \"2026-07-06 10:00:00\",
    \"customer_notes\": \"consequatur\",
    \"admin_notes\": \"Updated pickup time.\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/reservations/17"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "customer_id": 17,
    "vehicle_id": 1,
    "pickup_location_id": 1,
    "dropoff_location_id": 2,
    "start_datetime": "2026-07-02 10:00:00",
    "end_datetime": "2026-07-06 10:00:00",
    "customer_notes": "consequatur",
    "admin_notes": "Updated pickup time."
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-admin-reservations--reservation_id-">
</span>
<span id="execution-results-PUTapi-admin-reservations--reservation_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-admin-reservations--reservation_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-admin-reservations--reservation_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-admin-reservations--reservation_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-admin-reservations--reservation_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-admin-reservations--reservation_id-" data-method="PUT"
      data-path="api/admin/reservations/{reservation_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-admin-reservations--reservation_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-admin-reservations--reservation_id-"
                    onclick="tryItOut('PUTapi-admin-reservations--reservation_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-admin-reservations--reservation_id-"
                    onclick="cancelTryOut('PUTapi-admin-reservations--reservation_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-admin-reservations--reservation_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/admin/reservations/{reservation_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-admin-reservations--reservation_id-"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-admin-reservations--reservation_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-admin-reservations--reservation_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>reservation_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="reservation_id"                data-endpoint="PUTapi-admin-reservations--reservation_id-"
               value="17"
               data-component="url">
    <br>
<p>The ID of the reservation. Example: <code>17</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>customer_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="customer_id"                data-endpoint="PUTapi-admin-reservations--reservation_id-"
               value="17"
               data-component="body">
    <br>
<p>Must match an existing stored value. Example: <code>17</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>vehicle_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="vehicle_id"                data-endpoint="PUTapi-admin-reservations--reservation_id-"
               value="1"
               data-component="body">
    <br>
<p>optional Existing vehicle ID. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>pickup_location_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="pickup_location_id"                data-endpoint="PUTapi-admin-reservations--reservation_id-"
               value="1"
               data-component="body">
    <br>
<p>optional Pickup location ID. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>dropoff_location_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="dropoff_location_id"                data-endpoint="PUTapi-admin-reservations--reservation_id-"
               value="2"
               data-component="body">
    <br>
<p>optional Dropoff location ID. Example: <code>2</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>start_datetime</code></b>&nbsp;&nbsp;
<small>datetime</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="start_datetime"                data-endpoint="PUTapi-admin-reservations--reservation_id-"
               value="2026-07-02 10:00:00"
               data-component="body">
    <br>
<p>optional Reservation start datetime. Example: <code>2026-07-02 10:00:00</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>end_datetime</code></b>&nbsp;&nbsp;
<small>datetime</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="end_datetime"                data-endpoint="PUTapi-admin-reservations--reservation_id-"
               value="2026-07-06 10:00:00"
               data-component="body">
    <br>
<p>optional Reservation end datetime. Example: <code>2026-07-06 10:00:00</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>customer_notes</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="customer_notes"                data-endpoint="PUTapi-admin-reservations--reservation_id-"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>admin_notes</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="admin_notes"                data-endpoint="PUTapi-admin-reservations--reservation_id-"
               value="Updated pickup time."
               data-component="body">
    <br>
<p>optional Internal admin notes. Example: <code>Updated pickup time.</code></p>
        </div>
        </form>

                    <h2 id="reservations-PATCHapi-admin-reservations--reservation_id-">Update a reservation and recalculate pricing when booking inputs change.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>reservations.update</code>.</p>

<span id="example-requests-PATCHapi-admin-reservations--reservation_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PATCH \
    "http://localhost/api/admin/reservations/17" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"customer_id\": 17,
    \"vehicle_id\": 1,
    \"pickup_location_id\": 1,
    \"dropoff_location_id\": 2,
    \"start_datetime\": \"2026-07-02 10:00:00\",
    \"end_datetime\": \"2026-07-06 10:00:00\",
    \"customer_notes\": \"consequatur\",
    \"admin_notes\": \"Updated pickup time.\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/reservations/17"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "customer_id": 17,
    "vehicle_id": 1,
    "pickup_location_id": 1,
    "dropoff_location_id": 2,
    "start_datetime": "2026-07-02 10:00:00",
    "end_datetime": "2026-07-06 10:00:00",
    "customer_notes": "consequatur",
    "admin_notes": "Updated pickup time."
};

fetch(url, {
    method: "PATCH",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PATCHapi-admin-reservations--reservation_id-">
</span>
<span id="execution-results-PATCHapi-admin-reservations--reservation_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PATCHapi-admin-reservations--reservation_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PATCHapi-admin-reservations--reservation_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PATCHapi-admin-reservations--reservation_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PATCHapi-admin-reservations--reservation_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PATCHapi-admin-reservations--reservation_id-" data-method="PATCH"
      data-path="api/admin/reservations/{reservation_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PATCHapi-admin-reservations--reservation_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PATCHapi-admin-reservations--reservation_id-"
                    onclick="tryItOut('PATCHapi-admin-reservations--reservation_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PATCHapi-admin-reservations--reservation_id-"
                    onclick="cancelTryOut('PATCHapi-admin-reservations--reservation_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PATCHapi-admin-reservations--reservation_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/admin/reservations/{reservation_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PATCHapi-admin-reservations--reservation_id-"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PATCHapi-admin-reservations--reservation_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PATCHapi-admin-reservations--reservation_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>reservation_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="reservation_id"                data-endpoint="PATCHapi-admin-reservations--reservation_id-"
               value="17"
               data-component="url">
    <br>
<p>The ID of the reservation. Example: <code>17</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>customer_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="customer_id"                data-endpoint="PATCHapi-admin-reservations--reservation_id-"
               value="17"
               data-component="body">
    <br>
<p>Must match an existing stored value. Example: <code>17</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>vehicle_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="vehicle_id"                data-endpoint="PATCHapi-admin-reservations--reservation_id-"
               value="1"
               data-component="body">
    <br>
<p>optional Existing vehicle ID. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>pickup_location_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="pickup_location_id"                data-endpoint="PATCHapi-admin-reservations--reservation_id-"
               value="1"
               data-component="body">
    <br>
<p>optional Pickup location ID. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>dropoff_location_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="dropoff_location_id"                data-endpoint="PATCHapi-admin-reservations--reservation_id-"
               value="2"
               data-component="body">
    <br>
<p>optional Dropoff location ID. Example: <code>2</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>start_datetime</code></b>&nbsp;&nbsp;
<small>datetime</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="start_datetime"                data-endpoint="PATCHapi-admin-reservations--reservation_id-"
               value="2026-07-02 10:00:00"
               data-component="body">
    <br>
<p>optional Reservation start datetime. Example: <code>2026-07-02 10:00:00</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>end_datetime</code></b>&nbsp;&nbsp;
<small>datetime</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="end_datetime"                data-endpoint="PATCHapi-admin-reservations--reservation_id-"
               value="2026-07-06 10:00:00"
               data-component="body">
    <br>
<p>optional Reservation end datetime. Example: <code>2026-07-06 10:00:00</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>customer_notes</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="customer_notes"                data-endpoint="PATCHapi-admin-reservations--reservation_id-"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>admin_notes</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="admin_notes"                data-endpoint="PATCHapi-admin-reservations--reservation_id-"
               value="Updated pickup time."
               data-component="body">
    <br>
<p>optional Internal admin notes. Example: <code>Updated pickup time.</code></p>
        </div>
        </form>

                    <h2 id="reservations-DELETEapi-admin-reservations--reservation_id-">Soft delete a reservation.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>reservations.delete</code>.</p>

<span id="example-requests-DELETEapi-admin-reservations--reservation_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost/api/admin/reservations/17" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/reservations/17"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-admin-reservations--reservation_id-">
</span>
<span id="execution-results-DELETEapi-admin-reservations--reservation_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-admin-reservations--reservation_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-admin-reservations--reservation_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-admin-reservations--reservation_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-admin-reservations--reservation_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-admin-reservations--reservation_id-" data-method="DELETE"
      data-path="api/admin/reservations/{reservation_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-admin-reservations--reservation_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-admin-reservations--reservation_id-"
                    onclick="tryItOut('DELETEapi-admin-reservations--reservation_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-admin-reservations--reservation_id-"
                    onclick="cancelTryOut('DELETEapi-admin-reservations--reservation_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-admin-reservations--reservation_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/admin/reservations/{reservation_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="DELETEapi-admin-reservations--reservation_id-"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-admin-reservations--reservation_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-admin-reservations--reservation_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>reservation_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="reservation_id"                data-endpoint="DELETEapi-admin-reservations--reservation_id-"
               value="17"
               data-component="url">
    <br>
<p>The ID of the reservation. Example: <code>17</code></p>
            </div>
                    </form>

                    <h2 id="reservations-POSTapi-admin-reservations--reservation_id--confirm">Confirm a pending reservation and reserve the vehicle.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>reservations.confirm</code>.</p>

<span id="example-requests-POSTapi-admin-reservations--reservation_id--confirm">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/admin/reservations/17/confirm" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/reservations/17/confirm"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-admin-reservations--reservation_id--confirm">
</span>
<span id="execution-results-POSTapi-admin-reservations--reservation_id--confirm" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-admin-reservations--reservation_id--confirm"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-reservations--reservation_id--confirm"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-admin-reservations--reservation_id--confirm" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-reservations--reservation_id--confirm">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-admin-reservations--reservation_id--confirm" data-method="POST"
      data-path="api/admin/reservations/{reservation_id}/confirm"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-reservations--reservation_id--confirm', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-admin-reservations--reservation_id--confirm"
                    onclick="tryItOut('POSTapi-admin-reservations--reservation_id--confirm');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-admin-reservations--reservation_id--confirm"
                    onclick="cancelTryOut('POSTapi-admin-reservations--reservation_id--confirm');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-admin-reservations--reservation_id--confirm"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/admin/reservations/{reservation_id}/confirm</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-admin-reservations--reservation_id--confirm"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-admin-reservations--reservation_id--confirm"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-admin-reservations--reservation_id--confirm"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>reservation_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="reservation_id"                data-endpoint="POSTapi-admin-reservations--reservation_id--confirm"
               value="17"
               data-component="url">
    <br>
<p>The ID of the reservation. Example: <code>17</code></p>
            </div>
                    </form>

                    <h2 id="reservations-POSTapi-admin-reservations--reservation_id--start">Start a confirmed reservation.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>reservations.start</code>.</p>

<span id="example-requests-POSTapi-admin-reservations--reservation_id--start">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/admin/reservations/17/start" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/reservations/17/start"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-admin-reservations--reservation_id--start">
</span>
<span id="execution-results-POSTapi-admin-reservations--reservation_id--start" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-admin-reservations--reservation_id--start"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-reservations--reservation_id--start"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-admin-reservations--reservation_id--start" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-reservations--reservation_id--start">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-admin-reservations--reservation_id--start" data-method="POST"
      data-path="api/admin/reservations/{reservation_id}/start"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-reservations--reservation_id--start', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-admin-reservations--reservation_id--start"
                    onclick="tryItOut('POSTapi-admin-reservations--reservation_id--start');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-admin-reservations--reservation_id--start"
                    onclick="cancelTryOut('POSTapi-admin-reservations--reservation_id--start');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-admin-reservations--reservation_id--start"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/admin/reservations/{reservation_id}/start</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-admin-reservations--reservation_id--start"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-admin-reservations--reservation_id--start"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-admin-reservations--reservation_id--start"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>reservation_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="reservation_id"                data-endpoint="POSTapi-admin-reservations--reservation_id--start"
               value="17"
               data-component="url">
    <br>
<p>The ID of the reservation. Example: <code>17</code></p>
            </div>
                    </form>

                    <h2 id="reservations-POSTapi-admin-reservations--reservation_id--complete">Complete an in-progress reservation.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>reservations.complete</code>.</p>

<span id="example-requests-POSTapi-admin-reservations--reservation_id--complete">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/admin/reservations/17/complete" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/reservations/17/complete"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-admin-reservations--reservation_id--complete">
</span>
<span id="execution-results-POSTapi-admin-reservations--reservation_id--complete" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-admin-reservations--reservation_id--complete"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-reservations--reservation_id--complete"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-admin-reservations--reservation_id--complete" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-reservations--reservation_id--complete">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-admin-reservations--reservation_id--complete" data-method="POST"
      data-path="api/admin/reservations/{reservation_id}/complete"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-reservations--reservation_id--complete', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-admin-reservations--reservation_id--complete"
                    onclick="tryItOut('POSTapi-admin-reservations--reservation_id--complete');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-admin-reservations--reservation_id--complete"
                    onclick="cancelTryOut('POSTapi-admin-reservations--reservation_id--complete');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-admin-reservations--reservation_id--complete"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/admin/reservations/{reservation_id}/complete</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-admin-reservations--reservation_id--complete"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-admin-reservations--reservation_id--complete"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-admin-reservations--reservation_id--complete"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>reservation_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="reservation_id"                data-endpoint="POSTapi-admin-reservations--reservation_id--complete"
               value="17"
               data-component="url">
    <br>
<p>The ID of the reservation. Example: <code>17</code></p>
            </div>
                    </form>

                    <h2 id="reservations-POSTapi-admin-reservations--reservation_id--cancel">Cancel a reservation and free reserved/rented vehicles.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>reservations.cancel</code>.</p>

<span id="example-requests-POSTapi-admin-reservations--reservation_id--cancel">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/admin/reservations/17/cancel" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/reservations/17/cancel"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-admin-reservations--reservation_id--cancel">
</span>
<span id="execution-results-POSTapi-admin-reservations--reservation_id--cancel" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-admin-reservations--reservation_id--cancel"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-reservations--reservation_id--cancel"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-admin-reservations--reservation_id--cancel" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-reservations--reservation_id--cancel">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-admin-reservations--reservation_id--cancel" data-method="POST"
      data-path="api/admin/reservations/{reservation_id}/cancel"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-reservations--reservation_id--cancel', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-admin-reservations--reservation_id--cancel"
                    onclick="tryItOut('POSTapi-admin-reservations--reservation_id--cancel');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-admin-reservations--reservation_id--cancel"
                    onclick="cancelTryOut('POSTapi-admin-reservations--reservation_id--cancel');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-admin-reservations--reservation_id--cancel"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/admin/reservations/{reservation_id}/cancel</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-admin-reservations--reservation_id--cancel"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-admin-reservations--reservation_id--cancel"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-admin-reservations--reservation_id--cancel"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>reservation_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="reservation_id"                data-endpoint="POSTapi-admin-reservations--reservation_id--cancel"
               value="17"
               data-component="url">
    <br>
<p>The ID of the reservation. Example: <code>17</code></p>
            </div>
                    </form>

                    <h2 id="reservations-POSTapi-admin-reservations--reservation_id--reject">Reject a pending reservation request.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>reservations.reject</code>.</p>

<span id="example-requests-POSTapi-admin-reservations--reservation_id--reject">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/admin/reservations/17/reject" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/reservations/17/reject"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-admin-reservations--reservation_id--reject">
</span>
<span id="execution-results-POSTapi-admin-reservations--reservation_id--reject" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-admin-reservations--reservation_id--reject"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-reservations--reservation_id--reject"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-admin-reservations--reservation_id--reject" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-reservations--reservation_id--reject">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-admin-reservations--reservation_id--reject" data-method="POST"
      data-path="api/admin/reservations/{reservation_id}/reject"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-reservations--reservation_id--reject', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-admin-reservations--reservation_id--reject"
                    onclick="tryItOut('POSTapi-admin-reservations--reservation_id--reject');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-admin-reservations--reservation_id--reject"
                    onclick="cancelTryOut('POSTapi-admin-reservations--reservation_id--reject');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-admin-reservations--reservation_id--reject"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/admin/reservations/{reservation_id}/reject</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-admin-reservations--reservation_id--reject"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-admin-reservations--reservation_id--reject"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-admin-reservations--reservation_id--reject"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>reservation_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="reservation_id"                data-endpoint="POSTapi-admin-reservations--reservation_id--reject"
               value="17"
               data-component="url">
    <br>
<p>The ID of the reservation. Example: <code>17</code></p>
            </div>
                    </form>

                <h1 id="vehicle-brands">Vehicle Brands</h1>

    <p>Admin vehicle brand endpoints. Requires the matching <code>vehicle_brands.*</code> permission listed on each endpoint.</p>

                                <h2 id="vehicle-brands-GETapi-admin-vehicle-brands">List vehicle brands.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>vehicle_brands.view</code>.</p>

<span id="example-requests-GETapi-admin-vehicle-brands">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/vehicle-brands" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/vehicle-brands"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-vehicle-brands">
    </span>
<span id="execution-results-GETapi-admin-vehicle-brands" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-vehicle-brands"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-vehicle-brands"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-vehicle-brands" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-vehicle-brands">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-vehicle-brands" data-method="GET"
      data-path="api/admin/vehicle-brands"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-vehicle-brands', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-vehicle-brands"
                    onclick="tryItOut('GETapi-admin-vehicle-brands');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-vehicle-brands"
                    onclick="cancelTryOut('GETapi-admin-vehicle-brands');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-vehicle-brands"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/vehicle-brands</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-vehicle-brands"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-vehicle-brands"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-vehicle-brands"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="vehicle-brands-POSTapi-admin-vehicle-brands">Create a vehicle brand.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>vehicle_brands.create</code>.</p>

<span id="example-requests-POSTapi-admin-vehicle-brands">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/admin/vehicle-brands" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Dacia\",
    \"slug\": \"dacia\",
    \"is_active\": true
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/vehicle-brands"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Dacia",
    "slug": "dacia",
    "is_active": true
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-admin-vehicle-brands">
</span>
<span id="execution-results-POSTapi-admin-vehicle-brands" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-admin-vehicle-brands"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-vehicle-brands"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-admin-vehicle-brands" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-vehicle-brands">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-admin-vehicle-brands" data-method="POST"
      data-path="api/admin/vehicle-brands"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-vehicle-brands', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-admin-vehicle-brands"
                    onclick="tryItOut('POSTapi-admin-vehicle-brands');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-admin-vehicle-brands"
                    onclick="cancelTryOut('POSTapi-admin-vehicle-brands');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-admin-vehicle-brands"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/admin/vehicle-brands</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-admin-vehicle-brands"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-admin-vehicle-brands"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-admin-vehicle-brands"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-admin-vehicle-brands"
               value="Dacia"
               data-component="body">
    <br>
<p>Brand name. Example: <code>Dacia</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="slug"                data-endpoint="POSTapi-admin-vehicle-brands"
               value="dacia"
               data-component="body">
    <br>
<p>Unique brand slug. Example: <code>dacia</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_active</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="POSTapi-admin-vehicle-brands" style="display: none">
            <input type="radio" name="is_active"
                   value="true"
                   data-endpoint="POSTapi-admin-vehicle-brands"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-admin-vehicle-brands" style="display: none">
            <input type="radio" name="is_active"
                   value="false"
                   data-endpoint="POSTapi-admin-vehicle-brands"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>optional Whether the brand is active. Example: <code>true</code></p>
        </div>
        </form>

                    <h2 id="vehicle-brands-GETapi-admin-vehicle-brands--brand_id-">Display a vehicle brand.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>vehicle_brands.view</code>.</p>

<span id="example-requests-GETapi-admin-vehicle-brands--brand_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/vehicle-brands/1" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/vehicle-brands/1"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-vehicle-brands--brand_id-">
    </span>
<span id="execution-results-GETapi-admin-vehicle-brands--brand_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-vehicle-brands--brand_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-vehicle-brands--brand_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-vehicle-brands--brand_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-vehicle-brands--brand_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-vehicle-brands--brand_id-" data-method="GET"
      data-path="api/admin/vehicle-brands/{brand_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-vehicle-brands--brand_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-vehicle-brands--brand_id-"
                    onclick="tryItOut('GETapi-admin-vehicle-brands--brand_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-vehicle-brands--brand_id-"
                    onclick="cancelTryOut('GETapi-admin-vehicle-brands--brand_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-vehicle-brands--brand_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/vehicle-brands/{brand_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-vehicle-brands--brand_id-"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-vehicle-brands--brand_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-vehicle-brands--brand_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>brand_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="brand_id"                data-endpoint="GETapi-admin-vehicle-brands--brand_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the brand. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="vehicle-brands-PUTapi-admin-vehicle-brands--brand_id-">Update a vehicle brand.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>vehicle_brands.update</code>.</p>

<span id="example-requests-PUTapi-admin-vehicle-brands--brand_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost/api/admin/vehicle-brands/1" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Toyota\",
    \"slug\": \"toyota\",
    \"is_active\": true
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/vehicle-brands/1"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Toyota",
    "slug": "toyota",
    "is_active": true
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-admin-vehicle-brands--brand_id-">
</span>
<span id="execution-results-PUTapi-admin-vehicle-brands--brand_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-admin-vehicle-brands--brand_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-admin-vehicle-brands--brand_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-admin-vehicle-brands--brand_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-admin-vehicle-brands--brand_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-admin-vehicle-brands--brand_id-" data-method="PUT"
      data-path="api/admin/vehicle-brands/{brand_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-admin-vehicle-brands--brand_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-admin-vehicle-brands--brand_id-"
                    onclick="tryItOut('PUTapi-admin-vehicle-brands--brand_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-admin-vehicle-brands--brand_id-"
                    onclick="cancelTryOut('PUTapi-admin-vehicle-brands--brand_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-admin-vehicle-brands--brand_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/admin/vehicle-brands/{brand_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-admin-vehicle-brands--brand_id-"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-admin-vehicle-brands--brand_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-admin-vehicle-brands--brand_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>brand_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="brand_id"                data-endpoint="PUTapi-admin-vehicle-brands--brand_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the brand. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PUTapi-admin-vehicle-brands--brand_id-"
               value="Toyota"
               data-component="body">
    <br>
<p>optional Brand name. Example: <code>Toyota</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="slug"                data-endpoint="PUTapi-admin-vehicle-brands--brand_id-"
               value="toyota"
               data-component="body">
    <br>
<p>optional Unique brand slug. Example: <code>toyota</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_active</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="PUTapi-admin-vehicle-brands--brand_id-" style="display: none">
            <input type="radio" name="is_active"
                   value="true"
                   data-endpoint="PUTapi-admin-vehicle-brands--brand_id-"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="PUTapi-admin-vehicle-brands--brand_id-" style="display: none">
            <input type="radio" name="is_active"
                   value="false"
                   data-endpoint="PUTapi-admin-vehicle-brands--brand_id-"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>optional Whether the brand is active. Example: <code>true</code></p>
        </div>
        </form>

                    <h2 id="vehicle-brands-PATCHapi-admin-vehicle-brands--brand_id-">Update a vehicle brand.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>vehicle_brands.update</code>.</p>

<span id="example-requests-PATCHapi-admin-vehicle-brands--brand_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PATCH \
    "http://localhost/api/admin/vehicle-brands/1" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Toyota\",
    \"slug\": \"toyota\",
    \"is_active\": true
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/vehicle-brands/1"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Toyota",
    "slug": "toyota",
    "is_active": true
};

fetch(url, {
    method: "PATCH",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PATCHapi-admin-vehicle-brands--brand_id-">
</span>
<span id="execution-results-PATCHapi-admin-vehicle-brands--brand_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PATCHapi-admin-vehicle-brands--brand_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PATCHapi-admin-vehicle-brands--brand_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PATCHapi-admin-vehicle-brands--brand_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PATCHapi-admin-vehicle-brands--brand_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PATCHapi-admin-vehicle-brands--brand_id-" data-method="PATCH"
      data-path="api/admin/vehicle-brands/{brand_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PATCHapi-admin-vehicle-brands--brand_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PATCHapi-admin-vehicle-brands--brand_id-"
                    onclick="tryItOut('PATCHapi-admin-vehicle-brands--brand_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PATCHapi-admin-vehicle-brands--brand_id-"
                    onclick="cancelTryOut('PATCHapi-admin-vehicle-brands--brand_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PATCHapi-admin-vehicle-brands--brand_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/admin/vehicle-brands/{brand_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PATCHapi-admin-vehicle-brands--brand_id-"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PATCHapi-admin-vehicle-brands--brand_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PATCHapi-admin-vehicle-brands--brand_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>brand_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="brand_id"                data-endpoint="PATCHapi-admin-vehicle-brands--brand_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the brand. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PATCHapi-admin-vehicle-brands--brand_id-"
               value="Toyota"
               data-component="body">
    <br>
<p>optional Brand name. Example: <code>Toyota</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="slug"                data-endpoint="PATCHapi-admin-vehicle-brands--brand_id-"
               value="toyota"
               data-component="body">
    <br>
<p>optional Unique brand slug. Example: <code>toyota</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_active</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="PATCHapi-admin-vehicle-brands--brand_id-" style="display: none">
            <input type="radio" name="is_active"
                   value="true"
                   data-endpoint="PATCHapi-admin-vehicle-brands--brand_id-"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="PATCHapi-admin-vehicle-brands--brand_id-" style="display: none">
            <input type="radio" name="is_active"
                   value="false"
                   data-endpoint="PATCHapi-admin-vehicle-brands--brand_id-"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>optional Whether the brand is active. Example: <code>true</code></p>
        </div>
        </form>

                    <h2 id="vehicle-brands-DELETEapi-admin-vehicle-brands--brand_id-">Soft delete a vehicle brand.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>vehicle_brands.delete</code>.</p>

<span id="example-requests-DELETEapi-admin-vehicle-brands--brand_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost/api/admin/vehicle-brands/1" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/vehicle-brands/1"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-admin-vehicle-brands--brand_id-">
</span>
<span id="execution-results-DELETEapi-admin-vehicle-brands--brand_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-admin-vehicle-brands--brand_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-admin-vehicle-brands--brand_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-admin-vehicle-brands--brand_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-admin-vehicle-brands--brand_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-admin-vehicle-brands--brand_id-" data-method="DELETE"
      data-path="api/admin/vehicle-brands/{brand_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-admin-vehicle-brands--brand_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-admin-vehicle-brands--brand_id-"
                    onclick="tryItOut('DELETEapi-admin-vehicle-brands--brand_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-admin-vehicle-brands--brand_id-"
                    onclick="cancelTryOut('DELETEapi-admin-vehicle-brands--brand_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-admin-vehicle-brands--brand_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/admin/vehicle-brands/{brand_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="DELETEapi-admin-vehicle-brands--brand_id-"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-admin-vehicle-brands--brand_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-admin-vehicle-brands--brand_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>brand_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="brand_id"                data-endpoint="DELETEapi-admin-vehicle-brands--brand_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the brand. Example: <code>1</code></p>
            </div>
                    </form>

                <h1 id="vehicle-categories">Vehicle Categories</h1>

    <p>Admin vehicle category endpoints. Requires the matching <code>vehicle_categories.*</code> permission listed on each endpoint.</p>

                                <h2 id="vehicle-categories-GETapi-admin-vehicle-categories">List vehicle categories.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>vehicle_categories.view</code>.</p>

<span id="example-requests-GETapi-admin-vehicle-categories">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/vehicle-categories" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/vehicle-categories"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-vehicle-categories">
    </span>
<span id="execution-results-GETapi-admin-vehicle-categories" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-vehicle-categories"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-vehicle-categories"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-vehicle-categories" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-vehicle-categories">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-vehicle-categories" data-method="GET"
      data-path="api/admin/vehicle-categories"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-vehicle-categories', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-vehicle-categories"
                    onclick="tryItOut('GETapi-admin-vehicle-categories');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-vehicle-categories"
                    onclick="cancelTryOut('GETapi-admin-vehicle-categories');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-vehicle-categories"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/vehicle-categories</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-vehicle-categories"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-vehicle-categories"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-vehicle-categories"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="vehicle-categories-POSTapi-admin-vehicle-categories">Create a vehicle category.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>vehicle_categories.create</code>.</p>

<span id="example-requests-POSTapi-admin-vehicle-categories">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/admin/vehicle-categories" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Economy\",
    \"slug\": \"economy\",
    \"description\": \"Affordable daily rental vehicles.\",
    \"is_active\": true
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/vehicle-categories"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Economy",
    "slug": "economy",
    "description": "Affordable daily rental vehicles.",
    "is_active": true
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-admin-vehicle-categories">
</span>
<span id="execution-results-POSTapi-admin-vehicle-categories" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-admin-vehicle-categories"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-vehicle-categories"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-admin-vehicle-categories" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-vehicle-categories">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-admin-vehicle-categories" data-method="POST"
      data-path="api/admin/vehicle-categories"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-vehicle-categories', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-admin-vehicle-categories"
                    onclick="tryItOut('POSTapi-admin-vehicle-categories');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-admin-vehicle-categories"
                    onclick="cancelTryOut('POSTapi-admin-vehicle-categories');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-admin-vehicle-categories"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/admin/vehicle-categories</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-admin-vehicle-categories"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-admin-vehicle-categories"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-admin-vehicle-categories"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-admin-vehicle-categories"
               value="Economy"
               data-component="body">
    <br>
<p>Category name. Example: <code>Economy</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="slug"                data-endpoint="POSTapi-admin-vehicle-categories"
               value="economy"
               data-component="body">
    <br>
<p>Unique category slug. Example: <code>economy</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="POSTapi-admin-vehicle-categories"
               value="Affordable daily rental vehicles."
               data-component="body">
    <br>
<p>optional Category description. Example: <code>Affordable daily rental vehicles.</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_active</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="POSTapi-admin-vehicle-categories" style="display: none">
            <input type="radio" name="is_active"
                   value="true"
                   data-endpoint="POSTapi-admin-vehicle-categories"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-admin-vehicle-categories" style="display: none">
            <input type="radio" name="is_active"
                   value="false"
                   data-endpoint="POSTapi-admin-vehicle-categories"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>optional Whether the category is active. Example: <code>true</code></p>
        </div>
        </form>

                    <h2 id="vehicle-categories-GETapi-admin-vehicle-categories--category_id-">Display a vehicle category.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>vehicle_categories.view</code>.</p>

<span id="example-requests-GETapi-admin-vehicle-categories--category_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/vehicle-categories/1" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/vehicle-categories/1"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-vehicle-categories--category_id-">
    </span>
<span id="execution-results-GETapi-admin-vehicle-categories--category_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-vehicle-categories--category_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-vehicle-categories--category_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-vehicle-categories--category_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-vehicle-categories--category_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-vehicle-categories--category_id-" data-method="GET"
      data-path="api/admin/vehicle-categories/{category_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-vehicle-categories--category_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-vehicle-categories--category_id-"
                    onclick="tryItOut('GETapi-admin-vehicle-categories--category_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-vehicle-categories--category_id-"
                    onclick="cancelTryOut('GETapi-admin-vehicle-categories--category_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-vehicle-categories--category_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/vehicle-categories/{category_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-vehicle-categories--category_id-"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-vehicle-categories--category_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-vehicle-categories--category_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>category_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="category_id"                data-endpoint="GETapi-admin-vehicle-categories--category_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the category. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="vehicle-categories-PUTapi-admin-vehicle-categories--category_id-">Update a vehicle category.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>vehicle_categories.update</code>.</p>

<span id="example-requests-PUTapi-admin-vehicle-categories--category_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost/api/admin/vehicle-categories/1" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"SUV\",
    \"slug\": \"suv\",
    \"description\": \"High clearance vehicles.\",
    \"is_active\": true
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/vehicle-categories/1"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "SUV",
    "slug": "suv",
    "description": "High clearance vehicles.",
    "is_active": true
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-admin-vehicle-categories--category_id-">
</span>
<span id="execution-results-PUTapi-admin-vehicle-categories--category_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-admin-vehicle-categories--category_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-admin-vehicle-categories--category_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-admin-vehicle-categories--category_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-admin-vehicle-categories--category_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-admin-vehicle-categories--category_id-" data-method="PUT"
      data-path="api/admin/vehicle-categories/{category_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-admin-vehicle-categories--category_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-admin-vehicle-categories--category_id-"
                    onclick="tryItOut('PUTapi-admin-vehicle-categories--category_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-admin-vehicle-categories--category_id-"
                    onclick="cancelTryOut('PUTapi-admin-vehicle-categories--category_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-admin-vehicle-categories--category_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/admin/vehicle-categories/{category_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-admin-vehicle-categories--category_id-"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-admin-vehicle-categories--category_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-admin-vehicle-categories--category_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>category_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="category_id"                data-endpoint="PUTapi-admin-vehicle-categories--category_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the category. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PUTapi-admin-vehicle-categories--category_id-"
               value="SUV"
               data-component="body">
    <br>
<p>optional Category name. Example: <code>SUV</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="slug"                data-endpoint="PUTapi-admin-vehicle-categories--category_id-"
               value="suv"
               data-component="body">
    <br>
<p>optional Unique category slug. Example: <code>suv</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="PUTapi-admin-vehicle-categories--category_id-"
               value="High clearance vehicles."
               data-component="body">
    <br>
<p>optional Category description. Example: <code>High clearance vehicles.</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_active</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="PUTapi-admin-vehicle-categories--category_id-" style="display: none">
            <input type="radio" name="is_active"
                   value="true"
                   data-endpoint="PUTapi-admin-vehicle-categories--category_id-"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="PUTapi-admin-vehicle-categories--category_id-" style="display: none">
            <input type="radio" name="is_active"
                   value="false"
                   data-endpoint="PUTapi-admin-vehicle-categories--category_id-"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>optional Whether the category is active. Example: <code>true</code></p>
        </div>
        </form>

                    <h2 id="vehicle-categories-PATCHapi-admin-vehicle-categories--category_id-">Update a vehicle category.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>vehicle_categories.update</code>.</p>

<span id="example-requests-PATCHapi-admin-vehicle-categories--category_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PATCH \
    "http://localhost/api/admin/vehicle-categories/1" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"SUV\",
    \"slug\": \"suv\",
    \"description\": \"High clearance vehicles.\",
    \"is_active\": true
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/vehicle-categories/1"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "SUV",
    "slug": "suv",
    "description": "High clearance vehicles.",
    "is_active": true
};

fetch(url, {
    method: "PATCH",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PATCHapi-admin-vehicle-categories--category_id-">
</span>
<span id="execution-results-PATCHapi-admin-vehicle-categories--category_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PATCHapi-admin-vehicle-categories--category_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PATCHapi-admin-vehicle-categories--category_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PATCHapi-admin-vehicle-categories--category_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PATCHapi-admin-vehicle-categories--category_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PATCHapi-admin-vehicle-categories--category_id-" data-method="PATCH"
      data-path="api/admin/vehicle-categories/{category_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PATCHapi-admin-vehicle-categories--category_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PATCHapi-admin-vehicle-categories--category_id-"
                    onclick="tryItOut('PATCHapi-admin-vehicle-categories--category_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PATCHapi-admin-vehicle-categories--category_id-"
                    onclick="cancelTryOut('PATCHapi-admin-vehicle-categories--category_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PATCHapi-admin-vehicle-categories--category_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/admin/vehicle-categories/{category_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PATCHapi-admin-vehicle-categories--category_id-"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PATCHapi-admin-vehicle-categories--category_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PATCHapi-admin-vehicle-categories--category_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>category_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="category_id"                data-endpoint="PATCHapi-admin-vehicle-categories--category_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the category. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PATCHapi-admin-vehicle-categories--category_id-"
               value="SUV"
               data-component="body">
    <br>
<p>optional Category name. Example: <code>SUV</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="slug"                data-endpoint="PATCHapi-admin-vehicle-categories--category_id-"
               value="suv"
               data-component="body">
    <br>
<p>optional Unique category slug. Example: <code>suv</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="PATCHapi-admin-vehicle-categories--category_id-"
               value="High clearance vehicles."
               data-component="body">
    <br>
<p>optional Category description. Example: <code>High clearance vehicles.</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_active</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="PATCHapi-admin-vehicle-categories--category_id-" style="display: none">
            <input type="radio" name="is_active"
                   value="true"
                   data-endpoint="PATCHapi-admin-vehicle-categories--category_id-"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="PATCHapi-admin-vehicle-categories--category_id-" style="display: none">
            <input type="radio" name="is_active"
                   value="false"
                   data-endpoint="PATCHapi-admin-vehicle-categories--category_id-"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>optional Whether the category is active. Example: <code>true</code></p>
        </div>
        </form>

                    <h2 id="vehicle-categories-DELETEapi-admin-vehicle-categories--category_id-">Soft delete a vehicle category.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>vehicle_categories.delete</code>.</p>

<span id="example-requests-DELETEapi-admin-vehicle-categories--category_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost/api/admin/vehicle-categories/1" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/vehicle-categories/1"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-admin-vehicle-categories--category_id-">
</span>
<span id="execution-results-DELETEapi-admin-vehicle-categories--category_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-admin-vehicle-categories--category_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-admin-vehicle-categories--category_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-admin-vehicle-categories--category_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-admin-vehicle-categories--category_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-admin-vehicle-categories--category_id-" data-method="DELETE"
      data-path="api/admin/vehicle-categories/{category_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-admin-vehicle-categories--category_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-admin-vehicle-categories--category_id-"
                    onclick="tryItOut('DELETEapi-admin-vehicle-categories--category_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-admin-vehicle-categories--category_id-"
                    onclick="cancelTryOut('DELETEapi-admin-vehicle-categories--category_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-admin-vehicle-categories--category_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/admin/vehicle-categories/{category_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="DELETEapi-admin-vehicle-categories--category_id-"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-admin-vehicle-categories--category_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-admin-vehicle-categories--category_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>category_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="category_id"                data-endpoint="DELETEapi-admin-vehicle-categories--category_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the category. Example: <code>1</code></p>
            </div>
                    </form>

                <h1 id="vehicles">Vehicles</h1>

    <p>Admin vehicle inventory endpoints. Requires the matching <code>vehicles.*</code> permission listed on each endpoint.</p>

                                <h2 id="vehicles-GETapi-admin-vehicles">List vehicles for the admin dashboard.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>vehicles.view</code>.</p>

<span id="example-requests-GETapi-admin-vehicles">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/vehicles" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/vehicles"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-vehicles">
    </span>
<span id="execution-results-GETapi-admin-vehicles" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-vehicles"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-vehicles"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-vehicles" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-vehicles">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-vehicles" data-method="GET"
      data-path="api/admin/vehicles"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-vehicles', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-vehicles"
                    onclick="tryItOut('GETapi-admin-vehicles');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-vehicles"
                    onclick="cancelTryOut('GETapi-admin-vehicles');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-vehicles"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/vehicles</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-vehicles"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-vehicles"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-vehicles"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="vehicles-POSTapi-admin-vehicles">Store a new vehicle.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>vehicles.create</code>.</p>

<span id="example-requests-POSTapi-admin-vehicles">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/admin/vehicles" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"brand_id\": 1,
    \"category_id\": 1,
    \"status_slug\": \"available\",
    \"transmission_type_slug\": \"automatic\",
    \"fuel_type_slug\": \"diesel\",
    \"name\": \"Dacia Sandero 2024\",
    \"slug\": \"dacia-sandero-2024\",
    \"model\": \"Sandero\",
    \"year\": 2024,
    \"plate_number\": \"12345-A-10\",
    \"mileage\": 12500,
    \"current_mileage_updated_at\": \"2026-06-10 10:00:00\",
    \"seats\": 5,
    \"doors\": 5,
    \"daily_price\": 350,
    \"weekly_price\": 2200,
    \"monthly_price\": 8500,
    \"deposit_amount\": 3000,
    \"description\": \"Reliable economy vehicle.\",
    \"is_featured\": true,
    \"is_active\": true
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/vehicles"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "brand_id": 1,
    "category_id": 1,
    "status_slug": "available",
    "transmission_type_slug": "automatic",
    "fuel_type_slug": "diesel",
    "name": "Dacia Sandero 2024",
    "slug": "dacia-sandero-2024",
    "model": "Sandero",
    "year": 2024,
    "plate_number": "12345-A-10",
    "mileage": 12500,
    "current_mileage_updated_at": "2026-06-10 10:00:00",
    "seats": 5,
    "doors": 5,
    "daily_price": 350,
    "weekly_price": 2200,
    "monthly_price": 8500,
    "deposit_amount": 3000,
    "description": "Reliable economy vehicle.",
    "is_featured": true,
    "is_active": true
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-admin-vehicles">
</span>
<span id="execution-results-POSTapi-admin-vehicles" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-admin-vehicles"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-vehicles"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-admin-vehicles" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-vehicles">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-admin-vehicles" data-method="POST"
      data-path="api/admin/vehicles"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-vehicles', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-admin-vehicles"
                    onclick="tryItOut('POSTapi-admin-vehicles');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-admin-vehicles"
                    onclick="cancelTryOut('POSTapi-admin-vehicles');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-admin-vehicles"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/admin/vehicles</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-admin-vehicles"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-admin-vehicles"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-admin-vehicles"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>brand_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="brand_id"                data-endpoint="POSTapi-admin-vehicles"
               value="1"
               data-component="body">
    <br>
<p>Existing vehicle brand ID. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>category_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="category_id"                data-endpoint="POSTapi-admin-vehicles"
               value="1"
               data-component="body">
    <br>
<p>Existing vehicle category ID. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>status_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status_slug"                data-endpoint="POSTapi-admin-vehicles"
               value="available"
               data-component="body">
    <br>
<p>Vehicle status slug. Example: <code>available</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>transmission_type_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="transmission_type_slug"                data-endpoint="POSTapi-admin-vehicles"
               value="automatic"
               data-component="body">
    <br>
<p>Transmission type slug. Example: <code>automatic</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>fuel_type_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="fuel_type_slug"                data-endpoint="POSTapi-admin-vehicles"
               value="diesel"
               data-component="body">
    <br>
<p>Fuel type slug. Example: <code>diesel</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-admin-vehicles"
               value="Dacia Sandero 2024"
               data-component="body">
    <br>
<p>Vehicle display name. Example: <code>Dacia Sandero 2024</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="slug"                data-endpoint="POSTapi-admin-vehicles"
               value="dacia-sandero-2024"
               data-component="body">
    <br>
<p>Unique vehicle slug. Example: <code>dacia-sandero-2024</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>model</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="model"                data-endpoint="POSTapi-admin-vehicles"
               value="Sandero"
               data-component="body">
    <br>
<p>Vehicle model. Example: <code>Sandero</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>year</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="year"                data-endpoint="POSTapi-admin-vehicles"
               value="2024"
               data-component="body">
    <br>
<p>Production year. Example: <code>2024</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>plate_number</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="plate_number"                data-endpoint="POSTapi-admin-vehicles"
               value="12345-A-10"
               data-component="body">
    <br>
<p>Unique plate number. Example: <code>12345-A-10</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>mileage</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="mileage"                data-endpoint="POSTapi-admin-vehicles"
               value="12500"
               data-component="body">
    <br>
<p>Current mileage. Example: <code>12500</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>current_mileage_updated_at</code></b>&nbsp;&nbsp;
<small>datetime</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="current_mileage_updated_at"                data-endpoint="POSTapi-admin-vehicles"
               value="2026-06-10 10:00:00"
               data-component="body">
    <br>
<p>optional Mileage update timestamp. Example: <code>2026-06-10 10:00:00</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>seats</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="seats"                data-endpoint="POSTapi-admin-vehicles"
               value="5"
               data-component="body">
    <br>
<p>Seat count. Example: <code>5</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>doors</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="doors"                data-endpoint="POSTapi-admin-vehicles"
               value="5"
               data-component="body">
    <br>
<p>Door count. Example: <code>5</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>daily_price</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="daily_price"                data-endpoint="POSTapi-admin-vehicles"
               value="350"
               data-component="body">
    <br>
<p>Daily rental price. Example: <code>350</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>weekly_price</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="weekly_price"                data-endpoint="POSTapi-admin-vehicles"
               value="2200"
               data-component="body">
    <br>
<p>Weekly rental price. Example: <code>2200</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>monthly_price</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="monthly_price"                data-endpoint="POSTapi-admin-vehicles"
               value="8500"
               data-component="body">
    <br>
<p>Monthly rental price. Example: <code>8500</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>deposit_amount</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="deposit_amount"                data-endpoint="POSTapi-admin-vehicles"
               value="3000"
               data-component="body">
    <br>
<p>Required deposit. Example: <code>3000</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="POSTapi-admin-vehicles"
               value="Reliable economy vehicle."
               data-component="body">
    <br>
<p>optional Vehicle description. Example: <code>Reliable economy vehicle.</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_featured</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="POSTapi-admin-vehicles" style="display: none">
            <input type="radio" name="is_featured"
                   value="true"
                   data-endpoint="POSTapi-admin-vehicles"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-admin-vehicles" style="display: none">
            <input type="radio" name="is_featured"
                   value="false"
                   data-endpoint="POSTapi-admin-vehicles"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>optional Whether the vehicle is featured. Example: <code>true</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_active</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="POSTapi-admin-vehicles" style="display: none">
            <input type="radio" name="is_active"
                   value="true"
                   data-endpoint="POSTapi-admin-vehicles"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-admin-vehicles" style="display: none">
            <input type="radio" name="is_active"
                   value="false"
                   data-endpoint="POSTapi-admin-vehicles"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>optional Whether the vehicle is active. Example: <code>true</code></p>
        </div>
        </form>

                    <h2 id="vehicles-GETapi-admin-vehicles--vehicle_id-">Display a vehicle.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>vehicles.view</code>.</p>

<span id="example-requests-GETapi-admin-vehicles--vehicle_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/vehicles/17" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/vehicles/17"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-vehicles--vehicle_id-">
    </span>
<span id="execution-results-GETapi-admin-vehicles--vehicle_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-vehicles--vehicle_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-vehicles--vehicle_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-vehicles--vehicle_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-vehicles--vehicle_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-vehicles--vehicle_id-" data-method="GET"
      data-path="api/admin/vehicles/{vehicle_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-vehicles--vehicle_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-vehicles--vehicle_id-"
                    onclick="tryItOut('GETapi-admin-vehicles--vehicle_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-vehicles--vehicle_id-"
                    onclick="cancelTryOut('GETapi-admin-vehicles--vehicle_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-vehicles--vehicle_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/vehicles/{vehicle_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-vehicles--vehicle_id-"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-vehicles--vehicle_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-vehicles--vehicle_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>vehicle_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="vehicle_id"                data-endpoint="GETapi-admin-vehicles--vehicle_id-"
               value="17"
               data-component="url">
    <br>
<p>The ID of the vehicle. Example: <code>17</code></p>
            </div>
                    </form>

                    <h2 id="vehicles-PUTapi-admin-vehicles--vehicle_id-">Update a vehicle.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>vehicles.update</code>.</p>

<span id="example-requests-PUTapi-admin-vehicles--vehicle_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost/api/admin/vehicles/17" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"brand_id\": 17,
    \"category_id\": 17,
    \"status_slug\": \"maintenance\",
    \"transmission_type_slug\": \"consequatur\",
    \"fuel_type_slug\": \"consequatur\",
    \"name\": \"mqeopfuudtdsufvyvddqa\",
    \"slug\": \"mniihfqcoynlazghdtqtq\",
    \"model\": \"xbajwbpilpmufinllwloa\",
    \"year\": 21,
    \"plate_number\": \"ydlsmsjuryvojcybzvrby\",
    \"mileage\": 13000,
    \"current_mileage_updated_at\": \"2026-06-10T20:50:28\",
    \"seats\": 3,
    \"doors\": 9,
    \"daily_price\": 375,
    \"weekly_price\": 48,
    \"monthly_price\": 36,
    \"deposit_amount\": 87,
    \"description\": \"Dolores dolorum amet iste laborum eius est dolor.\",
    \"is_featured\": false,
    \"is_active\": true
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/vehicles/17"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "brand_id": 17,
    "category_id": 17,
    "status_slug": "maintenance",
    "transmission_type_slug": "consequatur",
    "fuel_type_slug": "consequatur",
    "name": "mqeopfuudtdsufvyvddqa",
    "slug": "mniihfqcoynlazghdtqtq",
    "model": "xbajwbpilpmufinllwloa",
    "year": 21,
    "plate_number": "ydlsmsjuryvojcybzvrby",
    "mileage": 13000,
    "current_mileage_updated_at": "2026-06-10T20:50:28",
    "seats": 3,
    "doors": 9,
    "daily_price": 375,
    "weekly_price": 48,
    "monthly_price": 36,
    "deposit_amount": 87,
    "description": "Dolores dolorum amet iste laborum eius est dolor.",
    "is_featured": false,
    "is_active": true
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-admin-vehicles--vehicle_id-">
</span>
<span id="execution-results-PUTapi-admin-vehicles--vehicle_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-admin-vehicles--vehicle_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-admin-vehicles--vehicle_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-admin-vehicles--vehicle_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-admin-vehicles--vehicle_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-admin-vehicles--vehicle_id-" data-method="PUT"
      data-path="api/admin/vehicles/{vehicle_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-admin-vehicles--vehicle_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-admin-vehicles--vehicle_id-"
                    onclick="tryItOut('PUTapi-admin-vehicles--vehicle_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-admin-vehicles--vehicle_id-"
                    onclick="cancelTryOut('PUTapi-admin-vehicles--vehicle_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-admin-vehicles--vehicle_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/admin/vehicles/{vehicle_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-admin-vehicles--vehicle_id-"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-admin-vehicles--vehicle_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-admin-vehicles--vehicle_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>vehicle_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="vehicle_id"                data-endpoint="PUTapi-admin-vehicles--vehicle_id-"
               value="17"
               data-component="url">
    <br>
<p>The ID of the vehicle. Example: <code>17</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>brand_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="brand_id"                data-endpoint="PUTapi-admin-vehicles--vehicle_id-"
               value="17"
               data-component="body">
    <br>
<p>Must match an existing stored value. Example: <code>17</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>category_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="category_id"                data-endpoint="PUTapi-admin-vehicles--vehicle_id-"
               value="17"
               data-component="body">
    <br>
<p>Must match an existing stored value. Example: <code>17</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>status_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status_slug"                data-endpoint="PUTapi-admin-vehicles--vehicle_id-"
               value="maintenance"
               data-component="body">
    <br>
<p>optional Vehicle status slug. Example: <code>maintenance</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>transmission_type_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="transmission_type_slug"                data-endpoint="PUTapi-admin-vehicles--vehicle_id-"
               value="consequatur"
               data-component="body">
    <br>
<p>Must match an existing stored value. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>fuel_type_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="fuel_type_slug"                data-endpoint="PUTapi-admin-vehicles--vehicle_id-"
               value="consequatur"
               data-component="body">
    <br>
<p>Must match an existing stored value. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PUTapi-admin-vehicles--vehicle_id-"
               value="mqeopfuudtdsufvyvddqa"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>mqeopfuudtdsufvyvddqa</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="slug"                data-endpoint="PUTapi-admin-vehicles--vehicle_id-"
               value="mniihfqcoynlazghdtqtq"
               data-component="body">
    <br>
<p>Must contain only letters, numbers, dashes and underscores. Must not be greater than 255 characters. Example: <code>mniihfqcoynlazghdtqtq</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>model</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="model"                data-endpoint="PUTapi-admin-vehicles--vehicle_id-"
               value="xbajwbpilpmufinllwloa"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>xbajwbpilpmufinllwloa</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>year</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="year"                data-endpoint="PUTapi-admin-vehicles--vehicle_id-"
               value="21"
               data-component="body">
    <br>
<p>Must be at least 1900. Must not be greater than 2027. Example: <code>21</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>plate_number</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="plate_number"                data-endpoint="PUTapi-admin-vehicles--vehicle_id-"
               value="ydlsmsjuryvojcybzvrby"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>ydlsmsjuryvojcybzvrby</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>mileage</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="mileage"                data-endpoint="PUTapi-admin-vehicles--vehicle_id-"
               value="13000"
               data-component="body">
    <br>
<p>optional Current mileage. Example: <code>13000</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>current_mileage_updated_at</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="current_mileage_updated_at"                data-endpoint="PUTapi-admin-vehicles--vehicle_id-"
               value="2026-06-10T20:50:28"
               data-component="body">
    <br>
<p>Must be a valid date. Example: <code>2026-06-10T20:50:28</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>seats</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="seats"                data-endpoint="PUTapi-admin-vehicles--vehicle_id-"
               value="3"
               data-component="body">
    <br>
<p>Must be at least 1. Must not be greater than 99. Example: <code>3</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>doors</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="doors"                data-endpoint="PUTapi-admin-vehicles--vehicle_id-"
               value="9"
               data-component="body">
    <br>
<p>Must be at least 1. Must not be greater than 20. Example: <code>9</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>daily_price</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="daily_price"                data-endpoint="PUTapi-admin-vehicles--vehicle_id-"
               value="375"
               data-component="body">
    <br>
<p>optional Daily rental price. Example: <code>375</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>weekly_price</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="weekly_price"                data-endpoint="PUTapi-admin-vehicles--vehicle_id-"
               value="48"
               data-component="body">
    <br>
<p>Must be at least 0. Example: <code>48</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>monthly_price</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="monthly_price"                data-endpoint="PUTapi-admin-vehicles--vehicle_id-"
               value="36"
               data-component="body">
    <br>
<p>Must be at least 0. Example: <code>36</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>deposit_amount</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="deposit_amount"                data-endpoint="PUTapi-admin-vehicles--vehicle_id-"
               value="87"
               data-component="body">
    <br>
<p>Must be at least 0. Example: <code>87</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="PUTapi-admin-vehicles--vehicle_id-"
               value="Dolores dolorum amet iste laborum eius est dolor."
               data-component="body">
    <br>
<p>Example: <code>Dolores dolorum amet iste laborum eius est dolor.</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_featured</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="PUTapi-admin-vehicles--vehicle_id-" style="display: none">
            <input type="radio" name="is_featured"
                   value="true"
                   data-endpoint="PUTapi-admin-vehicles--vehicle_id-"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="PUTapi-admin-vehicles--vehicle_id-" style="display: none">
            <input type="radio" name="is_featured"
                   value="false"
                   data-endpoint="PUTapi-admin-vehicles--vehicle_id-"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>optional Whether the vehicle is featured. Example: <code>false</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_active</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="PUTapi-admin-vehicles--vehicle_id-" style="display: none">
            <input type="radio" name="is_active"
                   value="true"
                   data-endpoint="PUTapi-admin-vehicles--vehicle_id-"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="PUTapi-admin-vehicles--vehicle_id-" style="display: none">
            <input type="radio" name="is_active"
                   value="false"
                   data-endpoint="PUTapi-admin-vehicles--vehicle_id-"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Example: <code>true</code></p>
        </div>
        </form>

                    <h2 id="vehicles-PATCHapi-admin-vehicles--vehicle_id-">Update a vehicle.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>vehicles.update</code>.</p>

<span id="example-requests-PATCHapi-admin-vehicles--vehicle_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PATCH \
    "http://localhost/api/admin/vehicles/17" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"brand_id\": 17,
    \"category_id\": 17,
    \"status_slug\": \"maintenance\",
    \"transmission_type_slug\": \"consequatur\",
    \"fuel_type_slug\": \"consequatur\",
    \"name\": \"mqeopfuudtdsufvyvddqa\",
    \"slug\": \"mniihfqcoynlazghdtqtq\",
    \"model\": \"xbajwbpilpmufinllwloa\",
    \"year\": 21,
    \"plate_number\": \"ydlsmsjuryvojcybzvrby\",
    \"mileage\": 13000,
    \"current_mileage_updated_at\": \"2026-06-10T20:50:28\",
    \"seats\": 3,
    \"doors\": 9,
    \"daily_price\": 375,
    \"weekly_price\": 48,
    \"monthly_price\": 36,
    \"deposit_amount\": 87,
    \"description\": \"Dolores dolorum amet iste laborum eius est dolor.\",
    \"is_featured\": false,
    \"is_active\": true
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/vehicles/17"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "brand_id": 17,
    "category_id": 17,
    "status_slug": "maintenance",
    "transmission_type_slug": "consequatur",
    "fuel_type_slug": "consequatur",
    "name": "mqeopfuudtdsufvyvddqa",
    "slug": "mniihfqcoynlazghdtqtq",
    "model": "xbajwbpilpmufinllwloa",
    "year": 21,
    "plate_number": "ydlsmsjuryvojcybzvrby",
    "mileage": 13000,
    "current_mileage_updated_at": "2026-06-10T20:50:28",
    "seats": 3,
    "doors": 9,
    "daily_price": 375,
    "weekly_price": 48,
    "monthly_price": 36,
    "deposit_amount": 87,
    "description": "Dolores dolorum amet iste laborum eius est dolor.",
    "is_featured": false,
    "is_active": true
};

fetch(url, {
    method: "PATCH",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PATCHapi-admin-vehicles--vehicle_id-">
</span>
<span id="execution-results-PATCHapi-admin-vehicles--vehicle_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PATCHapi-admin-vehicles--vehicle_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PATCHapi-admin-vehicles--vehicle_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PATCHapi-admin-vehicles--vehicle_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PATCHapi-admin-vehicles--vehicle_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PATCHapi-admin-vehicles--vehicle_id-" data-method="PATCH"
      data-path="api/admin/vehicles/{vehicle_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PATCHapi-admin-vehicles--vehicle_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PATCHapi-admin-vehicles--vehicle_id-"
                    onclick="tryItOut('PATCHapi-admin-vehicles--vehicle_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PATCHapi-admin-vehicles--vehicle_id-"
                    onclick="cancelTryOut('PATCHapi-admin-vehicles--vehicle_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PATCHapi-admin-vehicles--vehicle_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/admin/vehicles/{vehicle_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PATCHapi-admin-vehicles--vehicle_id-"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PATCHapi-admin-vehicles--vehicle_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PATCHapi-admin-vehicles--vehicle_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>vehicle_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="vehicle_id"                data-endpoint="PATCHapi-admin-vehicles--vehicle_id-"
               value="17"
               data-component="url">
    <br>
<p>The ID of the vehicle. Example: <code>17</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>brand_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="brand_id"                data-endpoint="PATCHapi-admin-vehicles--vehicle_id-"
               value="17"
               data-component="body">
    <br>
<p>Must match an existing stored value. Example: <code>17</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>category_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="category_id"                data-endpoint="PATCHapi-admin-vehicles--vehicle_id-"
               value="17"
               data-component="body">
    <br>
<p>Must match an existing stored value. Example: <code>17</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>status_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status_slug"                data-endpoint="PATCHapi-admin-vehicles--vehicle_id-"
               value="maintenance"
               data-component="body">
    <br>
<p>optional Vehicle status slug. Example: <code>maintenance</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>transmission_type_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="transmission_type_slug"                data-endpoint="PATCHapi-admin-vehicles--vehicle_id-"
               value="consequatur"
               data-component="body">
    <br>
<p>Must match an existing stored value. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>fuel_type_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="fuel_type_slug"                data-endpoint="PATCHapi-admin-vehicles--vehicle_id-"
               value="consequatur"
               data-component="body">
    <br>
<p>Must match an existing stored value. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PATCHapi-admin-vehicles--vehicle_id-"
               value="mqeopfuudtdsufvyvddqa"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>mqeopfuudtdsufvyvddqa</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="slug"                data-endpoint="PATCHapi-admin-vehicles--vehicle_id-"
               value="mniihfqcoynlazghdtqtq"
               data-component="body">
    <br>
<p>Must contain only letters, numbers, dashes and underscores. Must not be greater than 255 characters. Example: <code>mniihfqcoynlazghdtqtq</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>model</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="model"                data-endpoint="PATCHapi-admin-vehicles--vehicle_id-"
               value="xbajwbpilpmufinllwloa"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>xbajwbpilpmufinllwloa</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>year</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="year"                data-endpoint="PATCHapi-admin-vehicles--vehicle_id-"
               value="21"
               data-component="body">
    <br>
<p>Must be at least 1900. Must not be greater than 2027. Example: <code>21</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>plate_number</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="plate_number"                data-endpoint="PATCHapi-admin-vehicles--vehicle_id-"
               value="ydlsmsjuryvojcybzvrby"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>ydlsmsjuryvojcybzvrby</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>mileage</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="mileage"                data-endpoint="PATCHapi-admin-vehicles--vehicle_id-"
               value="13000"
               data-component="body">
    <br>
<p>optional Current mileage. Example: <code>13000</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>current_mileage_updated_at</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="current_mileage_updated_at"                data-endpoint="PATCHapi-admin-vehicles--vehicle_id-"
               value="2026-06-10T20:50:28"
               data-component="body">
    <br>
<p>Must be a valid date. Example: <code>2026-06-10T20:50:28</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>seats</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="seats"                data-endpoint="PATCHapi-admin-vehicles--vehicle_id-"
               value="3"
               data-component="body">
    <br>
<p>Must be at least 1. Must not be greater than 99. Example: <code>3</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>doors</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="doors"                data-endpoint="PATCHapi-admin-vehicles--vehicle_id-"
               value="9"
               data-component="body">
    <br>
<p>Must be at least 1. Must not be greater than 20. Example: <code>9</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>daily_price</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="daily_price"                data-endpoint="PATCHapi-admin-vehicles--vehicle_id-"
               value="375"
               data-component="body">
    <br>
<p>optional Daily rental price. Example: <code>375</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>weekly_price</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="weekly_price"                data-endpoint="PATCHapi-admin-vehicles--vehicle_id-"
               value="48"
               data-component="body">
    <br>
<p>Must be at least 0. Example: <code>48</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>monthly_price</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="monthly_price"                data-endpoint="PATCHapi-admin-vehicles--vehicle_id-"
               value="36"
               data-component="body">
    <br>
<p>Must be at least 0. Example: <code>36</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>deposit_amount</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="deposit_amount"                data-endpoint="PATCHapi-admin-vehicles--vehicle_id-"
               value="87"
               data-component="body">
    <br>
<p>Must be at least 0. Example: <code>87</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="PATCHapi-admin-vehicles--vehicle_id-"
               value="Dolores dolorum amet iste laborum eius est dolor."
               data-component="body">
    <br>
<p>Example: <code>Dolores dolorum amet iste laborum eius est dolor.</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_featured</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="PATCHapi-admin-vehicles--vehicle_id-" style="display: none">
            <input type="radio" name="is_featured"
                   value="true"
                   data-endpoint="PATCHapi-admin-vehicles--vehicle_id-"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="PATCHapi-admin-vehicles--vehicle_id-" style="display: none">
            <input type="radio" name="is_featured"
                   value="false"
                   data-endpoint="PATCHapi-admin-vehicles--vehicle_id-"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>optional Whether the vehicle is featured. Example: <code>false</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_active</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="PATCHapi-admin-vehicles--vehicle_id-" style="display: none">
            <input type="radio" name="is_active"
                   value="true"
                   data-endpoint="PATCHapi-admin-vehicles--vehicle_id-"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="PATCHapi-admin-vehicles--vehicle_id-" style="display: none">
            <input type="radio" name="is_active"
                   value="false"
                   data-endpoint="PATCHapi-admin-vehicles--vehicle_id-"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Example: <code>true</code></p>
        </div>
        </form>

                    <h2 id="vehicles-DELETEapi-admin-vehicles--vehicle_id-">Soft delete a vehicle.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Requires permission: <code>vehicles.delete</code>.</p>

<span id="example-requests-DELETEapi-admin-vehicles--vehicle_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost/api/admin/vehicles/17" \
    --header "Authorization: Bearer {SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/vehicles/17"
);

const headers = {
    "Authorization": "Bearer {SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-admin-vehicles--vehicle_id-">
</span>
<span id="execution-results-DELETEapi-admin-vehicles--vehicle_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-admin-vehicles--vehicle_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-admin-vehicles--vehicle_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-admin-vehicles--vehicle_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-admin-vehicles--vehicle_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-admin-vehicles--vehicle_id-" data-method="DELETE"
      data-path="api/admin/vehicles/{vehicle_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-admin-vehicles--vehicle_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-admin-vehicles--vehicle_id-"
                    onclick="tryItOut('DELETEapi-admin-vehicles--vehicle_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-admin-vehicles--vehicle_id-"
                    onclick="cancelTryOut('DELETEapi-admin-vehicles--vehicle_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-admin-vehicles--vehicle_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/admin/vehicles/{vehicle_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="DELETEapi-admin-vehicles--vehicle_id-"
               value="Bearer {SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-admin-vehicles--vehicle_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-admin-vehicles--vehicle_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>vehicle_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="vehicle_id"                data-endpoint="DELETEapi-admin-vehicles--vehicle_id-"
               value="17"
               data-component="url">
    <br>
<p>The ID of the vehicle. Example: <code>17</code></p>
            </div>
                    </form>

            

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                            </div>
            </div>
</div>
</body>
</html>
