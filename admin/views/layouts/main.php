<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

dmstr\web\AdminLteAsset::register($this);
admin\assets\AppAsset::register($this);

$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
$this->title = $this->title = "EzShop";
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=83bf311a-8911-44f2-8c51-582280ff485f" type="text/javascript"></script>
    <?php $this->head() ?>
</head>
<body class="<?= \dmstr\helpers\AdminLteHelper::skinClass() ?>">
<?php $this->beginBody() ?>
<div class="wrapper">

    <?
    if (!Yii::$app->user->isGuest){
        echo $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset]
        );
        echo $this->render(
            'left.php',
            ['directoryAsset' => $directoryAsset]
        );
    }
    ?>

    <?= $this->render(
        'content.php',
        ['content' => $content, 'directoryAsset' => $directoryAsset]
    ) ?>

</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
