<template>
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
		</section>

		<section>
			<Table :labels="agentLabels" :contents="agents" :withIcon="withIcon"/>
		</section>
		
	</div>
</template>


<script>
	import Table from '../components/Table';
	export default {
	    name: "agent",
	    components: {
	        Table,
	    },
	    data() {
			return {
				agentLabels: ["", "Name", "On Duty", "Time In", "Time out"],
				agents: [
                    { iconClass:'fas fa-user', name: 'Jonathan Dalisay', onduty: 'DAVOR_000001', time_in: '8:00 AM', time_out: '9:10 PM' },
                    { iconClass:'fas fa-user', name: 'Mary Grace Dela Cruz', onduty: 'DAVOR_000003', time_in: '8:10 AM', time_out: '9:30 PM' },
                    { iconClass:'fas fa-user', name: 'Claire Ann Guerrero', onduty: 'DAVOR_000002', time_in: '8:05 AM', time_out: '9:15 PM' },
                    { iconClass:'fas fa-user', name: 'Richard Acebedo', onduty: 'DAVOR_000004', time_in: '8:01 AM', time_out: '9:20 PM' }
				],
				withIcon: true,
	        };
	    },
	    created() {
	    },
	    methods: {
	        fetchData(page = 1) {
	            axios.get('/api/v1/agents?page='+page)
	                .then((response) => {
	                    this.active = response.data.activeAgents.length
                        this.agentCount = response.data.agents.length
                        console.log(response)
	                })
	                .catch(function (error) {
	                    console.log(error);
	                });
	        },
	    }
	}
</script>



<style>

</style>
