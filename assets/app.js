/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './css/app.scss';
import './css/bootstrap.min.css';
import './css/bootstrap-datepicker.min.css';
import './css/all.css';

// start the Stimulus application
// import './bootstrap';

// Charge Jquery et définit la variable « $ »
const $ = require('jquery');

// Pour avoir JQuery (« $ ») dans toutes les pages
global.$ = global.jQuery = $;

// Charge 'bootstrap'
require('bootstrap');