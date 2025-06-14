#!/bin/bash

# === НАСТРОЙКИ ===

SERVER_IP="217.198.13.171"
SERVER_USER="root"
SSH_KEY_PATH="$HOME/.ssh/id_rsa"   # Путь к твоему приватному ключу
DEPLOY_PATH="/srv/your-project"

# === ЗАПУСК ===

echo "Создаём папку letsencrypt на сервере..."

ssh -i "$SSH_KEY_PATH" ${SERVER_USER}@${SERVER_IP} << EOF
  mkdir -p ${DEPLOY_PATH}/letsencrypt
  touch ${DEPLOY_PATH}/letsencrypt/acme.json
  chmod 600 ${DEPLOY_PATH}/letsencrypt/acme.json
EOF

echo "✅ LetsEncrypt storage успешно подготовлен на сервере"
