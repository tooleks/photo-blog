import {api} from "../../services";

export default {
    namespaced: true,
    state: {
        pending: false,
    },
    getters: {
        isPending: function (state) {
            return state.pending;
        },
    },
    actions: {
        createSubscription: function ({commit}, data) {
            commit("setPending", {pending: true});
            return api
                .createSubscription(data)
                .then((response) => {
                    commit("setPending", {pending: false});
                    return Promise.resolve();
                })
                .catch((error) => {
                    commit("setPending", {pending: false});
                    return Promise.reject(error);
                });
        },
        deleteSubscription: function ({commit}, {token}) {
            commit("setPending", {pending: true});
            return api
                .deleteSubscription(token)
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
        setPending(state, {pending}) {
            state.pending = pending;
        },
    },
}
