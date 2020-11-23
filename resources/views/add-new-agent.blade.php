@extends('adminlte::page')

@section('content')

<div class="cstm-container add-new-agent fit-modal">
	<section>
		<div class="cstm-row cstm-heading">
			<div class="flex-center">
				<h1 class="mx-0">Add New Agent</h1>
			</div>
		</div>
	</section>
	<section class="mt-4">
		<div class="cstm-row">
			<div class="cstm-form">
				
				<form action="" method="">
					<div class="main-icon flex-center">
						<i class="far fa-user"></i>
					</div>
					<div class="input-item">
						<input type="text" name="fullname" placeholder="Name">
						<i class="fas fa-user-edit"></i>
					</div>
					<div class="input-item">
						<input type="text" name="address" placeholder="Address">
						<i class="fas fa-map-marker-alt"></i>
					</div>
					<div class="input-item">
						<input type="number" name="contact_number" placeholder="Contact Number">
						<i class="fas fa-phone-alt"></i>
					</div>
					<div class="input-item">
						<input type="text" name="age" placeholder="Age">
						<i class="far fa-calendar"></i>
					</div>
					<div class="input-item">
						<div class="flex cstm-radio no-border">
							<p>Gender</p>
							<input type="radio" id="male" name="gender" value="male" class="mx-3 ml-5">
							<label for="male" class="my-0">Male</label>
							<input type="radio" id="female" name="gender" value="female" class="mx-3">
							<label for="female" class="my-0">Female</label>
						</div>
						<i class="fas fa-venus-mars"></i>
					</div>

					<input type="submit" name="submit-agent" value="Add">
					
				</form>
			</div>
			
		</div>
	</section>
</div>
@stop