<?php
namespace App\Controllers\Public;

use App\Core\View;
use App\Core\Analytics;
use App\Models\Product;

final class ProductsController
{
    public function index(): string
    {
        $category = isset($_GET['categoria']) ? trim((string) $_GET['categoria']) : null;
        Analytics::track('page_view', '/produtos', null, null, $category ? 'cat:' . $category : null);
        return View::render('public/products-index', [
            'page_title' => 'Produtos • Multi Cell',
            'page_desc'  => 'Acessórios premium: capas, películas, carregadores, fones e mais. Consulte no WhatsApp.',
            'products'   => Product::active($category),
            'categories' => Product::categories(),
            'current_cat' => $category,
        ], 'public');
    }

    public function show(array $params): string
    {
        $product = Product::findBySlug($params['slug'] ?? '');
        if (!$product) {
            http_response_code(404);
            return View::render('public/404', [], '');
        }
        Analytics::track('page_view', '/produtos/' . $product['slug'], 'product', (int) $product['id']);
        return View::render('public/products-show', [
            'page_title' => $product['name'] . ' • Multi Cell',
            'page_desc'  => $product['short_description'] ?? '',
            'product'    => $product,
            'related'    => array_slice(array_filter(Product::active($product['category'] ?? null), fn ($p) => $p['id'] !== $product['id']), 0, 4),
        ], 'public');
    }
}
