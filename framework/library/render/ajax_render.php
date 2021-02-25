<?
//header("content-type: application/json");
load::system('render/abstract_render');

class ajax_render extends abstract_render
{
	public function render()
	{
        return json_encode( $this->controller->json );
	}
}