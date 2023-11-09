# Goonhub

## Development

Uses docker via Laravel Sail. https://laravel.com/docs/9.x/installation#laravel-and-docker

For initial setup follow instructions here: https://laravel.com/docs/9.x/sail#installing-composer-dependencies-for-existing-projects

On Windows, I highly suggest doing all development on WSL 2, using VSCode with the remote development extension.

Automatically generated docs will be available at `http://localhost/docs/api`, and the Laravel development assistance thing will be at `http://localhost/telescope`.

### Initial setup

From the repo root directory:

```bash
cp .env.example .env
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan create-user
./vendor/bin/sail artisan create-api-token <user-id>
```

The above will initialize the database, guide you through creating a user, and then guide you through creating an API token.

### Limitations

There are some external services that the API relies on that are not yet public/integrated. As such, the following features are unavailable during development:

- Any communication with game servers
- Map switches
- VPN checking (unless you configure with your own IPQualityScore credentials)
