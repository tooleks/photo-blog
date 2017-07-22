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

    getKeywords(): string {
        return process.env.APP_KEYWORDS;
    }

    getAuthor(): string {
        return process.env.APP_AUTHOR;
    }

    getAuthorImage(): string {
        return process.env.APP_AUTHOR_IMAGE_URL;
    }

    getImage(): string {
        return process.env.APP_IMAGE_URL;
    }

    getLandingPageImage(): string {
        return process.env.LANDING_PAGE_IMAGE_URL;
    }

    getLandingPageColor(): string {
        return process.env.LANDING_PAGE_COLOR;
    }
}
