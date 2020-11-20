/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/fileCss/app.scss';
import './styles/fileCss/login.scss'
import './styles/fileCss/dashbord.scss'
import './styles/fileCss/article_show.scss'
import './styles/fileCss/index.scss'
import './styles/fileCss/info_perso.scss'
import 'bootstrap'
import 'bootstrap/dist/css/bootstrap.min.css'


//JS files

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
import $ from 'jquery';
global.$ = $;
import './styles/filejs/login.js'
import './styles/filejs/dashbord.js'
// import './styles/filejs/artile_show.js'
import './styles/filejs/index.js'
import './styles/filejs/info_perso.js'


console.log('Hello Webpack Encore! Edit me in assets/app.js');
