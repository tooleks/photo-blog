import * as apiDomainMapper from "../mapper/ApiDomain/transform";

export default class TagManager {
    /**
     * TagManager constructor.
     *
     * @param {ApiService} api
     */
    constructor(api) {
        this._api = api;
        this.getPopular = this.getPopular.bind(this);
    }

    /**
     * Get the most popular tags.
     *
     * @return {Promise<Array<Tag>>}
     */
    async getPopular() {
        const response = await this._api.getTags();
        return apiDomainMapper.toList(response.data, apiDomainMapper.toTag);
    }
}
