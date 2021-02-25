<?

load::system('render/abstract_render');
load::system('xml/rss');

class rss_render extends abstract_render
{
	public function render()
	{
		$rss_data = $this->controller->rss;

		$rss = new rss();

		$rss->SetChannel(
			$rss_data['channel']['url'],
			$rss_data['channel']['title'],
			$rss_data['channel']['description'],
			$rss_data['channel']['language'],
			$rss_data['channel']['copyright'],
			$rss_data['channel']['author'],
			$rss_data['channel']['subject']
		);

		foreach ( $rss_data['items'] as $item )
		{
			$rss->SetItem(
				$item['url'],
				$item['title'],
				$item['text'],
				$item['date']
			);
		}

        return $rss->Output();
	}
}