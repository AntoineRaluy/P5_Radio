// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.css';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';
const $ = require('jquery');
require('bootstrap');


// create global $ and jQuery variables
// global.$ = global.jQuery = $;

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});