<!doctype html>
<html lang="pt-BR">
<head>
    <?php require view_path('partials/head.php') ?>
</head>
<body>
    <main class="login-container">
        <section class="login-form-container">
            <h1>Entrar</h1>
            <?php if (isset($errors['credentials'])) : ?>
            <div class="field-error-container">
                <p class="field-error"><?= escapeHtml($errors['credentials']) ?></p>
            </div>
            <?php endif; ?>

            <form class="form"
                  action="/login" method="POST">
                <div class="field">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        maxlength="100"
                        autocomplete="email"
                        value="<?= escapeHtml($old['email'] ?? '') ?>"
                        <?= isset($errors['email']) ? 'aria-invalid="true" aria-describedby="email-error"' : '' ?>
                        required
                    >
                </div>

                <div class="field">
                    <label for="password">Senha</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        maxlength="255"
                        autocomplete="current-password"
                        <?= isset($errors['password']) ? 'aria-invalid="true" aria-describedby="password-error"' : '' ?>
                        required
                    >
                </div>

                <button class="button" type="submit">Entrar</button>
            </form>
        </section>
    </main>
</body>
</html>
