import {optional} from "tooleks";

export default {
    data: function () {
        return {
            authenticated: this.$dc.get("auth").authenticated(),
            userName: optional(() => this.$dc.get("auth").getUser().name),
            unsubscribeFromAuthChanges: undefined,
        };
    },
    mounted: function () {
        this.unsubscribeFromAuthChanges = this.$dc.get("auth").onChange(() => {
            this.authenticated = this.$dc.get("auth").authenticated();
            this.userName = optional(() => this.$dc.get("auth").getUser().name);
        });
    },
    beforeDestroy: function () {
        if (typeof this.unsubscribeFromAuthChanges !== "undefined") {
            this.unsubscribeFromAuthChanges();
        }
    },
}
