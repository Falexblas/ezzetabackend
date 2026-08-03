<?php

namespace App\Services;

use App\Models\Category;
use Exception;

class CategoryService
{
    public function getAllCategories(): array
    {
        try {
            return Category::with(['parent', 'children'])->get()->toArray();
        } catch (Exception $e) {
            throw new Exception("Error al obtener las categorías: " . $e->getMessage());
        }
    }
}
