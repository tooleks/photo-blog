import jQuery from "jquery";
import Popper from "popper.js";
import "bootstrap";

// Expose the following libraries to the global namespace so they can be used by bootstrap.
window.$ = window.jQuery = jQuery;
window.Popper = Popper;
