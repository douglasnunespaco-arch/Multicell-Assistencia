<?php
namespace App\Controllers\Admin;

use App\Core\View;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Upload;
use App\Models\Promotion;

final class PromotionsController
{
    public function index(): string
    {
        Auth::requireLogin();
        return View::render('admin/promotions/index', ['page_title' => 'Promoções • Admin', 'rows' => Promotion::all()], 'admin');
    }
    public function create(): string
    {
        Auth::requireLogin();
        return View::render('admin/promotions/form', ['page_title' => 'Nova promoção • Admin', 'row' => null], 'admin');
    }
    public function edit(array $params): string
    {
        Auth::requireLogin();
        $row = Promotion::find((int) ($params['id'] ?? 0));
        if (!$row) { Flash::error('Promoção não encontrada.'); header('Location: /admin/promotions'); exit; }
        return View::render('admin/promotions/form', ['page_title' => 'Editar promoção • Admin', 'row' => $row], 'admin');
    }
    public function store(): string
    {
        Auth::requireLogin(); Csrf::verifyOrFail();
        $data = $this->collect();
        if (empty($data['slug'])) $data['slug'] = slugify($data['title']);
        try {
            $img = Upload::save($_FILES['image'] ?? [], 'promotions');
            if ($img) $data['image_path'] = $img;
            $id = Promotion::create($data);
            Flash::success('Promoção criada.');
            header('Location: /admin/promotions/' . $id . '/edit'); exit;
        } catch (\Throwable $e) { Flash::error($e->getMessage()); header('Location: /admin/promotions/new'); exit; }
    }
    public function update(array $params): string
    {
        Auth::requireLogin(); Csrf::verifyOrFail();
        $id = (int) ($params['id'] ?? 0);
        $row = Promotion::find($id);
        if (!$row) { Flash::error('Promoção não encontrada.'); header('Location: /admin/promotions'); exit; }
        $data = $this->collect();
        if (empty($data['slug'])) $data['slug'] = slugify($data['title']);
        try {
            $img = Upload::save($_FILES['image'] ?? [], 'promotions');
            if ($img) { Upload::delete($row['image_path'] ?? null); $data['image_path'] = $img; }
            elseif (!empty($_POST['image_remove'])) { Upload::delete($row['image_path'] ?? null); $data['image_path'] = null; }
            Promotion::update($id, $data);
            Flash::success('Promoção atualizada.');
        } catch (\Throwable $e) { Flash::error($e->getMessage()); }
        header('Location: /admin/promotions/' . $id . '/edit'); exit;
    }
    public function delete(array $params): string
    {
        Auth::requireLogin(); Csrf::verifyOrFail();
        $id = (int) ($params['id'] ?? 0);
        $row = Promotion::find($id);
        if ($row) { Upload::delete($row['image_path'] ?? null); Promotion::delete($id); Flash::success('Promoção removida.'); }
        header('Location: /admin/promotions'); exit;
    }
    private function collect(): array
    {
        return [
            'title'       => trim((string) ($_POST['title'] ?? '')),
            'slug'        => trim((string) ($_POST['slug'] ?? '')),
            'description' => trim((string) ($_POST['description'] ?? '')) ?: null,
            'old_price'   => $_POST['old_price'] !== '' ? (float) $_POST['old_price'] : null,
            'new_price'   => $_POST['new_price'] !== '' ? (float) $_POST['new_price'] : null,
            'cta_label'   => trim((string) ($_POST['cta_label'] ?? '')) ?: null,
            'cta_url'     => trim((string) ($_POST['cta_url'] ?? '')) ?: null,
            'ends_at'     => !empty($_POST['ends_at']) ? $_POST['ends_at'] : null,
            'sort_order'  => (int) ($_POST['sort_order'] ?? 0),
            'is_active'   => !empty($_POST['is_active']) ? 1 : 0,
        ];
    }
}
