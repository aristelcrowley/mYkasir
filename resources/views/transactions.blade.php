<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mYkasir - Transactions</title>
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
                <a href="/products" class="text-gray-600 hover:bg-gray-100 rounded-lg py-3 px-4 font-medium transition duration-150 ease-in-out flex items-center">
                    <img src="/assets/product.png" alt="Products Icon" class="w-5 h-5 mr-3">
                    Products
                </a>
                <a href="/transactions" class="bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg py-3 px-4 font-medium transition duration-150 ease-in-out flex items-center">
                    <img src="/assets/transaction.png" alt="Transactions Icon" class="w-5 h-5 mr-3">
                    Transactions
                </a>
            </nav>
        </div>
    </aside>

    <div class="flex-1 overflow-y-auto">
        <div class="max-w-full mx-auto p-8">
            <h1 class="text-3xl font-semibold text-gray-800 mb-8">Manage Transactions</h1>

            <div id="addTransactionModal" class="fixed z-10 inset-0 overflow-y-auto bg-black/50 backdrop-blur-md hidden modal">
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div class="bg-white rounded-xl shadow-xl w-11/12 md:w-3/4 lg:w-1/2 max-w-lg">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex justify-between items-center">
                                <h2 class="text-xl font-semibold text-gray-800">Add New Transaction</h2>
                                <span class="close text-gray-400 hover:text-gray-600 cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <form id="addTransactionForm" class="p-6">
                            <div class="mb-4">
                                <label for="product_id" class="block text-gray-700 text-sm font-medium mb-2">Product:</label>
                                <select id="product_id" name="product_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                                    <option value="" disabled selected>Select a product</option>
                                </select>
                                <div id="product-id-error" class="text-red-500 text-xs mt-1" style="display: none;"></div>
                            </div>
                            <div class="mb-6">
                                <label for="quantity" class="block text-gray-700 text-sm font-medium mb-2">Quantity:</label>
                                <input type="number" id="quantity" name="quantity" min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                                <div id="quantity-error" class="text-red-500 text-xs mt-1" style="display: none;"></div>
                            </div>
                            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-medium py-2.5 px-4 rounded-lg transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">Add Transaction</button>
                        </form>
                    </div>
                </div>
            </div>

            <div id="editTransactionModal" class="fixed z-10 inset-0 overflow-y-auto bg-black/50 backdrop-blur-md hidden modal">
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div class="bg-white rounded-xl shadow-xl w-11/12 md:w-3/4 lg:w-1/2 max-w-lg">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex justify-between items-center">
                                <h2 class="text-xl font-semibold text-gray-800">Edit Transaction</h2>
                                <span class="close text-gray-400 hover:text-gray-600 cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <form id="editTransactionForm" class="p-6">
                            <div class="mb-4">
                                <label for="edit_product_id" class="block text-gray-700 text-sm font-medium mb-2">Product:</label>
                                <select id="edit_product_id" name="product_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                                    <option value="" disabled selected>Select a product</option>
                                </select>
                                <div id="edit-product-id-error" class="text-red-500 text-xs mt-1" style="display: none;"></div>
                            </div>
                            <div class="mb-6">
                                <label for="edit_quantity" class="block text-gray-700 text-sm font-medium mb-2">Quantity:</label>
                                <input type="number" id="edit_quantity" name="quantity" min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                                <div id="edit-quantity-error" class="text-red-500 text-xs mt-1" style="display: none;"></div>
                            </div>
                            <input type="hidden" id="edit_id" name="id">
                            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2.5 px-4 rounded-lg transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Update Transaction</button>
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
                <button id="addTransactionBtn" class="bg-green-500 hover:bg-green-600 text-white font-medium py-2.5 px-5 rounded-lg transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add New Transaction
                </button>
            </div>

            <div class="bg-white rounded-xl overflow-hidden table-container w-full">
                <div class="overflow-x-auto w-full">
                    <table id="transactionsTable" class="w-full">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[10%]">ID</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[30%]">Product</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[20%]">Quantity</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[20%]">Total Price</th>
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
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">${transaction.id}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">${transaction.product.name}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-purple-600">${transaction.quantity}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-700">${transaction.total_price}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <button data-id="${transaction.id}" class="edit-btn bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-1.5 px-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-1 mr-2">Edit</button>
                                        <button data-id="${transaction.id}" class="delete-btn bg-red-500 hover:bg-red-600 text-white font-medium py-1.5 px-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1">Delete</button>
                                    </td>
                                </tr>
                            `);
                        });
                    } else {
                        showErrorModal('Failed to fetch transactions: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    showErrorModal('Error fetching transactions: ' + error);
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
                    showErrorModal('Error fetching products for dropdown: ' + error);
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

            if (product_id === null) {
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
                    showErrorModal('Error adding transaction: ' + error);
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
                        setTimeout(function() {
                            $('#edit_product_id').val(transaction.product_id);
                        }, 500); // Small delay to ensure dropdown is populated

                        $('#editTransactionModal').show();
                    } else {
                        showErrorModal('Failed to retrieve transaction details.');
                    }
                },
                error: function(xhr, status, error) {
                    showErrorModal('Error fetching transaction details: ' + error);
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

            if (product_id === null) {
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
                    showErrorModal('Error updating transaction: ' + error);
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
                        showErrorModal('Error deleting transaction: ' + error);
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