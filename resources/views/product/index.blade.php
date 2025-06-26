<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

</head>
<body>

<div class="container mt-5">
    <h1>Product</h1>
@if(session('success'))
    <div class="alert alert-success mt-2" id="successAlert">
        {{ session('success') }}
    </div>

    <script>
        setTimeout(function() {
            let alertBox = document.getElementById('successAlert');
            if(alertBox){
                alertBox.style.display = 'none';
            }
        }, 2000); // auto-close after 3 seconds
    </script>
@endif

<!-- Search and Filter Products -->
<form method="GET" action="{{ route('products.index') }}" class="mb-3 d-flex gap-2 align-items-center" id="searchForm">
    <div class="position-relative" style="max-width:200px; width:100%;">
        <input type="text" name="search" id="searchInput" value="{{ request('search') }}" placeholder="Search product" class="form-control pe-5">
        @if(request('search'))
            <button type="button" id="clearSearch" class="btn btn-sm btn-light position-absolute end-0 top-50 translate-middle-y me-2" style="z-index:2;">
                &times;
            </button>
        @endif
    </div>
    <button type="submit" name="action" value="search" class="btn btn-primary">Search</button>
    <select name="category_filter" class="form-select" style="max-width:200px;">
        <option value="">All Category</option>
        @foreach($categories as $category)
            <option value="{{ $category }}" {{ request('category_filter') == $category ? 'selected' : '' }}>
                {{ $category }}
            </option>
        @endforeach
    </select>
    <button type="submit" name="action" value="filter" class="btn btn-success">Filter</button>
    
</form>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var clearBtn = document.getElementById('clearSearch');
    if(clearBtn){
        clearBtn.addEventListener('click', function() {
            document.getElementById('searchInput').value = '';
            document.getElementById('searchForm').submit();
        });
    }
});
</script>

<!-- Add Product Modal -->
<div class="d-flex justify-content-end mb-2">
    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#createProductModal">
        + Add Product
    </button>
</div>

<div class="modal fade" id="createProductModal" tabindex="-1" aria-labelledby="createProductModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('products.store') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createProductModalLabel">Add Product</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
          </div>
          <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <select class="form-select" id="category" name="category" required>
              <option value="">Select Category</option>
              <option value="Shirt">Shirt</option>
              <option value="Skirts">Skirts</option>
              <option value="Outer">Outer</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" class="form-control" id="price" name="price" required>
          </div>
          <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" class="form-control" id="stock" name="stock" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </div>
    </form>
  </div>
</div>



<!-- List of Products -->
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
@foreach($products as $product)
<tr>
    <td>{{ $product->name }}</td>
    <td>{{ $product->category }}</td>
    <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
    <td>{{ $product->stock }}</td>
    <td>
        <!-- Button Edit -->
        <button type="button" class="btn btn-link text-warning p-0 m-0 me-2" data-bs-toggle="modal" data-bs-target="#editProductModal{{ $product->id }}">
            <i class="bi bi-pencil-fill"></i>
        </button>

        <!-- Button Delete -->
        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-link p-0 m-0 text-danger">
                <i class="bi bi-trash-fill"></i>
            </button>
        </form>
    </td>
</tr>

<!-- Modal edit -->
<div class="modal fade" id="editProductModal{{ $product->id }}" tabindex="-1" aria-labelledby="editProductModalLabel{{ $product->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('products.update', $product->id) }}">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editProductModalLabel{{ $product->id }}">Edit Product</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Product Name</label>
            <input type="text" class="form-control" name="name" value="{{ $product->name }}" required>
          </div>
            <div class="mb-3">
            <label class="form-label">Category</label>
            <select class="form-select" name="category" required>
                <option value="">Select Category</option>
                <option value="Shirt" {{ $product->category == 'Shirt' ? 'selected' : '' }}>Shirt</option>
                <option value="Skirts" {{ $product->category == 'Skirts' ? 'selected' : '' }}>Skirts</option>
                <option value="Outer" {{ $product->category == 'Outer' ? 'selected' : '' }}>Outer</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Price</label>
            <input type="number" class="form-control" name="price" value="{{ $product->price }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Stock</label>
            <input type="number" class="form-control" name="stock" value="{{ $product->stock }}" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endforeach

        </tbody>
    </table>

</form>


    {!! $products->withQueryString()->links('pagination::bootstrap-5') !!}

    <form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit" class="btn btn-danger">Logout</button>
</form>

</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
