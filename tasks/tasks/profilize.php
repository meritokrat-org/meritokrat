<?

class profilize_task extends shell_task
{
	public function execute()
	{
		load::model('blogs/posts');
		load::model('blogs/mentions');

		load::lib('text/names.extractor');

		$posts_res = db::exec('SELECT id FROM ' . blogs_posts_peer::instance()->get_table_name() );

		while ( $row = db::fetch_row($posts_res) )
		{
			$id = $row['id'];
			$post = blogs_posts_peer::instance()->get_item( $id );

			$named_users = array();
			$text = names_extractor::process($post['body'], $named_users);
			blogs_posts_peer::instance()->update( array('id' => $post['id'], 'text_rendered' => $text) );

			if ( $named_users ) echo $id . ' ';

			$mentions = blogs_mentions_peer::instance()->get_by_post($id);
			$mentions = array_unique(array_merge($mentions, $named_users));
			blogs_mentions_peer::instance()->save_mentions($id, $mentions);
		}
	}
}
