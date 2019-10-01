"use strict";

import * as Fighters    from './fighters.js';
import * as Users       from './users.js';


function start() {
    Fighters.init();
    Users.init();
}

$(document).ready( function () {
	start();
});
