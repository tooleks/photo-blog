const state = {
    user: null,
    authenticated: false,
};

const mutations = {
    setUser(state, user) {
        state.user = user;
        state.authenticated = true;
    },
    removeUser(state) {
        state.user = null;
        state.authenticated = false;
    },
};

const actions = {
    setUser({commit}, pageStatusCode) {
        commit("setUser", pageStatusCode);
    },
    removeUser({commit}) {
        commit("removeUser");
    },
};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
};
