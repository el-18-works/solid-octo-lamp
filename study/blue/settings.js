#!/usr/local/bin/node

exports.rc =function (onready) {
	const fs = require('fs');
	const readline = require('readline');
	const rl = readline.createInterface({
	  input: fs.createReadStream('./blue.json'),
	  crlfDelay: Infinity
	});
	var data ="";
	rl.on('line', (line) => {
		data +=line;
	}).on('close', () =>{
		console.log(data);
		onready(JSON.parse(data));
	});
}

