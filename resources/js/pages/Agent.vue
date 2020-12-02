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
	            agent: {}
	        };
	    },
	    created() {
        	this.fetchData();
	    },
	    methods: {
	        fetchData(page = 1) {
	            axios.get('/api/agents?page='+page)
	                .then((response) => {
	                    this.agents = response.data
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