import Vue from "vue";
import Vuex from "vuex";
import meta from "./modules/meta"

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        meta,
    },
    strict: process.env.NODE_ENV !== "production",
});
