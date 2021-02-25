<?=party_helper::photo($this->data['id'], 's', true, array('class' => 'border1 mr10', 'align' => 'left'), true)?>
<?//=tag_helper::image('/menu/' . $this->icons[$this->type] . '.png', array('class' => 'vcenter'))?>
<span class="quiet ml10"><?=$this->types[$this->type]?></span><br />
<a href="/party<?=$this->data['id']?>"><?=stripslashes(htmlspecialchars($this->data['title']))?></a>