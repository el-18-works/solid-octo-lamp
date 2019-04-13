#!/usr/local/bin/node

const path = require("path");
const util = require("util");

function log( s ) {
	process.stderr.write(s);
}

require('./settings.js').rc((rc) =>{
	if (process.argv.length != 4) {
		log(util.format("Usage : %s cmd in-file\n", path.basename(process.argv[1])));
		process.exit(1);
	}

	const cmds =process.argv[2];
	if ( cmds == "send" )
		rc['cmd'] =0;
	if ( cmds == "url" || cmds == 'chromiumurl' )
		rc['cmd'] =1;
	if ( cmds == "clip" || cmds == 'clipboard' )
		rc['cmd'] =2;
	if ( cmds == "pipe" || cmds == 'shell' )
		rc['cmd'] =3;
	rc['fpath'] =process.argv[3];
	mycon(rc);
});

function mycon(rc) {
	const fs = require('fs');
	const bluetooth = require("node-bluetooth");
	const device =new bluetooth.DeviceINQ();
	const address =rc['mybodhi_addr'];
	const channel =rc['my_port'];
	const cmd =rc['cmd'];
	const fpath =rc['fpath'];
	const fn =path.basename(fpath);
	//log(util.format("%s:%d\n",address,channel));

	fs.stat(fpath, (err,stats) => {
		log(util.format("%s :\n", fpath));
		if (err) return log(err);
		log(util.format("stats.size : %d bytes\n", stats.size));
		const fsz =stats.size;
		if (fsz < 0 ) {
			log(util.format("stat failed on %s...\n", fn));
			return;
		}
		const fcnt =Buffer.allocUnsafe(fsz);
		fs.open(fpath, (err,fd) => {
			if (err) return log(err);
			fs.read(fd,fcnt,0,fsz,0,(err,bytesRead,buffer) => {
				if (err) return log(err);
				buffer.copy(fcnt,0,0,bytesRead);
				log(util.format("file read : %d/%d bytes\n", bytesRead,fsz));
			});
		});
		const bfn =Buffer.from(fn);

		bluetooth.connect(address, channel, function(err, connection) {
			if (err) return log(err);

			var rei =false;
			var cnt=0;
			connection.on('data', (buffer) => {
				//log(util.format('Recieved (connect) : \n', buffer.toString()));
				if (buffer.toString() == "ok") {
					//log("")
					process.exit(0);
				} else if (buffer.toString() == "re:") {
					log("")
					rei =true;
				} else if (rei) {
					process.stdout.write(buffer.toString());
					const buf =Buffer.from(String(buffer.length));
					connection.write(buf, () => {
						process.stderr.write(util.format('recv data (%d)\r', buffer.length));
					});
				} else {
					const n =Number(buffer.toString());
					const buflen =Math.min(1000,fsz-n);
					const buf =Buffer.allocUnsafe(buflen);
					fcnt.copy(buf, 0, n, n+Math.min(1000,fsz-n));
					connection.write(buf, () => {
						process.stderr.write(util.format('wrote data (%d/%d)\r', n+buflen, fsz));
					});
				}
			});
			connection.write(Buffer.from(String(cmd)), () => {
				log(util.format('wrote command %d\n', cmd));
			});
			connection.write(Buffer.from(String(bfn.length)), () => {
				log(util.format('wrote path length %d\n', fn.length));
			});
			connection.write(Buffer.from(String(fsz)), () => {
				log(util.format('wrote data data size %d\n', fsz));
			});
			connection.write(Buffer.from(fn), () => {
				log(util.format('wrote file name "%s"\n', fn));
			});
		});
	});
}

