<template>
    <div class="container">
        <loader :isLoading="isPending"></loader>
        <div class="row" v-if="photos.length">
            <div class="col py-1">
                <masonry :images="photos"></masonry>
            </div>
        </div>
        <div class="row" v-if="!isPending && !photos.length">
            <div class="col mt-3">
                <div class="alert alert-secondary">No photos found</div>
            </div>
        </div>
        <div class="row">
            <div class="col mt-2">
                <router-link
                        class="btn btn-secondary float-left"
                        v-if="isPreviousPageExists"
                        :to="{name: this.routeName, params: {page: this.previousPage}}"
                        title="Previous Page">Previous
                </router-link>
                <router-link
                        class="btn btn-secondary float-right"
                        v-if="isNextPageExists"
                        :to="{name: this.routeName, params: {page: this.nextPage}}"
                        title="Next Page">Next
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
    import Loader from "../layout/loader";
    import Masonry from "../gallery/masonry";
    import {GotoMixin, MetaMixin} from "../../mixins";

    export default {
        components: {
            Loader,
            Masonry,
        },
        mixins: [
            GotoMixin,
            MetaMixin,
        ],
        computed: {
            isPending: function () {
                return this.$store.getters["photoGallery/isPending"];
            },
            photos: function () {
                return this.$store.getters["photoGallery/getPhotos"];
            },
            currentPage: function () {
                return this.$store.getters["photoGallery/getCurrentPage"];
            },
            previousPage: function () {
                return this.$store.getters["photoGallery/getPreviousPage"];
            },
            nextPage: function () {
                return this.$store.getters["photoGallery/getNextPage"];
            },
            isPreviousPageExists: function () {
                return this.$store.getters["photoGallery/isPreviousPageExist"];
            },
            isNextPageExists: function () {
                return this.$store.getters["photoGallery/isNextPageExist"];
            },
            routeName: function () {
                const nameSuffix = "-with-page";
                return this.$route.name.endsWith(nameSuffix) ? this.$route.name : `${this.$route.name}${nameSuffix}`;
            },
            pageTitle: function () {
                if (this.$route.params.search_phrase) {
                    return `Search "${this.$route.params.search_phrase}"`;
                }
                if (this.$route.params.tag) {
                    return `Search by tag #${this.$route.params.tag}`;
                }
                return "All photos";
            },
        },
        watch: {
            "$route": function () {
                this.init();
            },
            photos: function () {
                if (this.currentPage > 1) {
                    this.$router.push({name: this.routeName, params: {page: this.currentPage}});
                }
            },
        },
        methods: {
            init: function () {
                if (this.$route.query.show) {
                    // @BC: Go to new photo page in order to preserve backward compatibility
                    // with an older version of the application.
                    this.goToPhotoPage(this.$route.query.show);
                } else {
                    this.loadPosts();
                }
            },
            loadPosts: function () {
                this.$store.dispatch("photoGallery/loadPhotos", {
                    per_page: 40,
                    page: this.$route.params.page,
                    tag: this.$route.params.tag,
                    search_phrase: this.$route.params.search_phrase,
                });
            },
        },
        created: function () {
            this.init();
        },
    }
</script>
