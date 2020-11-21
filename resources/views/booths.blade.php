@extends('adminlte::page')

@section('content')

<div class="cstm-container booths fit-modal">
	<section>
		<div class="cstm-row cstm-heading col2 flex-between">
			<div class="flex">
				<h1>All Booths</h1>
				<button class="cstm-btn small">Add New</button>
			</div>
			<div id="cstm-table_filter" class="dataTables_filter flex-end cstm-search">
				<p>Search</p>
				<input type="text" name="search" id="cstm-search" class="cstm-input">
			</div>
		</div>

		<?php
			function displayBooths(){
				$output = '';
				$data = array (
							array(
								'code' => 'DAVOR_000001',
								'is_active' => True,
								'agent_on_duty' => 'Jonathan Dalisay',
							),
							array(
								'code' => 'DAVOR_000002',
								'is_active' => True,
								'agent_on_duty' => 'Claire Ann Guerrero',
							),
							array(
								'code' => 'DAVOR_000003',
								'is_active' => True,
								'agent_on_duty' => 'Mary Grace Dela Cruz',
							),
							array(
								'code' => 'DAVOR_000004',
								'is_active' => True,
								'agent_on_duty' => 'Richard Acebedo',
							),
							array(
								'code' => 'DAVOR_000005',
								'is_active' => False,
								'agent_on_duty' => '-',
							),

				);
				foreach ($data as $booth) {
					$isactive = ($booth['is_active']) ? "<span class='yes'>Y</span>" : "<span class='no'>N</span>";
					$output .= "	<tr>
									<td><span class='avatar'><i class='fas fa-store'></i></span></td>
									<td>". $booth['code'] ."</td>
									<td>". $isactive ."</td>
									<td>". $booth['agent_on_duty'] ."</td>
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
						<th>Booth Code</th>
						<th>Is Active</th>
						<th>Agent On Duty</th>
					</tr>
				</thead>
				
				<tbody>
					<?php 
						displayBooths();
					 ?>
				</tbody>
			</table>
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
		</div>
		
		
		<div class="cstm-table-footer flex-between">
			<div>
				<!-- <input type="text" class="datepicker cstm-input">
				<button class="cstm-btn small ml-1">Filter</button> -->
			</div>
			<div>
				<p><b>4/5</b> booths are active</p>
			</div>
		</div>
	</section>
	
</div>


@stop