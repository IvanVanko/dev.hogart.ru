<?
/**
 * @var $this ViewNode
 * @var $cart array
 * @var $item_group string
 */
use Hogart\Lk\Helper\Template\ViewNode;
?>
<form role="form" data-toggle="validator">
    <input type="hidden" name="contract" value="<?= $cart['contract_id'] ?>">
    <input type="hidden" name="store" value="<?= $cart['store_guid'] ?>">
    <input type="hidden" name="item_group" value="<?= $item_group ?>">
    <div class="row text-left">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-12">
                    <div class="row spacer">
                        <div class="col-sm-12 form-group">
                            <label class="control-label">
                                Выберите файл
                                (
                                <span class="label label-xs label-info">*.tsv</span>
                                <span class="label label-xs label-info">*.csv,</span>
                                <span class="label label-xs label-info">*.xls,</span>
                                <span class="label label-xs label-info">*.xlsx</span>
                                )</label>
                            <input id="input-1a" multiple name="sku[]" type="file" class="file"
                                   data-language="ru"
                                   data-upload-url="?cart_id=<?= $cart['guid_id'] ?>&action=upload_sku&<?= BX_AJAX_PARAM_ID ?>=<?= $this->getId() ?>"
                                   data-upload-async="false"
                                   data-max-file-count="3"
                                   data-allowed-file-extensions='["tsv", "csv", "xls", "xlsx"]'
                                   data-msg-invalid-file-extension="Для загрузки доступны только файлы {extensions}"
                                   data-show-preview="false"
                                   data-show-remove="false"
                                   data-show-upload="false"
                                   data-el-error-container="#input-error"
                            >
                            <div id="input-error"></div>
                        </div>
                    </div>
                    <div class="row spacer">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="sku-column">Столбец с артикулом</label>
                                <input type="text" name="sku_column" class="form-control" id="sku-column" placeholder="Введите столбец" value="A">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="count-column">Столбец с кол-вом</label>
                                <input type="text" name="count_column" class="form-control" id="count-column" placeholder="Введите столбец" value="B">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>