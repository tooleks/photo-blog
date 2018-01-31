import {api, mapper} from "../../services";

const getDefaultPhotos = () => [];
const getDefaultCurrentPage = () => 1;
const getDefaultPreviousPage = () => null;
const getDefaultNextPage = () => null;
const getDefaultPreviousPageExists = () => false;
const getDefaultNextPageExists = () => false;
const getDefaultPending = () => false;

const calculatePreviousPage = (currentPage) => {
    if (!currentPage) return null;
    let previousPage = currentPage - 1;
    if (previousPage < 1) previousPage = 1;
    return previousPage;
};

const calculateNextPage = (currentPage) => {
    if (!currentPage) return null;
    let nextPage = currentPage + 1;
    return nextPage;
};

export default {
    namespaced: true,
    state: {
        photos: getDefaultPhotos(),
        currentPage: getDefaultCurrentPage(),
        previousPage: getDefaultPreviousPage(),
        nextPage: getDefaultNextPage(),
        previousPageExists: getDefaultPreviousPageExists(),
        nextPageExists: getDefaultNextPageExists(),
        pending: getDefaultPending(),
    },
    getters: {
        getPhotos: function (state) {
            return state.photos;
        },
        getCurrentPage: function (state) {
            return state.currentPage;
        },
        getPreviousPage: function (state) {
            return state.previousPage;
        },
        getNextPage: function (state) {
            return state.nextPage;
        },
        isPreviousPageExist: function (state) {
            return state.previousPageExists;
        },
        isNextPageExist: function (state) {
            return state.nextPageExists;
        },
        isPending: function (state) {
            return state.pending;
        },
    },
    actions: {
        loadPhotos: async function ({commit, getters}, data) {
            try {
                commit("setPending", {pending: true});
                const response = await api.getPosts(data);
                const photos = response.data.data.map((post) => mapper.map(post, "Api.V1.Post", "App.Photo"));
                commit("setPhotos", {
                    photos,
                    currentPage: response.data.current_page,
                    previousPageExists: Boolean(response.data.prev_page_url),
                    nextPageExists: Boolean(response.data.next_page_url),
                });
                return getters.getPhotos;
            } finally {
                commit("setPending", {pending: false});
            }
        },
    },
    mutations: {
        reset(state) {
            state.photos = getDefaultPhotos();
            state.currentPage = getDefaultCurrentPage();
            state.previousPage = getDefaultPreviousPage();
            state.nextPage = getDefaultNextPage();
            state.previousPageExists = getDefaultPreviousPageExists();
            state.nextPageExists = getDefaultNextPageExists();
            state.pending = getDefaultPending();
        },
        setPending(state, {pending}) {
            state.pending = pending;
        },
        setPhotos(state, {photos, currentPage, previousPageExists, nextPageExists}) {
            state.photos = photos;
            state.currentPage = currentPage;
            state.previousPage = calculatePreviousPage(currentPage);
            state.nextPage = calculateNextPage(currentPage);
            state.previousPageExists = Boolean(previousPageExists);
            state.nextPageExists = Boolean(nextPageExists);
        },
    },
}
