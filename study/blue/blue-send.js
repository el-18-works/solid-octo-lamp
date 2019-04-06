#!/usr/local/bin/node

const bluetooth = require("node-bluetooth");
const device =new bluetooth.DeviceINQ();
const util = require("util");
const path = require("path");
address ='00-1b-dc-05-b8-af';
channel =1;

if (process.argv.length != 3) {
	console.log("Usage : %s in-file", basename(process.argv[1]));
	process.exit(1);
}
const fpath =process.argv[2];
const fn =path.basename(fpath);
fs =require("fs");
fs.stat(fpath, (err,stats) => {
	if (err) return console.log(err);
	console.log("stats", stats.size);
	const fsz =stats.size;
	if (fsz < 0 ) {
		console.log("stats failed on %s...", fn);
		return;
	}
	const fcnt =Buffer.allocUnsafe(fsz);
	fs.open(fpath, (err,fd) => {
		if (err) return console.log(err);
		fs.read(fd,fcnt,0,fsz,0,(err,bytesRead,buffer) => {
			if (err) return console.log(err);
			buffer.copy(fcnt,0,0,bytesRead);
			console.log("read %d/%d", bytesRead,fsz);
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

