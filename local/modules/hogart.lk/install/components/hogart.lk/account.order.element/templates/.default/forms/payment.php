<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 10/10/2016
 * Time: 14:25
 *
 * @var array $order
 */
use Hogart\Lk\Entity\OrderPaymentTable;

?>

<div class="row text-center">
    <div class="col-sm-12">
        <div class="row spacer-20"></div>
        <div class="row spacer">
            <div class="col-sm-12 form-group">
                <label class="control-label text-nowrap" style="padding-right: 10px;">Сумма к оплате</label>
                <input class="form-control" style="display: inline-block; width: auto; border: none"
                       max="<?= $order['totals']['release'] ?>"
                       name="release_sum"
                       type="number"
                       step="0.01"
                       value="<?= number_format($order['totals']['release'], 2, '.', '') ?>"
                       data-error="Сумма не должна превышать <?= number_format($order['totals']['release'], 2, '.', '') ?>"
                >
                <div class="help-block with-errors"></div>
            </div>
        </div>
        <div class="row text-center">
            <div class="col-sm-12">
                <button type="submit" name="payment_type" value="<?= OrderPaymentTable::PAYMENT_FORM_BANK ?>" class="btn btn-default btn-lg">
                    <i class="fa fa-file-text-o"></i>
                    Получить счет
                </button>
            </div>
        </div>
    </div>
</div>
