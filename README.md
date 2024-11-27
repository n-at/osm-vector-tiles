# OpenStreetMap Vector Tiles

Пример подготовки векторных карт на основе OpenStreetMap.

Используется:

+ [systemed/tilemaker](https://github.com/systemed/tilemaker) для преобразования pbf в pmtiles
+ [maplibre/martin](https://github.com/maplibre/martin) в качестве сервера
+ [maplibre/maplibre-gl-js](https://github.com/maplibre/maplibre-gl-js) для отображения карты
+ [openmaptiles/fonts](https://github.com/openmaptiles/fonts) - шрифты
+ [openmaptiles/osm-bright-gl-style](https://github.com/openmaptiles/osm-bright-gl-style) - тема

## Подготовка к созданию карты

```bash
./prepare.sh
```

В скриптах скачивания контуров берегов может потребоваться удалить `--proto '=https' --tlsv1.3` (если скачивание не идет).

## Создание векторных карт

```bash
docker run -it --rm --pull always -v $(pwd)/prepare:/data -w /data \
    ghcr.io/systemed/tilemaker:master \
    /data/cyprus-latest.osm.pbf \
    --output /data/cyprus-latest.pmtiles \
    --store /data/store

# docker run -it --rm --pull always -v $(pwd)/prepare:/data -w /data \
#     ghcr.io/systemed/tilemaker:master \
#     /data/russia-latest.osm.pbf \
#     --output /data/russia-latest.pmtiles \
#     --store /data/store
```

Будут созданы файлы `.pmtiles`

## Подготовка сервера

```bash
./serve.sh
```

## Запуск сервера

```bash
cd serve
docker compose up -d
```

Открыть http://localhost:8080
