<?php include_once 'config/database.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Blood Bank Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <nav class="bg-red-600 text-white shadow-lg">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Blood Bank Management</h1>
                <div class="space-x-4">
                    <button onclick="showSection('donors')" class="px-4 py-2 border border-white rounded hover:bg-white hover:text-red-600 transition">Donors</button>
                    <button onclick="showSection('requests')" class="px-4 py-2 border border-white rounded hover:bg-white hover:text-red-600 transition">Requests</button>
                    <button onclick="showSection('inventory')" class="px-4 py-2 border border-white rounded hover:bg-white hover:text-red-600 transition">Inventory</button>
                </div>
            </div>
        </div>
    </nav>

    <main class="container mx-auto px-6 py-8">
        <div id="donors" class="section">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Blood Donors</h2>
            <form id="donor-form" class="max-w-md bg-white p-6 rounded-lg shadow-md mb-8">
                <div class="space-y-4">
                    <input type="text" id="donor-name" placeholder="Full Name" required
                           class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-red-500">
                    <input type="number" id="donor-age" placeholder="Age" required
                           class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-red-500">
                    <select id="donor-blood" required
                            class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="">Select Blood Type</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </select>
                    <button type="submit" 
                            class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700 transition">
                        Register Donor
                    </button>
                </div>
            </form>
            <div id="donors-list" class="grid gap-4 md:grid-cols-2 lg:grid-cols-3"></div>
        </div>

        <div id="requests" class="section hidden">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Blood Requests</h2>
            <form id="request-form" class="max-w-md bg-white p-6 rounded-lg shadow-md mb-8">
                <div class="space-y-4">
                    <input type="text" id="patient-name" placeholder="Patient Name" required
                           class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-red-500">
                    <select id="request-blood" required
                            class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="">Select Blood Type</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </select>
                    <input type="number" id="units-needed" placeholder="Units Needed" required
                           class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-red-500">
                    <button type="submit"
                            class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700 transition">
                        Submit Request
                    </button>
                </div>
            </form>
            <div id="requests-list" class="grid gap-4 md:grid-cols-2 lg:grid-cols-3"></div>
        </div>

        <div id="inventory" class="section hidden">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Blood Inventory</h2>
            <div id="inventory-list" class="grid gap-4 md:grid-cols-2 lg:grid-cols-4"></div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function showSection(sectionId) {
            $('.section').addClass('hidden');
            $(`#${sectionId}`).removeClass('hidden');
        }

        function updateDonorsList() {
            $.get('api/donor/read.php', function(data) {
                const donorsList = $('#donors-list');
                donorsList.empty();
                data.forEach(donor => {
                    donorsList.append(`
                        <div class="bg-white p-4 rounded-lg shadow">
                            <div class="font-semibold">${donor.name}</div>
                            <div>Age: ${donor.age}</div>
                            <div class="text-red-600 font-bold">Blood Type: ${donor.blood_type}</div>
                            <div class="text-sm text-gray-600">
                                ${new Date(donor.donation_date).toLocaleDateString()}
                            </div>
                        </div>
                    `);
                });
            });
        }

        function updateRequestsList() {
            $.get('api/request/read.php', function(data) {
                const requestsList = $('#requests-list');
                requestsList.empty();
                data.forEach(request => {
                    requestsList.append(`
                        <div class="bg-white p-4 rounded-lg shadow">
                            <div class="font-semibold">${request.patient_name}</div>
                            <div class="text-red-600 font-bold">Blood Type: ${request.blood_type}</div>
                            <div>Units Needed: ${request.units_needed}</div>
                            <div class="text-sm text-gray-600">
                                ${new Date(request.request_date).toLocaleDateString()}
                            </div>
                            <div class="mt-2">
                                <span class="px-2 py-1 rounded text-sm ${
                                    request.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                    'bg-green-100 text-green-800'
                                }">${request.status}</span>
                            </div>
                        </div>
                    `);
                });
            });
        }

        function updateInventoryList() {
            $.get('api/inventory/read.php', function(data) {
                const inventoryList = $('#inventory-list');
                inventoryList.empty();
                data.forEach(item => {
                    inventoryList.append(`
                        <div class="bg-white p-4 rounded-lg shadow">
                            <div class="text-red-600 font-bold text-xl">${item.blood_type}</div>
                            <div class="text-gray-600">Available Units: ${item.units}</div>
                        </div>
                    `);
                });
            });
        }

        $('#donor-form').on('submit', function(e) {
            e.preventDefault();
            const donorData = {
                name: $('#donor-name').val(),
                age: $('#donor-age').val(),
                blood_type: $('#donor-blood').val()
            };

            $.ajax({
                url: 'api/donor/create.php',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(donorData),
                success: function(response) {
                    alert(response.message);
                    $('#donor-form')[0].reset();
                    updateDonorsList();
                    updateInventoryList();
                },
                error: function() {
                    alert('Error registering donor');
                }
            });
        });

        $('#request-form').on('submit', function(e) {
            e.preventDefault();
            const requestData = {
                patient_name: $('#patient-name').val(),
                blood_type: $('#request-blood').val(),
                units_needed: $('#units-needed').val()
            };

            $.ajax({
                url: 'api/request/create.php',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(requestData),
                success: function(response) {
                    alert(response.message);
                    $('#request-form')[0].reset();
                    updateRequestsList();
                },
                error: function() {
                    alert('Error creating request');
                }
            });
        });

        // Initial load
        updateDonorsList();
        updateRequestsList();
        updateInventoryList();
    </script>
</body>
</html>