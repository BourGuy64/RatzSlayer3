"use strict";

import * as Fighters    from './fighters.js';
import * as Fight       from './fight.js';
import * as Users       from './users.js';
import * as Ui          from './ui.js';


function start() {
    Fighters.init();
    Fight.init();
    Users.init();
    Ui.init();
}

$(document).ready( function () {
	start();
});
