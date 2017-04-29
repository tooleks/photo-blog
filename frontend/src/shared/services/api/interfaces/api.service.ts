export interface ApiService {
    get(relativeUrl:string, options?:any):Promise<any>;
    post(relativeUrl:string, body?:any, options?:any):Promise<any>;
    put(relativeUrl:string, body?:any, options?:any):Promise<any>;
    delete(relativeUrl:string, options?:any):Promise<any>;
}
