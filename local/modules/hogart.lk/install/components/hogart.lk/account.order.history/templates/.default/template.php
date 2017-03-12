<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 03/10/2016
 * Time: 01:43
 *
 * @var array $arParams
 * @var array $arResult
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @global CDatabase $DB
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $templateFile
 * @var string $templateFolder
 * @var string $componentPath
 * @var CBitrixComponent $component
 */

use Hogart\Lk\Entity\OrderTable;
use Hogart\Lk\Entity\ContractTable;
use Hogart\Lk\Helper\Template\OrderEventNote;

$order = $arResult['order'];
?>

<div class="row history">
    <div class="col-sm-12">
        <div class="row spacer-20 order-line">
            <div class="col-sm-12">
                <div id="svg">
                    <svg id="relations"></svg>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <h4>
                            <a href="/account/order/<?= $order['id'] ?>">
                                <span class="title">
                                    <?= OrderTable::showName($order) ?>
                                </span>
                            </a>
                            <sup><span class="label label-primary"><?= OrderTable::getTypeText($order['type']) ?></span></sup>
                            <sup><span class="label label-warning"><?= OrderTable::getStateText($order['state']) ?></span></sup>
                        </h4>
                        <div><?= $order['co_name'] ?></div>
                        <div><?= ContractTable::showName($order, false, 'c_') ?></div>
                        <div>Отгрузка со склада: <u><?= $order['s_TITLE'] ?></u> </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <ul class="timeline">

                            <?
                            /** @var OrderEventNote $note */
                            foreach ($arResult['history'] as $k => $note):
                            ?>
                                <li data-relation-history-color="#95c600" data-relation-history="<?= $note->getRelationGuid() ?>" id="<?= $note->getGuid() ?>" class="timeline-inverted">
                                    <? if(($icon = $note->getBadgeIcon())): ?>
                                    <div class="timeline-badge <?= $note->getBadgeClass() ?>">
                                        <?= $icon ?>
                                    </div>
                                    <? endif; ?>
                                    <div class="timeline-panel">
                                        <div class="timeline-heading">
                                            <? if (!empty($note->getLink())): ?><a href="<?= $note->getLink() ?>"><? endif; ?>
                                                <h4 class="timeline-title"><?= $note->getTitle() ?></h4>
                                            <? if (!empty($note->getLink())): ?></a><? endif; ?>
                                            <? if(!empty($note->getDate())): ?>
                                                <p><small class="text-muted"><i class="glyphicon glyphicon-time"></i> <?= $note->getDate()->format(HOGART_DATE_TIME_FORMAT)?></small></p>
                                            <? endif; ?>
                                        </div>
                                        <div class="timeline-body">
                                            <?= $note->getBody(__DIR__ . "/entities") ?>
                                        </div>
                                    </div>
                                </li>
                            <? endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
