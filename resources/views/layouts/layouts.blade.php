
<!DOCTYPE html>
<html lang="en">

    @include('layouts.head')



<body>
	<div class="wrapper">


    @include('layouts.header')

		<!-- Sidebar -->
            @include('layouts.siderbar')
		<!-- End Sidebar -->

        @yield('content')

		<!-- Custom template | don't include it in your project! -->
        @include('layouts.main')
		<!-- End Custom template -->
	</div>


    @include('layouts.js')

    @include('sweetalert::alert')

    <!-- Select2 JS -->
   
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @stack('scripts')
</body>
</html>
