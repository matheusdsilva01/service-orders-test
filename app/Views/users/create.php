<form action="/users" method="POST">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?= escapeHtml($old['name'] ?? '') ?>" required>
    <?php if (isset($errors['name'])) : ?>
        <p><?= escapeHtml($errors['name']) ?></p>
    <?php endif; ?>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?= escapeHtml($old['email'] ?? '') ?>" required>
    <?php if (isset($errors['email'])) : ?>
        <p><?= escapeHtml($errors['email']) ?></p>
    <?php endif; ?>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <?php if (isset($errors['password'])) : ?>
        <p><?=escapeHtml($errors['password']) ?></p>
    <?php endif; ?>
    <button type="submit">Create User</button>
</form>