<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
//ini_set("xdebug.var_display_max_depth", -1);
//var_dump($arResult);
?>
<div class="row">
    <div class="col-sm-12 col-xs-12">
        <h3>Настройки аккаунта &laquo;<?= ($arResult['account']['user_NAME'] . $arResult['account']['user_LAST_NAME'] ? : $arResult['account']['user_LOGIN']) ?>&raquo;</h3>
    </div>
    <div class="col-sm-12 col-xs-12 authinfo">
        <h4>Данные авторизации</h4>
        <div class="row spacer">
            <div class="col-sm-12 col-xs-12">
                <strong style="margin-right: 20px;">Логин:</strong><?= $arResult['account']['user_LOGIN'] ?>
            </div>
        </div>
        <div class="row spacer">
            <div class="col-sm-12 col-xs-12">
                <button class="btn btn-primary">Изменить пароль</button>
            </div>
        </div>
    </div>
    <? if (count($arResult['account']['contacts']) || $arResult['account']['is_general']): ?>
    <div class="col-sm-12 col-xs-12 contacts">
        <h4>Контактная информация</h4>
        <div class="row header hidden-xs spacer">
            <div class="col-sm-3"><strong>Имя</strong></div>
            <div class="col-sm-3"><strong>Email</strong></div>
            <div class="col-sm-3"><strong>Телефон</strong></div>
            <div class="col-sm-3 operations"></div>
        </div>
        <? foreach ($arResult['account']['contacts'] as $contact): ?>
            <? if (empty($contact['info'])) continue; ?>
            <div class="row spacer contact" data-contact-id="<?= $contact['guid_id'] ?>">
                <div class="col-sm-3"><strong class="pull-left visible-xs">Имя:</strong><?= ($contact['last_name'] . " " . $contact['name'] . " " . $contact['middle_name']) ?></div>
                <div class="col-sm-3"><strong class="pull-left visible-xs">Email:</strong><?= $contact['info'][\Hogart\Lk\Entity\ContactInfoTable::TYPE_EMAIL]['value'] ?></div>
                <div class="col-sm-3"><strong class="pull-left visible-xs">Телефон:</strong><?= $contact['info'][\Hogart\Lk\Entity\ContactInfoTable::TYPE_PHONE]['value'] ?></div>
                <div class="col-sm-3 operations"></div>
            </div>
        <? endforeach;?>
        <? if ($arResult['account']['is_general']): ?>
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <button class="btn btn-primary">Добавить контактное лицо</button>
                </div>
            </div>
        <? endif; ?>
    </div>
    <? endif; ?>
    <? if (count($arResult['account']['stores']) || $arResult['account']['is_general']): ?>
    <div class="col-sm-12 col-xs-12 stores">
        <h4>Склады для доставки и самовывоза</h4>
        <? foreach ($arResult['account']['stores'] as $store): ?>
            <div class="row spacer store">
                <div class="col-sm-12 col-xs-12" data-store-id="<?= $store['store_XML_ID'] ?>">
                    <i class="fa fa-star<?= ($store['store_ID'] == $arResult['account']['main_store_id'] ? ' color-green' : '-o') ?>"></i>
                    <?= $store['store_ADDRESS'] ?>
                </div>
            </div>
        <? endforeach;?>
        <? if ($arResult['account']['is_general']): ?>
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <button class="btn btn-primary">Добавить склад</button>
                </div>
            </div>
        <? endif; ?>
    </div>
    <? endif; ?>
    <div class="col-sm-12 col-xs-12 staff">
        <h4>Ваши менеджеры</h4>
    </div>
    <div class="col-sm-12 col-xs-12 another-accounts">
        <h4>Прочие аккаунты холдинга</h4>
    </div>
</div>
