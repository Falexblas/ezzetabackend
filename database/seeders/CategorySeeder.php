<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear Categorías Padres
        $jeans = Category::create([
            'name' => 'Jeans',
            'slug' => 'jeans',
            'description' => 'Jeans de corte relajado, baggy, mom y wide leg.',
        ]);

        $poleras = Category::create([
            'name' => 'Poleras',
            'slug' => 'poleras',
            'description' => 'Poleras con capucha, cuello redondo y cierres de corte minimalista.',
        ]);

        $casacas = Category::create([
            'name' => 'Casacas',
            'slug' => 'casacas',
            'description' => 'Casacas denim, puffers y bombers estructuradas.',
        ]);

        $polos = Category::create([
            'name' => 'Polos',
            'slug' => 'polos',
            'description' => 'Polos Boxy Fit, Heavyweight y con estampados gráficos.',
        ]);

        // 2. Crear Categorías Hijas (Subcategorías)
        // Subcategorías de Jeans
        Category::create([
            'name' => 'Baggy Jeans',
            'slug' => 'baggy-jeans',
            'parent_id' => $jeans->id,
            'image' => 'https://ezzetacompany.com/wp-content/uploads/2026/06/Jean-Baggy-Maiz.jpg',
            'description' => 'Jeans holgados de tiro alto con caída perfecta.'
        ]);
        Category::create([
            'name' => 'Mom Jeans',
            'slug' => 'mom-jeans',
            'parent_id' => $jeans->id,
            'image' => 'https://ezzetacompany.com/wp-content/uploads/2026/04/mom-jean-hielo-2-0-hombre-a3-800x1067.jpg',
            'description' => 'Corte clásico mom retro, tiro súper alto.'
        ]);
        Category::create([
            'name' => 'Flared Jeans',
            'slug' => 'flared-jeans',
            'parent_id' => $jeans->id,
            'image' => 'https://ezzetacompany.com/wp-content/uploads/2026/04/jean-ice-flared-hombre-3-800x1000.jpg',
            'description' => 'Siluetas holgadas estilo skate de los 90.'
        ]);

        // Subcategorías de Poleras
        Category::create([
            'name' => 'Polera Basica',
            'slug' => 'polera-basica',
            'parent_id' => $poleras->id,
            'image' => 'https://ezzetacompany.com/wp-content/uploads/2026/05/POLERA-BASICA-MELANGE-3.jpg',
            'description' => 'Poleras con capucha de franela reactiva perchada.'
        ]);
        Category::create([
            'name' => 'Polera Canguro',
            'slug' => 'polera-canguro',
            'parent_id' => $poleras->id,
            'image' => 'https://ezzetacompany.com/wp-content/uploads/2026/05/CANGURO-NEGRO-1-800x1000.jpg',
            'description' => 'Poleras cuello redondo corte boxy fit.'
        ]);
        Category::create([
            'name' => 'Polera CR',
            'slug' => 'polera-cr',
            'parent_id' => $poleras->id,
            'image' => 'https://ezzetacompany.com/wp-content/uploads/2026/06/POLERA-CR-MELANGE-1.jpg',
            'description' => 'Poleras con cierre metálico de doble vía.'
        ]);

        // Subcategorías de Casacas
        Category::create([
            'name' => 'Casaca Basica',
            'slug' => 'casaca-basica',
            'parent_id' => $casacas->id,
            'image' => 'https://ezzetacompany.com/wp-content/uploads/2026/06/CASACA-BASICA-NEGRO-18-1-800x1200.jpg',
            'description' => 'Casacas denim de corte clásico oversized.'
        ]);
       

        // Subcategorías de Polos
        Category::create([
            'name' => 'Polo Prime',
            'slug' => 'polo-prime',
            'parent_id' => $polos->id,
            'image' => 'https://ezzetacompany.com/wp-content/uploads/2025/12/polo-blanco-prime-hombre-uomo-cattivo-3-800x1000.png',
            'description' => 'Polos de algodón peruano 20/1 de gran caída.'
        ]);
        Category::create([
            'name' => 'Polo Supremo',
            'slug' => 'polo-supremo',
            'parent_id' => $polos->id,
            'image' => 'https://ezzetacompany.com/wp-content/uploads/2026/07/POLO-SUPREMO-PERLA-17-800x1200.png',
            'description' => 'Polos boxi-fit con caída perfecta.'
        ]);
        Category::create([
            'name' => 'Polo Luxury',
            'slug' => 'polo-luxury',
            'parent_id' => $polos->id,
            'image' => 'https://ezzetacompany.com/wp-content/uploads/2026/05/POLO-LUXURY-BLANCO-3-800x1200.jpg',
            'description' => 'Polos de alta densidad 300 GSM, 100% algodón.'
        ]);
    }
}
