/**
 * q : quaestionis.
 * c : connexionis.
 */
exports.quaestio =function(q, c, cargar) {
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
						cargar(JSON.parse(chunk), res.statusCode);
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
		//case 500 :
			//console.log("error");
			//quaestio.post(q, c, this.f);
			//break;
		case 200 :
			this.callback(JSON.stringify(data));
			break;
		default :
			//console.log(`Error : %d ${data}`, statusCode);
		}
	}
	this.post(q, c, this.f);
}

