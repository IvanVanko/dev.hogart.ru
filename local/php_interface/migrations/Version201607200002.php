<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 12/07/16
 * Time: 15:26
 */

namespace Sprint\Migration;


use Sprint\Migration\Helpers\IblockHelper;

class Version201607200002 extends Version
{
    protected $description = "Статичный контент для страницы Бренды";

    public function up()
    {
        $html =<<<HTML
Компания <strong>«Хогарт»</strong> представляет <strong>широкий ассортимент</strong>
    продукции, для комплектации отопительных, вентиляционных сетей, инженерной и белой сантехники как от&nbsp;мировых
    производителей, так и&nbsp;небольших фабрик.
HTML;

        $iBlockHelper = new IblockHelper();
        $iBlockHelper->addElementIfNotExists($iBlockHelper->getIblockId("STATIC_CONTENT"), [
            "CODE" => "brandheadertext",
            "NAME" => "Бренды заголовок",
            "PREVIEW_TEXT" => $html,
            "PREVIEW_TEXT_TYPE" => "html"
        ]);
    }

}