<template>
    <div class="footer container pt-2">
        <div class="row">
            <div class="col">
                <div class="card border-bottom-0 mb-0">
                    <div class="card-body py-2 text-muted">
                        &copy; {{ currentYear }} <strong>{{ appName }}</strong>
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
            currentYear: function () {
                return (new Date).getFullYear();
            },
            appName: function () {
                return this.$dc.get("config").app.name;
            },
        },
    }
</script>
