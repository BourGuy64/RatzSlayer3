"use strict";

import * as Fighters  from './fighters.js';
import * as Fight from './fight.js';
import * as Users       from './users.js';


function start() {
    Fighters.init();
    Fight.init();
    Users.init();
}

$(document).ready( function () {
	start();
});
