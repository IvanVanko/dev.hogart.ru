<?
/**
 * @var $this ViewNode
 * @var $cart array
 * @var $item_group string
 */
use Hogart\Lk\Helper\Template\ViewNode;
?>
<form role="form" data-toggle="validator">
    <input type="hidden" name="cart_id" value="<?= $cart['guid_id'] ?>">
    <input type="hidden" name="action" value="create_order">
    <div class="row spacer text-left">
        <div class="col-m-sm-12">
            <label class="">
                <input data-on-text="Да" data-off-text="Нет" data-switch type="radio" checked="checked" name="perm_reserve" value="1">
                <small>
                    Подтверждаю состав заказа,
                    прошу выставить счет и зарезервировать товар для отгрузки
                </small>
            </label>
        </div>
    </div>
    <div class="row spacer text-left">
        <div class="col-m-sm-12">
            <label class="">
                <input data-on-text="Да" data-off-text="Нет" data-switch type="radio" name="perm_reserve" value="0">
                <small>Прошу проверить комплектацию заказа</small>
            </label>
        </div>
    </div>
    <div class="row text-left">
        <div class="col-sm-12">
            <label class="control-label">Комментарий</label>
        </div>
    </div>
    <div class="row spacer text-left">
        <div class="col-m-sm-12">
            <textarea class="form-control" rows="3" name="note"></textarea>
        </div>
    </div>
</form>