import {NgModule} from '@angular/core';
import {EnvironmentDetectorService, ScreenDetectorService} from './services';

@NgModule({
    providers: [
        EnvironmentDetectorService,
        ScreenDetectorService,
    ],
})
export class DetectorModule {
}
