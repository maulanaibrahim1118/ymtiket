<script>
  function formValidation() {
    var noAsset = document.getElementById('no_asset').value;
    var namaBarang = document.getElementById('nama_barang').value;
    var merk = document.getElementById('merk').value;
    var model = document.getElementById('model').value;
    var serialNumber = document.getElementById('serial_number').value;

    var errors = [];

    if (noAsset.length > 20) {
      errors.push('Asset Number must be a maximum of 20 characters!');
    }

    if (namaBarang.length < 3) {
      errors.push('Item Name must be at least 3 characters!');
    }

    if (namaBarang.length > 30) {
      errors.push('Item Name must be a maximum of 30 characters!');
    }

    if (merk.length > 30) {
      errors.push('Brand must be a maximum of 30 characters!');
    }

    if (model.length > 30) {
      errors.push('Model/Type must be a maximum of 30 characters!');
    }

    if (serialNumber.length > 30) {
      errors.push('Serial Number must be a maximum of 30 characters!');
    }

    if (errors.length > 0) {
      errors.forEach(showToast);
      return false;
    }

    var lanjut = confirm('Are you sure the data entered is correct?');
    return lanjut;
  }

  function showToast(message) {
    const toastBody = document.createElement('div');
    toastBody.className = 'toast-body';
    toastBody.textContent = message;

    const toastElement = document.createElement('div');
    toastElement.className = 'toast';
    toastElement.appendChild(toastBody);

    const toastContainer = document.getElementById('toastPlacement');
    toastContainer.appendChild(toastElement);

    const newToast = new bootstrap.Toast(toastElement);
    newToast.show();
  }
</script>