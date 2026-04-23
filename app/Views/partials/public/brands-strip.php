<?php
/**
 * Faixa de marcas — premium minimalista.
 * Mix balanceado: símbolos icônicos (Apple, Motorola, Xiaomi) + wordmarks tipografados.
 * SVGs com width/height explícitos (evita width:0 no Chromium quando CSS usa 'auto').
 * Mesma ordem/quantidade (11) da referência Vernon: Apple, Samsung, Motorola, Xiaomi, Realme, Poco, ASUS, LG, Multilaser, Infinix, Tecno.
 */

// Somente os símbolos gráficos (ícones icônicos, bem reconhecíveis no formato monocromático).
// Simple Icons v11 · CC0 · viewBox 0 0 24 24.
$brandPaths = [
    'Apple'    => 'M12.152 6.896c-.948 0-2.415-1.078-3.96-1.04-2.04.027-3.91 1.183-4.961 3.014-2.117 3.675-.546 9.103 1.519 12.09 1.013 1.454 2.208 3.09 3.792 3.039 1.52-.065 2.09-.987 3.935-.987 1.831 0 2.35.987 3.96.948 1.637-.026 2.676-1.48 3.676-2.948 1.156-1.688 1.636-3.325 1.662-3.415-.039-.013-3.182-1.221-3.22-4.857-.026-3.04 2.48-4.494 2.597-4.559-1.429-2.09-3.623-2.324-4.39-2.376-2-.156-3.675 1.09-4.61 1.09zM15.53 3.83c.843-1.012 1.4-2.427 1.245-3.83-1.207.052-2.662.805-3.532 1.818-.78.896-1.454 2.338-1.273 3.714 1.338.104 2.715-.688 3.559-1.701',
    'Motorola' => 'M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12C24.002 5.375 18.632.002 12.007 0H12zm7.327 18.065s-.581-2.627-1.528-4.197c-.514-.857-1.308-1.553-2.368-1.532-.745 0-1.399.423-2.2 1.553-.469.77-.882 1.573-1.235 2.403 0 0-.29-.675-.63-1.343a8.038 8.038 0 0 0-.605-1.049c-.804-1.13-1.455-1.539-2.2-1.553-1.049-.021-1.854.675-2.364 1.528-.948 1.574-1.528 4.197-1.528 4.197h-.864l4.606-15.12 3.56 11.804.024.021.024-.021 3.56-11.804 4.61 15.113h-.862z',
    'Xiaomi'   => 'M12 0C8.016 0 4.756.255 2.493 2.516.23 4.776 0 8.033 0 12.012c0 3.98.23 7.235 2.494 9.497C4.757 23.77 8.017 24 12 24c3.983 0 7.243-.23 9.506-2.491C23.77 19.247 24 15.99 24 12.012c0-3.984-.233-7.243-2.502-9.504C19.234.252 15.978 0 12 0zM4.906 7.405h5.624c1.47 0 3.007.068 3.764.827.746.746.827 2.233.83 3.676v4.54a.15.15 0 0 1-.152.147h-1.947a.15.15 0 0 1-.152-.148V11.83c-.002-.806-.048-1.634-.464-2.051-.358-.36-1.026-.441-1.72-.458H7.158a.15.15 0 0 0-.151.147v6.98a.15.15 0 0 1-.152.148H4.906a.15.15 0 0 1-.15-.148V7.554a.15.15 0 0 1 .15-.149zm12.131 0h1.949a.15.15 0 0 1 .15.15v8.892a.15.15 0 0 1-.15.148h-1.949a.15.15 0 0 1-.151-.148V7.554a.15.15 0 0 1 .151-.149zM8.92 10.948h2.046c.083 0 .15.066.15.147v5.352a.15.15 0 0 1-.15.148H8.92a.15.15 0 0 1-.152-.148v-5.352a.15.15 0 0 1 .152-.147Z',
];

// Wordmarks tipografados · consistência total.
// (Samsung, ASUS e LG saíram do SVG pois os paths Simple Icons renderizam glifos finos demais
// num viewBox 24×24 — ficam imperceptíveis numa faixa de 20–22px de altura.)
$displayOrder = [
    ['brand' => 'Apple',      'type' => 'logo'],
    ['brand' => 'Samsung',    'type' => 'word'],
    ['brand' => 'Motorola',   'type' => 'logo'],
    ['brand' => 'Xiaomi',     'type' => 'logo'],
    ['brand' => 'realme',     'type' => 'word'],
    ['brand' => 'POCO',       'type' => 'word'],
    ['brand' => 'ASUS',       'type' => 'word'],
    ['brand' => 'LG',         'type' => 'word'],
    ['brand' => 'Multilaser', 'type' => 'word'],
    ['brand' => 'Infinix',    'type' => 'word'],
    ['brand' => 'TECNO',      'type' => 'word'],
];
?>
<section class="brands-strip" aria-label="Marcas atendidas">
    <div class="container brands-strip__head">
        <span class="brands-strip__eyebrow">Atendimento às principais marcas</span>
    </div>
    <div class="brands-strip__viewport">
        <div class="brands-strip__track" aria-hidden="false">
            <?php for ($pass = 0; $pass < 2; $pass++): ?>
                <?php foreach ($displayOrder as $item): $brand = $item['brand']; ?>
                    <?php if ($item['type'] === 'logo' && isset($brandPaths[$brand])): ?>
                        <span class="brands-strip__item brands-strip__item--logo" title="<?= e($brand) ?>" aria-label="<?= e($brand) ?>">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" role="img" aria-hidden="true">
                                <path d="<?= $brandPaths[$brand] ?>"/>
                            </svg>
                        </span>
                    <?php else: ?>
                        <span class="brands-strip__item brands-strip__item--word" title="<?= e($brand) ?>"><?= e($brand) ?></span>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endfor; ?>
        </div>
    </div>
</section>
