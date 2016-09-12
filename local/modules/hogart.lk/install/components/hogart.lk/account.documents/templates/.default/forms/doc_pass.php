<?php
use Hogart\Lk\Entity\CompanyTable;
?>
<div class="row">
    <div class="col-sm-12">
        <label class="control-label">Вид документа удостоверяющего личность</label>
    </div>
</div>
<div class="row spacer">
    <div class="col-sm-6">
        <select name="doc_pass" class="form-control selectpicker">
            <option selected value="<?= CompanyTable::DOC_EMPTY?>">Без документа</option>
            <option value="<?= CompanyTable::DOC_PASSPORT?>">Пасспорт РФ</option>
            <option value="<?= CompanyTable::DOC_NO_PASSPORT?>">Другой документ</option>
        </select>
    </div>
    <div class="col-sm-3" data-doc-type="<?= CompanyTable::DOC_PASSPORT?>">
        <input data-mask="9999" name="doc_serial" type="text" class="form-control" placeholder="Серия">
    </div>
    <div class="col-sm-3" data-doc-type="<?= CompanyTable::DOC_PASSPORT?>">
        <input data-mask="999999" name="doc_number" type="text" class="form-control" placeholder="Номер">
    </div>
</div>
<div class="row spacer" data-doc-type="<?= CompanyTable::DOC_PASSPORT?>">
    <div class="col-sm-8">
        <input name="doc_ufms" type="text" class="form-control" placeholder="Кем выдан">
    </div>
    <div class="col-sm-4">
        <input name="doc_date" type="text" class="form-control" data-mask="99/99/9999" placeholder="ДД/ММ/ГГГГ">
    </div>
</div>