#!/usr/local/bin/node

require('./settings.js').rc((rc) =>{
	if (process.argv.length != 4) {
		console.log("Usage : %s cmd in-file", path.basename(process.argv[1]));
		process.exit(1);
	}

	const cmds =process.argv[2];
	if ( cmds == "send" )
		rc['cmd'] =0;
	if ( cmds == "url" || cmds == 'chromiumurl' )
		rc['cmd'] =1;
	rc['fpath'] =process.argv[3];
	mycon(rc);
});

function mycon(rc) {
	const util = require("util");
	const path = require("path");
	const fs = require('fs');
	const bluetooth = require("node-bluetooth");
	const device =new bluetooth.DeviceINQ();
	const address =rc['mybodhi_addr'];
	const channel =rc['my_port'];
	const cmd =rc['cmd'];
	const fpath =rc['fpath'];
	const fn =path.basename(fpath);
	//console.log("%s:%d",address,channel);

	fs.stat(fpath, (err,stats) => {
		console.log("%s :", fpath);
		if (err) return console.log(err);
		console.log("stats.size : %d bytes", stats.size);
		const fsz =stats.size;
		if (fsz < 0 ) {
			console.log("stat failed on %s...", fn);
			return;
		}
		const fcnt =Buffer.allocUnsafe(fsz);
		fs.open(fpath, (err,fd) => {
			if (err) return console.log(err);
			fs.read(fd,fcnt,0,fsz,0,(err,bytesRead,buffer) => {
				if (err) return console.log(err);
				buffer.copy(fcnt,0,0,bytesRead);
				console.log("file read : %d/%d bytes", bytesRead,fsz);
			});
		});
		const bfn =Buffer.from(fn);

		bluetooth.connect(address, channel, function(err, connection) {
			if (err) return console.log(err);

			connection.on('data', (buffer) => {
				//console.log('Recieved (connect) : ', buffer.toString());
				if (buffer.toString() == "ok") {
					console.log("")
					process.exit(0);
				}
				const n =Number(buffer.toString());
				const buflen =Math.min(1000,fsz-n);
				const buf =Buffer.allocUnsafe(buflen);
				fcnt.copy(buf, 0, n, n+Math.min(1000,fsz-n));
				connection.write(buf, () => {
					process.stdout.write(util.format('wrote data (%d/%d)\r', n+buflen, fsz));
				});
			});
			connection.write(Buffer.from(String(cmd)), () => {
				console.log('wrote command %d', cmd);
			});
			connection.write(Buffer.from(String(bfn.length)), () => {
				console.log('wrote path length %d', fn.length);
			});
			connection.write(Buffer.from(String(fsz)), () => {
				console.log('wrote data data size %d', fsz);
			});
			connection.write(Buffer.from(fn), () => {
				console.log('wrote file name "%s"', fn);
			});
		});
	});
}

