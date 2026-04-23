<?php
namespace App\Controllers\Admin;

use App\Core\View;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Upload;
use App\Models\Product;

final class ProductsController
{
    public function index(): string
    {
        Auth::requireLogin();
        return View::render('admin/products/index', ['page_title' => 'Produtos • Admin', 'rows' => Product::all()], 'admin');
    }
    public function create(): string
    {
        Auth::requireLogin();
        return View::render('admin/products/form', ['page_title' => 'Novo produto • Admin', 'row' => null], 'admin');
    }
    public function edit(array $params): string
    {
        Auth::requireLogin();
        $row = Product::find((int) ($params['id'] ?? 0));
        if (!$row) { Flash::error('Produto não encontrado.'); header('Location: /admin/products'); exit; }
        return View::render('admin/products/form', ['page_title' => 'Editar produto • Admin', 'row' => $row], 'admin');
    }
    public function store(): string
    {
        Auth::requireLogin(); Csrf::verifyOrFail();
        $data = $this->collect();
        if (empty($data['slug'])) $data['slug'] = slugify($data['title']);
        try {
            $img = Upload::save($_FILES['image'] ?? [], 'products');
            if ($img) $data['image_path'] = $img;
            $id = Product::create($data);
            Flash::success('Produto criado.');
            header('Location: /admin/products/' . $id . '/edit'); exit;
        } catch (\Throwable $e) { Flash::error($e->getMessage()); header('Location: /admin/products/new'); exit; }
    }
    public function update(array $params): string
    {
        Auth::requireLogin(); Csrf::verifyOrFail();
        $id = (int) ($params['id'] ?? 0);
        $row = Product::find($id);
        if (!$row) { Flash::error('Produto não encontrado.'); header('Location: /admin/products'); exit; }
        $data = $this->collect();
        if (empty($data['slug'])) $data['slug'] = slugify($data['title']);
        try {
            $img = Upload::save($_FILES['image'] ?? [], 'products');
            if ($img) { Upload::delete($row['image_path'] ?? null); $data['image_path'] = $img; }
            elseif (!empty($_POST['image_remove'])) { Upload::delete($row['image_path'] ?? null); $data['image_path'] = null; }
            Product::update($id, $data);
            Flash::success('Produto atualizado.');
        } catch (\Throwable $e) { Flash::error($e->getMessage()); }
        header('Location: /admin/products/' . $id . '/edit'); exit;
    }
    public function delete(array $params): string
    {
        Auth::requireLogin(); Csrf::verifyOrFail();
        $id = (int) ($params['id'] ?? 0);
        $row = Product::find($id);
        if ($row) { Upload::delete($row['image_path'] ?? null); Product::delete($id); Flash::success('Produto removido.'); }
        header('Location: /admin/products'); exit;
    }
    private function collect(): array
    {
        return [
            'title'       => trim((string) ($_POST['title'] ?? '')),
            'slug'        => trim((string) ($_POST['slug'] ?? '')),
            'category'    => trim((string) ($_POST['category'] ?? '')) ?: null,
            'summary'     => trim((string) ($_POST['summary'] ?? '')) ?: null,
            'description' => trim((string) ($_POST['description'] ?? '')) ?: null,
            'price'       => $_POST['price']     !== '' ? (float) $_POST['price']     : null,
            'old_price'   => $_POST['old_price'] !== '' ? (float) $_POST['old_price'] : null,
            'cta_label'   => trim((string) ($_POST['cta_label'] ?? '')) ?: null,
            'cta_url'     => trim((string) ($_POST['cta_url'] ?? '')) ?: null,
            'sort_order'  => (int) ($_POST['sort_order'] ?? 0),
            'is_active'   => !empty($_POST['is_active'])   ? 1 : 0,
            'is_featured' => !empty($_POST['is_featured']) ? 1 : 0,
        ];
    }
}
