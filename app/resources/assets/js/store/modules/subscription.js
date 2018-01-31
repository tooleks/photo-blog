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
        createSubscription: async function ({commit}, data) {
            try {
                commit("setPending", {pending: true});
                await api.createSubscription(data);
            } finally {
                commit("setPending", {pending: false});
            }
        },
        deleteSubscription: async function ({commit}, {token}) {
            try {
                commit("setPending", {pending: true});
                await api.deleteSubscription(token);
            } finally {
                commit("setPending", {pending: false});
            }
        },
    },
    mutations: {
        setPending(state, {pending}) {
            state.pending = pending;
        },
    },
}
