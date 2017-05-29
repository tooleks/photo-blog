import {NgModule} from '@angular/core';
import {Router} from '@angular/router';
import {DetectorModule, EnvironmentDetectorService} from '../detector';
import {GoogleAnalyticsService} from './services'

@NgModule({
    imports: [
        DetectorModule,
    ],
    exports: [
        DetectorModule,
    ],
    providers: [
        {
            provide: GoogleAnalyticsService,
            useFactory: getGoogleAnalyticsService,
            deps: [Router, EnvironmentDetectorService]
        }
    ],
})
export class ExternalModule {
}

export function getGoogleAnalyticsService(router: Router, environmentDetector: EnvironmentDetectorService) {
    return new GoogleAnalyticsService(router, environmentDetector, {trackingId: process.env.GOOGLE_ANALYTICS_TRACKING_ID});
}
