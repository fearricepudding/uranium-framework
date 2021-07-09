<body>
	<style>
		h1, h2, h3, h4, h5, h6{
			padding:0;
			margin:0;
			margin-top:15px;
		}
		.section{
			border: solid black 1px;
			padding:10px;
		}
	</style>
	<h4>Create a user account</h4>
	<div class="section form">
		<div class="input">
			<label>username</label><br />
			<input type="text" id="username" />
		</div>
		<div class="input">
			<label>Email address</label><br />
			<input type="text" id="email" />
		</div>
		<div class="input">
			<label>Password</label><br />
			<input type="text" id="password" />
		</div>
		<button onClick="submitCreate()">Submit</button>
		<div>
			<h5>Result</h5>
			<div id="createResult">
				Ready.
			</div>
		</div>
	</div>
	<h4>Search a user account</h4>
	<div class="section search">
		<div class="bar">
			<label for="searchfield">Search for a user</label><br />
			<input type="text" id="searchField" /><button onClick="submitSearch()">Search</button>
		</div>
		<div>
			<h5>Result</h5>
			<div>
				<div>
					<label>Username</label><br />
					<input type="text" id="searchUsername" />
				</div>
				<div>
					<label>Email</label><br />
					<input type="text" id="searchEmail" />
				</div>
				<div>
					<label>Password</label><br />
					<input type="text" id="searchPassword" />
				</div>
				<div>
					<button>Update</button>
				</div>
			</div>
			<div id="searchResult">
				Ready.
			</div>
		</div>
	</div>
	<script>
		function submitSearch(){
			let results = [];
			let term = document.getElementById('searchField').value;
			if(term.length > 3){
				let searchData = new FormData();
				searchData.append("search", term);
				fetch("searchUser", {method:'POST', body: searchData})
					.then(data=>data.json())
					.then((data)=>{
						console.log(data);
						results.push(data);
						document.getElementById('searchUsername').value = data[0].username;
						document.getElementById('searchEmail').value = data[0].email;
						document.getElementById('searchPassword').value = data[0].password;
					});
			}else{
				results.push('Search term must be longer than 3 chars');
			}
			document.getElementById('searchResult').innerHTML = "";
			for(let i = 0; i < results.length; i++){
				document.getElementById('searchResult').innerHTML += results[i];
			}
		}
		function submitCreate(){
			console.log("Creating user");
			let results = [];
			let username = document.getElementById('username').value;
			let email = document.getElementById('email').value;
			let password = document.getElementById('password').value;
			if((username.length > 3) && (password.length > 3) && (email.length > 3)){
				let createData = new FormData();
				createData.append("username", username);
				createData.append("email", email);
				createData.append("password", password);
				fetch("createUser", {method:'POST', body:createData})
					.then(data=>data.json())
					.then((data)=>{
						console.log(data);
						results.push(data);
					});
			}else{
				results.push('Values must be londer than 3 chars');
			}
			document.getElementById('createResult').innerHTML = "";
			for(let i = 0; i < results.length; i++){
				document.getElementById('createResult').innerHTML += '<br />'+results[i];
			};
		}
	</script>
</body>