import {api, mapper} from "../../services";

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
        loadPhoto: function ({commit, getters}, {id, params}) {
            commit("setPending", {pending: true});
            return api
                .getPost(id, params, {suppressNotFoundErrors: true})
                .then((response) => {
                    const photo = mapper.map(response.data, "Api.V1.Post", "App.Photo");
                    commit("setPhoto", {photo});
                    commit("setActivePhoto", {photo});
                    commit("setPending", {pending: false});
                    return Promise.resolve(getters.getPhotos);
                })
                .catch((error) => {
                    commit("setPending", {pending: false});
                    return Promise.reject(error);
                });
        },
        loadOlderPhoto: function ({commit, getters}, {params}) {
            commit("setPending", {pending: true});
            return api
                .getPreviousPost(getters.getActivePhoto.id, params, {suppressNotFoundErrors: true})
                .then((response) => {
                    const photo = mapper.map(response.data, "Api.V1.Post", "App.Photo");
                    commit("appendPhoto", {photo});
                    commit("setPending", {pending: false});
                    return Promise.resolve(getters.getPhotos);
                })
                .catch((error) => {
                    commit("setPending", {pending: false});
                    return Promise.reject(error);
                });
        },
        loadNewerPhoto: function ({commit, getters}, {params}) {
            commit("setPending", {pending: true});
            return api
                .getNextPost(getters.getActivePhoto.id, params, {suppressNotFoundErrors: true})
                .then((response) => {
                    const photo = mapper.map(response.data, "Api.V1.Post", "App.Photo");
                    commit("prependPhoto", {photo});
                    commit("setPending", {pending: false});
                    return Promise.resolve(getters.getPhotos);
                })
                .catch((error) => {
                    commit("setPending", {pending: false});
                    return Promise.reject(error);
                });
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
