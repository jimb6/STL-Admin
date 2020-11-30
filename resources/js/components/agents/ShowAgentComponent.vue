<template>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Bordered Table</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table class="table table-bordered" v-for="(agent, index) in agents" :key="agent.agent_name">
                        <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Agent Name</th>
                            <th>Progress</th>
                            <th style="width: 40px">Label</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{ index }}</td>
                            <td>{{ agent.agent_name}}</td>
                            <td>
                                <div class="progress progress-xs">
                                    <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                                </div>
                            </td>
                            <td><span class="badge bg-danger">55%</span></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    <ul class="pagination pagination-sm m-0 float-right">
                        <li class="page-item"><a class="page-link" href="#">«</a></li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">»</a></li>
                    </ul>
                </div>
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
</template>

<script>
export default {
    name: "ShowAgentComponent",
    data() {
        return {
            loading: false,
            agents: null,
            error: null,
        };
    },

    mounted() {
        this.getComments();
        this.listen();
    },
    methods: {
        getComments() {
            axios.get('/api/agents/')
                .then((response) => {
                    this.agents = response.data
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        // postComment() {
        //     axios.post('/api/posts/' + this.post.id + '/comment', {
        //         api_token: this.user.api_token,
        //         body: this.commentBox
        //     })
        //         .then((response) => {
        //             this.comments.unshift(response.data);
        //             this.commentBox = '';
        //         })
        //         .catch((error) => {
        //             console.log(error);
        //         })
        // },
        listen() {
            // Echo.private('post.' + this.post.id)
            //     .listen('NewComment', (comment) => {
            //         this.comments.unshift(comment);
            //     })
        }
    }
}
</script>

<style scoped>

</style>
