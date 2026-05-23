<?php
if (!isset($content)) {
    $content = '';
}
?>

<?php require __DIR__ . '/header.php'; ?>
<?php require __DIR__ . '/navbar.php'; ?>

<main class="page-shell">
    <section class="page-content">
        <?php echo $content; ?>
    </section>
</main>

<?php require __DIR__ . '/footer.php'; ?>