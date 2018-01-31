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
        createContactMessage: async function ({commit}, data) {
            try {
                commit("setPending", {pending: true});
                await api.createContactMessage(data);
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
