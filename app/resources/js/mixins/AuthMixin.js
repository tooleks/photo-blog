import {mapState} from "vuex";

export default {
    computed: mapState({
        currentUser: (state) => state.auth.currentUser,
        authenticated: (state) => state.auth.authenticated,
    }),
}
