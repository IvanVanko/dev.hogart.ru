<div class="sort">
    <span>Сортировать по:</span>
    <?

    $return = array();

    $sort_next_matcher = array(
        '' => 'desc',
        'desc' => 'asc',
        'asc' => 'desc'
    );

    $order = !empty($_REQUEST['sort_order']) ? $_REQUEST['sort_order'] : $order = "desc";
    $sort_by = !empty($_REQUEST['sort_by']) ? $_REQUEST['sort_by'] : $sort_by = "NEW";

    $next_order = $sort_next_matcher[strval($order)];

    $return['ORDER'] = $order;

    foreach ($sortFields as $key => $sField) {
        if ($sField['CODE'] == $sort_by) {
            $active = 'active';
            $return['CODE'] = $sField['CODE'];
            $link = $GLOBALS['APPLICATION']->GetCurPageParam("sort_by=".$sField['CODE']."&sort_order=".$next_order, array("sort_by","sort_order"));
        } else {
            $active = '';
            $link = $GLOBALS['APPLICATION']->GetCurPageParam("sort_by=".$sField['CODE']."&sort_order=desc", array("sort_by","sort_order"));
        }?>
        <a href="<?=$link?>" class="<?=$key?> <?if (strlen($active)) echo $active." ".$order?>"><span><?=$sField['NAME']?></span></a>
    <?}?>
</div>

<?return $return?>