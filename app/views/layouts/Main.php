<?php
if (!isset($content)) {
    $content = '';
}
?>

<?php require __DIR__ . '/Header.php'; ?>

<main class="page-shell">
    <section class="page-content">
        <?php echo $content; ?>
    </section>
</main>

<?php require __DIR__ . '/Footer.php'; ?>