import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

import * as modules from "./modules";

const store = new Vuex.Store({modules});

export default store;
