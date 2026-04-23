<?php
namespace App\Controllers\Public;

use App\Core\View;
use App\Core\Analytics;
use App\Core\Database;
use App\Models\Product;

/**
 * Acessórios — lista todos os produtos ativos cuja categoria NÃO seja 'Seminovos'.
 * Chips de subcategoria reaproveitam Product::categories() filtradas.
 */
final class AcessoriosController
{
    public function index(): string
    {
        $category = isset($_GET['categoria']) ? trim((string) $_GET['categoria']) : null;
        // Normaliza: evita cair em "Seminovos" via query string nesta página
        if ($category !== null && strcasecmp($category, 'Seminovos') === 0) {
            $category = null;
        }

        if ($category) {
            $products = Database::fetchAll(
                'SELECT * FROM products
                 WHERE is_active = 1 AND category = :c
                 ORDER BY sort_order ASC, id ASC',
                ['c' => $category]
            );
        } else {
            $products = Database::fetchAll(
                "SELECT * FROM products
                 WHERE is_active = 1 AND (category IS NULL OR category <> 'Seminovos')
                 ORDER BY sort_order ASC, id ASC"
            );
        }

        $categories = array_values(array_filter(
            Product::categories(),
            static fn(string $c): bool => strcasecmp($c, 'Seminovos') !== 0
        ));

        Analytics::track('page_view', '/acessorios', null, null, $category ? 'cat:' . $category : 'acessorios');
        return View::render('public/acessorios', [
            'page_title'  => 'Acessórios • Multi Cell',
            'page_desc'   => 'Capas, películas, carregadores, fones e mais. Compre sem sair de casa pelo WhatsApp.',
            'products'    => $products,
            'categories'  => $categories,
            'current_cat' => $category,
        ], 'public');
    }
}
