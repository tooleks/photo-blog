<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container px-3 bg-dark">
            <button class="navbar-toggler" type="button"
                    data-toggle="collapse"
                    data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <router-link :to="{name: 'home'}"
                                     class="nav-link"
                                     data-toggle="collapse"
                                     data-target=".navbar-collapse.show">
                            Home
                        </router-link>
                    </li>
                    <li v-if="tags.length" class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarTagsDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Tags</a>
                        <div class="dropdown-menu" aria-labelledby="navbarTagsDropdown">
                            <router-link v-for="tag in tags"
                                         :key="tag"
                                         :to="{name: 'photos-tag', params: {tag: tag}}"
                                         class="dropdown-item"
                                         data-toggle="collapse"
                                         data-target=".navbar-collapse.show">
                                #{{ tag }}
                            </router-link>
                        </div>
                    </li>
                    <li class="nav-item">
                        <router-link :to="{name: 'subscription'}"
                                     class="nav-link"
                                     data-toggle="collapse"
                                     data-target=".navbar-collapse.show">
                            Subscription
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           target="_blank"
                           href="/rss.xml"
                           title="RSS Feed">
                            <i class="fa fa-rss color-rss" aria-hidden="true"></i> RSS
                        </a>
                    </li>
                    <li class="nav-item">
                        <router-link :to="{name: 'contact-me'}"
                                     class="nav-link"
                                     data-toggle="collapse"
                                     data-target=".navbar-collapse.show">
                            Contact Me
                        </router-link>
                    </li>
                    <li class="nav-item" v-if="social.facebook">
                        <a class="nav-link"
                           :href="social.facebook"
                           target="_blank"
                           title="My Facebook Account">
                            <i class="fa fa-facebook-official" aria-hidden="true"></i> <span class="d-lg-none">My Facebook</span>
                        </a>
                    </li>
                    <li class="nav-item" v-if="social.github">
                        <a class="nav-link"
                           :href="social.github"
                           target="_blank"
                           title="My GitHub Account">
                            <i class="fa fa-github" aria-hidden="true"></i> <span
                                class="d-lg-none">My GitHub</span>
                        </a>
                    </li>
                    <li class="nav-item" v-if="!isAuthenticated">
                        <router-link :to="{name: 'sign-in'}"
                                     class="nav-link"
                                     title="Sign In"
                                     data-toggle="collapse"
                                     data-target=".navbar-collapse.show">
                            <i class="fa fa-sign-in" aria-hidden="true"></i> <span class="d-lg-none">Sign In</span>
                        </router-link>
                    </li>
                    <li class="nav-item dropdown" v-if="isAuthenticated">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarUserDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-user" aria-hidden="true"></i> {{ username }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarUserDropdown">
                            <router-link class="dropdown-item"
                                         :to="{name: 'photo/add'}"
                                         data-toggle="collapse"
                                         data-target=".navbar-collapse.show">
                                <i class="fa fa-plus" aria-hidden="true"></i> Add Photo
                            </router-link>
                            <div class="dropdown-divider"></div>
                            <router-link :to="{name: 'sign-out'}"
                                         class="dropdown-item"
                                         data-toggle="collapse"
                                         data-target=".navbar-collapse.show">
                                <i class="fa fa-sign-out" aria-hidden="true"></i> Sign Out
                            </router-link>
                        </div>
                    </li>
                </ul>
                <search-input></search-input>
            </div>
        </div>
    </nav>
</template>

<style scoped>
    .navbar {
        padding-left: 0;
        padding-right: 0;
        z-index: 1010;
    }

    .color-rss {
        color: #e19126;
    }
</style>

<script>
    import SearchInput from "../photos/search-input";
    import {AuthMixin} from "../../mixins";
    import config from "../../config";
    import {api} from "../../services";

    export default {
        components: {
            SearchInput,
        },
        mixins: [
            AuthMixin,
        ],
        computed: {
            social: function () {
                return config.url.social;
            },
            tags: function () {
                return this.$store.getters["tags/getTags"];
            },
        },
        methods: {
            init: function () {
                this.$store.dispatch("tags/loadTags", {per_page: 15});
            },
        },
        created: function () {
            this.init();
        },
    }
</script>
