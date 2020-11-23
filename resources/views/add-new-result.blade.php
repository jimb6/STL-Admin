@extends('adminlte::page')

@section('content')

<div class="cstm-container add-new-result fit-modal">
	<section>
		<div class="cstm-row cstm-heading">
			<div class="flex-center">
				<h1 class="mx-0">Add New Result</h1>
			</div>
		</div>
	</section>
	<section class="mt-4">
		<div class="cstm-row">
			<div class="cstm-form">
				
				<form action="" method="">
					<div class="main-icon flex-center">
						<i class="fas fa-trophy"></i>
					</div>
					<div class="input-field s12 py-0 input-item">
						<select class="cstm-select" name="game-category">
							<option value="" disabled selected>Game Type</option>
							<option value="STL 2D">STL 2D</option>
							<option value="STL 3D">STL 3D</option>
							<option value="STL 4D">STL 4D</option>
							<option value="STL Pares">STL Pares</option>
							<option value="STL Pick 3">STL Pick 3</option>
						</select>
						<i class="fas fa-puzzle-piece"></i>
					</div>
					<div class="input-item">
						<input type="text" class="datepicker mr-4" placeholder="Date" name="date">
						<i class="far fa-calendar-alt"></i>
					</div>
					<div class="input-field s12 py-0 input-item">
						<select class="cstm-select" name="draw-time">
							<option value="" disabled selected>Draw Time</option>
							<option value="2PM">2PM</option>
							<option value="5PM">5PM</option>
							<option value="9PM">9PM</option>
						</select>
						<i class="far fa-clock"></i>
					</div>
					<div class="input-item">
						<input type="text" name="combination" placeholder="Combination">
						<i class="fas fa-hashtag"></i>
					</div>

					<input type="submit" name="submit-agent" value="Add">
				</form>
			</div>
			
		</div>
	</section>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('select.cstm-select').formSelect();
		$('.datepicker').datepicker();
	});
</script>
@stop