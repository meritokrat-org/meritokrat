<div style="width: 760px;" class="left">

<h1 class="column_head mt10 mr10"> Надавати фінансову допомогу регулярно</h1>
    <div class="mt10 mr10 ml15 acenter">
        <? if (!request::get('email',null)) { ?>
        <form action="" method="get"><br>
                Хочу і можу надавати допомогу мерітократичному рухові на регулярній основі.<br><br>
                Мій e-mail: <input type="text" class="text" name="email">                 <input type="submit" class="button"  name="submit" value="Підтвердити">
        </form>
        <? } else { ?><br>
        Дякуємо за Ваше бажання надавати регулярну допомогу.<br><br> Ми будемо надсилати Вам автоматичне нагадування 5-го числа кожного місяця.
        <? } ?>
        
    </div>
</div>