<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mYkasir - Products</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/@tailwindcss/browser@latest"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .modal {
            transition: all 0.3s ease;
        }
        .table-container {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>
</head>
<body class="bg-gray-50 antialiased h-screen flex overflow-hidden">
    <aside class="bg-white w-64 flex-shrink-0 border-r border-gray-200">
        <div class="p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-8">mYkasir</h2>
            <nav class="flex flex-col space-y-2">
                <a href="/products" class="bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg py-3 px-4 font-medium transition duration-150 ease-in-out flex items-center">
                    <img src="/assets/product.png" alt="Products Icon" class="w-5 h-5 mr-3">
                    Products
                </a>
                <a href="/transactions" class="text-gray-600 hover:bg-gray-100 rounded-lg py-3 px-4 font-medium transition duration-150 ease-in-out flex items-center">
                    <img src="/assets/transaction.png" alt="Transactions Icon" class="w-5 h-5 mr-3">
                    Transactions
                </a>
            </nav>
        </div>
    </aside>

    <div class="flex-1 overflow-y-auto">
        <div class="max-w-full mx-auto p-8">
            <h1 class="text-3xl font-semibold text-gray-800 mb-8">Manage Products</h1>

            <div id="addProductModal" class="fixed z-10 inset-0 overflow-y-auto bg-black/50 backdrop-blur-md hidden modal">
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div class="bg-white rounded-xl shadow-xl w-11/12 md:w-3/4 lg:w-1/2 max-w-lg">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex justify-between items-center">
                                <h2 class="text-xl font-semibold text-gray-800">Add New Product</h2>
                                <span class="close text-gray-400 hover:text-gray-600 cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <form id="addProductForm" class="p-6">
                            <div class="mb-4">
                                <label for="name" class="block text-gray-700 text-sm font-medium mb-2">Name:</label>
                                <input type="text" id="name" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                                <div id="name-error" class="text-red-500 text-xs mt-1" style="display: none;"></div>
                            </div>
                            <div class="mb-4">
                                <label for="price" class="block text-gray-700 text-sm font-medium mb-2">Price:</label>
                                <input type="number" id="price" name="price" min="0" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                                <div id="price-error" class="text-red-500 text-xs mt-1" style="display: none;"></div>
                            </div>
                            <div class="mb-6">
                                <label for="stock" class="block text-gray-700 text-sm font-medium mb-2">Stock:</label>
                                <input type="number" id="stock" name="stock" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                                <div id="stock-error" class="text-red-500 text-xs mt-1" style="display: none;"></div>
                            </div>
                            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-medium py-2.5 px-4 rounded-lg transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">Add Product</button>
                        </form>
                    </div>
                </div>
            </div>

            <div id="editProductModal" class="fixed z-10 inset-0 overflow-y-auto bg-black/50 backdrop-blur-md hidden modal">
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div class="bg-white rounded-xl shadow-xl w-11/12 md:w-3/4 lg:w-1/2 max-w-lg">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex justify-between items-center">
                                <h2 class="text-xl font-semibold text-gray-800">Edit Product</h2>
                                <span class="close text-gray-400 hover:text-gray-600 cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <form id="editProductForm" class="p-6">
                            <div class="mb-4">
                                <label for="edit_name" class="block text-gray-700 text-sm font-medium mb-2">Name:</label>
                                <input type="text" id="edit_name" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                                <div id="edit-name-error" class="text-red-500 text-xs mt-1" style="display: none;"></div>
                            </div>
                            <div class="mb-4">
                                <label for="edit_price" class="block text-gray-700 text-sm font-medium mb-2">Price:</label>
                                <input type="number" id="edit_price" name="price" min="0" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                                <div id="edit-price-error" class="text-red-500 text-xs mt-1" style="display: none;"></div>
                            </div>
                            <div class="mb-6">
                                <label for="edit_stock" class="block text-gray-700 text-sm font-medium mb-2">Stock:</label>
                                <input type="number" id="edit_stock" name="stock" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                                <div id="edit-stock-error" class="text-red-500 text-xs mt-1" style="display: none;"></div>
                            </div>
                            <input type="hidden" id="edit_id" name="id">
                            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2.5 px-4 rounded-lg transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Update Product</button>
                        </form>
                    </div>
                </div>
            </div>

            <div id="errorModal" class="fixed z-10 inset-0 overflow-y-auto bg-black/50 backdrop-blur-md hidden modal">
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div class="bg-white rounded-xl shadow-xl w-11/12 md:w-3/4 lg:w-1/2 max-w-lg">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex justify-between items-center">
                                <h2 class="text-xl font-semibold text-red-600">Error</h2>
                                <span class="close text-gray-400 hover:text-gray-600 cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div id="error-message" class="text-red-600"></div>
                            <button class="close mt-4 w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="successModal" class="fixed z-10 inset-0 overflow-y-auto bg-black/50 backdrop-blur-md hidden modal">
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div class="bg-white rounded-xl shadow-xl w-11/12 md:w-3/4 lg:w-1/2 max-w-lg">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex justify-between items-center">
                                <h2 class="text-xl font-semibold text-green-600">Success</h2>
                                <span class="close text-gray-400 hover:text-gray-600 cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div id="success-message" class="text-green-600"></div>
                            <button class="close mt-4 w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-6 flex justify-between items-center">
                <button id="addProductBtn" class="bg-green-500 hover:bg-green-600 text-white font-medium py-2.5 px-5 rounded-lg transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add New Product
                </button>
            </div>

            <div class="bg-white rounded-xl overflow-hidden table-container w-full">
                <div class="overflow-x-auto w-full">
                    <table id="productsTable" class="w-full">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[10%]">ID</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[40%]">Name</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[15%]">Price</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[15%]">Stock</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[20%]">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-sm text-gray-500 text-center">Loading...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">${product.id}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">${product.name}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-700">${product.price}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-purple-600">${product.stock}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <button data-id="${product.id}" class="edit-btn bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-1.5 px-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-1 mr-2">Edit</button>
                                        <button data-id="${product.id}" class="delete-btn bg-red-500 hover:bg-red-600 text-white font-medium py-1.5 px-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1">Delete</button>
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
            }   else if (price < 0) {
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
                    showErrorModal('Error adding product: ' + error);
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
                    showErrorModal('Error fetching product details: ' + error);
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
                $('#edit-stock-error').text('Stock isrequired').show();
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
                    showErrorModal('Error updating product: ' + error);
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
                        showErrorModal('Error deleting product: ' + error);
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