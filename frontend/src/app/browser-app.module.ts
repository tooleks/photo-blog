import {NgModule} from '@angular/core';
import {BrowserModule} from '@angular/platform-browser';
import {AppComponent} from './components';
import {AppModule} from './app.module';
import {BrowserTransferStateModule} from '../modules';
import {BrowserAnimationsModule} from "@angular/platform-browser/animations";

@NgModule({
    bootstrap: [AppComponent],
    imports: [
        BrowserModule.withServerTransition({
            appId: 'my-app-id'
        }),
        BrowserTransferStateModule,
        BrowserAnimationsModule,
        AppModule
    ]
})
export class BrowserAppModule {
}
