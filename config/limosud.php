<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Limosud Cars company details (rental contracts, documents)
    |--------------------------------------------------------------------------
    */

    'company' => [
        'name' => env('LIMOSUD_COMPANY_NAME', 'LIMOSUD CARS'),
        'tagline' => env('LIMOSUD_COMPANY_TAGLINE', 'Location Voiture'),
        'address' => env('LIMOSUD_COMPANY_ADDRESS', 'Hay alqods N10 - Dakhla'),
        'email' => env('LIMOSUD_COMPANY_EMAIL', 'Limosudcars@gmail.com'),
        'phone' => env('LIMOSUD_COMPANY_PHONE', '06 61 04 09 67'),
        'phone_alt' => env('LIMOSUD_COMPANY_PHONE_ALT', '06 18 51 84 28'),
        'city' => env('LIMOSUD_COMPANY_CITY', 'Dakhla'),
        'legal_name' => env('LIMOSUD_COMPANY_LEGAL_NAME', 'LIMOSUD CARS SARL AU ELHOUSSAINE EZZOGGAGHY'),
        'stamp_address' => env('LIMOSUD_COMPANY_STAMP_ADDRESS', 'Hay EL Quds N° 10 DAKHLA'),
        'stamp_phones' => env('LIMOSUD_COMPANY_STAMP_PHONES', '0684870112/0606906525'),
        'insurance_deductible' => (int) env('LIMOSUD_INSURANCE_DEDUCTIBLE', 5000),
    ],

    'vehicle_condition_image' => env('LIMOSUD_VEHICLE_CONDITION_IMAGE', 'images/vehicle-condition-notes.jpeg'),

    'contract_labels_ar' => [
        'locataire' => 'المستأجر',
        'conducteur' => 'السائق الإضافي',
        'nom_prenom' => 'الاسم والنسب',
        'adresse' => 'العنوان',
        'nationalite' => 'الجنسية',
        'permis' => 'رخصة السياقة',
        'delivre' => 'تاريخ الإصدار',
        'cin_passeport' => 'ب.و.ت.إ أو جواز السفر',
        'adresse_etranger' => 'العنوان بالخارج',
        'equipement' => 'التجهيزات',
        'papiers' => 'وثائق السيارة',
        'dates' => 'التواريخ',
        'depart' => 'الانطلاق',
        'retour' => 'العودة',
        'prix_unitaire' => 'الثمن لليوم',
        'prix_total' => 'المجموع',
        'prolongation' => 'التمديد',
        'prix_apres_prolongation' => 'المجموع بعد التمديد',
        'etat_voiture' => 'حالة السيارة',
        'assurance' => 'التأمين',
        'paiement' => 'الدفع',
        'sortie_ville' => 'السماح بخروج السيارة خارج المدينة',
        'locataire_sign' => 'توقيع المستأجر',
        'loueur_sign' => 'توقيع المؤجر',
    ],

    'contract_legal_ar' => 'في حالة وقوع حادث أو ضرر ناتج عن المستأجر، يلتزم الأخير بدفع مبلغ التأمين التكميلي المحدد في :deductible درهم، إضافة إلى تعويض يقابل مدة توقف السيارة عن السير أثناء التصليح.',

    'contract_legal_fr' => 'En cas d\'accident ou de dommage causé par le locataire, celui-ci s\'engage à payer la franchise d\'assurance fixée à :deductible dirhams, ainsi qu\'une indemnité correspondant au temps d\'immobilisation du véhicule pendant les réparations.',

];
