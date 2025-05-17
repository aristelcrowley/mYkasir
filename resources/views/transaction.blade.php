<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/@tailwindcss/browser@latest"></script>
</head>
<body class="bg-gray-100 font-inter antialiased">
    <div class="container mx-auto p-6 sm:p-10 md:p-12 lg:p-16">
        <h1 class="text-2xl font-semibold text-indigo-600 text-center mb-8">Manage Transactions</h1>

        <div id="addTransactionModal" class="modal fixed z-10 inset-0 overflow-y-auto bg-black bg-opacity-50 hidden">
            <div class="modal-content bg-white m-auto p-8 rounded-lg shadow-lg w-11/12 md:w-3/4 lg:w-1/2">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Add New Transaction</h2>
                    <span class="close text-gray-500 hover:text-gray-800 text-2xl font-bold cursor-pointer">&times;</span>
                </div>
                <form id="addTransactionForm" class="space-y-4">
                    <div>
                        <label for="product_id" class="block text-gray-700 text-sm font-bold mb-2">Product:</label>
                        <select id="product_id" name="product_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="" disabled selected>Select a product</option>
                        </select>
                        <div id="product-id-error" class="text-red-500 text-xs italic" style="display: none;"></div>
                    </div>
                    <div>
                        <label for="quantity" class="block text-gray-700 text-sm font-bold mb-2">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" min="1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <div id="quantity-error" class="text-red-500 text-xs italic" style="display: none;"></div>
                    </div>
                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add Transaction</button>
                </form>
            </div>
        </div>

        <div id="editTransactionModal" class="modal fixed z-10 inset-0 overflow-y-auto bg-black bg-opacity-50 hidden">
            <div class="modal-content bg-white m-auto p-8 rounded-lg shadow-lg w-11/12 md:w-3/4 lg:w-1/2">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Edit Transaction</h2>
                    <span class="close text-gray-500 hover:text-gray-800 text-2xl font-bold cursor-pointer">&times;</span>
                </div>
                <form id="editTransactionForm" class="space-y-4">
                    <div>
                        <label for="edit_product_id" class="block text-gray-700 text-sm font-bold mb-2">Product:</label>
                        <select id="edit_product_id" name="product_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                             <option value="" disabled selected>Select a product</option>
                        </select>
                        <div id="edit-product-id-error" class="text-red-500 text-xs italic" style="display: none;"></div>
                    </div>
                    <div>
                        <label for="edit_quantity" class="block text-gray-700 text-sm font-bold mb-2">Quantity:</label>
                        <input type="number" id="edit_quantity" name="quantity" min="1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                         <div id="edit-quantity-error" class="text-red-500 text-xs italic" style="display: none;"></div>
                    </div>
                    <input type="hidden" id="edit_id" name="id">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update Transaction</button>
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
                <button id="addTransactionBtn" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add New Transaction</button>
                <a href="/product" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Go to Product</a>
            </div>
            <div class="overflow-x-auto">
                <table id="transactionsTable" class="min-w-full leading-normal shadow-md rounded-lg overflow-hidden">
                    <thead class="bg-gray-200 text-gray-700">
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wider">ID</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wider">Product</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wider">Quantity</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase tracking-wider">Total Price</th>
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
        // Fetch and display transactions
        function fetchTransactions() {
            $.ajax({
                url: '/api/transactions',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        let transactions = response.data;
                        let tableBody = $('#transactionsTable').find('tbody');
                        tableBody.empty();

                        transactions.forEach(transaction => {
                            tableBody.append(`
                                <tr>
                                    <td class="px-5 py-5 border-b border-gray-200 text-sm"><span class="font-italic text-gray-800">${transaction.id}</span></td>
                                    <td class="px-5 py-5 border-b border-gray-200 text-sm"><span class="font-semibold text-gray-900">${transaction.product.name}</span></td>
                                    <td class="px-5 py-5 border-b border-gray-200 text-sm"><span class="font-oblique font-sans text-purple-600">${transaction.quantity}</span></td>
                                    <td class="px-5 py-5 border-b border-gray-200 text-sm"><span class="font-semibold text-blue-700">${transaction.total_price}</span></td>
                                    <td class="px-5 py-5 border-b border-gray-200 text-sm">
                                        <button data-id="${transaction.id}" class="edit-btn bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline mr-2">Edit</button>
                                        <button data-id="${transaction.id}" class="delete-btn bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline">Delete</button>
                                    </td>
                                </tr>
                            `);
                        });
                    } else {
                        showErrorModal('Failed to fetch transactions: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    showErrorModal('Error fetching transactions: ' . error);
                }
            });
        }

        // Function to populate the product dropdown during add/edit
        function populateProductDropdown(dropdownId) {
            $.ajax({
                url: '/api/products-list',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        let products = response.data;
                        let dropdown = $('#' + dropdownId);
                        dropdown.empty().append('<option value="" disabled selected>Select a product</option>'); // Clear and add default option

                        products.forEach(product => {
                            dropdown.append(`
                                <option value="${product.id}">${product.name}</option>
                            `);
                        });
                    } else {
                        showErrorModal('Failed to fetch products for dropdown: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    showErrorModal('Error fetching products for dropdown: ' . error);
                }
            });
        }

        // Add Transaction
        $('#addTransactionBtn').click(function() {
            $('#addTransactionModal').show();
            populateProductDropdown('product_id'); // Populate dropdown when adding
        });

        $('#addTransactionModal .close').click(function() {
            $('#addTransactionModal').hide();
            $('#addTransactionForm')[0].reset();
            $('.error-message').hide();
        });

        $('#addTransactionForm').submit(function(event) {
            event.preventDefault();

            let product_id = $('#product_id').val();
            let quantity = $('#quantity').val();
            let hasErrors = false;

            $('.error-message').hide(); // Clear previous errors

            if (product_id === '') {
                $('#product-id-error').text('Please select a product').show();
                hasErrors = true;
            }
            if (quantity === '' || quantity < 1) {
                $('#quantity-error').text('Quantity is required and must be greater than 0').show();
                hasErrors = true;
            }

            if (hasErrors) {
                return;
            }

            $.ajax({
                url: '/api/transactions',
                type: 'POST',
                dataType: 'json',
                data: {
                    product_id: product_id,
                    quantity: quantity
                },
                success: function(response) {
                    if (response.status === 'success') {
                        $('#addTransactionModal').hide();
                        $('#addTransactionForm')[0].reset();
                        fetchTransactions();
                        showSuccessModal(response.message);
                    } else {
                        showErrorModal(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    showErrorModal('Error adding transaction: ' . error);
                }
            });
        });

        // Edit Transaction
        $(document).on('click', '.edit-btn', function() {
            let id = $(this).data('id');
            $.ajax({
                url: '/api/transactions/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        let transaction = response.data;
                        $('#edit_id').val(transaction.id);
                        $('#edit_quantity').val(transaction.quantity);

                        // Populate the product dropdown in the edit modal
                        populateProductDropdown('edit_product_id');

                        // Set the selected product in the dropdown
                        $('#edit_product_id').val(transaction.product_id);

                        $('#editTransactionModal').show();
                    } else {
                        showErrorModal('Failed to retrieve transaction details.');
                    }
                },
                error: function(xhr, status, error) {
                    showErrorModal('Error fetching transaction details: ' . error);
                }
            });
        });

        $('#editTransactionModal .close').click(function() {
            $('#editTransactionModal').hide();
            $('#editTransactionForm')[0].reset();
            $('.error-message').hide();
        });

        $('#editTransactionForm').submit(function(event) {
            event.preventDefault();

            let id = $('#edit_id').val();
            let product_id = $('#edit_product_id').val();
            let quantity = $('#edit_quantity').val();
            let hasErrors = false;

            $('.error-message').hide(); // Clear previous errors

            if (product_id === '') {
                $('#edit-product-id-error').text('Please select a product').show();
                hasErrors = true;
            }
            if (quantity === '' || quantity < 1) {
                $('#edit-quantity-error').text('Quantity is required and must be greater than 0').show();
                hasErrors = true;
            }

            if (hasErrors) {
                return;
            }

            $.ajax({
                url: '/api/transactions/' + id,
                type: 'PUT',
                dataType: 'json',
                data: {
                    product_id: product_id,
                    quantity: quantity
                },
                success: function(response) {
                    if (response.status === 'success') {
                        $('#editTransactionModal').hide();
                        $('#editTransactionForm')[0].reset();
                        fetchTransactions();
                        showSuccessModal(response.message);
                    } else {
                        showErrorModal(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    showErrorModal('Error updating transaction: ' . error);
                }
            });
        });

        // Delete Transaction
        $(document).on('click', '.delete-btn', function() {
            let id = $(this).data('id');
            if (confirm('Are you sure you want to delete this transaction?')) {
                $.ajax({
                    url: '/api/transactions/' + id,
                    type: 'DELETE',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            fetchTransactions();
                            showSuccessModal(response.message);
                        } else {
                            showErrorModal(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        showErrorModal('Error deleting transaction: ' . error);
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

        // Initial fetch of transactions
        fetchTransactions();
    });
    </script>
</body>
</html>
