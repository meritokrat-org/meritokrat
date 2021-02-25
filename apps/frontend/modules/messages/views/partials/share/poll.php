<?=user_helper::photo($this->data['user_id'], 's', array('class' => 'border1 mr10', 'align' => 'left'), true)?>
<?//=tag_helper::image('/menu/' . $this->icons[$this->type] . '.png', array('class' => 'vcenter'))?>
<span class="quiet ml10"><?=$this->types[$this->type]?></span><br />
<a href="/poll<?=$this->data['id']?>"><?=stripslashes(htmlspecialchars($this->data['question']))?></a>