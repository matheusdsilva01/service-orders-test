<?php if (is_array($flashMessage) && isset($flashMessage['text'])) : ?>
    <?php
    $flashType = ($flashMessage['type'] ?? '') === 'success' ? 'success' : 'error';
    $flashRole = $flashType === 'success' ? 'status' : 'alert';
    ?>
    <div
            class="toast toast-<?= $flashType ?>"
            role="<?= $flashRole ?>"
            aria-live="<?= $flashType === 'success' ? 'polite' : 'assertive' ?>"
            data-toast
    >
        <?= escapeHtml($flashMessage['text']) ?>
    </div>
<?php endif; ?>
