<?php
/** @var array $t */
$rating = max(0, min(5, (int) ($t['rating'] ?? 5)));
$initial = mb_strtoupper(mb_substr($t['author_name'] ?? 'A', 0, 1));
?>
<article class="testimonial" data-reveal>
    <div class="testimonial__stars" aria-label="<?= $rating ?> de 5 estrelas">
        <?php for ($i = 0; $i < $rating; $i++) echo icon('star', 16); ?>
    </div>
    <p class="testimonial__content">“<?= e($t['content']) ?>”</p>
    <div class="testimonial__author">
        <div class="testimonial__avatar" aria-hidden="true"><?= e($initial) ?></div>
        <div class="testimonial__meta">
            <strong><?= e($t['author_name']) ?></strong>
            <?php if (!empty($t['source'])): ?><span>via <?= e($t['source']) ?></span><?php endif; ?>
        </div>
    </div>
</article>
