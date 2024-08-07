<div class="content-wrapper">
	<div class="card shadow">
		<div class="card-body">
			<div class="container">
				<div class="row">
					<div class="col-12 grid-margin">
						<div class="card-body">
							<!-- GET USER -->
							<input type="text" name="user" id="user" value="<?=$name['username'];?>" hidden>
							<div class="container">
									<div class="row mt-5 mx-2">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <label for="newpassword1" class="col-sm-3 col-form-label">New password</label>
                                                <div class="col-sm-5">
                                                    <input type="password" min="1" class="form-control" id="newpassword1" name="newpassword1" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
									<div class="row mt-3 mx-2">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <label for="newpassword2" class="col-sm-3 col-form-label">Confirmation Password</label>
                                                <div class="col-sm-5">
                                                    <input type="password" min="1" class="form-control" id="newpassword2" name="newpassword2" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
							</div>
						</div>
					</div>
					<div class="col-md text-end">
						<button type="button" class="btn btn-primary" onclick="changepassword()">Save</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	function changepassword(){
		var newpassword1 = $('#newpassword1').val();
		var newpassword2 = $('#newpassword2').val();
		var id = <?=$name['id'];?>;
		var user = $('#user').val();

		if(newpassword1 != newpassword2){
			Swal.fire({
				title: "Error",
				text: "Password doesn't match",
				icon: "error"
			});
		}
		else{
			$.ajax({
				url: '<?= base_url('user/changenewpassword'); ?>',
				type: 'post',
				dataType: 'json',
				data: {
					newpassword1, newpassword2, id, user
				},
				success: function(res) {
					if(res == 'success'){
						Swal.fire({
							title: "Success",
							text: "Password has been changed",
							icon: "success"
						}).then(() => {
							window.location.href = '<?=base_url('user/change_password');?>';
						});
					}
					else{
						Swal.fire({
							title: "Error",
							text: "Failed to change password",
							icon: "error"
						}).then(() => {
							window.location.href = '<?=base_url('user/change_password');?>';
						});
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					console.error(xhr.statusText);
				}
			});
		}
	}
</script>