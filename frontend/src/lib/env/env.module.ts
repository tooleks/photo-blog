import {NgModule} from '@angular/core';
import {EnvService} from './services';
import {env as envVariables} from '../../../env'

@NgModule({
    providers: [
        {
            provide: EnvService,
            useFactory() {
                return new EnvService(envVariables);
            },
        },
    ],
})
export class EnvModule {
}
