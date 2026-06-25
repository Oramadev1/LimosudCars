<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Contrat {{ $contractNumber }}</title>
    <style>
        body {
            color: #000;
            font-family: dejavusans, sans-serif;
            font-size: 8pt;
            line-height: 1.2;
            margin: 0;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        .cell {
            border: 1px solid #000;
            padding: 2px 3px;
            vertical-align: top;
        }

        .fr-label {
            font-family: dejavusans, sans-serif;
            font-size: 6.5pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .ar-label {
            direction: rtl;
            font-family: scheherazade, dejavusans, sans-serif;
            font-size: 6.5pt;
            text-align: right;
            unicode-bidi: embed;
        }

        .value {
            font-family: dejavusans, sans-serif;
            font-size: 8.5pt;
            font-weight: bold;
            min-height: 12px;
            padding-top: 1px;
            word-break: break-word;
        }

        .value-line {
            border-bottom: 1px solid #666;
            min-height: 12px;
        }

        .section-head {
            background: #fff;
            padding: 2px 3px;
        }

        .center { text-align: center; }
        .tiny { font-size: 6pt; }
        .mark { font-family: dejavusans, sans-serif; font-size: 8pt; font-weight: bold; }

        .legal-box {
            background: #000;
            color: #fff;
            font-family: dejavusans, sans-serif;
            font-size: 7pt;
            line-height: 1.35;
            padding: 5px 6px;
        }

        .legal-ar {
            direction: rtl;
            font-family: scheherazade, dejavusans, sans-serif;
            margin-bottom: 4px;
            text-align: justify;
            unicode-bidi: embed;
        }

        .car-panel {
            border: 1px solid #000;
            height: 34px;
            margin-bottom: 2px;
            text-align: center;
        }

        .car-panel-title {
            font-size: 6pt;
            font-weight: bold;
            padding-top: 2px;
        }

        .paper-item {
            font-size: 6.5pt;
            line-height: 1.5;
            text-align: center;
        }

        .header-title {
            font-size: 14pt;
            font-weight: bold;
        }

        .header-sub {
            font-size: 8pt;
            font-weight: bold;
        }

        .paper-title {
            font-size: 6.5pt;
            font-weight: bold;
            line-height: 1.25;
            text-align: center;
        }
    </style>
</head>
<body>
@php
    $ar = $labelsAr;
    $box = static fn (bool $checked = false): string => $checked ? '[X]' : '[ ]';
    $isCash = in_array($paymentMethodSlug, ['cash', 'check'], true);
    $isTransfer = $paymentMethodSlug === 'bank_transfer';
    $isCard = in_array($paymentMethodSlug, ['credit_card', 'debit_card', 'online'], true);
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
                <span class="fr-label" style="margin-left: 14px;">N°</span> <span class="value">A</span>
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
            <div class="fr-label">Marque</div>
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
                        <div class="value-line"></div>
                    </td>
                </tr>
                <tr>
                    <td class="cell" colspan="2" style="border-top: 0;">
                        @include('pdf.partials.bilingual-label', ['fr' => 'NATIONALITÉ', 'ar' => $ar['nationalite']])
                        <div class="value">{{ $customerNationality }}</div>
                    </td>
                </tr>
                <tr>
                    <td class="cell" style="border-top: 0;">
                        @include('pdf.partials.bilingual-label', ['fr' => 'Permis de conduire', 'ar' => $ar['permis']])
                        @if ($customerLicense)
                            <div class="value">{{ $customerLicense }}</div>
                        @else
                            <div class="value-line"></div>
                        @endif
                    </td>
                    <td class="cell" style="border-top: 0; border-left: 0;">
                        @include('pdf.partials.bilingual-label', ['fr' => 'Délivré le', 'ar' => $ar['delivre']])
                        <div class="value-line"></div>
                    </td>
                </tr>
                <tr>
                    <td class="cell" style="border-top: 0;">
                        @include('pdf.partials.bilingual-label', ['fr' => 'C.I.N N° ou passeport', 'ar' => $ar['cin_passeport']])
                        @if ($customerPassportOrCin)
                            <div class="value">{{ $customerPassportOrCin }}</div>
                        @else
                            <div class="value-line"></div>
                        @endif
                    </td>
                    <td class="cell" style="border-top: 0; border-left: 0;">
                        @include('pdf.partials.bilingual-label', ['fr' => 'Délivré le', 'ar' => $ar['delivre']])
                        <div class="value-line"></div>
                    </td>
                </tr>
                <tr>
                    <td class="cell" colspan="2" style="border-top: 0;">
                        @include('pdf.partials.bilingual-label', ['fr' => 'Adresse à l\'étranger', 'ar' => $ar['adresse_etranger']])
                        <div class="value-line"></div>
                    </td>
                </tr>
                <tr>
                    <td class="section-head cell" colspan="2" style="border-top: 0;">
                        @include('pdf.partials.bilingual-head', ['fr' => 'Le conducteur supplémentaire', 'ar' => $ar['conducteur']])
                    </td>
                </tr>
                @foreach (['NOM ET PRENOM' => $ar['nom_prenom'], 'ADRESSE' => $ar['adresse']] as $fr => $labelAr)
                    <tr>
                        <td class="cell" colspan="2" style="border-top: 0;">
                            @include('pdf.partials.bilingual-label', ['fr' => $fr, 'ar' => $labelAr])
                            <div class="value-line"></div>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td class="cell" style="border-top: 0;">
                        @include('pdf.partials.bilingual-label', ['fr' => 'Permis de conduire', 'ar' => $ar['permis']])
                        <div class="value-line"></div>
                    </td>
                    <td class="cell" style="border-top: 0; border-left: 0;">
                        @include('pdf.partials.bilingual-label', ['fr' => 'C.I.N N° ou passeport', 'ar' => $ar['cin_passeport']])
                        <div class="value-line"></div>
                    </td>
                </tr>
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
                @foreach (['Cric', 'Manivelle', 'Clé-roue', 'Triangle', 'Extincteur'] as $item)
                    <tr>
                        <td class="cell" style="border-top:0;">{{ $item }}</td>
                        <td class="cell center mark" style="border-top:0;">{{ $box(true) }}</td>
                        <td class="cell center mark" style="border-top:0;">{{ $box(false) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" class="cell tiny" style="border-top:0;">
                        Permettant la sortie de la voiture à l'extérieur de l'orbite urbaine
                        <div class="ar-label">{{ $ar['sortie_ville'] }}</div>
                        <span class="mark">{{ $box(true) }} OUI</span>
                        <span class="mark" style="margin-left:8px;">{{ $box(false) }} NON</span>
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
                        <div class="fr-label">Prix unitaire</div>
                        <div class="value">{{ $pricePerDay }}</div>
                    </td>
                    <td colspan="3" class="cell" style="border-top:0;">
                        <div class="fr-label">Prix total</div>
                        <div class="value">{{ $totalPrice }}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" class="cell" style="border-top:0;">
                        <div class="fr-label">Prolongation</div>
                        <div class="value-line"></div>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" class="cell" style="border-top:0;">
                        <div class="fr-label">Prix total après prolongation</div>
                        <div class="value-line"></div>
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
                        @foreach (['WW', 'C.GRISE', 'V.TECH', 'ASSUR', 'CARTE VERTE', 'DECISION', 'VIGNETT', 'CONTRAT'] as $paper)
                            <div><span class="mark">[X]</span> {{ $paper }}</div>
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
                        @foreach (['Vue dessus', 'Avant / Arrière', 'Côtés'] as $view)
                            <div class="car-panel"><div class="car-panel-title">{{ $view }}</div></div>
                        @endforeach
                    </td>
                    <td class="cell" style="border-top:0; border-left:0;">
                        @foreach (['Vue dessus', 'Avant / Arrière', 'Côtés'] as $view)
                            <div class="car-panel"><div class="car-panel-title">{{ $view }}</div></div>
                        @endforeach
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
                        <span class="mark">{{ $box(false) }} Basic</span>
                        <span class="mark" style="margin-left:12px;">{{ $box(false) }} Premium</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="section-head cell" style="border-top:0;">
                        @include('pdf.partials.bilingual-head', ['fr' => 'Paiement', 'ar' => $ar['paiement']])
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="cell" style="border-top:0;">
                        <span class="mark">{{ $box($isCash) }} Espèce</span>
                        <span class="mark" style="margin-left:6px;">{{ $box($isTransfer) }} Virement</span>
                        <span class="mark" style="margin-left:6px;">{{ $box($isCard) }} Carte Crédit</span>
                    </td>
                </tr>
                <tr>
                    <td class="cell" style="width:50%; border-top:0;">
                        <div class="fr-label">Avance</div>
                        <div class="value">{{ $paidAmountFormatted }}</div>
                    </td>
                    <td class="cell" style="width:50%; border-top:0; border-left:0;">
                        <div class="fr-label">Reste</div>
                        <div class="value">{{ $remainingAmountFormatted }}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="cell tiny" style="border-top:0;">
                        <div class="fr-label">Date de paiement programmé</div>
                        <div class="value-line"></div>
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
