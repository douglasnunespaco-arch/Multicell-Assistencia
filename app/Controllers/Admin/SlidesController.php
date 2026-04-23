<?php
namespace App\Controllers\Admin;

use App\Core\View;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Upload;
use App\Models\HeroSlide;

final class SlidesController
{
    public function index(): string
    {
        Auth::requireLogin();
        return View::render('admin/slides/index', [
            'page_title' => 'Slides • Admin',
            'rows'       => HeroSlide::all(),
        ], 'admin');
    }

    public function create(): string
    {
        Auth::requireLogin();
        return View::render('admin/slides/form', [
            'page_title' => 'Novo slide • Admin',
            'row'        => null,
        ], 'admin');
    }

    public function edit(array $params): string
    {
        Auth::requireLogin();
        $row = HeroSlide::find((int) ($params['id'] ?? 0));
        if (!$row) { Flash::error('Slide não encontrado.'); header('Location: /admin/slides'); exit; }
        return View::render('admin/slides/form', [
            'page_title' => 'Editar slide #' . $row['id'] . ' • Admin',
            'row'        => $row,
        ], 'admin');
    }

    public function store(): string
    {
        Auth::requireLogin(); Csrf::verifyOrFail();
        $data = $this->collect();
        try {
            $img = Upload::save($_FILES['image'] ?? [], 'hero');
            if ($img) $data['image_path'] = $img;
            $id = HeroSlide::create($data);
            Flash::success('Slide criado.');
            header('Location: /admin/slides/' . $id . '/edit'); exit;
        } catch (\Throwable $e) {
            Flash::error($e->getMessage());
            header('Location: /admin/slides/new'); exit;
        }
    }

    public function update(array $params): string
    {
        Auth::requireLogin(); Csrf::verifyOrFail();
        $id  = (int) ($params['id'] ?? 0);
        $row = HeroSlide::find($id);
        if (!$row) { Flash::error('Slide não encontrado.'); header('Location: /admin/slides'); exit; }
        $data = $this->collect();
        try {
            $img = Upload::save($_FILES['image'] ?? [], 'hero');
            if ($img) { Upload::delete($row['image_path'] ?? null); $data['image_path'] = $img; }
            elseif (!empty($_POST['image_remove'])) { Upload::delete($row['image_path'] ?? null); $data['image_path'] = null; }
            HeroSlide::update($id, $data);
            Flash::success('Slide atualizado.');
        } catch (\Throwable $e) { Flash::error($e->getMessage()); }
        header('Location: /admin/slides/' . $id . '/edit'); exit;
    }

    public function delete(array $params): string
    {
        Auth::requireLogin(); Csrf::verifyOrFail();
        $id = (int) ($params['id'] ?? 0);
        $row = HeroSlide::find($id);
        if ($row) { Upload::delete($row['image_path'] ?? null); HeroSlide::delete($id); Flash::success('Slide removido.'); }
        header('Location: /admin/slides'); exit;
    }

    private function collect(): array
    {
        return [
            'title'      => trim((string) ($_POST['title'] ?? '')) ?: null,
            'subtitle'   => trim((string) ($_POST['subtitle'] ?? '')) ?: null,
            'cta_label'  => trim((string) ($_POST['cta_label'] ?? '')) ?: null,
            'cta_url'    => trim((string) ($_POST['cta_url'] ?? '')) ?: null,
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
            'is_active'  => !empty($_POST['is_active']) ? 1 : 0,
        ];
    }
}
