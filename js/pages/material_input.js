// js/pages/material_input.js
// JavaScript สำหรับหน้ารับวัสดุเข้าคลัง

$(document).ready(function() {
    // โหลดรายการวัสดุในฟอร์ม
    loadMaterialOptions();
    
    // Event handlers
    setupEventHandlers();
    
    // คำนวณราคาเมื่อเปลี่ยนจำนวนหรือราคา
    $(document).on('input', '.quantity-input, .price-input', function() {
        calculateRowTotal($(this).closest('tr'));
        calculateGrandTotal();
    });
    
    // เมื่อเลือกวัสดุ ให้แสดงหน่วย
    $(document).on('change', '.material-select', function() {
        const selectedOption = $(this).find('option:selected');
        const unit = selectedOption.data('unit') || '';
        $(this).closest('tr').find('.unit-display').val(unit);
    });
});

// โหลดรายการวัสดุสำหรับ dropdown
function loadMaterialOptions() {
    $.ajax({
        url: 'api/materials.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                let options = '<option value="">-- เลือกวัสดุ --</option>';
                response.data.forEach(function(material) {
                    options += `<option value="${material.id}" data-unit="${material.unit}" data-price="${material.price}">
                               ${material.code} - ${material.name}
                               </option>`;
                });
                
                $('.material-select').html(options);
            }
        },
        error: function() {
            showAlert('error', 'ไม่สามารถโหลดรายการวัสดุได้');
        }
    });
}

// ตั้งค่า Event Handlers
function setupEventHandlers() {
    // Submit form
    $('#materialReceiptForm').on('submit', function(e) {
        e.preventDefault();
        if (validateForm()) {
            submitForm();
        }
    });
    
    // เพิ่ม tooltip
    $('[data-toggle="tooltip"]').tooltip();
}

// เพิ่มแถวใหม่ในตาราง
function addMaterialRow() {
    const tbody = $('#materialTableBody');
    const rowCount = tbody.find('tr').length;
    
    const newRow = `
        <tr>
            <td>
                <select class="form-control material-select" name="materials[${rowCount}][material_id]" required>
                    <option value="">-- เลือกวัสดุ --</option>
                </select>
            </td>
            <td>
                <input type="text" class="form-control unit-display" readonly>
            </td>
            <td>
                <input type="number" class="form-control quantity-input" 
                       name="materials[${rowCount}][quantity]" min="1" required>
            </td>
            <td>
                <input type="number" class="form-control price-input" 
                       name="materials[${rowCount}][unit_price]" step="0.01" min="0">
            </td>
            <td>
                <input type="number" class="form-control total-price" 
                       name="materials[${rowCount}][total_price]" readonly>
            </td>
            <td>
                <input type="date" class="form-control" name="materials[${rowCount}][expiry_date]">
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `;
    
    tbody.append(newRow);
    
    // โหลดรายการวัสดุสำหรับแถวใหม่
    loadMaterialOptions();
}

// ลบแถว
function removeRow(button) {
    const row = $(button).closest('tr');
    const tbody = $('#materialTableBody');
    
    if (tbody.find('tr').length > 1) {
        row.remove();
        calculateGrandTotal();
        updateRowIndices();
    } else {
        showAlert('warning', 'ต้องมีรายการวัสดุอย่างน้อย 1 รายการ');
    }
}

// อัพเดท index ของแถว
function updateRowIndices() {
    $('#materialTableBody tr').each(function(index) {
        $(this).find('input, select').each(function() {
            const name = $(this).attr('name');
            if (name && name.includes('materials[')) {
                const newName = name.replace(/materials\[\d+\]/, `materials[${index}]`);
                $(this).attr('name', newName);
            }
        });
    });
}

// คำนวณราคารวมของแต่ละแถว
function calculateRowTotal(row) {
    const quantity = parseFloat(row.find('.quantity-input').val()) || 0;
    const price = parseFloat(row.find('.price-input').val()) || 0;
    const total = quantity * price;
    
    row.find('.total-price').val(total.toFixed(2));
}

