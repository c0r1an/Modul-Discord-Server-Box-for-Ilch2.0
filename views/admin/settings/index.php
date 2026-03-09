<?php /** @var \Ilch\View $this */ ?>

<form method="post">
    <?=$this->getTokenField() ?>

    <div class="alert alert-info">
        <strong><?=$this->getTrans('hint') ?>:</strong> <?=$this->getTrans('widgetHint') ?>
    </div>

    <div class="row mb-3<?=$this->validation()->hasError('server_id') ? ' has-error' : '' ?>">
        <label class="col-lg-3 col-form-label" for="discordServerId"><?=$this->getTrans('serverId') ?></label>
        <div class="col-lg-9">
            <input class="form-control" type="text" id="discordServerId" name="server_id" value="<?=$this->escape($this->originalInput('server_id', $this->get('serverId'))) ?>">
            <small class="form-text text-muted"><?=$this->getTrans('serverIdHelp') ?></small>
        </div>
    </div>

    <div class="row mb-3<?=$this->validation()->hasError('invite_url') ? ' has-error' : '' ?>">
        <label class="col-lg-3 col-form-label" for="discordInviteUrl"><?=$this->getTrans('inviteUrl') ?></label>
        <div class="col-lg-9">
            <input class="form-control" type="text" id="discordInviteUrl" name="invite_url" value="<?=$this->escape($this->originalInput('invite_url', $this->get('inviteUrl'))) ?>">
            <small class="form-text text-muted"><?=$this->getTrans('inviteUrlHelp') ?></small>
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-lg-3 col-form-label" for="discordTitle"><?=$this->getTrans('boxTitle') ?></label>
        <div class="col-lg-9">
            <input class="form-control" type="text" id="discordTitle" name="title" value="<?=$this->escape($this->originalInput('title', $this->get('title'))) ?>">
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-lg-3 col-form-label" for="discordSubtitle"><?=$this->getTrans('boxSubtitle') ?></label>
        <div class="col-lg-9">
            <input class="form-control" type="text" id="discordSubtitle" name="subtitle" value="<?=$this->escape($this->originalInput('subtitle', $this->get('subtitle'))) ?>">
        </div>
    </div>

    <div class="row mb-3<?=$this->validation()->hasError('cache_ttl') ? ' has-error' : '' ?>">
        <label class="col-lg-3 col-form-label" for="discordCacheTtl"><?=$this->getTrans('cacheTtl') ?></label>
        <div class="col-lg-3">
            <input class="form-control" type="number" min="60" max="86400" id="discordCacheTtl" name="cache_ttl" value="<?=$this->escape($this->originalInput('cache_ttl', $this->get('cacheTtl'))) ?>">
        </div>
    </div>

    <hr>

    <div class="row mb-3">
        <label class="col-lg-3 col-form-label"><?=$this->getTrans('displayOptions') ?></label>
        <div class="col-lg-9">
            <?php
            $checkboxes = [
                'show_server_icon' => ['showServerIcon', 'showServerIconLabel'],
                'show_description' => ['showDescription', 'showDescriptionLabel'],
                'show_join_button' => ['showJoinButton', 'showJoinButtonLabel'],
                'show_online_count' => ['showOnlineCount', 'showOnlineCountLabel'],
                'show_member_count' => ['showMemberCount', 'showMemberCountLabel'],
                'show_member_preview' => ['showMemberPreview', 'showMemberPreviewLabel'],
                'show_announcement' => ['showAnnouncement', 'showAnnouncementLabel'],
            ];
            ?>
            <?php foreach ($checkboxes as $field => [$viewValue, $labelKey]): ?>
                <div class="form-check mb-2">
                    <input type="hidden" name="<?=$field ?>" value="0">
                    <input class="form-check-input"
                           type="checkbox"
                           id="<?=$field ?>"
                           name="<?=$field ?>"
                           value="1"
                           <?=$this->originalInput($field, $this->get($viewValue)) === '1' ? 'checked' : '' ?>>
                    <label class="form-check-label" for="<?=$field ?>"><?=$this->getTrans($labelKey) ?></label>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="row mb-3<?=$this->validation()->hasError('member_preview_limit') ? ' has-error' : '' ?>">
        <label class="col-lg-3 col-form-label" for="memberPreviewLimit"><?=$this->getTrans('memberPreviewLimit') ?></label>
        <div class="col-lg-3">
            <input class="form-control" type="number" min="1" max="12" id="memberPreviewLimit" name="member_preview_limit" value="<?=$this->escape($this->originalInput('member_preview_limit', $this->get('memberPreviewLimit'))) ?>">
        </div>
    </div>

    <hr>

    <div class="row mb-3">
        <label class="col-lg-3 col-form-label" for="announcementTitle"><?=$this->getTrans('announcementTitle') ?></label>
        <div class="col-lg-9">
            <input class="form-control" type="text" id="announcementTitle" name="announcement_title" value="<?=$this->escape($this->originalInput('announcement_title', $this->get('announcementTitle'))) ?>">
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-lg-3 col-form-label" for="announcementText"><?=$this->getTrans('announcementText') ?></label>
        <div class="col-lg-9">
            <textarea class="form-control" rows="4" id="announcementText" name="announcement_text"><?=$this->escape($this->originalInput('announcement_text', $this->get('announcementText'))) ?></textarea>
        </div>
    </div>

    <div class="row mb-3<?=$this->validation()->hasError('announcement_url') ? ' has-error' : '' ?>">
        <label class="col-lg-3 col-form-label" for="announcementUrl"><?=$this->getTrans('announcementUrl') ?></label>
        <div class="col-lg-9">
            <input class="form-control" type="text" id="announcementUrl" name="announcement_url" value="<?=$this->escape($this->originalInput('announcement_url', $this->get('announcementUrl'))) ?>">
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-lg-3 col-form-label" for="announcementDate"><?=$this->getTrans('announcementDate') ?></label>
        <div class="col-lg-9">
            <input class="form-control" type="text" id="announcementDate" name="announcement_date" value="<?=$this->escape($this->originalInput('announcement_date', $this->get('announcementDate'))) ?>">
            <small class="form-text text-muted"><?=$this->getTrans('announcementDateHelp') ?></small>
        </div>
    </div>

    <?=$this->getSaveBar('saveButton') ?>
</form>
