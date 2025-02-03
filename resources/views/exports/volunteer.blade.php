<table>
    <thead>
        <tr>
            <th style="background-color: #F55E1D;  width: 50px; text-align: center; vertical-align: middle;">#</th>
            <th style="background-color: #F55E1D;  width: 150px; text-align: center; vertical-align: middle;">Event Title</th>
            <th style="background-color: #F55E1D;  width: 100px; text-align: center; vertical-align: middle;">Shift Name</th>
            <th style="background-color: #F55E1D;  width: 100px; text-align: center; vertical-align: middle;">First Name</th>
            <th style="background-color: #F55E1D;  width: 100px; text-align: center; vertical-align: middle;">Middle Name</th>
            <th style="background-color: #F55E1D;  width: 100px; text-align: center; vertical-align: middle;">Last Name</th>
            <th style="background-color: #F55E1D;  width: 80px; text-align: center; vertical-align: middle;">Birthday</th>
            <th style="background-color: #F55E1D;  width: 200px; text-align: center; vertical-align: middle;">Email</th>
            <th style="background-color: #F55E1D;  width: 50px; text-align: center; vertical-align: middle;">AFI ID</th>
            <th style="background-color: #F55E1D;  width: 150px; text-align: center; vertical-align: middle;">Company Name</th>
            <th style="background-color: #F55E1D;  width: 650px; text-align: center; vertical-align: middle;">Company Address</th>
            <th style="background-color: #F55E1D;  width: 165px; text-align: center; vertical-align: middle;">Company Contact Number</th>
            <th style="background-color: #F55E1D;  width: 160px; text-align: center; vertical-align: middle;">Company Representative</th>
            <th style="background-color: #F55E1D;  width: 150px; text-align: center; vertical-align: middle;">School</th>
            <th style="background-color: #F55E1D;  width: 160px; text-align: center; vertical-align: middle;">Emergency Contact Name</th>
            <th style="background-color: #F55E1D;  width: 155px; text-align: center; vertical-align: middle;">Emergy Contact Number</th>
            <th style="background-color: #F55E1D;  width: 80px; text-align: center; vertical-align: middle;">Status</th>
            <th style="background-color: #F55E1D;  width: 100px; text-align: center; vertical-align: middle;">Date Joined</th>
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
            <td style="text-align: center; vertical-align: middle;">{{$counter}}</td>
            <td style="text-align: center; vertical-align: middle;">{{$collection['event']}}</td>
            <td style="text-align: center; vertical-align: middle;">{{$collection['shift']}}</td>
            <td style="text-align: center; vertical-align: middle;">{{$collection['first_name']}}</td>
            <td style="text-align: center; vertical-align: middle;">{{$collection['middle_name']}}</td>
            <td style="text-align: center; vertical-align: middle;">{{$collection['last_name']}}</td>
            <td style="text-align: center; vertical-align: middle;">{{$collection['age']}}</td>
            <td style="text-align: center; vertical-align: middle;">{{$collection['email']}}</td>
            <td style="text-align: center; vertical-align: middle;">{{$collection['affiliate_type_id']}}</td>
            <td style="text-align: center; vertical-align: middle;">{{$collection['company_name']}}</td>
            <td style="text-align: center; vertical-align: middle;">{{$collection['company_address']}}</td>
            <td style="text-align: center; vertical-align: middle;">{{$collection['company_contact_number']}}</td>
            <td style="text-align: center; vertical-align: middle;">{{$collection['company_representative']}}</td>
            <td style="text-align: center; vertical-align: middle;">{{$collection['school']}}</td>
            <td style="text-align: center; vertical-align: middle;">{{$collection['emergency_contact_name']}}</td>
            <td style="text-align: center; vertical-align: middle;">{{$collection['emergency_contact_number']}}</td>
            <td style="text-align: center; vertical-align: middle;">{{$collection['status']}}</td>
            <td style="text-align: center; vertical-align: middle;"> {{ \Carbon\Carbon::parse($collection['created_at'])->format('M-d-Y')}}</td>
        </tr>
        @php
        $counter++;
    @endphp
        @endforeach
    </tbody>
</table>
