<style>
    .side_href {
        bottom: -46px;
        right: 0;
        position: fixed;
        width: 330px;
    }

    .side_href {
        box-sizing: border-box;
        background: #515151;
        overflow: hidden;
        padding-top: 36px;
        padding-bottom: 60px;
    }

    .side_href a {
        display: block;
        margin-left: 20px;
        float: left;
        color: #fff;
        text-decoration: none;
        font-size: 12px;
        line-height: 16px;
        padding-bottom: 20px;
        background-position: left 2px;
    }
</style>
<div class="side_href">
    <a href="#" class="icon-email js-popup-open" data-popup="#popup-subscribe">Отправить на e-mail</a>
    <a href="#" onclick="window.print(); return false;" class="icon-print">Распечатать</a>
    <a href="#" class="icon-phone js-popup-open" data-popup="#popup-subscribe-phone">Отправить SMS</a>
</div>