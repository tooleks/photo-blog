export default class TagService {
    constructor(apiService, mapperService) {
        this.apiService = apiService;
        this.mapperService = mapperService;
    }

    async getTags({page = 1, perPage = 15} = {}) {
        const {data} = await this.apiService.getTags({page, per_page: perPage});
        const items = data.data.map((post) => this.mapperService.map(post, "Api.V1.Tag", "App.Tag"));
        const currentPage = Number(data.current_page);
        const previousPage = currentPage > 1 ? currentPage - 1 : null;
        const nextPage = currentPage + 1;
        const previousPageExists = Boolean(data.prev_page_url);
        const nextPageExists = Boolean(data.next_page_url);
        return {items, previousPage, currentPage, nextPage, previousPageExists, nextPageExists};
    }
}
