# Headless REST consumer example (optional)

The primary frontend for AFC Partner Directory is the **Gutenberg block** (`afc/partner-directory`). This note shows how external clients can consume the same partner data via the WordPress REST API.

## Endpoint

```http
GET /wp-json/wp/v2/partner?per_page=100&status=publish
```

Single partner:

```http
GET /wp-json/wp/v2/partner/{id}
```

## Preferred response fields

| Field | Usage |
|-------|--------|
| `title.rendered` | Partner name |
| `afc_partner.website_url` | Website link |
| `afc_partner.category` | Category label |
| `logo_url` | Logo image URL |
| `featured_image_url` | Featured image URL |

Prefer these computed fields over resolving raw `_afc_partner_*` meta or attachment IDs in client code.

## Minimal fetch example (JavaScript)

```javascript
const response = await fetch('/wp-json/wp/v2/partner?per_page=100');
const partners = await response.json();

partners.forEach((partner) => {
  const name = partner.title?.rendered ?? '';
  const { website_url, category } = partner.afc_partner ?? {};
  const logo = partner.logo_url ?? '';
  // Use name, website_url, category, logo in your UI layer.
});
```

## When a headless consumer makes sense

- External marketing sites or microsites
- Mobile or other non-WordPress clients
- API contract validation before building a separate front end

For WordPress-operated sites, the Gutenberg block keeps editorial workflow and rendering in one place.
