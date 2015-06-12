<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?= $this->pageTitle; ?></title>
    </head>
    <body>
        <?= $this->partial(APP_ROOT . 'layout/partials/header.php'); ?>

        <section role="main">
            <?= $this->yieldView(); ?>

        </section>
        <?= $this->partial(APP_ROOT . 'layout/partials/footer.php'); ?>

    </body>
</html>