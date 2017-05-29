import {Injectable} from '@angular/core';

@Injectable()
export class AppService {
    inDebugMode(): boolean {
        return process.env.NODE_ENV !== 'production';
    }

    getApiUrl(): string {
        return process.env.API_URL;
    }

    getUrl(): string {
        return process.env.APP_URL;
    }

    getName(): string {
        return process.env.APP_NAME;
    }

    getDescription(): string {
        return process.env.APP_DESCRIPTION;
    }

    getAuthor(): string {
        return process.env.APP_AUTHOR;
    }

    getImage(): string {
        return process.env.APP_IMAGE_URL;
    }
}
