<?php
/** @var \Ilch\View $this */
static $discordServerBoxCssLoaded = false;
$settings = $this->get('settings');
$discord = $this->get('discord');
$memberPreview = $this->get('memberPreview');
$hasLiveData = (bool)$this->get('hasLiveData');
$showAnnouncement = (bool)$this->get('showAnnouncement');
$hasJoinLink = $settings['show_join_button'] && !empty($discord['invite_url']);
?>

<?php if (!$discordServerBoxCssLoaded): ?>
    <link rel="stylesheet" href="<?=$this->getBaseUrl('application/modules/discordserverbox/static/css/discord-server-box.css') ?>">
    <?php $discordServerBoxCssLoaded = true; ?>
<?php endif; ?>

<div class="discord-server-box-widget card border-0">
    <div class="card-body discord-server-box-widget__body">
        <div class="discord-server-box-widget__header d-flex flex-column align-items-center justify-content-center text-center">
            <div class="discord-server-box-widget__avatar-wrap mx-auto">
                <?php if ($settings['show_server_icon'] && !empty($discord['icon_url'])): ?>
                    <img class="discord-server-box-widget__icon rounded-circle" src="<?=$this->escape($discord['icon_url']) ?>" alt="<?=$this->escape($this->get('title')) ?>">
                <?php else: ?>
                    <div class="discord-server-box-widget__icon discord-server-box-widget__icon--fallback rounded-circle">
                        <i class="fa-brands fa-discord"></i>
                    </div>
                <?php endif; ?>
            </div>

            <div class="discord-server-box-widget__headline text-center mx-auto">
                <div class="discord-server-box-widget__eyebrow text-center">
                    <span class="discord-server-box-widget__status-badge mx-auto">
                        <span class="discord-server-box-widget__dot"></span>
                        Discord
                    </span>
                </div>
                <div class="discord-server-box-widget__title text-center"><?=$this->escape($this->get('title')) ?></div>
                <?php if (!empty($settings['subtitle'])): ?>
                    <div class="discord-server-box-widget__subtitle text-center"><?=$this->escape($settings['subtitle']) ?></div>
                <?php elseif ($settings['show_description'] && !empty($discord['description'])): ?>
                    <div class="discord-server-box-widget__subtitle text-center"><?=$this->escape($discord['description']) ?></div>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($settings['show_online_count'] || ($settings['show_member_count'] && $discord['member_count'] !== null)): ?>
            <div class="discord-server-box-widget__stats row g-2">
                <?php if ($settings['show_online_count'] && $discord['online_count'] !== null): ?>
                    <div class="col-6">
                        <div class="discord-server-box-widget__stat card">
                            <div class="card-body">
                                <div class="discord-server-box-widget__stat-top">
                                    <span class="discord-server-box-widget__dot"></span>
                                    <span class="discord-server-box-widget__stat-label"><?=$this->getTrans('onlineNow') ?></span>
                                </div>
                                <strong class="discord-server-box-widget__stat-value"><?=$this->escape($discord['online_count']) ?></strong>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($settings['show_member_count'] && $discord['member_count'] !== null): ?>
                    <div class="col-6">
                        <div class="discord-server-box-widget__stat card">
                            <div class="card-body">
                                <div class="discord-server-box-widget__stat-top">
                                    <i class="fa-solid fa-users"></i>
                                    <span class="discord-server-box-widget__stat-label"><?=$this->getTrans('members') ?></span>
                                </div>
                                <strong class="discord-server-box-widget__stat-value"><?=$this->escape($discord['member_count']) ?></strong>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($hasJoinLink): ?>
            <div class="discord-server-box-widget__actions">
                <a class="discord-server-box-widget__button btn btn-primary"
                   href="<?=$this->escape($discord['invite_url']) ?>"
                   target="_blank"
                   rel="noopener">
                    <i class="fa-brands fa-discord"></i> <?=$this->getTrans('joinServer') ?>
                </a>
            </div>
        <?php endif; ?>

        <?php if (!empty($memberPreview)): ?>
            <div class="discord-server-box-widget__members card">
                <div class="card-body">
                    <div class="discord-server-box-widget__section-title"><?=$this->getTrans('memberPreview') ?></div>
                    <div class="discord-server-box-widget__member-list">
                        <?php foreach ($memberPreview as $member): ?>
                            <div class="discord-server-box-widget__member">
                                <?php if (!empty($member['avatar_url'])): ?>
                                    <img src="<?=$this->escape($member['avatar_url']) ?>" alt="<?=$this->escape($member['name']) ?>">
                                <?php else: ?>
                                    <span class="discord-server-box-widget__member-fallback">
                                        <?=strtoupper(substr($this->escape($member['name']), 0, 1)) ?>
                                    </span>
                                <?php endif; ?>
                                <span class="discord-server-box-widget__member-name"><?=$this->escape($member['name']) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($showAnnouncement): ?>
            <div class="discord-server-box-widget__announcement card">
                <div class="card-body">
                    <?php if ($settings['announcement_date'] !== ''): ?>
                        <div class="discord-server-box-widget__announcement-date"><?=$this->escape($settings['announcement_date']) ?></div>
                    <?php endif; ?>

                    <?php if ($settings['announcement_title'] !== ''): ?>
                        <div class="discord-server-box-widget__section-title"><?=$this->escape($settings['announcement_title']) ?></div>
                    <?php endif; ?>

                    <?php if ($settings['announcement_text'] !== ''): ?>
                        <p><?=nl2br($this->escape($settings['announcement_text'])) ?></p>
                    <?php endif; ?>

                    <?php if ($settings['announcement_url'] !== ''): ?>
                        <a href="<?=$this->escape($settings['announcement_url']) ?>" target="_blank" rel="noopener">
                            <?=$this->getTrans('readAnnouncement') ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!$hasLiveData): ?>
            <div class="discord-server-box-widget__notice alert mb-0">
                <?php if ($this->get('errorMessage') !== ''): ?>
                    <?=$this->escape($this->get('errorMessage')) ?>
                <?php else: ?>
                    <?=$this->getTrans('noLiveDataAvailable') ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
