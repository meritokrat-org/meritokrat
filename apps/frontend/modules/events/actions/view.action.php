<?

load::app('modules/events/controller');
class events_view_action extends events_controller
{
	public function execute()
	{
                $this->event = events_peer::instance()->get_item(request::get_int('id'));
                if(!$this->event['id'])
                {
                    throw new public_exception( t('Событие не найдено') );
                }
                load::model('invites/invites');
                $invites = invites_peer::instance()->get_by_id(session::get_user_id(), request::get_int('id'),1);
                $this->whos_invited = invites_peer::instance()->get_from_user(session::get_user_id(), 1, request::get_int('id'));
                load::model('events/members');
                $this->is_member = events_members_peer::instance()->is_member($this->event['id'], session::get_user_id());
								
								$this->event["format"] = unserialize($this->event["format"]);
								
                load::model('user/user_desktop');
                if(user_desktop_peer::instance()->is_regional_coordinator(session::get_user_id())
                    OR user_desktop_peer::instance()->is_raion_coordinator(session::get_user_id())
                    )
                    $this->coordinator = 1;

                if($this->event['type']==1)
                {
                    load::model('groups/groups');
                    load::model('groups/members');
                    $this->is_gmember = groups_members_peer::instance()->is_member($this->event['content_id'],session::get_user_id());
                }

                if( $this->event['hidden'] && $this->event['user_id']!=session::get_user_id() && !$invites && !session::has_credential('admin') && !$this->is_gmember )
                {
                    throw new public_exception( t('У вас недостаточно прав') );
                }

                if(request::get_string('type')=='ajax')
                {
                        $event = $this->event;
                        ?>
                        <div class="tab_pane">
                            <a href="javascript:;" class="selected" rel="users1" style="text-transform:uppercase;"><?=t('Посетят')?> (<?=$event['users1count']+$event['users1sum']?>)</a>
                            <a href="javascript:;" rel="users2" style="text-transform:uppercase;"><?=t('Не посетят')?> (<?=$event['users2count']+$event['users2sum']?>)</a>
                            <a href="javascript:;" rel="users3" style="text-transform:uppercase;"><?=t('Возможно посетят')?> (<?=$event['users3count']+$event['users3sum']?>)</a>
                            <div class="clear"></div>
                        </div>
                        <div class="content_pane" id="pane_users1">
                        <?$i=0; foreach(events_members_peer::instance()->get_members(request::get_int('id'), 1) as $user){?>
                        <? include conf::get('project_root').'/apps/frontend/modules/events/views/partials/member_short.php'; ?>
                        <?($i==1)?$i=0:$i++;}?>
                        <div class="clear"></div>
                        </div>
                        <div class="content_pane hidden" id="pane_users2">
                        <?$i=0; foreach(events_members_peer::instance()->get_members(request::get_int('id'), 2) as $user){?>
                        <? include conf::get('project_root').'/apps/frontend/modules/events/views/partials/member_short.php'; ?>
                        <?($i==1)?$i=0:$i++;}?>
                        </div>
                        <div class="content_pane hidden" id="pane_users3">
                        <?$i=0; foreach(events_members_peer::instance()->get_members(request::get_int('id'), 3) as $user){?>
                        <? include conf::get('project_root').'/apps/frontend/modules/events/views/partials/member_short.php'; ?>
                        <?($i==1)?$i=0:$i++;}?>
                        </div>
                        <script type="text/javascript">
                            tabclick();
                        </script>
                        <? die();
                }

                load::model('events/comments');
                $this->comments = events_comments_peer::instance()->get_by_event( request::get_int('id'));

                if (session::has_credential('admin') && request::get_int('print')>0)
                {
                    $this->set_template('print');
                    $this->set_layout('');
                }
	}
}
