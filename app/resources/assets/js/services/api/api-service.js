export default class ApiService {
    constructor(httpClient, apiEndpoint, onSuccess, onError) {
        this.httpClient = httpClient;
        this.apiEndpoint = apiEndpoint;
        this.onSuccess = onSuccess;
        this.onError = onError;
    }

    _getFullUrl(relativeUrl) {
        return `${this.apiEndpoint}${relativeUrl}`;
    }

    _handle(callback, options = {}) {
        return callback
            .call(this)
            .then((response) => this.onSuccess(response))
            .catch((error) => this.onError(error, options));
    }

    createToken(data) {
        return this._handle(() => this.httpClient.post(this._getFullUrl("/auth/token"), data));
    }

    deleteToken() {
        return this._handle(() => this.httpClient.delete(this._getFullUrl("/auth/token")));
    }

    get(id) {
        return this._handle(() => this.httpClient.get(this._getFullUrl(`/users/${id}`)));
    }

    createPhoto(file) {
        const data = new FormData;
        data.append("file", file);
        return this._handle(() => this.httpClient.post(this._getFullUrl("/photos"), data));
    }

    createPost(data) {
        return this._handle(() => this.httpClient.post(this._getFullUrl("/posts"), Object.assign(data, {is_published: true})));
    }

    updatePost(id, data) {
        return this._handle(() => this.httpClient.put(this._getFullUrl(`/posts/${id}`), data));
    }

    getPosts(params = {}) {
        return this._handle(() => this.httpClient.get(this._getFullUrl("/posts"), {params}));
    }

    getPost(id, params = {}, options = {}) {
        return this._handle(() => this.httpClient.get(this._getFullUrl(`/posts/${id}`), {params}), options);
    }

    getPreviousPost(id, params = {}, options = {}) {
        return this._handle(() => this.httpClient.get(this._getFullUrl(`/posts/${id}/previous`), {params}), options);
    }

    getNextPost(id, params = {}, options = {}) {
        return this._handle(() => this.httpClient.get(this._getFullUrl(`/posts/${id}/next`), {params}), options);
    }

    deletePost(id) {
        return this._handle(() => this.httpClient.delete(this._getFullUrl(`/posts/${id}`)));
    }

    getTags(params = {}) {
        return this._handle(() => this.httpClient.get(this._getFullUrl("/tags"), {params}));
    }

    createContactMessage(data) {
        return this._handle(() => this.httpClient.post(this._getFullUrl("/contact_messages"), data));
    }

    createSubscription(data) {
        return this._handle(() => this.httpClient.post(this._getFullUrl("/subscriptions"), data));
    }

    deleteSubscription(token) {
        return this._handle(() => this.httpClient.delete(this._getFullUrl(`/subscriptions/${token}`)));
    }
}
