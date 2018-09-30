import {mapState} from "vuex";

export default {
    computed: mapState({
        currentUser: (state) => state.auth.user,
        authenticated: (state) => state.auth.authenticated,
    }),
}
