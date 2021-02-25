<?
load::action_helper('pager', true);
class page_helper extends pager_helper
{
        public static function get_full_ajax( $list, $current = null, $per_page = null, $show_pages = 5, $q='', $url='/' )
	{
		if ( $list instanceof pager )
		{
			$pager = $list;
		}
		else
		{
			$pager = self::get_pager($list, $current, $per_page);
		}

		$html = '';

		$start = $pager->get_page() - floor($show_pages/2);
		if ( $start < 1 ) $start = 1;

		$end = $start + $show_pages;
		if ( $end > $pager->get_pages() )
		{
			$start  = $start - ($end - $pager->get_pages());
			if ( $start < 1 ) $start = 1;

			$end = $pager->get_pages();
		}

		if ( $pager->get_previous() )
		{
			$html .= '<a '.self::onclick_page(1,$q,$url).'>&larr;</a>';
		}

		for ( $i = $start; $i <= $end; $i++ )
		{
			$html .= '<a ' . ( $i == $pager->get_page() ? 'class="selected"' : '' ) . self::onclick_page($i,$q,$url).'>' . $i . '</a>';
		}

		if ( $pager->get_next() )
		{
			$html .= '<a ' . self::onclick_page($current+($show_pages/2),$q,$url) . '>&rarr;</a>';
		}
		return $html;
	}

        public function onclick_page($page=1,$q='',$url)
	{
                return "onclick=\"Application.changePage('$q',$page,'$url')\" ";
	}
}