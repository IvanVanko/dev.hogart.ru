<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 03/10/2016
 * Time: 04:15
 *
 * @var array $payment
 */

use Hogart\Lk\Entity\OrderPaymentTable;
use Hogart\Lk\Helper\Template\Text;
?>
<div class="row">
    <div class="col-sm-12">
        Форма оплаты: <?= Text::ucfirst(OrderPaymentTable::showFormText($payment['form'])) ?>
    </div>
</div>
