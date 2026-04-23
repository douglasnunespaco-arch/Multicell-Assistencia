<?php
namespace App\Controllers\Admin;

use App\Core\View;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Flash;
use App\Models\Branch;

final class UnitsController
{
    public function index(): string
    {
        Auth::requireLogin();
        return View::render('admin/units/index', [
            'page_title' => 'Unidades • Admin',
            'rows'       => Branch::all(),
        ], 'admin');
    }

    public function create(): string
    {
        Auth::requireLogin();
        return View::render('admin/units/form', ['page_title' => 'Nova unidade • Admin', 'row' => null], 'admin');
    }

    public function edit(array $params): string
    {
        Auth::requireLogin();
        $row = Branch::find((int) ($params['id'] ?? 0));
        if (!$row) { Flash::error('Unidade não encontrada.'); header('Location: /admin/units'); exit; }
        return View::render('admin/units/form', ['page_title' => 'Editar unidade • Admin', 'row' => $row], 'admin');
    }

    public function store(): string
    {
        Auth::requireLogin(); Csrf::verifyOrFail();
        try {
            $id = Branch::create($this->collect());
            Flash::success('Unidade criada.');
            header('Location: /admin/units/' . $id . '/edit'); exit;
        } catch (\Throwable $e) { Flash::error($e->getMessage()); header('Location: /admin/units/new'); exit; }
    }

    public function update(array $params): string
    {
        Auth::requireLogin(); Csrf::verifyOrFail();
        $id = (int) ($params['id'] ?? 0);
        if (!Branch::find($id)) { Flash::error('Unidade não encontrada.'); header('Location: /admin/units'); exit; }
        try { Branch::update($id, $this->collect()); Flash::success('Unidade atualizada.'); }
        catch (\Throwable $e) { Flash::error($e->getMessage()); }
        header('Location: /admin/units/' . $id . '/edit'); exit;
    }

    public function delete(array $params): string
    {
        Auth::requireLogin(); Csrf::verifyOrFail();
        $id = (int) ($params['id'] ?? 0);
        if (Branch::find($id)) { Branch::delete($id); Flash::success('Unidade removida.'); }
        header('Location: /admin/units'); exit;
    }

    private function collect(): array
    {
        return [
            'name'          => trim((string) ($_POST['name'] ?? '')),
            'address'       => trim((string) ($_POST['address'] ?? '')),
            'city'          => trim((string) ($_POST['city'] ?? '')),
            'state'         => strtoupper(trim((string) ($_POST['state'] ?? ''))),
            'zip_code'      => trim((string) ($_POST['zip_code'] ?? '')) ?: null,
            'phone'         => trim((string) ($_POST['phone'] ?? '')) ?: null,
            'whatsapp'      => trim((string) ($_POST['whatsapp'] ?? '')) ?: null,
            'hours_text'    => trim((string) ($_POST['hours_text'] ?? '')) ?: null,
            'latitude'      => $_POST['latitude']  !== '' ? (float) $_POST['latitude']  : null,
            'longitude'     => $_POST['longitude'] !== '' ? (float) $_POST['longitude'] : null,
            'map_embed_url' => trim((string) ($_POST['map_embed_url'] ?? '')) ?: null,
            'sort_order'    => (int) ($_POST['sort_order'] ?? 0),
            'is_active'     => !empty($_POST['is_active']) ? 1 : 0,
        ];
    }
}
