<style>
#page{padding:10px}
</style>

<div class="mt10">

    <div style="">
        <h1 class="column_head mb10" style="background: url(/static/images/common/bg-header.jpg) repeat-x 0 -75px;height:21px">
            <?=$doc['title']?>
            <a class="right" href="/docs"><?=t('Все документы')?></a>
        </h1>
    </div>

    <div id="page">
        <?=$page[0]?>
    </div>

    <div style="">
        <div id="pager" class="right pager mr5 mt10">
            <?=pager_helper::get_full($pager)?>
        </div>
    </div>

</div>

<script type="text/javascript">

</script>