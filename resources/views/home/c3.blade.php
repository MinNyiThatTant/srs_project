@extends('master')

@section('content')

<body class="bg-light">
    <!-- Hero Section -->
    <section class="courses-hero mb-5 custom-padding" style="background-image: url(images/hero-bg.png);">
        <div class="container mt-4 py-5">
            <h1 class="display-4 font-weight-bold mb-3 text-white">Our Engineering Programs Departments</h1>
            <p class="lead text-white">West Yangon Technological University offers 11 specialized engineering departments
                with modern facilities and industry-focused curriculum.</p>
        </div>
    </section>

    
      <h1 class="text-center text-success mb-4">တတိယနှစ်</h1>

    <table class="table table-hover text-center p-4 mb-4 ">
   
        <div class="container container-fluid">
         <caption><h3 class="text-center text-success mb-4"><pre><a href="{{route('home.department1')}}"> Go to Back</a></pre></h3></caption>

    
        <thead>
            <tr >
                <th></th>
            </tr>
        </thead>

        <tbody>
            <!-- <first row> -->
            <tr>
                <td class="table" style= "width: 22rem;height: 24rem;">
                    <div class="container d-flex justify-content-center align-items-center vh-50">
                        <div class="card shadow-lg p-4" style="width: 100%; max-width: 300px;">
                            <h5 class="text-center text-success mb-4"><b>Civil</b> </h5>

                            <table class="table table-bordered border-danger text-center table-sm mb-4">

                                <thead class="table-success ">
                                    <tr class="table-primary">
                                        <th colspan="2 ">
                                            Third Year
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-light">
                                        <td>No.</td>
                                        <td>Courses</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>1.</td>
                                        <td>C301</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>2.</td>
                                        <td>C302</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>3.</td>
                                        <td>C303</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>4.</td>
                                        <td>C304</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td>5.</td>
                                        <td>E005</td>
                                    </tr>
                                    <tr class="table-success">
                                        <td>6.</td>
                                        <td>G306</td>
                                    </tr>
                                    <tr class="table-danger">
                                        <td>7.</td>
                                        <td>P307</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>


                    </div>

                </td>

                 <td class="table" style= "width: 22rem;height: 24rem;">
                    <div class="container d-flex justify-content-center align-items-center vh-50">
                        <div class="card shadow-lg p-4" style="width: 100%; max-width: 300px;">
                            <h5 class="text-center text-primary mb-4"><b>Architecture</b> </h5>  

                            <table class="table table-bordered border-danger text-center table-sm mb-4">

                                <thead class="table-success ">
                                    <tr class="table-primary">
                                        <th colspan="2 ">
                                            Third Year
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-light">
                                        <td>No.</td>
                                        <td>Courses</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>1.</td>
                                        <td>A301</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>2.</td>
                                        <td>A302</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>3.</td>
                                        <td>A303</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>4.</td>
                                        <td>A304</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td>5.</td>
                                        <td>E105</td>
                                    </tr>
                                    <tr class="table-success">
                                        <td>6.</td>
                                        <td>M006</td>
                                    </tr>
                                    <tr class="table-danger">
                                        <td>7.</td>
                                        <td>P007</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>


                    </div>

                </td>

                <td class="table" style= "width: 22rem;height: 24rem;">
                    <div class="container d-flex justify-content-center align-items-center vh-50">
                        <div class="card shadow-lg p-4" style="width: 100%; max-width: 300px;">
                            <h5 class="text-center text-warning mb-4"><b>Electrical Power</b> </h5>  

                            <table class="table table-bordered border-danger text-center table-sm mb-4">

                                <thead class="table-success ">
                                    <tr class="table-primary">
                                        <th colspan="2 ">
                                            Third Year
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-light">
                                        <td>No.</td>
                                        <td>Courses</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>1.</td>
                                        <td>EP301</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>2.</td>
                                        <td>EP302</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>3.</td>
                                        <td>EP303</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>4.</td>
                                        <td>EP104</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td>5.</td>
                                        <td>E305</td>
                                    </tr>
                                    <tr class="table-success">
                                        <td>6.</td>
                                        <td>M006</td>
                                    </tr>
                                    <tr class="table-danger">
                                        <td>7.</td>
                                        <td>P007</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>


                    </div>

                </td>

                <td class="table" style= "width: 22rem;height: 24rem;">
                    <div class="container d-flex justify-content-center align-items-center vh-50">
                        <div class="card shadow-lg p-4" style="width: 100%; max-width: 300px;">
                            <h5 class="text-center text-dark mb-4"><b>Electronic </b> </h5>  

                            <table class="table table-bordered border-danger text-center table-sm mb-4">

                                <thead class="table-success ">
                                    <tr class="table-primary">
                                        <th colspan="2 ">
                                            Third Year
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-light">
                                        <td>No.</td>
                                        <td>Courses</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>1.</td>
                                        <td>Ec301</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>2.</td>
                                        <td>Ec302</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>3.</td>
                                        <td>Ec303</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>4.</td>
                                        <td>Ec304</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td>5.</td>
                                        <td>E105</td>
                                    </tr>
                                    <tr class="table-success">
                                        <td>6.</td>
                                        <td>M006</td>
                                    </tr>
                                    <tr class="table-danger">
                                        <td>7.</td>
                                        <td>P007</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>


                    </div>

                </td>
            </tr>
            <!-- <second row> -->
            <tr>
                <td class="table" style= "width: 22rem;height: 24rem;">
                    <div class="container d-flex justify-content-center align-items-center vh-50">
                        <div class="card shadow-lg p-4" style="width: 100%; max-width: 300px;">
                            <h5 class="text-center text-success mb-4"><b>Information Technology</b> </h5>

                            <table class="table table-bordered border-danger text-center table-sm mb-4">

                                <thead class="table-success ">
                                    <tr class="table-primary">
                                        <th colspan="2 ">
                                            Third Year
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-light">
                                        <td>No.</td>
                                        <td>Courses</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>1.</td>
                                        <td>IT301</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>2.</td>
                                        <td>IT302</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>3.</td>
                                        <td>IT303</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>4.</td>
                                        <td>IT304</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td>5.</td>
                                        <td>E105</td>
                                    </tr>
                                    <tr class="table-success">
                                        <td>6.</td>
                                        <td>Math106</td>
                                    </tr>
                                    <tr class="table-danger">
                                        <td>7.</td>
                                        <td>M107</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>


                    </div>

                </td>

                 <td class="table" style= "width: 22rem;height: 24rem;">
                    <div class="container d-flex justify-content-center align-items-center vh-50">
                        <div class="card shadow-lg p-4" style="width: 100%; max-width: 300px;">
                            <h5 class="text-center text-primary mb-4"><b>Textile</b> </h5>  

                            <table class="table table-bordered border-danger text-center  table-sm mb-4">

                                <thead class="table-success ">
                                    <tr class="table-primary">
                                        <th colspan="2 ">
                                            Third Year
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-light">
                                        <td>No.</td>
                                        <td>Courses</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>1.</td>
                                        <td>Tex301</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>2.</td>
                                        <td>Tex302</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>3.</td>
                                        <td>Tex303</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>4.</td>
                                        <td>Tex304</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td>5.</td>
                                        <td>E105</td>
                                    </tr>
                                    <tr class="table-success">
                                        <td>6.</td>
                                        <td>M006</td>
                                    </tr>
                                    <tr class="table-danger">
                                        <td>7.</td>
                                        <td>Chy007</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>


                    </div>

                </td>

                <td class="table" style= "width: 22rem;height: 24rem;">
                    <div class="container d-flex justify-content-center align-items-center vh-50">
                        <div class="card shadow-lg p-4" style="width: 100%; max-width: 300px;">
                            <h5 class="text-center text-warning mb-4"><b>Metallagury</b> </h5>  

                            <table class="table table-bordered border-danger text-center table-sm mb-4">

                                <thead class="table-success ">
                                    <tr class="table-primary">
                                        <th colspan="2 ">
                                            Third Year
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-light">
                                        <td>No.</td>
                                        <td>Courses</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>1.</td>
                                        <td>Metal301</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>2.</td>
                                        <td>Metal302</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>3.</td>
                                        <td>Metal303</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>4.</td>
                                        <td>Metal304</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td>5.</td>
                                        <td>E105</td>
                                    </tr>
                                    <tr class="table-success">
                                        <td>6.</td>
                                        <td>M006</td>
                                    </tr>
                                    <tr class="table-danger">
                                        <td>7.</td>
                                        <td>P007</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>


                    </div>

                </td>

                <td class="table" style= "width: 22rem;height: 24rem;">
                    <div class="container d-flex justify-content-center align-items-center vh-50">
                        <div class="card shadow-lg p-4" style="width: 100%; max-width: 300px;">
                            <h5 class="text-center text-dark mb-4"><b>Agricultural </b> </h5>  

                            <table class="table table-bordered border-danger text-center table-sm mb-4">

                                <thead class="table-success ">
                                    <tr class="table-primary">
                                        <th colspan="2 ">
                                            Third Year
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-light">
                                        <td>No.</td>
                                        <td>Courses</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>1.</td>
                                        <td>Agri301</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>2.</td>
                                        <td>Agri302</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>3.</td>
                                        <td>Agri303</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>4.</td>
                                        <td>Agri304</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td>5.</td>
                                        <td>E105</td>
                                    </tr>
                                    <tr class="table-success">
                                        <td>6.</td>
                                        <td>M006</td>
                                    </tr>
                                    <tr class="table-danger">
                                        <td>7.</td>
                                        <td>P007</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>


                    </div>

                </td>
            </tr>
            <!-- <Third row> -->
            <tr>
                <td class="table" style= "width: 22rem;height: 24rem;">
                    <div class="container d-flex justify-content-center align-items-center vh-50">
                        <div class="card shadow-lg p-4" style="width: 100%; max-width: 300px;">
                            <h5 class="text-center text-success mb-4"><b>Mechanical</b> </h5>

                            <table class="table table-bordered border-danger text-center table-sm mb-4">

                                <thead class="table-success ">
                                    <tr class="table-primary">
                                        <th colspan="2 ">
                                            Third Year
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-light">
                                        <td>No.</td>
                                        <td>Courses</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>1.</td>
                                        <td>Mech301</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>2.</td>
                                        <td>Mech302</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>3.</td>
                                        <td>Mech303</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>4.</td>
                                        <td>Mech304</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td>5.</td>
                                        <td>E105</td>
                                    </tr>
                                    <tr class="table-success">
                                        <td>6.</td>
                                        <td>Math106</td>
                                    </tr>
                                    <tr class="table-danger">
                                        <td>7.</td>
                                        <td>P107</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>


                    </div>

                </td>

                 <td class="table" style= "width: 22rem;height: 24rem;">
                    <div class="container d-flex justify-content-center align-items-center vh-50">
                        <div class="card shadow-lg p-4" style="width: 100%; max-width: 300px;">
                            <h5 class="text-center text-primary mb-4"><b>Mechatronic</b> </h5>  

                            <table class="table table-bordered border-danger text-center  table-sm mb-4">

                                <thead class="table-success ">
                                    <tr class="table-primary">
                                        <th colspan="2 ">
                                            Third Year
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-light">
                                        <td>No.</td>
                                        <td>Courses</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>1.</td>
                                        <td>Mce301</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>2.</td>
                                        <td>Mce302</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>3.</td>
                                        <td>Mce303</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>4.</td>
                                        <td>Mce304</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td>5.</td>
                                        <td>E105</td>
                                    </tr>
                                    <tr class="table-success">
                                        <td>6.</td>
                                        <td>M006</td>
                                    </tr>
                                    <tr class="table-danger">
                                        <td>7.</td>
                                        <td>P007</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>


                    </div>

                </td>

                <td class="table" style= "width: 22rem;height: 24rem;">
                    <div class="container d-flex justify-content-center align-items-center vh-50">
                        <div class="card shadow-lg p-4" style="width: 100%; max-width: 300px;">
                            <h5 class="text-center text-warning mb-4"><b>Chemical</b> </h5>  

                            <table class="table table-bordered border-danger text-center table-sm mb-4">

                                <thead class="table-success ">
                                    <tr class="table-primary">
                                        <th colspan="2 ">
                                            Third Year
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-light">
                                        <td>No.</td>
                                        <td>Courses</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>1.</td>
                                        <td>Chem301</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>2.</td>
                                        <td>Chem302</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>3.</td>
                                        <td>Chem303</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>4.</td>
                                        <td>Chem304</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td>5.</td>
                                        <td>E105</td>
                                    </tr>
                                    <tr class="table-success">
                                        <td>6.</td>
                                        <td>M006</td>
                                    </tr>
                                    <tr class="table-danger">
                                        <td>7.</td>
                                        <td>chy007</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>


                    </div>

                </td>

                <td class="table" style= "width: 22rem;height: 24rem;">
                    <div class="container d-flex justify-content-center align-items-center vh-50">
                        <div class="card shadow-lg p-4" style="width: 100%; max-width: 300px;">
                            <h5 class="text-center text-dark mb-4"><b>WYTU </b> </h5>  

                            <table class="table table-bordered border-danger text-center table-sm mb-4">

                                <thead class="table-success ">
                                    <tr class="table-primary">
                                        <th colspan="2 ">
                                            Apply courses
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-light">
                                        <td>No.</td>
                                        <td>Courses</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>1.</td>
                                        <td>M=Myanmar</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>2.</td>
                                        <td>E=English</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>3.</td>
                                        <td>Chy=Chemistry</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>4.</td>
                                        <td>Math=Mathematic</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td>5.</td>
                                        <td>P=Physics</td>
                                    </tr>
                                    <tr class="table-success">
                                        <td>6.</td>
                                        <td>G=Geological</td>
                                    </tr>
                                    <tr class="table-danger">
                                        <td>7.</td>
                                        <td>Total=seven</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>


                    </div>

                </td>
            </tr>

        </tbody>
    </table>
      </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
        crossorigin="anonymous"></script>
</body>

<div class="services-heading py-5 mt-4" style="background-image: url(images/hero-bg.png);">
            <h2 class="display-4 font-weight-bold mb-3 text-white">Our Departments</h2>
            <p class="lead text-white">In WYTU, there are 11 Departments, in there, following are available...</p>
        </div>
@endsection
