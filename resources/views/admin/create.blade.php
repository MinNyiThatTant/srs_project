@extends('admins.layout.master')

@section('content')

		
        {{-- create list --}}
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">New Teacher</h4>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="basicInput">Name</label>
                            <input type="text" class="form-control" id="basicInput" placeholder="Enter Name">
                        </div>

                        <div class="form-group">
                            <label for="basicInput">Position</label>
                            <fieldset class="form-group">
                                <select class="form-select" id="basicSelect">
                                    <option>Lecturer</option>
                                    <option>Assistant Lecturer</option>
                                    <option>Director</option>
                                </select>
                            </fieldset>
                        </div>

                        <div class="form-group">
                            <label for="basicInput">Department</label>
                            <fieldset class="form-group">
                                <select class="form-select" id="basicSelect">
                                    <option>Development</option>
                                    <option>Design</option>
                                    <option>Hardware</option>
                                </select>
                            </fieldset>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="basicInput">Email</label>
                            <input type="email" class="form-control" id="basicInput" placeholder="Enter email">
                        </div>
                         <div class="form-group">
                            <label for="basicInput">Ph No</label>
                            <input type="text" class="form-control" id="basicInput" placeholder="Enter Ph No">
                        </div>

                        <div class="form-group">
                            <label for="formFile" class="form-label">Photo</label>
                            <input class="form-control" type="file" id="formFile">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label for="exampleFormControlTextarea1" class="form-label">Address</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" placeholder="Enter Address"></textarea>
                    </div>
                    <div class="button my-3">
                            <a href="#" class="btn btn-primary">Save </a>
                    </div>
                </div>
            </div>
        </div>
        {{-- end create list --}}

@endsection