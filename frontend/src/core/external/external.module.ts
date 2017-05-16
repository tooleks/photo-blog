import {NgModule} from '@angular/core';
import {Router} from '@angular/router';
import {DetectorModule, EnvironmentDetectorService} from '../detector';
import {EnvModule, EnvService} from '../env';
import {GoogleAnalyticsService} from './services'

@NgModule({
    imports: [
        DetectorModule,
        EnvModule,
    ],
    exports: [
        DetectorModule,
        EnvModule,
    ],
    providers: [
        {
            provide: GoogleAnalyticsService,
            useFactory: getGoogleAnalyticsService,
            deps: [Router, EnvironmentDetectorService, EnvService]
        }
    ],
})
export class ExternalModule {
}

export function getGoogleAnalyticsService(router: Router, environmentDetector: EnvironmentDetectorService, env: EnvService) {
    return new GoogleAnalyticsService(router, environmentDetector, {trackingId: env.get('googleAnalyticsTrackingId')});
}
