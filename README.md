# Goonhub

## Development

Uses docker via Laravel Sail. https://laravel.com/docs/11.x/installation#laravel-and-docker

On Windows, I highly suggest doing all development on WSL 2, using VSCode with the remote development extension.

Automatically generated docs will be available at `http://localhost/docs/api`, and the Laravel development assistance thing will be at `http://localhost/telescope`.

### Initial setup

- Navigate to your project root
- Create a shared docker network:

```bash
docker network create sail
```

- Install dependencies:

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

- Prepare your environment:

```bash
echo -e "\nalias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'" >> ~/.bashrc
source ~/.bashrc
```

- Bootstrap your application:
  
  This might take a while as it builds the docker containers.

```bash
sail up -d
sail artisan initial-setup
```

The above will initialize the database, guide you through creating a user, and then guide you through creating an API token.

- Start the frontend

```bash
sail npm i
sail npm run dev
```

The above will install the frontend packages and start the dev server, allowing you to access the web UI at `http://localhost`.

### Limitations

There are some external services that the API relies on that are not yet public/integrated. As such, the following features are unavailable during development:

- Any communication with game servers
- Map switches
- VPN checking (unless you configure with your own IPQualityScore credentials)
