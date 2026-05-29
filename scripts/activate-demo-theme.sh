#!/usr/bin/env bash
# Activate AFC Partner Shell theme and provision the Partner Directory Demo homepage.
set -euo pipefail

cd "$(dirname "${BASH_SOURCE[0]}")/.."
export DDEV_NONINTERACTIVE=true

DEMO_BLOCK='<!-- wp:afc/partner-directory {"showHeading":false} /-->'

echo "==> Activating AFC Partner Shell theme..."
ddev wp theme activate afc-partner-shell

DEMO_PAGE_ID="$(ddev wp post list --post_type=page --name=partner-directory-demo --field=ID --format=ids 2>/dev/null | head -1 || true)"

if [ -z "$DEMO_PAGE_ID" ]; then
	echo "==> Creating Partner Directory Demo page..."
	DEMO_PAGE_ID="$(ddev wp post create \
		--post_type=page \
		--post_title='Partner Directory Demo' \
		--post_name='partner-directory-demo' \
		--post_status=publish \
		--post_content="$DEMO_BLOCK" \
		--porcelain)"
	echo "  Created page ID $DEMO_PAGE_ID"
else
	echo "==> Updating Partner Directory Demo page (ID $DEMO_PAGE_ID)..."
	ddev wp post update "$DEMO_PAGE_ID" --post_content="$DEMO_BLOCK" >/dev/null
fi

ddev wp option update show_on_front page
ddev wp option update page_on_front "$DEMO_PAGE_ID"
echo "Front page set to Partner Directory Demo (ID $DEMO_PAGE_ID)."

echo "==> Flushing rewrite rules..."
ddev wp rewrite flush --quiet

SITE_URL="$(ddev wp option get siteurl)"
echo ""
echo "Active theme: $(ddev wp theme list --status=active --field=name)"
echo "Homepage: ${SITE_URL}/"
echo "Expected after seeding: branded shell, hero, 6 partner cards, 6+ metric, internal profile links."
