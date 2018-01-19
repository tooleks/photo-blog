<template>
    <div>
        <loader :isLoading="isPending"></loader>
        <viewer v-if="activePhoto"
                :activeImage.sync="activePhoto"
                :images="photos"
                @onFirstImage="loadNewerPhoto"
                @onLastImage="loadOlderPhoto"
                @onExit="goToPhotosPage"></viewer>
        <div class="container" v-if="activePhoto">
            <div class="row">
                <div class="col">
                    <photo-description-card :photo="activePhoto" @onBack="goToPhotosPage"></photo-description-card>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Loader from "../layout/loader";
    import Viewer from "../gallery/viewer";
    import PhotoDescriptionCard from "./photo-description-card";
    import {GotoMixin, MetaMixin} from "../../mixins";
    import {value} from "../../utils";

    export default {
        components: {
            Loader,
            PhotoDescriptionCard,
            Viewer,
        },
        mixins: [
            GotoMixin,
            MetaMixin,
        ],
        computed: {
            isPending: function () {
                return this.$store.getters["photoGalleryViewer/isPending"];
            },
            photos: function () {
                return this.$store.getters["photoGalleryViewer/getPhotos"];
            },
            activePhoto: {
                get: function () {
                    return this.$store.getters["photoGalleryViewer/getActivePhoto"];
                },
                set: function (photo) {
                    this.$store.commit("photoGalleryViewer/setActivePhoto", {photo});
                },
            },
            pageTitle: function () {
                return value(() => this.activePhoto.description);
            },
            pageImage: function () {
                return value(() => this.activePhoto.original.url);
            },
        },
        watch: {
            "$route.params.id": function (id) {
                const photo = this.photos.find((photo) => photo.id === Number(id));
                if (typeof photo !== "undefined") {
                    this.activePhoto = photo;
                } else {
                    this.goTo404Page();
                }
            },
            activePhoto: function (activePhoto) {
                this.goToPhotoPage(activePhoto.id);
            },
        },
        methods: {
            init: function () {
                this.reset();
                this.loadPhoto(this.$route.params.id, this.$route.query);
            },
            reset: function () {
                this.$store.commit("photoGalleryViewer/reset");
            },
            loadPhoto: function () {
                this.$store
                    .dispatch("photoGalleryViewer/loadPhoto", {
                        id: this.$route.params.id,
                        params: this.$route.query
                    })
                    .catch((error) => {
                        if (value(() => error.response.status) === 404) {
                            this.goTo404Page();
                        } else {
                            this.goToHomePage();
                        }
                    });
            },
            loadOlderPhoto: function () {
                this.$store.dispatch("photoGalleryViewer/loadOlderPhoto", {params: this.$route.query});
            },
            loadNewerPhoto: function () {
                this.$store.dispatch("photoGalleryViewer/loadNewerPhoto", {params: this.$route.query});
            },
        },
        created: function () {
            this.init();
        },
        beforeDestroy: function () {
            this.reset();
        },
    }
</script>
