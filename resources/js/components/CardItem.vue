<template>
	
	<div class="carditem-container">
		<div v-for="carditem in cards" class="card-item">
			<div>
				<i :class='carditem.icon'></i>
			</div>
			<div>
				<h3>{{ carditem.title }}</h3>
				<p>{{ carditem.description }}</p>
			</div>
			
		</div>
	</div>
	
</template>

<script>
export default {
	name: "CardItem",
	props: ["cards"],
};
</script>


<style scoped>


</style>