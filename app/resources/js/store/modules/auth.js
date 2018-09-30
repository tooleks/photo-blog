const state = {
    currentUser: null,
    authenticated: false,
};

const mutations = {
    setUser(state, currentUser) {
        state.currentUser = currentUser;
        state.authenticated = true;
    },
    removeUser(state) {
        state.currentUser = null;
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
