<template>
    <div :id="id" class="map"></div>
</template>

<style scoped>
    .map {
        display: block;
        width: 100%;
        height: 100%;
        min-width: 200px;
        min-height: 350px;
    }
</style>

<script>
    import L from "leaflet";
    import "leaflet/dist/leaflet.css";
    import "leaflet.markercluster";
    import "leaflet.markercluster/dist/MarkerCluster.Default.css";

    // Workaround for issue: https://github.com/Leaflet/Leaflet/issues/4968#issuecomment-269750768
    import iconRetinaUrl from "leaflet/dist/images/marker-icon-2x.png";
    import iconUrl from "leaflet/dist/images/marker-icon.png";
    import shadowUrl from "leaflet/dist/images/marker-shadow.png";

    delete L.Icon.Default.prototype._getIconUrl;
    L.Icon.Default.mergeOptions({iconRetinaUrl, iconUrl, shadowUrl});

    export default {
        props: {
            id: {
                type: String,
                default: () => {
                    const id = Math.random().toString(36).substr(2, 5);
                    return `map-${id}`;
                },
            },
            tileLayerUrl: {
                type: String,
                default: "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
            },
            tileLayerOptions: {
                type: Object,
                default: () => {
                    return {
                        maxZoom: 18,
                        attribution: `&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>`,
                    };
                },
            },
            location: {
                type: Object,
                validator: function ({lat, lng}) {
                    const isValidLat = lat >= -90 && lat <= 90;
                    const isValidLng = lng >= -180 && lng <= 180;
                    return isValidLat && isValidLng;
                },
                default: function () {
                    // Lviv, Ukraine.
                    return {
                        lat: 49.85,
                        lng: 24.0166666667,
                    };
                },
            },
            zoom: {
                type: Number,
                default: 8,
            },
        },
        data: function () {
            return {
                map: null,
                tileLayer: null,
            };
        },
        watch: {
            location: function (location) {
                this.map.panTo(location);
            },
            zoom: function (zoom) {
                this.map.setView(this.map.getCenter(), zoom);
            },
        },
        methods: {
            init: function () {
                this.map = L.map(this.id).setView(this.location, this.zoom);
                this.tileLayer = L.tileLayer(this.tileLayerUrl, this.tileLayerOptions).addTo(this.map);
                this.map.on("zoomend", this.onMapEvent);
                this.map.on("moveend", this.onMapEvent);
            },
            destroy: function () {
                this.map.remove();
                this.map = null;
                this.tileLayer = null;
            },
            onMapEvent: function (event) {
                this.$emit("update:location", event.target.getCenter());
                this.$emit("update:zoom", event.target.getZoom());
                this.$emit("bounds", {
                    southWest: event.target.getBounds().getSouthWest(),
                    northEast: event.target.getBounds().getNorthEast(),
                });
            },
        },
        mounted: function () {
            this.init();
        },
        beforeDestroy: function () {
            this.destroy();
        },
    }
</script>
