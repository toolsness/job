<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-4">
            Quick Login for Testing Purposes
        </h1>

        <form action="{{ route('test.login.index') }}" method="GET" class="mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="name" value="{{ request('name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="text" name="email" id="email" value="{{ request('email') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label for="user_type" class="block text-sm font-medium text-gray-700">User Type</label>
                    <select name="user_type" id="user_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">All Types</option>
                        @foreach($userTypes as $type)
                            <option value="{{ $type }}" {{ request('user_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="company" class="block text-sm font-medium text-gray-700">Company</label>
                    <select name="company" id="company" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">All Companies</option>
                        @foreach($companies as $id => $name)
                            <option value="{{ $id }}" {{ request('company') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Filter</button>
                <a href="{{ route('test.login.index') }}" class="ml-2 text-blue-500 hover:underline">Clear Filters</a>
            </div>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($users as $user)
                <div class="bg-white p-4 rounded shadow">
                    <h2 class="text-lg font-semibold mb-2">{{ $user->display_name }}</h2>
                    <p class="text-sm text-gray-600 mb-2">ID: {{ $user->id }}</p>
                    <p class="text-sm text-gray-600 mb-2">Email: {{ $user->email }}</p>
                    <p class="text-sm text-gray-600 mb-2">Type: {{ $user->user_type }}</p>
                    <p class="text-sm text-gray-600 mb-4">Company: {{ $user->company_name }}</p>
                    <a href="{{ route('test.login', $user->id) }}"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Login as
                        {{ $user->user_type }}</a>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $users->links() }}
        </div>
    </div>
</body>

</html>
