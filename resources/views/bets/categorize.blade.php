@extends('adminlte::page')

@section('content')
@endsection

@section('scripts')
    <script>



        import Form from "vform";

        const app = new Vue({
            el: '#app',
            data: {
                game: {}
            },

            beforeMount() {
                this.$Progress.start();
            },

            mounted() {
                this.getAgents();
                this.listen();
            },

            methods: {
                getBets(page = 1) {
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
