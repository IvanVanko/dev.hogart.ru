<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Личный кабинет");
?>
<?/*$APPLICATION->IncludeComponent("bitrix:main.profile","",Array(
        "USER_PROPERTY_NAME" => "",
        "SET_TITLE" => "Y", 
        "AJAX_MODE" => "N", 
        "USER_PROPERTY" => Array(), 
        "SEND_INFO" => "Y", 
        "CHECK_RIGHTS" => "Y",  
        "AJAX_OPTION_JUMP" => "N", 
        "AJAX_OPTION_STYLE" => "Y", 
        "AJAX_OPTION_HISTORY" => "N" 
    )
);*/?> 
<div class="inner">
    <div class="lk-mini-menu-wrap">
        <ul class="lk-mini-menu">
            <li><a href="#">Настройки</a></li>
            <li><a href="#">Выход</a></li>
        </ul>
    </div>

    <ul class="lk-main-menu">
        <li>
            <div class="inner">
                <h1 class="icon-lk-cart">Заказы</h1>
                <div class="popup-btn">
                    <a href="#">СФОРМИРОВАТЬ НОВЫЙ ЗАКАЗ</a>
                </div>
            </div>
        </li>
        <li>
            <div class="inner">
                <h1 class="icon-lk-doc">Документы</h1>
                <div class="popup-btn">
                    <a href="#">Создать новый документ</a>
                </div>
            </div>
        </li>
        <li>
            <div class="inner">
                <h1 class="icon-lk-ost">ОстаткИ</h1>
                <div class="popup-btn">
                    <a href="#">Просмотреть остатки</a>
                </div>
            </div>
        </li>
        <li>
            <div class="inner">
                <h1 class="icon-lk-msg">СООБЩЕНИЯ</h1>
                <div class="popup-btn">
                    <a href="#">Открыть сообщения</a>
                </div>
            </div>
        </li>
    </ul>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>