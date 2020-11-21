@extends('adminlte::page')

@section('content')

<div class="cstm-container winners fit-modal">
	<section>
		<div class="cstm-row cstm-heading col2 flex-between">
			<div class="flex">
				<h1>All Winners</h1>
				<button class="cstm-btn small">Add New</button>
			</div>
			<div id="cstm-table_filter" class="dataTables_filter flex-end cstm-search">
				<p>Search</p>
				<input type="text" name="search" id="cstm-search" class="cstm-input">
			</div>
		</div>

		<?php
			function displayWinners(){
				$output = '';
				$data = array (
							array(
								'bet_id' => 'BET_00000001',
								'combination' => '10-22-23',
								'total_bet' => '10',
								'total_winning' => '5000',
							),
							array(
								'bet_id' => 'BET_00000147',
								'combination' => '10-22-23',
								'total_bet' => '5',
								'total_winning' => '2500',
							),

							array(
								'bet_id' => 'BET_00000420',
								'combination' => '10-22-23',
								'total_bet' => '20',
								'total_winning' => '10000',
							),
							array(
								'bet_id' => 'BET_00000241',
								'combination' => '10-22-23',
								'total_bet' => '25',
								'total_winning' => '12500',
							),

				);
				foreach ($data as $winner) {
					$output .= "	<tr>
									<td><span class='avatar'><i class='fas fa-trophy'></i></span></td>
									<td>". $winner['bet_id'] ."</td>
									<td>". $winner['combination'] ."</td>
									<td>". $winner['total_bet'] ."</td>
									<td>". $winner['total_winning'] ."</td>
								</tr>";
				}
				echo $output;
			}
			
		 ?>

		<div class="cstm-display">
			<table id="cstm-table" class="table table-bordered">
				<thead>
					<tr>
						<th></th>
						<th>Bet Id</th>
						<th>Combination</th>
						<th>Total Bet</th>
						<th>Total Winning</th>
					</tr>
				</thead>
				
				<tbody>
					<?php 
						displayWinners();
					 ?>
				</tbody>
			</table>
		</div>
		
		
		<div class="cstm-table-footer flex-between">
			<div class="flex">
				<div class="input-field col s12">
					<select class="cstm-select">
						<option value="" disabled selected>Game Type</option>
						<option value="1">STL 2D</option>
						<option value="2">STL 3D</option>
						<option value="3">STL 4D</option>
						<option value="4">STL Pares</option>
						<option value="5">STL Pick 3</option>
					</select>
				</div>
				<input type="text" class="datepicker cstm-input" placeholder="Date">
				<input type="text" class="cstm-input" placeholder="Draw Time">
				<button class="cstm-btn small ml-1">Filter</button>
			</div>
			<div>
				<p><b>4</b> bettors won</p>
			</div>
		</div>
	</section>
	
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('#cstm-table').dataTable({
		    "bPaginate": false,
		    "bInfo": false
		});

		oTable = $('#cstm-table').DataTable();
		$('#cstm-search').keyup(function(){
		      oTable.search($(this).val()).draw() ;
		});

		$('select.cstm-select').formSelect();
		$('.datepicker').datepicker();
	});
</script>
@stop