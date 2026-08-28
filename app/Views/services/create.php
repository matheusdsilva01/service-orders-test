<!doctype html>
<html lang="pt-BR">
<head>
    <?php require view_path('partials/head.php') ?>
</head>

<body>
<header class="header">
    <a class="anchor-home" href="/">Dashboard</a>
</header>

<main class="service-edit-page">
    <section>
        <h1>Criar serviço</h1>

        <form
                class="form"
                action="/services"
                method="POST"
        >
            <div class="field">
                <label for="description">Descrição</label>

                <input
                        id="description"
                        name="description"
                        type="text"
                        maxlength="45"
                        value="<?= escapeHtml($old['description'] ?? '') ?>"
                        required
                >

                <?php if (isset($errors['description'])) : ?>
                    <p class="field-error">
                        <?= escapeHtml($errors['description']) ?>
                    </p>
                <?php endif; ?>
            </div>

            <div class="field">
                <label for="price">Valor</label>

                <input
                        id="price"
                        name="price"
                        type="number"
                        min="0.001"
                        max="99999999.999"
                        step="0.001"
                        value="<?= escapeHtml($old['price'] ?? '') ?>"
                        inputmode="decimal"
                        required
                >

                <?php if (isset($errors['price'])) : ?>
                    <p class="field-error">
                        <?= escapeHtml($errors['price']) ?>
                    </p>
                <?php endif; ?>
            </div>

            <div class="form-actions">
                <a class="button button-secondary" href="/">
                    Cancelar
                </a>

                <button class="button" type="submit">
                    Criar serviço
                </button>
            </div>
        </form>
    </section>
</main>
</body>
</html>