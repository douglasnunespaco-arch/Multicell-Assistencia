<?php
namespace App\Controllers\Public;

use App\Core\View;
use App\Core\Analytics;
use App\Models\HeroSlide;
use App\Models\Service;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\Testimonial;
use App\Models\Branch;

final class HomeController
{
    public function index(): string
    {
        Analytics::track('page_view', '/');

        return View::render('public/home', [
            'page_title'    => \App\Models\Setting::get('seo_title', 'Multi Cell Assistência Técnica'),
            'page_desc'     => \App\Models\Setting::get('seo_description'),
            'slides'        => HeroSlide::active(),
            'services'      => array_slice(Service::active(), 0, 6),
            'products'      => array_slice(Product::active(), 0, 8),
            'promotions'    => Promotion::active(6),
            'testimonials'  => Testimonial::active(6),
            'branch'        => Branch::primary(),
        ], 'public');
    }
}
