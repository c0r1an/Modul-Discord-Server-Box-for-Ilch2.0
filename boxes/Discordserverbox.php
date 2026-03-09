<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Discordserverbox\Boxes;

use Modules\Discordserverbox\Libraries\ServerWidget;

class Discordserverbox extends \Ilch\Box
{
    public function render()
    {
        $settings = [
            'title' => trim((string)$this->getConfig()->get('discordserverbox_title')),
            'subtitle' => trim((string)$this->getConfig()->get('discordserverbox_subtitle')),
            'server_id' => trim((string)$this->getConfig()->get('discordserverbox_serverId')),
            'invite_url' => trim((string)$this->getConfig()->get('discordserverbox_inviteUrl')),
            'cache_ttl' => max(60, (int)$this->getConfig()->get('discordserverbox_cacheTtl')),
            'show_server_icon' => (bool)$this->getConfig()->get('discordserverbox_showServerIcon'),
            'show_description' => (bool)$this->getConfig()->get('discordserverbox_showDescription'),
            'show_join_button' => (bool)$this->getConfig()->get('discordserverbox_showJoinButton'),
            'show_online_count' => (bool)$this->getConfig()->get('discordserverbox_showOnlineCount'),
            'show_member_count' => (bool)$this->getConfig()->get('discordserverbox_showMemberCount'),
            'show_member_preview' => (bool)$this->getConfig()->get('discordserverbox_showMemberPreview'),
            'member_preview_limit' => max(1, min(12, (int)$this->getConfig()->get('discordserverbox_memberPreviewLimit'))),
            'show_announcement' => (bool)$this->getConfig()->get('discordserverbox_showAnnouncement'),
            'announcement_title' => trim((string)$this->getConfig()->get('discordserverbox_announcementTitle')),
            'announcement_text' => trim((string)$this->getConfig()->get('discordserverbox_announcementText')),
            'announcement_url' => trim((string)$this->getConfig()->get('discordserverbox_announcementUrl')),
            'announcement_date' => trim((string)$this->getConfig()->get('discordserverbox_announcementDate')),
        ];

        $serverWidget = new ServerWidget($settings['server_id'], $settings['invite_url'], $settings['cache_ttl']);
        $result = $serverWidget->fetch();
        $discord = $result['data'];

        $title = $settings['title'] !== '' ? $settings['title'] : ($discord['server_name'] ?: $this->getTrans('moduleName'));
        $memberPreview = [];
        if ($settings['show_member_preview'] && !empty($discord['members'])) {
            $memberPreview = array_slice($discord['members'], 0, $settings['member_preview_limit']);
        }

        $showAnnouncement = $settings['show_announcement']
            && (
                $settings['announcement_title'] !== ''
                || $settings['announcement_text'] !== ''
                || $settings['announcement_url'] !== ''
                || $settings['announcement_date'] !== ''
            );

        $this->getView()->setArray([
            'settings' => $settings,
            'title' => $title,
            'discord' => $discord,
            'memberPreview' => $memberPreview,
            'showAnnouncement' => $showAnnouncement,
            'hasLiveData' => $result['success'],
            'errorMessage' => $result['success'] ? '' : $result['error'],
        ]);
    }
}
