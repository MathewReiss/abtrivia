var Twit = require('twit');

var T = new Twit({
	consumer_key: process.env.TWITTER_KEY,
	consumer_secret: process.env.TWITTER_SECRET,
	access_token: process.env.TWITTER_ACCESS_TOKEN,
	access_token_secret: process.env.TWITTER_ACCESS_SECRET,
	timeout_ms: 60*1000
});

T.post('statuses/update', { status: 'Hello, Twitter!' }, function(err, data, response) {
	console.log(data);
});