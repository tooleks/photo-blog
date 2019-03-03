import * as services from "../services/factory";

export default {
    computed: {
        currentUser() {
            return services.getAuth().getUser();
        },
        authenticated() {
            return services.getAuth().hasUser();
        },
    },
}
