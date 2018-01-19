<template>
    <form class="form-inline my-2 my-lg-0" @submit.prevent="debounceSearch">
        <div class="input-group">
            <input class="form-control mr-sm-2"
                   type="search"
                   aria-label="Search"
                   placeholder="Search photos"
                   required
                   @input="debounceSearch"
                   v-model.trim="input">
        </div>
    </form>
</template>

<script>
    export default {
        props: {
            delay: {type: Number, default: 500},
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
