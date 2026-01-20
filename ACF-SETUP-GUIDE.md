# ACF Field Setup Guide for Radio Station Manager

This plugin requires **Advanced Custom Fields (ACF)** plugin to function properly.

## Required Plugin

- **Advanced Custom Fields** (free or PRO version)
- Download: https://wordpress.org/plugins/advanced-custom-fields/

## Quick Import Method (Recommended)

The fastest way to set up all required fields is to import the JSON field groups.

### Steps:

1. Install and activate the ACF plugin
2. Go to **Custom Fields → Tools** in WordPress admin
3. Click the **Import Field Groups** tab
4. Copy one of the JSON blocks below
5. Paste it into the import text box
6. Click **Import JSON**
7. Repeat for all three field groups below

---

## Field Group 1: Radio Show Details

**What it does:** Adds scheduling fields to radio shows (days, start time, end time)

**Location:** Appears when editing Radio Shows

**JSON to Import:**
```json
{
    "key": "group_radio_show_details",
    "title": "Radio Show Details",
    "fields": [
        {
            "key": "field_show_days",
            "label": "Show Days",
            "name": "show_days",
            "type": "checkbox",
            "instructions": "Select the days this show airs",
            "required": 1,
            "choices": {
                "monday": "Monday",
                "tuesday": "Tuesday",
                "wednesday": "Wednesday",
                "thursday": "Thursday",
                "friday": "Friday",
                "saturday": "Saturday",
                "sunday": "Sunday"
            },
            "default_value": [],
            "layout": "vertical",
            "toggle": 0,
            "return_format": "value"
        },
        {
            "key": "field_start_time",
            "label": "Start Time",
            "name": "start_time",
            "type": "time_picker",
            "instructions": "When does the show start?",
            "required": 1,
            "display_format": "g:i a",
            "return_format": "H:i:s"
        },
        {
            "key": "field_end_time",
            "label": "End Time",
            "name": "end_time",
            "type": "time_picker",
            "instructions": "When does the show end?",
            "required": 1,
            "display_format": "g:i a",
            "return_format": "H:i:s"
        }
    ],
    "location": [
        [
            {
                "param": "post_type",
                "operator": "==",
                "value": "radio_show"
            }
        ]
    ],
    "menu_order": 0,
    "position": "normal",
    "style": "default",
    "label_placement": "top",
    "instruction_placement": "label",
    "active": true
}
```

---

## Field Group 2: Hero Slide Fields

**What it does:** Adds all customization options for hero slider slides

**Location:** Appears when editing Hero Slides

