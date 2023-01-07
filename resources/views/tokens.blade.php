<html>

<head>

<title>Quickbooks Token Manager</title>

<style>
body {
    background-color: #efefef;
    margin: 3rem;
}
select {
    padding: 7px;
    width: 200px;
    border-color: #8b8b8b;
    background-color: #fff;
    border-radius: 5px;
}
button {
    background-color: #f5f5f5;
    border: 1px solid #8b8b8b;
    color: black;
    padding: 10px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    border-radius: 5px;
    cursor: pointer;
}
button:hover {
    background-color: #d9d9d9;
}
.qb-table {
    border: 1px solid #8b8b8b;
    width: 100%;
    border-collapse: collapse;
}
.qb-table th {
    background-color: #8b8b8b;
    color: #fff;
    text-align: left;
}
.qb-table th,
.qb-table tr:not(:last-child) td {
    border-bottom: 1px solid #8b8b8b;
    padding: 0.5rem;
}
.qb-table th:not(:last-child) {
    padding: 0.5rem;
}
.qb-table tr td:not(:last-child) {
    border-right: 1px solid #8b8b8b;
    padding: 0.5rem;
}
.qb-table td:last-child {
    text-align: center;
}
.qb-table form {
    display: inline;
}
.qb-table form button {
    margin: 5px 0;
}
.qb-danger {
    background-color: #ffd0d0;
}
    </style>


</head>

<body>

<!-- Create Connection -->
<form method="POST" action="{{ route('quickbooks.store') }}">
    @csrf
    <select name="id">
        @foreach ($models as $key => $value)
            <option value="{{ $key }}">{{ $value }}</option>
        @endforeach
    </select>
    <button>
        Create Connection
    </button>
</form>
<!-- Create Connection -->

<!-- List Connections -->
<table class="qb-table">
    <thead>
        <th>Model</th>
        <th>Model ID</th>
        <th>Realm ID</th>
        <th>Access Token Expiry</th>
        <th>Refresh Token Expiry</th>
        <th>Created At</th>
        <th></th>
    </thead>
    <tbody>
        @foreach($tokens as $token)
        <tr>
            <td>
                {{ $token->model_type }}
            </td>
            <td>
                {{ $token->model_id }}
            </td>
            <td>
                {{ $token->realm_id }}
            </td>
            <td class="{{ $token->validAccessToken ? '' : 'qb-danger' }}">
                {{ $token->access_token_expires_at }}
            </td>
            <td class="{{ $token->validRefreshToken ? '' : 'qb-danger' }}">
                {{ $token->refresh_token_expires_at }}
            </td>
            <td>
                {{ $token->created_at }}
            </td>
            <td>
                <form method="POST" action="{{ route('quickbooks.update', $token->id) }}">
                    @csrf
                    @method('PUT')
                    <button>
                        Refresh
                    </button>
                </form>
                <form method="POST" action="{{ route('quickbooks.destroy', $token->id) }}">
                    @csrf
                    @method('DELETE')
                    <button>
                        Revoke
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<!-- List Connections -->

</body>
</html>