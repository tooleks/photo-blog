import * as apiDomainMapper from "../mapper/ApiDomain/transform";
import * as domainApiMapper from "../mapper/DomainApi/transform";

export default class PhotoManager {
    /**
     * PhotoProvider constructor.
     *
     * @param {ApiService} api
     */
    constructor(api) {
        this._api = api;
        this.upload = this.upload.bind(this);
        this.publish = this.publish.bind(this);
        this.deleteByPostId = this.deleteByPostId.bind(this);
        this.getByPostId = this.getByPostId.bind(this);
        this.getAll = this.getAll.bind(this);
    }

    /**
     * Upload a new photo file.
     *
     * @param {File} file
     * @return {Promise<Photo>}
     */
    async upload(file) {
        const response = await this._api.uploadPhotoFile(file);
        return apiDomainMapper.toPhoto({
            photo: response.data,
        });
    }

    /**
     * Publish the photo.
     *
     * @param {Photo} photo
     * @return {Promise<Photo>}
     */
    async publish(photo) {
        // Update the photo location in case if the photo location is set.
        if (photo.location) {
            await this._api.updatePhoto(photo.id, domainApiMapper.toPhoto(photo));
        }

        const post = domainApiMapper.toPost(photo);

        // Create a new post in case if the photo is not published yet.
        if (!photo.published) {
            const response = await this._api.createPost(post);
            return apiDomainMapper.toPhoto(response.data);
        }

        const response = await this._api.updatePost(photo.postId, post);
        return apiDomainMapper.toPhoto(response.data);
    }

    /**
     * Delete the photo by related post ID.
     *
     * @param {number} postId
     * @return {Promise<void>}
     */
    async deleteByPostId(postId) {
        await this._api.deletePost(postId);
    }

    /**
     * Get the photo by related post ID.
     *
     * @param {number} postId
     * @return {Promise<Photo>}
     */
    async getByPostId(postId) {
        const response = await this._api.getPost(postId);
        return apiDomainMapper.toPhoto(response.data);
    }

    /**
     * Recursively get all photos.
     *
     * @param {Object} [params]
     * @param {number} [params.page=1]
     * @param {number} [params.perPage=50]
     * @return {Promise<Array<Photo>>}
     */
    async getAll({page = 1, perPage = 50} = {}) {
        const response = await this._api.getPosts({page, per_page: perPage});
        let {nextPageExists, items} = apiDomainMapper.toPaginator(response.data, apiDomainMapper.toPhoto);
        if (nextPageExists) {
            items = [...items, ...await this.getAll({page: ++page})];
        }
        return items;
    }
}
