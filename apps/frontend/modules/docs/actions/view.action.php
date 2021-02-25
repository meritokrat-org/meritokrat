<?
load::app('modules/docs/controller');
class docs_view_action extends docs_controller
{
	public function execute()
	{
            $alias = request::get_string('alias');
            $this->doc = docs_peer::instance()->get_document(addslashes(strip_tags($alias)));
            if(!$this->doc['id'])
            {
                throw new public_exception('Документ не найден');
            }

            $pages = array();
            $docsize = mb_strlen($this->doc['text'],'UTF-8');
            $num = 0;
            $pos = 0;
            for( $i=0; $i<$docsize; $i+=10000 )
            {
                $oldpos = $pos;
                if(($pos+10004)>=$docsize)
                {
                    $pos = $docsize;
                    break;
                }
                else
                {
                    $pos = mb_strpos($this->doc['text'], '</p>', $pos+10000) + 4;
                }
                $pages[$num] = mb_substr($this->doc['text'], $pos, $pos-$oldpos);
                $num++;
            }

            $this->pager = pager_helper::get_pager($pages, request::get_int('page'), 1);
            $this->page = $this->pager->get_list();

	}
}