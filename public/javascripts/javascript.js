function import(source) {
	var script = document.createElement('script');
	script.type = 'text/javascript';
	script.src = source;
	document.getElementsByTagName('head')[0].appendChild(script);
}

import('/ext4/ext-core-debug.js');
import('/ext4/ext-all-debug.js');