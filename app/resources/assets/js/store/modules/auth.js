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
        signIn: async function ({commit, getters}, data) {
            try {
                commit("setPending", {pending: true});
                await api.createToken(data);
                const response = await api.getUser("me");
                const user = mapper.map(response.data, "Api.V1.User", "App.User");
                commit("setUser", {user});
                return getters.getUser;
            } catch (error) {
                commit("setUser", {user: null});
                throw error;
            } finally {
                commit("setPending", {pending: false});
            }
        },
        signOut: async function ({commit, getters}) {
            try {
                commit("setPending", {pending: true});
                await api.deleteToken();
                commit("setUser", {user: null});
                return getters.getUser;
            } catch (error) {
                commit("setUser", {user: null});
                throw error;
            } finally {
                commit("setPending", {pending: false});
            }
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
