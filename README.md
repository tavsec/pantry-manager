# Pantry Manager

A web application for tracking food inventory in your pantry. Keep track of what you have, when you bought it, when it expires, and log your usage over time.

## Features

- 📦 **Track Pantry Items** - Add items with quantity, purchase date, and expiration date
- 📸 **Photo Upload** - Attach photos to your items
- 🏷️ **Categories & Locations** - Organize items by category and storage location
- 📊 **Usage Logging** - Log when you consume, waste, or add items
- 📈 **Usage History** - View complete history of all pantry activity
- 🔔 **Expiration Alerts** - Visual indicators for expired and expiring items
- 🔍 **Search & Filter** - Find items quickly with powerful filters
- 🔒 **User Authentication** - Secure multi-user support

## Quick Start with Docker

The easiest way to deploy Pantry Manager is using Docker:

```bash
docker compose up -d
```

Then open `http://localhost:8080` in your browser!

📖 **See [DOCKER-QUICKSTART.md](DOCKER-QUICKSTART.md) for the 2-minute setup guide**

📖 **See [DEPLOYMENT.md](DEPLOYMENT.md) for comprehensive deployment documentation**

## What You Get

- ✅ **Single container** - Everything included
- ✅ **SQLite database** - No external database needed
- ✅ **Automatic setup** - Migrations run automatically
- ✅ **Data persistence** - Your data is safe in Docker volumes
- ✅ **Production ready** - Nginx + PHP-FPM

## Requirements

- Docker 20.10+
- Docker Compose 2.0+

That's it! No PHP, no Node.js, no database server needed on your host machine.

## Technology Stack

- **Backend**: Laravel 12 (PHP 8.4)
- **Frontend**: Blade templates with Tailwind CSS
- **Database**: SQLite (configurable to MySQL/PostgreSQL)
- **Web Server**: Nginx + PHP-FPM

## Usage Tracking

The app includes comprehensive usage tracking:

- **Consumed** - Mark items when you use them
- **Wasted** - Track food waste
- **Expired** - Log expired items
- **Added** - Record when you restock

Items are automatically removed from your pantry when quantity reaches zero, but remain in your usage history.

## Development

For local development without Docker:

```bash
# Install dependencies
composer install
npm install

# Set up environment
cp .env.example .env
php artisan key:generate

# Create SQLite database
touch database/database.sqlite

# Run migrations
php artisan migrate

# Build assets
npm run build

# Start development server
php artisan serve
```

## License

Built with Laravel - open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
