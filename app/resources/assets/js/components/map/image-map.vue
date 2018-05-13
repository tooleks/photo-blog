<template>
    <basic-map ref="map" :location="location" :zoom="zoom"></basic-map>
</template>

<script>
    import BasicMap from "./basic-map";
    import {provideImageMarkerHtml} from "./image-marker-provider";

    export default {
        components: {
            BasicMap,
        },
        props: {
            location: {
                type: Object,
            },
            zoom: {
                type: Number,
            },
            images: {
                type: Array,
                default: () => {
                    return [];
                },
            },
        },
        data: function () {
            return {
                markerClusterGroup: null,
            };
        },
        computed: {
            map: function () {
                return this.$refs.map.getMap();
            },
        },
        watch: {
            images: function () {
                this.destroyMarkerClusterGroup();
                this.initMarkerClusterGroup();
                this.images.forEach((image) => this.renderImage(image));
            },
        },
        methods: {
            init: function () {
                this.map.on("moveend", (event) => {
                    const southWest = event.target.getBounds().getSouthWest();
                    const northEast = event.target.getBounds().getNorthEast();
                    this.$emit("positionChange", {southWest, northEast});
                });
                this.map.on("zoomend", (event) => this.$emit("update:zoom", event.target.getZoom()));
                this.initMarkerClusterGroup();
            },
            initMarkerClusterGroup: function () {
                this.markerClusterGroup = L.markerClusterGroup({
                    spiderLegPolylineOptions: {opacity: 0},
                });
                this.map.addLayer(this.markerClusterGroup);
            },
            destroyMarkerClusterGroup: function () {
                if (this.markerClusterGroup !== null) {
                    this.map.removeLayer(this.markerClusterGroup);
                }
            },
            renderImage: function (image) {
                const icon = L.divIcon({html: provideImageMarkerHtml(image)});
                const marker = L.marker(image.location, {icon, riseOnHover: true});
                this.markerClusterGroup.addLayer(marker);
            },
        },
        mounted: function () {
            this.init();
        },
    }
</script>
