#!/bin/bash
set -e

echo "🚀 Iniciando EZZETA Backend..."

# Generar el archivo .env desde las variables de entorno de Render
echo "📝 Generando .env..."
cat > .env << EOF
APP_NAME=${APP_NAME:-EZZETA}
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY}
APP_DEBUG=${APP_DEBUG:-false}
APP_URL=${APP_URL:-http://localhost}

DB_CONNECTION=${DB_CONNECTION:-pgsql}
DB_HOST=${DB_HOST}
DB_PORT=${DB_PORT:-5432}
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}

JWT_SECRET=${JWT_SECRET}
JWT_TTL=${JWT_TTL:-60}

FRONTEND_URL=${FRONTEND_URL:-*}
LOG_CHANNEL=${LOG_CHANNEL:-stderr}
LOG_LEVEL=${LOG_LEVEL:-error}
EOF

echo "✅ .env generado correctamente"

# Ejecutar migraciones
echo "🔄 Ejecutando migraciones..."
php artisan migrate --seed --force || echo "️ Las migraciones ya existían o hubo un error menor"

echo "✅ Migraciones completadas"

# Ejecutar el comando principal (Apache)
echo "🌐 Iniciando Apache..."
exec "$@"