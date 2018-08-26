<script>
    import BasicMap from "./BasicMap";
    import {provideImageMarkerHtml} from "./imageMarkerProvider";

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
                markerCluster: null,
            };
        },
        watch: {
            images: function () {
                this.destroyMarkerCluster();
                this.initMarkerCluster();
                this.images.forEach((image) => this.renderImage(image));
            },
        },
        methods: {
            initMarkerCluster: function () {
                this.markerCluster = L.markerClusterGroup({
                    spiderLegPolylineOptions: {opacity: 0},
                });
                this.map.addLayer(this.markerCluster);
            },
            destroyMarkerCluster: function () {
                if (this.markerCluster !== null) {
                    this.map.removeLayer(this.markerCluster);
                    this.markerCluster = null;
                }
            },
            renderImage: function (image) {
                const icon = L.divIcon({html: provideImageMarkerHtml(image)});
                const marker = L.marker(image.location, {icon, riseOnHover: true});
                this.markerCluster.addLayer(marker);
            },
        },
        mounted: function () {
            this.initMarkerCluster();
        },
    }
</script>
