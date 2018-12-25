<template>
    <div class="container my-15 text-center">
        <div class="row">
            <div class="col">
                <h1>{{ $lang("404") }}
                    <small>{{ $lang("Page not found") }}</small>
                </h1>
                <span>{{ $lang("The link you followed may be broken, or the page may have been removed.") }} <router-link
                        :to="{name: 'home'}">{{ $lang("Go to the home page.") }}</router-link></span>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .my-15 {
        margin-top: 9rem !important;
    }
</style>

<script>
    import {MetaMixin} from "../../mixins";

    export default {
        mixins: [
            MetaMixin,
        ],
        created() {
            this.setPageStatusCode(404);
            this.setPageTitle(this.$lang("Page not found"));
        },
    }
</script>
