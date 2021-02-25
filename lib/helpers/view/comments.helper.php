<?

class comments_helper
{
	const TYPE_PARTY_NEWS = 1;
	const TYPE_GROUP_NEWS = 1;

	public static function render( $oid, $otype )
	{
		context::set('client_handler', 'Comments.init()');

		load::model('comments');
		$comments = comments_peer::instance()->get_comments($otype, $oid);

		ob_start();
		include dirname(__FILE__) . '/../../../apps/frontend/modules/comments/views/comments.view.php';
		return ob_get_clean();
	}
}