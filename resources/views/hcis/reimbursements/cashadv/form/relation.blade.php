<script src="{{ asset('/js/cashAdvanced/relation.js') }}"></script>

@if (!empty($detailCA['relation_e']) && $detailCA['relation_e'][0]['name'] !== null)
    <div id="form-container-relation">
        <div id="form-container-e-relation-1" class="card-body p-2 mb-3" style="background-color: #f8f8f8">
            <p class="fs-4 text-primary" style="font-weight: bold; ">Relation Entertainment 1</p>
            <div id="form-container-e-relation-req-1" class="card-body bg-light p-2 mb-3">
                <p class="fs-5 text-primary" style="font-weight: bold;">Relation Entertainment Request</p>
                @foreach($detailCA['relation_e'] as $relation)
                    <div id="form-container-e-relation-{{ $loop->index + 1 }}" class="card-body bg-light p-2 mb-3" style="border-radius: 1%;">
                        <div class="row">
                            <!-- Relation Date -->
                            <div class="col-md-12 mb-2">
                                <label class="form-label">Relation Type</label>
                                <div class="form-check">
                                    <input class="form-check-input"
                                        type="checkbox"
                                        name="accommodation_e_relation[]"
                                        id="accommodation_e_relation_1"
                                        value="accommodation" {{ isset($relation['relation_type']['Accommodation']) && $relation['relation_type']['Accommodation'] ? 'checked' : '' }}>
                                    <label class="form-check-label"
                                        for="accommodation_e_relation_1">Accommodation</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input"
                                        name="transport_e_relation[]"
                                        type="checkbox"
                                        id="transport_e_relation_1"
                                        value="transport" {{ isset($relation['relation_type']['Transport']) && $relation['relation_type']['Transport'] ? 'checked' : '' }}>
                                    <label class="form-check-label"
                                        for="transport_e_relation_1">Transport</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input"
                                        name="gift_e_relation[]" type="checkbox"
                                        id="gift_e_relation_1"
                                        value="gift" {{ isset($relation['relation_type']['Gift']) && $relation['relation_type']['Gift'] ? 'checked' : '' }}>
                                    <label class="form-check-label"
                                        for="gift_e_relation_1">Gift</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input"
                                        name="fund_e_relation[]" type="checkbox"
                                        id="fund_e_relation_1"
                                        value="fund" {{ isset($relation['relation_type']['Fund']) && $relation['relation_type']['Fund'] ? 'checked' : '' }}>
                                    <label class="form-check-label"
                                        for="fund_e_relation_1">Fund</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input"
                                        name="food_e_relation[]" type="checkbox"
                                        id="food_e_relation_1"
                                        value="food" {{ isset($relation['relation_type']['Food']) && $relation['relation_type']['Food'] ? 'checked' : '' }}>
                                    <label class="form-check-label"
                                        for="food_e_relation_1">Food/Beverages/Souvenir</label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label"
                                    for="name">Name</label>
                                <input type="text"
                                    name="rname_e_relation[]"
                                    id="rname_e_relation_1"
                                    value="{{ $relation['name'] }}"
                                    class="form-control">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label"
                                    for="position">Position</label>
                                <input type="text"
                                    name="rposition_e_relation[]"
                                    id="rposition_e_relation_1"
                                    value="{{ $relation['position'] }}"
                                    class="form-control">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label"
                                    for="company">Company</label>
                                <input type="text"
                                    name="rcompany_e_relation[]"
                                    id="rcompany_e_relation_1"
                                    value="{{ $relation['company'] }}"
                                    class="form-control">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label"
                                    for="purpose">Purpose</label>
                                <textarea name="rpurpose_e_relation[]"
                                    id="rpurpose_e_relation_1"
                                    class="form-control">{{ $relation['purpose'] }}</textarea>
                            </div>
                        </div>
                        <br>
                        <div class="row mt-3">
                            <div class="d-flex justify-start w-100">
                                <button class="btn btn-danger mr-2" style="margin-right: 10px" onclick="clearFormRelation({{ $loop->index + 1 }}, event)">Reset</button>
                                <button class="btn btn-warning mr-2" onclick="removeFormRelation({{ $loop->index + 1 }}, event)">Delete</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="mt-3">
        <button class="btn btn-primary" id="addMoreButtonRelation" onclick="addMoreFormRelationReq(event)">Add More</button>
    </div>
@else
    <div id="form-container-relation">
        <div id="form-container-e-relation-1" class="card-body p-2 mb-3" style="background-color: #f8f8f8">
            <p class="fs-4 text-primary" style="font-weight: bold; ">Relation Entertainment 1</p>
            <div id="form-container-e-relation-req-1" class="card-body bg-light p-2 mb-3">
                <p class="fs-5 text-primary" style="font-weight: bold;">Relation Entertainment Request</p>
                <div class="row">
                    <!-- Relation Date -->
                    <div class="col-md-12 mb-2">
                        <label class="form-label">Relation Type</label>
                        <div class="form-check">
                            <input class="form-check-input"
                                type="checkbox"
                                name="accommodation_e_relation[]"
                                id="accommodation_e_relation_1"
                                value="accommodation">
                            <label class="form-check-label"
                                for="accommodation_e_relation_1">Accommodation</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input"
                                name="transport_e_relation[]"
                                type="checkbox"
                                id="transport_e_relation_1"
                                value="transport">
                            <label class="form-check-label"
                                for="transport_e_relation_1">Transport</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input"
                                name="gift_e_relation[]" type="checkbox"
                                id="gift_e_relation_1" value="gift">
                            <label class="form-check-label"
                                for="gift_e_relation_1">Gift</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input"
                                name="fund_e_relation[]" type="checkbox"
                                id="fund_e_relation_1" value="fund">
                            <label class="form-check-label"
                                for="fund_e_relation_1">Fund</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input"
                                name="food_e_relation[]" type="checkbox"
                                id="food_e_relation_1" value="food">
                            <label class="form-check-label"
                                for="food_e_relation_1">Food/Beverages/Souvenir</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label"
                            for="name">Name</label>
                        <input type="text" name="rname_e_relation[]"
                            id="rname_e_relation_1" class="form-control">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label"
                            for="position">Position</label>
                        <input type="text"
                            name="rposition_e_relation[]"
                            id="rposition_e_relation_1"
                            class="form-control">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label"
                            for="company">Company</label>
                        <input type="text" name="rcompany_e_relation[]"
                            id="rcompany_e_relation_1"
                            class="form-control">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label"
                            for="purpose">Purpose</label>
                        <textarea name="rpurpose_e_relation[]"
                            id="rpurpose_e_relation_1"
                            class="form-control"></textarea>
                    </div>
                </div>
                <br>
                <div class="row mt-3">
                    <div class="d-flex justify-start w-100">
                        <button class="btn btn-danger mr-2" style="margin-right: 10px" onclick="clearFormRelation(1, event)">Reset</button>
                        <button class="btn btn-warning mr-2" onclick="removeFormRelation(1, event)">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <button class="btn btn-primary" id="addMoreButtonRelation" onclick="addMoreFormRelationReq(event)">Add More</button>
    </div>
@endif
