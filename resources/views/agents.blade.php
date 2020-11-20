@extends('adminlte::page')

@section('content')

<div class="cstm-container">
	<section>
		<div class="cstm-row cstm-heading col2 flex-between">
			<div class="flex">
				<h1>All Agents</h1>
				<button class="cstm-btn small">Add New</button>
			</div>
			<div class="flex-end cstm-search">
				<p>Search</p>
				<input type="text" name="search">
			</div>
		</div>

		<?php
			function displayAgents(){
				$output = '';
				$data = array (
							array(
								'name' => 'Jonathan Dalisay',
								'collection_today' => '17,500',
								'onduty' => 'DAVOR_0001',
								'time_in' => '8:00 AM',
								'time_out' => '9:10 PM',
							),
							array(
								'name' => 'Mary Grace Dela Cruz',
								'collection_today' => '100,365',
								'onduty' => 'DAVOR_0003',
								'time_in' => '8:10 AM',
								'time_out' => '9:30 PM',
							),

							array(
								'name' => 'Claire Ann Guerrero',
								'collection_today' => '38,880',
								'onduty' => 'DAVOR_0002',
								'time_in' => '8:05 AM',
								'time_out' => '9:15 PM',
							),
							array(
								'name' => 'Richard Acebedo',
								'collection_today' => '25, 010',
								'onduty' => 'DAVOR_0004',
								'time_in' => '8:01 AM',
								'time_out' => '9:20 PM',
							),

				);
				foreach ($data as $agent) {
					$output .= "	<tr>
									<td><span class='avatar'><i class='fas fa-user'></i></span></td>
									<td>". $agent['name'] ."</td>
									<td>". $agent['collection_today'] ."</td>
									<td>". $agent['onduty'] ."</td>
									<td>". $agent['time_in'] ."</td>
									<td>". $agent['time_out'] ."</td>
								</tr>";
				}
				echo $output;
			}
			
		 ?>

		<div class="cstm-display">
			<table>
				<tr>
					<th></th>
					<th>Name</th>
					<th>Collection Today</th>
					<th>On Duty</th>
					<th>Time In</th>
					<th>Time Out</th>
				</tr>
				<!-- <tr>
					<td><span class="avatar"><i class="fas fa-user"></i></span></td>
					<td>Name</td>
					<td>Collection Today</td>
					<td>On Duty</td>
					<td>Time In</td>
					<td>Time Out</td>
				</tr>
				<tr>
					<td><span class="avatar"><i class="fas fa-user"></i></span></td>
					<td>Name</td>
					<td>Collection Today</td>
					<td>On Duty</td>
					<td>Time In</td>
					<td>Time Out</td>
				</tr>
				<tr>
					<td><span class="avatar"><i class="fas fa-user"></i></span></td>
					<td>Name</td>
					<td>Collection Today</td>
					<td>On Duty</td>
					<td>Time In</td>
					<td>Time Out</td>
				</tr>
				<tr>
					<td><span class="avatar"><i class="fas fa-user"></i></span></td>
					<td>Name</td>
					<td>Collection Today</td>
					<td>On Duty</td>
					<td>Time In</td>
					<td>Time Out</td>
				</tr> -->
				<?php 
					displayAgents();
				 ?>
			</table>
		</div>
	</section>
	
</div>


@stop