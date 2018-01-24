import {mapper} from "../../services";

export default {
    namespaced: true,
    state: {
        values: {},
        vueMetaInfo: {},
    },
    getters: {
        getTitle: function (state) {
            return state.values.title;
        },
        getDescription: function (state) {
            return state.values.description;
        },
        getVueMetaInfo: function (state) {
            return state.vueMetaInfo;
        },
    },
    mutations: {
        setName(state, {name}) {
            state.values.name = name;
            state.vueMetaInfo = mapper.map(state.values, "App.Meta", "Vue.MetaInfo");
        },
        setDescription(state, {description}) {
            state.values.description = description;
            state.vueMetaInfo = mapper.map(state.values, "App.Meta", "Vue.MetaInfo");
        },
        setKeywords(state, {keywords}) {
            state.values.keywords = keywords;
            state.vueMetaInfo = mapper.map(state.values, "App.Meta", "Vue.MetaInfo");
        },
        setTitle(state, {title}) {
            state.values.title = title;
            state.vueMetaInfo = mapper.map(state.values, "App.Meta", "Vue.MetaInfo");
        },
        setImage(state, {image}) {
            state.values.image = image;
            state.vueMetaInfo = mapper.map(state.values, "App.Meta", "Vue.MetaInfo");
        },
    },
}
