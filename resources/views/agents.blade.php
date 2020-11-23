@extends('adminlte::page')

@section('content')

<?php
	function displayAgents(){
		$output = '';
		$data = array (
					array(
						'name' => 'Jonathan Dalisay',
						'onduty' => 'DAVOR_000001',
						'time_in' => '8:00 AM',
						'time_out' => '9:10 PM',
					),
					array(
						'name' => 'Mary Grace Dela Cruz',
						'onduty' => 'DAVOR_000003',
						'time_in' => '8:10 AM',
						'time_out' => '9:30 PM',
					),

					array(
						'name' => 'Claire Ann Guerrero',
						'onduty' => 'DAVOR_000002',
						'time_in' => '8:05 AM',
						'time_out' => '9:15 PM',
					),
					array(
						'name' => 'Richard Acebedo',
						'onduty' => 'DAVOR_000004',
						'time_in' => '8:01 AM',
						'time_out' => '9:20 PM',
					),

		);
		foreach ($data as $agent) {
			$output .= "	<tr>
							<td><span class='avatar'><i class='fas fa-user'></i></span></td>
							<td>". $agent['name'] ."</td>
							<td>". $agent['onduty'] ."</td>
							<td>". $agent['time_in'] ."</td>
							<td>". $agent['time_out'] ."</td>
						</tr>";
		}
		echo $output;
	}
?>

<div class="cstm-container agents fit-modal">
	<section>
		<div class="cstm-row cstm-heading col2 flex-between">
			<div class="flex">
				<h1>All Agents</h1>
				<button class="cstm-btn small">Add New</button>
			</div>
			<div id="cstm-table_filter" class="dataTables_filter flex-end cstm-search">
				<p>Search</p>
				<input type="text" name="search" id="cstm-search" class="cstm-input">
			</div>
		</div>

		<div class="cstm-display">
			<table id="cstm-table" class="table table-bordered">
				<thead>
					<tr>
						<th></th>
						<th>Name</th>
						<th>On Duty</th>
						<th>Time In</th>
						<th>Time Out</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						displayAgents();
					 ?>
				</tbody>
			</table>
		</div>
		
		<div class="cstm-table-filter flex-between">
			<div>
				<input type="text" class="datepicker mr-4" placeholder="Date">
				<button class="cstm-btn small ml-1">Filter</button>
			</div>
			<div>
				<p><b>4/4</b> agents are on duty</p>
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
		$(document).ready(function(){
			$('.datepicker').datepicker();
		});
	});
</script>
@stop