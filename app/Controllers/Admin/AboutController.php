<?php
namespace App\Controllers\Admin;

use App\Core\View;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Upload;
use App\Models\AboutBlock;

final class AboutController
{
    public function index(): string
    {
        Auth::requireLogin();
        return View::render('admin/about/index', [
            'page_title' => 'Sobre • Admin',
            'rows'       => AboutBlock::all(),
        ], 'admin');
    }

    public function create(): string
    {
        Auth::requireLogin();
        return View::render('admin/about/form', ['page_title' => 'Novo bloco • Admin', 'row' => null], 'admin');
    }

    public function edit(array $params): string
    {
        Auth::requireLogin();
        $row = AboutBlock::find((int) ($params['id'] ?? 0));
        if (!$row) { Flash::error('Bloco não encontrado.'); header('Location: /admin/about'); exit; }
        return View::render('admin/about/form', ['page_title' => 'Editar bloco • Admin', 'row' => $row], 'admin');
    }

    public function store(): string
    {
        Auth::requireLogin(); Csrf::verifyOrFail();
        $data = $this->collect();
        try {
            $img = Upload::save($_FILES['image'] ?? [], 'about');
            if ($img) $data['image_path'] = $img;
            $id = AboutBlock::create($data);
            Flash::success('Bloco criado.');
            header('Location: /admin/about/' . $id . '/edit'); exit;
        } catch (\Throwable $e) { Flash::error($e->getMessage()); header('Location: /admin/about/new'); exit; }
    }

    public function update(array $params): string
    {
        Auth::requireLogin(); Csrf::verifyOrFail();
        $id = (int) ($params['id'] ?? 0);
        $row = AboutBlock::find($id);
        if (!$row) { Flash::error('Bloco não encontrado.'); header('Location: /admin/about'); exit; }
        $data = $this->collect();
        try {
            $img = Upload::save($_FILES['image'] ?? [], 'about');
            if ($img) { Upload::delete($row['image_path'] ?? null); $data['image_path'] = $img; }
            elseif (!empty($_POST['image_remove'])) { Upload::delete($row['image_path'] ?? null); $data['image_path'] = null; }
            AboutBlock::update($id, $data);
            Flash::success('Bloco atualizado.');
        } catch (\Throwable $e) { Flash::error($e->getMessage()); }
        header('Location: /admin/about/' . $id . '/edit'); exit;
    }

    public function delete(array $params): string
    {
        Auth::requireLogin(); Csrf::verifyOrFail();
        $id = (int) ($params['id'] ?? 0);
        $row = AboutBlock::find($id);
        if ($row) { Upload::delete($row['image_path'] ?? null); AboutBlock::delete($id); Flash::success('Bloco removido.'); }
        header('Location: /admin/about'); exit;
    }

    private function collect(): array
    {
        $layout = trim((string) ($_POST['layout'] ?? 'image-left'));
        if (!in_array($layout, ['image-left','image-right','full'], true)) $layout = 'image-left';
        return [
            'title'      => trim((string) ($_POST['title'] ?? '')),
            'content'    => trim((string) ($_POST['content'] ?? '')) ?: null,
            'layout'     => $layout,
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
            'is_active'  => !empty($_POST['is_active']) ? 1 : 0,
        ];
    }
}
