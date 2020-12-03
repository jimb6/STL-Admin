<template>
	<Table />
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
	            agentCount: {},
	            active: 0,
	        };
	    },
	    mounted() {
        	this.fetchData();
	    },
	    methods: {
	        fetchData(page = 1) {
	            axios.get('/api/agents?page='+page)
	                .then((response) => {
	                    this.active = response.data.activeAgents.length
                        this.agentCount = response.data.agents.length
	                })
	                .catch(function (error) {
	                    console.log(error);
	                });
	        },
	    }
	};

</script>



<style>

</style>
