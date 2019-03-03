import * as apiEntityMapper from "../mapper/apiEntity";
import * as entityApiMapper from "../mapper/entityApi";

export default class PhotoManager {
    /**
     * @param {ApiService} api
     */
    constructor(api) {
        this._api = api;
        this.upload = this.upload.bind(this);
        this.publish = this.publish.bind(this);
        this.deleteByPostId = this.deleteByPostId.bind(this);
        this.getByPostId = this.getByPostId.bind(this);
        this.getPreviousByPostId = this.getPreviousByPostId.bind(this);
        this.getNextByPostId = this.getNextByPostId.bind(this);
        this.getAll = this.getAll.bind(this);
        this.paginate = this.paginate.bind(this);
    }

    /**
     * Upload a new photo file.
     *
     * @param {File} file
     * @returns {Promise<Photo>}
     */
    async upload(file) {
        const response = await this._api.uploadPhotoFile(file);
        return apiEntityMapper.toPhoto({
            photo: response.data,
        });
    }

    /**
     * Publish the photo.
     *
     * @param {Photo} photo
     * @returns {Promise<Photo>}
     */
    async publish(photo) {
        // Update the photo location in case if the photo location is set.
        if (photo.location) {
            await this._api.updatePhoto(photo.id, entityApiMapper.toPhoto(photo));
        }

        const post = entityApiMapper.toPost(photo);

        // Create a new post in case if the photo is not published yet.
        if (!photo.published) {
            const response = await this._api.createPost(post);
            return apiEntityMapper.toPhoto(response.data);
        }

        const response = await this._api.updatePost(photo.postId, post);
        return apiEntityMapper.toPhoto(response.data);
    }

    /**
     * Delete the photo by related post ID.
     *
     * @param {number} postId
     * @returns {Promise<void>}
     */
    async deleteByPostId(postId) {
        await this._api.deletePost(postId);
    }

    /**
     * Get the photo by related post ID.
     *
     * @param {number} postId
     * @param {Object} [params]
     * @param {string} [params.tag]
     * @param {string} [params.searchPhrase]
     * @returns {Promise<Photo>}
     */
    async getByPostId(postId, {tag, searchPhrase} = {}) {
        const response = await this._api.getPost(postId, {
            tag,
            search_phrase: searchPhrase,
        });
        return apiEntityMapper.toPhoto(response.data);
    }

    /**
     * Get the previous photo by related post ID.
     *
     * @param {number} postId
     * @param {Object} [params]
     * @param {string} [params.tag]
     * @param {string} [params.searchPhrase]
     * @returns {Promise<Photo>}
     */
    async getPreviousByPostId(postId, {tag, searchPhrase} = {}) {
        const response = await this._api.getPreviousPost(postId, {
            tag,
            search_phrase: searchPhrase,
        }, {
            suppressNotFoundErrors: true,
        });
        return apiEntityMapper.toPhoto(response.data);
    }

    /**
     * Get the next photo by related post ID.
     *
     * @param {number} postId
     * @param {Object} [params]
     * @param {string} [params.tag]
     * @param {string} [params.searchPhrase]
     * @returns {Promise<Photo>}
     */
    async getNextByPostId(postId, {tag, searchPhrase} = {}) {
        const response = await this._api.getNextPost(postId, {
            tag,
            search_phrase: searchPhrase,
        }, {
            suppressNotFoundErrors: true,
        });
        return apiEntityMapper.toPhoto(response.data);
    }

    /**
     * Recursively get all photos.
     *
     * @param {Object} [params]
     * @param {number} [params.page=1]
     * @param {number} [params.perPage=50]
     * @returns {Promise<Array<Photo>>}
     */
    async getAll({page = 1, perPage = 50} = {}) {
        const response = await this._api.getPosts({page, per_page: perPage});
        let {nextPageExists, items} = apiEntityMapper.toPaginator(response.data, apiEntityMapper.toPhoto);
        if (nextPageExists) {
            items = [...items, ...await this.getAll({page: ++page})];
        }
        return items;
    }

    /**
     * Paginate over photos.
     *
     * @param {Object} [params]
     * @param {number} [params.page]
     * @param {number} [params.perPage]
     * @param {string} [params.tag]
     * @param {string} [params.searchPhrase]
     * @returns {Promise<Object>}
     */
    async paginate({page = 1, perPage = 40, tag, searchPhrase}) {
        const response = await this._api.getPosts({
            page,
            per_page: perPage,
            tag,
            search_phrase: searchPhrase,
        });
        return apiEntityMapper.toPaginator(response.data, apiEntityMapper.toPhoto);
    }
}
