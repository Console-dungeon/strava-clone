#!/usr/bin/env bash
# PostToolUse hook: auto-format file changed by Claude Code.
# Reads hook payload (JSON) from stdin, dispatches to the right formatter.

set +e
cd "$(dirname "$0")/../.." || exit 0

file=$(node -e 'const i=JSON.parse(require("fs").readFileSync(0,"utf8"));process.stdout.write(i.tool_input?.file_path||"")' 2>/dev/null)
[ -z "$file" ] && exit 0

case "$file" in
  */vendor/*|*/node_modules/*|*/resources/js/Components/ui/*) exit 0 ;;
esac

case "$file" in
  *.php)
    ./vendor/bin/pint "$file" >/dev/null 2>&1
    ;;
  *.vue|*.ts|*.tsx|*.js)
    pnpm exec prettier --write "$file" >/dev/null 2>&1
    pnpm exec eslint --fix "$file" >/dev/null 2>&1
    ;;
esac

exit 0
