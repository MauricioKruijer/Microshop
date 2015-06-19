<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?= $this->pageTitle; ?></title>
    </head>
    <body>
        <?= $this->partial(APP_ROOT . 'layout/partials/header.php'); // Include header?>
        <?php
        // Display service flash messages in main layout
        // @todo consider handling this per view or global partial
        ?>
        <?php foreach($this->flashes() as $type => $messages): ?>
            <?php foreach($messages as $msg): ?>
                <div class="alert alert-<?= $type ?>"><?= $msg ?></div>
            <?php endforeach; ?>
        <?php endforeach; ?>

        <section role="main">
            <?php /* This is where the app lives */ ?>
            <?= $this->yieldView(); ?>

        </section>
        <?= $this->partial(APP_ROOT . 'layout/partials/footer.php'); // Include footer?>

    </body>
</html>