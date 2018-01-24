import {api, mapper} from "../../services";

const getDefaultPost = () => mapper.map(undefined, "*", "Api.V1.Post");
const getDefaultPending = () => false;

export default {
    namespaced: true,
    state: {
        post: getDefaultPost(),
        pending: getDefaultPending(),
    },
    getters: {
        isPending: function (state) {
            return state.pending;
        },
        getPhoto: function (state) {
            return mapper.map(state.post, "Api.V1.Post", "App.Photo");
        },
        getTags: function (state) {
            return state.post.tags.map((tag) => mapper.map(tag, "Api.V1.Tag", "App.Tag"));
        },
        getDescription: function (state) {
            return state.post.description;
        },
    },
    actions: {
        loadPhoto: function ({commit, getters}, {id}) {
            commit("setPending", {pending: true});
            return api
                .getPost(id, {}, {suppressNotFoundErrors: true})
                .then((response) => {
                    commit("setPending", {pending: false});
                    commit("setPost", {post: response.data});
                    return Promise.resolve(getters.getPhoto);
                })
                .catch((error) => {
                    commit("setPending", {pending: false});
                    return Promise.reject(error);
                });
        },
        uploadFile: function ({commit, getters}, {file}) {
            commit("setPending", {pending: true});
            return api
                .createPhoto(file)
                .then((response) => {
                    commit("setPending", {pending: false});
                    commit("setPhoto", {photo: response.data});
                    return Promise.resolve(getters.getPhoto);
                })
                .catch((error) => {
                    commit("setPending", {pending: false});
                    return Promise.reject(error);
                });
        },
        savePhoto: function ({commit, state, getters}) {
            const createPost = () => api.createPost(state.post);
            const updatePost = () => api.updatePost(state.post.id, state.post);
            const savePost = typeof state.post.id === "undefined" ? createPost : updatePost;
            commit("setPending", {pending: true});
            return savePost()
                .then((response) => {
                    commit("setPending", {pending: false});
                    commit("setPost", {post: response.data});
                    return Promise.resolve(getters.getPhoto);
                })
                .catch((error) => {
                    commit("setPending", {pending: false});
                    return Promise.reject(error);
                });
        },
        deletePhoto: function ({commit, state}) {
            commit("setPending", {pending: true});
            return api
                .deletePost(state.post.id)
                .then((response) => {
                    commit("setPending", {pending: false});
                    return Promise.resolve();
                })
                .catch((error) => {
                    commit("setPending", {pending: false});
                    return Promise.reject(error);
                });
        },
    },
    mutations: {
        reset(state) {
            state.post = getDefaultPost();
            state.pending = getDefaultPending();
        },
        setPending(state, {pending}) {
            state.pending = pending;
        },
        setPost(state, {post}) {
            state.post = post;
        },
        setPhoto(state, {photo}) {
            state.post.photo = photo;
        },
        setTags(state, {tags}) {
            state.post.tags = tags.map((value) => mapper.map(value, "App.Tag", "Api.V1.Tag"));
        },
        setDescription(state, {description}) {
            state.post.description = description;
        },
    },
}
