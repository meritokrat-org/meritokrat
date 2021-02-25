<?

load::system('form/form');

class party_picture_form extends form
{
	public function set_up()
	{
		$this->add_element('file');

		load::system('form/validators/file_type_validator');
		$file_validator = new file_type_validator();
		$file_validator->set_types(array('jpg', 'gif', 'jpeg', 'png'));

		$this->add_validator('file', $file_validator);
	}
}