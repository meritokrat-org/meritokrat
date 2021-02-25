<div>

    <h1 class="column_head"><a href="/shop"><?=t('М-магазин')?></a></h1>

    <div>
        <? include "partials/".request::get_string("view").".php"; ?>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#cart_list").html($.cookie("cart"));
    });
</script>