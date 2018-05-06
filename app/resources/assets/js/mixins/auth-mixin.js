import {optional as opt} from "tooleks";

export default {
    data: function () {
        return {
            listeners: [],
            authenticated: this.$dc.get("auth").authenticated(),
            userName: opt(() => this.$dc.get("auth").getUser().name),
        };
    },
    created: function () {
        const unsubscribe = this.$dc.get("auth").onChange(() => {
            this.authenticated = this.$dc.get("auth").authenticated();
            this.userName = opt(() => this.$dc.get("auth").getUser().name);
        });
        this.listeners.push(unsubscribe);
    },
    beforeDestroy: function () {
        this.listeners.forEach((unsubscribe) => unsubscribe());
        this.listeners = [];
    },
}
