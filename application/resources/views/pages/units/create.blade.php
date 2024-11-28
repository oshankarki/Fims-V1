<form action="{{ route('unit.create') }}" method="POST">
    @csrf
    <div class="d-flex flex-column form-group">
        <div>Name</div>
        <input class="form-control" type="text" name="unit_name" id="unit_name" />
    </div>

    <!--  -->
    <button type="submit" class="btn btn-primary my-2">Save</button>
</form>
