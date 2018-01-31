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
        loadPhoto: async function ({commit, getters}, {id}) {
            try {
                commit("setPending", {pending: true});
                const response = await api.getPost(id, {}, {suppressNotFoundErrors: true});
                commit("setPost", {post: response.data});
                return getters.getPhoto;
            } finally {
                commit("setPending", {pending: false});
            }
        },
        uploadFile: async function ({commit, getters}, {file}) {
            try {
                commit("setPending", {pending: true});
                const response = await api.createPhoto(file);
                commit("setPhoto", {photo: response.data});
                return getters.getPhoto;
            } finally {
                commit("setPending", {pending: false});
            }
        },
        savePhoto: async function ({commit, state, getters}) {
            const createPost = () => api.createPost(state.post);
            const updatePost = () => api.updatePost(state.post.id, state.post);
            const savePost = typeof state.post.id === "undefined" ? createPost : updatePost;
            try {
                commit("setPending", {pending: true});
                const response = await savePost();
                commit("setPost", {post: response.data});
                return getters.getPhoto;
            } finally {
                commit("setPending", {pending: false});
            }
        },
        deletePhoto: async function ({commit, state}) {
            try {
                commit("setPending", {pending: true});
                await api.deletePost(state.post.id);
            } finally {
                commit("setPending", {pending: false});
            }
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
