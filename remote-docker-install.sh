#!/bin/bash

# === НАСТРОЙКИ ===

SERVER_IP="217.198.13.171"
SERVER_USER="root"
SSH_KEY_PATH="$HOME/.ssh/id_rsa"  # путь к приватному ключу

# === КОНЕЦ НАСТРОЕК ===

# === ШАГ 1: СОЗДАЁМ ЛОКАЛЬНЫЙ ФАЙЛ ===

cat > install-docker.sh << 'EOF'
#!/bin/bash

set -e

echo "Обновляем пакеты..."
apt update && apt upgrade -y

echo "Удаляем старые версии Docker (если есть)..."
apt remove docker docker-engine docker.io containerd runc -y || true

echo "Устанавливаем зависимости..."
apt install ca-certificates curl gnupg lsb-release -y

echo "Добавляем официальный GPG ключ Docker..."
mkdir -p /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | gpg --dearmor -o /etc/apt/keyrings/docker.gpg

echo "Добавляем репозиторий Docker..."
echo \
  "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] \
  https://download.docker.com/linux/ubuntu \
  $(lsb_release -cs) stable" | \
  tee /etc/apt/sources.list.d/docker.list > /dev/null

echo "Обновляем список пакетов..."
apt update

echo "Устанавливаем Docker и Compose плагин..."
apt install docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin -y

echo "Готово. Docker и Docker Compose установлены."
EOF

chmod +x install-docker.sh

# === ШАГ 2: КОПИРУЕМ НА СЕРВЕР ===

scp -i "$SSH_KEY_PATH" install-docker.sh ${SERVER_USER}@${SERVER_IP}:/root/install-docker.sh

# === ШАГ 3: ЗАПУСКАЕМ НА СЕРВЕРЕ ===

ssh -i "$SSH_KEY_PATH" ${SERVER_USER}@${SERVER_IP} "/root/install-docker.sh"

echo "✅ Готово!"