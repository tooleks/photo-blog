export class UserModel {
    id:number;
    name:string;
    email:string;
    api_token:string;
    created_at:string;
    updated_at:string;
    role:any;

    getApiToken() {
        return this.api_token;
    }
}
