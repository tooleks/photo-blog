import {optional as opt} from "tooleks";
import * as services from "../services/factory";

/**
 * Determine whether current user is authenticated or not.
 *
 * @return {boolean}
 */
function isAuthenticated() {
    return services.getAuth().authenticated();
}

/**
 * Get current user name.
 *
 * @return {string}
 */
function getUserName() {
    return opt(() => services.getAuth().getUser().name);
}

export default {
    data: function () {
        return {
            authenticated: isAuthenticated(),
            userName: getUserName(),
            unsubscribeFromAuthChanges: null,
        };
    },
    created: function () {
        this.unsubscribeFromAuthChanges = this.$services.getAuth().subscribe(() => {
            this.authenticated = isAuthenticated();
            this.userName = getUserName();
        });
    },
    beforeDestroy: function () {
        if (this.unsubscribeFromAuthChanges) {
            this.unsubscribeFromAuthChanges();
        }
    },
}
