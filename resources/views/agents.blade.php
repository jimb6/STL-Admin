@extends('adminlte::page')

@section('content')
    <vue-progress-bar></vue-progress-bar>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Bordered Table</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table class="table table-bordered" >
                        <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Agent Name</th>
                            <th>Progress</th>
                            <th style="width: 40px">Label</th>
                        </tr>
                        </thead>
                        <tbody v-for="(agent, index) in agents" :key="agent.agent_name">
                        <tr>
                            <td>@{{ index }}</td>
                            <td>@{{ agent.agent_name }}</td>
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
{{--                <pagination :data="agents" @pagination-change-page="getAgents"></pagination>--}}
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>

@endsection
@section('scripts')
    <script>
        const app = new Vue({
            el: '#app',
            data: {
                loading: false,
                agents: {},
                error: null,
            },

            beforeMount(){
                this.$Progress.start();
            },

            mounted() {
                this.getAgents();
                this.listen();
            },

            methods: {
                getAgents(page = 1) {
                    axios.get('/api/agents?page=' + page)
                        .then((response) => {
                            this.agents = response.data
                            this.$Progress.finish();
                        })
                        .catch(function (error) {
                            console.log(error);
                            this.$Progress.finish();
                        });
                },
                listen() {
                    // Echo.private('post.' + this.post.id)
                    //     .listen('NewComment', (comment) => {
                    //         this.comments.unshift(comment);
                    //     })
                }

            }
        });
    </script>
@endsection
