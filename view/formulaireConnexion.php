<div class="row">
	<div class="col-sm-9">
		<h2>Connexion</h2>
		<form class="form-horizontal" role="form" method="post" action="../connexionUser/">
			<div class="form-group">
				<label for="inputConnexionPseudo" class="col-sm-3 control-label">Pseudo *</label>
				<div class="col-sm-9">
					<input type="text" name="pseudo" class="form-control" id="inputConnexionPseudo" placeholder="Pseudo">
				</div>
			</div>
			<div class="form-group">
				<label for="inputConnexionPassword" class="col-sm-3 control-label">Password *</label>
				<div class="col-sm-9">
					<input type="password" name="password" class="form-control" id="inputConnexionPassword" placeholder="Password">
				</div>
			</div>
			<div class="form-group">
				<label for="inputConnexionConnectAuto" class="col-sm-3 control-label">Connexion automatique</label>
				<div class="col-sm-9">
					<div class="checkbox">
						<input type="checkbox" name="connect_auto" id="inputConnexionConnectAuto" >
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-3 col-sm-9">
					<button type="submit" class="btn btn-default">Connexion</button>
				</div>
			</div>
		</form>
	</div>
</div>