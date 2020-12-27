<?php require(__DIR__.'/../layouts/header.html'); ?>

<h1 class="cover-heading">User API</h1><br>
<table>
	<tr>
		<td>Login</td>
		<td>
			<div class="form-control">
				<input type="text" id="login" value="test">
			</div>
		</td>
	</tr>
	<tr>
		<td>Password</td>
		<td>
			<div class="form-control">
				<input type="text" id="pass" value="12345">
			</div>
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<div class="form-control btn-container">
				<div id="get-token" class="btn btn-lg btn-secondary" data-url="/user/token">Get token</div>
				<div id="login-errors" class="alert alert-danger" role="alert"></div>
				<div id="token" class="alert alert-success" role="alert"></div>
			</div>
		</td>
	</tr>
	<tr>
		<td>Token</td>
		<td>
			<div class="form-control">
				<input type="text" id="get-user-token" value="KqxZ8nOvRx">
			</div>
		</td>
	</tr>
	<tr>
		<td>Username</td>
		<td>
			<div class="form-control">
				<input type="text" id="get-user-username" value="ivanov">
			</div>
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<div class="form-control btn-container">
				<div id="get-user-data" class="btn btn-lg btn-secondary" data-url="/user/data">Get user data</div>
				<div id="get-user-data-errors" class="alert alert-danger" role="alert"></div>
				<pre id="get-user-data-result" class="alert alert-success" role="alert"></pre>
			</div>
		</td>
	</tr>
	<tr>
		<td>Name</td>
		<td>
			<div class="form-control">
				<input type="text" id="send-user-name" value="ivanov">
			</div>
		</td>
	</tr>
	<tr>
		<td>Userid</td>
		<td>
			<div class="form-control">
				<input type="text" id="send-user-id" value="23">
			</div>
		</td>
	</tr>
	<tr>
		<td>Token</td>
		<td>
			<div class="form-control">
				<input type="text" id="send-user-token" value="KqxZ8nOvRx">
			</div>
		</td>
	</tr>
	<tr>
		<td>Blocked</td>
		<td>
			<div class="form-control checkbox-container">
				<input type="checkbox" id="send-user-blocked" checked>
			</div>
		</td>
	</tr>
	<tr>
		<td>Active</td>
		<td>
			<div class="form-control checkbox-container">
				<input type="checkbox" id="send-user-active">
			</div>
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<div class="form-control">
				<div id="change-permissions" class="btn btn-lg btn-secondary">Change permissions</div>
				<div id="permissions-hint">Enter by 'id,name' and ';' if multiple:</div>
				<input type="text" id="permissions" placeholder="1,comment;3,update messages">
			</div>
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<div class="form-control btn-container">
				<div id="send-user-data" class="btn btn-lg btn-secondary" data-url="/user/data">Send user data</div>
				<div id="send-user-data-errors" class="alert alert-danger" role="alert"></div>
				<pre id="send-user-data-result" class="alert alert-success" role="alert"></pre>
			</div>
		</td>
	</tr>
</table>

<?php require(__DIR__.'/../layouts/footer.html'); ?>
