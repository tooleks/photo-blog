export default {
    computed: {
        currentUser() {
            return this.$services.getAuth().getUser();
        },
        authenticated() {
            return this.$services.getAuth().hasUser();
        },
    },
}
