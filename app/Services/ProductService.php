<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Exception;

class ProductService
{
    public function getAllProducts(array $filters = []): array
    {
        try {
            $query = Product::with(['category', 'variants'])->where('is_active', true);

            // Filtrar por categoría (slug o id)
            if (isset($filters['category']) && !empty($filters['category'])) {
                $categorySlug = $filters['category'];
                $query->whereHas('category', function ($q) use ($categorySlug) {
                    $q->where('slug', $categorySlug)
                      ->orWhereHas('parent', function ($pq) use ($categorySlug) {
                          $pq->where('slug', $categorySlug);
                      });
                });
            }

            // Filtrar por término de búsqueda (ej. baggy, mom, crewneck)
            if (isset($filters['search']) && !empty($filters['search'])) {
                $search = $filters['search'];
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Filtrar por precio mínimo (precio base + ajuste de variante)
            if (isset($filters['min_price']) && is_numeric($filters['min_price'])) {
                $minPrice = floatval($filters['min_price']);
                $query->whereHas('variants', function ($q) use ($minPrice) {
                    $q->whereRaw('(products.base_price + price_adjustment) >= ?', [$minPrice]);
                });
            }

            // Filtrar por precio máximo
            if (isset($filters['max_price']) && is_numeric($filters['max_price'])) {
                $maxPrice = floatval($filters['max_price']);
                $query->whereHas('variants', function ($q) use ($maxPrice) {
                    $q->whereRaw('(products.base_price + price_adjustment) <= ?', [$maxPrice]);
                });
            }

            // Filtrar por tallas (sizes[] array)
            if (isset($filters['sizes']) && is_array($filters['sizes']) && !empty($filters['sizes'])) {
                $sizes = $filters['sizes'];
                $query->whereHas('variants', function ($q) use ($sizes) {
                    $q->whereIn('size', $sizes);
                });
            }

            // Filtrar por colores (colors[] array)
            if (isset($filters['colors']) && is_array($filters['colors']) && !empty($filters['colors'])) {
                $colors = $filters['colors'];
                $query->whereHas('variants', function ($q) use ($colors) {
                    $q->whereIn('color', $colors);
                });
            }

            // Ordenamiento por precio
            if (isset($filters['sort']) && !empty($filters['sort'])) {
                if ($filters['sort'] === 'price_asc') {
                    $query->orderBy('base_price', 'asc');
                } elseif ($filters['sort'] === 'price_desc') {
                    $query->orderBy('base_price', 'desc');
                }
            }

            return $query->get()->toArray();
        } catch (Exception $e) {
            throw new Exception("Error al obtener productos: " . $e->getMessage());
        }
    }

    public function getProductBySlug(string $slug): Product
    {
        try {
            $product = Product::with(['category', 'variants'])
                ->where('slug', $slug)
                ->where('is_active', true)
                ->first();

            if (!$product) {
                throw new Exception("Producto no encontrado", 404);
            }

            return $product;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function createProduct(array $data): Product
    {
        DB::beginTransaction();
        try {
            // Generar slug
            $slug = Str::slug($data['name']);
            // Asegurar que sea único
            $originalSlug = $slug;
            $count = 1;
            while (Product::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }

            $product = Product::create([
                'category_id' => $data['category_id'],
                'name' => $data['name'],
                'slug' => $slug,
                'description' => $data['description'] ?? null,
                'base_price' => $data['base_price'],
                'main_image' => $data['main_image'],
                'is_active' => true,
            ]);

            // Crear las variantes
            foreach ($data['variants'] as $variant) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'size' => $variant['size'],
                    'color' => $variant['color'],
                    'price_adjustment' => $variant['price_adjustment'] ?? 0.00,
                    'stock' => $variant['stock'],
                    'sku' => $variant['sku'],
                ]);
            }

            DB::commit();
            return $product->load('variants');
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Error al crear el producto: " . $e->getMessage());
        }
    }

    public function updateProduct(int $id, array $data): Product
    {
        DB::beginTransaction();
        try {
            $product = Product::find($id);
            if (!$product) {
                throw new Exception("Producto no encontrado", 404);
            }

            // Si cambia el nombre, regenerar slug
            if ($product->name !== $data['name']) {
                $slug = Str::slug($data['name']);
                $originalSlug = $slug;
                $count = 1;
                while (Product::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                    $slug = $originalSlug . '-' . $count++;
                }
                $product->slug = $slug;
            }

            $product->update([
                'category_id' => $data['category_id'],
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'base_price' => $data['base_price'],
                'main_image' => $data['main_image'],
            ]);

            // Sincronizar variantes
            $variantIdsToKeep = [];
            foreach ($data['variants'] as $variantData) {
                if (isset($variantData['id']) && !empty($variantData['id'])) {
                    // Actualizar variante existente
                    $variant = ProductVariant::where('product_id', $product->id)->find($variantData['id']);
                    if ($variant) {
                        $variant->update([
                            'size' => $variantData['size'],
                            'color' => $variantData['color'],
                            'price_adjustment' => $variantData['price_adjustment'] ?? 0.00,
                            'stock' => $variantData['stock'],
                            'sku' => $variantData['sku'],
                        ]);
                        $variantIdsToKeep[] = $variant->id;
                    }
                } else {
                    // Crear nueva variante
                    $newVariant = ProductVariant::create([
                        'product_id' => $product->id,
                        'size' => $variantData['size'],
                        'color' => $variantData['color'],
                        'price_adjustment' => $variantData['price_adjustment'] ?? 0.00,
                        'stock' => $variantData['stock'],
                        'sku' => $variantData['sku'],
                    ]);
                    $variantIdsToKeep[] = $newVariant->id;
                }
            }

            // Eliminar las variantes que no estén en la lista enviada
            ProductVariant::where('product_id', $product->id)
                ->whereNotIn('id', $variantIdsToKeep)
                ->delete();

            DB::commit();
            return $product->load('variants');
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Error al actualizar el producto: " . $e->getMessage());
        }
    }

    public function deleteProduct(int $id): Product
    {
        try {
            $product = Product::find($id);
            if (!$product) {
                throw new Exception("Producto no encontrado", 404);
            }

            // "Desactivar" el producto
            $product->update(['is_active' => false]);
            return $product;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
