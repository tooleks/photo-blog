<template>
    <div>
        <round-spinner :loading="loading"/>
        <gallery-viewer v-if="currentPhoto"
                        :currentImage.sync="currentPhoto"
                        :images="photos"
                        @onFirstImage="loadNewerPhoto"
                        @onLastImage="loadOlderPhoto"
                        @onExit="goToBackUri"/>
        <div class="container" v-if="currentPhoto">
            <div class="row">
                <div class="col">
                    <photo-description-card :photo="currentPhoto" @onBack="goToBackUri"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {optional as opt} from "tooleks";
    import RoundSpinner from "../utils/RoundSpinner";
    import Viewer from "../gallery/Viewer";
    import PhotoDescriptionCard from "./PhotoDescriptionCard";
    import {MetaMixin, RouteMixin} from "../../mixins";

    export default {
        components: {
            RoundSpinner,
            PhotoDescriptionCard,
            GalleryViewer: Viewer,
        },
        mixins: [
            RouteMixin,
            MetaMixin,
        ],
        data() {
            return {
                /** @type {boolean} */
                loading: false,
                /** @type {Array<Photo>} */
                photos: [],
                /** @type {Photo} */
                currentPhoto: null,
            };
        },
        watch: {
            ["$route.params.id"](id) {
                const photo = this.photos.find((photo) => photo.postId === Number(id));
                if (photo) {
                    this.currentPhoto = photo;
                    return;
                }
                this.goToNotFoundPage();
            },
            currentPhoto(currentPhoto, previousPhoto) {
                if (previousPhoto) {
                    this.goToPhotoPage(currentPhoto.postId);
                }
            },
        },
        methods: {
            async loadPhoto() {
                this.loading = true;
                try {
                    const photo = await this.$services.getPhotoManager().getByPostId(
                        this.$route.params.id,
                        this.$route.query,
                    );
                    this.photos = [photo];
                    this.currentPhoto = photo;
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
            async loadOlderPhoto() {
                this.loading = true;
                try {
                    const photo = await this.$services.getPhotoManager().getPreviousByPostId(
                        this.currentPhoto.postId,
                        this.$route.query,
                    );
                    this.photos = [...this.photos, photo];
                } catch (error) {
                    // The error is handled by the API service.
                    // No additional actions needed.
                } finally {
                    this.loading = false;
                }
            },
            async loadNewerPhoto() {
                this.loading = true;
                try {
                    const photo = await this.$services.getPhotoManager().getNextByPostId(
                        this.currentPhoto.postId,
                        this.$route.query,
                    );
                    this.photos = [photo, ...this.photos];
                } catch (error) {
                    // The error is handled by the API service.
                    // No additional actions needed.
                } finally {
                    this.loading = false;
                }
            },
        },
        async created() {
            await this.loadPhoto();
            this.setPageTitle(this.currentPhoto.description);
            this.setPageImage(this.currentPhoto.image.url);
        },
    }
</script>
