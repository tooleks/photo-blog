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

    createToken(data) {
        const url = this._getFullUrl("/auth/token");
        const request = () => this.httpClient.post(url, data);
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
        const data = new FormData;
        data.append("file", file);
        const url = this._getFullUrl("/photos");
        const request = () => this.httpClient.post(url, data);
        return this._handleRequest(request);
    }

    updatePhotoLocation(id, data) {
        const url = this._getFullUrl(`/photos/${id}`);
        const request = () => this.httpClient.put(url, data);
        return this._handleRequest(request);
    }

    createPost(data) {
        const url = this._getFullUrl("/posts");
        const request = () => this.httpClient.post(url, {...data, is_published: true});
        return this._handleRequest(request);
    }

    updatePost(id, data) {
        const url = this._getFullUrl(`/posts/${id}`);
        const request = () => this.httpClient.put(url, data);
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

    getTags({page = 1, per_page = 15} = {}) {
        const url = this._getFullUrl("/tags");
        const request = () => this.httpClient.get(url, {params: {page, per_page}});
        return this._handleRequest(request);
    }

    createContactMessage(data) {
        const url = this._getFullUrl("/contact_messages");
        const request = () => this.httpClient.post(url, data);
        return this._handleRequest(request);
    }

    createSubscription(data) {
        const url = this._getFullUrl("/subscriptions");
        const request = () => this.httpClient.post(url, data);
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
