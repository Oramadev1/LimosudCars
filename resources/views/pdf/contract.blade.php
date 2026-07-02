<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Contrat {{ $contractNumber }}</title>
    <style>
        body { color: #000; font-family: dejavusans, sans-serif; font-size: 8pt; line-height: 1.2; margin: 0; }
        table { border-collapse: collapse; width: 100%; }
        .cell { border: 1px solid #000; padding: 2px 3px; vertical-align: top; }
        .fr-label { font-size: 6.5pt; font-weight: bold; text-transform: uppercase; }
        .ar-label { direction: rtl; font-family: scheherazade, dejavusans, sans-serif; font-size: 6.5pt; text-align: right; unicode-bidi: embed; }
        .value { font-size: 8.5pt; font-weight: bold; min-height: 12px; padding-top: 1px; word-break: break-word; }
        .value-line { border-bottom: 1px solid #666; min-height: 12px; }
        .section-head { background: #fff; padding: 2px 3px; }
        .center { text-align: center; }
        .tiny { font-size: 6pt; }
        .mark { font-family: dejavusans, sans-serif; font-size: 8pt; font-weight: bold; }
        .legal-box { background: #000; color: #fff; font-size: 7pt; line-height: 1.35; padding: 5px 6px; }
        .legal-ar { direction: rtl; font-family: scheherazade, dejavusans, sans-serif; margin-bottom: 4px; text-align: justify; unicode-bidi: embed; }
        .car-panel { border: 1px solid #000; height: 34px; margin-bottom: 2px; text-align: center; font-size: 6pt; padding: 2px; }
        .car-panel-title { font-size: 6pt; font-weight: bold; }
        .paper-item { font-size: 6.5pt; line-height: 1.5; text-align: center; }
        .header-title { font-size: 14pt; font-weight: bold; }
        .header-sub { font-size: 8pt; font-weight: bold; }
        .paper-title { font-size: 6.5pt; font-weight: bold; line-height: 1.25; text-align: center; }
    </style>
</head>
<body>
@php
    $ar = $labelsAr;
    $box = static fn (bool $checked = false): string => $checked ? '[X]' : '[ ]';
    $isCash = in_array($paymentMethodSlug, ['cash', 'check'], true);
    $isTransfer = $paymentMethodSlug === 'bank_transfer';
    $isCard = in_array($paymentMethodSlug, ['credit_card', 'debit_card', 'online'], true);
    $isOnline = $paymentMethodSlug === 'online';
    $valueOrLine = static fn (?string $value): string => filled($value) ? e($value) : '<div class="value-line"></div>';
    $driver = $additionalDriver;
@endphp

<table>
    <tr>
        <td class="cell center" style="width: 16%;">
            @if (!empty($logoData))
                <img src="data:image/jpeg;base64,{{ $logoData }}" alt="Logo" style="height: 48px; width: auto;">
            @endif
        </td>
        <td class="cell center" style="width: 44%;">
            <div class="header-title">{{ $company['name'] }}.</div>
            <div class="header-sub">{{ $company['tagline'] }}</div>
            <div style="margin-top: 6px;">
                <span class="fr-label">N°</span> <span class="value">{{ $contractDisplayNumber }}</span>
                <span class="fr-label" style="margin-left: 14px;">Série</span> <span class="value">{{ $contractSeries }}</span>
            </div>
        </td>
        <td class="cell" style="width: 40%; font-size: 7pt; line-height: 1.35;">
            {{ $company['address'] }}<br>
            {{ $company['email'] }}<br>
            {{ $company['phone'] }}<br>
            {{ $company['phone_alt'] }}
        </td>
    </tr>
</table>

<table>
    <tr>
        <td class="cell" style="width: 24%;">
            <div class="fr-label">Marque / Modèle</div>
            <div class="value">{{ $vehicleBrand }}</div>
        </td>
        <td class="cell" style="width: 22%;">
            <div class="fr-label">Lieu de livraison</div>
            <div class="value">{{ $pickupLocation }}</div>
        </td>
        <td class="cell" style="width: 22%;">
            <div class="fr-label">N° Immatriculation</div>
            <div class="value">{{ $vehiclePlate }}</div>
        </td>
        <td class="cell" style="width: 32%;">
            <div class="fr-label">Lieu et date de reprise</div>
            <div class="value">{{ $dropoffRepriseText }}</div>
        </td>
    </tr>
    <tr>
        <td class="cell">
            <div class="fr-label">Catégorie</div>
            <div class="value">{{ $vehicleCategory }}</div>
        </td>
        <td class="cell">
            <div class="fr-label">Couleur / Année</div>
            <div class="value">{{ trim(($vehicleColor ?: '—').' / '.($vehicleYear ?: '—')) }}</div>
        </td>
        <td class="cell">
            <div class="fr-label">Kilométrage / Carburant</div>
            <div class="value">{{ trim(($vehicleMileage ?: '—').' km / '.($vehicleFuelLevel ?: '—')) }}</div>
        </td>
        <td class="cell">
            <div class="fr-label">Boîte / Énergie / VIN</div>
            <div class="value">{{ trim(($vehicleTransmission ?: '—').' / '.($vehicleFuelType ?: '—')) }}</div>
            @if ($vehicleVin)<div class="tiny">VIN: {{ $vehicleVin }}</div>@endif
        </td>
    </tr>
</table>

<table>
    <tr>
        <td class="cell" style="width: 56%; padding: 0;">
            <table width="100%">
                <tr>
                    <td class="section-head cell" colspan="2">
                        @include('pdf.partials.bilingual-head', ['fr' => 'Locataire', 'ar' => $ar['locataire']])
                    </td>
                </tr>
                <tr>
                    <td class="cell" style="width: 50%; border-top: 0;">
                        @include('pdf.partials.bilingual-label', ['fr' => 'NOM ET PRENOM', 'ar' => $ar['nom_prenom']])
                        <div class="value">{{ $customerName }}</div>
                    </td>
                    <td class="cell" style="width: 50%; border-top: 0; border-left: 0;">
                        @include('pdf.partials.bilingual-label', ['fr' => 'ADRESSE', 'ar' => $ar['adresse']])
                        @if ($customerAddress)<div class="value">{{ $customerAddress }}</div>@else<div class="value-line"></div>@endif
                    </td>
                </tr>
                <tr>
                    <td class="cell" colspan="2" style="border-top: 0;">
                        @include('pdf.partials.bilingual-label', ['fr' => 'NATIONALITÉ / TÉL / EMAIL', 'ar' => $ar['nationalite']])
                        <div class="value">{{ trim($customerNationality.' · '.$customerPhone.($customerEmail ? ' · '.$customerEmail : '')) }}</div>
                    </td>
                </tr>
                <tr>
                    <td class="cell" style="border-top: 0;">
                        @include('pdf.partials.bilingual-label', ['fr' => 'Permis de conduire', 'ar' => $ar['permis']])
                        @if ($customerLicense)<div class="value">{{ $customerLicense }}</div>@else<div class="value-line"></div>@endif
                    </td>
                    <td class="cell" style="border-top: 0; border-left: 0;">
                        @include('pdf.partials.bilingual-label', ['fr' => 'Délivré / Expire', 'ar' => $ar['delivre']])
                        <div class="value">{{ trim(($customerLicenseIssuedAt ?: '—').' / '.($customerLicenseExpiresAt ?: '—')) }}</div>
                    </td>
                </tr>
                <tr>
                    <td class="cell" style="border-top: 0;">
                        @include('pdf.partials.bilingual-label', ['fr' => 'C.I.N N° ou passeport', 'ar' => $ar['cin_passeport']])
                        @if ($customerPassportOrCin)<div class="value">{{ $customerPassportOrCin }}</div>@else<div class="value-line"></div>@endif
                    </td>
                    <td class="cell" style="border-top: 0; border-left: 0;">
                        @include('pdf.partials.bilingual-label', ['fr' => 'Délivré le', 'ar' => $ar['delivre']])
                        @if ($customerPassportOrCinIssuedAt)<div class="value">{{ $customerPassportOrCinIssuedAt }}</div>@else<div class="value-line"></div>@endif
                    </td>
                </tr>
                <tr>
                    <td class="cell" colspan="2" style="border-top: 0;">
                        @include('pdf.partials.bilingual-label', ['fr' => 'Adresse à l\'étranger', 'ar' => $ar['adresse_etranger']])
                        @if ($customerForeignAddress)<div class="value">{{ $customerForeignAddress }}</div>@else<div class="value-line"></div>@endif
                    </td>
                </tr>
                <tr>
                    <td class="section-head cell" colspan="2" style="border-top: 0;">
                        @include('pdf.partials.bilingual-head', ['fr' => 'Le conducteur supplémentaire', 'ar' => $ar['conducteur']])
                    </td>
                </tr>
                @if (!empty($driver['enabled']))
                    <tr>
                        <td class="cell" colspan="2" style="border-top: 0;">
                            <div class="value">{{ $driver['full_name'] ?? '' }} · {{ $driver['nationality'] ?? '' }} · {{ $driver['phone'] ?? '' }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="cell" style="border-top: 0;"><div class="value">{{ $driver['address'] ?? '' }}</div></td>
                        <td class="cell" style="border-top: 0; border-left: 0;"><div class="value">{{ $driver['passport_or_cin'] ?? '' }}</div></td>
                    </tr>
                    <tr>
                        <td class="cell" style="border-top: 0;"><div class="value">{{ $driver['driving_license_number'] ?? '' }}</div></td>
                        <td class="cell" style="border-top: 0; border-left: 0;"><div class="value">{{ ($driver['license_issued_at'] ?? '').' / '.($driver['license_expires_at'] ?? '') }}</div></td>
                    </tr>
                @else
                    @foreach (['NOM ET PRENOM' => $ar['nom_prenom'], 'ADRESSE' => $ar['adresse']] as $fr => $labelAr)
                        <tr>
                            <td class="cell" colspan="2" style="border-top: 0;">
                                @include('pdf.partials.bilingual-label', ['fr' => $fr, 'ar' => $labelAr])
                                <div class="value-line"></div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </table>
        </td>

        <td class="cell" style="width: 30%; padding: 0;">
            <table width="100%">
                <tr>
                    <td colspan="3" class="section-head cell">
                        @include('pdf.partials.bilingual-head', ['fr' => 'Équipement', 'ar' => $ar['equipement']])
                    </td>
                </tr>
                <tr class="center tiny">
                    <td class="cell" style="border-top:0;"></td>
                    <td class="cell" style="border-top:0;">OUI</td>
                    <td class="cell" style="border-top:0;">NON</td>
                </tr>
                @foreach ($equipmentItems as $item)
                    @php $checked = (bool) ($equipment[$item['key']] ?? false); @endphp
                    <tr>
                        <td class="cell" style="border-top:0;">{{ $item['label'] }}</td>
                        <td class="cell center mark" style="border-top:0;">{{ $box($checked) }}</td>
                        <td class="cell center mark" style="border-top:0;">{{ $box(!$checked) }}</td>
                    </tr>
                @endforeach
                @foreach ($extraEquipmentItems as $item)
                    @php $checked = (bool) ($equipment[$item['key']] ?? false); @endphp
                    <tr>
                        <td class="cell tiny" style="border-top:0;">{{ $item['label'] }}</td>
                        <td class="cell center mark" style="border-top:0;">{{ $box($checked) }}</td>
                        <td class="cell center mark" style="border-top:0;">{{ $box(!$checked) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" class="cell tiny" style="border-top:0;">
                        Permettant la sortie de la voiture à l'extérieur de l'orbite urbaine
                        <div class="ar-label">{{ $ar['sortie_ville'] }}</div>
                        <span class="mark">{{ $box($leaveUrbanArea) }} OUI</span>
                        <span class="mark" style="margin-left:8px;">{{ $box(!$leaveUrbanArea) }} NON</span>
                    </td>
                </tr>
            </table>

            <table width="100%">
                <tr>
                    <td colspan="5" class="section-head cell">
                        @include('pdf.partials.bilingual-head', ['fr' => 'Dates', 'ar' => $ar['dates']])
                    </td>
                </tr>
                <tr class="center tiny">
                    <td class="cell" style="border-top:0; width:28%;"></td>
                    <td class="cell" style="border-top:0;">J</td>
                    <td class="cell" style="border-top:0;">M</td>
                    <td class="cell" style="border-top:0;">A</td>
                    <td class="cell" style="border-top:0;">H</td>
                </tr>
                <tr>
                    <td class="cell fr-label" style="border-top:0;">Départ</td>
                    <td class="cell center value" style="border-top:0;">{{ $start['day'] }}</td>
                    <td class="cell center value" style="border-top:0;">{{ $start['month'] }}</td>
                    <td class="cell center value" style="border-top:0;">{{ $start['year'] }}</td>
                    <td class="cell center value" style="border-top:0;">{{ $start['hour'] }}</td>
                </tr>
                <tr>
                    <td class="cell fr-label" style="border-top:0;">Retour</td>
                    <td class="cell center value" style="border-top:0;">{{ $end['day'] }}</td>
                    <td class="cell center value" style="border-top:0;">{{ $end['month'] }}</td>
                    <td class="cell center value" style="border-top:0;">{{ $end['year'] }}</td>
                    <td class="cell center value" style="border-top:0;">{{ $end['hour'] }}</td>
                </tr>
                <tr>
                    <td colspan="2" class="cell" style="border-top:0;">
                        <div class="fr-label">Prix / jour</div>
                        <div class="value">{{ $pricePerDay }}</div>
                    </td>
                    <td colspan="3" class="cell" style="border-top:0;">
                        <div class="fr-label">Prix total</div>
                        <div class="value">{{ $totalPrice }}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" class="cell tiny" style="border-top:0;">
                        Durée: {{ $rentalDurationDays }} j · Semaine: {{ $pricePerWeek }} · Mois: {{ $pricePerMonth }}
                    </td>
                </tr>
                <tr>
                    <td colspan="5" class="cell" style="border-top:0;">
                        <div class="fr-label">Prolongation</div>
                        <div class="value">{{ $extension ?: ' ' }}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" class="cell" style="border-top:0;">
                        <div class="fr-label">Prix total après prolongation</div>
                        <div class="value">{{ $extensionTotal ?: ' ' }}</div>
                    </td>
                </tr>
            </table>
        </td>

        <td class="cell" style="width: 14%; padding: 0;">
            <table width="100%">
                <tr>
                    <td class="section-head cell">
                        <div class="paper-title">Papier de Véhicule</div>
                        <div class="ar-label">{{ $ar['papiers'] }}</div>
                    </td>
                </tr>
                <tr>
                    <td class="cell paper-item" style="border-top:0;">
                        @foreach ($documentItems as $paper)
                            @php $checked = (bool) ($documents[$paper['key']] ?? false); @endphp
                            <div><span class="mark">{{ $box($checked) }}</span> {{ $paper['label'] }}</div>
                        @endforeach
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table>
    <tr>
        <td class="cell" style="width: 48%; padding: 0;">
            <table width="100%">
                <tr>
                    <td colspan="2" class="section-head cell">
                        @include('pdf.partials.bilingual-head', ['fr' => 'État voiture', 'ar' => $ar['etat_voiture']])
                    </td>
                </tr>
                <tr>
                    <td class="cell center fr-label" style="width:50%; border-top:0;">Départ</td>
                    <td class="cell center fr-label" style="width:50%; border-top:0; border-left:0;">Retour</td>
                </tr>
                <tr>
                    <td class="cell" style="border-top:0;">
                        @foreach ($conditionViews as $view)
                            <div class="car-panel">
                                <div class="car-panel-title">{{ $view['label'] }}</div>
                                {{ $conditionBefore[$view['key']] ?? '' }}
                            </div>
                        @endforeach
                        @if (!empty($conditionBefore['notes']))<div class="tiny">Notes: {{ $conditionBefore['notes'] }}</div>@endif
                    </td>
                    <td class="cell" style="border-top:0; border-left:0;">
                        @foreach ($conditionViews as $view)
                            <div class="car-panel">
                                <div class="car-panel-title">{{ $view['label'] }}</div>
                                {{ $conditionAfter[$view['key']] ?? '' }}
                            </div>
                        @endforeach
                        @if (!empty($conditionAfter['notes']))<div class="tiny">Notes: {{ $conditionAfter['notes'] }}</div>@endif
                    </td>
                </tr>
            </table>
        </td>
        <td class="cell" style="width: 52%; padding: 0;">
            <table width="100%">
                <tr>
                    <td colspan="2" class="section-head cell">
                        @include('pdf.partials.bilingual-head', ['fr' => 'Assurance', 'ar' => $ar['assurance']])
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="cell" style="border-top:0;">
                        <span class="mark">{{ $box($insuranceType === 'basic') }} Basic</span>
                        <span class="mark" style="margin-left:12px;">{{ $box($insuranceType === 'premium') }} Premium</span>
                        <span class="mark" style="margin-left:12px;">{{ $box($insuranceType === 'full_coverage') }} Full</span>
                        <div class="tiny" style="margin-top:2px;">Franchise: {{ $deductibleFormatted }} MAD</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="section-head cell" style="border-top:0;">
                        @include('pdf.partials.bilingual-head', ['fr' => 'Paiement', 'ar' => $ar['paiement']])
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="cell tiny" style="border-top:0;">
                        <span class="mark">{{ $box($isCash) }} Espèce</span>
                        <span class="mark" style="margin-left:4px;">{{ $box($isTransfer) }} Virement</span>
                        <span class="mark" style="margin-left:4px;">{{ $box($isCard) }} Carte</span>
                        <span class="mark" style="margin-left:4px;">{{ $box($isOnline) }} Online</span>
                    </td>
                </tr>
                <tr>
                    <td class="cell tiny" style="width:50%; border-top:0;">Caution: {{ $depositAmount }}</td>
                    <td class="cell tiny" style="width:50%; border-top:0; border-left:0;">Livraison: {{ $deliveryFee }}</td>
                </tr>
                <tr>
                    <td class="cell tiny" style="border-top:0;">Remise: {{ $discount }}</td>
                    <td class="cell tiny" style="border-top:0; border-left:0;">Frais divers: {{ $additionalFees }}</td>
                </tr>
                <tr>
                    <td class="cell tiny" style="border-top:0;">Retard: {{ $lateReturnFees }}</td>
                    <td class="cell tiny" style="border-top:0; border-left:0;">Carburant: {{ $fuelCharges }}</td>
                </tr>
                <tr>
                    <td class="cell tiny" style="border-top:0;">Nettoyage: {{ $cleaningCharges }}</td>
                    <td class="cell tiny" style="border-top:0; border-left:0;">Dommages: {{ $damageCharges }}</td>
                </tr>
                <tr>
                    <td class="cell" style="border-top:0;">
                        <div class="fr-label">Total général</div>
                        <div class="value">{{ $grandTotal }}</div>
                    </td>
                    <td class="cell" style="border-top:0; border-left:0;">
                        <div class="fr-label">Taxe</div>
                        <div class="value">{{ $taxAmount }}</div>
                    </td>
                </tr>
                <tr>
                    <td class="cell" style="border-top:0;">
                        <div class="fr-label">Avance</div>
                        <div class="value">{{ $paidAmountFormatted }}</div>
                    </td>
                    <td class="cell" style="border-top:0; border-left:0;">
                        <div class="fr-label">Reste ({{ $paymentStatus }})</div>
                        <div class="value">{{ $remainingAmountFormatted }}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="cell tiny" style="border-top:0;">
                        <div class="fr-label">Date de paiement programmé</div>
                        <div class="value">{{ $scheduledPaymentDate ?: ' ' }}</div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<div class="legal-box" style="margin-top: 4px;">
    <div class="legal-ar">{{ $legalAr }}</div>
    <div>{{ $legalFr }}</div>
</div>

<table style="margin-top: 6px;">
    <tr>
        <td style="width: 28%; vertical-align: top;">
            <div style="border-top: 1px solid #000; margin-top: 36px; padding-top: 3px; text-align: center;">
                Le locataire<br>
                <span class="ar-label">{{ $ar['locataire_sign'] }}</span>
            </div>
        </td>
        <td style="width: 44%; vertical-align: top;">
            <div class="cell center tiny" style="min-height: 58px;">
                Fait à {{ $company['city'] }} le : {{ $contractDate }}<br><br>
                <strong>{{ $company['legal_name'] }}</strong><br>
                {{ $company['stamp_address'] }}<br>
                Tél: {{ $company['stamp_phones'] }}
            </div>
        </td>
        <td style="width: 28%; vertical-align: top;">
            <div style="border-top: 1px solid #000; margin-top: 36px; padding-top: 3px; text-align: center;">
                Le loueur<br>
                <span class="ar-label">{{ $ar['loueur_sign'] }}</span>
            </div>
        </td>
    </tr>
</table>
</body>
</html>
