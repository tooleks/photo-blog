import {NgModule} from '@angular/core';
import {EnvService} from './services';
// Environment variables file in the project root directory.
import {env} from '../../../env';

@NgModule({
    providers: [
        {provide: EnvService, useFactory: getEnvService},
    ],
})
export class EnvModule {
}

export function getEnvService() {
    return new EnvService(env);
}
