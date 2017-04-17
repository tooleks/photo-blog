import {NgModule} from '@angular/core';
import {EnvService} from './services';
// Environment variables file in the project root directory.
import {env} from '../../../env';

@NgModule({
    providers: [
        {
            provide: EnvService,
            useFactory() {
                return new EnvService(env);
            },
        },
    ],
})
export class EnvModule {
}
