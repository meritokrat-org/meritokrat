<?php
/**
 * @var admin_invited_by_action $controller
 * @var array                   $invitations
 */

use App\Component\Person\Card;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Cards
{
    private $collection;

    public function __construct($collection)
    {
        $this->collection = new ArrayCollection($collection);
    }

    public static function create($ids)
    {
        return new self(
            array_map(
                static function ($id) {
                    return array_merge(
                        user_auth_peer::instance()->get_item($id),
                        user_data_peer::instance()->get_item($id)
                    );
                },
                $ids
            )
        );
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function activatedUsers()
    {
        return $this->collection->filter(
            function ($e) {
                return true === (bool) $e['active'];
            }
        );
    }

    public function notActivatedUsers()
    {
        return $this->collection->filter(
            function ($e) {
                return true !== (bool) $e['active'];
            }
        );
    }
}

?>

<div class="accordion" id="invitedBy">
    <?php foreach ($invitations as $level => $context) { ?>
        <?php $cards = Cards::create($context['ids']); ?>
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading-<?= $level ?>">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?= $level ?>"
                        aria-expanded="true" aria-controls="collapse-<?= $level ?>">
                    Рівень <?= $level + 1 ?>
                </button>
            </h2>
            <div id="collapse-<?= $level ?>" class="accordion-collapse collapse"
                 aria-labelledby="heading-<?= $level ?>"
                 data-bs-parent="#invitedBy">
                <div class="accordion-body">
                    <table class="table table-sm table-striped table-hover">
                        <tbody>
                        <?php $i = 0; foreach ($cards->notActivatedUsers()->toArray() as $p) { ?>
                            <tr>
                                <td class="align-middle"><?= (++$i) ?></td>
                                <td class="align-middle">
                                    <div>
                                        <a href="/profile-<?= $p['id'] ?>" class="link" target="_blank"><?= sprintf(
                                                '%s %s',
                                                $p['first_name'],
                                                $p['last_name']
                                            ) ?></a>
                                    </div>
                                    <div class="text-muted"><?= $p['email'] ?></div>
                                </td>
                                <td class="text-end align-middle">
                                    <!--<div class="btn-group btn-group-sm" role="group" aria-label="First group">-->
                                        <button type="button" class="btn btn-sm btn-secondary"
                                                onclick="sendInvite(<?= $p['id'] ?>)">
                                            <svg width="32" height="32" viewBox="0 0 20 20" class="bi bi-envelope-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555zM0 4.697v7.104l5.803-3.558L0 4.697zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757zm3.436-.586L16 11.801V4.697l-5.803 3.546z"/>
                                            </svg>
                                            <span class="badge bg-light text-dark"><?= (db_key::i()->exists(
                                                    $p['id'].'_invited_again'
                                                )) ? db_key::i()->get($p['id'].'_invited_again') : '1' ?></span>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary">
                                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash"
                                                 fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                                <path fill-rule="evenodd"
                                                      d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                            </svg>
                                        </button>
                                    <!--</div>-->
                                </td>
                            </tr>
                        <?php } ?>
                        <?php foreach ($cards->activatedUsers()->toArray() as $p) { ?>
                            <tr class="table-success">
                                <th class="align-middle" scope="row"><?= (++$i) ?></th>
                                <td class="align-middle">
                                    <a href="/profile-<?= $p['id'] ?>" class="link" target="_blank"><?= sprintf(
                                            '%s %s',
                                            $p['first_name'],
                                            $p['last_name']
                                        ) ?></a>
                                </td>
                                <td class="text-end align-middle">
                                    <div class="btn-group btn-group-sm" role="group" aria-label="First group">
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                                onclick="sendInvite(<?= $p['id'] ?>)">
                                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-envelope"
                                                 fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                      d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383l-4.758 2.855L15 11.114v-5.73zm-.034 6.878L9.271 8.82 8 9.583 6.728 8.82l-5.694 3.44A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.739zM1 11.114l4.758-2.876L1 5.383v5.73z"/>
                                            </svg>
                                            <span id="result<?= $p['id'] ?>"><?= (db_key::i()->exists(
                                                    $p['id'].'_invited_again'
                                                )) ? db_key::i()->get($p['id'].'_invited_again') : '1' ?></span>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary">
                                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash"
                                                 fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                                <path fill-rule="evenodd"
                                                      d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<script>
    const sendInvite = function (id) {
        $.ajax({
            type: 'post',
            url: '/profile/user_inv_ajax',
            data: {
                id: id,
                act: 'resend'
            },
            success: function (resp) {
                data = eval("(" + resp + ")");
                context.obj_id = data.message;
                if (data.success !== 'ok') {
                    context.obj_id = data.message;
                    Popup.show();
                    Popup.setHtml(data.html);
                    Popup.position();
                } else {
                    $('#result' + id).html(data.c);
                    // var date = new Date(parseInt(data.last_invite) * 1000);
                    // $('#lastinv' + id).html('<b>' + addZero(date.getDate()) + '.' + addZero(date.getUTCMonth()) + '.' + date.getFullYear() + ' ' + addZero(date.getHours()) + ':' + addZero(date.getMinutes()) + '</b>');
                }
            }
        });
    }

    function addZero(i) {
        return i < 10 ? '0' + i : '' + i;
    }

    document.addEventListener('DOMContentLoaded', () => {
        console.log(document
            .querySelector('div#invitedBy.accordion > div.accordion-item:first-child button'))
        document
            .querySelector('div#invitedBy.accordion > div.accordion-item:first-child button')
            .dispatchEvent(new Event('click'));
    })
</script>
