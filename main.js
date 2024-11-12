// Data management
const storage = {
  donors: JSON.parse(localStorage.getItem('donors')) || [],
  requests: JSON.parse(localStorage.getItem('requests')) || [],
  inventory: JSON.parse(localStorage.getItem('inventory')) || {
    'A+': 0, 'A-': 0, 'B+': 0, 'B-': 0,
    'AB+': 0, 'AB-': 0, 'O+': 0, 'O-': 0
  }
};

// Save data to localStorage
function saveData() {
  localStorage.setItem('donors', JSON.stringify(storage.donors));
  localStorage.setItem('requests', JSON.stringify(storage.requests));
  localStorage.setItem('inventory', JSON.stringify(storage.inventory));
}

// UI Management
window.showSection = function(sectionId) {
  document.querySelectorAll('.section').forEach(section => {
    section.classList.add('hidden');
  });
  document.getElementById(sectionId).classList.remove('hidden');
};

// Donors Management
document.getElementById('donor-form').addEventListener('submit', function(e) {
  e.preventDefault();
  const donor = {
    id: Date.now(),
    name: document.getElementById('donor-name').value,
    age: document.getElementById('donor-age').value,
    bloodType: document.getElementById('donor-blood').value,
    donationDate: new Date().toISOString()
  };
  
  storage.donors.push(donor);
  storage.inventory[donor.bloodType]++;
  saveData();
  this.reset();
  updateDonorsList();
  updateInventoryList();
});

function updateDonorsList() {
  const donorsList = document.getElementById('donors-list');
  donorsList.innerHTML = storage.donors.map(donor => `
    <div class="card">
      <div>Name: ${donor.name}</div>
      <div>Age: ${donor.age}</div>
      <div class="blood-type">Blood Type: ${donor.bloodType}</div>
      <div>Donation Date: ${new Date(donor.donationDate).toLocaleDateString()}</div>
    </div>
  `).join('');
}

// Requests Management
document.getElementById('request-form').addEventListener('submit', function(e) {
  e.preventDefault();
  const request = {
    id: Date.now(),
    patientName: document.getElementById('patient-name').value,
    bloodType: document.getElementById('request-blood').value,
    unitsNeeded: document.getElementById('units-needed').value,
    status: 'pending',
    requestDate: new Date().toISOString()
  };
  
  storage.requests.push(request);
  saveData();
  this.reset();
  updateRequestsList();
});

function updateRequestsList() {
  const requestsList = document.getElementById('requests-list');
  requestsList.innerHTML = storage.requests.map(request => `
    <div class="card">
      <div>Patient: ${request.patientName}</div>
      <div class="blood-type">Blood Type: ${request.bloodType}</div>
      <div>Units Needed: ${request.unitsNeeded}</div>
      <div>Request Date: ${new Date(request.requestDate).toLocaleDateString()}</div>
      <div class="status ${request.status}">${request.status}</div>
      ${request.status === 'pending' ? `
        <button onclick="processRequest(${request.id})">
          ${storage.inventory[request.bloodType] >= request.unitsNeeded ? 'Approve' : 'Insufficient Stock'}
        </button>
      ` : ''}
    </div>
  `).join('');
}

window.processRequest = function(requestId) {
  const request = storage.requests.find(r => r.id === requestId);
  if (storage.inventory[request.bloodType] >= request.unitsNeeded) {
    storage.inventory[request.bloodType] -= request.unitsNeeded;
    request.status = 'approved';
    saveData();
    updateRequestsList();
    updateInventoryList();
  }
};

// Inventory Management
function updateInventoryList() {
  const inventoryList = document.getElementById('inventory-list');
  inventoryList.innerHTML = Object.entries(storage.inventory).map(([type, units]) => `
    <div class="card">
      <div class="blood-type">Blood Type: ${type}</div>
      <div>Available Units: ${units}</div>
    </div>
  `).join('');
}

// Initial render
updateDonorsList();
updateRequestsList();
updateInventoryList();