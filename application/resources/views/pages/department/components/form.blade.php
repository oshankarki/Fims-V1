<form action="{{route("department.create")}}" method="POST">
    @csrf

    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" name="name" id="name" />
    </div>

    <div class="form-group">
        <label for="type">Type</label>
        <select name="type" id="type" class="form-control" required>
            <option value="">Select Type</option>
            <option value="cutting">Cutting</option>
            <option value="printing">Printing</option>
            <option value="stiching">Stiching</option>
            <option value="final_production">Final Production</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary my-2">Save</button>
</form>