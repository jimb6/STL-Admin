<template>
    <div class="dashboard">
        <div class="row">
            <div class="col-lg-12">
                <Card v-bind:cards="cards"/>
            </div>

            <div class="col-lg-9">
                <div class="cstm-linechart">
                    <div class="flex-between linechart-title">
                        <h3 class="">Collections</h3>
                        <p>{{ totalCollection }}</p>
                    </div>

                    <LineChart
                        :chartData="arrCollections"
                        :options="chartOptions"
                        :chartColors="collectionsChartColors"
                        label=""
                    />
                </div>
            </div>
            <div class="col-3">
                <!--                <NotificationCard></NotificationCard>-->
            </div>
        </div>
    </div>
</template>

<script>
import Card from "../components/Card";
import LineChart from "../components/LineChart";
import NotificationCard from "../components/NotificationCard";

export default {
    name: "Dashboard",
    components: {
        NotificationCard,
        Card,
        LineChart
    },
    data() {
        return {
            cards: [
                {
                    id: 1,
                    icon: "fas fa-users",
                    title: "Active Agents",
                    description: "",
                },
                {
                    id: 2,
                    icon: "fas fa-store",
                    title: "Active Booths",
                    description: "",
                },
                {
                    id: 3,
                    icon: "fas fa-trophy",
                    title: "Today Winners",
                    description: "",
                },
                {
                    id: 4,
                    icon: "fas fa-briefcase",
                    title: "Today Collections",
                    description: "",
                },
            ],
            arrCollections: [],
            collectionsChartColors: {
                borderColor: "#1d3557",
                pointBorderColor: "#1d3557",
                pointBackgroundColor: "#f1faee",
                backgroundColor: "#a8dadc"
            },
            totalCollection: 0,
            chartOptions: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                tooltips: {
                    callbacks: {
                        label: function (tooltipItem) {
                            return tooltipItem.yLabel;
                        }
                    }
                }
            },
            activeAgents: 0,
            activeBooths: 0,
            headers: {
                'content-type': 'application-json',
                'accept': 'application-json',
            }
        };
    },

    async created() {
        console.log("Created");
        const data = {
            collections: [
                {date: "Dec 1", total: "300000"},
                {date: "Dec 2", total: "350000"},
                {date: "Dec 3", total: "250000"},
                {date: "Dec 4", total: "370350"},
                {date: "Dec 5", total: "208100"},
                {date: "Dec 6", total: "208900"},
                {date: "Dec 7", total: "304420"},
                {date: "Dec 8", total: "300100"},
                {date: "Dec 9", total: "389300.50"},
                {date: "Dec 10", total: "357010"},
                {date: "Dec 11", total: "300000"},
                {date: "Dec 12", total: "350000"},
                {date: "Dec 13", total: "250000"},
                {date: "Dec 14", total: "370350"},
                {date: "Dec 15", total: "258100"},
                {date: "Dec 16", total: "208900"},
                {date: "Dec 17", total: "304420"},
                {date: "Dec 18", total: "300100"},
                {date: "Dec 19", total: "389300"},
                {date: "Dec 20", total: "357010"},
                {date: "Dec 21", total: "300000"},
                {date: "Dec 22", total: "350000"},
                {date: "Dec 23", total: "250000"},
                {date: "Dec 24", total: "370350"},
                {date: "Dec 25", total: "0"},
                {date: "Dec 26", total: "208900"},
                {date: "Dec 27", total: "304420"},
                {date: "Dec 28", total: "300100"},
                {date: "Dec 29", total: "389300"},
                {date: "Dec 30", total: "357010"},
                {date: "Dec 31", total: "357010"},
            ],
        };
        data["collections"].forEach(d => {
            const date = d.date;
            const {
                total,
            } = d;
            this.arrCollections.push({date, total: total});
            this.totalCollection += parseFloat(total);
        });
        this.totalCollection = this.formatMoney(this.totalCollection);
        // await this.getActiveAgents();
        // await this.getActiveBooths();
        await this.getDailyTotalCollections();
        await this.getPermission();
    },
    methods: {
        async getActiveAgents() {
            // axios.get('/sanctum/csrf-cookie').then(response => {
            const response = await axios.get('/agents/count/').catch(error => {
                console.log(error)
            })
            console.log(response);
        },

        async getActiveBooths() {
            const response = await axios.get('/booths/count/',
                {
                    headers: {
                        'content-type': 'application/json',
                        'accept': 'application/json'
                    }
                }).catch(error => {
                console.log(error)
            });
            console.log(response.data)
        },

        async getDailyTotalCollections() {
            const response = await axios.get('/api/user/', {
                headers: {
                    'content-type': 'application/json',
                    'accept': 'application/json'
                }
            }).catch(error => console.log(error));
            console.log("Sa Total ni ha!", response)
        },

        async getPermission(){
            const response = await axios.get('/api/permissions').catch(err => console.log(err))
            console.log(response)
        },


        formatMoney(money) {
            money = (Math.round(money * 100) / 100).toFixed(2);
            return money.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
    }
};
</script>

<style>

.dashboard .card {
    background: unset;
    box-shadow: unset;
}

.dashboard .carditem-container {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr;
    grid-gap: 40px;
    padding: 20px 0;
}

.dashboard .card-item {
    position: relative;
    padding: 20px;
    background: #fff;
    display: flex;
    border-radius: 5px;
    box-shadow: 0 0 5px 1px rgba(0, 0, 0, .15);
}

.dashboard .card-item > div:first-child {
    width: 40%;
}

.dashboard .card-item > div:last-child {
    width: 60%;
}

.dashboard .card-item i {
    position: absolute;
    width: 30%;
    height: 100px;
    top: -20px;
    text-align: center;
    line-height: 100px;
    font-size: 35px;
    color: #fff;
    border-radius: 5px;
    background: linear-gradient(200DEG, rgb(75, 108, 183), rgb(24, 40, 72));
}

.dashboard .card-item h3 {
    text-transform: uppercase;
    font-size: 18px;
    font-weight: 300;
    letter-spacing: 1px;
    text-align: right;
    letter-spacing: 1px;
}

.dashboard .card-item p {
    font-size: 40px;
    text-align: right;
}

.dashboard .cstm-linechart {
    padding: 40px 20px;
    background: #fff;
    border-radius: 5px;
    box-shadow: 0 0 5px 1px rgba(0, 0, 0, .15);
}

.linechart-title {
    position: relative;
    padding-bottom: 20px;
    margin-bottom: 30px;
    border-bottom: 1px solid #f3f3f3;
}

.linechart-title h3 {
    text-transform: uppercase;
    font-weight: 300;
    letter-spacing: 1px;
    margin: 0;
}

.linechart-title p {
    padding: 10px 20px;
    background: #a8dadc;
    color: #fff;
    border-radius: 5px;
    font-weight: 600;
}

.linechart-title p:before {
    content: "₱";
    margin-right: 5px;
}

.card-item-description {
    font-size: 14px;
    color: #555555;
}

</style>
