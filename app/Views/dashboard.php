<!doctype html>
<html lang="pt-BR">
<head>
    <?php require view_path('partials/head.php') ?>
</head>
<body>
<header class="header">
    <a class="anchor-home" href="/">Dashboard</a>

    <div class="account">
        <div>
            <p><?= date('d/m/Y H:i') ?></p>
        </div>
        <div>
            <p class="account-name"><?= escapeHtml($user['name']) ?></p>
        </div>

        <form action="/logout" method="POST">
            <input type="hidden" name="_method" value="DELETE">
            <button class="button" type="submit">Sair</button>
        </form>
    </div>
</header>

<?php require view_path('partials/toast.php') ?>

<main class="dashboard-container">
    <section class="dashboard-summary">
        <article class="summary-card summary-total">
            <p>Valor total dos seus serviços</p>
            <h2>
                R$ <?= escapeHtml(price_format($totalServicePrice)) ?>
            </h2>
        </article>

        <article class="summary-card">
            <h2>Últimos serviços pendentes</h2>

            <?php if (!$pendingServices) : ?>
                <p>Você não possui serviços pendentes.</p>
            <?php else : ?>
                <ul class="pending-list">
                    <?php foreach ($pendingServices as $pendingService) : ?>
                        <li>
                        <span>
                            #<?= escapeHtml($pendingService['id_service']) ?>
                            -
                            <?= escapeHtml($pendingService['description']) ?>
                        </span>

                            <strong>
                                R$ <?= escapeHtml(price_format($pendingService['price'])) ?>
                            </strong>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </article>
    </section>
    <section class="dashboard-actions">
        <a class="button button-primary" href="/services/create">Criar serviço</a>
    </section>
    <section class="filters-card">
        <div>
            <h2 class="filters-title">Filtrar serviços</h2>
        </div>
        <?php if ($filterErrors) : ?>
            <div class="filter-errors">
                <?php foreach ($filterErrors as $error) : ?>
                    <p><?= escapeHtml($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form action="/" method="GET" class="filters-form">
            <div class="filters-grid">
                <div class="field">
                    <label for="start_date">
                        Data inicial
                    </label>

                    <input
                            id="start_date"
                            name="start_date"
                            type="date"
                            value="<?= escapeHtml($filterValues['start_date'] ?? '') ?>"
                    >
                </div>

                <div class="field">
                    <label for="end_date">
                        Data final
                    </label>

                    <input
                            id="end_date"
                            name="end_date"
                            type="date"
                            value="<?= escapeHtml($filterValues['end_date'] ?? '') ?>"
                    >
                </div>

                <div class="field">
                    <label for="service_name">
                        Nome do serviço
                    </label>

                    <input
                            id="service_name"
                            name="service_name"
                            type="search"
                            maxlength="45"
                            placeholder="Ex.: Manutenção"
                            value="<?= escapeHtml($filterValues['service_name'] ?? '') ?>"
                    >
                </div>

                <div class="field">
                    <label for="status">
                        Status
                    </label>

                    <select id="status" name="status">
                        <option value="">
                            Todos
                        </option>

                        <option
                                value="pending"
                                <?= (($filterValues['status'] ?? '') === 'pending') ? 'selected' : '' ?>
                        >
                            Pendente
                        </option>

                        <option
                                value="finished"
                                <?= (($filterValues['status'] ?? '') === 'finished') ? 'selected' : '' ?>
                        >
                            Finalizado
                        </option>
                    </select>
                </div>

                <div class="field">
                    <label for="user_name">
                        Nome do usuário
                    </label>

                    <input
                            id="user_name"
                            name="user_name"
                            type="search"
                            maxlength="150"
                            placeholder="Ex.: João"
                            value="<?= escapeHtml($filterValues['user_name'] ?? '') ?>"
                    >
                </div>
            </div>

            <div class="filters-actions">
                <button class="button" type="submit">
                    Filtrar
                </button>

                <a class="button button-secondary" href="/">
                    Limpar
                </a>
            </div>
        </form>
    </section>
    <?php if (!$services) : ?>
        <p class="empty-state">Nenhum serviço encontrado.</p>
    <?php else : ?>
        <div class="table-container">
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Descrição</th>
                    <th>Status</th>
                    <th>Valor</th>
                    <th>Usuário</th>
                    <th>Ações</th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($services as $service) : ?>
                    <tr>
                        <td>
                            <?= escapeHtml($service['id_service']) ?>
                        </td>

                        <td>
                            <?= escapeHtml($service['description']) ?>
                        </td>

                        <td>
                            <?= escapeHtml($service['status']) ?>
                        </td>

                        <td>
                            R$ <?= escapeHtml(price_format((float)$service['price'])) ?>
                        </td>

                        <td>
                            <?= escapeHtml($service['user_name']) ?>
                        </td>

                        <td>
                            <div class="table-actions">
                                <?php if ($service['finished_at'] === null) : ?>
                                    <a class="button" href="/services/<?= escapeHtml($service['id_service']) ?>/edit">
                                        <i class="icon-pen-line"></i>
                                    </a>
                                <?php endif; ?>

                                <form action="/services/<?= escapeHtml($service['id_service']) ?>" method="POST">
                                    <input type="hidden" name="_method" value="DELETE">

                                    <button class="button button-secondary" type="submit">
                                        <i class="icon-trash"></i>
                                    </button>
                                </form>
                                <?php if ($service['finished_at'] === null) : ?>
                                    <form action="/services/<?= escapeHtml($service['id_service']) ?>/finish"
                                          method="POST">
                                        <button class="button button-success" type="submit">
                                            <i class="icon-circle-check-big"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</main>
</body>
</html>
