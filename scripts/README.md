# Scripts

This folder is intended for short development or debugging scripts. Do NOT commit production secrets or long dumps here.

Guidelines:

- Keep scripts small and remove them after use.
- Do not store credentials or large data dumps.
- Prefer using tinker (`php artisan tinker`) or artisan commands for debugging.

To run a script locally:

```bash
php scripts/my_script.php
```

If you need a permanent tool, consider moving it into a proper Artisan command instead of a standalone script.
