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
			<input type="text" id="searchField" /><br />
			<input type="checkbox" id="includeDetails" checked/> Include user details<br />
			<button onClick="submitSearch()">Search</button>
		</div>
		<div>
			<div>
				<h5>User</h5>
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
			</div>
			<div>
				<h5>User Details</h5>
				<div>
					<label>First name</label><br />
					<input type="text" id="searchFirstName" />
				</div>
				<div>
					<label>Last name</label><br />
					<input type="text" id="searchLastName" />
				</div>
			</div>
			<div>
				<button>Update</button>
			</div>
			<h5>Response</h5>
			<div id="searchResult">
				Ready.
			</div>
		</div>
	</div>
	<script>
		function resetSearchInputs(){
			document.getElementById('searchUsername').value = "";
			document.getElementById('searchEmail').value = "";
			document.getElementById('searchPassword').value = "";
			document.getElementById('searchFirstName').value = "";
			document.getElementById('searchLastName').value = "";
		}
		function submitSearch(){
			resetSearchInputs();
			let results = [];
			let term = document.getElementById('searchField').value;
			let includeDetails = document.getElementById('includeDetails').checked;
			if(term.length > 3){
				let searchData = new FormData();
				searchData.append("search", term);
				searchData.append("includeDetails", includeDetails);
				fetch("searchUser", {method:'POST', body: searchData})
					.then(data=>data.json())
					.then((data)=>{
						console.log(data);
						if(data.length > 0){
							document.getElementById('searchUsername').value = data[0].username;
							document.getElementById('searchEmail').value = data[0].email;
							document.getElementById('searchPassword').value = data[0].password;
							if(data[0].userDetails){
								let details = data[0].userDetails[0];
								document.getElementById('searchFirstName').value = details.first_name;
								document.getElementById('searchLastName').value = details.last_name;
							}
						}else{
							results.push("User not found");
						}
					});
			}else{
				results.push('Search term must be longer than 3 chars');
			}
			document.getElementById('searchResult').innerHTML = "";
			console.log(results);
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