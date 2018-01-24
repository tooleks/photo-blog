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
        loadTags: function ({commit, getters}, data) {
            return api
                .getTags(data)
                .then((response) => {
                    const tags = response.data.data.map((tag) => mapper.map(tag, "Api.V1.Tag", "App.Tag"));
                    commit("setTags", {tags});
                    return Promise.resolve(getters.getTags);
                });
        },
    },
    mutations: {
        setTags(state, {tags}) {
            state.tags = tags;
        },
    },
}
