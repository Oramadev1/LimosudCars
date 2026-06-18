<?php

$fixturePdf = __DIR__.'/fixtures/sample.pdf';

return [
    42 => [
        ['key' => 'document_type_slug', 'value' => 'passport', 'type' => 'text'],
        ['key' => 'title', 'value' => 'Passport Scan', 'type' => 'text'],
        ['key' => 'file', 'type' => 'file', 'src' => str_replace('\\', '/', $fixturePdf)],
        ['key' => 'expires_at', 'value' => '2028-12-31', 'type' => 'text'],
    ],
    73 => [
        ['key' => 'signed_pdf', 'type' => 'file', 'src' => str_replace('\\', '/', $fixturePdf)],
    ],
];
