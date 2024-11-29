# OpenStreetMap Vector Tiles

Пример подготовки векторных карт на основе OpenStreetMap.

Используется:

+ [onthegomap/planetiler](https://github.com/onthegomap/planetiler) для преобразования osm.pbf в pmtiles (Apache-2.0)
+ [maplibre/martin](https://github.com/maplibre/martin) в качестве сервера (Apache-2.0)
+ [maplibre/maplibre-gl-js](https://github.com/maplibre/maplibre-gl-js) для отображения карты (BSD-3-Clause)
+ [openmaptiles/fonts](https://github.com/openmaptiles/fonts) - шрифты (OFL, Apache-2.0)
+ [openmaptiles/osm-bright-gl-style](https://github.com/openmaptiles/osm-bright-gl-style) - тема и иконки (BSD-3-Clause, CC-BY 4.0)
+ [gravitystorm/openstreetmap-carto](https://github.com/gravitystorm/openstreetmap-carto) - иконки оригинальной темы OSM Carto (CC0)

## Подготовка к созданию карты

Скачивание файлов с данными OSM и planetiler.

```bash
mkdir -m 0777 prepare

cd prepare

wget https://github.com/onthegomap/planetiler/releases/latest/download/planetiler.jar

#Кипр (для тестирования)
wget "https://download.geofabrik.de/europe/cyprus-latest.osm.pbf"

#Россия (на geofabrik.de границы до 2022 года)
wget "https://download.geofabrik.de/russia-latest.osm.pbf"

#Весь мир
wget https://planet.openstreetmap.org/pbf/planet-latest.osm.pbf
```

## Создание векторных карт

```bash
#Кипр (для тестирования), ~10 минут
docker run -it --rm -v $(pwd)/prepare:/data -w /data openjdk:21 \
    java -Xmx1g -jar planetiler.jar --download \
    --fetch-wikidata \
    --nodemap-type=array --storage=ram \
    --output cyprus-latest.pmtiles \
    --osm-path cyprus-latest.osm.pbf 

#Россия, ~1 час
docker run -it --rm -v $(pwd)/prepare:/data -w /data openjdk:21 \
    java -Xmx4g -jar planetiler.jar \
    --download --fetch-wikidata \
    --nodemap-type=array --storage=ram \
    --output russia-latest.pmtiles \
    --osm-path russia-latest.osm.pbf 

#Весь мир
docker run -it --rm -v $(pwd)/prepare:/data -w /data openjdk:21 \
    java -Xmx40g -jar planetiler.jar \
    --download --fetch-wikidata \
    --nodemap-type=array --storage=mmap \
    --output planet.pmtiles \
    --osm-path planet.osm.pbf 
```

Будут созданы файлы `.pmtiles`

## Подготовка сервера

```bash
cd serve

mkdir -m 0777 nginx_cache nginx_logs tiles

git clone "https://github.com/openmaptiles/fonts" fonts

#Кипр (для тестирования)
mv ../prepare/cyprus-latest.pmtiles tiles/

#Россия
mv ../prepare/russia-latest.pmtiles tiles/

#Весь мир
mv ../prepare/planet.pmtiles tiles/

cd static

npm install

cd ../..
```

## Запуск сервера

```bash
cd serve
docker compose up -d
```

Открыть http://localhost:8080/
