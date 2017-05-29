import {Injectable} from '@angular/core';

@Injectable()
export class AppService {
    inDebugMode(): boolean {
        return process.env.NODE_ENV !== 'production';
    }

    getApiUrl(): string {
        return String(process.env.API_URL);
    }

    getUrl(): string {
        return String(process.env.APP_URL);
    }

    getName(): string {
        return String(process.env.APP_NAME);
    }

    getDescription(): string {
        return String(process.env.APP_DESCRIPTION);
    }

    getAuthor(): string {
        return String(process.env.APP_AUTHOR);
    }

    getImage(): string {
        return String(process.env.APP_IMAGE_URL);
    }
}
