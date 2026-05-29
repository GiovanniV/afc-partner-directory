#!/usr/bin/env bash
# Reproducible local WordPress setup for AFC Partner Directory.
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

export DDEV_NONINTERACTIVE=true

echo "==> Starting DDEV..."
ddev start

if ! ddev wp core is-installed 2>/dev/null; then
  echo "==> Downloading WordPress core..."
  ddev wp core download --locale=en_US

  echo "==> Installing WordPress..."
  ddev wp core install \
    --url="https://afc-partner-directory.ddev.site" \
    --title="AFC Partner Directory (Local)" \
    --admin_user=admin \
    --admin_password=admin \
    --admin_email=admin@example.com \
    --skip-email
fi

echo "==> Activating AFC Partner Directory plugin..."
ddev wp plugin activate afc-partner-directory

echo ""
echo "Done. Next steps for a full demo:"
echo "  ./scripts/seed-demo-partners.sh --fresh"
echo "  ./scripts/activate-demo-theme.sh"
echo "  ddev wp rewrite flush"
ddev describe
