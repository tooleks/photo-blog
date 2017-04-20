import {NgModule} from '@angular/core';
import {Router} from '@angular/router';
import {EnvModule, EnvService} from '../env';
import {GoogleAnalyticsService} from './services'

@NgModule({
    imports: [
        EnvModule,
    ],
    exports: [
        EnvModule,
    ],
    providers: [
        {provide: GoogleAnalyticsService, useFactory: getGoogleAnalyticsService, deps: [Router, EnvService]}
    ],
})
export class ExternalModule {
}

export function getGoogleAnalyticsService(router:Router, env:EnvService) {
    return new GoogleAnalyticsService(router, {trackingId: env.get('googleAnalyticsTrackingId')})
}
