const XMLHttpRequest = require("xmlhttprequest").XMLHttpRequest;
const { Client } = require("pg");

const client = new Client({
	connectionString: process.env.DATABASE_URL,
	ssl: true
});

client.connect();

var apiParams = [
	[],
	["&amount=6&difficulty=easy"], //Monday 6
	["&amount=4&difficulty=easy", "&amount=2&difficulty=medium"], //Tuesday 4-2
	["&amount=3&difficulty=easy", "&amount=3&difficulty=medium"], //Wednesday 3-3
	["&amount=3&difficulty=easy", "&amount=2&difficulty=medium", "&amount=1&difficulty=hard"], //Thursday 3-2-1
	["&amount=2&difficulty=easy", "&amount=2&difficulty=medium", "&amount=2&difficulty=hard"], //Friday 2-2-2
	[]
];

function callOpenTriviaDB() {
	var apiEndpoint = "https://opentdb.com/api.php?category=9"; //General Knowledge
	var today = new Date();

	if(today.getDay() === 0 || today.getDay() == 6) {
		console.log("No trivia on the weekend!");
		return;
	}

	var response = [];

	for(var d = 0; d < apiParams[today.getDay()].length; d++) {
		var url = apiEndpoint + apiParams[today.getDay()][d];
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function() {
			if(this.readyState == 4 && this.status == 200){
				response.push(JSON.parse(this.responseText));
			}
		}
		xhr.open("GET", url, false);
		xhr.send();
	}

	var myQuery = "INSERT INTO questions (day, data) VALUES ('" + today.getFullYear() + "-" + (today.getMonth() + 1) + "-" + today.getDate() + "', '" + JSON.stringify(response) + "');";
	
	client.query(myQuery, (err, res) => {
		if(err) throw err;
		for(let row of res.rows) {
			console.log(JSON.stringify(row));
		}
		client.end();
	});
		
}

callOpenTriviaDB();