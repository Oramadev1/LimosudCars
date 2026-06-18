<?php

$saveId = static function (string $jsonPath, string $envVar): array {
    return [
        "if (pm.response.code === 200 || pm.response.code === 201) {",
        '    const json = pm.response.json();',
        "    const value = json{$jsonPath};",
        '    if (value !== undefined && value !== null) {',
        "        pm.environment.set('{$envVar}', String(value));",
        '    }',
        '}',
    ];
};

return [
    17 => $saveId('.data.id', 'disposable_brand_id'),
    23 => $saveId('.data.id', 'disposable_category_id'),
    29 => $saveId('.data.id', 'disposable_vehicle_id'),
    37 => $saveId('.data.id', 'disposable_customer_id'),
    42 => $saveId('.data.id', 'customer_document_id'),
    45 => $saveId('.data.id', 'disposable_location_id'),
    51 => $saveId('.data.id', 'disposable_reservation_id'),
    65 => $saveId('.data.id', 'disposable_payment_id'),
    70 => $saveId('.data.id', 'contract_id'),
    76 => $saveId('.data.id', 'disposable_maintenance_id'),
    83 => $saveId('.data.id', 'disposable_expense_id'),
    91 => $saveId('.data.id', 'disposable_alert_id'),
];