**JSON to Import:**
```json
{
    "key": "group_hero_slide_fields",
    "title": "Hero Slide Fields",
    "fields": [
        {
            "key": "field_background_image",
            "label": "Background Image",
            "name": "background_image",
            "type": "image",
            "instructions": "Full-width background image for the slide",
            "required": 1,
            "return_format": "array",
            "preview_size": "medium",
            "library": "all"
        },
        {
            "key": "field_heading",
            "label": "Heading",
            "name": "heading",
            "type": "text",
            "instructions": "Main headline text",
            "required": 0
        },
        {
            "key": "field_subheading",
            "label": "Subheading",
            "name": "subheading",
            "type": "text",
            "instructions": "Secondary headline",
            "required": 0
        },
        {
            "key": "field_description",
            "label": "Description",
            "name": "description",
            "type": "textarea",
            "instructions": "Descriptive text",
            "required": 0,
            "rows": 3
        },
        {
            "key": "field_button_text",
            "label": "Button Text",
            "name": "button_text",
            "type": "text",
            "instructions": "Call-to-action button text",
            "required": 0
        },
        {
            "key": "field_button_link",
            "label": "Button Link",
            "name": "button_link",
            "type": "url",
            "instructions": "Where should the button link to?",
            "required": 0
        },
        {
            "key": "field_layout",
            "label": "Layout",
            "name": "layout",
            "type": "select",
            "instructions": "Choose text and image placement",
            "required": 0,
            "choices": {
                "text-left": "Text Left, Image Right",
                "text-right": "Text Right, Image Left"
            },
            "default_value": "text-left",
            "allow_null": 0,
            "multiple": 0,
            "ui": 0,
            "return_format": "value"
        },
        {
            "key": "field_overlay_photo",
            "label": "Overlay Photo",
            "name": "overlay_photo",
            "type": "image",
            "instructions": "Optional image to display alongside text",
            "required": 0,
            "return_format": "array",
            "preview_size": "medium",
            "library": "all"
        },
        {
            "key": "field_overlay_color",
            "label": "Overlay Color",
            "name": "overlay_color",
            "type": "color_picker",
            "instructions": "Color overlay on background image",
            "required": 0,
            "default_value": "#000000",
            "enable_opacity": 0,
            "return_format": "string"
        },
        {
            "key": "field_overlay_opacity",
            "label": "Overlay Opacity",
            "name": "overlay_opacity",
            "type": "number",
            "instructions": "0-100 (0 = transparent, 100 = solid)",
            "required": 0,
            "default_value": 50,
            "min": 0,
            "max": 100,
            "step": 5
        },
        {
            "key": "field_heading_color",
            "label": "Heading Color",
            "name": "heading_color",
            "type": "color_picker",
            "instructions": "Color for the main heading",
            "required": 0,
            "default_value": "#ffffff",
            "enable_opacity": 0,
            "return_format": "string"
        },
        {
            "key": "field_subheading_color",
            "label": "Subheading Color",
            "name": "subheading_color",
            "type": "color_picker",
            "instructions": "Color for the subheading",
            "required": 0,
            "default_value": "#ffffff",
            "enable_opacity": 0,
            "return_format": "string"
        },
        {
            "key": "field_description_color",
            "label": "Description Color",
            "name": "description_color",
            "type": "color_picker",
            "instructions": "Color for the description text",
            "required": 0,
            "default_value": "#ffffff",
            "enable_opacity": 0,
            "return_format": "string"
        },
        {
            "key": "field_button_bg_color",
            "label": "Button Background Color",
            "name": "button_bg_color",
            "type": "color_picker",
            "instructions": "Background color for the button",
            "required": 0,
            "default_value": "#0066cc",
            "enable_opacity": 0,
            "return_format": "string"
        },
        {
            "key": "field_button_text_color",
            "label": "Button Text Color",
            "name": "button_text_color",
            "type": "color_picker",
            "instructions": "Text color for the button",
            "required": 0,
            "default_value": "#ffffff",
            "enable_opacity": 0,
            "return_format": "string"
        },
        {
            "key": "field_text_bg_overlay",
            "label": "Text Background Overlay",
            "name": "text_bg_overlay",
            "type": "true_false",
            "instructions": "Add a background box behind text for better readability",
            "required": 0,
            "message": "Enable text background overlay",
            "default_value": 0,
            "ui": 1,
            "ui_on_text": "Yes",
            "ui_off_text": "No"
        },
        {
            "key": "field_text_bg_color",
            "label": "Text Background Color",
            "name": "text_bg_color",
            "type": "color_picker",
            "instructions": "Color for the text background box",
            "required": 0,
            "conditional_logic": [
                [
                    {
                        "field": "field_text_bg_overlay",
                        "operator": "==",
                        "value": "1"
                    }
                ]
            ],
            "default_value": "#000000",
            "enable_opacity": 0,
            "return_format": "string"
        },
        {
            "key": "field_text_bg_opacity",
            "label": "Text Background Opacity",
            "name": "text_bg_opacity",
            "type": "number",
            "instructions": "0-100 (transparency of text background)",
            "required": 0,
            "conditional_logic": [
                [
                    {
                        "field": "field_text_bg_overlay",
                        "operator": "==",
                        "value": "1"
                    }
                ]
            ],
            "default_value": 70,
            "min": 0,
            "max": 100,
            "step": 5
        }
    ],
    "location": [
        [
            {
                "param": "post_type",
                "operator": "==",
                "value": "hero-slide"
            }
        ]
    ],
    "menu_order": 0,
    "position": "normal",
    "style": "default",
    "label_placement": "top",
    "instruction_placement": "label",
    "active": true
}
```

---

## Field Group 3: Concert Details

**What it does:** Adds venue, date, and ticket information to concerts

**Location:** Appears when editing Concerts

