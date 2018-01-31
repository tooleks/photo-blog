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

    async _handleRequest(request, options = {}) {
        return request.call(this)
            .then((response) => this.onSuccess.call(this.onSuccess, response))
            .catch((error) => this.onError.call(this.onError, error, options));
    }

    createToken(data) {
        const request = () => this.httpClient.post(this._getFullUrl("/auth/token"), data);
        return this._handleRequest(request);
    }

    deleteToken() {
        const request = () => this.httpClient.delete(this._getFullUrl("/auth/token"));
        return this._handleRequest(request);
    }

    getUser(id) {
        const request = () => this.httpClient.get(this._getFullUrl(`/users/${id}`));
        return this._handleRequest(request);
    }

    createPhoto(file) {
        const data = new FormData;
        data.append("file", file);
        const request = () => this.httpClient.post(this._getFullUrl("/photos"), data);
        return this._handleRequest(request);
    }

    createPost(data) {
        const request = () => this.httpClient.post(this._getFullUrl("/posts"), Object.assign({}, data, {is_published: true}));
        return this._handleRequest(request);
    }

    updatePost(id, data) {
        const request = () => this.httpClient.put(this._getFullUrl(`/posts/${id}`), data);
        return this._handleRequest(request);
    }

    getPosts(params = {}) {
        const request = () => this.httpClient.get(this._getFullUrl("/posts"), {params});
        return this._handleRequest(request);
    }

    getPost(id, params = {}, options = {}) {
        const request = () => this.httpClient.get(this._getFullUrl(`/posts/${id}`), {params});
        return this._handleRequest(request, options);
    }

    getPreviousPost(id, params = {}, options = {}) {
        const request = () => this.httpClient.get(this._getFullUrl(`/posts/${id}/previous`), {params});
        return this._handleRequest(request, options);
    }

    getNextPost(id, params = {}, options = {}) {
        const request = () => this.httpClient.get(this._getFullUrl(`/posts/${id}/next`), {params});
        return this._handleRequest(request, options);
    }

    deletePost(id) {
        const request = () => this.httpClient.delete(this._getFullUrl(`/posts/${id}`));
        return this._handleRequest(request);
    }

    getTags(params = {}) {
        const request = () => this.httpClient.get(this._getFullUrl("/tags"), {params});
        return this._handleRequest(request);
    }

    createContactMessage(data) {
        const request = () => this.httpClient.post(this._getFullUrl("/contact_messages"), data);
        return this._handleRequest(request);
    }

    createSubscription(data) {
        const request = () => this.httpClient.post(this._getFullUrl("/subscriptions"), data);
        return this._handleRequest(request);
    }

    deleteSubscription(token) {
        const request = () => this.httpClient.delete(this._getFullUrl(`/subscriptions/${token}`));
        return this._handleRequest(request);
    }
}
