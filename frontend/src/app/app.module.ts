import {NgModule} from '@angular/core';
import {BrowserModule} from '@angular/platform-browser';
import {HttpModule, JsonpModule} from '@angular/http';
import {ToastModule} from 'ng2-toastr/ng2-toastr';
import {AppComponent} from './components/app/app.component';
import {AppRouting, AppRoutingProviders} from './app.routing';
import {SharedModule} from '../shared/shared.module';
import {PhotosModule} from '../photos/photos.module';
import {SignInFormComponent} from './components/signin-form/signin-form.component';
import {SignOutComponent} from './components/signout/signout.component';
import {SideBarComponent} from './components/layout/sidebar/sidebar.component';
import {SearchFormComponent} from './components/layout/search-form/search-form.component';

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
        SearchFormComponent,
        SignInFormComponent,
        SignOutComponent,
    ],
    exports: [
    ],
    providers: [
        AppRoutingProviders,
    ],
    bootstrap: [
        AppComponent,
    ],
})
export class AppModule {
}
