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
    export default {
        props: {
            delay: {
                type: Number,
                default: 500,
            },
        },
        data: function () {
            return {
                input: "",
            };
        },
        watch: {
            "$route": function () {
                this.init();
            },
        },
        methods: {
            init: function () {
                this.input = this.$route.params.search_phrase || this.$route.query.search_phrase;
            },
            search: function () {
                this.input
                    ? this.$router.push({name: "photos-search", params: {search_phrase: this.input}})
                    : this.$router.push({name: "photos"});
            },
        },
        computed: {
            debounceSearch: function () {
                return _.debounce(() => this.search(), this.delay);
            },
        },
        created: function () {
            this.init();
        },
    }
</script>
