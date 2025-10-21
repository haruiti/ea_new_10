#!/bin/bash
# Script de deploy automático Laravel - Yamato Hipnose Clínica

cd /home/seu_usuario/domains/yamatohipnoseclinica.com.br/public_html/offices/ea_new_10

echo "🧠 Limpando cache e atualizando estrutura..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear

echo "📦 Executando migrations..."
php artisan migrate --force

echo "✅ Deploy concluído com sucesso!"
