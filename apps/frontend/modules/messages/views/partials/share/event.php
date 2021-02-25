<?=image_helper::photo($this->data['photo'], 's', 'events', array('class' => 'mr10', 'align' => 'left'))?>
<span class="quiet ml10"><?=$this->types[$this->type]?></span><br />
<a href="/event<?=$this->data['id']?>"><?=stripslashes(htmlspecialchars($this->data['name']))?></a>