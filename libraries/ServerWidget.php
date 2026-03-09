<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Discordserverbox\Libraries;

class ServerWidget
{
    /** @var string */
    private $serverId;

    /** @var string */
    private $inviteUrl;

    /** @var int */
    private $cacheTtl;

    public function __construct(string $serverId = '', string $inviteUrl = '', int $cacheTtl = 300)
    {
        $this->serverId = trim($serverId);
        $this->inviteUrl = trim($inviteUrl);
        $this->cacheTtl = max(60, $cacheTtl);
    }

    public function fetch(): array
    {
        $widgetData = null;
        $inviteData = null;
        $errors = [];
        $resolvedInviteUrl = $this->inviteUrl;

        if ($this->serverId !== '') {
            try {
                $widgetData = $this->fetchWidgetData($this->serverId);
                if (!$resolvedInviteUrl && !empty($widgetData['instant_invite'])) {
                    $resolvedInviteUrl = (string)$widgetData['instant_invite'];
                }
            } catch (\Throwable $exception) {
                $errors[] = $this->normalizeErrorMessage($exception->getMessage());
            }
        }

        $inviteCode = $this->extractInviteCode($resolvedInviteUrl);
        if ($inviteCode !== '') {
            try {
                $inviteData = $this->fetchInviteData($inviteCode);
                if (!$resolvedInviteUrl) {
                    $resolvedInviteUrl = 'https://discord.gg/' . $inviteCode;
                }
            } catch (\Throwable $exception) {
                $errors[] = $this->normalizeErrorMessage($exception->getMessage());
            }
        }

        $data = [
            'server_name' => '',
            'description' => '',
            'icon_url' => '',
            'invite_url' => $resolvedInviteUrl ?: '',
            'online_count' => null,
            'member_count' => null,
            'members' => [],
        ];

        if (!empty($inviteData['guild']['name'])) {
            $data['server_name'] = (string)$inviteData['guild']['name'];
        } elseif (!empty($widgetData['name'])) {
            $data['server_name'] = (string)$widgetData['name'];
        }

        if (!empty($inviteData['guild']['description'])) {
            $data['description'] = (string)$inviteData['guild']['description'];
        }

        if (!empty($inviteData['guild']['id']) && !empty($inviteData['guild']['icon'])) {
            $data['icon_url'] = $this->buildGuildIconUrl((string)$inviteData['guild']['id'], (string)$inviteData['guild']['icon']);
        }

        if (isset($inviteData['approximate_presence_count'])) {
            $data['online_count'] = (int)$inviteData['approximate_presence_count'];
        } elseif (isset($widgetData['presence_count'])) {
            $data['online_count'] = (int)$widgetData['presence_count'];
        }

        if (isset($inviteData['approximate_member_count'])) {
            $data['member_count'] = (int)$inviteData['approximate_member_count'];
        }

        if (!empty($widgetData['members']) && is_array($widgetData['members'])) {
            foreach ($widgetData['members'] as $member) {
                $name = (string)($member['username'] ?? $member['global_name'] ?? '');
                if ($name === '') {
                    continue;
                }

                $data['members'][] = [
                    'name' => $name,
                    'avatar_url' => (string)($member['avatar_url'] ?? ''),
                ];
            }
        }

        $success = $data['server_name'] !== ''
            || $data['invite_url'] !== ''
            || $data['online_count'] !== null
            || $data['member_count'] !== null
            || !empty($data['members']);

        return [
            'success' => $success,
            'error' => $success ? '' : ($errors[0] ?? ''),
            'data' => $data,
        ];
    }

    private function fetchWidgetData(string $serverId): array
    {
        return $this->fetchJson(
            'https://discord.com/api/guilds/' . rawurlencode($serverId) . '/widget.json',
            'widget_' . md5($serverId)
        );
    }

    private function fetchInviteData(string $inviteCode): array
    {
        return $this->fetchJson(
            'https://discord.com/api/v9/invites/' . rawurlencode($inviteCode) . '?with_counts=true&with_expiration=true',
            'invite_' . md5($inviteCode)
        );
    }

