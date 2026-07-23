# Address Book — Laravel Backend

This project is fully Dockerized. No need to install PHP, Composer, MySQL, or Laravel Herd/Laragon/Xampp on your machine — everything runs inside containers.

## Prerequisites

Make sure you have the following installed on your machine:

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) (includes Docker Compose)
- [Git](https://git-scm.com/downloads)

That's it — no PHP, Composer, or MySQL needed locally.

---

## Step 1: Clone the Repository

```bash
git clone <your-repo-url>
cd address-book/backend
```

---

## Step 2: Create the `.env` File

Copy the example environment file:

```bash
cp .env.example .env
```

Open `.env` and make sure the database settings look like this (these values must match `docker-compose.yml`):

```env
APP_URL=http://localhost:8001
APP_PORT=8001

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=database_name
DB_USERNAME=user_name
DB_PASSWORD=yourPassword
```

> **Important:** `DB_HOST` must be `mysql` (the Docker service name), **not** `127.0.0.1` or `localhost`. Since MySQL runs in its own container, Laravel reaches it over the internal Docker network using the service name.

---

## Step 3: Build and Start the Containers

From the `backend` folder (where `docker-compose.yml` is located), run:

```bash
docker compose up -d --build
```

This will:

1. Build the Laravel app image (`backend` service) from the `Dockerfile`
2. Pull and start the `mysql:8.0` image
3. Create a Docker network so both containers can talk to each other
4. Create a persistent volume for MySQL data (`mysql_data`)

Check that both containers are running:

```bash
docker compose ps
```

You should see `address_backend` and `address_mysql` both in an `Up`/`healthy` state.

---

## Step 4: Generate the App Key

If `APP_KEY` in your `.env` is empty, generate one:

```bash
docker compose exec backend php artisan key:generate
```

---

## Step 5: Run Migrations

Once MySQL is healthy, run the database migrations inside the backend container:

```bash
docker compose exec backend php artisan migrate
```

If you also have seeders:

```bash
docker compose exec backend php artisan migrate --seed
```

---

## Step 6: Test the API

The app is now available at:

```
http://localhost:8001
```

Try hitting an endpoint in Postman or your browser, e.g.:

```
POST http://localhost:8001/api/v1/register
```

---

## Common Commands

| Action | Command |
|---|---|
| Start containers (detached) | `docker compose up -d` |
| Stop containers | `docker compose down` |
| Stop and remove volumes (⚠️ wipes DB data) | `docker compose down -v` |
| Rebuild after Dockerfile/composer.json changes | `docker compose build --no-cache` |
| View backend logs | `docker compose logs -f backend` |
| View MySQL logs | `docker compose logs -f mysql` |
| Open a shell inside the backend container | `docker compose exec backend bash` |
| Run any artisan command | `docker compose exec backend php artisan <command>` |
| Run composer command | `docker compose exec backend composer <command>` |
| Clear config/route/view cache | `docker compose exec backend php artisan optimize:clear` |

---

## Troubleshooting

**Getting `Connection refused` on MySQL (Host: 127.0.0.1)?**
This means Laravel is using a stale cached config. Fix it with:

```bash
docker compose exec backend php artisan config:clear
```

Then rebuild if the issue persists:

```bash
docker compose down
docker compose build --no-cache
docker compose up -d
```

**Port already in use?**
Change `APP_PORT` or `DB_PORT` in `.env` to a free port, then restart:

```bash
docker compose down
docker compose up -d
```

**MySQL container keeps restarting?**
Check logs:

```bash
docker compose logs mysql
```

Usually caused by a mismatched `MYSQL_ROOT_PASSWORD` after the volume was already initialized with a different password. Reset with:

```bash
docker compose down -v
docker compose up -d
```

⚠️ This deletes existing database data — only use in local/dev environments.

---

## Fresh Install (One-Shot Setup)

For a first-time setup, you can run all steps in sequence:

```bash
cp .env.example .env
docker compose up -d --build
docker compose exec backend php artisan key:generate
docker compose exec backend php artisan migrate --seed
```

Then open `http://localhost:8001` — the API is ready to use.