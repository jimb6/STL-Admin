@extends('layouts.app')
@section('content')
    <script type="text/javascript">
        // $(document).ready(function(){
        let gamePerDraw = {};
        let gameDetails = {};

        async function getDrawPeriodsAndGames() {
            try {
                // let authorization = "Bearer " + agentData.api_token;
                let authorization = "Bearer 1|sAMQB9H7PuodeJXaX7jOkDTsBJGE45d9NtNb8dPJ";
                const request = {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': authorization
                    }
                }
                let xxx = "http://stl-application-v2.herokuapp.com/api/v1/games/mobile-config/today/";

                const response = await fetch(xxx, request);
                console.log("Lahos");
                let data = await response.json();
                let status = await response.status;

                if( status == 200 ) {
                    console.log( data );
                    // gameDetails = data[0];
                    // displayAllBets();
                    // setDrawTime();
                } else {
                    alert("Error");
                    // window.location.href = "index.html";
                }
            } catch(e){
                alert("Failed to get Draw Periods and Games");
                // window.location.href = "index.html";
            }
        }
        getDrawPeriodsAndGames()
        // });
    </script>
@endsection
