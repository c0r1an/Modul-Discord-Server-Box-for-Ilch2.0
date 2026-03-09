<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Discordserverbox\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'discordserverbox',
        'version' => '1.0.0',
        'icon_small' => 'fa-brands fa-discord',
        'author' => 'c0r1an',
        'link' => 'https://github.com/c0r1an',
        'languages' => [
            'de_DE' => [
                'name' => 'Discord Server Box',
                'description' => 'Kleines Discord-Widget mit Einladungslink, Online-Status, Mitgliederzahl und optionaler Ankündigung.',
            ],
            'en_EN' => [
                'name' => 'Discord Server Box',
                'description' => 'Compact Discord widget with invite link, online status, member count and optional announcement.',
            ],
        ],
        'boxes' => [
            'discordserverbox' => [
                'de_DE' => [
                    'name' => 'Discord Server Box',
                ],
                'en_EN' => [
                    'name' => 'Discord Server Box',
                ],
            ],
        ],
        'ilchCore' => '2.2.0',
        'phpVersion' => '7.3',
    ];

    public function install()
    {
        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig
            ->set('discordserverbox_title', 'Discord Server')
            ->set('discordserverbox_subtitle', '')
            ->set('discordserverbox_serverId', '')
            ->set('discordserverbox_inviteUrl', '')
            ->set('discordserverbox_cacheTtl', '300')
            ->set('discordserverbox_showServerIcon', '1')
            ->set('discordserverbox_showDescription', '0')
            ->set('discordserverbox_showJoinButton', '1')
            ->set('discordserverbox_showOnlineCount', '1')
            ->set('discordserverbox_showMemberCount', '1')
            ->set('discordserverbox_showMemberPreview', '0')
            ->set('discordserverbox_memberPreviewLimit', '6')
            ->set('discordserverbox_showAnnouncement', '0')
            ->set('discordserverbox_announcementTitle', '')
            ->set('discordserverbox_announcementText', '')
            ->set('discordserverbox_announcementUrl', '')
            ->set('discordserverbox_announcementDate', '');
    }

    public function uninstall()
    {
        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->delete([
            'discordserverbox_title',
            'discordserverbox_subtitle',
            'discordserverbox_serverId',
            'discordserverbox_inviteUrl',
            'discordserverbox_cacheTtl',
            'discordserverbox_showServerIcon',
            'discordserverbox_showDescription',
            'discordserverbox_showJoinButton',
            'discordserverbox_showOnlineCount',
            'discordserverbox_showMemberCount',
            'discordserverbox_showMemberPreview',
            'discordserverbox_memberPreviewLimit',
            'discordserverbox_showAnnouncement',
            'discordserverbox_announcementTitle',
            'discordserverbox_announcementText',
            'discordserverbox_announcementUrl',
            'discordserverbox_announcementDate',
        ]);
    }

    public function getUpdate(string $installedVersion): string
    {
        return '"' . $this->config['key'] . '" Update-function executed.';
    }
}
