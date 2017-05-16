export interface ApiService {
    get(relativeUrl: string, options?): Promise<any>;
    post(relativeUrl: string, body?, options?): Promise<any>;
    put(relativeUrl: string, body?, options?): Promise<any>;
    delete(relativeUrl: string, options?): Promise<any>;
}