// คำนวณราคารวมทั้งหมด
function calculateGrandTotal() {
    let grandTotal = 0;
    
    $('.total-price').each(function() {
        const value = parseFloat($(this).val()) || 0;
        grandTotal += value;
    });
    
    $('#grandTotal').val(grandTotal.toFixed(2));
}

// ตรวจสอบความถูกต้องของฟอร์ม
function validateForm() {
    // ตรวจสอบว่ามีรายการวัสดุหรือไม่
    const hasItems = $('#materialTableBody tr').length > 0;
    if (!hasItems) {
        showAlert('error', 'กรุณาเพิ่มรายการวัสดุอย่างน้อย 1 รายการ');
        return false;
    }
    
    // ตรวจสอบว่าเลือกวัสดุแล้วหรือไม่
    let hasValidItems = true;
    $('.material-select').each(function() {
        if (!$(this).val()) {
            hasValidItems = false;
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });
    
    if (!hasValidItems) {
        showAlert('error', 'กรุณาเลือกวัสดุในทุกรายการ');
        return false;
    }
    
    // ตรวจสอบจำนวน
    let hasValidQuantity = true;
    $('.quantity-input').each(function() {
        const quantity = parseFloat($(this).val()) || 0;
        if (quantity <= 0) {
            hasValidQuantity = false;
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });
    
    if (!hasValidQuantity) {
        showAlert('error', 'กรุณาใส่จำนวนที่ถูกต้องในทุกรายการ');
        return false;
    }
    
    return true;
}

// ส่งข้อมูลฟอร์ม
function submitForm() {
    const formData = new FormData($('#materialReceiptForm')[0]);
    
    // แสดง loading
    showLoading(true);
    
    $.ajax({
        url: 'process/save_receipt.php',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            showLoading(false);
            
            if (response.success) {
                showAlert('success', 'บันทึกการรับวัสดุเรียบร้อยแล้ว');
                
                // Redirect หลังจาก 2 วินาที
                setTimeout(function() {
                    window.location.href = 'warehouse.php';
                }, 2000);
            } else {
                showAlert('error', response.message || 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
            }
        },
        error: function() {
            showLoading(false);
            showAlert('error', 'เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์');
        }
    });
}

// บันทึกวัสดุใหม่
function saveMaterial() {
    const formData = new FormData($('#newMaterialForm')[0]);
    
    $.ajax({
        url: 'process/save_material.php',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#addMaterialModal').modal('hide');
                $('#newMaterialForm')[0].reset();
                showAlert('success', 'เพิ่มวัสดุใหม่เรียบร้อยแล้ว');
                
                // โหลดรายการวัสดุใหม่
                loadMaterialOptions();
            } else {
                showAlert('error', response.message || 'เกิดข้อผิดพลาดในการเพิ่มวัสดุ');
            }
        },
        error: function() {
            showAlert('error', 'เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์');
        }
    });
}

// แสดงข้อความแจ้งเตือน
function showAlert(type, message) {
    const alertClass = {
        'success': 'alert-success',
        'error': 'alert-danger',
        'warning': 'alert-warning',
        'info': 'alert-info'
    };
    
    const alertHtml = `
        <div class="alert ${alertClass[type]} alert-dismissible fade show" role="alert">
            <strong>${type === 'success' ? 'สำเร็จ!' : type === 'error' ? 'ข้อผิดพลาด!' : 'แจ้งเตือน!'}</strong> ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    `;
    
    // แสดงที่ด้านบนของ form
    $('#materialReceiptForm').prepend(alertHtml);
    
    // เลื่อนขึ้นด้านบน
    $('html, body').animate({
        scrollTop: 0
    }, 500);
    
    // ซ่อนหลังจาก 5 วินาที
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
}

// แสดง/ซ่อน loading
function showLoading(show) {
    if (show) {
        $('body').append('<div id="loadingOverlay" class="loading-overlay"><div class="spinner-border text-primary" role="status"><span class="sr-only">กำลังโหลด...</span></div></div>');
    } else {
        $('#loadingOverlay').remove();
    }
}