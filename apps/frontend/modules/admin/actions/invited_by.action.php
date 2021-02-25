<?php

load::app('modules/admin/controller');

load::model('user/user_data');
load::model('user/invitation');

/**
 * Class admin_invited_by_action
 */
class admin_invited_by_action extends admin_controller
{
    const VIEW = __DIR__.'/../views/invited_by.view.php';

    /**
     * @var UserInvitationRepository
     */
    private $repo;

    /**
     * admin_invited_by_action constructor.
     *
     * @param $module
     * @param $action
     */
    public function __construct($module, $action)
    {
        parent::__construct($module, $action);

        $this->repo = UserInvitationRepository::i();
    }

    public function execute()
    {
        $userId = request::get_int('user_id', session::get_user_id());
        $this->invitations = $this->repo->getInvitationsTreeByUserId($userId);
    }
}