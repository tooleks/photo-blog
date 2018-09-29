export default {
    data: function () {
        return {
            currentUser: this.$services.getAuth().getUser(),
            offAuthEvents: null,
        };
    },
    created: function () {
        this.offAuthEvents = this.$services.getAuth().subscribe((user) => {
            this.currentUser = user;
        });
    },
    beforeDestroy: function () {
        if (this.offAuthEvents !== null) {
            this.offAuthEvents();
        }
    },
}
