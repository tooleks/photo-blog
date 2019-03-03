import axios from "axios";

export default class ApiService {
    /**
     * @param {string} baseUrl
     * @param {Function} onData
     * @param {Function} onError
     */
    constructor(baseUrl, onData, onError) {
        this._api = axios.create({baseURL: baseUrl});
        this._api.defaults.headers.common["Accept"] = "application/json";
        this._api.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
        this._api.interceptors.response.use(onData, onError);
        this.createToken = this.createToken.bind(this);
        this.deleteToken = this.deleteToken.bind(this);
        this.getUser = this.getUser.bind(this);
        this.uploadPhotoFile = this.uploadPhotoFile.bind(this);
        this.updatePhoto = this.updatePhoto.bind(this);
        this.createPost = this.createPost.bind(this);
        this.updatePost = this.updatePost.bind(this);
        this.getPosts = this.getPosts.bind(this);
        this.getPost = this.getPost.bind(this);
        this.getPreviousPost = this.getPreviousPost.bind(this);
        this.getNextPost = this.getNextPost.bind(this);
        this.deletePost = this.deletePost.bind(this);
        this.getTags = this.getTags.bind(this);
        this.createSubscription = this.createSubscription.bind(this);
        this.deleteSubscription = this.deleteSubscription.bind(this);
        this.getSubscriptions = this.getSubscriptions.bind(this);
    }

    /**
     * Create auth token.
     *
     * @param {Object} data
     * @returns {Promise}
     */
    createToken(data) {
        return this._api.post("/auth/token", data);
    }

    /**
     * Delete auth token.
     *
     * @returns {Promise}
     */
    deleteToken() {
        return this._api.delete("/auth/token");
    }

    /**
     * Get user by ID.
     *
     * @param {number} id
     * @returns {Promise}
     */
    getUser(id) {
        return this._api.get(`/users/${encodeURIComponent(id)}`);
    }

    /**
     * Upload photo file.
     *
     * @param {File} file
     * @returns {Promise}
     */
    uploadPhotoFile(file) {
        const data = new FormData;
        data.append("file", file);
        return this._api.post("/photos", data);
    }

    /**
     * Update photo.
     *
     * @param {number} id
     * @param {Object} data
     * @returns {Promise}
     */
    updatePhoto(id, data) {
        return this._api.put(`/photos/${encodeURIComponent(id)}`, data);
    }

    /**
     * Created post.
     *
     * @param {Object} data
     * @returns {Promise}
     */
    createPost(data) {
        return this._api.post("/posts", {...data, is_published: true});
    }

    /**
     * Update post.
     *
     * @param {number} id
     * @param {Object} data
     * @returns {Promise}
     */
    updatePost(id, data) {
        return this._api.put(`/posts/${encodeURIComponent(id)}`, data);
    }

    /**
     * Get posts.
     *
     * @param {Object} [params]
     * @param {number} [params.page=1]
     * @param {number} [params.per_page=15]
     * @param {string} [params.tag]
     * @param {string} [params.search_phrase]
     * @returns {Promise}
     */
    getPosts({page = 1, per_page = 15, tag, search_phrase} = {}) {
        return this._api.get("/posts", {params: {page, per_page, tag, search_phrase}});
    }

    /**
     * Get post.
     *
     * @param {number} id
     * @param {Object} [params]
     * @param {string} params.tag
     * @param {string} params.search_phrase
     * @param {Object} [options]
     * @returns {Promise}
     */
    getPost(id, {tag, search_phrase} = {}, options = {}) {
        return this._api.get(`/posts/${encodeURIComponent(id)}`, {params: {tag, search_phrase}});
    }

    /**
     * Get previous post.
     *
     * @param {number} id
     * @param {Object} [params]
     * @param {string} params.tag
     * @param {string} params.search_phrase
     * @param {Object} [options]
     * @returns {Promise}
     */
    getPreviousPost(id, {tag, search_phrase} = {}, options = {}) {
        return this._api.get(`/posts/${encodeURIComponent(id)}/previous`, {params: {tag, search_phrase}});
    }

    /**
     * Get next post.
     *
     * @param {number} id
     * @param {Object} [params]
     * @param {string} params.tag
     * @param {string} params.search_phrase
     * @param {Object} [options]
     * @returns {Promise}
     */
    getNextPost(id, {tag, search_phrase} = {}, options = {}) {
        return this._api.get(`/posts/${encodeURIComponent(id)}/next`, {params: {tag, search_phrase}});
    }

    /**
     * Delete post.
     *
     * @param {number} id
     * @returns {Promise}
     */
    deletePost(id) {
        return this._api.delete(`/posts/${encodeURIComponent(id)}`);
    }

    /**
     * Get tags.
     *
     * @param {Object} [params]
     * @param {number} [params.page=1]
     * @param {number} [params.per_page=15]
     * @returns {Promise}
     */
    getTags({page = 1, per_page = 15} = {}) {
        return this._api.get("/tags", {params: {page, per_page}});
    }

    /**
     * Create contact message.
     *
     * @param {Object} data
     * @returns {Promise}
     */
    createContactMessage(data) {
        return this._api.post("/contact_messages", data);
    }

    /**
     * Create subscription.
     *
     * @param {Object} data
     * @returns {Promise}
     */
    createSubscription(data) {
        return this._api.post("/subscriptions", data);
    }

    /**
     * Delete subscription.
     *
     * @param {string} token
     * @returns {Promise}
     */
    deleteSubscription(token) {
        return this._api.delete(`/subscriptions/${encodeURIComponent(token)}`);
    }

    /**
     * Get subscriptions.
     *
     * @param {Object} [params]
     * @param {number} [params.page=1]
     * @param {number} [params.per_page=15]
     * @returns {Promise}
     */
    getSubscriptions({page = 1, per_page = 15} = {}) {
        return this._api.get("/subscriptions", {params: {page, per_page}});
    }
}
