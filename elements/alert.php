<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!defined('SWEETALERT_INCLUDED')) {
    define('SWEETALERT_INCLUDED', true);
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
}

if (!empty($_SESSION['alert'])) {
    $alert = $_SESSION['alert'];

    $title = htmlspecialchars($alert['title'] ?? '', ENT_QUOTES, 'UTF-8');
    $text = htmlspecialchars($alert['text'] ?? '', ENT_QUOTES, 'UTF-8');
    $icon = htmlspecialchars($alert['icon'] ?? 'info', ENT_QUOTES, 'UTF-8');
    $iconColor = htmlspecialchars($alert['iconColor'] ?? '', ENT_QUOTES, 'UTF-8');

    ?>
    <script>
        Swal.fire({
            title: "<?= $title ?>",
            text: "<?= $text ?>",
            icon: "<?= $icon ?>",
            confirmButtonColor: "#2563eb",
            <?php if (!empty($iconColor)): ?>
                iconColor: "<?= $iconColor ?>",
            <?php endif; ?>
        });
    </script>
    <?php

    $_SESSION['alert'] = null;
}
