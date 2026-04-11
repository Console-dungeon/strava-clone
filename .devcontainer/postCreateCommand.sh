#!/bin/bash

echo "🔑 Generating application key..."
php artisan key:generate

echo "🗄️  Running database migrations..."
php artisan migrate --force

echo "📦 Installing npm dependencies..."
npm install

echo "✅ Setup complete!"
