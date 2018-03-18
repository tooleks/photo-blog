<template>
    <div class="photo-map">
        <loader :loading="loading"></loader>
        <image-map :images="images"></image-map>
    </div>
</template>

<style scoped>
    .photo-map {
        display: block;

        width: 100%;
        height: 85vh;
    }
</style>

<script>
    import Loader from "../utils/loader";
    import ImageMap from "../map/image-map";
    import {MetaMixin} from "../../mixins";
    import {photoMapService} from "../../services";

    export default {
        components: {
            Loader,
            ImageMap,
        },
        mixins: [
            MetaMixin,
        ],
        data: function () {
            return {
                loading: false,
                images: [],
            };
        },
        computed: {
            pageTitle: function () {
                return "Map";
            },
        },
        methods: {
            init: function () {
                this.loadImages();
            },
            loadImages: async function () {
                this.loading = true;
                try {
                    this.images = await photoMapService.getImages();
                } finally {
                    this.loading = false;
                }
            },
        },
        created: function () {
            this.init();
        },
    }
</script>
