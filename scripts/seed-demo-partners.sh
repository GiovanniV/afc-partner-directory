#!/usr/bin/env bash
# Local bootstrap only: idempotent demo partner seeding (not used in production).
#
# Usage:
#   ./scripts/seed-demo-partners.sh          # create or update six demo partners
#   ./scripts/seed-demo-partners.sh --fresh  # delete seeded demo partners, then reseed
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

export DDEV_NONINTERACTIVE=true

META_SEEDED_DEMO="_afc_partner_seeded_demo"
META_CATEGORY="_afc_partner_category"
META_WEBSITE="_afc_partner_website_url"
DEMO_WEBSITE="https://afcscholarshipfund.org/"

FRESH_MODE=0
if [[ "${1:-}" == "--fresh" ]]; then
	FRESH_MODE=1
elif [[ -n "${1:-}" ]]; then
	echo "Usage: $0 [--fresh]" >&2
	exit 1
fi

# slug|title|category
DEMO_PARTNERS=(
	"arizona-scholarship-alliance|Arizona Scholarship Alliance|Scholarship Partner"
	"community-foundation-for-education|Community Foundation for Education|Community Partner"
	"donorbridge-giving-platform|DonorBridge Giving Platform|Donor Platform"
	"edchoice-resource-center|EdChoice Resource Center|Technology Partner"
	"florida-school-choice-network|Florida School Choice Network|School Partner"
	"nevada-education-partners|Nevada Education Partners|Regional Education"
)

wp() {
	ddev wp "$@"
}

find_partner_id_by_slug() {
	local slug="$1"
	wp post list \
		--post_type=partner \
		--name="$slug" \
		--post_status=any \
		--field=ID \
		--format=ids 2>/dev/null | head -n1 | tr -d '[:space:]'
}

delete_seeded_demo_partners() {
	local ids meta_ids slug id

	echo "==> Removing existing seeded demo partners..."

	meta_ids="$(wp post list \
		--post_type=partner \
		--meta_key="$META_SEEDED_DEMO" \
		--meta_value=1 \
		--field=ID \
		--format=ids 2>/dev/null || true)"

	for id in $meta_ids; do
		[[ -z "$id" ]] && continue
		local title
		title="$(wp post get "$id" --field=post_title 2>/dev/null || echo "ID $id")"
		wp post delete "$id" --force >/dev/null
		echo "  Deleted seeded demo partner: $title (ID $id)"
	done

	for entry in "${DEMO_PARTNERS[@]}"; do
		IFS='|' read -r slug _ _ <<<"$entry"
		id="$(find_partner_id_by_slug "$slug")"
		if [[ -n "$id" ]]; then
			local title
			title="$(wp post get "$id" --field=post_title 2>/dev/null || echo "$slug")"
			wp post delete "$id" --force >/dev/null
			echo "  Deleted demo partner by slug: $title (ID $id)"
		fi
	done
}

upsert_demo_partner() {
	local slug="$1"
	local title="$2"
	local category="$3"
	local id

	id="$(find_partner_id_by_slug "$slug")"

	if [[ -n "$id" ]]; then
		wp post update "$id" \
			--post_title="$title" \
			--post_name="$slug" \
			--post_status=publish >/dev/null
		wp post meta update "$id" "$META_CATEGORY" "$category" >/dev/null
		wp post meta update "$id" "$META_WEBSITE" "$DEMO_WEBSITE" >/dev/null
		wp post meta update "$id" "$META_SEEDED_DEMO" "1" >/dev/null
		echo "  Updated: $title (ID $id)"
		return
	fi

	id="$(wp post create \
		--post_type=partner \
		--post_title="$title" \
		--post_name="$slug" \
		--post_status=publish \
		--porcelain)"

	wp post meta update "$id" "$META_CATEGORY" "$category" >/dev/null
	wp post meta update "$id" "$META_WEBSITE" "$DEMO_WEBSITE" >/dev/null
	wp post meta update "$id" "$META_SEEDED_DEMO" "1" >/dev/null
	echo "  Created: $title (ID $id)"
}

echo "==> AFC Partner Directory — demo partner seed"
if [[ "$FRESH_MODE" -eq 1 ]]; then
	echo "    Mode: --fresh (reset seeded demo partners)"
	delete_seeded_demo_partners
else
	echo "    Mode: idempotent (create or update by slug)"
fi

echo "==> Seeding six demo partners..."
for entry in "${DEMO_PARTNERS[@]}"; do
	IFS='|' read -r slug title category <<<"$entry"
	upsert_demo_partner "$slug" "$title" "$category"
done

echo ""
echo "==> Verification"
PUBLISHED_COUNT="$(wp post list --post_type=partner --post_status=publish --format=count)"
echo "Published partner count: $PUBLISHED_COUNT"
echo ""
wp post list \
	--post_type=partner \
	--post_status=publish \
	--fields=ID,post_title,post_name,post_status \
	--format=table

SEEDED_COUNT="$(wp post list \
	--post_type=partner \
	--post_status=publish \
	--meta_key="$META_SEEDED_DEMO" \
	--meta_value=1 \
	--format=count 2>/dev/null || echo 0)"

echo ""
echo "Seed complete: $SEEDED_COUNT published seeded demo partner(s)."
if [[ "$SEEDED_COUNT" != "6" ]]; then
	echo "Warning: expected 6 published seeded demo partners. Review partners above." >&2
	exit 1
fi

echo "Open http://afc-partner-directory.ddev.site/ to view the directory."
