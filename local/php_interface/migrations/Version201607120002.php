<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 12/07/16
 * Time: 15:26
 */

namespace Sprint\Migration;


use Sprint\Migration\Helpers\IblockHelper;

class Version201607120002 extends Version
{
    protected $description = "Статичный контент для страницы Регистрация/Партнерам";

    public function up()
    {
        $html =<<<HTML
<p>
    Уважаемые партнеры, добрый день! В ближайшее время компания "Хогарт" будет рада представить круглосуточный сервис для оформления заказов и получения информации о ценах и остатках товара.
</p>

<div class="video-block">
    <div class="video-item fbig">
        <img src="/images/reg_video.jpg" alt=""/>
    </div>
</div>
HTML;

        $iBlockHelper = new IblockHelper();
        $iBlockHelper->addElementIfNotExists($iBlockHelper->getIblockId("STATIC_CONTENT"), [
            "CODE" => "register",
            "NAME" => "Партнерам",
            "PREVIEW_TEXT" => $html,
            "PREVIEW_TEXT_TYPE" => "html"
        ]);
    }

}