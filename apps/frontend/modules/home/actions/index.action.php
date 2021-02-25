<?

class home_index_action extends frontend_controller
{
	#protected $authorized_access = true;
	public function execute()
	{

		cookie::set('auth', "{$user['email']}|{$user['password']}", time() + 60 * 60 * 24 * 31, '/', '.meritokratia.loc');

		load::model('blogs/posts');
		load::model('blogs/tags');
		load::model('blogs/posts_tags');
		load::model('blogs/comments');
		load::model('groups/groups');
		load::model('groups/news');
		load::model('reform/reform');
		load::model('team/team');

		$this->load_news();

		$list = blogs_posts_peer::instance()->get_rated();

		$pop_posts = array();
		foreach ($list as $item) {
			$post = blogs_posts_peer::instance()->get_item($item);
			if ($post["for"] > 20 && $post["visible"] == true)
				$pop_posts[] = $post;
		}

		//uasort($pop_posts, "home_index_action::my_sort");
		$this->pop_posts = array_slice($pop_posts, 0, 5);

		$this->rated_posts = array_slice($list, 0, 10);

		foreach (reform_peer::instance()->get_list(["category" => "3"], [], [], 3) as $project)
			$this->projects[] = reform_peer::instance()->get_item($project);

//		foreach (team_peer::instance()->get_list(["id" => 3, "id" => 5, "id" => 6]) as $member)
//			$this->team[] = team_peer::instance()->get_item($member);

		$this->team[] = team_peer::instance()->get_item(3);
		$this->team[] = team_peer::instance()->get_item(5);
		$this->team[] = team_peer::instance()->get_item(6);

		$list = blogs_posts_peer::instance()->get_newest(blogs_posts_peer::TYPE_BLOG_POST);
		$this->blogs_posts = array_slice($list, 0, 5);

		load::model('events/events');

		$this->new_events = events_peer::instance()->get_newest(3);

		load::model('polls/polls');
		load::model('polls/answers');
		load::model('polls/votes');

		$polls = polls_peer::instance()->get_newest();
		$this->new_polls = array_slice($polls, 0, 5);

		$this->set_slot('context', 'partials/context.polls');

		$groups = groups_peer::instance()->get_list(['show_in_main_page' => 1]);
		$this->groups = array_slice($groups, 0, 10);


		load::model('attentions');
		$this->attention = attentions_peer::instance()->get_attention();

		load::model('photo/photo_competition');
		$this->photo_competition = photo_competition_peer::instance()->get_list();
		$this->photo_competition = shuffle($this->photo_competition);

		/*
						load::model('ideas/ideas');
						$ideas = ideas_peer::instance()->get_new();
				$this->ideas = array_slice($ideas, 0, 5);

						load::model('user/user_auth');

						$new_people = user_auth_peer::instance()->get_new_people();
						shuffle($new_people);
				$this->new_people = array_slice($new_people, 0, 5);

						$famous_people = user_auth_peer::instance()->get_famous_people();
						shuffle($famous_people);
				$this->famous_people = array_slice($famous_people, 0, 5);
		*/
		load::model('photo/photo_competition');
		$photos_ids = photo_competition_peer::instance()->get_list();

		$this->photos_ids = array();
		while (count($this->photos_ids) < 4) {
			$photo_id = $photos_ids[rand(0, count($photos_ids) - 1)];
			if (!in_array($photo_id, $this->photos_ids)) {
				$this->photos_ids[] = $photo_id;
			}
		}

		load::model('ppo/ppo');
		load::model('eventreport/eventreport');
		$this->reports = eventreport_peer::instance()->get_list(array('status' => 3), array(), array('id desc'));
		$this->reports = array_slice($this->reports, 0, 3);
	}

	protected function load_news()
	{

		$list = blogs_posts_peer::instance()->get_list(array('type' => blogs_posts_peer::TYPE_MIND_POST, 'visible' => true));
		$this->left_blogposts = array_slice($list, 0, 10);

		$list = blogs_posts_peer::instance()->get_news_posts();
		$this->news = array_slice($list, 0, 3);

		$list = groups_news_peer::instance()->get_new();
		$this->group_news = array_slice($list, 0, 3);

		$list = blogs_posts_peer::instance()->get_declarations_posts();
		$this->declarations = array_slice($list, 0, 3);

	}

}
