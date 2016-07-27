<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 27/07/16
 * Time: 18:47
 */
if (is_file($_SERVER["DOCUMENT_ROOT"] . "/local/modules/hogart.lk/admin/hogart_lk.php")) {
    /** @noinspection PhpIncludeInspection */
    require($_SERVER["DOCUMENT_ROOT"] . "/local/modules/hogart.lk/admin/hogart_lk.php");
} else {
    /** @noinspection PhpIncludeInspection */
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/hogart.lk/admin/hogart_lk.php");
}