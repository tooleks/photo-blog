<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-shark box-shadow-1dp">
        <div class="container px-3 bg-shark">
            <button class="navbar-toggler" type="button"
                    data-toggle="collapse"
                    data-target="#navbar"
                    aria-controls="navbar"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbar">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <router-link :to="{name: 'home'}"
                                     class="nav-link"
                                     data-toggle="collapse"
                                     data-target=".navbar-collapse.show">
                            Home
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link :to="{name: 'photos-map'}"
                                     class="nav-link"
                                     data-toggle="collapse"
                                     data-target=".navbar-collapse.show">
                            Map
                        </router-link>
                    </li>
                    <li v-if="tags.length" class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarTagsDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Tags</a>
                        <div class="dropdown-menu box-shadow-2dp" aria-labelledby="navbarTagsDropdown">
                            <router-link v-for="tag in tags"
                                         :key="`${tag}`"
                                         :to="{name: 'photos-tag', params: {tag: `${tag}`}}"
                                         class="dropdown-item"
                                         data-toggle="collapse"
                                         data-target=".navbar-collapse.show">
                                #{{ `${tag}` }}
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
                           title="My Facebook Account"
                           aria-label="My Facebook Account">
                            <i class="fa fa-facebook-official" aria-hidden="true"></i> <span class="d-lg-none">My Facebook</span>
                        </a>
                    </li>
                    <li class="nav-item" v-if="social.github">
                        <a class="nav-link"
                           :href="social.github"
                           target="_blank"
                           title="My GitHub Account"
                           aria-label="My GitHub Account">
                            <i class="fa fa-github" aria-hidden="true"></i> <span
                                class="d-lg-none">My GitHub</span>
                        </a>
                    </li>
                    <li class="nav-item" v-if="!authenticated">
                        <router-link :to="{name: 'sign-in'}"
                                     class="nav-link"
                                     title="Sign In"
                                     data-toggle="collapse"
                                     data-target=".navbar-collapse.show"
                                     aria-label="Sign In">
                            <i class="fa fa-sign-in" aria-hidden="true"></i> <span class="d-lg-none">Sign In</span>
                        </router-link>
                    </li>
                    <li class="nav-item dropdown" v-if="authenticated">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarUserDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-user" aria-hidden="true"></i> {{ currentUser.name }}
                        </a>
                        <div class="dropdown-menu  box-shadow-2dp" aria-labelledby="navbarUserDropdown">
                            <router-link class="dropdown-item"
                                         :to="{name: 'photo/add'}"
                                         data-toggle="collapse"
                                         data-target=".navbar-collapse.show">
                                Add Photo
                            </router-link>
                            <router-link class="dropdown-item"
                                         :to="{name: 'subscriptions'}"
                                         data-toggle="collapse"
                                         data-target=".navbar-collapse.show">
                                Subscriptions
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
                <search-input/>
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

    .navbar-expand-lg .navbar-nav .nav-item:first-child .nav-link {
        padding-left: 0;
    }

    .nav-link > .color-rss {
        color: #e19126;
    }

    .nav-link:hover > .color-rss {
        color: #e7a853;
    }
</style>

<script>
    import SearchInput from "../photos/SearchInput";
    import {AuthMixin} from "../../mixins";

    export default {
        components: {
            SearchInput,
        },
        mixins: [
            AuthMixin,
        ],
        data() {
            return {
                /** @type {Array<Tag>} */
                tags: [],
            };
        },
        computed: {
            social() {
                return this.$services.getConfig().url.social;
            },
        },
        methods: {
            async loadTags() {
                this.tags = await this.$services.getTagManager().getPopular();
            },
        },
        created() {
            this.loadTags();
        },
    }
</script>
