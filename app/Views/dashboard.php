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
            <p class="account-name"><?= escapeHtml($user['name']) ?></p>
        </div>

        <form action="/logout" method="POST">
            <input type="hidden" name="_method" value="DELETE">
            <button class="button" type="submit">Sair</button>
        </form>
    </div>
</header>

<main class="dashboard-container">
    <p>Dashboard</p>
</main>
</body>
</html>
