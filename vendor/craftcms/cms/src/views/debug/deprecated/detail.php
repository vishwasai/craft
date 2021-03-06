<?php

use craft\helpers\UrlHelper;
use craft\models\DeprecationError;

/** @var craft\debug\DeprecatedPanel $panel */
?>
<h1>Deprecation Warnings</h1>
<?php

array_walk($panel->data, function(&$log) {
    $log = new DeprecationError($log);
});

/** @var DeprecationError[] $logs */
$logs = $panel->data;

?>

<?php if (empty($logs)): ?>
    <p>No deprecation errors were logged on this request.</p>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-condensed table-bordered table-striped table-hover"
               style="table-layout: fixed;">
            <thead>
            <tr>
                <th style="nowrap"><?= Craft::t('app', 'Message') ?></th>
                <th><?= Craft::t('app', 'Origin') ?></th>
                <th><?= Craft::t('app', 'Stack Trace') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?= \yii\helpers\Markdown::processParagraph(\craft\helpers\Html::encode($log->message)) ?></td>
                    <td><code><?= str_replace('/', '/<wbr>', \craft\helpers\Html::encode($log->file)) . ($log->line ? ':' . $log->line : '') ?></code>
                    </td>
                    <td><?php if ($log->id): ?><a
                            href="<?= $panel->getUrl() . '&trace=' . $log->id ?>"><?= Craft::t('app', 'Stack Trace') ?></a><?php else: ?><?= Craft::t('app', 'See logs') ?><?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<p><a href="<?= UrlHelper::cpUrl('utilities/deprecation-errors') ?>"
      target="_parent">View all deprecation errors</a></p>
