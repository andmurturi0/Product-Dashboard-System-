// JS FOR PRODUCTS
function confirmDelete(id, type) {
    Swal.fire({
        title: 'A jeni të sigurt?',
        text: "Ky veprim nuk mund të kthehet mbrapa!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e74c3c',
        cancelButtonColor: '#95a5a6',
        confirmButtonText: 'Po, fshije!',
        cancelButtonText: 'Anulo'
    }).then((result) => {
        if (result.isConfirmed) {
            if (type === 'product') {
                window.location.href = 'delete_product.php?id=' + id;
            } else if (type === 'user') {
                window.location.href = 'delete_user.php?id=' + id;
            }
        }
    })
}

function editProduct(id) {
    window.location.href = 'dashboard.php?view=add&edit_id=' + id;
}

// Plotësimi i formës nëse kemi edit_id
window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    
    // Shfaq alerta nëse ka suksese në URL
    if (urlParams.has('success')) {
        let msg = urlParams.get('success');
        let icon = 'success';
        let title = 'Sukses!';

        if (msg === 'ProduktiUpërditësua') msg = 'Produkti u përditësua me sukses!';
        else if (msg === 'Produktregjistruar') msg = 'Produkti u regjistrua me sukses!';
        else if (msg === 'ProfileUpdated') msg = 'Të dhënat e profilit tuaj u përditësuan me sukses!';
        else if (msg === 'UserDeleted') {
            msg = 'Përdoruesi u fshi me sukses nga sistemi.';
            title = 'U fshi!';
        }
        
        Swal.fire({
            icon: icon,
            title: title,
            text: msg,
            timer: 3000,
            showConfirmButton: false
        });
    }

    if (urlParams.has('deleted')) {
        Swal.fire({
            icon: 'success',
            title: 'U fshi!',
            text: 'Artikulli u fshi me sukses nga sistemi.',
            timer: 3000,
            showConfirmButton: false
        });
    }

    const editId = urlParams.get('edit_id');
    if (editId && document.querySelector('.product-form')) {
        fetch('get_product_details.php?id=' + editId)
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
                const form = document.querySelector('.product-form');
                form.action = 'process_product.php?update_id=' + editId;
                form.querySelector('input[name="product_name"]').value = data.product.product_name;
                form.querySelector('select[name="category"]').value = data.product.category;
                form.querySelector('input[name="monthly_price"]').value = data.product.monthly_price;
                form.querySelector('input[name="registration_date"]').value = data.product.registration_date;
                form.querySelector('textarea[name="product_features"]').value = data.product.product_features;
                form.querySelector('input[name="is_available"]').checked = data.product.is_available == 1;
                form.querySelector('.submit-btn').innerText = "Përditëso Produktin";
                form.querySelector('.submit-btn').style.background = "rgba(52, 152, 219, 0.7)";
                
                form.onsubmit = function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'A jeni të sigurt?',
                        text: "Dëshironi të ruani ndryshimet për këtë produkt?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3498db',
                        cancelButtonColor: '#95a5a6',
                        confirmButtonText: 'Po, përditësoje!',
                        cancelButtonText: 'Anulo'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.onsubmit = null;
                            form.submit();
                        }
                    });
                };
                
                const cancelBtn = document.createElement('button');
                cancelBtn.type = "button";
                cancelBtn.innerText = "Anulo Përditësimin";
                cancelBtn.className = "submit-btn";
                cancelBtn.style.background = "rgba(108, 117, 125, 0.7)";
                cancelBtn.style.marginTop = "10px";
                cancelBtn.onclick = function() { window.location.href = 'dashboard.php?view=list'; };
                form.appendChild(cancelBtn);

                window.scrollTo(0, 0);
            }
        });
    }
}

// JS FOR SEARCH FILTER
function filterProducts() {
    const input = document.getElementById('productSearch');
    const filter = input.value.toLowerCase();
    const grid = document.getElementById('productGrid');
    const cards = grid.getElementsByClassName('product-card');

    for (let i = 0; i < cards.length; i++) {
        const name = cards[i].getAttribute('data-name');
        const category = cards[i].getAttribute('data-category');
        
        if (name.includes(filter) || category.includes(filter)) {
            cards[i].style.display = "";
        } else {
            cards[i].style.display = "none";
        }
    }
}

// JS FOR USERS (PROFILE)
function editUser(id) {
    const row = document.getElementById('user-row-' + id);
    
    const nameCell = row.querySelector('.u-name');
    const nameVal = nameCell.innerText;
    nameCell.innerHTML = `<input type="text" class="inline-edit-input" value="${nameVal}" style="width: 100%;">`;

    const emailCell = row.querySelector('.u-email');
    const emailVal = emailCell.innerText;
    emailCell.innerHTML = `<input type="email" class="inline-edit-input" value="${emailVal}" style="width: 100%;">`;

    document.getElementById('user-btn-edit-' + id).style.display = 'none';
    document.getElementById('user-btn-save-' + id).style.display = 'inline-block';
    document.getElementById('user-btn-cancel-' + id).style.display = 'inline-block';
}

function saveUser(id) {
    Swal.fire({
        title: 'A jeni të sigurt?',
        text: "Dëshironi të ruani ndryshimet për këtë përdorues?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3498db',
        cancelButtonColor: '#95a5a6',
        confirmButtonText: 'Po, ruaje!',
        cancelButtonText: 'Anulo'
    }).then((result) => {
        if (result.isConfirmed) {
            const row = document.getElementById('user-row-' + id);
            const name = row.querySelector('.u-name input').value;
            const email = row.querySelector('.u-email input').value;

            const formData = new FormData();
            formData.append('id', id);
            formData.append('name', name);
            formData.append('email', email);

            fetch('update_user_inline.php', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'U përditësua!',
                        text: 'Të dhënat e përdoruesit u ruajtën.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => location.reload());
                } else { 
                    Swal.fire('Gabim!', data.message, 'error');
                }
            });
        }
    });
}

function cancelEdit(id) {
    location.reload();
}
