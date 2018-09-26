export default class ApiService {
    /**
     * ApiService constructor.
     *
     * @param {*} httpClient
     * @param {string} apiEndpoint
     * @param {Function} onData
     * @param {Function} onError
     */
    constructor(httpClient, apiEndpoint, onData, onError) {
        this._httpClient = httpClient;
        this._apiEndpoint = apiEndpoint;
        this._onData = onData;
        this._onError = onError;
        this._getFullUrl = this._getFullUrl.bind(this);
        this._handleRequest = this._handleRequest.bind(this);
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
     * Get full URL by relative.
     *
     * @param {string} relativeUrl
     * @return {string}
     * @private
     */
    _getFullUrl(relativeUrl) {
        return this._apiEndpoint + relativeUrl;
    }

    /**
     * Send request and handle response.
     *
     * @param {Function} request
     * @param {Object} options
     * @return {Promise}
     * @private
     */
    _handleRequest(request, options = {}) {
        return request()
            .then((response) => this._onData.call(this._onData, response))
            .catch((error) => this._onError.call(this._onError, error, options));
    }

    /**
     * Create auth token.
     *
     * @param {Object} data
     * @return {Promise}
     */
    createToken(data) {
        const url = this._getFullUrl("/auth/token");
        const request = () => this._httpClient.post(url, data);
        return this._handleRequest(request);
    }

    /**
     * Delete auth token.
     *
     * @return {Promise}
     */
    deleteToken() {
        const url = this._getFullUrl("/auth/token");
        const request = () => this._httpClient.delete(url);
        return this._handleRequest(request);
    }

    /**
     * Get user by ID.
     *
     * @param {number} id
     * @return {Promise}
     */
    getUser(id) {
        const url = this._getFullUrl(`/users/${encodeURIComponent(id)}`);
        const request = () => this._httpClient.get(url);
        return this._handleRequest(request);
    }

    /**
     * Upload photo file.
     *
     * @param {File} file
     * @return {Promise}
     */
    uploadPhotoFile(file) {
        const data = new FormData;
        data.append("file", file);
        const url = this._getFullUrl("/photos");
        const request = () => this._httpClient.post(url, data);
        return this._handleRequest(request);
    }

    /**
     * Update photo.
     *
     * @param {number} id
     * @param {Object} data
     * @return {Promise}
     */
    updatePhoto(id, data) {
        const url = this._getFullUrl(`/photos/${encodeURIComponent(id)}`);
        const request = () => this._httpClient.put(url, data);
        return this._handleRequest(request);
    }

    /**
     * Created post.
     *
     * @param {Object} data
     * @return {Promise}
     */
    createPost(data) {
        const url = this._getFullUrl("/posts");
        const request = () => this._httpClient.post(url, {...data, is_published: true});
        return this._handleRequest(request);
    }

    /**
     * Update post.
     *
     * @param {number} id
     * @param {Object} data
     * @return {Promise}
     */
    updatePost(id, data) {
        const url = this._getFullUrl(`/posts/${encodeURIComponent(id)}`);
        const request = () => this._httpClient.put(url, data);
        return this._handleRequest(request);
    }

    /**
     * Get posts.
     *
     * @param {Object} [params]
     * @param {number} [params.page=1]
     * @param {number} [params.per_page=15]
     * @param {string} [params.tag]
     * @param {string} [params.search_phrase]
     * @return {Promise}
     */
    getPosts({page = 1, per_page = 15, tag, search_phrase} = {}) {
        const url = this._getFullUrl("/posts");
        const request = () => this._httpClient.get(url, {params: {page, per_page, tag, search_phrase}});
        return this._handleRequest(request);
    }

    /**
     * Get post.
     *
     * @param {number} id
     * @param {Object} [params]
     * @param {string} params.tag
     * @param {string} params.search_phrase
     * @param {Object} [options]
     * @return {Promise}
     */
    getPost(id, {tag, search_phrase} = {}, options = {}) {
        const url = this._getFullUrl(`/posts/${encodeURIComponent(id)}`);
        const request = () => this._httpClient.get(url, {params: {tag, search_phrase}});
        return this._handleRequest(request, options);
    }

    /**
     * Get previous post.
     *
     * @param {number} id
     * @param {Object} [params]
     * @param {string} params.tag
     * @param {string} params.search_phrase
     * @param {Object} [options]
     * @return {Promise}
     */
    getPreviousPost(id, {tag, search_phrase} = {}, options = {}) {
        const url = this._getFullUrl(`/posts/${encodeURIComponent(id)}/previous`);
        const request = () => this._httpClient.get(url, {params: {tag, search_phrase}});
        return this._handleRequest(request, options);
    }

    /**
     * Get next post.
     *
     * @param {number} id
     * @param {Object} [params]
     * @param {string} params.tag
     * @param {string} params.search_phrase
     * @param {Object} [options]
     * @return {Promise}
     */
    getNextPost(id, {tag, search_phrase} = {}, options = {}) {
        const url = this._getFullUrl(`/posts/${encodeURIComponent(id)}/next`);
        const request = () => this._httpClient.get(url, {params: {tag, search_phrase}});
        return this._handleRequest(request, options);
    }

    /**
     * Delete post.
     *
     * @param {number} id
     * @return {Promise}
     */
    deletePost(id) {
        const url = this._getFullUrl(`/posts/${encodeURIComponent(id)}`);
        const request = () => this._httpClient.delete(url);
        return this._handleRequest(request);
    }

    /**
     * Get tags.
     *
     * @param {Object} [params]
     * @param {number} [params.page=1]
     * @param {number} [params.per_page=15]
     * @return {Promise}
     */
    getTags({page = 1, per_page = 15} = {}) {
        const url = this._getFullUrl("/tags");
        const request = () => this._httpClient.get(url, {params: {page, per_page}});
        return this._handleRequest(request);
    }

    /**
     * Create contact message.
     *
     * @param {Object} data
     * @return {Promise}
     */
    createContactMessage(data) {
        const url = this._getFullUrl("/contact_messages");
        const request = () => this._httpClient.post(url, data);
        return this._handleRequest(request);
    }

    /**
     * Create subscription.
     *
     * @param {Object} data
     * @return {Promise}
     */
    createSubscription(data) {
        const url = this._getFullUrl("/subscriptions");
        const request = () => this._httpClient.post(url, data);
        return this._handleRequest(request);
    }

    /**
     * Delete subscription.
     *
     * @param {string} token
     * @return {Promise}
     */
    deleteSubscription(token) {
        const url = this._getFullUrl(`/subscriptions/${encodeURIComponent(token)}`);
        const request = () => this._httpClient.delete(url);
        return this._handleRequest(request);
    }

    /**
     * Get subscriptions.
     *
     * @param {Object} [params]
     * @param {number} [params.page=1]
     * @param {number} [params.per_page=15]
     * @return {Promise}
     */
    getSubscriptions({page = 1, per_page = 15} = {}) {
        const url = this._getFullUrl("/subscriptions");
        const request = () => this._httpClient.get(url, {params: {page, per_page}});
        return this._handleRequest(request);
    }
}
