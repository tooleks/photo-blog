import Vue from "vue";
import {reverseGeocode} from "esri-leaflet-geocoder";
import LocationPopup from "./LocationPopup";

const LocationPopupComponent = Vue.extend(LocationPopup);

export function provideLocationPopupHtml({location = {}} = {}) {
    return new Promise((resolve) => {
        reverseGeocode()
            .latlng(location)
            .run((error, result) => {
                let address;
                if (!error) {
                    address = result.address.LongLabel;
                }
                const propsData = {lat: location.lat, lng: location.lng, address};
                const popup = new LocationPopupComponent({propsData});
                const html = popup.$mount().$el.outerHTML;
                resolve(html);
            });
    });
}
