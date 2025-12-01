@extends('layouts.master')

@section('title', 'West Yangon Technological University - Home')

@section('content')

    <style>
        /* body {
        background-image: url('school.jpg');
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-size: 100% 100%;
        } */

        input[type=text],
        select {
            width: 80%;
            padding: 5px;
            margin: 15px 0;
            display: inline-block;
            border: 2px solid #1e90ff;
            /* lighter blue border */
            border-radius: 5px;
            box-sizing: border-box;
            background-color: #4b4dcf;
            /* light blue background */
            color: #003366;
            /* dark blue text */
            font-weight: 600;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        select:hover,
        select:focus {
            background-color: #5a99e6;
            /* slightly darker blue on hover/focus */
            border-color: #0056b3;
            /* darker blue border on hover/focus */
            outline: none;
            cursor: pointer;
        }

        div {
            border-radius: 20px;
            padding: 15px;
        }



        .course-card img {
            height: 200px;
            /* Fixed height for uniformity */
            width: 100%;
            object-fit: cover;
            Ensures the image covers the area
        }
    </style>
    </head>

    <body>

        <!-- Hero Section -->
        <section class="courses-hero mb-5 custom-padding" style="background-image: url({{ asset('images/hero-bg.png') }});">
            <div class="container mt-4 py-5">
                <h1 class="display-4 font-weight-bold mb-3 text-white">Our Engineering Programs Departments</h1>
                <p class="lead text-white">West Yangon Technological University offers 11 specialized engineering departments
                    with modern facilities and industry-focused curriculum.</p>
            </div>
        </section>

        @yield('content')
        <div class="container">

            <div class="row">

                <table style="width:100%">
                    <thead class="table-hover">
                        <tr>
                            <!-- civil card -->
                            <td>
                                <div class="card bg-light text-bg-light" style="width: 22rem;height: 24rem;">
                                    <img src="{{ asset('images\ce1.jfif') }}" class="card-img-top" alt="...">
                                    <div class="card-body">
                                        <h5 class="card-title">Civil</h5>
                                        <select name="links" class="btn btn-light" id="" size="1"
                                            onchange="window.location.href=this.value;">
                                            <option value="">သင်တန်းနှစ်နှင့်ဘာသာရပ်များ</option>
                                            <option value="{{ route('home.coursecode') }}">ပထမနှစ်</option>
                                            <option value="{{ route('home.coursecode1') }}">ဒုတိယနှစ်</option>
                                            <option value="{{ route('home.coursecode2') }}">တတိယနှစ်</option>
                                            <option value="{{ route('home.coursecode3') }}">စတုတ္ထနှစ်</option>
                                            <option value="{{ route('home.coursecode4') }}">ပဉ္စမနှစ်</option>
                                            <option value="{{ route('home.coursecode5') }}">ဆဌမနှစ်</option>
                                            <option value="{{ route('home.coursecode6') }}">မဟာတန်း</option>
                                        </select>
                                    </div>
                                </div>
                            </td>

                            <!-- Archi card -->
                            <th>
                                <div class="card bg-light text-bg-black" style="width: 22rem;height: 24rem;">
                                    <img src="{{ asset('images\archi.jpg') }}" class="card-img-top" alt="...">
                                    <div class="card-body">
                                        <h5 class="card-title">Architecture</h5>
                                        <select name="links" class="btn btn-light" id="" size="1"
                                            onchange="window.location.href=this.value;">
                                            <option value="">သင်တန်းနှစ်နှင့်ဘာသာရပ်များ</option>
                                            <option value="{{ route('home.coursecode') }}">ပထမနှစ်</option>
                                            <option value="{{ route('home.coursecode1') }}">ဒုတိယနှစ်</option>
                                            <option value="{{ route('home.coursecode2') }}">တတိယနှစ်</option>
                                            <option value="{{ route('home.coursecode3') }}">စတုတ္ထနှစ်</option>
                                            <option value="{{ route('home.coursecode4') }}">ပဉ္စမနှစ်</option>
                                            <option value="{{ route('home.coursecode5') }}">ဆဌမနှစ်</option>
                                            <option value="{{ route('home.coursecode6') }}">မဟာတန်း</option>
                                        </select>
                                    </div>
                                </div>
                            </th>


                            <!-- EP card -->

                            <th>
                                <div class="card bg-light text-bg-black" style="width: 22rem;height: 24rem;">
                                    <img src="{{ asset('images\ep1.jpg') }}" class="card-img-top" alt="...">
                                    <div class="card-body">
                                        <h5 class="card-title">Electrical Power</h5>
                                        <select name="links" class="btn btn-light" id="" size="1"
                                            onchange="window.location.href=this.value;">
                                            <option value="">သင်တန်းနှစ်နှင့်ဘာသာရပ်များ</option>
                                            <option value="{{ route('home.coursecode') }}">ပထမနှစ်</option>
                                            <option value="{{ route('home.coursecode1') }}">ဒုတိယနှစ်</option>
                                            <option value="{{ route('home.coursecode2') }}">တတိယနှစ်</option>
                                            <option value="{{ route('home.coursecode3') }}">စတုတ္ထနှစ်</option>
                                            <option value="{{ route('home.coursecode4') }}">ပဉ္စမနှစ်</option>
                                            <option value="{{ route('home.coursecode5') }}">ဆဌမနှစ်</option>
                                            <option value="{{ route('home.coursecode6') }}">မဟာတန်း</option>
                                        </select>
                                    </div>
                                </div>
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <!-- Information Technology -->
                            <td>
                                <div class="card bg-light text-bg-dark" style="width: 22rem;height: 24rem;">
                                    <img src="{{ asset('images\it1.jpg') }}" class="card-img-top" alt="...">
                                    <div class="card-body">
                                        <h5 class="card-title">Information Technology</h5>
                                        <select name="links" class="btn btn-light" id="" size="1"
                                            onchange="window.location.href=this.value;">
                                            <option value="">သင်တန်းနှစ်နှင့်ဘာသာရပ်များ</option>
                                            <option value="{{ route('home.coursecode') }}">ပထမနှစ်</option>
                                            <option value="{{ route('home.coursecode1') }}">ဒုတိယနှစ်</option>
                                            <option value="{{ route('home.coursecode2') }}">တတိယနှစ်</option>
                                            <option value="{{ route('home.coursecode3') }}">စတုတ္ထနှစ်</option>
                                            <option value="{{ route('home.coursecode4') }}">ပဉ္စမနှစ်</option>
                                            <option value="{{ route('home.coursecode5') }}">ဆဌမနှစ်</option>
                                            <option value="{{ route('home.coursecode6') }}">မဟာတန်း</option>
                                        </select>
                                    </div>
                                </div>
                            </td>
                            <!-- Electronic card -->
                            <td>
                                <div class="card bg-light text-bg-dark" style="width: 22rem;height: 24rem;">
                                    <img src="{{ asset('images\ec1.jpg') }}" class="card-img-top" alt="...">
                                    <div class="card-body">
                                        <h5 class="card-title">Electronic</h5>
                                        <select name="links" class="btn btn-light" id="" size="1"
                                            onchange="window.location.href=this.value;">
                                            <option value="">သင်တန်းနှစ်နှင့်ဘာသာရပ်များ</option>
                                            <option value="{{ route('home.coursecode') }}">ပထမနှစ်</option>
                                            <option value="{{ route('home.coursecode1') }}">ဒုတိယနှစ်</option>
                                            <option value="{{ route('home.coursecode2') }}">တတိယနှစ်</option>
                                            <option value="{{ route('home.coursecode3') }}">စတုတ္ထနှစ်</option>
                                            <option value="{{ route('home.coursecode4') }}">ပဉ္စမနှစ်</option>
                                            <option value="{{ route('home.coursecode5') }}">ဆဌမနှစ်</option>
                                            <option value="{{ route('home.coursecode6') }}">မဟာတန်း</option>
                                        </select>
                                    </div>
                                </div>
                            </td>
                            <!-- Mechanical card -->
                            <td>
                                <div class="card bg-light text-bg-black" style="width: 22rem;height: 24rem;">
                                    <img src="{{ asset('images\mech1.jpg') }}" class="card-img-top" alt="...">
                                    <div class="card-body">
                                        <h5 class="card-title">Mechanical</h5>
                                        <select name="links" class="btn btn-light" id="" size="1"
                                            onchange="window.location.href=this.value;">
                                            <option value="">သင်တန်းနှစ်နှင့်ဘာသာရပ်များ</option>
                                            <option value="{{ route('home.coursecode') }}">ပထမနှစ်</option>
                                            <option value="{{ route('home.coursecode1') }}">ဒုတိယနှစ်</option>
                                            <option value="{{ route('home.coursecode2') }}">တတိယနှစ်</option>
                                            <option value="{{ route('home.coursecode3') }}">စတုတ္ထနှစ်</option>
                                            <option value="{{ route('home.coursecode4') }}">ပဉ္စမနှစ်</option>
                                            <option value="{{ route('home.coursecode5') }}">ဆဌမနှစ်</option>
                                            <option value="{{ route('home.coursecode6') }}">မဟာတန်း</option>
                                        </select>
                                    </div>
                                </div>
                            </td>

                        </tr>

                        <tr>
                            <!-- Chemical card -->
                            <td>
                                <div class="card bg-light text-bg-black" style="width: 22rem;height: 24rem;">
                                    <img src="{{ asset('images\che1.jpg') }}" class="card-img-top" alt="...">
                                    <div class="card-body">
                                        <h5 class="card-title">Chemical</h5>
                                        <select name="links" class="btn btn-light" id="" size="1"
                                            onchange="window.location.href=this.value;">
                                            <option value="">သင်တန်းနှစ်နှင့်ဘာသာရပ်များ</option>
                                            <option value="{{ route('home.coursecode') }}">ပထမနှစ်</option>
                                            <option value="{{ route('home.coursecode1') }}">ဒုတိယနှစ်</option>
                                            <option value="{{ route('home.coursecode2') }}">တတိယနှစ်</option>
                                            <option value="{{ route('home.coursecode3') }}">စတုတ္ထနှစ်</option>
                                            <option value="{{ route('home.coursecode4') }}">ပဉ္စမနှစ်</option>
                                            <option value="{{ route('home.coursecode5') }}">ဆဌမနှစ်</option>
                                            <option value="{{ route('home.coursecode6') }}">မဟာတန်း</option>
                                        </select>
                                    </div>
                                </div>
                            </td>
                            <!-- Metallagury card -->
                            <td>
                                <div class="card bg-light text-bg-black" style="width: 22rem;height: 24rem;">
                                    <img src="{{ asset('images\metal.jfif') }}" class="card-img-top" alt="...">
                                    <div class="card-body">
                                        <h5 class="card-title">Metallagury</h5>
                                        <select name="links" class="btn btn-light" id="" size="1"
                                            onchange="window.location.href=this.value;">
                                            <option value="">သင်တန်းနှစ်နှင့်ဘာသာရပ်များ</option>
                                            <option value="{{ route('home.coursecode') }}">ပထမနှစ်</option>
                                            <option value="{{ route('home.coursecode1') }}">ဒုတိယနှစ်</option>
                                            <option value="{{ route('home.coursecode2') }}">တတိယနှစ်</option>
                                            <option value="{{ route('home.coursecode3') }}">စတုတ္ထနှစ်</option>
                                            <option value="{{ route('home.coursecode4') }}">ပဉ္စမနှစ်</option>
                                            <option value="{{ route('home.coursecode5') }}">ဆဌမနှစ်</option>
                                            <option value="{{ route('home.coursecode6') }}">မဟာတန်း</option>
                                        </select>
                                    </div>
                                </div>
                            </td>
                            <!-- Mechatronic card -->
                            <td>
                                <div class="card bg-light text-bg-black" style="width: 22rem;height: 24rem;">
                                    <img src="{{ asset('images\mce2.jpg') }}" class="card-img-top" alt="...">
                                    <div class="card-body">
                                        <h5 class="card-title">Mechatronic </h5>
                                        <select name="links" class="btn btn-light" id="" size="1"
                                            onchange="window.location.href=this.value;">
                                            <option value="">သင်တန်းနှစ်နှင့်ဘာသာရပ်များ</option>
                                            <option value="{{ route('home.coursecode') }}">ပထမနှစ်</option>
                                            <option value="{{ route('home.coursecode1') }}">ဒုတိယနှစ်</option>
                                            <option value="{{ route('home.coursecode2') }}">တတိယနှစ်</option>
                                            <option value="{{ route('home.coursecode3') }}">စတုတ္ထနှစ်</option>
                                            <option value="{{ route('home.coursecode4') }}">ပဉ္စမနှစ်</option>
                                            <option value="{{ route('home.coursecode5') }}">ဆဌမနှစ်</option>
                                            <option value="{{ route('home.coursecode6') }}">မဟာတန်း</option>
                                        </select>
                                    </div>
                                </div>
                            </td>

                        </tr>

                        <tr>
                            <!-- Textile card -->
                            <td>
                                <div class="card bg-light text-bg-black" style="width: 22rem;height: 24rem;">
                                    <img src="{{ asset('images\tex2.jpg') }}" class="card-img-top" alt="...">
                                    <div class="card-body">
                                        <h5 class="card-title">Textile</h5>
                                        <select name="links" class="btn btn-light" id="" size="1"
                                            onchange="window.location.href=this.value;">
                                            <option value="">သင်တန်းနှစ်နှင့်ဘာသာရပ်များ</option>
                                            <option value="{{ route('home.coursecode') }}">ပထမနှစ်</option>
                                            <option value="{{ route('home.coursecode1') }}">ဒုတိယနှစ်</option>
                                            <option value="{{ route('home.coursecode2') }}">တတိယနှစ်</option>
                                            <option value="{{ route('home.coursecode3') }}">စတုတ္ထနှစ်</option>
                                            <option value="{{ route('home.coursecode4') }}">ပဉ္စမနှစ်</option>
                                            <option value="{{ route('home.coursecode5') }}">ဆဌမနှစ်</option>
                                            <option value="{{ route('home.coursecode6') }}">မဟာတန်း</option>
                                        </select>
                                    </div>
                                </div>
                            </td>
                            <!-- Agricultural card -->
                            <td>
                                <div class="card bg-light text-bg-black" style="width: 22rem; height: 24rem;">
                                    <img src="{{ asset('images\agri.jfif') }}" class="card-img-top" alt="...">
                                    <div class="card-body">
                                        <h5 class="card-title">Agricultural</h5>
                                        <select name="links" class="btn btn-light" id="" size="1"
                                            onchange="window.location.href=this.value;">
                                            <option value="">သင်တန်းနှစ်နှင့်ဘာသာရပ်များ</option>
                                            <option value="{{ route('home.coursecode') }}">ပထမနှစ်</option>
                                            <option value="{{ route('home.coursecode1') }}">ဒုတိယနှစ်</option>
                                            <option value="{{ route('home.coursecode2') }}">တတိယနှစ်</option>
                                            <option value="{{ route('home.coursecode3') }}">စတုတ္ထနှစ်</option>
                                            <option value="{{ route('home.coursecode4') }}">ပဉ္စမနှစ်</option>
                                            <option value="{{ route('home.coursecode5') }}">ဆဌမနှစ်</option>
                                            <option value="{{ route('home.coursecode6') }}">မဟာတန်း</option>
                                        </select>
                                    </div>
                                </div>
                            </td>
                            <!-- WYTU card -->
                            <td>
                                <div class="card bg-light text-bg-light" style="width: 22rem; height: 24rem;">
                                    <img src="{{ asset('images\school.jpg') }}" class="card-img-top" alt="...">
                                    <div class="card-body">
                                        <h3 class="card-title">Welcome Our School </h3>
                                        <p><b>West Yangon Technological University</b></p>

                                    </div>
                                </div>
                            </td>
                        </tr>

                    </tbody>

                </table>
            </div>




            </form>
        </div>
        </div>






        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous">
        </script>
        </section>
    </body>

    </html>

    <div class="services-heading py-5 mt-4" style="background-image: url(images/hero-bg.png);">
        <h2 class="display-4 font-weight-bold mb-3 text-white">Our Departments</h2>
        <p class="lead text-white">In WYTU, there are 11 Departments, in there, following are available...</p>
    </div>
@endsection
