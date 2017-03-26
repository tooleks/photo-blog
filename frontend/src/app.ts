import {platformBrowserDynamic} from '@angular/platform-browser-dynamic';
import {enableProdMode} from '@angular/core';
import {AppModule} from './app/app.module';

import '../assets/app/css/overrides.css';

const platform = platformBrowserDynamic();

if (process.env.ENV === 'production') {
    enableProdMode();
}

platform.bootstrapModule(AppModule);
