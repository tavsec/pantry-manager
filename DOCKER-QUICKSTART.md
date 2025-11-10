# Pantry Manager - Docker Quick Start

## TL;DR - Get Running in 2 Commands

```bash
# 1. Start the application
docker compose up -d

# 2. Open in browser
open http://localhost:8080
```

That's it! The application will:
- ✅ Automatically create the SQLite database
- ✅ Run all migrations
- ✅ Set up storage directories
- ✅ Generate encryption keys
- ✅ Be ready to use

## First Time Setup

1. **Register your account** at `http://localhost:8080/register`
2. **Start tracking your pantry!**

## Common Commands

```bash
# Start the app
docker compose up -d

# Stop the app
docker compose down

# View logs
docker compose logs -f

# Update to latest version
docker compose pull && docker compose up -d

# Backup database
docker compose exec pantry-manager cp /var/www/html/database/database.sqlite /tmp/backup.sqlite
docker cp pantry-manager:/tmp/backup.sqlite ./pantry-backup.sqlite
```

## Changing the Port

Edit `docker-compose.yml` and change:
```yaml
ports:
  - "8080:80"  # Change 8080 to your preferred port
```

Then update the URL:
```yaml
environment:
  - APP_URL=http://localhost:YOUR_PORT
```

Restart:
```bash
docker compose down && docker compose up -d
```

## Data Location

All your data is stored in Docker volumes:
- `pantry-data` - Your database
- `pantry-storage` - Uploaded photos

Your data persists even if you remove and recreate the container!

## Need Help?

See [DEPLOYMENT.md](DEPLOYMENT.md) for detailed documentation.
