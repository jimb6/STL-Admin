<template>

    <div class="dashboard">
        <Card v-bind:cards="cards" />
    </div>

</template>

<script>
import Card from "../components/Card";

export default {
    name: "Dashboard",
    components: {
        Card,
    },
    data() {
        return {
            cards : [
                {
                    id: 1,
                    icon: "fas fa-users",
                    title: "Total Agents",
                    description: "1000",
                },
                {
                    id: 2,
                    icon: "fas fa-store",
                    title: "Total Booths",
                    description: "450",
                },
                {
                    id: 3,
                    icon: "fas fa-trophy",
                    title: "Total Winners",
                    description: "125",
                },
                {
                    id: 4,
                    icon: "fas fa-briefcase",
                    title: "Total Collections",
                    description: "50000",
                },
            ],
        };
    },
};
</script>

<style>

.card {
    background: unset;
    box-shadow: unset;
}
.carditem-container {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr;
    grid-gap: 40px;
    padding: 20px 0;
}
.card-item {
    position: relative;
    padding: 20px;
    background: #fff;
    display: flex;
    border-radius: 5px;
    box-shadow: 0 0 5px 1px rgba(0,0,0,.15);
}
.card-item>div:first-child {
    width: 40%;
}
.card-item>div:last-child {
    width: 60%;
}
.card-item i {
    position: absolute;
    width: 30%;
    height: 100px;
    background: #E63946;
    top: -20px;
    text-align: center;
    line-height: 100px;
    font-size: 35px;
    color: #fff;
    border-radius: 5px;
}
.card-item h3 {
    text-transform: uppercase;
    font-size: 18px;
    font-weight: 300;
    letter-spacing: 1px;
    text-align: right;
}
.card-item p {
    font-size: 40px;
    text-align: right;
}

</style>
