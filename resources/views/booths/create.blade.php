@extends('adminlte::page')
@section('content')
    <div class="cstm-container add-new-booth fit-modal">
        <section>
            <div class="cstm-row cstm-heading">
                <div class="flex-center">
                    <h1 class="mx-0">Add New Booth</h1>
                </div>
            </div>
        </section>
        <section class="mt-4">
            <div class="cstm-row">
                <div class="cstm-form">

                    <form action="" method="">
                        <div class="main-icon flex-center">
                            <i class="fas fa-store"></i>
                        </div>
                        <div class="input-item">
                            <input type="text" name="address" placeholder="Address">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <input type="submit" name="submit-agent" value="Add">
                    </form>
                </div>

            </div>
        </section>
    </div>
@endsection
