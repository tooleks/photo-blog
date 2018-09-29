<template>
    <basic-map ref="map"
               :center="location"
               :zoom="zoom"></basic-map>
</template>

<script>
    import L from "leaflet";
    import BasicMap from "./BasicMap";
    import Location from "../../entities/Location";
    import {provideLocationPopupHtml} from "./locationPopupProvider";

    export default {
        components: {
            BasicMap,
        },
        props: {
            location: {
                type: Object,
                validator: function ({lat, lng}) {
                    try {
                        // Location constructor will throw an exception if invalid coordinates will be provided.
                        new Location({lat, lng});
                        return true;
                    } catch (error) {
                        return false;
                    }
                },
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
                return this.$refs.map.map;
            },
        },
        watch: {
            location: function (location) {
                this.resetMarker(location);
            },
        },
        methods: {
            init: function () {
                // Handle scroll events if the map is in a focus.
                this.map.scrollWheelZoom.disable();
                this.map.on("blur", (event) => event.target.scrollWheelZoom.disable());
                this.map.on("focus", (event) => event.target.scrollWheelZoom.enable());

                // Handle map click events if the component is not disabled.
                if (!this.disabled) {
                    this.map.on("click", (event) => this.resetMarker(event.latlng));
                }

                if (this.location) {
                    this.setMarker(this.location);
                }
            },
            setMarker: function (location) {
                this.marker = L.marker(location, {draggable: !this.disabled}).addTo(this.map);
                // Show marker popup.
                this.map.on("click", () => this.showMarkerPopup());
                this.showMarkerPopup();

                // Handle marker drag events if component is not disabled.
                if (!this.disabled) {
                    this.marker.on("dragend", (event) => this.resetMarker(event.target.getLatLng()));
                }

                this.$emit("update:location", location);
            },
            removeMarker: function () {
                if (this.marker !== null) {
                    this.map.removeLayer(this.marker);
                    this.marker = null;
                }
            },
            resetMarker: function (location) {
                this.removeMarker();
                if (location) {
                    this.setMarker(location);
                }
            },
            showMarkerPopup: async function () {
                if (this.marker !== null) {
                    const location = this.marker.getLatLng();
                    const html = await provideLocationPopupHtml({location});
                    this.marker.bindPopup(html).openPopup();
                }
            },
        },
        mounted: function () {
            this.init();
        },
    }
</script>
