<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 25/10/2016
 * Time: 02:07
 *
 * @global $APPLICATION
 *
 * @var CBitrixComponent $component
 * @var array $arParams
 */
$component->arParams['url'] = '#manager-feedback';
$component->arParams['dialog'] = 'manager-feedback';
use Hogart\Lk\Helper\Template\Dialog;
?>

<? if (!empty($arParams['manager']['email'])): ?>

    <? Dialog::Start("manager-feedback", [
        'dialog-options' => 'closeOnConfirm: false',
        'title' => vsprintf('Сообщение для %s (%s)', [$arParams['manager']['name'], $arParams['manager']['email']])
    ]) ?>
    <form class="text-left" action="<?= $APPLICATION->GetCurPage(false) ?>" method="post">
        <label class="control-label">Тема сообщения</label>
        <div class="row">
            <div class="col-sm-12 form-group">
                <input required name="subject" type="text" class="form-control" placeholder="Тема" data-error="Поле должно быть заполнено">
                <div class="help-block with-errors"></div>
            </div>
        </div>
        <label class="control-label">Сообщение</label>
        <div class="row">
            <div class="col-sm-12 form-group">
                <textarea required class="form-control" name="message" rows="3" data-error="Поле должно быть заполнено"></textarea>
                <div class="help-block with-errors"></div>
            </div>
        </div>
        <input type="hidden" name="manager" value="<?= $arParams['manager']['id'] ?>">
        <?=bitrix_sessid_post()?>
    </form>
    <?
    $id = Dialog::$id;
    $handler =<<<JS
        (function() {
            $('[data-remodal-id="$id"] form').validator();
        })
JS;
    Dialog::Event('opening', $handler);
    $handler =<<<JS
        (function() {
            $('[data-remodal-id="$id"] form').submit();
        })
JS;
    Dialog::Event('confirmation', $handler);
    Dialog::End()
    ?>

<? endif; ?>