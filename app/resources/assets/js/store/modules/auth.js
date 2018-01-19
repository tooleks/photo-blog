import {api, auth, mapper} from "../../services";

export default {
    namespaced: true,
    state: {
        user: auth.get(),
        authenticated: auth.exists(),
        pending: false,
    },
    getters: {
        isPending: function (state) {
            return state.pending;
        },
        isAuthenticated: function (state) {
            return state.authenticated;
        },
        getUser: function (state) {
            return state.user;
        },
    },
    actions: {
        signIn: function ({commit, getters}, data) {
            commit("setPending", {pending: true});
            return api
                .createToken(data)
                .then(() => api.get("me"))
                .then((response) => {
                    const user = mapper.map(response.data, "Api.V1.User", "App.User");
                    commit("setUser", {user});
                    commit("setPending", {pending: false});
                    return Promise.resolve(getters.getUser);
                })
                .catch((error) => {
                    commit("setUser", {user: null});
                    commit("setPending", {pending: false});
                    return Promise.reject(error);
                });
        },
        signOut: function ({commit, getters}) {
            commit("setPending", {pending: true});
            return api
                .deleteToken()
                .then((response) => {
                    commit("setUser", {user: null});
                    commit("setPending", {pending: false});
                    return Promise.resolve(getters.getUser);
                })
                .catch((error) => {
                    commit("setUser", {user: null});
                    commit("setPending", {pending: false});
                    return Promise.reject(error);
                });
        },
    },
    mutations: {
        setPending(state, {pending}) {
            state.pending = pending;
        },
        setUser(state, {user}) {
            auth.set(user);
            state.user = auth.get();
            state.authenticated = auth.exists();
        },
    },
}
