<template>
    <div id="container">
        <app-header class="app-header"></app-header>
        <div class="app-router">
            <transition name="fade">
                <router-view :key="routeKey"></router-view>
            </transition>
        </div>
        <notifications group="main"></notifications>
        <app-footer></app-footer>
        <go-top-button :animate="true"
                       :speed="50"
                       :acceleration="2"
                       :scrollDistance="150"
                       title="Go to top">
            <i class="fa fa-arrow-up" aria-hidden="true"></i>
        </go-top-button>
    </div>
</template>

<style scoped>
    .app-router {
        padding-top: 0;
        padding-bottom: 50px;
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

    export default {
        components: {
            GoTopButton,
        },
        computed: {
            routeKey: function () {
                return this.$route.meta.transition !== false ? this.$route.fullPath : null;
            },
        },
    }
</script>
