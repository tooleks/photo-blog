import {optional as opt} from "tooleks";

export default {
    data: function () {
        return {
            authenticated: this.$dc.get("auth").authenticated(),
            userName: opt(() => this.$dc.get("auth").getUser().name),
            unsubscribeFromAuthChanges: null,
        };
    },
    created: function () {
        this.unsubscribeFromAuthChanges = this.$dc.get("auth").onChange(() => {
            this.authenticated = this.$dc.get("auth").authenticated();
            this.userName = opt(() => this.$dc.get("auth").getUser().name);
        });
    },
    beforeDestroy: function () {
        if (this.unsubscribeFromAuthChanges) {
            this.unsubscribeFromAuthChanges();
        }
    },
}
