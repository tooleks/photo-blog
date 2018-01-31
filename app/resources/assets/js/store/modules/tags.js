import {api, mapper} from "../../services";

export default {
    namespaced: true,
    state: {
        tags: [],
    },
    getters: {
        getTags: function (state) {
            return state.tags;
        },
    },
    actions: {
        loadTags: async function ({commit, getters}, data) {
            const response = await api.getTags(data);
            const tags = response.data.data.map((tag) => mapper.map(tag, "Api.V1.Tag", "App.Tag"));
            commit("setTags", {tags});
        },
    },
    mutations: {
        setTags(state, {tags}) {
            state.tags = tags;
        },
    },
}
