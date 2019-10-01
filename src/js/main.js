"use strict";

import * as Fighters  from './fighters.js';
import * as Fight from './fight.js';


function start() {
    Fighters.init();
    Fight.init();
}

$(document).ready( function () {
	start();
});