    private function fetchJson(string $url, string $cacheKey): array
    {
        $cacheFile = ROOT_PATH . '/cache/discordserverbox_' . $cacheKey . '.json';
        if (is_file($cacheFile) && (filemtime($cacheFile) + $this->cacheTtl) >= time()) {
            $cached = json_decode((string)file_get_contents($cacheFile), true);
            if (is_array($cached)) {
                return $cached;
            }
        }

        try {
            $payload = $this->loadUrl($url);
            $decoded = json_decode($payload, true);
            if (!is_array($decoded)) {
                throw new \RuntimeException('Discord returned invalid data.');
            }

            file_put_contents($cacheFile, json_encode($decoded));

            return $decoded;
        } catch (\Throwable $exception) {
            if (is_file($cacheFile)) {
                $cached = json_decode((string)file_get_contents($cacheFile), true);
                if (is_array($cached)) {
                    return $cached;
                }
            }

            throw $exception;
        }
    }

    private function loadUrl(string $url): string
    {
        if (function_exists('curl_init')) {
            $handle = curl_init($url);
            $options = [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS => 5,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_TIMEOUT => 20,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_USERAGENT => 'Ilch DiscordServerBox/1.0',
            ];

            $caFile = ROOT_PATH . '/certificate/cacert.pem';
            if (is_file($caFile)) {
                $options[CURLOPT_CAINFO] = $caFile;
            }

            curl_setopt_array($handle, $options);
            $content = (string)curl_exec($handle);
            $httpCode = (int)curl_getinfo($handle, CURLINFO_HTTP_CODE);
            $error = (string)curl_error($handle);
            curl_close($handle);

            if ($content === '' || ($httpCode >= 400 && $httpCode !== 0)) {
                $errorMessage = $this->extractApiError($content);
                throw new \RuntimeException($error !== '' ? $error : ($errorMessage !== '' ? $errorMessage : 'HTTP ' . $httpCode));
            }

            return $content;
        }

        $context = stream_context_create([
            'http' => [
                'timeout' => 20,
                'user_agent' => 'Ilch DiscordServerBox/1.0',
            ],
            'ssl' => [
                'verify_peer' => true,
                'verify_peer_name' => true,
            ],
        ]);

        $content = @file_get_contents($url, false, $context);
        if ($content === false) {
            throw new \RuntimeException('Discord data could not be loaded.');
        }

        return (string)$content;
    }

    private function extractInviteCode(string $invite): string
    {
        $invite = trim($invite);
        if ($invite === '') {
            return '';
        }

        if (preg_match('~discord(?:app)?\.com/invite/([A-Za-z0-9\-]+)~i', $invite, $matches)) {
            return $matches[1];
        }

        if (preg_match('~discord\.gg/([A-Za-z0-9\-]+)~i', $invite, $matches)) {
            return $matches[1];
        }

        if (preg_match('~^[A-Za-z0-9\-]+$~', $invite)) {
            return $invite;
        }

        return '';
    }

    private function buildGuildIconUrl(string $guildId, string $iconHash): string
    {
        $extension = strpos($iconHash, 'a_') === 0 ? 'gif' : 'png';

        return 'https://cdn.discordapp.com/icons/' . rawurlencode($guildId) . '/' . rawurlencode($iconHash) . '.' . $extension . '?size=128';
    }

    private function normalizeErrorMessage(string $message): string
    {
        $message = trim($message);
        if ($message === '') {
            return $this->trans('liveDataUnavailable', 'Discord live data is currently unavailable.');
        }

        if (stripos($message, '50004') !== false || stripos($message, 'widget') !== false) {
            return $this->trans('widgetDisabled', 'Discord widget disabled. Activate the server widget in Discord to enable live data.');
        }

        if (stripos($message, '404') !== false) {
            return $this->trans('discordNotFound', 'Discord server or invite not found.');
        }

        return $message;
    }

    private function extractApiError(string $content): string
    {
        $decoded = json_decode($content, true);
        if (!is_array($decoded) || empty($decoded['message'])) {
            return '';
        }

        $message = (string)$decoded['message'];
        if (!empty($decoded['code'])) {
            $message .= ' (' . $decoded['code'] . ')';
        }

        return $message;
    }

    private function trans(string $key, string $fallback): string
    {
        $translator = \Ilch\Registry::get('translator');
        if ($translator) {
            return (string)$translator->trans($key);
        }

        return $fallback;
    }
}
