<template>
    <div class="footer container pt-2">
        <div class="row">
            <div class="col">
                <div class="card border-bottom-0 mb-0">
                    <div class="card-body py-2 text-muted">
                        &copy; {{ currentYear }} <strong>{{ appName }}</strong>
                        <div>Favicon made by <a href="https://www.flaticon.com/authors/good-ware" title="Good Ware">Good
                            Ware</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> is
                            licensed by <a href="http://creativecommons.org/licenses/by/3.0/"
                                           title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .footer {
        font-size: 0.9rem;
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
    }
</style>

<script>
    export default {
        computed: {
            currentYear() {
                return (new Date).getFullYear();
            },
            appName() {
                return this.$services.getConfig().app.name;
            },
        },
    }
</script>
