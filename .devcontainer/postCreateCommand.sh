#!/bin/bash


echo "📦 Installing dependencies with pnpm..."
CI=true pnpm install 

echo "🔑 Generating application key..."
php artisan key:generate

if php artisan migrate:status --no-ansi > /dev/null 2>&1; then
    echo "🗄️  Database already initialized — running only new migrations..."
    php artisan migrate 
else
    echo "🧹 Fresh database detected — running full setup..."
    php artisan migrate 
    echo "🌱 Seeding the database..."
    php artisan db:seed
fi

echo "✅ Setup complete!"
