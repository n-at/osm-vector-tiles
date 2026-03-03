# Создание БД и запуск сервиса геокодирования

+ [osm-search/Nominatim](https://github.com/osm-search/Nominatim) - геокодирование (GPL-3.0)

```bash
mkdir -m 0777 nominatim

wget -O "nominatim/wikimedia-importance.sql.gz" "https://nominatim.org/data/wikimedia-importance.sql.gz"
wget -O "nominatim/secondary_importance.sql.gz" "https://nominatim.org/data/wikimedia-secondary-importance.sql.gz"

#Кипр (для тестирования)
mkdir -m 0777 nominatim/cyprus-flatnode nominatim/cyprus-data
docker compose -f nominatim-cyprus-docker-compose.yml up -d

#Россия
mkdir -m 0777 nominatim/russia-flatnode nominatim/russia-data
docker compose -f nominatim-russia-docker-compose.yml up -d

#Россия (СЗФО)
mkdir -m 0777 nominatim/russia-szfo-flatnode nominatim/russia-szfo-data
docker compose -f nominatim-russia-szfo-docker-compose.yml up -d

#Весь мир
mkdir -m 0777 nominatim/planet-flatnode nominatim/planet-data
docker compose -f nominatim-planet-docker-compose.yml up -d
```
