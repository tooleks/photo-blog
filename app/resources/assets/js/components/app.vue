<template>
    <div id="container">
        <app-header class="app-header"></app-header>
        <div class="app-router">
            <transition name="fade">
                <router-view></router-view>
            </transition>
        </div>
        <notifications group="main"></notifications>
        <app-footer></app-footer>
    </div>
</template>

<style scoped>
    .fade-enter-active, .fade-leave-active {
        transition-property: opacity;
        transition-duration: .2s;
    }

    .fade-enter-active {
        transition-delay: .2s;
    }

    .fade-enter, .fade-leave-active {
        opacity: 0
    }

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
    import {MetaMixin} from "../mixins";

    export default {
        mixins: [
            MetaMixin,
        ],
    }
</script>
