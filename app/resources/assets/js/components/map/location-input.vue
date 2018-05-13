<template>
    <basic-map ref="map" :location="location" :zoom="this.zoom"></basic-map>
</template>

<script>
    import L from "leaflet";
    import BasicMap from "./basic-map";
    import {provideLocationPopupHtml} from "./location-popup-provider";

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
            disabled: {
                type: Boolean,
                default: false,
            },
        },
        data: function () {
            return {
                marker: null,
            };
        },
        computed: {
            map: function () {
                return this.$refs.map.getMap();
            },
        },
        watch: {
            location: function (location) {
                if (location) {
                    this.updateMarkerPosition(location);
                }
            },
        },
        methods: {
            init: function () {
                // Handle map click events if component is not disabled.
                if (!this.disabled) {
                    this.map.on("click", (event) => this.updateMarkerPosition(event.latlng));
                }

                // Enable scroll wheel zooming only when map is in focus.
                this.map.scrollWheelZoom.disable();
                this.map.on("blur", (event) => event.target.scrollWheelZoom.disable());
                this.map.on("focus", (event) => event.target.scrollWheelZoom.enable());

                // Set marker if location is initialized.
                if (this.location) {
                    this.setMarker(this.location);
                }
            },
            updateMarkerPosition: function (location = {}) {
                this.setMarker(location);
                this.$emit("update:location", location);
            },
            setMarker: function (location = {}) {
                // If marker is already initialized remove it from the map.
                if (this.marker !== null) {
                    this.map.removeLayer(this.marker);
                }

                this.marker = L.marker(location, {draggable: !this.disabled}).addTo(this.$refs.map.getMap());
                // Show marker popup.
                this.map.on("click", () => this.showMarkerPopup());
                this.showMarkerPopup();

                // Handle marker drag events if component is not disabled.
                if (!this.disabled) {
                    this.marker.on("dragend", (event) => this.updateMarkerPosition(event.target.getLatLng()));
                }
            },
            showMarkerPopup: function () {
                if (this.marker !== null) {
                    const location = this.marker.getLatLng();
                    provideLocationPopupHtml({location}).then((html) => this.marker.bindPopup(html).openPopup());
                }
            },
        },
        mounted: function () {
            this.init();
        },
    }
</script>
