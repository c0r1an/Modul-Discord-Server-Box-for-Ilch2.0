# Discord Server Box Module for Ilch 2.0

Compact Discord server widget for Ilch 2.0 with invite link, online status, member count and an optional announcement block.

## Features

- Frontend box for Discord server presentation
- Optional server icon, title and subtitle
- Online count and approximate member count
- Optional member preview from the Discord widget API
- Optional announcement block with title, text, date and link
- Admin settings for display options and cache duration
- Theme-friendly styling using transparent surfaces and layout colors

## Requirements

- Ilch Core `>= 2.2.0`
- PHP `>= 7.3`
- Discord server widget enabled for live widget/member preview data

## Installation

1. Copy this folder to:
   `application/modules/discordserverbox`
2. Install the module in Ilch admin.
3. Add the box `Discord Server Box` in the box management.
4. Configure server ID and/or invite link in the module settings.

## Configuration

Available settings in admin:

- Server ID
- Invite link
- Box title and subtitle
- Cache duration
- Show server icon
- Show Discord description
- Show join button
- Show online count
- Show member count
- Show member preview with limit
- Show optional announcement block

## Notes

- Member count comes from the public invite API.
- Member preview and widget invite come from the public Discord widget API.
- If the Discord widget is disabled, live data is limited to what the invite API returns.
