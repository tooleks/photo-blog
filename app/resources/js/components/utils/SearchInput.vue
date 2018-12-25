<template>
    <form @submit.prevent="debounceSearch" class="form-inline my-2 my-lg-0">
        <div class="input-group">
            <input @input="debounceSearch"
                   v-model.trim="input"
                   v-focus
                   class="form-control border-0"
                   type="search"
                   :aria-label="$lang('Search')"
                   :placeholder="$lang('Search...')"
                   required>
            <div class="input-group-append">
                <button @click="debounceSearch"
                        type="button"
                        class="btn btn-secondary border-0"
                        :title="$lang('Search')"
                        :aria-label="$lang('Search')">
                    <i class="fa fa-search" aria-hidden="true"></i>
                </button>
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
        methods: {
            search() {
                this.$services.getEventBus().emit("search", this.input);
            },
        },
        created() {
            this.debounceSearch = debounce(this.search, this.delay);
            this.$services
                .getEventBus()
                .on("search.init", (input) => {
                    this.input = input;
                })
                .on("search.clear", () => {
                    this.input = "";
                });
        },
        beforeDestroy() {
            this.$services
                .getEventBus()
                .off("search.init")
                .off("search.clear");
        },
    }
</script>
