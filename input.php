<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>รับวัสดุเข้าคลัง - ระบบงานผัสดุ</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include 'app/sidebar.php'; ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <?php include 'app/searchfull.php'; ?>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <?php include 'app/searchmini.php'; ?>

                        <!-- Nav Item - Alerts -->
                        <?php include 'app/alert.php'; ?>

                        <!-- Nav Item - Messages -->
                        <?php include 'app/message.php'; ?>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <?php include 'app/userinfo.php'; ?>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">รับวัสดุเข้าคลัง</h1>
                        <a href="index.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                            <i class="fas fa-arrow-left fa-sm text-white-50"></i> กลับหน้าหลัก
                        </a>
                    </div>

                    <!-- Form Content -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-plus-circle"></i> ฟอร์มรับวัสดุเข้าคลัง
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <form id="materialReceiptForm" method="POST" action="">
                                        
                                        <!-- ข้อมูลการรับวัสดุ -->
                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="receipt_date">วันที่รับวัสดุ <span class="text-danger">*</span></label>
                                                    <input type="date" class="form-control" id="receipt_date" name="receipt_date" 
                                                           value="<?php echo date('Y-m-d'); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="supplier">ผู้จำหน่าย/แหล่งที่มา</label>
                                                    <select class="form-control" id="supplier" name="supplier_id">
                                                        <option value="">-- เลือกผู้จำหน่าย --</option>
                                                        <option value="1">บริษัท นภาพรรณดีวลลภ์ จำกัด</option>
                                                        <option value="2">ยอดนยาม</option>
                                                        <option value="3">ร้านเครื่องเขียนจำกัด</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="invoice_number">เลขที่ใบแจ้งหนี้</label>
                                                    <input type="text" class="form-control" id="invoice_number" name="invoice_number" 
                                                           placeholder="เช่น INV-2025-001">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="received_by">ผู้รับวัสดุ <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="received_by" name="received_by" 
                                                           placeholder="ชื่อผู้รับวัสดุ" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label for="notes">หมายเหตุ</label>
                                            <textarea class="form-control" id="notes" name="notes" rows="3" 
                                                      placeholder="หมายเหตุเพิ่มเติม..."></textarea>
                                        </div>

                                        <hr>

                                        <!-- รายการวัสดุ -->
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h5 class="mb-0">รายการวัสดุที่รับ</h5>
                                            <button type="button" class="btn btn-success btn-sm" onclick="addMaterialRow()">
                                                <i class="fas fa-plus"></i> เพิ่มรายการ
                                            </button>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="materialTable">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th width="25%">ชื่อวัสดุ</th>
                                                        <th width="10%">รหัสวัสดุ</th>
                                                        <th width="10%">หน่วย</th>
                                                        <th width="15%">จำนวน</th>
                                                        <th width="15%">ราคา/หน่วย</th>
                                                        <th width="15%">ราคารวม</th>
                                                        <th width="10%">ลบ</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="materialTableBody">
                                                    <tr>
                                                        <td>
                                                            <input type="text" class="form-control" name="materials[0][name]" 
                                                                   placeholder="ชื่อวัสดุ" required>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="materials[0][code]" 
                                                                   placeholder="รหัสวัสดุ">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="materials[0][unit]" 
                                                                   placeholder="หน่วย" required>
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control quantity-input" 
                                                                   name="materials[0][quantity]" min="1" placeholder="จำนวน" required>
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control price-input" 
                                                                   name="materials[0][unit_price]" step="0.01" min="0" placeholder="ราคา">
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control total-price" 
                                                                   name="materials[0][total_price]" readonly>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr class="table-info">
                                                        <td colspan="5" class="text-right font-weight-bold">ยอดรวมทั้งหมด:</td>
                                                        <td>
                                                            <input type="number" class="form-control font-weight-bold" 
                                                                   id="grandTotal" name="total_amount" readonly>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>

                                        <!-- ปุ่มบันทึก -->
                                        <div class="text-center mt-4">
                                            <button type="submit" class="btn btn-primary btn-lg">
                                                <i class="fas fa-save"></i> บันทึกการรับวัสดุ
                                            </button>
                                            <a href="index.php" class="btn btn-secondary btn-lg ml-2">
                                                <i class="fas fa-arrow-left"></i> ยกเลิก
                                            </a>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include 'footer.php'; ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <?php include 'logout.php'; ?>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- JavaScript สำหรับฟอร์ม -->
    <script>
        // ตัวแปรนับแถว
        let rowCount = 1;

        // เพิ่มแถวใหม่
        function addMaterialRow() {
            const tbody = document.getElementById('materialTableBody');
            const newRow = `
                <tr>
                    <td>
                        <input type="text" class="form-control" name="materials[${rowCount}][name]" 
                               placeholder="ชื่อวัสดุ" required>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="materials[${rowCount}][code]" 
                               placeholder="รหัสวัสดุ">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="materials[${rowCount}][unit]" 
                               placeholder="หน่วย" required>
                    </td>
                    <td>
                        <input type="number" class="form-control quantity-input" 
                               name="materials[${rowCount}][quantity]" min="1" placeholder="จำนวน" required>
                    </td>
                    <td>
                        <input type="number" class="form-control price-input" 
                               name="materials[${rowCount}][unit_price]" step="0.01" min="0" placeholder="ราคา">
                    </td>
                    <td>
                        <input type="number" class="form-control total-price" 
                               name="materials[${rowCount}][total_price]" readonly>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            tbody.insertAdjacentHTML('beforeend', newRow);
            rowCount++;
        }

        // ลบแถว
        function removeRow(button) {
            const row = button.closest('tr');
            const tbody = document.getElementById('materialTableBody');
            
            if (tbody.children.length > 1) {
                row.remove();
                calculateGrandTotal();
            } else {
                alert('ต้องมีรายการวัสดุอย่างน้อย 1 รายการ');
            }
        }

        // คำนวณราคารวมของแต่ละแถว
        function calculateRowTotal(row) {
            const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const total = quantity * price;
            
            row.querySelector('.total-price').value = total.toFixed(2);
        }

        // คำนวณราคารวมทั้งหมด
        function calculateGrandTotal() {
            let grandTotal = 0;
            
            document.querySelectorAll('.total-price').forEach(function(input) {
                const value = parseFloat(input.value) || 0;
                grandTotal += value;
            });
            
            document.getElementById('grandTotal').value = grandTotal.toFixed(2);
        }

        // Event listeners สำหรับการคำนวณ
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('quantity-input') || e.target.classList.contains('price-input')) {
                const row = e.target.closest('tr');
                calculateRowTotal(row);
                calculateGrandTotal();
            }
        });

        // ตรวจสอบฟอร์มก่อนส่ง
        document.getElementById('materialReceiptForm').addEventListener('submit', function(e) {
            const materials = document.querySelectorAll('input[name*="[name]"]');
            let hasValidMaterial = false;
            
            materials.forEach(function(input) {
                if (input.value.trim() !== '') {
                    hasValidMaterial = true;
                }
            });
            
            if (!hasValidMaterial) {
                e.preventDefault();
                alert('กรุณาระบุรายการวัสดุอย่างน้อย 1 รายการ');
            }
        });
    </script>

</body>

</html>