**JSON to Import:**
```json
{
    "key": "group_concert_details",
    "title": "Concert Details",
    "fields": [
        {
            "key": "field_concert_image",
            "label": "Concert Image",
            "name": "concert_image",
            "type": "image",
            "instructions": "Image for the concert/artist",
            "required": 1,
            "return_format": "array",
            "preview_size": "medium",
            "library": "all"
        },
        {
            "key": "field_event_date",
            "label": "Event Date",
            "name": "event_date",
            "type": "date_picker",
            "instructions": "When is the concert?",
            "required": 1,
            "display_format": "m/d/Y",
            "return_format": "Ymd",
            "first_day": 0
        },
        {
            "key": "field_show_time",
            "label": "Show Time",
            "name": "show_time",
            "type": "text",
            "instructions": "e.g., \"8:00 PM\" or \"Doors at 7:00 PM\"",
            "required": 0
        },
        {
            "key": "field_venue_name",
            "label": "Venue Name",
            "name": "venue_name",
            "type": "text",
            "instructions": "Name of the venue",
            "required": 1
        },
        {
            "key": "field_city",
            "label": "City",
            "name": "city",
            "type": "text",
            "instructions": "City where the concert is taking place",
            "required": 1
        },
        {
            "key": "field_state",
            "label": "State",
            "name": "state",
            "type": "text",
            "instructions": "Two-letter state code (e.g., WV, PA, OH)",
            "required": 1,
            "maxlength": 2
        },
        {
            "key": "field_ticket_link",
            "label": "Ticket Link",
            "name": "ticket_link",
            "type": "url",
            "instructions": "Link to purchase tickets",
            "required": 0
        }
    ],
    "location": [
        [
            {
                "param": "post_type",
                "operator": "==",
                "value": "concert"
            }
        ]
    ],
    "menu_order": 0,
    "position": "normal",
    "style": "default",
    "label_placement": "top",
    "instruction_placement": "label",
    "active": true
}
```

---

## Manual Setup (If Import Doesn't Work)

If you prefer to create fields manually or import isn't working:

### Radio Show Details Fields

1. Go to **Custom Fields → Add New**
2. Title: `Radio Show Details`
3. Add Location Rule: Post Type = `radio_show`
4. Add these fields:
   - **Show Days** (Checkbox) - Field name: `show_days`
     - Choices: monday, tuesday, wednesday, thursday, friday, saturday, sunday
   - **Start Time** (Time Picker) - Field name: `start_time`
   - **End Time** (Time Picker) - Field name: `end_time`

### Hero Slide Fields

1. Go to **Custom Fields → Add New**
2. Title: `Hero Slide Fields`
3. Add Location Rule: Post Type = `hero-slide`
4. Add 18 fields (see full list in JSON above)

### Concert Details Fields

1. Go to **Custom Fields → Add New**
2. Title: `Concert Details`
3. Add Location Rule: Post Type = `concert`
4. Add these fields:
   - **Concert Image** (Image) - Field name: `concert_image`
   - **Event Date** (Date Picker) - Field name: `event_date`
   - **Show Time** (Text) - Field name: `show_time`
   - **Venue Name** (Text) - Field name: `venue_name`
   - **City** (Text) - Field name: `city`
   - **State** (Text) - Field name: `state`
   - **Ticket Link** (URL) - Field name: `ticket_link`

---

## Verification

After importing/creating all field groups, you should see them appear when:

- Editing a **Radio Show** → Shows scheduling fields
- Editing a **Hero Slide** → Shows all design customization fields
- Editing a **Concert** → Shows venue and event information fields

## Troubleshooting

**Fields not showing up?**
- Make sure ACF plugin is activated
- Check Location Rules are set correctly
- Try deactivating and reactivating the Radio Station Manager plugin
- Go to Settings → Permalinks and click Save Changes

**Import not working?**
- Make sure you're copying the ENTIRE JSON block including all curly braces
- Try importing one field group at a time
- Verify you're in Custom Fields → Tools → Import tab

---

## Support

For additional help with ACF setup, visit:
- ACF Documentation: https://www.advancedcustomfields.com/resources/
- Plugin Support: Contact JustinWd @ WVRC Digital