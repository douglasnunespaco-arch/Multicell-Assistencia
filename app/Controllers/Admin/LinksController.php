<?php
namespace App\Controllers\Admin;

use App\Core\View;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Upload;
use App\Models\BioLink;

final class LinksController
{
    public function index(): string
    {
        Auth::requireLogin();
        return View::render('admin/links/index', [
            'page_title' => 'Links / Bio • Admin',
            'rows'       => BioLink::all(),
        ], 'admin');
    }

    public function create(): string
    {
        Auth::requireLogin();
        return View::render('admin/links/form', ['page_title' => 'Novo item • Admin', 'row' => null], 'admin');
    }

    public function edit(array $params): string
    {
        Auth::requireLogin();
        $row = BioLink::find((int) ($params['id'] ?? 0));
        if (!$row) { Flash::error('Item não encontrado.'); header('Location: /admin/links'); exit; }
        return View::render('admin/links/form', ['page_title' => 'Editar item • Admin', 'row' => $row], 'admin');
    }

    public function store(): string
    {
        Auth::requireLogin(); Csrf::verifyOrFail();
        $data = $this->collect();
        try {
            $img = Upload::save($_FILES['image'] ?? [], 'bio_links');
            if ($img) $data['image_path'] = $img;
            $id = BioLink::create($data);
            Flash::success('Item criado.');
            header('Location: /admin/links/' . $id . '/edit'); exit;
        } catch (\Throwable $e) { Flash::error($e->getMessage()); header('Location: /admin/links/new'); exit; }
    }

    public function update(array $params): string
    {
        Auth::requireLogin(); Csrf::verifyOrFail();
        $id  = (int) ($params['id'] ?? 0);
        $row = BioLink::find($id);
        if (!$row) { Flash::error('Item não encontrado.'); header('Location: /admin/links'); exit; }
        $data = $this->collect();
        try {
            $img = Upload::save($_FILES['image'] ?? [], 'bio_links');
            if ($img) { Upload::delete($row['image_path'] ?? null); $data['image_path'] = $img; }
            elseif (!empty($_POST['image_remove'])) { Upload::delete($row['image_path'] ?? null); $data['image_path'] = null; }
            BioLink::update($id, $data);
            Flash::success('Item atualizado.');
        } catch (\Throwable $e) { Flash::error($e->getMessage()); }
        header('Location: /admin/links/' . $id . '/edit'); exit;
    }

    public function delete(array $params): string
    {
        Auth::requireLogin(); Csrf::verifyOrFail();
        $id = (int) ($params['id'] ?? 0);
        $row = BioLink::find($id);
        if ($row) { Upload::delete($row['image_path'] ?? null); BioLink::delete($id); Flash::success('Item removido.'); }
        header('Location: /admin/links'); exit;
    }

    private function collect(): array
    {
        $type = (string) ($_POST['type'] ?? 'link');
        if (!in_array($type, ['link', 'banner'], true)) $type = 'link';
        $style = (string) ($_POST['style'] ?? 'default');
        if (!in_array($style, ['default', 'highlight'], true)) $style = 'default';
        $h = (int) ($_POST['height_px'] ?? 0);
        if ($h < 0) $h = 0;
        if ($h > 400) $h = 400;
        return [
            'title'        => trim((string) ($_POST['title'] ?? '')),
            'subtitle'     => trim((string) ($_POST['subtitle'] ?? '')) ?: null,
            'type'         => $type,
            'url'          => trim((string) ($_POST['url'] ?? '')),
            'icon'         => trim((string) ($_POST['icon'] ?? '')) ?: null,
            'style'        => $style,
            'height_px'    => $h,
            'sort_order'   => (int) ($_POST['sort_order'] ?? 0),
            'is_active'    => !empty($_POST['is_active']) ? 1 : 0,
            'open_new_tab' => !empty($_POST['open_new_tab']) ? 1 : 0,
        ];
    }
}
