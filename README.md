# Radio Station Manager

Complete radio station management system for WordPress with scheduling, hosts, hero sliders, and concert calendars.

## Features

- **Radio Shows** - Manage your station's programming schedule
- **Hosts/DJs** - Create profiles for your on-air talent with images
- **Hero Slider** - Eye-catching homepage sliders with custom layouts
- **Concert Calendar** - Promote upcoming concerts and events
- **Multiple Shortcodes** - Display content anywhere on your site

## Installation

1. Upload the `radio-station-manager` folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Install and activate Advanced Custom Fields (ACF) plugin
4. Import the ACF field groups (see ACF Setup section below)
5. Go to Settings → Permalinks and click Save Changes

## ACF Setup Required

This plugin requires **Advanced Custom Fields (ACF)** plugin to be installed and active.

After activating the plugin, you need to create custom field groups in ACF. See the "Complete ACF Field Setup" section below for detailed instructions.

## Available Shortcodes

### Radio Schedule Shortcodes

#### [now_playing]
Displays the currently airing show based on day/time.

**Attributes:**
- `show_image` - yes/no (default: no)
- `show_time` - yes/no (default: yes)
- `image_only` - yes/no (default: no) - Shows only the featured image
- `image_size` - thumbnail/medium/large/full (default: full)
- `layout` - horizontal/vertical (default: horizontal)
- `debug` - yes/no (default: no) - Shows debug information

**Examples:**
```
[now_playing]
[now_playing show_image="yes" show_time="yes"]
[now_playing image_only="yes" image_size="large"]
```

#### [weekly_schedule]
Displays the weekly programming schedule.

**Attributes:**
- `days` - all/today/monday/tuesday/etc (default: all)
- `show_empty` - yes/no (default: yes)
- `show_images` - yes/no (default: no)

**Examples:**
```
[weekly_schedule]
[weekly_schedule days="today" show_images="yes"]
[weekly_schedule days="monday" show_empty="no"]
```

#### [upcoming_shows]
Shows the next upcoming shows.

**Attributes:**
- `limit` - number of shows to display (default: 5)
- `show_images` - yes/no (default: yes)
- `show_day` - yes/no (default: yes)
- `layout` - vertical/horizontal (default: vertical)
- `columns` - 2/3/4 (default: 3) - Only for horizontal layout

**Examples:**
```
[upcoming_shows limit="5"]
[upcoming_shows limit="3" layout="horizontal" columns="3"]
[upcoming_shows show_images="no" show_day="no"]
```

#### [all_shows]
Lists all radio shows.

**Attributes:**
- `show_images` - yes/no (default: no)

**Examples:**
```
[all_shows]
[all_shows show_images="yes"]
```

#### [all_hosts]
Displays a grid of all hosts/DJs.

**Attributes:**
- `show_images` - yes/no (default: yes)
- `show_count` - yes/no (default: yes) - Show number of shows
- `show_times` - yes/no (default: no) - Show when their shows air
- `show_description` - yes/no (default: yes)
- `columns` - 2/3/4 (default: 3)
- `hide_empty` - yes/no (default: yes) - Hide hosts with no shows
- `include` - Comma-separated list of host slugs to include
- `exclude` - Comma-separated list of host slugs to exclude

**Examples:**
```
[all_hosts]
[all_hosts columns="4" show_times="yes"]
[all_hosts include="john-doe,jane-smith"]
[all_hosts exclude="former-host"]
```

### Hero Slider Shortcode

#### [hero_slider]
Displays the hero slider with all published slides.

**No attributes needed.**

**Example:**
```
[hero_slider]
```

### Concert Calendar Shortcode

#### [concert_calendar]
Displays upcoming concerts grouped by month.

**No attributes needed.**

**Example:**
```
[concert_calendar]
```

## Usage Tips

### Creating a Show
1. Go to **Shows → Add New**
2. Add title, description, and featured image
3. Fill in ACF fields:
   - Select days the show airs
   - Set start and end times
4. Assign host(s) from the Hosts taxonomy
5. Publish

### Adding a Host
1. Go to **Shows → Hosts**
2. Click **Add New Host**
3. Enter name and optional description
4. Upload a host image
5. Save

### Creating Hero Slides
1. Go to **Hero Slider → Add New**
2. Set the slide title (for admin reference only)
3. Upload background image
4. Fill in ACF fields for text content and styling
5. Set menu order for slide sequence
6. Publish

### Adding Concerts
1. Go to **Concerts → Add New**
2. Add concert/artist name as title
3. Upload concert image
4. Fill in ACF fields:
   - Event date
   - Venue details
   - Ticket link
5. Publish

## Styling Customization

The plugin uses CSS variables that pull from your theme's color palette. You can override these in your theme's Additional CSS:
```css
:root {
    --srs-now-playing-bg: #your-color;
    --srs-schedule-time: #your-color;
    --srs-hosts-link-color: #your-color;
}
```

## Support

For issues or questions, contact JustinWd @ WVRC Digital.

## Changelog

### Version 2.0.0
- Complete rewrite as unified plugin
- Added hero slider functionality
- Added concert calendar
- Improved code organization
- Enhanced shortcode options

### Version 1.3.1
- Fixed show scheduling logic
- Added debug mode for troubleshooting
```

---
