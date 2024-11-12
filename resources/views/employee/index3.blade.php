<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
        }

        .sidebar a {
            color: wheat;
            padding: 15px;
            display: block;
            text-decoration: none;
        }

        .sidebar a:hover {
            background-color: whitesmoke;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .navbar {
            background-color: #343a40;
        }

        .navbar-brand {
            color: #fff;
        }

        .navbar-brand:hover {
            color: #ccc;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar position-fixed d-flex flex-column p-3" style="width: 250px;">
        <a href="{{ route('dashboard') }}" class="navbar-brand">Dashboard</a>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="#">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('employees.index') }}">Employee</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Settings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Reports</a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Employee</a>
                <div class="d-flex ms-auto">
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">Logout</button>
                    </form>
                </div>
            </div>
        </nav>

        <div class="container mt-3">

            @if(session('success'))
            <div class="alert alert-danger" role="alert">
                {{ session('success') }}
            </div>
            @endif

            <a href="{{ route('employees.create') }}" class="float-end mb-3">
                <button class="btn btn-primary"><i class="fas fa-plus"></i></button>
            </a>

            <h2 class="text-center mt-5 mb-4 text-primary">Employee List</h2>

            <!-- search -->

            <div class="d-flex justify-content-end mt-4">
                <form action="{{ route('employees.index') }}" method="GET" class="d-flex mb-4">

                    <!-- Date Range -->
                    <label for="">Start_date: </label>
                    <input type="date" name="start_date" class="form-control me-2" value="{{ request('start_date') }}">
                    <label for="">End_date: </label>
                    <input type="date" name="end_date" class="form-control me-2" value="{{ request('end_date') }}">

                    <!-- Search Field -->
                    <input type="text" name="search" class="form-control me-2" placeholder="Search" value="{{ request('search') }}">

                    <button type="submit" class="btn btn-primary">Filter</button>
                </form>
            </div>


            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle text-center shadow-lg">
                    <thead class="table-primary">
                        <tr>
                            <th>SL</th>
                            <th>Name</th>
                            <th>Image</th>
                            <th>Email</th>
                            <th>Position</th>
                            <th>Department</th>
                            <th>Date of Joining</th>
                            <th>Salary</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                            <td>
                                @if($employee->image)
                                <img src="{{ asset('storage/' . $employee->image) }}"  class="" style="width: 50px; height: auto;">
                                @else
                                <span>No Image</span>
                                @endif
                            </td>
                            <td>{{ $employee->email }}</td>
                            <td>{{ $employee->position }}</td>
                            <td>{{ $employee->department }}</td>
                            <td>{{ \Carbon\Carbon::parse($employee->joining_date)->format('d M, Y') }}</td>
                            <td>{{ number_format($employee->salary, 2) }}</td>
                            <td>
                                <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-primary btn-sm me-1"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this employee?')"><i class="fas fa-trash-alt"></i> </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No employees found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            <div class="d-flex justify-content-center mt-4">
                {{ $employees->links() }}
            </div>
        </div>


    </div>


    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>