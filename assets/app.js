/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';
import 'bootstrap';
import 'datatables.net';
import 'datatables.net-bs5';


$(document).ready( function () {
    $('#tasksTable').DataTable({
        paging: false,
        searching: false,
        info: false,
        autoWidth: false
    });
} );
