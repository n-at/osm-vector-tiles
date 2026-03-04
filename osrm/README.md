# Подготовка и запуск сервиса построения маршрутов

> TODO в процессе переработки

+ [Project-OSRM/osrm-backend](https://github.com/Project-OSRM/osrm-backend) - построение маршрутов (BSD-2-Clause)

```bash
mkdir -m 0777 osrm

#Кипр (для тестирования)

mkdir -m 0777 osrm/cyprus-car osrm/cyprus-bicycle osrm/cyprus-foot
ln -s ../../prepare/cyprus-latest.osm.pbf osrm/cyprus-car/cyprus-latest.osm.pbf
ln -s ../../prepare/cyprus-latest.osm.pbf osrm/cyprus-bicycle/cyprus-latest.osm.pbf
ln -s ../../prepare/cyprus-latest.osm.pbf osrm/cyprus-foot/cyprus-latest.osm.pbf

docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-extract -p /opt/car.lua /data/osrm/cyprus-car/cyprus-latest.osm.pbf
docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-partition /data/osrm/cyprus-car/cyprus-latest.osrm
docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-customize /data/osrm/cyprus-car/cyprus-latest.osrm

docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-extract -p /opt/bicycle.lua /data/osrm/cyprus-bicycle/cyprus-latest.osm.pbf
docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-partition /data/osrm/cyprus-bicycle/cyprus-latest.osrm
docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-customize /data/osrm/cyprus-bicycle/cyprus-latest.osrm

docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-extract -p /opt/foot.lua /data/osrm/cyprus-foot/cyprus-latest.osm.pbf
docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-partition /data/osrm/cyprus-foot/cyprus-latest.osrm
docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-customize /data/osrm/cyprus-foot/cyprus-latest.osrm

#Россия

mkdir -m 0777 osrm/russia-car osrm/russia-bicycle osrm/russia-foot
ln -s ../../prepare/russia-latest.osm.pbf osrm/russia-car/russia-latest.osm.pbf
ln -s ../../prepare/russia-latest.osm.pbf osrm/russia-bicycle/russia-latest.osm.pbf
ln -s ../../prepare/russia-latest.osm.pbf osrm/russia-foot/russia-latest.osm.pbf

docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-extract -p /opt/car.lua /data/osrm/russia-car/russia-latest.osm.pbf
docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-partition /data/osrm/russia-car/russia-latest.osrm
docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-customize /data/osrm/russia-car/russia-latest.osrm

docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-extract -p /opt/bicycle.lua /data/osrm/russia-bicycle/russia-latest.osm.pbf
docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-partition /data/osrm/russia-bicycle/russia-latest.osrm
docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-customize /data/osrm/russia-bicycle/russia-latest.osrm

docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-extract -p /opt/foot.lua /data/osrm/russia-foot/russia-latest.osm.pbf
docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-partition /data/osrm/russia-foot/russia-latest.osrm
docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-customize /data/osrm/russia-foot/russia-latest.osrm

#Россия (СЗФО)

mkdir -m 0777 osrm/russia-szfo-car osrm/russia-szfo-bicycle osrm/russia-szfo-foot
ln -s ../../prepare/russia-szfo-latest.osm.pbf osrm/russia-szfo-car/russia-szfo-latest.osm.pbf
ln -s ../../prepare/russia-szfo-latest.osm.pbf osrm/russia-szfo-bicycle/russia-szfo-latest.osm.pbf
ln -s ../../prepare/russia-szfo-latest.osm.pbf osrm/russia-szfo-foot/russia-szfo-latest.osm.pbf

docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-extract -p /opt/car.lua /data/osrm/russia-szfo-car/russia-szfo-latest.osm.pbf
docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-partition /data/osrm/russia-szfo-car/russia-szfo-latest.osrm
docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-customize /data/osrm/russia-szfo-car/russia-szfo-latest.osrm

docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-extract -p /opt/bicycle.lua /data/osrm/russia-szfo-bicycle/russia-szfo-latest.osm.pbf
docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-partition /data/osrm/russia-szfo-bicycle/russia-szfo-latest.osrm
docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-customize /data/osrm/russia-szfo-bicycle/russia-szfo-latest.osrm

docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-extract -p /opt/foot.lua /data/osrm/russia-szfo-foot/russia-szfo-latest.osm.pbf
docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-partition /data/osrm/russia-szfo-foot/russia-szfo-latest.osrm
docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-customize /data/osrm/russia-szfo-foot/russia-szfo-latest.osrm

#Весь мир

mkdir -m 0777 osrm/planet-car osrm/planet-bicycle osrm/planet-foot
ln -s ../../prepare/planet.osm.pbf osrm/planet-car/planet.osm.pbf
ln -s ../../prepare/planet.osm.pbf osrm/planet-bicycle/planet.osm.pbf
ln -s ../../prepare/planet.osm.pbf osrm/planet-foot/planet.osm.pbf

docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-extract -p /opt/car.lua /data/osrm/planet-car/planet.osm.pbf
docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-partition /data/osrm/planet-car/planet.osrm
docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-customize /data/osrm/planet-car/planet.osrm

docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-extract -p /opt/bicycle.lua /data/osrm/planet-bicycle/planet.osm.pbf
docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-partition /data/osrm/planet-bicycle/planet.osrm
docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-customize /data/osrm/planet-bicycle/planet.osrm

docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-extract -p /opt/foot.lua /data/osrm/planet-foot/planet.osm.pbf
docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-partition /data/osrm/planet-foot/planet.osrm
docker run -it --rm -v $(pwd):/data \
  ghcr.io/project-osrm/osrm-backend \
  osrm-customize /data/osrm/planet-foot/planet.osrm

#Запуск

docker compose -f osrm-cyprus-docker-compose.yml up -d
docker compose -f osrm-russia-docker-compose.yml up -d
docker compose -f osrm-russia-szfo-docker-compose.yml up -d
docker compose -f osrm-planet-docker-compose.yml up -d
```
