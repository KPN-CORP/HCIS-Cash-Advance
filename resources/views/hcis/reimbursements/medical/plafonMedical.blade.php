<h4>Health Coverage Limit</h4>
<div class="table-responsive">
    <table class="display nowrap dataTable dtr-inline collapsed">
        <thead class="bg-primary text-center align-middle">
            <tr>
                <th rowspan="2" class="text-center sticky"
                    style="z-index:auto !important;background-color:#AB2F2B !important;">Period</th>
                <th colspan="{{ count($master_medical) }}" class="text-center">Type of Health Coverage</th>
            </tr>
            <tr>
                @foreach ($master_medical as $master_medicals)
                    <th class="text-center">{{  $master_medicals->name }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($formatted_data as $period => $balances)
                <tr>
                    <td class="text-center">{{ $period }}</td>
                    @foreach ($master_medical as $master_medical_item)
                        <td class="text-center">
                            {{ isset($balances[$master_medical_item->medical_type]) ? number_format($balances[$master_medical_item->medical_type], 0, ',', '.') : '-' }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>