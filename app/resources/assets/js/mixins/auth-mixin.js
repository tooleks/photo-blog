import {optional} from "tooleks";
import {authService} from "../services";

export default {
    data: function () {
        return {
            authenticated: authService.authenticated(),
            userName: optional(() => authService.getUser().name),
            unsubscribeFromAuthChanges: undefined,
        };
    },
    mounted: function () {
        this.unsubscribeFromAuthChanges = authService.onChange(() => {
            this.authenticated = authService.authenticated();
            this.userName = optional(() => authService.getUser().name);
        });
    },
    beforeDestroy: function () {
        if (typeof this.unsubscribeFromAuthChanges !== "undefined") {
            this.unsubscribeFromAuthChanges();
        }
    },
}
