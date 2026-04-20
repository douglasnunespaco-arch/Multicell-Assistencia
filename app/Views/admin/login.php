<?php
/**
 * @var array $errors
 * @var array $old
 * @var int|null $locked_until
 */
$errors = $errors ?? [];
$old    = $old    ?? [];
$locked = $locked_until && time() < $locked_until;
?>
<div class="login-shell" data-testid="login-shell">
    <div class="login-card">
        <a href="/" class="login-brand" data-testid="login-brand">
            <span class="admin-brand__dot"></span>
            <span>Multi Cell <small>painel administrativo</small></span>
        </a>

        <h1 class="login-title">Entrar no painel</h1>
        <p class="login-sub">Acesso restrito à equipe.</p>

        <form method="post" action="/admin/login" novalidate data-testid="login-form">
            <?= \App\Core\Csrf::field() ?>

            <?php if ($locked): ?>
                <div class="admin-flash admin-flash--error" data-testid="login-locked-alert">
                    Acesso temporariamente bloqueado por excesso de tentativas. Aguarde alguns minutos e tente novamente.
                </div>
            <?php endif; ?>

            <label class="admin-field">
                <span>E-mail</span>
                <input type="email" name="email" required autocomplete="username"
                       value="<?= e($old['email'] ?? '') ?>"
                       <?= $locked ? 'disabled' : '' ?>
                       data-testid="login-email">
                <?php if (!empty($errors['email'])): ?>
                    <small class="admin-field__error"><?= e($errors['email']) ?></small>
                <?php endif; ?>
            </label>

            <label class="admin-field">
                <span>Senha</span>
                <input type="password" name="password" required autocomplete="current-password"
                       <?= $locked ? 'disabled' : '' ?>
                       data-testid="login-password">
                <?php if (!empty($errors['password'])): ?>
                    <small class="admin-field__error"><?= e($errors['password']) ?></small>
                <?php endif; ?>
            </label>

            <button type="submit" class="btn btn--primary btn--block btn--lg"
                    <?= $locked ? 'disabled' : '' ?>
                    data-testid="login-submit">
                Entrar
            </button>
        </form>

        <p class="login-hint">
            <?= icon('shield-check', 14) ?>
            <span>Conexão criptografada. Tentativas são limitadas por segurança.</span>
        </p>
    </div>
    <a href="/" class="login-back" data-testid="login-back-link">← Voltar ao site</a>
</div>
