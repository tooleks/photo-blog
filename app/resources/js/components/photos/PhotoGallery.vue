<template>
    <div class="container">
        <round-spinner :loading="loading"/>
        <div v-if="photos.length" class="row">
            <div class="col py-1">
                <gallery-masonry ref="masonry" :images="photos"/>
            </div>
        </div>
        <div v-if="!loading && !photos.length" class="row">
            <div class="col mt-3">
                <div class="alert alert-secondary">{{ $lang("No photos found") }}</div>
            </div>
        </div>
        <div v-if="previousPageExists || nextPageExists" class="row">
            <div class="col mt-2">
                <router-link
                        v-if="previousPageExists"
                        :to="{name: $route.name, params: {page: previousPage}}"
                        class="btn btn-secondary float-left"
                        :title="$lang('Previous Page')">{{ $lang("Previous") }}
                </router-link>
                <router-link
                        v-if="nextPageExists"
                        :to="{name: $route.name, params: {page: nextPage}}"
                        class="btn btn-secondary float-right"
                        :title="$lang('Next Page')">{{ $lang("Next") }}
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
    import waitUntil from "../../utils/waitUntil";
    import RoundSpinner from "../utils/RoundSpinner";
    import Masonry from "../gallery/Masonry";
    import {MetaMixin, RouteMixin} from "../../mixins";

    export default {
        components: {
            RoundSpinner,
            GalleryMasonry: Masonry,
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
                /** @type {number|null} */
                previousPage: null,
                /** @type {number} */
                currentPage: 1,
                /** @type {number|null} */
                nextPage: null,
                /** @type {boolean} */
                previousPageExists: false,
                /** @type {boolean} */
                nextPageExists: false,
            };
        },
        watch: {
            currentPage(currentPage) {
                if (currentPage > 1) {
                    this.$router.push({
                        name: this.$route.name,
                        params: {page: currentPage},
                        hash: this.$route.hash,
                    });
                }
            },
            async photos() {
                const masonry = await waitUntil(() => this.$refs.masonry);
                masonry.scrollToImage();
            },
        },
        methods: {
            setPhotos({items, previousPageExists, nextPageExists, currentPage, nextPage, previousPage}) {
                this.photos = items;
                this.previousPageExists = previousPageExists;
                this.nextPageExists = nextPageExists;
                this.currentPage = currentPage;
                this.nextPage = nextPage;
                this.previousPage = previousPage;
            },
            async loadPhotos(params = this.$route.params) {
                this.loading = true;
                try {
                    this.setPhotos(await this.$services.getPhotoManager().paginate(params));
                } catch (error) {
                    // The error is handled by the API service.
                    // No additional actions needed.
                } finally {
                    this.loading = false;
                }
            },
        },
        created() {
            this.loadPhotos();
            if (this.$route.params.searchPhrase) {
                this.setPageTitle(this.$lang("Search \"{phrase}\"", this.$route.params.searchPhrase));
            } else if (this.$route.params.tag) {
                this.setPageTitle(this.$lang("Tag #{tag}", this.$route.params.tag));
            } else {
                this.setPageTitle(this.$lang("All photos"));
            }
        },
    }
</script>
