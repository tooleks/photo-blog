import {NgModule} from '@angular/core';
import {BrowserModule} from '@angular/platform-browser';
import {HttpModule, JsonpModule} from '@angular/http';
import {ToastModule} from 'ng2-toastr/ng2-toastr';
import {AppComponent, SideBarComponent, TopBarComponent, SignInFormComponent, SignOutComponent} from './components';
import {AppRouting, AppRoutingProviders} from './app.routing';
import {SharedModule} from '../shared/shared.module';
import {PhotosModule} from '../photos/photos.module';

@NgModule({
    imports: [
        BrowserModule,
        HttpModule,
        JsonpModule,
        ToastModule,
        AppRouting,
        SharedModule,
        PhotosModule,
    ],
    declarations: [
        AppComponent,
        SideBarComponent,
        TopBarComponent,
        SignInFormComponent,
        SignOutComponent,
    ],
    exports: [],
    providers: [
        AppRoutingProviders,
    ],
    bootstrap: [
        AppComponent,
    ],
})
export class AppModule {
}
