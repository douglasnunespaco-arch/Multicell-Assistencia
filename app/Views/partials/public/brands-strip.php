<?php
/**
 * Faixa de marcas — bloco institucional logo abaixo do hero.
 * CSS-only marquee · leve · sem logos oficiais.
 */
$brands = ['Apple', 'Samsung', 'Xiaomi', 'Motorola', 'OPPO', 'Realme', 'JBL', 'QCY', 'Asus', 'Lenovo'];
?>
<section class="brands-strip" aria-label="Marcas atendidas">
    <div class="container brands-strip__head">
        <span class="brands-strip__eyebrow">Atendemos as principais marcas</span>
    </div>
    <div class="brands-strip__viewport">
        <div class="brands-strip__track" aria-hidden="false">
            <?php // Duplicamos a lista para loop contínuo sem "salto". ?>
            <?php for ($pass = 0; $pass < 2; $pass++): ?>
                <?php foreach ($brands as $brand): ?>
                    <span class="brands-strip__item"><?= e($brand) ?></span>
                    <span class="brands-strip__sep" aria-hidden="true">•</span>
                <?php endforeach; ?>
            <?php endfor; ?>
        </div>
    </div>
</section>
