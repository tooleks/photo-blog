import * as apiDomainMapper from "../mapper/ApiDomain/transform";

export default class SubscriptionManager {
    /**
     * SubscriptionManager constructor.
     *
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
     * @return {Promise<void>}
     */
    async deleteByToken(token) {
        await this._api.deleteSubscription(token);
    }

    /**
     * Paginate over subscriptions.
     *
     * @param {Object} [options]
     * @param {number} [options.page]
     * @param {number} [options.perPage]
     * @return {Promise<Object>}
     */
    async paginate({page, perPage} = {}) {
        const response = await this._api.getSubscriptions({page, per_page: perPage});
        return apiDomainMapper.toPaginator(response.data, apiDomainMapper.toSubscription);
    }
}
