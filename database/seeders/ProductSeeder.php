<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Obtener subcategorías hijas
        $catBaggy = Category::where('slug', 'baggy-jeans')->first();
        $catMom = Category::where('slug', 'mom-jeans')->first();
        $catFlared = Category::where('slug', 'flared-jeans')->first();
        
        $catPoleraBasica = Category::where('slug', 'polera-basica')->first();
        $catPoleraCanguro = Category::where('slug', 'polera-canguro')->first();
        $catPoleraCr = Category::where('slug', 'polera-cr')->first();
        
        $catCasacaBasica = Category::where('slug', 'casaca-basica')->first();

        
        $catPoloPrime = Category::where('slug', 'polo-prime')->first();
        $catPoloSupremo = Category::where('slug', 'polo-supremo')->first();
        $catPoloLuxury = Category::where('slug', 'polo-luxury')->first();

        // 2. Definir los productos con colores divididos (datos 100% reales en BD)
        $productsData = [
            
            // --- JEANS BAGGY ---
            [
                'category_id' => $catBaggy->id,
                'name' => 'Baggy Jeans Maíz Ezzeta',
                'description' => 'Jeans holgados clásicos de color maíz confeccionados en denim rígido de 14 oz. Corte relajado, tiro alto.',
                'base_price' => 189.90,
                'main_image' => 'https://ezzetacompany.com/wp-content/uploads/2026/06/Jean-Baggy-Maiz.jpg',
                'color' => 'Beige',
                'variants' => [
                    ['size' => '28', 'stock' => 15, 'price_adjustment' => 0.00],
                    ['size' => '30', 'stock' => 20, 'price_adjustment' => 0.00],
                    ['size' => '32', 'stock' => 25, 'price_adjustment' => 5.00],
                    ['size' => '34', 'stock' => 10, 'price_adjustment' => 5.00],
                ]
            ],
            [
                'category_id' => $catBaggy->id,
                'name' => 'Baggy Jeans Celeste Ezzeta',
                'description' => 'Jeans holgados clásicos de color celeste hielo confeccionados en denim rígido. Corte relajado, tiro alto.',
                'base_price' => 189.90,
                'main_image' => 'https://ezzetacompany.com/wp-content/uploads/2025/11/JEAN-BAGGY-CELESTE-1-800x1200.jpg',
                'color' => 'Celeste',
                'variants' => [
                    ['size' => '28', 'stock' => 12, 'price_adjustment' => 0.00],
                    ['size' => '30', 'stock' => 18, 'price_adjustment' => 0.00],
                    ['size' => '32', 'stock' => 22, 'price_adjustment' => 5.00],
                    ['size' => '34', 'stock' => 8, 'price_adjustment' => 5.00],
                ]
            ],
            [
                'category_id' => $catBaggy->id,
                'name' => 'Baggy Jeans Azul Rasgado Ezzeta',
                'description' => 'Jeans holgados clásicos de color azul oscuro lavado, denim estructurado de 14 oz. Corte relajado, tiro alto.',
                'base_price' => 189.90,
                'main_image' => 'https://ezzetacompany.com/wp-content/uploads/2025/11/JEAN-BAGGY-DSTR-RASGADO-AZUL-1.jpg',
                'color' => 'Azul',
                'variants' => [
                    ['size' => '28', 'stock' => 10, 'price_adjustment' => 0.00],
                    ['size' => '30', 'stock' => 15, 'price_adjustment' => 0.00],
                    ['size' => '32', 'stock' => 20, 'price_adjustment' => 5.00],
                    ['size' => '34', 'stock' => 12, 'price_adjustment' => 5.00],
                ]
            ],

            // --- JEANS MOM ---
            [
                'category_id' => $catMom->id,
                'name' => 'Mom Jeans Celeste',
                'description' => 'Corte mom jean retro, tiro súper alto, ajustado en cintura y semi holgado en muslos. Color celeste claro.',
                'base_price' => 179.90,
                'main_image' => 'https://ezzetacompany.com/wp-content/uploads/2026/04/mom-jean-hielo-2-0-hombre-a3-800x1067.jpg',
                'color' => 'Celeste',
                'variants' => [
                    ['size' => '28', 'stock' => 10, 'price_adjustment' => 0.00],
                    ['size' => '30', 'stock' => 15, 'price_adjustment' => 0.00],
                    ['size' => '32', 'stock' => 12, 'price_adjustment' => 0.00],
                ]
            ],
            [
                'category_id' => $catMom->id,
                'name' => 'Mom Jeans Negro Focal',
                'description' => 'Corte mom jean retro, tiro súper alto, ajustado en cintura. Color negro sólido pre-lavado.',
                'base_price' => 179.90,
                'main_image' => 'https://ezzetacompany.com/wp-content/uploads/2025/12/MOM-JEAN-FOCAL-BLACK-6-800x1200.jpg',
                'color' => 'Negro',
                'variants' => [
                    ['size' => '28', 'stock' => 8, 'price_adjustment' => 5.00],
                    ['size' => '30', 'stock' => 14, 'price_adjustment' => 5.00],
                    ['size' => '32', 'stock' => 10, 'price_adjustment' => 5.00],
                ]
            ],

            // --- JEANS WIDE LEG ---
            [
                'category_id' => $catFlared->id,
                'name' => 'Jean Humo Flared',
                'description' => 'Jeans wide leg súper holgados, estilo skate de los 90. Confeccionados en color azul clásico.',
                'base_price' => 199.90,
                'main_image' => 'https://ezzetacompany.com/wp-content/uploads/2026/04/jean-humo-flared-hombre-3-800x1000.jpg',
                'color' => 'Azul',
                'variants' => [
                    ['size' => '28', 'stock' => 8, 'price_adjustment' => 0.00],
                    ['size' => '30', 'stock' => 12, 'price_adjustment' => 0.00],
                    ['size' => '32', 'stock' => 15, 'price_adjustment' => 0.00],
                    ['size' => '34', 'stock' => 10, 'price_adjustment' => 5.00],
                ]
            ],
            [
                'category_id' => $catFlared->id,
                'name' => 'Jean Ice Flared',
                'description' => 'Jeans wide leg súper holgados, estilo skate. Confeccionados en color negro pre-lavado.',
                'base_price' => 199.90,
                'main_image' => 'https://ezzetacompany.com/wp-content/uploads/2026/04/jean-ice-flared-hombre-3-600x800.jpg',
                'color' => 'Celeste',
                'variants' => [
                    ['size' => '28', 'stock' => 10, 'price_adjustment' => 0.00],
                    ['size' => '30', 'stock' => 15, 'price_adjustment' => 0.00],
                    ['size' => '32', 'stock' => 12, 'price_adjustment' => 0.00],
                    ['size' => '34', 'stock' => 6, 'price_adjustment' => 5.00],
                ]
            ],

            // --- POLERAS ---
            [
                'category_id' => $catPoleraBasica->id,
                'name' => 'Polera Basica Negro ',
                'description' => 'Polera con capucha oversized premium en color negro. Fabricada en franela reactiva perchada.',
                'base_price' => 149.90,
                'main_image' => 'https://ezzetacompany.com/wp-content/uploads/2026/05/CANGURO-NEGRO-3-800x1000.jpg',
                'color' => 'Negro',
                'variants' => [
                    ['size' => 'S', 'stock' => 30, 'price_adjustment' => 0.00],
                    ['size' => 'M', 'stock' => 45, 'price_adjustment' => 0.00],
                    ['size' => 'L', 'stock' => 40, 'price_adjustment' => 0.00],
                    ['size' => 'XL', 'stock' => 20, 'price_adjustment' => 5.00],
                ]
            ],
            [
                'category_id' => $catPoleraBasica->id,
                'name' => 'Polera Basica Blanco ',
                'description' => 'Polera con capucha oversized premium en color blanco. Fabricada en franela reactiva perchada.',
                'base_price' => 149.90,
                'main_image' => 'https://ezzetacompany.com/wp-content/uploads/2026/05/POLERA-BASICA-BLANCA-800x1000.jpg',
                'color' => 'Blanco',
                'variants' => [
                    ['size' => 'S', 'stock' => 15, 'price_adjustment' => 0.00],
                    ['size' => 'M', 'stock' => 25, 'price_adjustment' => 0.00],
                    ['size' => 'L', 'stock' => 20, 'price_adjustment' => 0.00],
                    ['size' => 'XL', 'stock' => 10, 'price_adjustment' => 5.00],
                ]
            ],
            [
                'category_id' => $catPoleraBasica->id,
                'name' => 'Polera Basica Melange',
                'description' => 'Polera cuello redondo sin capucha, corte boxy fit minimalista en color melange.',
                'base_price' => 139.90,
                'main_image' => 'https://ezzetacompany.com/wp-content/uploads/2026/05/POLERA-BASICA-MELANGE-3.jpg',
                'color' => 'Melange',
                'variants' => [
                    ['size' => 'S', 'stock' => 20, 'price_adjustment' => 0.00],
                    ['size' => 'M', 'stock' => 30, 'price_adjustment' => 0.00],
                    ['size' => 'L', 'stock' => 25, 'price_adjustment' => 0.00],
                    ['size' => 'XL', 'stock' => 15, 'price_adjustment' => 0.00],
                ]
            ],
            [
                'category_id' => $catPoleraCanguro->id,
                'name' => 'Polera Canguro Gargola',
                'description' => 'Polera con capucha oversized premium en color gargola. Fabricada en franela reactiva perchada.',
                'base_price' => 149.90,
                'main_image' => 'https://ezzetacompany.com/wp-content/uploads/2026/05/POLERA-BASICA-GARGOLA-3-1-800x1202.png',
                'color' => 'Gargola',
                'variants' => [
                    ['size' => 'S', 'stock' => 20, 'price_adjustment' => 0.00],
                    ['size' => 'M', 'stock' => 30, 'price_adjustment' => 0.00],
                    ['size' => 'L', 'stock' => 25, 'price_adjustment' => 0.00],
                    ['size' => 'XL', 'stock' => 15, 'price_adjustment' => 0.00],
                ]
            ],
            [
                'category_id' => $catPoleraCr->id,
                'name' => 'Polera CR Negro',
                'description' => 'Polera con capucha oversized premium en color gargola. Fabricada en franela reactiva perchada.',
                'base_price' => 149.90,
                'main_image' => 'https://ezzetacompany.com/wp-content/uploads/2026/06/POLERA-CR-NEGRO-2-800x1200.jpg',
                'color' => 'Negro',
                'variants' => [
                    ['size' => 'S', 'stock' => 20, 'price_adjustment' => 0.00],
                    ['size' => 'M', 'stock' => 30, 'price_adjustment' => 0.00],
                    ['size' => 'L', 'stock' => 25, 'price_adjustment' => 0.00],
                    ['size' => 'XL', 'stock' => 15, 'price_adjustment' => 0.00],
                ]
            ],


            // --- CASACAS ---
            [
                'category_id' => $catCasacaBasica->id,
                'name' => 'Casaca Basica Negro',
                'description' => 'Casaca de jean clásica de corte oversized en color negro.',
                'base_price' => 249.90,
                'main_image' => 'https://ezzetacompany.com/wp-content/uploads/2026/06/CASACA-BASICA-NEGRO-18-1.jpg',
                'color' => 'Negro',
                'variants' => [
                    ['size' => 'S', 'stock' => 10, 'price_adjustment' => 0.00],
                    ['size' => 'M', 'stock' => 15, 'price_adjustment' => 0.00],
                    ['size' => 'L', 'stock' => 12, 'price_adjustment' => 0.00],
                ]
            ],

                        [
                'category_id' => $catCasacaBasica->id,
                'name' => 'Casaca Basica Blanco',
                'description' => 'Casaca de jean clásica de corte oversized en color blanco.',
                'base_price' => 249.90,
                'main_image' => 'https://ezzetacompany.com/wp-content/uploads/2026/06/CASACA-BASICA-BLANCA-24-800x1200.jpg',
                'color' => 'Blanco',
                'variants' => [
                    ['size' => 'S', 'stock' => 10, 'price_adjustment' => 0.00],
                    ['size' => 'M', 'stock' => 15, 'price_adjustment' => 0.00],
                    ['size' => 'L', 'stock' => 12, 'price_adjustment' => 0.00],
                ]
            ],


            // --- POLOS ---
            [
                'category_id' => $catPoloPrime->id,
                'name' => 'Polo Prime Negro',
                'description' => 'Polo de corte boxy fit en color negro. Algodón peruano 20/1.',
                'base_price' => 79.90,
                'main_image' => 'https://ezzetacompany.com/wp-content/uploads/2025/12/polo-negro-prime-1-800x1000.png',
                'color' => 'Negro',
                'variants' => [
                    ['size' => 'S', 'stock' => 40, 'price_adjustment' => 0.00],
                    ['size' => 'M', 'stock' => 50, 'price_adjustment' => 0.00],
                    ['size' => 'L', 'stock' => 45, 'price_adjustment' => 0.00],
                ]
            ],
            [
                'category_id' => $catPoloSupremo->id,
                'name' => 'Polo Supremo Negro',
                'description' => 'Polo de corte boxy fit en color negro. Algodón peruano 20/1.',
                'base_price' => 79.90,
                'main_image' => 'https://ezzetacompany.com/wp-content/uploads/2026/07/POLO-SUPREMO-NEGRO-12.png',
                'color' => 'Negro',
                'variants' => [
                    ['size' => 'S', 'stock' => 30, 'price_adjustment' => 0.00],
                    ['size' => 'M', 'stock' => 45, 'price_adjustment' => 0.00],
                ]
            ],
            [
                'category_id' => $catPoloLuxury->id,
                'name' => 'Polo Luxury Negro',
                'description' => 'Polo grueso de alta densidad 300 GSM en color negro.',
                'base_price' => 99.90,
                'main_image' => 'https://ezzetacompany.com/wp-content/uploads/2026/05/POLO-LUXURY-NEGRO-3-800x1200.jpg',
                'color' => 'Negro',
                'variants' => [
                    ['size' => 'S', 'stock' => 20, 'price_adjustment' => 0.00],
                    ['size' => 'M', 'stock' => 30, 'price_adjustment' => 0.00],
                    ['size' => 'L', 'stock' => 25, 'price_adjustment' => 0.00],
                    ['size' => 'XL', 'stock' => 12, 'price_adjustment' => 0.00],
                ]
            ],
        ];

        // 3. Crear productos y variantes
        foreach ($productsData as $prod) {
            $slug = Str::slug($prod['name']);
            $product = Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $prod['category_id'],
                    'name' => $prod['name'],
                    'description' => $prod['description'],
                    'base_price' => $prod['base_price'],
                    'main_image' => $prod['main_image'],
                    'is_active' => true,
                ]
            );

            foreach ($prod['variants'] as $v) {
                $cleanColor = Str::slug($prod['color']);
                $sku = strtoupper("EZZ-{$slug}-{$v['size']}-{$cleanColor}");

                ProductVariant::updateOrCreate(
                    ['sku' => $sku],
                    [
                        'product_id' => $product->id,
                        'size' => $v['size'],
                        'color' => $prod['color'],
                        'price_adjustment' => $v['price_adjustment'],
                        'stock' => $v['stock'],
                        'image' => $prod['main_image']
                    ]
                );
            }
        }
    }
}
