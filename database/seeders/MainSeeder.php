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
            'profile_picture_path' => 'images/default_profile.avif',
            'password' => bcrypt('Segura1506@'),
            'admin' => true,
            'enabled' => true,
            'email_verified_at' => $currentTimestamp,
        ]);

        User::create([
            'name' => 'Adrian Sullca',
            'email' => 'adrian.sullca@cirvianum.cat',
            'phone_number' => 631367253,
            'profile_picture_path' => 'images/default_profile.avif',
            'password' => bcrypt('Segura1506@'),
            'admin' => false,
            'enabled' => true,
            'email_verified_at' => $currentTimestamp,
        ]);

        User::create([
            'name' => 'Marta Fiorella',
            'email' => 'marta@gmail.com',
            'phone_number' => 631367252,
            'profile_picture_path' => 'images/default_profile.avif',
            'password' => bcrypt('Segura1506@'),
            'admin' => false,
            'enabled' => true,
            'email_verified_at' => $currentTimestamp,
        ]);

        User::create([
            'name' => 'Alexander Aquino',
            'email' => 'alexander@gmail.com',
            'phone_number' => 631367010,
            'profile_picture_path' => 'images/default_profile.avif',
            'password' => bcrypt('Segura1506@'),
            'admin' => false,
            'enabled' => true,
            'email_verified_at' => $currentTimestamp,
        ]);

        User::create([
            'name' => 'Nicol Ale',
            'email' => 'nicol@gmail.com',
            'phone_number' => 631367222,
            'profile_picture_path' => 'images/default_profile.avif',
            'password' => bcrypt('Segura1506@'),
            'admin' => false,
            'enabled' => true,
            'email_verified_at' => $currentTimestamp,
        ]);

        User::create([
            'name' => 'Matias Mendoza',
            'email' => 'matias@gmail.com',
            'phone_number' => 631367572,
            'profile_picture_path' => 'images/default_profile.avif',
            'password' => bcrypt('Segura1506@'),
            'admin' => false,
            'enabled' => true,
            'email_verified_at' => $currentTimestamp,
        ]);

        // ANNOUNCEMENTS

        $announcements = [
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
            'image_url' => "https://res.cloudinary.com/dxvjedi2n/image/upload/v1735251175/announcement-1-2_d8xpmo.jpg",
            'image_public_id' => "announcement-1-2_d8xpmo",
        ]);

        Photo::create([
            'announcement_id' => 1,
            'image_url' => "https://res.cloudinary.com/dxvjedi2n/image/upload/v1735251175/announcement-1-1_xa0toe.jpg",
            'image_public_id' => "announcement-1-1_xa0toe",
        ]);

        Photo::create([
            'announcement_id' => 1,
            'image_url' => "https://res.cloudinary.com/dxvjedi2n/image/upload/v1735251175/announcement-1-3_ws0dhn.jpg",
            'image_public_id' => "announcement-1-3_ws0dhn",
        ]);
        /* 
        Photo::create([
            'announcement_id' => 1,
            "photo_path" => 'images/announcement-1-2.jpg',
        ]);

        Photo::create([
            'announcement_id' => 1,
            "photo_path" => 'images/announcement-1-3.jpg',
        ]);

        Photo::create([
            'announcement_id' => 2,
            "photo_path" => 'images/announcement-2-1.webp',
        ]);

        Photo::create([
            'announcement_id' => 3,
            "photo_path" => 'images/announcement-3-1.jpg',
        ]);

        Photo::create([
            'announcement_id' => 3,
            "photo_path" => 'images/announcement-3-2.jpg',
        ]);

        Photo::create([
            'announcement_id' => 4,
            "photo_path" => 'images/announcement-4-1.webp',
        ]);

        Photo::create([
            'announcement_id' => 4,
            "photo_path" => 'images/announcement-4-2.webp',
        ]);

        Photo::create([
            'announcement_id' => 5,
            "photo_path" => 'images/announcement-5-1.jpg',
        ]);

        Photo::create([
            'announcement_id' => 5,
            "photo_path" => 'images/announcement-5-2.jpg',
        ]);

        Photo::create([
            'announcement_id' => 6,
            "photo_path" => 'images/announcement-6-1.png',
        ]);

        Photo::create([
            'announcement_id' => 6,
            "photo_path" => 'images/announcement-6-2.jpg',
        ]);

        Photo::create([
            'announcement_id' => 7,
            "photo_path" => 'images/announcement-7-1.jpg',
        ]);

        Photo::create([
            'announcement_id' => 7,
            "photo_path" => 'images/announcement-7-2.webp',
        ]);

        Photo::create([
            'announcement_id' => 8,
            "photo_path" => 'images/announcement-8-1.jpg',
        ]);

        Photo::create([
            'announcement_id' => 8,
            "photo_path" => 'images/announcement-8-2.jpg',
        ]);

        Photo::create([
            'announcement_id' => 9,
            "photo_path" => 'images/announcement-9-1.webp',
        ]);

        Photo::create([
            'announcement_id' => 9,
            "photo_path" => 'images/announcement-9-2.webp',
        ]);

        Photo::create([
            'announcement_id' => 10,
            "photo_path" => 'images/announcement-10-1.jpg',
        ]);

        Photo::create([
            'announcement_id' => 10,
            "photo_path" => 'images/announcement-10-2.jpg',
        ]);

        Photo::create([
            'announcement_id' => 11,
            "photo_path" => 'images/announcement-11-1.avif',
        ]);

        Photo::create([
            'announcement_id' => 11,
            "photo_path" => 'images/announcement-11-2.jpg',
        ]);

        Photo::create([
            'announcement_id' => 12,
            "photo_path" => 'images/announcement-12-1.jpg',
        ]);

        Photo::create([
            'announcement_id' => 13,
            "photo_path" => 'images/announcement-13-1.jpg',
        ]);

        Photo::create([
            'announcement_id' => 13,
            "photo_path" => 'images/announcement-13-2.jpg',
        ]);

        Photo::create([
            'announcement_id' => 13,
            "photo_path" => 'images/announcement-13-3.jpg',
        ]); */

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
