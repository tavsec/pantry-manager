# Pantry Manager - Docker Deployment Guide

This guide explains how to deploy Pantry Manager using Docker.

## Quick Start

### Prerequisites

- Docker installed (version 20.10 or higher)
- Docker Compose installed (version 2.0 or higher)

### Deploy with Docker Compose (Recommended)

1. **Clone or download the repository**

```bash
git clone <repository-url>
cd pantry-manager
```

2. **Start the application**

```bash
docker compose up -d
```

3. **Access the application**

Open your browser and navigate to: `http://localhost:8080`

4. **Register your first user**

The first user you register will be your admin account.

### Deploy with Docker only

If you prefer to use Docker without Docker Compose:

1. **Build the image**

```bash
docker build -t pantry-manager:latest .
```

2. **Run the container**

```bash
docker run -d \
  --name pantry-manager \
  -p 8080:80 \
  -v pantry-data:/var/www/html/database \
  -v pantry-storage:/var/www/html/storage/app \
  -e APP_URL=http://localhost:8080 \
  --restart unless-stopped \
  pantry-manager:latest
```

3. **Access the application**

Open your browser and navigate to: `http://localhost:8080`

## Configuration

### Environment Variables

You can customize the deployment using environment variables. The most important ones are:

| Variable | Default | Description |
|----------|---------|-------------|
| `APP_NAME` | "Pantry Manager" | Application name |
| `APP_URL` | http://localhost:8080 | Base URL for the application |
| `APP_DEBUG` | false | Enable debug mode (not recommended for production) |
| `LOG_LEVEL` | error | Logging level (debug, info, warning, error) |
| `DB_CONNECTION` | sqlite | Database driver (sqlite recommended) |
| `DB_DATABASE` | /var/www/html/database/database.sqlite | Database path |

### Custom Port

To run on a different port, change the port mapping:

```bash
docker compose up -d
```

Then edit `docker-compose.yml`:

```yaml
ports:
  - "3000:80"  # Change 3000 to your desired port
```

And update the `APP_URL` environment variable:

```yaml
environment:
  - APP_URL=http://localhost:3000
```

### Using Custom Domain

If you're deploying to a server with a domain name:

1. Edit `docker-compose.yml`:

```yaml
environment:
  - APP_URL=https://pantry.yourdomain.com
  - SESSION_SECURE_COOKIE=true  # For HTTPS
```

2. Set up a reverse proxy (nginx, Traefik, Caddy) to handle HTTPS

## Data Persistence

All data is persisted using Docker volumes:

- `pantry-data`: SQLite database
- `pantry-storage`: Uploaded photos and files

### Backup Your Data

**Backup database:**
```bash
docker compose exec pantry-manager cp /var/www/html/database/database.sqlite /tmp/backup.sqlite
docker cp pantry-manager:/tmp/backup.sqlite ./backup-$(date +%Y%m%d).sqlite
```

**Backup uploaded photos:**
```bash
docker run --rm \
  -v pantry-storage:/data \
  -v $(pwd):/backup \
  alpine tar czf /backup/storage-backup-$(date +%Y%m%d).tar.gz -C /data .
```

### Restore from Backup

**Restore database:**
```bash
docker cp ./backup.sqlite pantry-manager:/var/www/html/database/database.sqlite
docker compose restart
```

**Restore storage:**
```bash
docker run --rm \
  -v pantry-storage:/data \
  -v $(pwd):/backup \
  alpine sh -c "cd /data && tar xzf /backup/storage-backup.tar.gz"
docker compose restart
```

## Updating

To update to a new version:

1. **Pull the latest code**

```bash
git pull origin main
```

2. **Rebuild and restart**

```bash
docker compose down
docker compose build --no-cache
docker compose up -d
```

3. **Database migrations run automatically** on container start

## Monitoring

### View Logs

```bash
# All logs
docker compose logs -f

# Only application logs
docker compose logs -f pantry-manager
```

### Check Container Status

```bash
docker compose ps
```

### Health Check

```bash
docker compose exec pantry-manager wget -O- http://localhost/
```

## Troubleshooting

### Container won't start

Check the logs:
```bash
docker compose logs pantry-manager
```

### Permission issues

Reset permissions:
```bash
docker compose exec pantry-manager sh -c "chown -R www-data:www-data /var/www/html/storage /var/www/html/database"
```

### Database is locked

Restart the container:
```bash
docker compose restart
```

### Clear cache

```bash
docker compose exec pantry-manager php artisan cache:clear
docker compose exec pantry-manager php artisan config:clear
docker compose exec pantry-manager php artisan view:clear
docker compose restart
```

### Reset application (WARNING: Deletes all data!)

```bash
docker compose down -v
docker compose up -d
```

## Production Deployment

For production deployments:

1. **Use HTTPS** - Set up a reverse proxy with SSL certificates
2. **Change default secrets** - Generate new `APP_KEY`
3. **Regular backups** - Schedule automated backups
4. **Monitor logs** - Set up log aggregation
5. **Resource limits** - Configure Docker resource constraints
6. **Update regularly** - Keep the application updated

### Example Production docker-compose.yml

```yaml
version: '3.8'

services:
  pantry-manager:
    build: .
    image: pantry-manager:latest
    container_name: pantry-manager
    restart: always
    ports:
      - "127.0.0.1:8080:80"  # Only bind to localhost
    volumes:
      - pantry-data:/var/www/html/database
      - pantry-storage:/var/www/html/storage/app
    environment:
      - APP_ENV=production
      - APP_DEBUG=false
      - APP_URL=https://pantry.yourdomain.com
      - LOG_LEVEL=warning
      - SESSION_SECURE_COOKIE=true
    deploy:
      resources:
        limits:
          cpus: '1'
          memory: 512M
        reservations:
          cpus: '0.5'
          memory: 256M

volumes:
  pantry-data:
  pantry-storage:
```

## Support

For issues and questions:
- Check the logs: `docker compose logs -f`
- Review this documentation
- Submit issues to the repository

## License

[Your License Here]
