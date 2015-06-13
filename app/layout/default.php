<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?= $this->pageTitle; ?></title>
    </head>
    <body>
        <?= $this->partial(APP_ROOT . 'layout/partials/header.php'); ?>

        <?php foreach($this->flashes() as $type => $messages): ?>
            <?php foreach($messages as $msg): ?>
                <div class="alert alert-<?= $type ?>"><?= $msg ?></div>
            <?php endforeach; ?>
        <?php endforeach; ?>

        <section role="main">
            <?= $this->yieldView(); ?>

        </section>
        <?= $this->partial(APP_ROOT . 'layout/partials/footer.php'); ?>

    </body>
</html>