#!/bin/bash


echo "📦 Installing dependencies with pnpm..."
CI=true pnpm install 

echo "🔑 Generating application key..."
php artisan key:generate

echo "🧹 Wiping the database..."
php artisan db:wipe

echo "🗄️  Running database migrations..."
php artisan migrate --force

echo "🌱 Seeding the database..."
php artisan db:seed 

echo "✅ Setup complete!"
