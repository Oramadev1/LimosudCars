<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    /**
     * Seed sample blog posts for the public website.
     */
    public function run(): void
    {
        $posts = [
            [
                'title' => 'Guide complet pour louer une voiture à Dakhla',
                'slug' => 'guide-location-voiture-dakhla',
                'excerpt' => 'Tout ce qu\'il faut savoir avant de réserver votre véhicule : documents, assurance, livraison et conseils pratiques.',
                'content' => "Dakhla est une destination idéale pour explorer le Sahara atlantique en toute liberté. Avant de réserver, préparez votre permis de conduire, une pièce d'identité valide et vérifiez les conditions d'assurance incluses dans votre contrat.\n\nChez Limosud Cars, nous proposons la livraison à l'aéroport, à votre hôtel ou directement à l'agence. Pensez à réserver à l'avance en haute saison pour garantir le véhicule adapté à votre séjour : 4x4 pour les pistes, berline pour la ville ou SUV pour la famille.\n\nNotre équipe reste disponible sur WhatsApp pour vous accompagner avant, pendant et après votre location.",
                'cover_image' => '/cars/cr-v.png',
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Pourquoi choisir un 4x4 pour vos excursions à Dakhla ?',
                'slug' => 'pourquoi-4x4-dakhla',
                'excerpt' => 'Lagunes, dunes et pistes côtières : découvrez les avantages d\'un véhicule tout-terrain pour profiter pleinement du sud marocain.',
                'content' => "Les environs de Dakhla offrent des paysages variés : baie protégée, lagunes turquoise, dunes blanches et routes désertiques. Un 4x4 vous donne l'assurance de rejoindre les spots les plus photogéniques sans stress.\n\nAvec une garde au sol plus élevée et une transmission adaptée, vous roulez confortablement sur sable compacté ou pistes irrégulières. Pensez à vérifier la pression des pneus et à emporter eau, carte offline et batterie externe.\n\nLimosud Cars entretient régulièrement sa flotte 4x4 pour garantir fiabilité et sécurité sur chaque sortie.",
                'cover_image' => '/cars/terios.png',
                'is_published' => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Les meilleures périodes pour visiter Dakhla en voiture',
                'slug' => 'meilleures-periodes-visiter-dakhla',
                'excerpt' => 'Climat, affluence et tarifs : planifiez votre road trip au bon moment pour un séjour serein.',
                'content' => "Dakhla bénéficie d'un climat doux la majeure partie de l'année. L'automne et le printemps sont particulièrement agréables pour la conduite et les activités outdoor.\n\nEn été, les températures restent supportables grâce à la brise océanique, mais réservez tôt votre véhicule. En hiver, les journées sont lumineuses et moins chargées en touristes : parfait pour un road trip au rythme tranquille.\n\nQuelle que soit la saison, anticipez votre réservation et demandez conseil à notre équipe pour choisir le modèle le plus adapté.",
                'cover_image' => '/cars/rush.png',
                'is_published' => true,
                'published_at' => now()->subDays(8),
            ],
            [
                'title' => 'Assurance et caution : ce qui est inclus chez Limosud Cars',
                'slug' => 'assurance-caution-limosud',
                'excerpt' => 'Comprenez clairement la couverture de base, la franchise et le dépôt de garantie avant de prendre la route.',
                'content' => "Chaque location inclut une assurance responsabilité civile conforme à la réglementation marocaine. Des options complémentaires peuvent réduire la franchise en cas de dommage.\n\nLa caution est bloquée au départ et libérée après restitution du véhicule dans l'état convenu. Un état des lieux détaillé est réalisé à la prise en charge et au retour.\n\nN'hésitez pas à poser vos questions avant la signature du contrat : la transparence fait partie de notre engagement qualité.",
                'cover_image' => '/cars/rolls-royce.png',
                'is_published' => true,
                'published_at' => now()->subDays(12),
            ],
            [
                'title' => 'Road trip côtier : itinéraire Dakhla en une journée',
                'slug' => 'road-trip-cotier-dakhla',
                'excerpt' => 'Une journée type entre plages, spots kitesurf et pauses panoramiques le long de la côte saharienne.',
                'content' => "Départ tôt depuis Dakhla pour profiter de la lumière du matin. Direction la lagune pour observer les kitesurfeurs, puis route vers des plages isolées accessibles en véhicule.\n\nPrévoyez des pauses régulières, respectez les zones protégées et ramenez vos déchets. Un plein effectué la veille et une carte téléchargée suffisent pour une escapade mémorable.\n\nPour les groupes ou les longues distances, privilégiez un SUV spacieux avec climatisation performante.",
                'cover_image' => '/cars/gtr.png',
                'is_published' => true,
                'published_at' => now()->subDays(15),
            ],
            [
                'title' => 'Comment préparer votre arrivée à l\'aéroport de Dakhla',
                'slug' => 'preparer-arrivee-aeroport-dakhla',
                'excerpt' => 'Vol atterri ? Voici comment récupérer votre voiture de location rapidement et sans stress.',
                'content' => "Communiquez votre numéro de vol lors de la réservation pour que notre équipe suive d'éventuels retards. À l'arrivée, un conseiller vous accueille avec le contrat et le véhicule prêt à partir.\n\nVérifiez le niveau de carburant, l'état extérieur et intérieur, puis conservez une copie du contrat et des contacts d'urgence.\n\nEn quelques minutes, vous êtes sur la route vers votre hébergement ou directement vers la lagune.",
                'cover_image' => '/cars/cr-v.png',
                'is_published' => true,
                'published_at' => now()->subDays(18),
            ],
        ];

        foreach ($posts as $post) {
            BlogPost::query()->updateOrCreate(
                ['slug' => $post['slug']],
                $post,
            );
        }
    }
}
