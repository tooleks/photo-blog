<template>
    <div>
        <loader :loading="loading"></loader>
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
    import {optional as opt} from "tooleks";
    import Loader from "../utils/loader";
    import Viewer from "../gallery/viewer";
    import PhotoDescriptionCard from "./photo-description-card";
    import {GotoMixin, MetaMixin} from "../../mixins";

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
        data: function () {
            return {
                loading: false,
                photos: [],
                activePhoto: undefined,
            };
        },
        computed: {
            pageTitle: function () {
                return opt(() => this.activePhoto.description, "");
            },
            pageImage: function () {
                return opt(() => this.activePhoto.original.url, "");
            },
        },
        watch: {
            "$route.params.id": function (id) {
                const photo = this.photos.find((photo) => photo.id === Number(id));
                if (typeof photo !== "undefined") {
                    this.activePhoto = photo;
                } else {
                    this.goToNotFoundPage();
                }
            },
            activePhoto: function (activePhoto) {
                this.goToPhotoPage(activePhoto.id);
            },
        },
        methods: {
            init: function () {
                this.loadPhoto();
            },
            loadPhoto: async function () {
                this.loading = true;
                try {
                    const {data: post} = await this.$dc.get("api").getPost(this.$route.params.id, this.$route.query, {suppressNotFoundErrors: true});
                    const photo = this.$dc.get("mapper").map(post, "Api.Post", "Photo");
                    this.photos = [photo];
                    this.activePhoto = photo;
                } catch (error) {
                    if (opt(() => error.response.status) === 404) {
                        this.goToNotFoundPage();
                    } else {
                        throw error;
                    }
                } finally {
                    this.loading = false;
                }
            },
            loadOlderPhoto: async function () {
                this.loading = true;
                try {
                    const {data: post} = await this.$dc.get("api").getPreviousPost(this.activePhoto.id, this.$route.query, {suppressNotFoundErrors: true});
                    const photo = this.$dc.get("mapper").map(post, "Api.Post", "Photo");
                    this.photos = [...this.photos, photo];
                } finally {
                    this.loading = false;
                }
            },
            loadNewerPhoto: async function () {
                this.loading = true;
                try {
                    const {data: post} = await this.$dc.get("api").getNextPost(this.activePhoto.id, this.$route.query, {suppressNotFoundErrors: true});
                    const photo = this.$dc.get("mapper").map(post, "Api.Post", "Photo");
                    this.photos = [photo, ...this.photos];
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
