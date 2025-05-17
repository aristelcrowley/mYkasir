<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/@tailwindcss/browser@latest"></script>
</head>
<body class="bg-gray-100 font-inter antialiased">
    <div class="container mx-auto p-6 sm:p-10 md:p-12 lg:p-16">
        <h1 class="text-2xl font-semibold text-blue-600 text-center mb-8">Manage Products</h1>

        <div id="addProductModal" class="modal fixed z-10 inset-0 overflow-y-auto bg-black bg-opacity-50 hidden">
            <div class="modal-content bg-white m-auto p-8 rounded-lg shadow-lg w-11/12 md:w-3/4 lg:w-1/2">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Add New Product</h2>
                    <span class="close text-gray-500 hover:text-gray-800 text-2xl font-bold cursor-pointer">&times;</span>
                </div>
                <form id="addProductForm" class="space-y-4">
                    <div>
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
                        <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <div id="name-error" class="text-red-500 text-xs italic" style="display: none;"></div>
                    </div>
                    <div>
                        <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Price:</label>
                        <input type="number" id="price" name="price" min="0" step="0.01" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <div id="price-error" class="text-red-500 text-xs italic" style="display: none;"></div>
                    </div>
                    <div>
                        <label for="stock" class="block text-gray-700 text-sm font-bold mb-2">Stock:</label>
                        <input type="number" id="stock" name="stock" min="0" class="shadowappearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <div id="stock-error" class="text-red-500 text-xs italic" style="display: none;"></div>
                    </div>
                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add Product</button>
                </form>
            </div>
        </div>

        <div id="editProductModal" class="modal fixed z-10 inset-0 overflow-y-auto bg-black bg-opacity-50 hidden">
            <div class="modal-content bg-white m-auto p-8 rounded-lg shadow-lg w-11/12 md:w-3/4 lg:w-1/2">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Edit Product</h2>
                    <span class="close text-gray-500 hover:text-gray-800 text-2xl font-bold cursor-pointer">&times;</span>
                </div>
                <form id="editProductForm" class="space-y-4">
                    <div>
                        <label for="edit_name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
                        <input type="text" id="edit_name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <div id="edit-name-error" class="text-red-500 text-xs italic" style="display: none;"></div>
                    </div>
                    <div>
                        <label for="edit_price" class="block text-gray-700 text-sm font-bold mb-2">Price:</label>
                        <input type="number" id="edit_price" name="price" min="0" step="0.01" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <div id="edit-price-error" class="text-red-500 text-xs italic" style="display: none;"></div>
                    </div>
                    <div>
                        <label for="edit_stock" class="block text-gray-700 text-sm font-bold mb-2">Stock:</label>
                        <input type="number" id="edit_stock" name="stock" min="0" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <div id="edit-stock-error" class="text-red-500 text-xs italic" style="display: none;"></div>
                    </div>
                    <input type="hidden" id="edit_id" name="id">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update Product</button>
                </form>
            </div>
        </div>

        <div id="errorModal" class="modal fixed z-10 inset-0 overflow-y-auto bg-black bg-opacity-50 hidden">
            <div class="modal-content bg-white m-auto p-8 rounded-lg shadow-lg w-11/12 md:w-3/4 lg:w-1/2">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Error</h2>
                    <span class="close text-gray-500 hover:text-gray-800 text-2xl font-bold cursor-pointer">&times;</span>
                </div>
                <div id="error-message" class="text-red-600"></div>
            </div>
        </div>

        <div id="successModal" class="modal fixed z-10 inset-0 overflow-y-auto bg-black bg-opacity-50 hidden">
            <div class="modal-content bg-white m-auto p-8 rounded-lg shadow-lg w-11/12 md:w-3/4 lg:w-1/2">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Success</h2>
                    <span class="close text-gray-500 hover:text-gray-800 text-2xl font-bold cursor-pointer">&times;</span>
                </div>
                <div id="success-message" class="text-green-600"></div>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <button id="addProductBtn" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add New Product</button>
                <a href="/transaction" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Go to Transaction</a>
            </div>
            <div class="overflow-x-auto">
                <table id="productsTable" class="min-w-full leading-normal shadow-md rounded-lg overflow-hidden">
                    <thead class="bg-gray-200 text-gray-700">
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wider">ID</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wider">Name</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wider">Price</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wider">Stock</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <tr>
                            <td colspan="5" class="px-5 py-5 border-b border-gray-200 text-sm">Loading...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        // Fetch and display products
        function fetchProducts() {
            $.ajax({
                url: '/api/products',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        let products = response.data;
                        let tableBody = $('#productsTable').find('tbody');
                        tableBody.empty(); // Clear loading message and any existing data

                        products.forEach(product => {
                            tableBody.append(`
                                <tr>
                                    <td class="px-5 py-5 border-b border-gray-200 text-sm"><span class="font-italic text-gray-800">${product.id}</span></td>
                                    <td class="px-5 py-5 border-b border-gray-200 text-sm"><span class="font-semibold text-gray-900">${product.name}</span></td>
                                    <td class="px-5 py-5 border-b border-gray-200 text-sm"><span class="font-semibold text-blue-700">${product.price}</span></td>
                                    <td class="px-5 py-5 border-b border-gray-200 text-sm"><span class="font-oblique font-sans text-purple-600">${product.stock}</span></td>
                                    <td class="px-5 py-5 border-b border-gray-200 text-sm">
                                        <button data-id="${product.id}" class="edit-btn bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline mr-2">Edit</button>
                                        <button data-id="${product.id}" class="delete-btn bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline">Delete</button>
                                    </td>
                                </tr>
                            `);
                        });
                    } else {
                        showErrorModal('Failed to fetch products: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    showErrorModal('Error fetching products: ' + error);
                }
            });
        }

        // Add Product
        $('#addProductBtn').click(function() {
            $('#addProductModal').show();
        });

        $('#addProductModal .close').click(function() {
            $('#addProductModal').hide();
            $('#addProductForm')[0].reset(); //reset form
             $('.error-message').hide();
        });

        $('#addProductForm').submit(function(event) {
            event.preventDefault();

            let name = $('#name').val();
            let price = $('#price').val();
            let stock = $('#stock').val();
            let hasErrors = false;

            $('.error-message').hide(); // Clear previous errors

            if (name.trim() === '') {
                $('#name-error').text('Name is required').show();
                hasErrors = true;
            }
            if (price === '') {
                $('#price-error').text('Price is required').show();
                hasErrors = true;
            }  else if (price < 0) {
                $('#price-error').text('Price must be greater than or equal to 0').show();
                hasErrors = true;
            }
            if (stock === '') {
                $('#stock-error').text('Stock is required').show();
                hasErrors = true;
            } else if (stock < 0) {
                $('#stock-error').text('Stock must be greater than or equal to 0').show();
                hasErrors = true;
            }

            if (hasErrors) {
                return;
            }

            $.ajax({
                url: '/api/products',
                type: 'POST',
                dataType: 'json',
                data: {
                    name: name,
                    price: price,
                    stock: stock
                },
                success: function(response) {
                    if (response.status === 'success') {
                        $('#addProductModal').hide();
                        $('#addProductForm')[0].reset();
                        fetchProducts();
                        showSuccessModal(response.message);
                    } else {
                        showErrorModal(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    showErrorModal('Error adding product: ' . error);
                }
            });
        });

        // Edit Product
        $(document).on('click', '.edit-btn', function() {
            let id = $(this).data('id');
            $.ajax({
                url: '/api/products/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        let product = response.data;
                        $('#edit_id').val(product.id);
                        $('#edit_name').val(product.name);
                        $('#edit_price').val(product.price);
                        $('#edit_stock').val(product.stock);
                        $('#editProductModal').show();
                    } else {
                        showErrorModal('Failed to retrieve product details.');
                    }
                },
                error: function(xhr, status, error) {
                    showErrorModal('Error fetching product details: ' . error);
                }
            });
        });

        $('#editProductModal .close').click(function() {
            $('#editProductModal').hide();
            $('#editProductForm')[0].reset();
            $('.error-message').hide();
        });

        $('#editProductForm').submit(function(event) {
            event.preventDefault();

            let id = $('#edit_id').val();
            let name = $('#edit_name').val();
            let price = $('#edit_price').val();
            let stock = $('#edit_stock').val();
            let hasErrors = false;

             $('.error-message').hide(); // Clear previous errors

            if (name.trim() === '') {
                $('#edit-name-error').text('Name is required').show();
                hasErrors = true;
            }
            if (price === '') {
                $('#edit-price-error').text('Price is required').show();
                hasErrors = true;
            } else if (price < 0) {
                $('#edit-price-error').text('Price must be greater than or equal to 0').show();
                hasErrors = true;
            }
            if (stock === '') {
                $('#edit-stock-error').text('Stock is required').show();
                hasErrors = true;
            } else if (stock < 0) {
                $('#edit-stock-error').text('Stock must be greater than or equal to 0').show();
                hasErrors = true;
            }

            if (hasErrors) {
                return;
            }

            $.ajax({
                url: '/api/products/' + id,
                type: 'PUT',
                dataType: 'json',
                data: {
                    name: name,
                    price: price,
                    stock: stock
                },
                success: function(response) {
                    if (response.status === 'success') {
                        $('#editProductModal').hide();
                        $('#editProductForm')[0].reset();
                        fetchProducts();
                        showSuccessModal(response.message);
                    } else {
                        showErrorModal(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    showErrorModal('Error updating product: ' . error);
                }
            });
        });

        // Delete Product
        $(document).on('click', '.delete-btn', function() {
            let id = $(this).data('id');
            if (confirm('Are you sure you want to delete this product?')) {
                $.ajax({
                    url: '/api/products/' + id,
                    type: 'DELETE',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            fetchProducts();
                            showSuccessModal(response.message);
                        } else {
                            showErrorModal(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        showErrorModal('Error deleting product: ' . error);
                    }
                });
            }
        });

        // Generic error modal display function
        function showErrorModal(message) {
            $('#error-message').text(message);
            $('#errorModal').show();
        }

        // Generic success modal display function
        function showSuccessModal(message) {
            $('#success-message').text(message);
            $('#successModal').show();
        }

        // Close modals
        $('.modal .close').click(function() {
            $(this).closest('.modal').hide();
        });

        $(window).click(function(event) {
            if ($(event.target).hasClass('modal')) {
                $('.modal').hide();
            }
        });

        // Initial fetch of products
        fetchProducts();
    });
    </script>
</body>
</html>
