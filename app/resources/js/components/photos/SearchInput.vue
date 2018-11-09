<template>
    <form @submit.prevent="debounceSearch" class="form-inline my-2 my-lg-0">
        <div class="input-group">
            <input @input="debounceSearch"
                   v-model.trim="input"
                   v-focus
                   class="form-control border-0"
                   type="search"
                   aria-label="Search"
                   placeholder="Search photos"
                   required>
            <div class="input-group-append">
                <button @click="debounceSearch"
                        type="button"
                        class="btn btn-secondary border-0"
                        aria-label="Search"
                        title="Search"><i class="fa fa-search" aria-hidden="true"></i></button>
            </div>
        </div>
    </form>
</template>

<script>
    import {debounce} from "lodash";

    export default {
        props: {
            delay: {
                type: Number,
                default: 500,
            },
        },
        data() {
            return {
                /** @type {string} */
                input: "",
            };
        },
        watch: {
            ["$route"]() {
                this.init();
            },
        },
        methods: {
            init() {
                this.input = this.$route.params.searchPhrase || this.$route.query.searchPhrase || "";
            },
            search() {
                this.input
                    ? this.$router.push({name: "photos-search", params: {searchPhrase: this.input}})
                    : this.$router.push({name: "photos"});
            },
        },
        created() {
            this.debounceSearch = debounce(() => this.search(), this.delay);
            this.init();
        },
    }
</script>
