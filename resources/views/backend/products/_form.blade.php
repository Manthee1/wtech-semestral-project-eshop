@php
    use App\Models\Product;
    use App\Models\ProductMake;
    use App\Models\ProductModel;
    use App\Models\ProductDrivetrain;
    use App\Models\ProductBodyType;
    use App\Models\ProductEngineType;

    $makes = ProductMake::pluck('name', 'id');
    $models = ProductModel::pluck('name', 'id');

    $drivetrains = ProductDrivetrain::pluck('name', 'id');
    $bodyTypes = ProductBodyType::pluck('name', 'id');
    $engineTypes = ProductEngineType::pluck('name', 'id');

    $makeModelsAll = ProductModel::all()->groupBy('make_id');
    // Turn into json
    $makeModelsAll = $makeModelsAll
        ->map(function ($models) {
            return $models->map(function ($model) {
                return [
                    'id' => $model->id,
                    'name' => $model->name,
                ];
            });
        })
        ->toJson();

    // dd($makeModels);

@endphp

<style>
    #product-form {
        max-width: 1200px;
    }
</style>

{{-- Errors --}}
<x-alert />

<div class="flex form-container mb-6 gap-5">
    <div class="form-section">
        <h5 class="form-section-title">Basic Information</h5>
        <hr>
        <x-form.select name="make_id" :options="$makes" label="Product Make" :value="$product->make_id ?? null" class="flex-4" required id="make_id" />
        <x-form.select name="model_id" :options="$models" label="Product Model" :value="$product->model_id ?? null" class="flex-4" required id="model_id" />
        <x-form.input type="number" name="year" label="Product Year" :value="$product->year ?? null" required id="year" class="flex-4" min="0" />
        <x-form.input type="number" name="price" label="Product Price" :value="$product->price ?? ''" required id="price" class="flex-6" step="0.01" min="0" />
        <x-form.input type="number" name="stock" label="Product Stock" :value="$product->stock ?? ''" required id="stock" class="flex-6" min="0" />
        <x-form.textarea name="description" label="Product Description" :value="$product->description ?? ''" required id="description" class="flex-12" rows="4" />
        {{-- is the product active --}}
        <div class='flex-12 flex flex-row flex-left'>
            {{ html()->checkbox('active', isset($product) ? $product->active : true, ['class' => 'my-auto', 'id' => 'active']) }}
            <label class="my-auto" for="active">Active</label>
        </div>
    </div>

    <div class="form-section">
        <h5 class="form-section-title">Product Details</h5>
        <hr>

        <x-form.select name="drivetrain_id" :options="$drivetrains" label="Drivetrain" :value="$product->drivetrain_id ?? null" class="flex-4" id="drivetrain_id" />
        <x-form.select name="body_type_id" :options="$bodyTypes" label="Body Type" :value="$product->body_type_id ?? null" class="flex-4" id="body_type_id" />
        <x-form.select name="engine_type_id" :options="$engineTypes" label="Engine Type" :value="$product->engine_type_id ?? null" class="flex-4" id="engine_type_id" />
        <x-form.input type="number" name="passenger_capacity" label="Passenger Capacity" :value="$product->passenger_capacity ?? null" class="flex-4" min="0" id="passenger_capacity" />
        <x-form.input type="number" name="efficiency" label="Efficiency" :value="$product->efficiency ?? null" class="flex-4" min="0" id="efficiency" />
        <x-form.input type="number" name="horse_power" label="Horse Power" :value="$product->horse_power ?? null" class="flex-4" min="0" id="horse_power" />
    </div>

    <div class="form-section">
        <h5 class="form-section-title">Product Dimensions</h5>
        <hr>
        <x-form.input type="number" name="height" label="Product Height" :value="$product->height ?? null" class="flex-4" min="0" id="height" />
        <x-form.input type="number" name="width" label="Product Width" :value="$product->width ?? null" class="flex-4" min="0" id="width" />
        <x-form.input type="number" name="length" label="Product Length" :value="$product->length ?? null" class="flex-4" min="0" id="length" />
    </div>

    <div class="form-section">
        <h5 class="form-section-title">Product Images</h5>
        <hr>
        <input class="flex-12" type="file" name="images[]" id="images" accept="image/*" multiple>
        @if (isset($product) && $product->images->count() > 0)
            @foreach ($product->images as $image)
                <div class="flex flex-column flex-center gap-5 p-4" style="border: 1px solid #ccc;">
                    <img width="340px" src="{{ $image->getUrl() }}" alt="{{ $product->name }}">
                    {{-- Remove checkbox --}}
                    <div class="flex flex-row flex-left gap-1  width-full">
                        <input type="checkbox" name="delete_image[]" value="{{ $image->id }}"><span></span>
                        <span>Delete Image</span>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>


{{-- Make a script that only allows select the models of the selected make. If the make is changed. then the model gets switched tothe first one of that make. If make is not set. model is also not set and disabled --}}
<script>
    makeModelsAll = JSON.parse(@json($makeModelsAll))
    models = @json($models);
    makeSelect = document.getElementById('make_id');
    modelSelect = document.getElementById('model_id');
    // Set the model to the first one of the selected make
    window.addEventListener('load', () => {

        makeSelect.addEventListener('change', (e) => {
            const makeId = e.target.value;
            const makeModels = makeModelsAll[makeId];
            modelSelect.innerHTML = '';
            if (makeModels) {
                makeModels.forEach(model => {
                    const option = document.createElement('option');
                    option.value = model.id;
                    option.text = model.name;
                    modelSelect.add(option);
                });
                modelSelect.disabled = false;
            } else {
                modelSelect.disabled = true;
            }
        });

    });
</script>
