export default class PhotoService {
    constructor(apiService, mapperService) {
        this.apiService = apiService;
        this.mapperService = mapperService;
    }

    async getPhoto(id, {tag, searchPhrase} = {}) {
        const {data} = await this.apiService.getPost(id, {tag, search_phrase: searchPhrase}, {suppressNotFoundErrors: true});
        return this.mapperService.map(data, "Api.V1.Post", "App.Photo");
    }

    async getOlderPhoto(id, {tag, searchPhrase} = {}) {
        const {data} = await this.apiService.getPreviousPost(id, {tag, search_phrase: searchPhrase}, {suppressNotFoundErrors: true});
        return this.mapperService.map(data, "Api.V1.Post", "App.Photo");
    }

    async getNewerPhoto(id, {tag, searchPhrase} = {}) {
        const {data} = await this.apiService.getNextPost(id, {tag, search_phrase: searchPhrase}, {suppressNotFoundErrors: true});
        return this.mapperService.map(data, "Api.V1.Post", "App.Photo");
    }

    async getPhotos({page = 1, perPage = 40, tag, searchPhrase} = {}) {
        const {data} = await this.apiService.getPosts({page, per_page: perPage, tag, search_phrase: searchPhrase});
        const items = data.data.map((post) => this.mapperService.map(post, "Api.V1.Post", "App.Photo"));
        const currentPage = Number(data.current_page);
        const previousPage = currentPage > 1 ? currentPage - 1 : null;
        const nextPage = currentPage + 1;
        const previousPageExists = Boolean(data.prev_page_url);
        const nextPageExists = Boolean(data.next_page_url);
        return {items, previousPage, currentPage, nextPage, previousPageExists, nextPageExists};
    }
}
