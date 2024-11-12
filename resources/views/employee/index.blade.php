<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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


    <!-- Sidebar and Main Content Sections (same as before) -->
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

            <form action="{{ route('employees.index') }}" method="GET" class="d-flex mb-4 align-items-center">
                <label for="">Start Date: </label>
                <input type="date" name="start_date" class="form-control me-2" value="{{ request('start_date') }}">

                <label for="">End Date: </label>
                <input type="date" name="end_date" class="form-control me-2" value="{{ request('end_date') }}">

                <input type="text" name="search" class="form-control me-2" placeholder="Search" value="{{ request('search') }}">

                <button type="submit" class="btn btn-primary">Search</button>
            </form>

            <table class="table table-hover table-bordered align-middle text-center shadow-lg">
                <thead class="table-primary">
                    <tr>
                        <th>
                            <a href="{{ route('employees.index', array_merge(request()->query(), ['sort_column' => 'id', 'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none">
                                SL
                                <i class="fas fa-sort-up"></i>
                                <i class="fas fa-sort-down"></i>
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('employees.index', array_merge(request()->query(), ['sort_column' => 'first_name', 'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none">
                                Name
                                <i class="fas fa-sort-up"></i>
                                <i class="fas fa-sort-down"></i>
                            </a>
                        </th>
                        <th>Image</th>
                        <th>
                            <a href="{{ route('employees.index', array_merge(request()->query(), ['sort_column' => 'email', 'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none">
                                Email
                                <i class="fas fa-sort-up"></i>
                                <i class="fas fa-sort-down"></i>
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('employees.index', array_merge(request()->query(), ['sort_column' => 'position', 'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none">
                                Position
                                <i class="fas fa-sort-up"></i>
                                <i class="fas fa-sort-down"></i>
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('employees.index', array_merge(request()->query(), ['sort_column' => 'department', 'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none">
                                Department
                                <i class="fas fa-sort-up"></i>
                                <i class="fas fa-sort-down"></i>
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('employees.index', array_merge(request()->query(), ['sort_column' => 'joining_date', 'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none">
                                Date of Joining
                                <i class="fas fa-sort-up"></i>
                                <i class="fas fa-sort-down"></i>
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('employees.index', array_merge(request()->query(), ['sort_column' => 'salary', 'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none">
                                Salary
                                <i class="fas fa-sort-up"></i>
                                <i class="fas fa-sort-down"></i>
                            </a>
                        </th>
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
                            <img src="{{ asset('storage/' . $employee->image) }}" style="width: 50px; height: auto;">
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
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this employee?')"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">No employees found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

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