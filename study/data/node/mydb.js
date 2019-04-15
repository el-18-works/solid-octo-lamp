#!/usr/local/my/bin/node

/**
 */
function data(q, cargar, c=null) {
	const http =require("https");
	const url =require("url");
	const querystring =require("querystring");
	this.callback =cargar,
	this.post = function(q, c, callback) {
		const postdata ={"q" : JSON.stringify(q)};
		if (c) 
			postdata.c =JSON.stringify(c);
		const post =querystring.stringify(postdata);
		const link =url.parse("https://ens.l18.work/my/sql/");
		link.method ='POST';
		link.headers = {
			'Content-Type': 'application/x-www-form-urlencoded',
			'Content-Length' : Buffer.byteLength(post)
		};
		const req =http.request(link, (res) => {
			if (res.statusCode != 200) {
//				//console.log(`STATUS : ${res.statusCode}`);
//				//console.log(`HEADERS : ${JSON.stringify(res.headers)}`);
				callback(null, res.statusCode);
			} else {
				res.setEncoding('utf8');
				res.on('data', (chunk) => {
					try {
						callback(JSON.parse(chunk), res.statusCode);
					} catch (e) {
						console.log(`Error : ${e}`);
						console.log(` BODY : ${chunk}`);
					}
				}).on('end', () => {
//					//console.log('no more data');
				});
			}
		});
		req.on('error', (e) => {
			console.log(`problema con propuesta : ${e.message}`);
		});
		req.write(post);
		req.end();
	},
	this.f =function(data, statusCode) {
		switch(statusCode) {
		case 500 :
			//console.log("error");
			this.post(q, c, this.f);
			break;
		case 200 :
			this.callback(JSON.stringify(data));
			break;
		default :
			console.log("Error : %d", statusCode);
			console.log(data);
		}
	}
	this.post(q, c, this.f);
}

function myCargar(data) {
	console.log("my cargado : "+data);
};
data("select * from user_summary", myCargar, {"user" : "luckxa", "pass" : "my", "schema" : "sys"});
data("select * from user_summary", myCargar);
//data("select * from user_summary", {"user" : "luckxa", "pass" : "my", "schema" : "sys"}, myCargar);
//data("select * from event", {"user" : "luckxa", "pass" : "my", "db" : "menagerie"}, myCargar);

/*
http.get("http://localhost/index.php", function(res) {
    console.log("Got response: " + res.statusCode);
	res.on('data', (chunk) => {
		console.log(`BODY : ${chunk}`);
	});
}).on('error', function(e) {
    console.log("Got error: " + e.message);
});
*/


//exports.modulatio = function() ...
/**
 */
function modulatio() {
/*
	const fs = require('fs');
	const readline = require('readline');

	async function processLineByLine() {
	  const fileStream = fs.createReadStream('input.txt');

	  const rl = readline.createInterface({
		input: fileStream,
		crlfDelay: Infinity
	  });
	  // Note: we use the crlfDelay option to recognize all instances of CR LF
	  // ('\r\n') in input.txt as a single line break.

	  for await (const line of rl) {
		// Each line in input.txt will be successively available here as `line`.
		console.log(`Line from file: ${line}`);
	  }
	}

	processLineByLine();
*/
}


