<?php
namespace App\Controllers\Admin;

use App\Core\View;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Flash;
use App\Models\Testimonial;

final class TestimonialsController
{
    public const SOURCES = ['google','instagram','facebook','tiktok','whatsapp','manual'];

    public function index(): string
    {
        Auth::requireLogin();
        return View::render('admin/testimonials/index', ['page_title' => 'Depoimentos • Admin', 'rows' => Testimonial::all()], 'admin');
    }
    public function create(): string
    {
        Auth::requireLogin();
        return View::render('admin/testimonials/form', ['page_title' => 'Novo depoimento • Admin', 'row' => null, 'sources' => self::SOURCES], 'admin');
    }
    public function edit(array $params): string
    {
        Auth::requireLogin();
        $row = Testimonial::find((int) ($params['id'] ?? 0));
        if (!$row) { Flash::error('Depoimento não encontrado.'); header('Location: /admin/testimonials'); exit; }
        return View::render('admin/testimonials/form', ['page_title' => 'Editar depoimento • Admin', 'row' => $row, 'sources' => self::SOURCES], 'admin');
    }
    public function store(): string
    {
        Auth::requireLogin(); Csrf::verifyOrFail();
        $data = $this->collect();
        try {
            $id = Testimonial::create($data);
            Flash::success('Depoimento criado.');
            header('Location: /admin/testimonials/' . $id . '/edit'); exit;
        } catch (\Throwable $e) { Flash::error($e->getMessage()); header('Location: /admin/testimonials/new'); exit; }
    }
    public function update(array $params): string
    {
        Auth::requireLogin(); Csrf::verifyOrFail();
        $id = (int) ($params['id'] ?? 0);
        if (!Testimonial::find($id)) { Flash::error('Depoimento não encontrado.'); header('Location: /admin/testimonials'); exit; }
        try { Testimonial::update($id, $this->collect()); Flash::success('Depoimento atualizado.'); }
        catch (\Throwable $e) { Flash::error($e->getMessage()); }
        header('Location: /admin/testimonials/' . $id . '/edit'); exit;
    }
    public function delete(array $params): string
    {
        Auth::requireLogin(); Csrf::verifyOrFail();
        $id = (int) ($params['id'] ?? 0);
        if (Testimonial::find($id)) { Testimonial::delete($id); Flash::success('Depoimento removido.'); }
        header('Location: /admin/testimonials'); exit;
    }
    private function collect(): array
    {
        $rating = max(1, min(5, (int) ($_POST['rating'] ?? 5)));
        $source = $_POST['source'] ?? 'manual';
        if (!in_array($source, self::SOURCES, true)) $source = 'manual';
        return [
            'author_name' => trim((string) ($_POST['author_name'] ?? '')),
            'rating'      => $rating,
            'content'     => trim((string) ($_POST['content'] ?? '')),
            'source'      => $source,
            'sort_order'  => (int) ($_POST['sort_order'] ?? 0),
            'is_active'   => !empty($_POST['is_active']) ? 1 : 0,
        ];
    }
}
