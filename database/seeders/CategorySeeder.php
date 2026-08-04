<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $jeans = Category::updateOrCreate(
            ['slug' => 'jeans'],
            ['name' => 'Jeans', 'description' => 'Jeans de corte relajado, baggy, mom y wide leg.']
        );

        $poleras = Category::updateOrCreate(
            ['slug' => 'poleras'],
            ['name' => 'Poleras', 'description' => 'Poleras con capucha, cuello redondo y cierres de corte minimalista.']
        );

        $casacas = Category::updateOrCreate(
            ['slug' => 'casacas'],
            ['name' => 'Casacas', 'description' => 'Casacas denim, puffers y bombers estructuradas.']
        );

        $polos = Category::updateOrCreate(
            ['slug' => 'polos'],
            ['name' => 'Polos', 'description' => 'Polos Boxy Fit, Heavyweight y con estampados gráficos.']
        );

        Category::updateOrCreate(
            ['slug' => 'baggy-jeans'],
            [
                'name' => 'Baggy Jeans',
                'parent_id' => $jeans->id,
                'image' => 'https://ezzetacompany.com/wp-content/uploads/2026/06/Jean-Baggy-Maiz.jpg',
                'description' => 'Jeans holgados de tiro alto con caída perfecta.'
            ]
        );
        Category::updateOrCreate(
            ['slug' => 'mom-jeans'],
            [
                'name' => 'Mom Jeans',
                'parent_id' => $jeans->id,
                'image' => 'https://ezzetacompany.com/wp-content/uploads/2026/04/mom-jean-hielo-2-0-hombre-a3-800x1067.jpg',
                'description' => 'Corte clásico mom retro, tiro súper alto.'
            ]
        );
        Category::updateOrCreate(
            ['slug' => 'flared-jeans'],
            [
                'name' => 'Flared Jeans',
                'parent_id' => $jeans->id,
                'image' => 'https://ezzetacompany.com/wp-content/uploads/2026/04/jean-ice-flared-hombre-3-800x1000.jpg',
                'description' => 'Siluetas holgadas estilo skate de los 90.'
            ]
        );

        Category::updateOrCreate(
            ['slug' => 'polera-basica'],
            [
                'name' => 'Polera Basica',
                'parent_id' => $poleras->id,
                'image' => 'https://ezzetacompany.com/wp-content/uploads/2026/05/POLERA-BASICA-MELANGE-3.jpg',
                'description' => 'Poleras con capucha de franela reactiva perchada.'
            ]
        );
        Category::updateOrCreate(
            ['slug' => 'polera-canguro'],
            [
                'name' => 'Polera Canguro',
                'parent_id' => $poleras->id,
                'image' => 'https://ezzetacompany.com/wp-content/uploads/2026/05/CANGURO-NEGRO-1-800x1000.jpg',
                'description' => 'Poleras cuello redondo corte boxy fit.'
            ]
        );
        Category::updateOrCreate(
            ['slug' => 'polera-cr'],
            [
                'name' => 'Polera CR',
                'parent_id' => $poleras->id,
                'image' => 'https://ezzetacompany.com/wp-content/uploads/2026/06/POLERA-CR-MELANGE-1.jpg',
                'description' => 'Poleras con cierre metálico de doble vía.'
            ]
        );

        Category::updateOrCreate(
            ['slug' => 'casaca-basica'],
            [
                'name' => 'Casaca Basica',
                'parent_id' => $casacas->id,
                'image' => 'https://ezzetacompany.com/wp-content/uploads/2026/06/CASACA-BASICA-NEGRO-18-1-800x1200.jpg',
                'description' => 'Casacas denim de corte clásico oversized.'
            ]
        );

        Category::updateOrCreate(
            ['slug' => 'polo-prime'],
            [
                'name' => 'Polo Prime',
                'parent_id' => $polos->id,
                'image' => 'https://ezzetacompany.com/wp-content/uploads/2025/12/polo-blanco-prime-hombre-uomo-cattivo-3-800x1000.png',
                'description' => 'Polos de algodón peruano 20/1 de gran caída.'
            ]
        );
        Category::updateOrCreate(
            ['slug' => 'polo-supremo'],
            [
                'name' => 'Polo Supremo',
                'parent_id' => $polos->id,
                'image' => 'https://ezzetacompany.com/wp-content/uploads/2026/07/POLO-SUPREMO-PERLA-17-800x1200.png',
                'description' => 'Polos boxi-fit con caída perfecta.'
            ]
        );
        Category::updateOrCreate(
            ['slug' => 'polo-luxury'],
            [
                'name' => 'Polo Luxury',
                'parent_id' => $polos->id,
                'image' => 'https://ezzetacompany.com/wp-content/uploads/2026/05/POLO-LUXURY-BLANCO-3-800x1200.jpg',
                'description' => 'Polos de alta densidad 300 GSM, 100% algodón.'
            ]
        );
    }
}
