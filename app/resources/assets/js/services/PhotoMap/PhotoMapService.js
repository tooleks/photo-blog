/**
 * Class PhotoMapService.
 */
export default class PhotoMapService {
    /**
     * PhotoMapService constructor.
     *
     * @param {ApiService} apiService
     * @param {Mapper} mapperService
     */
    constructor(apiService, mapperService) {
        this.apiService = apiService;
        this.mapperService = mapperService;
        this.getImages = this.getImages.bind(this);
    }

    /**
     * Recursively get all images for photo map.
     *
     * @param {Object} [params={}]
     * @param {number} [params.page=1]
     * @param {number} [params.perPage=50]
     * @return {Promise}
     */
    async getImages({page = 1, perPage = 50} = {}) {
        const {data} = await this.apiService.getPosts({page, per_page: perPage});
        let images = data.data.map((post) => this.mapperService.map(post, "Api.Post", "Map.Image"));
        const nextPageExists = Boolean(data.next_page_url);
        if (nextPageExists) {
            images = [...images, ...await this.getImages(page + 1)];
        }
        return images.filter((image) => image.location);
    }
}
