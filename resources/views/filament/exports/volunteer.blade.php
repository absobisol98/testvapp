<table>
    <thead>
        <tr>
            <th>#</th>
            <th>First Name</th>
            <th>Middle Name</th>
            <th>Last Name</th>
            <th>Age</th>
            <th>Email</th>
            <th>AFI ID</th>
            <th>Date Joined</th>
        </tr>
    </thead>
    <tbody>
        @php
            $counter = 1;
        @endphp

        @foreach ($collections as $collection)
        <tr>
            {{-- @php
            dd($collection);
        @endphp --}}
            <td>{{$counter}}</td>
            <td>{{$collection['first_name']}}</td>
            <td>{{$collection['middle_name']}}</td>
            <td>{{$collection['last_name']}}</td>
            <td>{{$collection['age']}}</td>
            <td>{{$collection['email']}}</td>
            <td>{{$collection['affiliate_type_id']}}</td>
            <td> {{ \Carbon\Carbon::parse($collection['created_at'])->format('M-d-Y')}}</td>
        </tr>
        @php
        $counter++;
    @endphp
        @endforeach
    </tbody>
</table>
