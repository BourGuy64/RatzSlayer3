"use strict";

import * as Characters  from './characters.js';
import * as Monsters    from './monsters.js';


function start() {
    Characters.init();
    Monsters.init();
}

$(document).ready( function () {
	start();
});
