<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Favorite;
use App\Models\Photo;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentTimestamp = now();
        User::create([
            'name' => 'Admin',
            'email' => 'info.adriannn@gmail.com',
            'phone_number' => 999999999,
            'profile_picture_path' => 'https://res.cloudinary.com/dxvjedi2n/image/upload/v1736552760/profile_pictures/kbtqw3i0j0ylriivop6b.png',
            'password' => bcrypt('Segura1506@'),
            'admin' => true,
            'enabled' => true,
            'email_verified_at' => $currentTimestamp,
        ]);

        User::create([
            'name' => 'Adrian Sullca',
            'email' => 'adrian.sullcaa@cirvianum.cat',
            'phone_number' => 631367253,
            'profile_picture_path' => 'https://res.cloudinary.com/dxvjedi2n/image/upload/v1736552760/profile_pictures/kbtqw3i0j0ylriivop6b.png',
            'password' => bcrypt('Segura1506@'),
            'admin' => false,
            'enabled' => true,
            'email_verified_at' => $currentTimestamp,
        ]);

        User::create([
            'name' => 'Marta Fiorella',
            'email' => 'marta@gmail.com',
            'phone_number' => 631367252,
            'profile_picture_path' => 'https://res.cloudinary.com/dxvjedi2n/image/upload/v1736552760/profile_pictures/kbtqw3i0j0ylriivop6b.png',
            'password' => bcrypt('Segura1506@'),
            'admin' => false,
            'enabled' => true,
            'email_verified_at' => $currentTimestamp,
        ]);

        User::create([
            'name' => 'Alexander Aquino',
            'email' => 'alexander@gmail.com',
            'phone_number' => 631367010,
            'profile_picture_path' => 'https://res.cloudinary.com/dxvjedi2n/image/upload/v1736552760/profile_pictures/kbtqw3i0j0ylriivop6b.png',
            'password' => bcrypt('Segura1506@'),
            'admin' => false,
            'enabled' => true,
            'email_verified_at' => $currentTimestamp,
        ]);

        User::create([
            'name' => 'Nicol Ale',
            'email' => 'nicol@gmail.com',
            'phone_number' => 631367222,
            'profile_picture_path' => 'https://res.cloudinary.com/dxvjedi2n/image/upload/v1736552760/profile_pictures/kbtqw3i0j0ylriivop6b.png',
            'password' => bcrypt('Segura1506@'),
            'admin' => false,
            'enabled' => true,
            'email_verified_at' => $currentTimestamp,
        ]);

        User::create([
            'name' => 'Matias Mendoza',
            'email' => 'matias@gmail.com',
            'phone_number' => 631367572,
            'profile_picture_path' => 'https://res.cloudinary.com/dxvjedi2n/image/upload/v1736552760/profile_pictures/kbtqw3i0j0ylriivop6b.png',
            'password' => bcrypt('Segura1506@'),
            'admin' => false,
            'enabled' => true,
            'email_verified_at' => $currentTimestamp,
        ]);

        // ANNOUNCEMENTS

        $announcements = [
            // AD-1
            [
                'user_id' => 2,
                'title' => 'Tesla Model 3 Eléctrico en Venta',
                'price' => 50000.00,
                'description' => 'Vendo mi Tesla Model 3 por cambio de ciudad. Excelente estado, apenas 12000 km. Perfecto para quien busca un vehículo eléctrico de alta gama con bajo consumo.',
                'kilometers' => 12000.0,
                'brand' => 'Tesla',
                'model' => 'Model 3',
                'year' => 2022,
                'state' => 'active',
            ],
            // AD-2
            [
                'user_id' => 3,
                'title' => 'Volkswagen Golf 2019 - Oportunidad',
                'price' => 20000.00,
                'description' => 'Vendo mi Volkswagen Golf 2019 por upgrade a un vehículo más grande. Perfecto para ciudad, bajo consumo y fácil de aparcar. Mantenimiento al día.',
                'kilometers' => 45000.0,
                'brand' => 'Volkswagen',
                'model' => 'Golf',
                'year' => 2019,
                'state' => 'active',
            ],
            // AD-3
            [
                'user_id' => 4,
                'title' => 'Peugeot 208 2018 - Ideal para Ciudad',
                'price' => 15000.00,
                'description' => 'Vendo mi Peugeot 208 por cambio de trabajo. Perfecto para moverse por la ciudad, económico en consumo y fácil de estacionar. Excelente estado.',
                'kilometers' => 35000.0,
                'brand' => 'Peugeot',
                'model' => '208',
                'year' => 2018,
                'state' => 'active',
            ],
            // AD-4
            [
                'user_id' => 2,
                'title' => 'Tesla Model S 2021 - Lujo Eléctrico',
                'price' => 60000.00,
                'description' => 'Vendo mi Tesla Model S por cambio a otro modelo. Vehículo de lujo con todas las prestaciones, perfecto para largas distancias. Poco uso, como nuevo.',
                'kilometers' => 25000.0,
                'brand' => 'Tesla',
                'model' => 'Model S',
                'year' => 2021,
                'state' => 'active',
            ],
            // AD-5
            [
                'user_id' => 3,
                'title' => 'Volkswagen Polo 2020 - Bajo Kilometraje',
                'price' => 18000.00,
                'description' => 'Vendo mi Volkswagen Polo por mudanza al extranjero. Perfecto para uso diario, muy económico y en excelente estado. Oportunidad única.',
                'kilometers' => 23000.0,
                'brand' => 'Volkswagen',
                'model' => 'Polo',
                'year' => 2020,
                'state' => 'active',
            ],
            // AD-6
            [
                'user_id' => 4,
                'title' => 'Porsche 911 2019 - Deportivo de Ensueño',
                'price' => 22000.00,
                'description' => 'Vendo mi Porsche 911 por cambio a vehículo familiar. Deportivo en perfecto estado, ideal para amantes de la velocidad y el lujo. Mantenimiento al día.',
                'kilometers' => 15000.0,
                'brand' => 'Porsche',
                'model' => '911',
                'year' => 2019,
                'state' => 'active',
            ],
            // AD-7
            [
                'user_id' => 2,
                'title' => 'Citroën C4 2020 - Familiar y Espacioso',
                'price' => 25000.00,
                'description' => 'Vendo mi Citroën C4 por cambio a vehículo más pequeño. Ideal para familias, espacioso y cómodo. Perfecto para viajes largos y uso diario.',
                'kilometers' => 38000.0,
                'brand' => 'Citroën',
                'model' => 'C4',
                'year' => 2020,
                'state' => 'active',
            ],
            // AD-8
            [
                'user_id' => 3,
                'title' => 'Kia Sportage 2021 - SUV Versátil',
                'price' => 21000.00,
                'description' => 'Vendo mi Kia Sportage por cambio de trabajo. SUV versátil, perfecto para ciudad y carretera. Bajo consumo y excelentes prestaciones.',
                'kilometers' => 27000.0,
                'brand' => 'Kia',
                'model' => 'Sportage',
                'year' => 2021,
                'state' => 'active',
            ],
            // AD-9
            [
                'user_id' => 4,
                'title' => 'Tesla Model X 2021 - SUV Eléctrico de Lujo',
                'price' => 30000.00,
                'description' => 'Vendo mi Tesla Model X por cambio a otro modelo. SUV eléctrico de alta gama, perfecto para familias que buscan lujo y sostenibilidad. Como nuevo.',
                'kilometers' => 22000.0,
                'brand' => 'Tesla',
                'model' => 'Model X',
                'year' => 2021,
                'state' => 'active',
            ],
            // AD-10
            [
                'user_id' => 2,
                'title' => 'Volkswagen Jetta 2018 - Confiable y Económico',
                'price' => 12000.00,
                'description' => 'Vendo mi Volkswagen Jetta por upgrade. Sedán confiable y económico, ideal para uso diario. Perfecto para quienes buscan un vehículo espacioso y de bajo consumo.',
                'kilometers' => 55000.0,
                'brand' => 'Volkswagen',
                'model' => 'Jetta',
                'year' => 2018,
                'state' => 'active',
            ],
            // AD-11
            [
                'user_id' => 3,
                'title' => 'Porsche Cayenne 2017 - Lujo y Aventura',
                'price' => 27000.00,
                'description' => 'Vendo mi Porsche Cayenne por cambio a vehículo más pequeño. SUV de lujo con gran rendimiento, ideal para aventuras y viajes largos. Mantenimiento al día.',
                'kilometers' => 47000.0,
                'brand' => 'Porsche',
                'model' => 'Cayenne',
                'year' => 2017,
                'state' => 'active',
            ],
            // AD-12
            [
                'user_id' => 4,
                'title' => 'Citroën C3 2016 - Compacto y Económico',
                'price' => 9000.00,
                'description' => 'Vendo mi Citroën C3 por cambio a vehículo más grande. Compacto ideal para ciudad, muy económico en consumo y fácil de aparcar. Perfecto para primeros conductores.',
                'kilometers' => 65000.0,
                'brand' => 'Citroën',
                'model' => 'C3',
                'year' => 2016,
                'state' => 'active',
            ],
            // AD-13
            [
                'user_id' => 2,
                'title' => 'Kia Ceed 2019 - Versátil y Moderno',
                'price' => 17000.00,
                'description' => 'Vendo mi Kia Ceed por cambio de país. Vehículo versátil y moderno, perfecto para ciudad y viajes cortos. Bajo consumo y excelente estado de conservación.',
                'kilometers' => 42000.0,
                'brand' => 'Kia',
                'model' => 'Ceed',
                'year' => 2019,
                'state' => 'active',
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::create($announcement);
        }

        // PHOTOS
        Photo::create([
            'announcement_id' => 1,
            'image_url' => "https://res.cloudinary.com/dxvjedi2n/image/upload/f_avif/v1736781506/aaaaa_pa0sbx.jpg",
            'image_public_id' => "aaaaa_pa0sbx",
        ]);

        Photo::create([
            'announcement_id' => 1,
            'image_url' => "https://res.cloudinary.com/dxvjedi2n/image/upload/f_avif/v1736781506/ssss_rat22i.jpg",
            'image_public_id' => "ssss_rat22i",
        ]);
        // AD-2
        Photo::create([
            'announcement_id' => 2,
            'image_url' => "https://res.cloudinary.com/dxvjedi2n/image/upload/f_avif/v1736724947/announcements_photos/tlfihz3hlbcnj3k7mnsa.webp",
            'image_public_id' => "announcements_photos/tlfihz3hlbcnj3k7mnsa",
        ]);
        Photo::create([
            'announcement_id' => 2,
            'image_url' => "https://res.cloudinary.com/dxvjedi2n/image/upload/f_avif/v1736724947/announcements_photos/n5hxxm76bgtt2zeoj2su.jpg",
            'image_public_id' => "announcements_photos/n5hxxm76bgtt2zeoj2su",
        ]);
        // AD-3
        Photo::create([
            'announcement_id' => 3,
            'image_url' => "https://res.cloudinary.com/dxvjedi2n/image/upload/f_avif/v1736725712/announcements_photos/ap8bifeu2ntr99l2lnmr.webp",
            'image_public_id' => "announcements_photos/ap8bifeu2ntr99l2lnmr",
        ]);
        Photo::create([
            'announcement_id' => 3,
            'image_url' => "https://res.cloudinary.com/dxvjedi2n/image/upload/f_avif/v1736725672/announcements_photos/mowdykrnnyegvjf3iznv.jpg",
            'image_public_id' => "announcements_photos/mowdykrnnyegvjf3iznv",
        ]);
        // AD-4
        Photo::create([
            'announcement_id' => 4,
            'image_url' => "https://res.cloudinary.com/dxvjedi2n/image/upload/f_avif/v1736725889/announcements_photos/ydeiwb3erup2sl3f5pq8.webp",
            'image_public_id' => "announcements_photos/ydeiwb3erup2sl3f5pq8",
        ]);
        Photo::create([
            'announcement_id' => 4,
            'image_url' => "https://res.cloudinary.com/dxvjedi2n/image/upload/f_avif/v1736725889/announcements_photos/bxcadnyq5yae2w2bqvrk.jpg",
            'image_public_id' => "announcements_photos/bxcadnyq5yae2w2bqvrk",
        ]);
        // AD-5
        Photo::create([
            'announcement_id' => 5,
            'image_url' => "https://res.cloudinary.com/dxvjedi2n/image/upload/f_avif/v1736726044/announcements_photos/tycsvtkvytsycri0deji.webp",
            'image_public_id' => "announcements_photos/tycsvtkvytsycri0deji",
        ]);
        Photo::create([
            'announcement_id' => 5,
            'image_url' => "https://res.cloudinary.com/dxvjedi2n/image/upload/f_avif/v1736726044/announcements_photos/nojhfnjye4oli15rzhur.jpg",
            'image_public_id' => "announcements_photos/nojhfnjye4oli15rzhur",
        ]);
        // AD-6
        Photo::create([
            'announcement_id' => 6,
            'image_url' => "https://res.cloudinary.com/dxvjedi2n/image/upload/f_avif/v1736726193/announcements_photos/murzc80mfpzgrvuy8xvv.jpg",
            'image_public_id' => "announcements_photos/murzc80mfpzgrvuy8xvv",
        ]);
        Photo::create([
            'announcement_id' => 6,
            'image_url' => "https://res.cloudinary.com/dxvjedi2n/image/upload/f_avif/v1736726193/announcements_photos/u9fxungl4awo9nadzt8z.webp",
            'image_public_id' => "announcements_photos/u9fxungl4awo9nadzt8z",
        ]);
        // AD-7
        Photo::create([
            'announcement_id' => 7,
            'image_url' => "https://res.cloudinary.com/dxvjedi2n/image/upload/f_avif/v1736726396/announcements_photos/al7stgrb9ixmsyo1t0oq.jpg",
            'image_public_id' => "announcements_photos/al7stgrb9ixmsyo1t0oq",
        ]);
        Photo::create([
            'announcement_id' => 7,
            'image_url' => "https://res.cloudinary.com/dxvjedi2n/image/upload/f_avif/v1736726473/announcements_photos/rmw84smvev7ucoyizisa.jpg",
            'image_public_id' => "announcements_photos/rmw84smvev7ucoyizisa",
        ]);
        // AD-8
        Photo::create([
            'announcement_id' => 8,
            'image_url' => "https://res.cloudinary.com/dxvjedi2n/image/upload/f_avif/v1736726602/announcements_photos/nc8y3eewi6k5dyypwilr.jpg",
            'image_public_id' => "announcements_photos/nc8y3eewi6k5dyypwilr",
        ]);
        Photo::create([
            'announcement_id' => 8,
            'image_url' => "https://res.cloudinary.com/dxvjedi2n/image/upload/f_avif/v1736726602/announcements_photos/frxe4odcvtsu8dgikdh6.webp",
            'image_public_id' => "announcements_photos/frxe4odcvtsu8dgikdh6",
        ]);
        // AD-9
        Photo::create([
            'announcement_id' => 9,
            'image_url' => "https://res.cloudinary.com/dxvjedi2n/image/upload/f_avif/v1736727146/announcements_photos/jw0gu78uecggusijy2fx.jpg",
            'image_public_id' => "announcements_photos/jw0gu78uecggusijy2fx",
        ]);
        Photo::create([
            'announcement_id' => 9,
            'image_url' => "https://res.cloudinary.com/dxvjedi2n/image/upload/f_avif/v1736727146/announcements_photos/vnynsagt6kch7sc1ayqa.jpg",
            'image_public_id' => "announcements_photos/vnynsagt6kch7sc1ayqa",
        ]);
        // AD-10
        Photo::create([
            'announcement_id' => 10,
            'image_url' => "https://res.cloudinary.com/dxvjedi2n/image/upload/f_avif/v1736727220/announcements_photos/dyimcacx8bpbsnn8qkno.jpg",
            'image_public_id' => "announcements_photos/dyimcacx8bpbsnn8qkno",
        ]);
        Photo::create([
            'announcement_id' => 10,
            'image_url' => "https://res.cloudinary.com/dxvjedi2n/image/upload/f_avif/v1736727220/announcements_photos/kz8qbcjwnpvjqnhopt8e.avif",
            'image_public_id' => "announcements_photos/kz8qbcjwnpvjqnhopt8e",
        ]);
        // AD-11
        Photo::create([
            'announcement_id' => 11,
            'image_url' => "https://res.cloudinary.com/dxvjedi2n/image/upload/f_avif/v1736727336/announcements_photos/mpx2ls0iiksjoswjzqt3.jpg",
            'image_public_id' => "announcements_photos/mpx2ls0iiksjoswjzqt3",
        ]);
        Photo::create([
            'announcement_id' => 11,
            'image_url' => "https://res.cloudinary.com/dxvjedi2n/image/upload/f_avif/v1736727336/announcements_photos/ej8goj0lcanm1e0c1dtb.jpg",
            'image_public_id' => "announcements_photos/ej8goj0lcanm1e0c1dtb",
        ]);
        // AD-12
        Photo::create([
            'announcement_id' => 12,
            'image_url' => "https://res.cloudinary.com/dxvjedi2n/image/upload/f_avif/v1736727455/announcements_photos/vxyuj7wmv7o8cfnifg3u.jpg",
            'image_public_id' => "announcements_photos/vxyuj7wmv7o8cfnifg3u",
        ]);
        // AD-13
        Photo::create([
            'announcement_id' => 13,
            'image_url' => "https://res.cloudinary.com/dxvjedi2n/image/upload/f_avif/v1736727519/announcements_photos/gz4onzjkjhpywlsaqooe.jpg",
            'image_public_id' => "announcements_photos/gz4onzjkjhpywlsaqooe",
        ]);
        Photo::create([
            'announcement_id' => 13,
            'image_url' => "https://res.cloudinary.com/dxvjedi2n/image/upload/f_avif/v1736727518/announcements_photos/ndoczfegzkra4xu1bpbn.webp",
            'image_public_id' => "announcements_photos/ndoczfegzkra4xu1bpbn",
        ]);

        // REVIEWS
        Review::create([
            'valuator_user_id' => 3,
            'rated_user_id' => 2,
            'comment' => 'Muy buen coche, todo según lo descrito. ¡Recomendado!',
            'rating' => 4,
        ]);

        Review::create([
            'valuator_user_id' => 4,
            'rated_user_id' => 2,
            'comment' => 'Excelente trato, el coche estaba en perfecto estado.',
            'rating' => 5,
        ]);

        Review::create([
            'valuator_user_id' => 2,
            'rated_user_id' => 3,
            'comment' => 'Muy buena experiencia, el coche estaba en excelente estado.',
            'rating' => 5,
        ]);

        Review::create([
            'valuator_user_id' => 4,
            'rated_user_id' => 3,
            'comment' => 'Buen vendedor, muy rápido y eficiente.',
            'rating' => 4,
        ]);

        Review::create([
            'valuator_user_id' => 2,
            'rated_user_id' => 4,
            'comment' => 'Coche tal como se describió, muy confiable.',
            'rating' => 5,
        ]);

        Review::create([
            'valuator_user_id' => 3,
            'rated_user_id' => 4,
            'comment' => 'Todo perfecto, trato muy amable.',
            'rating' => 5,
        ]);

        // FAVORITES
        Favorite::create([
            'user_id' => 2,
            'announcement_id' => 2,
        ]);

        Favorite::create([
            'user_id' => 2,
            'announcement_id' => 9,
        ]);

        Favorite::create([
            'user_id' => 2,
            'announcement_id' => 10,
        ]);
        Favorite::create([
            'user_id' => 2,
            'announcement_id' => 13,
        ]);

        Favorite::create([
            'user_id' => 2,
            'announcement_id' => 8,
        ]);

        Favorite::create([
            'user_id' => 2,
            'announcement_id' => 11,
        ]);

        Favorite::create([
            'user_id' => 2,
            'announcement_id' => 4,
        ]);


        Favorite::create([
            'user_id' => 2,
            'announcement_id' => 3,
        ]);

        Favorite::create([
            'user_id' => 2,
            'announcement_id' => 5,
        ]);

        Favorite::create([
            'user_id' => 2,
            'announcement_id' => 6,
        ]);

        Favorite::create([
            'user_id' => 3,
            'announcement_id' => 7,
        ]);

        Favorite::create([
            'user_id' => 4,
            'announcement_id' => 8,
        ]);

        Favorite::create([
            'user_id' => 3,
            'announcement_id' => 9,
        ]);

        Favorite::create([
            'user_id' => 4,
            'announcement_id' => 11,
        ]);

        Favorite::create([
            'user_id' => 3,
            'announcement_id' => 13,
        ]);
    }
}
