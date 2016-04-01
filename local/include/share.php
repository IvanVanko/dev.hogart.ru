<script type="text/javascript" src="//yastatic.net/share/share.js" charset="utf-8"></script>
<div id="ya_share1"></div>
<?/*убираем переносы строки, табы, возврат карретки, обрезаем, обрезаем теги, преобразуем html сущности*/?>
<?global $APPLICATION;?>
<?$TITLE = substr(html_entity_decode(strip_tags(preg_replace("/(\t|\n|\r)/", "", $TITLE))),0,100);?>
<?$DESCRIPTION = substr(html_entity_decode(strip_tags(preg_replace("/(\t|\n|\r)/", "", $DESCRIPTION))),0,200).'...';?>
<script>
    new Ya.share({
        element: 'ya_share1',
        elementStyle: {
            'type': 'none',
            'quickServices': ["vkontakte","facebook","twitter","odnoklassniki","moimir"]
        },
        link: '<?="http://".$_SERVER['SERVER_NAME'].$LINK?>',
        title: '<?=$TITLE;?>',
        description : '<?=$DESCRIPTION;?>',
        image: '<?="http://".$_SERVER['SERVER_NAME'].$IMAGE?>'
    });
</script>
<?$APPLICATION->AddHeadString('<meta property="og:description" content="'.$DESCRIPTION.'"/>')?>
<?$APPLICATION->AddHeadString('<meta property="og:title" content="'.$TITLE.'"/>')?>