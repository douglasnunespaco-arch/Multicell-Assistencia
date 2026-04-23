<?php
namespace App\Controllers\Admin;

use App\Core\View;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Upload;
use App\Models\Service;

final class ServicesController
{
    public function index(): string
    {
        Auth::requireLogin();
        return View::render('admin/services/index', [
            'page_title' => 'Serviços • Admin',
            'rows'       => Service::all(),
        ], 'admin');
    }

    public function create(): string
    {
        Auth::requireLogin();
        return View::render('admin/services/form', ['page_title' => 'Novo serviço • Admin', 'row' => null], 'admin');
    }

    public function edit(array $params): string
    {
        Auth::requireLogin();
        $row = Service::find((int) ($params['id'] ?? 0));
        if (!$row) { Flash::error('Serviço não encontrado.'); header('Location: /admin/services'); exit; }
        return View::render('admin/services/form', ['page_title' => 'Editar serviço • Admin', 'row' => $row], 'admin');
    }

    public function store(): string
    {
        Auth::requireLogin(); Csrf::verifyOrFail();
        $data = $this->collect();
        if (empty($data['slug'])) $data['slug'] = slugify($data['title']);
        try {
            $img = Upload::save($_FILES['image'] ?? [], 'services');
            if ($img) $data['image_path'] = $img;
            $id = Service::create($data);
            Flash::success('Serviço criado.');
            header('Location: /admin/services/' . $id . '/edit'); exit;
        } catch (\Throwable $e) { Flash::error($e->getMessage()); header('Location: /admin/services/new'); exit; }
    }

    public function update(array $params): string
    {
        Auth::requireLogin(); Csrf::verifyOrFail();
        $id = (int) ($params['id'] ?? 0);
        $row = Service::find($id);
        if (!$row) { Flash::error('Serviço não encontrado.'); header('Location: /admin/services'); exit; }
        $data = $this->collect();
        if (empty($data['slug'])) $data['slug'] = slugify($data['title']);
        try {
            $img = Upload::save($_FILES['image'] ?? [], 'services');
            if ($img) { Upload::delete($row['image_path'] ?? null); $data['image_path'] = $img; }
            elseif (!empty($_POST['image_remove'])) { Upload::delete($row['image_path'] ?? null); $data['image_path'] = null; }
            Service::update($id, $data);
            Flash::success('Serviço atualizado.');
        } catch (\Throwable $e) { Flash::error($e->getMessage()); }
        header('Location: /admin/services/' . $id . '/edit'); exit;
    }

    public function delete(array $params): string
    {
        Auth::requireLogin(); Csrf::verifyOrFail();
        $id = (int) ($params['id'] ?? 0);
        $row = Service::find($id);
        if ($row) { Upload::delete($row['image_path'] ?? null); Service::delete($id); Flash::success('Serviço removido.'); }
        header('Location: /admin/services'); exit;
    }

    private function collect(): array
    {
        return [
            'title'       => trim((string) ($_POST['title'] ?? '')),
            'slug'        => trim((string) ($_POST['slug'] ?? '')),
            'summary'     => trim((string) ($_POST['summary'] ?? '')) ?: null,
            'description' => trim((string) ($_POST['description'] ?? '')) ?: null,
            'icon'        => trim((string) ($_POST['icon'] ?? '')) ?: null,
            'sort_order'  => (int) ($_POST['sort_order'] ?? 0),
            'is_active'   => !empty($_POST['is_active']) ? 1 : 0,
            'is_featured' => !empty($_POST['is_featured']) ? 1 : 0,
        ];
    }
}
