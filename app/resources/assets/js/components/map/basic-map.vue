<template>
    <div :id="this.id" class="map"></div>
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
    delete L.Icon.Default.prototype._getIconUrl;
    L.Icon.Default.mergeOptions({
        iconRetinaUrl: require("leaflet/dist/images/marker-icon-2x.png"),
        iconUrl: require("leaflet/dist/images/marker-icon.png"),
        shadowUrl: require("leaflet/dist/images/marker-shadow.png"),
    });

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
            lat: {
                type: Number,
                default: 49.85,
            },
            lng: {
                type: Number,
                default: 24.0166666667,
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
            lat: function () {
                this.map.panTo([this.lat, this.lng]);
            },
            lng: function () {
                this.map.panTo([this.lat, this.lng]);
            },
            zoom: function () {
                this.map.setView([this.lat, this.lng], this.zoom);
            },
        },
        methods: {
            init: function () {
                this.map = L.map(this.id).setView([this.lat, this.lng], this.zoom);
                this.tileLayer = L.tileLayer(this.tileLayerUrl, this.tileLayerOptions).addTo(this.map);
            },
            destroy: function () {
                this.map.remove();
            },
            getMap: function () {
                return this.map;
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
