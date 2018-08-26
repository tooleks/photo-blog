<script>
    import BasicMap from "./basic-map";
    import {provideImageMarkerHtml} from "./image-marker-provider";

    export default {
        extends: BasicMap,
        props: {
            images: {
                type: Array,
                default: [],
            },
        },
        data: function () {
            return {
                markerClusterGroup: null,
            };
        },
        watch: {
            images: function () {
                this.destroyMarkerClusterGroup();
                this.initMarkerClusterGroup();
                this.images.forEach((image) => this.renderImage(image));
            },
        },
        methods: {
            initMarkerClusterGroup: function () {
                this.markerClusterGroup = L.markerClusterGroup({
                    spiderLegPolylineOptions: {opacity: 0},
                });
                this.map.addLayer(this.markerClusterGroup);
            },
            destroyMarkerClusterGroup: function () {
                if (this.markerClusterGroup !== null) {
                    this.map.removeLayer(this.markerClusterGroup);
                    this.markerClusterGroup = null;
                }
            },
            renderImage: function (image) {
                const icon = L.divIcon({html: provideImageMarkerHtml(image)});
                const marker = L.marker(image.location, {icon, riseOnHover: true});
                this.markerClusterGroup.addLayer(marker);
            },
        },
        mounted: function () {
            this.initMarkerClusterGroup();
        },
    }
</script>
