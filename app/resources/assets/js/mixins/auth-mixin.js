import {value} from "../utils";

export default {
    computed: {
        isAuthenticated: function () {
            return this.$store.getters["auth/isAuthenticated"];
        },
        username: function () {
            return value(() => this.$store.getters["auth/getUser"].name);
        },
    },
}
