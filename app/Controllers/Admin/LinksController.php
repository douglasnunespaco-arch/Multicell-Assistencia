<?php
namespace App\Controllers\Admin;

use App\Core\View;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Flash;
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
        return View::render('admin/links/form', ['page_title' => 'Novo link • Admin', 'row' => null], 'admin');
    }

    public function edit(array $params): string
    {
        Auth::requireLogin();
        $row = BioLink::find((int) ($params['id'] ?? 0));
        if (!$row) { Flash::error('Link não encontrado.'); header('Location: /admin/links'); exit; }
        return View::render('admin/links/form', ['page_title' => 'Editar link • Admin', 'row' => $row], 'admin');
    }

    public function store(): string
    {
        Auth::requireLogin(); Csrf::verifyOrFail();
        try {
            $id = BioLink::create($this->collect());
            Flash::success('Link criado.');
            header('Location: /admin/links/' . $id . '/edit'); exit;
        } catch (\Throwable $e) { Flash::error($e->getMessage()); header('Location: /admin/links/new'); exit; }
    }

    public function update(array $params): string
    {
        Auth::requireLogin(); Csrf::verifyOrFail();
        $id = (int) ($params['id'] ?? 0);
        if (!BioLink::find($id)) { Flash::error('Link não encontrado.'); header('Location: /admin/links'); exit; }
        try { BioLink::update($id, $this->collect()); Flash::success('Link atualizado.'); }
        catch (\Throwable $e) { Flash::error($e->getMessage()); }
        header('Location: /admin/links/' . $id . '/edit'); exit;
    }

    public function delete(array $params): string
    {
        Auth::requireLogin(); Csrf::verifyOrFail();
        $id = (int) ($params['id'] ?? 0);
        if (BioLink::find($id)) { BioLink::delete($id); Flash::success('Link removido.'); }
        header('Location: /admin/links'); exit;
    }

    private function collect(): array
    {
        return [
            'title'        => trim((string) ($_POST['title'] ?? '')),
            'url'          => trim((string) ($_POST['url'] ?? '')),
            'icon'         => trim((string) ($_POST['icon'] ?? '')) ?: null,
            'sort_order'   => (int) ($_POST['sort_order'] ?? 0),
            'is_active'    => !empty($_POST['is_active']) ? 1 : 0,
            'open_new_tab' => !empty($_POST['open_new_tab']) ? 1 : 0,
        ];
    }
}
