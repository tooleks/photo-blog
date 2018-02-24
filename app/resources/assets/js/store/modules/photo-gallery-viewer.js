import {api, mapper} from "../../services";
import {optional} from "../../utils";

const getDefaultPhotos = () => [];
const getDefaultActivePhoto = () => undefined;
const getDefaultPending = () => false;

export default {
    namespaced: true,
    state: {
        photos: getDefaultPhotos(),
        activePhoto: getDefaultActivePhoto(),
        pending: getDefaultPending(),
    },
    getters: {
        isPending: function (state) {
            return state.pending;
        },
        getPhotos: function (state) {
            return state.photos;
        },
        getActivePhoto: function (state) {
            return state.activePhoto;
        },
    },
    actions: {
        loadPhoto: async function ({commit, getters}, {id, params}) {
            try {
                commit("setPending", {pending: true});
                const response = await api.getPost(id, params, {suppressNotFoundErrors: true});
                const photo = mapper.map(response.data, "Api.V1.Post", "App.Photo");
                commit("setPhoto", {photo});
                commit("setActivePhoto", {photo});
                return getters.getPhotos
            } finally {
                commit("setPending", {pending: false});
            }
        },
        loadOlderPhoto: async function ({commit, getters}, {params}) {
            try {
                commit("setPending", {pending: true});
                const response = await api.getPreviousPost(getters.getActivePhoto.id, params, {suppressNotFoundErrors: true});
                const photo = mapper.map(response.data, "Api.V1.Post", "App.Photo");
                commit("appendPhoto", {photo});
                return getters.getPhotos
            } catch (error) {
                if (optional(() => error.response.status) !== 404) {
                    throw error;
                }
            } finally {
                commit("setPending", {pending: false});
            }
        },
        loadNewerPhoto: async function ({commit, getters}, {params}) {
            try {
                commit("setPending", {pending: true});
                const response = await api.getNextPost(getters.getActivePhoto.id, params, {suppressNotFoundErrors: true});
                const photo = mapper.map(response.data, "Api.V1.Post", "App.Photo");
                commit("prependPhoto", {photo});
                return getters.getPhotos
            } catch (error) {
                if (optional(() => error.response.status) !== 404) {
                    throw error;
                }
            } finally {
                commit("setPending", {pending: false});
            }
        },
    },
    mutations: {
        reset(state) {
            state.photos = getDefaultPhotos();
            state.activePhoto = getDefaultActivePhoto();
            state.pending = getDefaultPending();
        },
        setPending(state, {pending}) {
            state.pending = pending;
        },
        setActivePhoto(state, {photo}) {
            state.activePhoto = photo;
        },
        setPhoto(state, {photo}) {
            state.photos = [photo];
        },
        appendPhoto(state, {photo}) {
            state.photos = [...state.photos, photo];
        },
        prependPhoto(state, {photo}) {
            state.photos = [photo, ...state.photos];
        },
    },
}
