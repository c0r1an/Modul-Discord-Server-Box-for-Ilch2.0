<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Discordserverbox\Controllers\Admin;

use Ilch\Validation;

class Settings extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->addMenu('menuDiscordServerBox', [
            [
                'name' => 'settings',
                'active' => true,
                'icon' => 'fa-solid fa-gears',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index']),
            ],
        ]);
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('moduleName'), ['controller' => 'settings', 'action' => 'index'])
            ->add($this->getTranslator()->trans('settings'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $post = [
                'invite_url' => trim((string)$this->getRequest()->getPost('invite_url')),
                'announcement_url' => trim((string)$this->getRequest()->getPost('announcement_url')),
                'cache_ttl' => (string)$this->getRequest()->getPost('cache_ttl'),
                'member_preview_limit' => (string)$this->getRequest()->getPost('member_preview_limit'),
                'show_server_icon' => (string)$this->getRequest()->getPost('show_server_icon'),
                'show_description' => (string)$this->getRequest()->getPost('show_description'),
                'show_join_button' => (string)$this->getRequest()->getPost('show_join_button'),
                'show_online_count' => (string)$this->getRequest()->getPost('show_online_count'),
                'show_member_count' => (string)$this->getRequest()->getPost('show_member_count'),
                'show_member_preview' => (string)$this->getRequest()->getPost('show_member_preview'),
                'show_announcement' => (string)$this->getRequest()->getPost('show_announcement'),
            ];

            $validation = Validation::create($post, [
                'invite_url' => 'url',
                'announcement_url' => 'url',
                'cache_ttl' => 'required|numeric|integer|min:60|max:86400',
                'member_preview_limit' => 'required|numeric|integer|min:1|max:12',
                'show_server_icon' => 'required|numeric|integer|min:0|max:1',
                'show_description' => 'required|numeric|integer|min:0|max:1',
                'show_join_button' => 'required|numeric|integer|min:0|max:1',
                'show_online_count' => 'required|numeric|integer|min:0|max:1',
                'show_member_count' => 'required|numeric|integer|min:0|max:1',
                'show_member_preview' => 'required|numeric|integer|min:0|max:1',
                'show_announcement' => 'required|numeric|integer|min:0|max:1',
            ]);

            $serverId = trim((string)$this->getRequest()->getPost('server_id'));
            if ($serverId !== '' && !ctype_digit($serverId)) {
                $validation->getErrorBag()->addError('server_id', $this->getTranslator()->trans('serverIdDigits'));
            }

            if ($validation->isValid()) {
                $this->getConfig()
                    ->set('discordserverbox_title', trim((string)$this->getRequest()->getPost('title')))
                    ->set('discordserverbox_subtitle', trim((string)$this->getRequest()->getPost('subtitle')))
                    ->set('discordserverbox_serverId', $serverId)
                    ->set('discordserverbox_inviteUrl', $post['invite_url'])
                    ->set('discordserverbox_cacheTtl', (string)max(60, (int)$post['cache_ttl']))
                    ->set('discordserverbox_showServerIcon', $post['show_server_icon'])
                    ->set('discordserverbox_showDescription', $post['show_description'])
                    ->set('discordserverbox_showJoinButton', $post['show_join_button'])
                    ->set('discordserverbox_showOnlineCount', $post['show_online_count'])
                    ->set('discordserverbox_showMemberCount', $post['show_member_count'])
                    ->set('discordserverbox_showMemberPreview', $post['show_member_preview'])
                    ->set('discordserverbox_memberPreviewLimit', (string)max(1, min(12, (int)$post['member_preview_limit'])))
                    ->set('discordserverbox_showAnnouncement', $post['show_announcement'])
                    ->set('discordserverbox_announcementTitle', trim((string)$this->getRequest()->getPost('announcement_title')))
                    ->set('discordserverbox_announcementText', trim((string)$this->getRequest()->getPost('announcement_text')))
                    ->set('discordserverbox_announcementUrl', $post['announcement_url'])
                    ->set('discordserverbox_announcementDate', trim((string)$this->getRequest()->getPost('announcement_date')));

                $this->redirect()->withMessage('saveSuccess')->to(['action' => 'index']);
                return;
            }

            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'index']);
            return;
        }

        $this->getView()->setArray([
            'title' => (string)$this->getConfig()->get('discordserverbox_title'),
            'subtitle' => (string)$this->getConfig()->get('discordserverbox_subtitle'),
            'serverId' => (string)$this->getConfig()->get('discordserverbox_serverId'),
            'inviteUrl' => (string)$this->getConfig()->get('discordserverbox_inviteUrl'),
            'cacheTtl' => (string)$this->getConfig()->get('discordserverbox_cacheTtl'),
            'showServerIcon' => (string)$this->getConfig()->get('discordserverbox_showServerIcon'),
            'showDescription' => (string)$this->getConfig()->get('discordserverbox_showDescription'),
            'showJoinButton' => (string)$this->getConfig()->get('discordserverbox_showJoinButton'),
            'showOnlineCount' => (string)$this->getConfig()->get('discordserverbox_showOnlineCount'),
            'showMemberCount' => (string)$this->getConfig()->get('discordserverbox_showMemberCount'),
            'showMemberPreview' => (string)$this->getConfig()->get('discordserverbox_showMemberPreview'),
            'memberPreviewLimit' => (string)$this->getConfig()->get('discordserverbox_memberPreviewLimit'),
            'showAnnouncement' => (string)$this->getConfig()->get('discordserverbox_showAnnouncement'),
            'announcementTitle' => (string)$this->getConfig()->get('discordserverbox_announcementTitle'),
            'announcementText' => (string)$this->getConfig()->get('discordserverbox_announcementText'),
            'announcementUrl' => (string)$this->getConfig()->get('discordserverbox_announcementUrl'),
            'announcementDate' => (string)$this->getConfig()->get('discordserverbox_announcementDate'),
        ]);
    }
}
