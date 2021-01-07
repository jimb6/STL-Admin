<template>

	<div class="card ">
		<CardItem v-bind:cards="cards"/>
	</div>


</template>

<script>
import CardItem from './CardItem';

export default {
	name: "Card",
	props: ["cards"],
	components: {
        CardItem,
    },
    data() {
        return {

        };
    },
};
</script>


<style scoped>

</style>
