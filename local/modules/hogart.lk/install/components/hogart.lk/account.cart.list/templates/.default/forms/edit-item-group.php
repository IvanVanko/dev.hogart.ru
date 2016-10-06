<?
/**
 * @var $this ViewNode
 * @var $cart array
 */
use Hogart\Lk\Entity\ContractTable;
use Hogart\Lk\Helper\Template\ViewNode;
?>
<form role="form" data-toggle="validator">
    <div class="row text-left">
        <!-- Выбор договора и склада -->
        <div class="col-sm-12">
            <div class="row spacer-20">
                <div class="col-sm-6">
                    <select title="Выберете договор" name="contract" class="form-control selectpicker">
                        <? foreach ($this->getComponent()->arResult['contracts'] as $contract): ?>
                            <option <?= ($cart['contract_id'] == $contract['id'] ? "selected" : "") ?> data-content="<?= htmlspecialchars(ContractTable::showName($contract, true)) ?>" value="<?= $contract['id'] ?>"><?= htmlspecialchars(ContractTable::showName($contract, true)) ?></option>
                        <? endforeach; ?>
                    </select>
                </div>
                <div class="col-sm-6">
                    <select title="Выберете склад" name="store" class="form-control selectpicker">
                        <? foreach ($this->getComponent()->arResult['stores'] as $store): ?>
                            <?
                            $store_name = $store['TITLE'];
                            if (!empty(trim($store['ADDRESS']))) {
                                $store_name .= '<span class="footer-text">' . $store['ADDRESS'] . '</span>';
                            }
                            $store_name = htmlspecialchars($store_name);
                            ?>
                            <option <?= ($cart['store_guid'] == $store['XML_ID'] ? "selected" : "") ?> data-content="<?= $store_name ?>" value="<?= $store['XML_ID'] ?>"><?= $store_name ?></option>
                        <? endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 center-between">
                    <label class="control-label">Имя группы</label>
                    <div class="pull-right text-right">
                        <label class="checkbox-inline">
                             Копировать выбранные строки <input data-on-text="Да" data-off-text="Нет" data-switch type="checkbox" name="copy" value="1">
                        </label>
                    </div>
                </div>
                <div class="col-sm-12 form-group">
                    <input data-source="<?= htmlspecialchars(json_encode($this->getComponent()->arResult['item_groups'])) ?>" data-provide="typeahead" class="form-control" type="text" name="new_item_group" value=""/>
                </div>
            </div>
        </div>
    </div>
</form>