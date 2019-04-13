#!/usr/local/bin/node

exports.rc =function (onready) {
	const fs = require('fs');
	const readline = require('readline');
	const rl = readline.createInterface({
	  input: fs.createReadStream('/usr/local/my/share/blue.json'),
	  crlfDelay: Infinity
	});
	var data ="";
	rl.on('line', (line) => {
		data +=line;
	}).on('close', () =>{
		onready(JSON.parse(data));
	});
}

