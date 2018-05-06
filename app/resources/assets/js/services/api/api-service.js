export default class ApiService {
    constructor(httpClient, apiEndpoint, onSuccess, onError) {
        this.httpClient = httpClient;
        this.apiEndpoint = apiEndpoint;
        this.onSuccess = onSuccess;
        this.onError = onError;
    }

    _getFullUrl(relativeUrl) {
        return this.apiEndpoint + relativeUrl;
    }

    _handleRequest(request, options = {}) {
        return request.call(this)
            .then((response) => this.onSuccess.call(this.onSuccess, response))
            .catch((error) => this.onError.call(this.onError, error, options));
    }

    createToken(payload) {
        const url = this._getFullUrl("/auth/token");
        const request = () => this.httpClient.post(url, payload);
        return this._handleRequest(request);
    }

    deleteToken() {
        const url = this._getFullUrl("/auth/token");
        const request = () => this.httpClient.delete(url);
        return this._handleRequest(request);
    }

    getUser(id) {
        const url = this._getFullUrl(`/users/${id}`);
        const request = () => this.httpClient.get(url);
        return this._handleRequest(request);
    }

    uploadPhotoFile(file) {
        const payload = new FormData;
        payload.append("file", file);
        const url = this._getFullUrl("/photos");
        const request = () => this.httpClient.post(url, payload);
        return this._handleRequest(request);
    }

    updatePhotoLocation(id, payload) {
        const url = this._getFullUrl(`/photos/${id}`);
        const request = () => this.httpClient.put(url, payload);
        return this._handleRequest(request);
    }

    createPost(payload) {
        const url = this._getFullUrl("/posts");
        const request = () => this.httpClient.post(url, Object.assign({}, payload, {is_published: true}));
        return this._handleRequest(request);
    }

    updatePost(id, payload) {
        const url = this._getFullUrl(`/posts/${id}`);
        const request = () => this.httpClient.put(url, payload);
        return this._handleRequest(request);
    }

    getPosts({page = 1, per_page = 15, tag, search_phrase} = {}) {
        const url = this._getFullUrl("/posts");
        const request = () => this.httpClient.get(url, {params: {page, per_page, tag, search_phrase}});
        return this._handleRequest(request);
    }

    getPost(id, {tag, search_phrase} = {}, options = {}) {
        const url = this._getFullUrl(`/posts/${id}`);
        const request = () => this.httpClient.get(url, {params: {tag, search_phrase}});
        return this._handleRequest(request, options);
    }

    getPreviousPost(id, {tag, search_phrase} = {}, options = {}) {
        const url = this._getFullUrl(`/posts/${id}/previous`);
        const request = () => this.httpClient.get(url, {params: {tag, search_phrase}});
        return this._handleRequest(request, options);
    }

    getNextPost(id, {tag, search_phrase} = {}, options = {}) {
        const url = this._getFullUrl(`/posts/${id}/next`);
        const request = () => this.httpClient.get(url, {params: {tag, search_phrase}});
        return this._handleRequest(request, options);
    }

    deletePost(id) {
        const url = this._getFullUrl(`/posts/${id}`);
        const request = () => this.httpClient.delete(url);
        return this._handleRequest(request);
    }

    getTags(params = {}) {
        const url = this._getFullUrl("/tags");
        const request = () => this.httpClient.get(url, {params});
        return this._handleRequest(request);
    }

    createContactMessage(payload) {
        const url = this._getFullUrl("/contact_messages");
        const request = () => this.httpClient.post(url, payload);
        return this._handleRequest(request);
    }

    createSubscription(payload) {
        const url = this._getFullUrl("/subscriptions");
        const request = () => this.httpClient.post(url, payload);
        return this._handleRequest(request);
    }

    deleteSubscription(token) {
        const url = this._getFullUrl(`/subscriptions/${token}`);
        const request = () => this.httpClient.delete(url);
        return this._handleRequest(request);
    }

    getSubscriptions({page = 1, per_page = 15} = {}) {
        const url = this._getFullUrl("/subscriptions");
        const request = () => this.httpClient.get(url, {params: {page, per_page}});
        return this._handleRequest(request);
    }
}
