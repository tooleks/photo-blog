<template>
    <div id="container">
        <app-header class="app-header"></app-header>
        <div class="app-router">
            <router-view></router-view>
        </div>
        <notifications group="main"></notifications>
        <app-footer></app-footer>
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
    import {MetaMixin} from "../mixins";

    export default {
        mixins: [
            MetaMixin,
        ],
    }
</script>
