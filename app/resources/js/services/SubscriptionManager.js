import * as apiEntityMapper from "../mapper/apiEntity";

export default class SubscriptionManager {
    /**
     * @param {ApiService} api
     */
    constructor(api) {
        this._api = api;
        this.deleteByToken = this.deleteByToken.bind(this);
        this.paginate = this.paginate.bind(this);
    }

    /**
     * Delete the subscription by token value.
     *
     * @param {string} token
     * @returns {Promise<void>}
     */
    async deleteByToken(token) {
        await this._api.deleteSubscription(token);
    }

    /**
     * Paginate over subscriptions.
     *
     * @param {Object} [params]
     * @param {number} [params.page]
     * @param {number} [params.perPage]
     * @returns {Promise<Object>}
     */
    async paginate({page, perPage} = {}) {
        const response = await this._api.getSubscriptions({page, per_page: perPage});
        return apiEntityMapper.toPaginator(response.data, apiEntityMapper.toSubscription);
    }
}
