<template>
    <div id="container">
        <app-header class="app-header"/>
        <div class="app-router">
            <transition name="fade">
                <router-view :key="routeKey"/>
            </transition>
        </div>
        <notifications group="main"/>
        <app-footer/>
        <go-top-button :animate="true"
                       :speed="50"
                       :acceleration="2"
                       :scrollDistance="150"
                       :title="$lang('Go to top')">
            <i class="fa fa-arrow-up" aria-hidden="true"></i>
        </go-top-button>
    </div>
</template>

<style lang="scss" scoped>
    .app-router {
        padding-top: 0;
        padding-bottom: 100px;

        @media screen and (min-width: 768px) {
            padding-bottom: 80px;
        }
    }

    @media screen and (min-width: 992px) {
        .app-header {
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
        }

        .app-router {
            padding-top: 56px;
        }
    }
</style>

<script>
    import GoTopButton from "vue-go-top-button";
    import "vue-go-top-button/dist/lib/vue-go-top-button.min.css";
    import Header from "./layout/Header";
    import Footer from "./layout/Footer";

    export default {
        components: {
            GoTopButton,
            AppHeader: Header,
            AppFooter: Footer,
        },
        computed: {
            routeKey() {
                return this.$route.meta.transition !== false ? this.$route.fullPath : null;
            },
        },
        watch: {
            $route() {
                this.initSearchInput(this.$route.params.searchPhrase);
            },
        },
        methods: {
            initSearchInput(searchPhrase) {
                // Initialize search input value if search phrase is provided.
                if (searchPhrase) {
                    this.$services.getEventBus().emit("search.init", searchPhrase);
                }
                // Otherwise, clear search input value.
                else {
                    this.$services.getEventBus().emit("search.clear");
                }
            },
            onSearch(searchPhrase) {
                // Redirect to search photos page if search phrase is provided.
                if (searchPhrase) {
                    this.$router.push({name: "photos-search", params: {searchPhrase}});
                }
                // Otherwise, fallback to all photos page.
                else {
                    this.$router.push({name: "photos"});
                }
            },
        },
        created() {
            this.$nextTick(() => this.initSearchInput(this.$route.params.searchPhrase));
            this.$services.getEventBus().on("search", this.onSearch);
        },
        beforeDestroy() {
            this.$services.getEventBus().off("search", this.onSearch);
        },
    }
</script>
