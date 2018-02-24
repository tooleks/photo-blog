import {optional} from "../utils";

export default {
    computed: {
        isAuthenticated: function () {
            return this.$store.getters["auth/isAuthenticated"];
        },
        username: function () {
            return optional(() => this.$store.getters["auth/getUser"].name);
        },
    },
}
