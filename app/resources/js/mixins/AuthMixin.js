import {optional as opt} from "tooleks";
import dc from "../dc";

/**
 * Determine whether current user is authenticated or not.
 *
 * @return {boolean}
 */
function isAuthenticated() {
    return dc.get("auth").authenticated();
}

/**
 * Get current user name.
 *
 * @return {string}
 */
function getUserName() {
    return opt(() => dc.get("auth").getUser().name);
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
        this.unsubscribeFromAuthChanges = this.$dc.get("auth").subscribe(() => {
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
